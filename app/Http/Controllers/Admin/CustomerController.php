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
}
