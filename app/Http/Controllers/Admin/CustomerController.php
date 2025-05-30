<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {
        $query = User::whereHas('role', function($q) {
            $q->where('name', 'Customer');
        });

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified customer.
     */
    public function show($id)
    {
        $customer = User::findOrFail($id);

        // Check if user is a customer
        if (!$customer->hasRole('Customer')) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Người dùng này không phải là khách hàng.');
        }

        // Get customer's appointments
        $appointments = Appointment::where('customer_id', $customer->id)
            ->with(['service', 'employee', 'timeAppointment'])
            ->orderBy('date_appointments', 'desc')
            ->get();

        // Calculate statistics
        $statistics = [
            'total' => $appointments->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
        ];

        return view('admin.customers.show', compact('customer', 'appointments', 'statistics'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit($id)
    {
        $customer = User::with('role')->findOrFail($id);

        // Check if user is a customer
        if (!$customer->role || $customer->role->name !== 'Customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Người dùng này không phải là khách hàng.');
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, $id)
    {
        $customer = User::with('role')->findOrFail($id);

        // Check if user is a customer
        if (!$customer->role || $customer->role->name !== 'Customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Người dùng này không phải là khách hàng.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
        ]);

        $customer->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', 'Thông tin khách hàng đã được cập nhật thành công.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy($id)
    {
        $customer = User::with('role')->findOrFail($id);

        // Check if user is a customer
        if (!$customer->role || $customer->role->name !== 'Customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Người dùng này không phải là khách hàng.');
        }

        // Check if customer has any appointments
        $appointmentCount = $customer->appointments()->count();

        if ($appointmentCount > 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Không thể xóa khách hàng này vì đã có ' . $appointmentCount . ' lịch hẹn. Vui lòng xóa tất cả lịch hẹn trước.');
        }

        $customerName = $customer->first_name . ' ' . $customer->last_name;
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Khách hàng "' . $customerName . '" đã được xóa thành công.');
    }
}
