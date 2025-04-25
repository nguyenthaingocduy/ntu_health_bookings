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
        $completedSessions = Appointment::with(['customer', 'service', 'timeSlot'])
            ->where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
            
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
        $session = Appointment::with(['customer', 'service', 'timeSlot', 'professionalNotes'])
            ->where('employee_id', Auth::id())
            ->findOrFail($id);
            
        return view('nvkt.sessions.show', compact('session'));
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
