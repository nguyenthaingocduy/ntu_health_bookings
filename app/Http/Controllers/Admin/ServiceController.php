<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use App\Models\Clinic;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index(Request $request)
    {
        $query = Service::with(['category', 'clinic']);

        // Áp dụng các bộ lọc nếu có
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('admin.services.index', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $categories = Category::with('children')
            ->where('status', 'active')
            ->get();
        $clinics = Clinic::where('status', 'active')->get();
        return view('admin.services.create', compact('categories', 'clinics'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'promotion' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'required|exists:categories,id',
            'clinic_id' => 'required|exists:clinics,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        // Xử lý slug
        $validated['slug'] = Str::slug($validated['name']);

        // Xử lý hình ảnh nếu có
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('services', $filename, 'public');
            $validated['image_url'] = '/storage/services/' . $filename;
        }

        Service::create($validated);
        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được tạo thành công');
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        $categories = Category::with('children')
            ->where('status', 'active')
            ->get();
        $clinics = Clinic::where('status', 'active')->get();
        return view('admin.services.edit', compact('service', 'categories', 'clinics'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'promotion' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'required|exists:categories,id',
            'clinic_id' => 'required|exists:clinics,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        // Cập nhật slug
        $validated['slug'] = Str::slug($validated['name']);

        // Xử lý hình ảnh mới nếu có
        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ nếu có
            if ($service->image_url && !str_contains($service->image_url, 'unsplash.com')) {
                $oldPath = str_replace('/storage/', '', $service->image_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Lưu hình ảnh mới
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('services', $filename, 'public');
            $validated['image_url'] = '/storage/services/' . $filename;
        }

        $service->update($validated);
        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được cập nhật thành công');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        // Xóa hình ảnh liên quan nếu có
        if ($service->image_url && !str_contains($service->image_url, 'unsplash.com')) {
            $path = str_replace('/storage/', '', $service->image_url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được xóa thành công');
    }

    /**
     * Toggle the status of the specified service.
     */
    public function toggleStatus(Service $service)
    {
        $service->status = $service->status === 'active' ? 'inactive' : 'active';
        $service->save();

        return redirect()->route('admin.services.index')
            ->with('success', 'Trạng thái dịch vụ đã được cập nhật thành công!');
    }
}
