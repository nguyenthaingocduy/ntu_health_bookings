<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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
        try {
            $reminders = Reminder::with(['appointment', 'appointment.customer', 'createdBy'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('le-tan.reminders.index', compact('reminders'));
        } catch (\Exception $e) {
            // Log lỗi
            Log::error('Error in ReminderController@index: ' . $e->getMessage());

            // Trả về view với danh sách rỗng
            $reminders = collect([]);
            return view('le-tan.reminders.index', compact('reminders'))
                ->with('error', 'Có lỗi xảy ra khi tải danh sách nhắc nhở: ' . $e->getMessage());
        }
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
            ->where('date_appointments', '>=', Carbon::today())
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

        try {
            $reminder = new Reminder();
            $reminder->appointment_id = $request->appointment_id;
            $reminder->reminder_date = $request->reminder_date;
            $reminder->message = $request->message;
            $reminder->reminder_type = $request->reminder_type;
            $reminder->status = 'pending';
            $reminder->created_by = Auth::id();
            $reminder->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }

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
        try {
            $reminder = Reminder::with(['appointment', 'appointment.customer', 'appointment.service', 'createdBy'])
                ->findOrFail($id);

            return view('le-tan.reminders.show', compact('reminder'));
        } catch (\Exception $e) {
            // Log lỗi
            Log::error('Error in ReminderController@show: ' . $e->getMessage());

            return redirect()->route('le-tan.reminders.index')
                ->with('error', 'Không tìm thấy nhắc nhở hoặc có lỗi xảy ra: ' . $e->getMessage());
        }
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
            ->where('date_appointments', '>=', Carbon::today())
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
        try {
            $reminder = Reminder::with(['appointment', 'appointment.customer', 'appointment.service'])
                ->findOrFail($id);

            $customer = $reminder->appointment->customer;
            $appointment = $reminder->appointment;

            // Gửi email nhắc lịch hẹn
            if ($reminder->reminder_type == 'email' || $reminder->reminder_type == 'both') {
                try {
                    Mail::to($customer->email)->send(new AppointmentReminder($appointment, $reminder->message));
                } catch (\Exception $e) {
                    Log::error('Error sending reminder email: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Có lỗi khi gửi email: ' . $e->getMessage());
                }
            }

            // Gửi SMS nhắc lịch hẹn (nếu có)
            if ($reminder->reminder_type == 'sms' || $reminder->reminder_type == 'both') {
                // Implement SMS sending logic here
                // Hiện tại chỉ log thông báo
                Log::info('SMS reminder would be sent to: ' . $customer->phone);
            }

            // Cập nhật trạng thái nhắc lịch hẹn
            $reminder->status = 'sent';
            $reminder->sent_at = Carbon::now();
            $reminder->save();

            return redirect()->route('le-tan.reminders.index')
                ->with('success', 'Nhắc lịch hẹn đã được gửi thành công.');
        } catch (\Exception $e) {
            Log::error('Error in sendReminder: ' . $e->getMessage());
            return redirect()->route('le-tan.reminders.index')
                ->with('error', 'Có lỗi xảy ra khi gửi nhắc nhở: ' . $e->getMessage());
        }
    }
}
