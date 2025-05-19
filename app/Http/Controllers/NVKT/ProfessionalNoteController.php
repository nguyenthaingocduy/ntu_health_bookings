<?php

namespace App\Http\Controllers\NVKT;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ProfessionalNote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessionalNoteController extends Controller
{
    /**
     * Hiển thị danh sách ghi chú chuyên môn
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = ProfessionalNote::with(['customer', 'service', 'appointment', 'appointment.service'])
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Xử lý dịch vụ cho mỗi ghi chú nếu không có dịch vụ trực tiếp
        foreach ($notes as $note) {
            if (!$note->service && $note->appointment && $note->appointment->service) {
                $note->service = $note->appointment->service;
            }
        }

        return view('nvkt.notes.index', compact('notes'));
    }

    /**
     * Hiển thị form tạo ghi chú chuyên môn mới
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = User::whereHas('role', function($query) {
                $query->where('name', 'Customer');
            })
            ->orderBy('first_name')
            ->get();

        $appointments = Appointment::with(['customer', 'service'])
            ->where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('date_appointments', 'desc')
            ->get();

        $services = \App\Models\Service::orderBy('name')->get();

        return view('nvkt.notes.create', compact('customers', 'appointments', 'services'));
    }

    /**
     * Lưu ghi chú chuyên môn mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'service_id' => 'nullable|exists:services,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note = new ProfessionalNote();
        $note->customer_id = $request->customer_id;
        $note->appointment_id = $request->appointment_id;
        $note->service_id = $request->service_id; // Lưu service_id
        $note->title = $request->title;
        $note->content = $request->content;
        $note->created_by = Auth::id();
        $note->save();

        return redirect()->route('nvkt.notes.index')
            ->with('success', 'Ghi chú chuyên môn đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết ghi chú chuyên môn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $note = ProfessionalNote::with(['customer', 'service', 'appointment', 'appointment.service'])
                ->where('created_by', Auth::id())
                ->findOrFail($id);

            // Nếu không có dịch vụ trực tiếp, thử lấy từ appointment
            if (!$note->service && $note->appointment && $note->appointment->service) {
                $note->service = $note->appointment->service;
            }

            return view('nvkt.notes.show', compact('note'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error showing professional note: ' . $e->getMessage());
            return redirect()->route('nvkt.notes.index')
                ->with('error', 'Không thể hiển thị ghi chú. Vui lòng thử lại sau.');
        }
    }

    /**
     * Hiển thị form chỉnh sửa ghi chú chuyên môn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $note = ProfessionalNote::where('created_by', Auth::id())->findOrFail($id);

        $customers = User::whereHas('role', function($query) {
                $query->where('name', 'Customer');
            })
            ->orderBy('first_name')
            ->get();

        $appointments = Appointment::with(['customer', 'service'])
            ->where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('date_appointments', 'desc')
            ->get();

        $services = \App\Models\Service::orderBy('name')->get();

        return view('nvkt.notes.edit', compact('note', 'customers', 'appointments', 'services'));
    }

    /**
     * Cập nhật ghi chú chuyên môn
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'service_id' => 'nullable|exists:services,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note = ProfessionalNote::where('created_by', Auth::id())->findOrFail($id);
        $note->customer_id = $request->customer_id;
        $note->appointment_id = $request->appointment_id;
        $note->service_id = $request->service_id; // Cập nhật service_id
        $note->title = $request->title;
        $note->content = $request->content;
        $note->save();

        return redirect()->route('nvkt.notes.index')
            ->with('success', 'Ghi chú chuyên môn đã được cập nhật thành công.');
    }

    /**
     * Xóa ghi chú chuyên môn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = ProfessionalNote::where('created_by', Auth::id())->findOrFail($id);
        $note->delete();

        return redirect()->route('nvkt.notes.index')
            ->with('success', 'Ghi chú chuyên môn đã được xóa thành công.');
    }
}
