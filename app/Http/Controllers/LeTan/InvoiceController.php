<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class InvoiceController extends Controller
{
    /**
     * Hiển thị danh sách hóa đơn
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::with(['appointment', 'customer', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('le-tan.invoices.index', compact('invoices'));
    }

    /**
     * Hiển thị form tạo hóa đơn mới
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $appointments = Appointment::with(['customer', 'service', 'payment'])
            ->where('status', 'completed')
            ->whereHas('payment', function($query) {
                $query->where('payment_status', 'completed');
            })
            ->whereDoesntHave('invoice')
            ->get();

        return view('le-tan.invoices.create', compact('appointments'));
    }

    /**
     * Lưu hóa đơn mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'invoice_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::with(['service', 'payment', 'customer'])
            ->findOrFail($request->appointment_id);
        
        // Kiểm tra xem lịch hẹn đã có hóa đơn chưa
        if ($appointment->invoice) {
            return back()->with('error', 'Lịch hẹn này đã có hóa đơn.');
        }

        // Tạo mã hóa đơn
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

        $invoice = new Invoice();
        $invoice->appointment_id = $request->appointment_id;
        $invoice->customer_id = $appointment->customer_id;
        $invoice->invoice_number = $invoiceNumber;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->amount = $appointment->payment->amount;
        $invoice->notes = $request->notes;
        $invoice->created_by = Auth::id();
        $invoice->save();

        return redirect()->route('le-tan.invoices.show', $invoice->id)
            ->with('success', 'Hóa đơn đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết hóa đơn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::with(['appointment', 'appointment.service', 'customer', 'createdBy'])
            ->findOrFail($id);

        return view('le-tan.invoices.show', compact('invoice'));
    }

    /**
     * In hóa đơn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $invoice = Invoice::with(['appointment', 'appointment.service', 'customer', 'createdBy'])
            ->findOrFail($id);

        $pdf = PDF::loadView('le-tan.invoices.print', compact('invoice'));
        
        return $pdf->download('hoa-don-' . $invoice->invoice_number . '.pdf');
    }
}
