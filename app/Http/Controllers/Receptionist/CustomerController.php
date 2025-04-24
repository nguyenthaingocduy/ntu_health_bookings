<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->paginate(10);
        
        return view('receptionist.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('receptionist.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birthday' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        ]);
        
        $customer = new Customer();
        $customer->id = Str::uuid();
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->gender = $request->gender;
        $customer->birthday = $request->birthday;
        $customer->created_by = Auth::id();
        $customer->save();
        
        return redirect()->route('receptionist.customers.index')
            ->with('success', 'Khách hàng đã được tạo thành công.');
    }

    /**
     * Display the specified customer.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::with(['appointments', 'appointments.service', 'appointments.timeSlot'])
            ->findOrFail($id);
        
        return view('receptionist.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        
        return view('receptionist.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birthday' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        ]);
        
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->gender = $request->gender;
        $customer->birthday = $request->birthday;
        $customer->updated_by = Auth::id();
        $customer->save();
        
        return redirect()->route('receptionist.customers.index')
            ->with('success', 'Thông tin khách hàng đã được cập nhật thành công.');
    }
}
