<?php

namespace App\Http\Controllers\NVKT;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Hiển thị danh sách khách hàng
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = User::whereHas('role', function($query) {
                $query->where('name', 'Customer');
            })
            ->orderBy('first_name')
            ->paginate(10);

        return view('nvkt.customers.index', compact('customers'));
    }

    /**
     * Hiển thị chi tiết khách hàng
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = User::findOrFail($id);

        // Kiểm tra xem người dùng có phải là khách hàng không
        if (!$customer->hasRole('Customer')) {
            return redirect()->route('nvkt.customers.index')
                ->with('error', 'Người dùng không phải là khách hàng.');
        }

        return view('nvkt.customers.show', compact('customer'));
    }

    /**
     * Hiển thị lịch sử sử dụng dịch vụ của khách hàng
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function serviceHistory($id)
    {
        $customer = User::findOrFail($id);

        // Kiểm tra xem người dùng có phải là khách hàng không
        if (!$customer->hasRole('Customer')) {
            return redirect()->route('nvkt.customers.index')
                ->with('error', 'Người dùng không phải là khách hàng.');
        }

        $appointments = Appointment::with(['service', 'employee'])
            ->where('customer_id', $id)
            ->orderBy('date_appointments', 'desc')
            ->paginate(10);

        return view('nvkt.customers.service-history', compact('customer', 'appointments'));
    }
}
