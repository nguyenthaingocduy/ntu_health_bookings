<?php

namespace App\Http\Controllers\NVKT;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Hiển thị danh sách các buổi chăm sóc đã hoàn thành
     *
     * @return \Illuminate\Http\Response
     */
    public function completed()
    {
        $completedSessions = Appointment::with(['customer', 'service', 'timeSlot', 'timeAppointment'])
            ->where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('date_appointments', 'desc')
            ->paginate(10);

        // Convert string dates to Carbon objects
        foreach ($completedSessions as $session) {
            // Ensure date_appointments is a Carbon instance
            if (is_string($session->date_appointments)) {
                $session->date_appointments = \Carbon\Carbon::parse($session->date_appointments);
            }

            // Ensure check_in_time and check_out_time are Carbon instances if they exist
            if ($session->check_in_time && is_string($session->check_in_time)) {
                $session->check_in_time = \Carbon\Carbon::parse($session->check_in_time);
            }

            if ($session->check_out_time && is_string($session->check_out_time)) {
                $session->check_out_time = \Carbon\Carbon::parse($session->check_out_time);
            }
        }

        return view('nvkt.sessions.completed', compact('completedSessions'));
    }

    /**
     * Hiển thị chi tiết buổi chăm sóc
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = Appointment::with(['customer', 'service', 'timeAppointment', 'professionalNotes'])
            ->findOrFail($id);

        // Kiểm tra quyền truy cập - chỉ cho phép nhân viên được phân công hoặc admin
        if ($appointment->employee_id != Auth::id()) {
            return redirect()->route('nvkt.dashboard')
                ->with('error', 'Bạn không có quyền xem phiên làm việc này.');
        }

        return view('nvkt.sessions.show', compact('appointment'));
    }

    /**
     * Cập nhật trạng thái buổi chăm sóc
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $session = Appointment::where('employee_id', Auth::id())->findOrFail($id);
        $session->status = $request->status;
        $session->notes = $request->notes;
        $session->updated_by = Auth::id();
        $session->save();

        return redirect()->route('nvkt.sessions.show', $id)
            ->with('success', 'Trạng thái buổi chăm sóc đã được cập nhật thành công.');
    }
}
