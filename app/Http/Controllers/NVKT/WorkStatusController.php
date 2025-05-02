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
        $pendingAppointments = Appointment::with(['customer', 'service', 'timeSlot'])
            ->where('employee_id', Auth::id())
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('date_appointments')
            ->orderBy('time_slot_id')
            ->paginate(10);
            
        $inProgressAppointments = Appointment::with(['customer', 'service', 'timeSlot'])
            ->where('employee_id', Auth::id())
            ->where('status', 'in_progress')
            ->orderBy('date_appointments')
            ->orderBy('time_slot_id')
            ->paginate(10);
            
        return view('nvkt.work-status.index', compact('pendingAppointments', 'inProgressAppointments'));
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
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::where('employee_id', Auth::id())->findOrFail($id);
        $oldStatus = $appointment->status;
        $appointment->status = $request->status;
        $appointment->notes = $request->notes;
        
        // Cập nhật thời gian check-in và check-out
        if ($oldStatus != 'in_progress' && $request->status == 'in_progress') {
            $appointment->check_in_time = Carbon::now();
        }
        
        if ($oldStatus != 'completed' && $request->status == 'completed') {
            $appointment->check_out_time = Carbon::now();
            $appointment->is_completed = true;
        }
        
        $appointment->save();

        return redirect()->route('nvkt.work-status.index')
            ->with('success', 'Trạng thái công việc đã được cập nhật thành công.');
    }
}
