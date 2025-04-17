<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->where('status', 'active')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('status', 'active')
            ->where('id', '!=', $category->id)
            ->get();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Prevent category from being its own parent
        if (!empty($validated['parent_id']) && $validated['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'Danh mục không thể là danh mục cha của chính nó'])->withInput();
        }

        // Prevent circular references
        if (!empty($validated['parent_id'])) {
            $parentCategory = Category::find($validated['parent_id']);
            if ($parentCategory && $parentCategory->parent_id == $category->id) {
                return back()->withErrors(['parent_id' => 'Không thể tạo vòng lặp giữa danh mục cha và con'])->withInput();
            }
        }

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has services
        if ($category->services()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục này vì có dịch vụ đang sử dụng');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa thành công');
    }

    /**
     * Toggle the status of the specified category.
     */
    public function toggleStatus(Category $category)
    {
        $category->status = $category->status === 'active' ? 'inactive' : 'active';
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Trạng thái danh mục đã được cập nhật thành công!');
    }
}
