<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\ServiceConsultation;
use App\Models\Service;
use App\Models\Category;
use App\Models\User;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ConsultationController extends Controller
{
    /**
     * Hiển thị danh sách tư vấn dịch vụ
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Khởi tạo query builder
        $query = ServiceConsultation::with(['customer', 'service', 'createdBy']);

        // Áp dụng bộ lọc trạng thái nếu có
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Áp dụng tìm kiếm nếu có
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhereHas('service', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Sắp xếp theo ngày tạo mới nhất
        $query->orderBy('created_at', 'desc');

        // Lấy danh sách tư vấn và phân trang
        $consultations = $query->paginate(10);

        return view('le-tan.consultations.index', compact('consultations'));
    }

    /**
     * Hiển thị form tạo tư vấn dịch vụ mới
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->orderBy('first_name')->get();
        $categories = Category::with(['services', 'children.services'])->whereNull('parent_id')->get();
        $activePromotions = Promotion::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();

        return view('le-tan.consultations.create', compact('customers', 'categories', 'activePromotions'));
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
        $consultation->status = 'pending';
        $consultation->created_by = Auth::id();
        $consultation->updated_by = Auth::id();
        $consultation->save();

        return redirect()->route('le-tan.consultations.show', $consultation->id)
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
        $consultation = ServiceConsultation::with(['customer', 'service', 'service.category', 'createdBy', 'appointment'])
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

        // Chỉ cho phép chỉnh sửa tư vấn đang ở trạng thái chờ
        if ($consultation->status !== 'pending') {
            return redirect()->route('le-tan.consultations.show', $consultation->id)
                ->with('error', 'Không thể chỉnh sửa tư vấn đã được chuyển đổi hoặc hủy.');
        }

        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->orderBy('first_name')->get();
        $categories = Category::with(['services', 'children.services'])->whereNull('parent_id')->get();
        $activePromotions = Promotion::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();

        return view('le-tan.consultations.edit', compact('consultation', 'customers', 'categories', 'activePromotions'));
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

        // Chỉ cho phép chỉnh sửa tư vấn đang ở trạng thái chờ
        if ($consultation->status !== 'pending') {
            return redirect()->route('le-tan.consultations.show', $consultation->id)
                ->with('error', 'Không thể chỉnh sửa tư vấn đã được chuyển đổi hoặc hủy.');
        }

        $consultation->customer_id = $request->customer_id;
        $consultation->service_id = $request->service_id;
        $consultation->notes = $request->notes;
        $consultation->recommended_date = $request->recommended_date;
        $consultation->updated_by = Auth::id();
        $consultation->save();

        return redirect()->route('le-tan.consultations.show', $consultation->id)
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

        // Chỉ cho phép xóa tư vấn đang ở trạng thái chờ
        if ($consultation->status !== 'pending') {
            return redirect()->route('le-tan.consultations.show', $consultation->id)
                ->with('error', 'Không thể xóa tư vấn đã được chuyển đổi hoặc hủy.');
        }

        $consultation->delete();

        return redirect()->route('le-tan.consultations.index')
            ->with('success', 'Tư vấn dịch vụ đã được xóa thành công.');
    }

    /**
     * Hiển thị form chuyển đổi tư vấn thành lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function convert($id)
    {
        $consultation = ServiceConsultation::with(['customer', 'service'])->findOrFail($id);

        // Chỉ cho phép chuyển đổi tư vấn đang ở trạng thái chờ
        if ($consultation->status !== 'pending') {
            return redirect()->route('le-tan.consultations.show', $consultation->id)
                ->with('error', 'Không thể chuyển đổi tư vấn đã được chuyển đổi hoặc hủy.');
        }

        // Chuyển hướng đến trang đặt lịch hẹn với thông tin từ tư vấn
        return redirect()->route('le-tan.appointments.create', [
            'customer_id' => $consultation->customer_id,
            'service_id' => $consultation->service_id,
            'consultation_id' => $consultation->id,
            'appointment_date' => $consultation->recommended_date ? $consultation->recommended_date->format('Y-m-d') : null,
        ]);
    }
}
