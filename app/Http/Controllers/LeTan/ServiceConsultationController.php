<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceConsultation;
use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceConsultationController extends Controller
{
    /**
     * Hiển thị danh sách tư vấn dịch vụ
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consultations = ServiceConsultation::with(['customer', 'service', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('le-tan.consultations.index', compact('consultations'));
    }

    /**
     * Hiển thị form tạo tư vấn dịch vụ mới
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        $services = Service::where('status', 'active')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $activePromotions = Promotion::where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();

        return view('le-tan.consultations.create', compact('customers', 'services', 'categories', 'activePromotions'));
    }

    /**
     * Lưu tư vấn dịch vụ mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'notes' => 'required|string',
            'recommended_date' => 'nullable|date',
        ]);

        $consultation = new ServiceConsultation();
        $consultation->customer_id = $request->customer_id;
        $consultation->service_id = $request->service_id;
        $consultation->notes = $request->notes;
        $consultation->recommended_date = $request->recommended_date;
        $consultation->created_by = Auth::id();
        $consultation->save();

        return redirect()->route('le-tan.consultations.index')
            ->with('success', 'Tư vấn dịch vụ đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết tư vấn dịch vụ
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $consultation = ServiceConsultation::with(['customer', 'service', 'createdBy'])
            ->findOrFail($id);

        return view('le-tan.consultations.show', compact('consultation'));
    }

    /**
     * Hiển thị form chỉnh sửa tư vấn dịch vụ
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $consultation = ServiceConsultation::findOrFail($id);
        $customers = Customer::all();
        $services = Service::where('status', 'active')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $activePromotions = Promotion::where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();

        return view('le-tan.consultations.edit', compact('consultation', 'customers', 'services', 'categories', 'activePromotions'));
    }

    /**
     * Cập nhật tư vấn dịch vụ
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'notes' => 'required|string',
            'recommended_date' => 'nullable|date',
        ]);

        $consultation = ServiceConsultation::findOrFail($id);
        $consultation->customer_id = $request->customer_id;
        $consultation->service_id = $request->service_id;
        $consultation->notes = $request->notes;
        $consultation->recommended_date = $request->recommended_date;
        $consultation->updated_by = Auth::id();
        $consultation->save();

        return redirect()->route('le-tan.consultations.index')
            ->with('success', 'Tư vấn dịch vụ đã được cập nhật thành công.');
    }

    /**
     * Xóa tư vấn dịch vụ
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $consultation = ServiceConsultation::findOrFail($id);
        $consultation->delete();

        return redirect()->route('le-tan.consultations.index')
            ->with('success', 'Tư vấn dịch vụ đã được xóa thành công.');
    }

    /**
     * Chuyển đổi tư vấn dịch vụ thành lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function convertToAppointment($id)
    {
        $consultation = ServiceConsultation::with(['customer', 'service'])
            ->findOrFail($id);

        return redirect()->route('le-tan.appointments.create', [
            'customer_id' => $consultation->customer_id,
            'service_id' => $consultation->service_id,
            'date' => $consultation->recommended_date,
            'consultation_id' => $consultation->id,
        ]);
    }
}
