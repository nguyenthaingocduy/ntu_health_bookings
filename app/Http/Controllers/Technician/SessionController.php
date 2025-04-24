<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ProfessionalNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Display the specified session.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = Appointment::with(['customer', 'service', 'timeSlot'])
            ->where('employee_id', Auth::id())
            ->findOrFail($id);
        
        $professionalNote = ProfessionalNote::where('customer_id', $appointment->customer_id)
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();
        
        return view('technician.sessions.show', compact('appointment', 'professionalNote'));
    }

    /**
     * Update the specified session in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'progress' => 'required|integer|min:0|max:100',
            'session_notes' => 'nullable|string|max:500',
        ]);
        
        $appointment = Appointment::where('employee_id', Auth::id())->findOrFail($id);
        
        $appointment->status = $request->status;
        $appointment->progress = $request->progress;
        $appointment->session_notes = $request->session_notes;
        $appointment->updated_by = Auth::id();
        $appointment->save();
        
        return redirect()->back()->with('success', 'Trạng thái buổi chăm sóc đã được cập nhật thành công.');
    }

    /**
     * Display a listing of the completed sessions.
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
        
        return view('technician.sessions.completed', compact('completedSessions'));
    }
}
