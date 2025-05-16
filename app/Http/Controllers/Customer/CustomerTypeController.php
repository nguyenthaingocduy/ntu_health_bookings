<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerTypeController extends Controller
{
    /**
     * Hiển thị trang thông tin về các loại khách hàng
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lấy tất cả các loại khách hàng đang kích hoạt
        $customerTypes = CustomerType::where('is_active', true)
            ->orderBy('priority_level', 'desc')
            ->get();
        
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();
        
        // Lấy tổng chi tiêu của người dùng
        $totalSpending = $user->appointments()
            ->where('status', 'completed')
            ->sum('final_price');
        
        return view('customer.customer_types.index', compact('customerTypes', 'user', 'totalSpending'));
    }
    
    /**
     * Hiển thị thông tin chi tiết về một loại khách hàng cụ thể
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Lấy thông tin loại khách hàng
        $customerType = CustomerType::findOrFail($id);
        
        // Kiểm tra xem loại khách hàng có đang kích hoạt không
        if (!$customerType->is_active) {
            return redirect()->route('customer.customer-types.index')
                ->with('error', 'Loại khách hàng này hiện không khả dụng.');
        }
        
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();
        
        // Lấy tổng chi tiêu của người dùng
        $totalSpending = $user->appointments()
            ->where('status', 'completed')
            ->sum('final_price');
        
        // Tính toán số tiền cần chi tiêu thêm để đạt được loại khách hàng này
        $remainingSpending = max(0, $customerType->min_spending - $totalSpending);
        
        return view('customer.customer_types.show', compact('customerType', 'user', 'totalSpending', 'remainingSpending'));
    }
}
