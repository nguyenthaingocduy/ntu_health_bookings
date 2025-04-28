<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PromotionController extends Controller
{
    /**
     * Display a listing of the promotions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotions = Promotion::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new promotion.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::where('status', 'active')->get();

        return view('admin.promotions.create', compact('services'));
    }

    /**
     * Store a newly created promotion in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:promotions,code',
            'description' => 'nullable|string|max:1000',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $promotion = Promotion::create([
                'id' => Str::uuid(),
                'title' => $request->title,
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'minimum_purchase' => $request->minimum_purchase ?? 0,
                'maximum_discount' => $request->maximum_discount,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? true,
                'usage_limit' => $request->usage_limit,
                'usage_count' => 0,
                'created_by' => Auth::id(),
            ]);

            // Liên kết với các dịch vụ nếu có
            if ($request->has('services')) {
                $promotion->services()->attach($request->services);
            }

            return redirect()->route('admin.promotions.show', $promotion->id)
                ->with('success', 'Khuyến mãi đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi tạo khuyến mãi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified promotion.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $promotion = Promotion::with('creator')->findOrFail($id);

        return view('admin.promotions.show', compact('promotion'));
    }

    /**
     * Show the form for editing the specified promotion.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $services = Service::where('status', 'active')->get();

        return view('admin.promotions.edit', compact('promotion', 'services'));
    }

    /**
     * Update the specified promotion in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'description' => 'nullable|string|max:1000',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'usage_limit' => 'nullable|integer|min:' . $promotion->usage_count,
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $promotion->update([
                'title' => $request->title,
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'minimum_purchase' => $request->minimum_purchase ?? 0,
                'maximum_discount' => $request->maximum_discount,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? true,
                'usage_limit' => $request->usage_limit,
            ]);

            // Cập nhật liên kết với các dịch vụ
            if ($request->has('services')) {
                $promotion->services()->sync($request->services);
            } else {
                $promotion->services()->detach();
            }

            return redirect()->route('admin.promotions.show', $promotion->id)
                ->with('success', 'Khuyến mãi đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật khuyến mãi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified promotion from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);

        try {
            $promotion->delete();

            return redirect()->route('admin.promotions.index')
                ->with('success', 'Khuyến mãi đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xóa khuyến mãi: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the active status of the specified promotion.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleActive($id)
    {
        $promotion = Promotion::findOrFail($id);

        try {
            $promotion->update([
                'is_active' => !$promotion->is_active,
            ]);

            return redirect()->route('admin.promotions.show', $promotion->id)
                ->with('success', 'Trạng thái kích hoạt của khuyến mãi đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật trạng thái kích hoạt của khuyến mãi: ' . $e->getMessage());
        }
    }

    /**
     * Reset the usage count of the specified promotion.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function resetUsageCount($id)
    {
        $promotion = Promotion::findOrFail($id);

        try {
            $promotion->update([
                'usage_count' => 0,
            ]);

            return redirect()->route('admin.promotions.show', $promotion->id)
                ->with('success', 'Số lần sử dụng của khuyến mãi đã được đặt lại thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi đặt lại số lần sử dụng của khuyến mãi: ' . $e->getMessage());
        }
    }

    /**
     * Validate a promotion code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Hiển thị danh sách dịch vụ được áp dụng khuyến mãi
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function services($id)
    {
        $promotion = Promotion::with('services')->findOrFail($id);
        $allServices = Service::where('status', 'active')->get();

        return view('admin.promotions.services', compact('promotion', 'allServices'));
    }

    /**
     * Cập nhật danh sách dịch vụ được áp dụng khuyến mãi
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function updateServices(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Cập nhật liên kết với các dịch vụ
            if ($request->has('services')) {
                $promotion->services()->sync($request->services);
            } else {
                $promotion->services()->detach();
            }

            return redirect()->route('admin.promotions.services', $promotion->id)
                ->with('success', 'Danh sách dịch vụ áp dụng khuyến mãi đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật danh sách dịch vụ: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function validateCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $code = strtoupper($request->code);
        $amount = $request->amount;

        $promotion = Promotion::where('code', $code)->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại.',
            ]);
        }

        if (!$promotion->is_valid) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn.',
            ]);
        }

        if ($amount < $promotion->minimum_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Giá trị đơn hàng không đủ để áp dụng mã khuyến mãi này. Tối thiểu: ' . number_format($promotion->minimum_purchase, 0, ',', '.') . ' VNĐ',
            ]);
        }

        $discount = $promotion->calculateDiscount($amount);

        return response()->json([
            'success' => true,
            'message' => 'Mã khuyến mãi hợp lệ.',
            'data' => [
                'promotion' => $promotion,
                'discount' => $discount,
                'formatted_discount' => number_format($discount, 0, ',', '.') . ' VNĐ',
            ],
        ]);
    }
}
