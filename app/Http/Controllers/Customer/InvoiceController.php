<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Hiển thị danh sách hóa đơn của khách hàng
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $invoices = Invoice::with(['appointment', 'appointment.service'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('customer.invoices.index', compact('invoices'));
        } catch (\Exception $e) {
            Log::error('Error in Customer InvoiceController@index: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('customer.dashboard')
                ->with('error', 'Đã xảy ra lỗi khi tải danh sách hóa đơn. Vui lòng thử lại sau.');
        }
    }

    /**
     * Hiển thị chi tiết hóa đơn
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $invoice = Invoice::with(['appointment', 'appointment.service', 'creator'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            return view('customer.invoices.show', compact('invoice'));
        } catch (\Exception $e) {
            Log::error('Error in Customer InvoiceController@show: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'invoice_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('customer.invoices.index')
                ->with('error', 'Không tìm thấy hóa đơn hoặc bạn không có quyền xem hóa đơn này.');
        }
    }

    /**
     * Tải xuống hóa đơn dưới dạng PDF
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        try {
            $invoice = Invoice::with(['appointment', 'appointment.service', 'creator'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            $companyInfo = [
                'name' => \App\Models\Setting::get('site_name', 'NTU Health Booking'),
                'address' => \App\Models\Setting::get('address', '02 Nguyễn Đình Chiểu, Nha Trang, Khánh Hòa'),
                'phone' => \App\Models\Setting::get('contact_phone', '(0258) 2471303'),
                'email' => \App\Models\Setting::get('contact_email', 'ntuhealthbooking@gmail.com'),
                'tax_id' => \App\Models\Setting::get('tax_id', ''),
            ];

            $pdf = Pdf::loadView('customer.invoices.pdf', compact('invoice', 'companyInfo'));

            return $pdf->download('hoa-don-' . $invoice->invoice_number . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error in Customer InvoiceController@download: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'invoice_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('customer.invoices.index')
                ->with('error', 'Không thể tải xuống hóa đơn. Vui lòng thử lại sau.');
        }
    }
}
