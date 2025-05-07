<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('le-tan.customers.index', compact('customers'));
    }

    /**
     * Hiển thị form tạo khách hàng mới
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('le-tan.customers.create');
    }

    /**
     * Lưu khách hàng mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female,other',
        ]);

        $customerRole = Role::where('name', 'Customer')->first();
        if (!$customerRole) {
            return back()->with('error', 'Vai trò khách hàng không tồn tại trong hệ thống.');
        }

        $customer = new User();
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->password = Hash::make($request->password);
        $customer->address = $request->address;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->gender = $request->gender;
        $customer->role_id = $customerRole->id;
        $customer->created_by = Auth::id();
        $customer->save();

        return redirect()->route('le-tan.customers.index')
            ->with('success', 'Khách hàng đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết khách hàng
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = User::with(['appointments' => function($query) {
                $query->orderBy('date_appointments', 'desc');
            }])
            ->findOrFail($id);

        return view('le-tan.customers.show', compact('customer'));
    }

    /**
     * Hiển thị form chỉnh sửa khách hàng
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = User::findOrFail($id);
        return view('le-tan.customers.edit', compact('customer'));
    }

    /**
     * Cập nhật khách hàng
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female,other',
        ]);

        $customer = User::findOrFail($id);
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->gender = $request->gender;
        $customer->updated_by = Auth::id();
        $customer->save();

        return redirect()->route('le-tan.customers.index')
            ->with('success', 'Thông tin khách hàng đã được cập nhật thành công.');
    }
}
