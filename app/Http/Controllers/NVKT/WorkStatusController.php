<?php

namespace App\Http\Controllers\NVKT;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkStatusController extends Controller
{
    /**
     * Hiển thị danh sách công việc cần cập nhật trạng thái
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
            }

            $pendingAppointments = Appointment::with(['customer', 'service', 'timeAppointment'])
                ->where('employee_id', $userId)
                ->whereIn('status', ['confirmed', 'pending'])
                ->orderBy('date_appointments')
                ->orderBy('time_appointments_id')
                ->paginate(10);

            $inProgressAppointments = Appointment::with(['customer', 'service', 'timeAppointment'])
                ->where('employee_id', $userId)
                ->where('status', 'in_progress')
                ->orderBy('date_appointments')
                ->orderBy('time_appointments_id')
                ->paginate(10);

            return view('nvkt.work-status.index', compact('pendingAppointments', 'inProgressAppointments'));
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái công việc
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
                'notes' => 'nullable|string',
            ]);

            $userId = Auth::id();
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
            }

            $appointment = Appointment::where('employee_id', $userId)->findOrFail($id);
            $oldStatus = $appointment->status;

            // Cập nhật trạng thái
            $appointment->status = $request->status;
            if ($request->has('notes')) {
                $appointment->notes = $request->notes;
            }

            // Cập nhật thời gian check-in và check-out
            if ($oldStatus != 'in_progress' && $request->status == 'in_progress') {
                $appointment->check_in_time = Carbon::now();
            }

            if ($oldStatus != 'completed' && $request->status == 'completed') {
                $appointment->check_out_time = Carbon::now();
                $appointment->is_completed = true;
            }

            $appointment->save();

            $statusText = [
                'in_progress' => 'đã bắt đầu',
                'completed' => 'đã hoàn thành',
                'cancelled' => 'đã hủy'
            ];

            $message = 'Lịch hẹn ' . ($statusText[$request->status] ?? 'đã được cập nhật') . ' thành công.';

            return redirect()->route('nvkt.work-status.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('nvkt.work-status.index')
                ->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $e->getMessage());
        }
    }
}
