<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::with(['customer', 'service', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('receptionist.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::orderBy('full_name')->get();
        $services = Service::where('status', 'active')->orderBy('name')->get();
        $appointments = Appointment::where('status', 'completed')
            ->whereNull('payment_id')
            ->with(['customer', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->get();
        
        return view('receptionist.payments.create', compact('customers', 'services', 'appointments'));
    }

    /**
     * Store a newly created payment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer,other',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $payment = new Payment();
        $payment->id = Str::uuid();
        $payment->customer_id = $request->customer_id;
        $payment->service_id = $request->service_id;
        $payment->appointment_id = $request->appointment_id;
        $payment->amount = $request->amount;
        $payment->payment_method = $request->payment_method;
        $payment->payment_date = $request->payment_date;
        $payment->notes = $request->notes;
        $payment->status = 'completed';
        $payment->created_by = Auth::id();
        $payment->save();
        
        // Update appointment if provided
        if ($request->appointment_id) {
            $appointment = Appointment::find($request->appointment_id);
            if ($appointment) {
                $appointment->payment_id = $payment->id;
                $appointment->payment_status = 'paid';
                $appointment->save();
            }
        }
        
        return redirect()->route('receptionist.payments.index')
            ->with('success', 'Thanh toán đã được ghi nhận thành công.');
    }

    /**
     * Display the specified payment.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::with(['customer', 'service', 'appointment'])->findOrFail($id);
        
        return view('receptionist.payments.show', compact('payment'));
    }
}
