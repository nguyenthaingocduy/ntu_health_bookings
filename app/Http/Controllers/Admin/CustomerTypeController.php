<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomerTypeController extends Controller
{
    /**
     * Display a listing of the customer types.
     */
    public function index()
    {
        $customerTypes = CustomerType::withCount('users')->get();

        return view('admin.customer_types.index', compact('customerTypes'));
    }

    /**
     * Show the form for creating a new customer type.
     */
    public function create()
    {
        return view('admin.customer_types.create');
    }

    /**
     * Store a newly created customer type in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log request data
        \Illuminate\Support\Facades\Log::info('CustomerType store request data:', $request->all());

        try {
            $validated = $request->validate([
                'type_name' => 'required|string|max:50|unique:customer_types',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'priority_level' => 'required|integer|min:0',
                'min_spending' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'color_code' => 'required|string|max:20',
                'is_active' => 'nullable|boolean',
                'has_priority_booking' => 'nullable|boolean',
                'has_personal_consultant' => 'nullable|boolean',
                'has_birthday_gift' => 'nullable|boolean',
                'has_free_service' => 'nullable|boolean',
                'free_service_count' => 'nullable|integer|min:0',
                'has_extended_warranty' => 'nullable|boolean',
                'extended_warranty_days' => 'nullable|integer|min:0',
            ]);

            $validated['id'] = Str::uuid();
            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['has_priority_booking'] = $request->has('has_priority_booking') ? true : false;
            $validated['has_personal_consultant'] = $request->has('has_personal_consultant') ? true : false;
            $validated['has_birthday_gift'] = $request->has('has_birthday_gift') ? true : false;
            $validated['has_free_service'] = $request->has('has_free_service') ? true : false;
            $validated['has_extended_warranty'] = $request->has('has_extended_warranty') ? true : false;

            // Debug: Log validated data
            \Illuminate\Support\Facades\Log::info('CustomerType validated data:', $validated);

            $customerType = CustomerType::create($validated);

            // Debug: Log created customer type
            \Illuminate\Support\Facades\Log::info('CustomerType created:', $customerType->toArray());

            return redirect()->route('admin.customer-types.index')
                ->with('success', 'Loại khách hàng đã được tạo thành công.');
        } catch (\Exception $e) {
            // Debug: Log error
            \Illuminate\Support\Facades\Log::error('Error creating customer type: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Error trace: ' . $e->getTraceAsString());

            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified customer type.
     */
    public function edit(CustomerType $customerType)
    {
        return view('admin.customer_types.edit', compact('customerType'));
    }

    /**
     * Update the specified customer type in storage.
     */
    public function update(Request $request, CustomerType $customerType)
    {
        try {
            // Kiểm tra xem tên loại khách hàng đã thay đổi chưa
            $nameChanged = $request->type_name !== $customerType->type_name;

            // Nếu tên đã thay đổi, kiểm tra xem tên mới đã tồn tại chưa
            if ($nameChanged) {
                $existingType = CustomerType::where('type_name', $request->type_name)
                    ->where('id', '!=', $customerType->id)
                    ->first();

                if ($existingType) {
                    return back()->withInput()->with('error', 'Tên loại khách hàng đã được sử dụng.');
                }
            }

            // Xác thực các trường khác
            $validated = $request->validate([
                'type_name' => 'required|string|max:50',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'priority_level' => 'required|integer|min:0',
                'min_spending' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'color_code' => 'required|string|max:20',
                'is_active' => 'nullable|boolean',
                'has_priority_booking' => 'nullable|boolean',
                'has_personal_consultant' => 'nullable|boolean',
                'has_birthday_gift' => 'nullable|boolean',
                'has_free_service' => 'nullable|boolean',
                'free_service_count' => 'nullable|integer|min:0',
                'has_extended_warranty' => 'nullable|boolean',
                'extended_warranty_days' => 'nullable|integer|min:0',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['has_priority_booking'] = $request->has('has_priority_booking') ? true : false;
            $validated['has_personal_consultant'] = $request->has('has_personal_consultant') ? true : false;
            $validated['has_birthday_gift'] = $request->has('has_birthday_gift') ? true : false;
            $validated['has_free_service'] = $request->has('has_free_service') ? true : false;
            $validated['has_extended_warranty'] = $request->has('has_extended_warranty') ? true : false;

            // Debug: Log validated data
            \Illuminate\Support\Facades\Log::info('CustomerType update validated data:', $validated);

            $customerType->update($validated);

            // Debug: Log updated customer type
            \Illuminate\Support\Facades\Log::info('CustomerType updated:', $customerType->toArray());

            return redirect()->route('admin.customer-types.index')
                ->with('success', 'Loại khách hàng đã được cập nhật thành công.');
        } catch (\Exception $e) {
            // Debug: Log error
            \Illuminate\Support\Facades\Log::error('Error updating customer type: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Error trace: ' . $e->getTraceAsString());

            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified customer type from storage.
     */
    public function destroy(CustomerType $customerType)
    {
        // Kiểm tra xem có khách hàng nào đang sử dụng loại này không
        $usersCount = User::where('type_id', $customerType->id)->count();

        if ($usersCount > 0) {
            return back()->with('error', 'Không thể xóa loại khách hàng này vì đang có ' . $usersCount . ' khách hàng thuộc loại này.');
        }

        $customerType->delete();

        return redirect()->route('admin.customer-types.index')
            ->with('success', 'Loại khách hàng đã được xóa thành công.');
    }

    /**
     * Toggle the status of the specified customer type.
     */
    public function toggleStatus(CustomerType $customerType)
    {
        $customerType->is_active = !$customerType->is_active;
        $customerType->save();

        $status = $customerType->is_active ? 'kích hoạt' : 'vô hiệu hóa';

        return redirect()->route('admin.customer-types.index')
            ->with('success', "Loại khách hàng {$customerType->type_name} đã được {$status}.");
    }
}
