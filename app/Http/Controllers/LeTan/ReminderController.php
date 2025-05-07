<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentReminder;
use Carbon\Carbon;

class ReminderController extends Controller
{
    /**
     * Hiển thị danh sách nhắc lịch hẹn
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reminders = Reminder::with(['appointment', 'appointment.customer', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('le-tan.reminders.index', compact('reminders'));
    }

    /**
     * Hiển thị form tạo nhắc lịch hẹn mới
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $appointments = Appointment::with(['customer', 'service'])
            ->where('status', 'confirmed')
            ->where('appointment_date', '>=', Carbon::today())
            ->get();

        return view('le-tan.reminders.create', compact('appointments'));
    }

    /**
     * Lưu nhắc lịch hẹn mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'reminder_date' => 'required|date',
            'message' => 'required|string',
            'reminder_type' => 'required|in:email,sms,both',
        ]);

        $reminder = new Reminder();
        $reminder->appointment_id = $request->appointment_id;
        $reminder->reminder_date = $request->reminder_date;
        $reminder->message = $request->message;
        $reminder->reminder_type = $request->reminder_type;
        $reminder->status = 'pending';
        $reminder->created_by = Auth::id();
        $reminder->save();

        return redirect()->route('le-tan.reminders.index')
            ->with('success', 'Nhắc lịch hẹn đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết nhắc lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reminder = Reminder::with(['appointment', 'appointment.customer', 'appointment.service', 'createdBy'])
            ->findOrFail($id);

        return view('le-tan.reminders.show', compact('reminder'));
    }

    /**
     * Hiển thị form chỉnh sửa nhắc lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reminder = Reminder::findOrFail($id);
        $appointments = Appointment::with(['customer', 'service'])
            ->where('status', 'confirmed')
            ->where('appointment_date', '>=', Carbon::today())
            ->get();

        return view('le-tan.reminders.edit', compact('reminder', 'appointments'));
    }

    /**
     * Cập nhật nhắc lịch hẹn
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'reminder_date' => 'required|date',
            'message' => 'required|string',
            'reminder_type' => 'required|in:email,sms,both',
        ]);

        $reminder = Reminder::findOrFail($id);
        $reminder->appointment_id = $request->appointment_id;
        $reminder->reminder_date = $request->reminder_date;
        $reminder->message = $request->message;
        $reminder->reminder_type = $request->reminder_type;
        $reminder->save();

        return redirect()->route('le-tan.reminders.index')
            ->with('success', 'Nhắc lịch hẹn đã được cập nhật thành công.');
    }

    /**
     * Xóa nhắc lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->delete();

        return redirect()->route('le-tan.reminders.index')
            ->with('success', 'Nhắc lịch hẹn đã được xóa thành công.');
    }

    /**
     * Gửi nhắc lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendReminder($id)
    {
        $reminder = Reminder::with(['appointment', 'appointment.customer'])
            ->findOrFail($id);

        $customer = $reminder->appointment->customer;
        $appointment = $reminder->appointment;

        // Gửi email nhắc lịch hẹn
        if ($reminder->reminder_type == 'email' || $reminder->reminder_type == 'both') {
            Mail::to($customer->email)->send(new AppointmentReminder($appointment, $reminder->message));
        }

        // Gửi SMS nhắc lịch hẹn (nếu có)
        if ($reminder->reminder_type == 'sms' || $reminder->reminder_type == 'both') {
            // Implement SMS sending logic here
        }

        // Cập nhật trạng thái nhắc lịch hẹn
        $reminder->status = 'sent';
        $reminder->sent_at = Carbon::now();
        $reminder->save();

        return redirect()->route('le-tan.reminders.index')
            ->with('success', 'Nhắc lịch hẹn đã được gửi thành công.');
    }
}
