<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::with(['user', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.invoices.index', compact('invoices'));
    }
    
    /**
     * Show the form for creating a new invoice.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->get();
        
        $services = Service::where('status', 'active')->get();
        $appointments = Appointment::whereIn('status', ['confirmed', 'completed'])
            ->whereDoesntHave('invoice')
            ->get();
        
        $taxRate = Setting::get('tax_rate', 10);
        
        return view('admin.invoices.create', compact('customers', 'services', 'appointments', 'taxRate'));
    }
    
    /**
     * Store a newly created invoice in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card',
            'payment_status' => 'required|in:pending,paid,cancelled,refunded',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'nullable|exists:services,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Calculate totals
            $subtotal = 0;
            $items = $request->items;
            
            foreach ($items as &$item) {
                $item['total'] = $item['quantity'] * $item['unit_price'];
                
                if (isset($item['discount']) && $item['discount'] > 0) {
                    $item['total'] -= $item['discount'];
                } else {
                    $item['discount'] = 0;
                }
                
                $subtotal += $item['total'];
            }
            
            $taxRate = Setting::get('tax_rate', 10);
            $tax = $subtotal * ($taxRate / 100);
            $discount = $request->discount ?? 0;
            $total = $subtotal + $tax - $discount;
            
            // Generate invoice number
            $latestInvoice = Invoice::orderBy('created_at', 'desc')->first();
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . sprintf('%04d', $latestInvoice ? (intval(substr($latestInvoice->invoice_number, -4)) + 1) : 1);
            
            // Create invoice
            $invoice = Invoice::create([
                'id' => Str::uuid(),
                'invoice_number' => $invoiceNumber,
                'user_id' => $request->user_id,
                'appointment_id' => $request->appointment_id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);
            
            // Create invoice items
            foreach ($items as $item) {
                InvoiceItem::create([
                    'id' => Str::uuid(),
                    'invoice_id' => $invoice->id,
                    'service_id' => $item['service_id'] ?? null,
                    'item_name' => $item['item_name'],
                    'item_description' => $item['item_description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'],
                    'total' => $item['total'],
                ]);
            }
            
            // Update appointment status if needed
            if ($request->appointment_id && $request->payment_status === 'paid') {
                $appointment = Appointment::find($request->appointment_id);
                if ($appointment && $appointment->status !== 'completed') {
                    $appointment->update(['status' => 'completed']);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', 'Hóa đơn đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi tạo hóa đơn: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the specified invoice.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::with(['user', 'creator', 'items.service', 'appointment'])
            ->findOrFail($id);
        
        $companyInfo = [
            'name' => Setting::get('site_name', 'NTU Health Booking'),
            'address' => Setting::get('address', '02 Nguyễn Đình Chiểu, Nha Trang, Khánh Hòa'),
            'phone' => Setting::get('contact_phone', '(0258) 2471303'),
            'email' => Setting::get('contact_email', 'ntuhealthbooking@gmail.com'),
            'tax_id' => Setting::get('tax_id', ''),
        ];
        
        return view('admin.invoices.show', compact('invoice', 'companyInfo'));
    }
    
    /**
     * Show the form for editing the specified invoice.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::with(['user', 'items.service', 'appointment'])
            ->findOrFail($id);
        
        // Only allow editing of pending invoices
        if ($invoice->payment_status !== 'pending') {
            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('error', 'Chỉ có thể chỉnh sửa hóa đơn đang chờ thanh toán.');
        }
        
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->get();
        
        $services = Service::where('status', 'active')->get();
        
        $appointments = Appointment::whereIn('status', ['confirmed', 'completed'])
            ->where(function($query) use ($invoice) {
                $query->whereDoesntHave('invoice')
                    ->orWhere('id', $invoice->appointment_id);
            })
            ->get();
        
        $taxRate = Setting::get('tax_rate', 10);
        
        return view('admin.invoices.edit', compact('invoice', 'customers', 'services', 'appointments', 'taxRate'));
    }
    
    /**
     * Update the specified invoice in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Only allow editing of pending invoices
        if ($invoice->payment_status !== 'pending') {
            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('error', 'Chỉ có thể chỉnh sửa hóa đơn đang chờ thanh toán.');
        }
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card',
            'payment_status' => 'required|in:pending,paid,cancelled,refunded',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:invoice_items,id',
            'items.*.service_id' => 'nullable|exists:services,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Calculate totals
            $subtotal = 0;
            $items = $request->items;
            $existingItemIds = [];
            
            foreach ($items as &$item) {
                $item['total'] = $item['quantity'] * $item['unit_price'];
                
                if (isset($item['discount']) && $item['discount'] > 0) {
                    $item['total'] -= $item['discount'];
                } else {
                    $item['discount'] = 0;
                }
                
                $subtotal += $item['total'];
                
                if (isset($item['id'])) {
                    $existingItemIds[] = $item['id'];
                }
            }
            
            $taxRate = Setting::get('tax_rate', 10);
            $tax = $subtotal * ($taxRate / 100);
            $discount = $request->discount ?? 0;
            $total = $subtotal + $tax - $discount;
            
            // Update invoice
            $invoice->update([
                'user_id' => $request->user_id,
                'appointment_id' => $request->appointment_id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'notes' => $request->notes,
            ]);
            
            // Delete removed items
            $invoice->items()->whereNotIn('id', $existingItemIds)->delete();
            
            // Update or create invoice items
            foreach ($items as $item) {
                if (isset($item['id'])) {
                    // Update existing item
                    InvoiceItem::where('id', $item['id'])->update([
                        'service_id' => $item['service_id'] ?? null,
                        'item_name' => $item['item_name'],
                        'item_description' => $item['item_description'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'discount' => $item['discount'],
                        'total' => $item['total'],
                    ]);
                } else {
                    // Create new item
                    InvoiceItem::create([
                        'id' => Str::uuid(),
                        'invoice_id' => $invoice->id,
                        'service_id' => $item['service_id'] ?? null,
                        'item_name' => $item['item_name'],
                        'item_description' => $item['item_description'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'discount' => $item['discount'],
                        'total' => $item['total'],
                    ]);
                }
            }
            
            // Update appointment status if needed
            if ($request->appointment_id && $request->payment_status === 'paid') {
                $appointment = Appointment::find($request->appointment_id);
                if ($appointment && $appointment->status !== 'completed') {
                    $appointment->update(['status' => 'completed']);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', 'Hóa đơn đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật hóa đơn: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Remove the specified invoice from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Only allow deleting of pending invoices
        if ($invoice->payment_status !== 'pending') {
            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('error', 'Chỉ có thể xóa hóa đơn đang chờ thanh toán.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete invoice items
            $invoice->items()->delete();
            
            // Delete invoice
            $invoice->delete();
            
            DB::commit();
            
            return redirect()->route('admin.invoices.index')
                ->with('success', 'Hóa đơn đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xóa hóa đơn: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate PDF for the specified invoice.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function generatePdf($id)
    {
        $invoice = Invoice::with(['user', 'creator', 'items.service', 'appointment'])
            ->findOrFail($id);
        
        $companyInfo = [
            'name' => Setting::get('site_name', 'NTU Health Booking'),
            'address' => Setting::get('address', '02 Nguyễn Đình Chiểu, Nha Trang, Khánh Hòa'),
            'phone' => Setting::get('contact_phone', '(0258) 2471303'),
            'email' => Setting::get('contact_email', 'ntuhealthbooking@gmail.com'),
            'tax_id' => Setting::get('tax_id', ''),
        ];
        
        $pdf = PDF::loadView('admin.invoices.pdf', compact('invoice', 'companyInfo'));
        
        return $pdf->download('hoa-don-' . $invoice->invoice_number . '.pdf');
    }
    
    /**
     * Update the payment status of the specified invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,paid,cancelled,refunded',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            $invoice->update([
                'payment_status' => $request->payment_status,
            ]);
            
            // Update appointment status if needed
            if ($invoice->appointment_id && $request->payment_status === 'paid') {
                $appointment = Appointment::find($invoice->appointment_id);
                if ($appointment && $appointment->status !== 'completed') {
                    $appointment->update(['status' => 'completed']);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', 'Trạng thái hóa đơn đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật trạng thái hóa đơn: ' . $e->getMessage());
        }
    }
    
    /**
     * Display a listing of the invoices for reporting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        $startDate = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : date('Y-m-01');
        $endDate = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : date('Y-m-d');
        
        $invoices = Invoice::with(['user', 'creator'])
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalRevenue = $invoices->sum('total');
        $totalTax = $invoices->sum('tax');
        $totalDiscount = $invoices->sum('discount');
        
        $dailyRevenue = $invoices->groupBy(function($invoice) {
            return $invoice->created_at->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('total');
        });
        
        $serviceRevenue = InvoiceItem::whereHas('invoice', function($query) use ($startDate, $endDate) {
            $query->where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        })
        ->whereNotNull('service_id')
        ->with('service')
        ->get()
        ->groupBy('service_id')
        ->map(function($group) {
            return [
                'service_name' => $group->first()->service->name,
                'total' => $group->sum('total'),
                'count' => $group->sum('quantity'),
            ];
        })
        ->sortByDesc('total')
        ->values();
        
        return view('admin.invoices.report', compact(
            'invoices',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalTax',
            'totalDiscount',
            'dailyRevenue',
            'serviceRevenue'
        ));
    }
}
