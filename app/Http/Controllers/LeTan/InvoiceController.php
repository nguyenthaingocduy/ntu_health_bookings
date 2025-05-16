<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Hiển thị danh sách hóa đơn
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::with(['appointment', 'user', 'creator'])
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
        try {
            $appointments = Appointment::with(['customer', 'service', 'payment'])
                ->where('status', 'completed')
                ->whereHas('payment', function($query) {
                    $query->where('payment_status', 'completed');
                })
                ->whereDoesntHave('invoice')
                ->get();

            return view('le-tan.invoices.create', compact('appointments'));
        } catch (\Exception $e) {
            Log::error('Error in InvoiceController@create: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
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
        $existingInvoice = Invoice::where('appointment_id', $appointment->id)->first();
        if ($existingInvoice) {
            return back()->with('error', 'Lịch hẹn này đã có hóa đơn.');
        }

        // Tạo mã hóa đơn
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

        // Lấy số tiền từ payment
        $amount = $appointment->payment ? $appointment->payment->amount : 0;

        $invoice = new Invoice();
        $invoice->appointment_id = $request->appointment_id;
        $invoice->user_id = $appointment->customer_id; // Sử dụng user_id thay vì customer_id
        $invoice->invoice_number = $invoiceNumber;
        $invoice->subtotal = $amount; // Sử dụng subtotal thay vì amount
        $invoice->tax = 0;
        $invoice->discount = 0;
        $invoice->total = $amount; // Sử dụng total thay vì amount
        $invoice->payment_method = 'cash';
        $invoice->payment_status = 'paid';
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
        $invoice = Invoice::with(['appointment', 'appointment.service', 'user', 'creator'])
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
        $invoice = Invoice::with(['appointment', 'appointment.service', 'user', 'creator'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('le-tan.invoices.print', compact('invoice'));

        return $pdf->download('hoa-don-' . $invoice->invoice_number . '.pdf');
    }
}
