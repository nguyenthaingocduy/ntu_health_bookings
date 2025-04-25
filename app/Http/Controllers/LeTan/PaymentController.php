<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Hiển thị danh sách thanh toán
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::with(['appointment', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('le-tan.payments.index', compact('payments'));
    }

    /**
     * Hiển thị form tạo thanh toán mới
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $appointments = Appointment::with(['customer', 'service'])
            ->where('status', 'completed')
            ->whereDoesntHave('payment')
            ->get();
            
        return view('le-tan.payments.create', compact('appointments'));
    }

    /**
     * Lưu thanh toán mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer',
            'payment_status' => 'required|in:pending,completed,failed',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        
        // Kiểm tra xem lịch hẹn đã có thanh toán chưa
        if ($appointment->payment) {
            return back()->with('error', 'Lịch hẹn này đã có thanh toán.');
        }

        $payment = new Payment();
        $payment->appointment_id = $request->appointment_id;
        $payment->customer_id = $appointment->customer_id;
        $payment->amount = $request->amount;
        $payment->payment_method = $request->payment_method;
        $payment->payment_status = $request->payment_status;
        $payment->notes = $request->notes;
        $payment->created_by = Auth::id();
        $payment->save();

        return redirect()->route('le-tan.payments.index')
            ->with('success', 'Thanh toán đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết thanh toán
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::with(['appointment', 'customer', 'appointment.service'])
            ->findOrFail($id);
            
        return view('le-tan.payments.show', compact('payment'));
    }
}
