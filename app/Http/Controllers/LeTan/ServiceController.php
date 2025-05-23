<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceController extends Controller
{
    /**
     * Hiển thị danh sách dịch vụ
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Khởi tạo query builder
        $query = Service::with('category');

        // Áp dụng bộ lọc danh mục nếu có
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Áp dụng tìm kiếm nếu có
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Áp dụng sắp xếp nếu có
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                default:
                    $query->orderBy('name', 'asc');
                    break;
            }
        } else {
            // Mặc định sắp xếp theo tên
            $query->orderBy('name', 'asc');
        }

        // Lấy danh sách dịch vụ và phân trang
        $services = $query->paginate(12);

        // Lấy danh sách danh mục
        $categories = Category::withCount('services')->get();

        // Lấy danh sách khuyến mãi đang hoạt động
        $activePromotions = Promotion::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();

        return view('le-tan.services.index', compact('services', 'categories', 'activePromotions'));
    }

    /**
     * Hiển thị chi tiết dịch vụ
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::with(['category', 'promotions' => function($query) {
            $query->where('is_active', true)
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now());
        }])->findOrFail($id);

        // Lấy các dịch vụ liên quan (cùng danh mục)
        $relatedServices = Service::where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->limit(4)
            ->get();

        return view('le-tan.services.show', compact('service', 'relatedServices'));
    }

    /**
     * Hiển thị form tạo dịch vụ mới
     */
    public function create()
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('services', 'create')) {
            abort(403, 'Bạn không có quyền tạo dịch vụ');
        }

        $categories = Category::orderBy('name')->get();

        return view('le-tan.services.create', compact('categories'));
    }

    /**
     * Lưu dịch vụ mới
     */
    public function store(Request $request)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('services', 'create')) {
            abort(403, 'Bạn không có quyền tạo dịch vụ');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean'
        ]);

        Service::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'category_id' => $request->category_id,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('le-tan.services.index')
            ->with('success', 'Dịch vụ đã được tạo thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa dịch vụ
     */
    public function edit($id)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('services', 'edit')) {
            abort(403, 'Bạn không có quyền sửa dịch vụ');
        }

        $service = Service::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('le-tan.services.edit', compact('service', 'categories'));
    }

    /**
     * Cập nhật dịch vụ
     */
    public function update(Request $request, $id)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('services', 'edit')) {
            abort(403, 'Bạn không có quyền sửa dịch vụ');
        }

        $service = Service::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean'
        ]);

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'category_id' => $request->category_id,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('le-tan.services.index')
            ->with('success', 'Dịch vụ đã được cập nhật thành công!');
    }

    /**
     * Xóa dịch vụ
     */
    public function destroy($id)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('services', 'delete')) {
            abort(403, 'Bạn không có quyền xóa dịch vụ');
        }

        $service = Service::findOrFail($id);

        // Kiểm tra xem dịch vụ có đang được sử dụng không
        if ($service->appointments()->exists()) {
            return redirect()->route('le-tan.services.index')
                ->with('error', 'Không thể xóa dịch vụ đã có lịch hẹn!');
        }

        $service->delete();

        return redirect()->route('le-tan.services.index')
            ->with('success', 'Dịch vụ đã được xóa thành công!');
    }
}
