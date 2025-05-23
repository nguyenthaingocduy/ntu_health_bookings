<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    /**
     * Hiển thị danh sách khuyến mãi
     */
    public function index()
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('promotions', 'view')) {
            abort(403, 'Bạn không có quyền xem khuyến mãi');
        }

        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(10);

        return view('le-tan.promotions.index', compact('promotions'));
    }

    /**
     * Hiển thị form tạo khuyến mãi mới
     */
    public function create()
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('promotions', 'create')) {
            abort(403, 'Bạn không có quyền tạo khuyến mãi');
        }

        return view('le-tan.promotions.create');
    }

    /**
     * Lưu khuyến mãi mới
     */
    public function store(Request $request)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('promotions', 'create')) {
            abort(403, 'Bạn không có quyền tạo khuyến mãi');
        }

        $request->validate([
            'code' => 'required|string|max:50|unique:promotions',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500'
        ]);

        Promotion::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'usage_limit' => $request->usage_limit,
            'usage_count' => 0,
            'is_active' => true,
            'description' => $request->description,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('le-tan.promotions.index')
            ->with('success', 'Khuyến mãi đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết khuyến mãi
     */
    public function show($id)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('promotions', 'view')) {
            abort(403, 'Bạn không có quyền xem khuyến mãi');
        }

        $promotion = Promotion::findOrFail($id);

        return view('le-tan.promotions.show', compact('promotion'));
    }

    /**
     * Hiển thị form chỉnh sửa khuyến mãi
     */
    public function edit($id)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('promotions', 'edit')) {
            abort(403, 'Bạn không có quyền sửa khuyến mãi');
        }

        $promotion = Promotion::findOrFail($id);

        return view('le-tan.promotions.edit', compact('promotion'));
    }

    /**
     * Cập nhật khuyến mãi
     */
    public function update(Request $request, $id)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('promotions', 'edit')) {
            abort(403, 'Bạn không có quyền sửa khuyến mãi');
        }

        $promotion = Promotion::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code,' . $id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $promotion->update([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'usage_limit' => $request->usage_limit,
            'is_active' => $request->has('is_active'),
            'description' => $request->description
        ]);

        return redirect()->route('le-tan.promotions.index')
            ->with('success', 'Khuyến mãi đã được cập nhật thành công!');
    }

    /**
     * Xóa khuyến mãi
     */
    public function destroy($id)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasAnyPermission('promotions', 'delete')) {
            abort(403, 'Bạn không có quyền xóa khuyến mãi');
        }

        $promotion = Promotion::findOrFail($id);

        // Kiểm tra xem khuyến mãi có đang được sử dụng không
        if ($promotion->usage_count > 0) {
            return redirect()->route('le-tan.promotions.index')
                ->with('error', 'Không thể xóa khuyến mãi đã được sử dụng!');
        }

        $promotion->delete();

        return redirect()->route('le-tan.promotions.index')
            ->with('success', 'Khuyến mãi đã được xóa thành công!');
    }
}
