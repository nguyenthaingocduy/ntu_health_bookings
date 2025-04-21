<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\EmailNotificationService;
use App\Services\EmailService;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['service', 'employee', 'timeAppointment'])
            ->where('customer_id', Auth::id())
            ->orderBy('date_appointments', 'desc')
            ->paginate(10);

        return view('customer.appointments.index', compact('appointments'));
    }

    public function create($serviceId = null)
    {
        $service = null;
        if ($serviceId) {
            $service = Service::findOrFail($serviceId);
        }

        $services = Service::where('status', 'active')
            ->get();

        // Get times with booking counts
        $times = Time::orderBy('started_time')->get();

        return view('customer.appointments.create', compact('services', 'times', 'service'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date_appointments' => 'required|date|after_or_equal:today',
            'time_appointments_id' => 'required|exists:times,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Kiểm tra xem thời gian đã đầy chưa
        $timeSlot = Time::findOrFail($request->time_appointments_id);

        if ($timeSlot->isFull()) {
            return back()->withInput()->with('error', 'Thời gian này đã đầy. Vui lòng chọn thời gian khác.');
        }

        try {
            // Kiểm tra dịch vụ tồn tại
            Service::findOrFail($request->service_id);

            // Tìm một employee có role là nhân viên
            $employee = \App\Models\User::whereHas('role', function($query) {
                $query->where('name', 'employee');
            })->first();

            // Nếu không tìm thấy employee nào, lấy user ID đầu tiên
            $employeeId = $employee ? $employee->id : \App\Models\User::first()->id;

            // Tạo UUID cho id
            $uuid = Str::uuid()->toString();

            // Bắt đầu transaction để đảm bảo tính nhất quán dữ liệu
            DB::beginTransaction();

            try {
                // Tăng số lượng đặt chỗ cho khung giờ này
                $timeSlot->increment('booked_count');

                $appointment = Appointment::create([
                    'id' => $uuid,
                    'customer_id' => Auth::id(),
                    'service_id' => $request->service_id,
                    'date_register' => now(),
                    'status' => 'pending',
                    'date_appointments' => $request->date_appointments,
                    'time_appointments_id' => $request->time_appointments_id,
                    'employee_id' => $employeeId, // Gán nhân viên tạm thời
                    'notes' => $request->notes,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            // Gửi email xác nhận đặt lịch
            try {
                Log::info('Bắt đầu gửi email xác nhận đặt lịch cho: ' . Auth::user()->email);

                // Load appointment with relations
                $appointmentWithRelations = Appointment::with(['service', 'customer', 'timeAppointment'])
                    ->findOrFail($appointment->id);

                $userEmail = Auth::user()->email;

                // Kiểm tra cấu hình email
                Log::info('Cấu hình email:', [
                    'driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'username' => config('mail.mailers.smtp.username'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                ]);

                // Phương thức 1: Sử dụng Mail facade trực tiếp với log driver
                try {
                    // Tạo nội dung email
                    $emailData = [
                        'appointment' => $appointmentWithRelations,
                        'user_name' => $appointmentWithRelations->customer->first_name . ' ' . $appointmentWithRelations->customer->last_name,
                        'service_name' => $appointmentWithRelations->service->name,
                        'appointment_date' => $appointmentWithRelations->date_appointments,
                        'appointment_time' => $appointmentWithRelations->timeAppointment->time,
                        'appointment_url' => route('customer.appointments.show', $appointmentWithRelations->id),
                        'app_name' => config('app.name'),
                        'current_year' => date('Y'),
                    ];

                    Mail::send('emails.appointment-confirmation', $emailData, function ($message) use ($userEmail) {
                        $message->to($userEmail)
                            ->subject('Xác nhận đặt lịch - Beauty Spa Booking');
                    });

                    Log::info('Đã gửi email xác nhận đặt lịch thành công cho: ' . $userEmail);

                    // Lưu thông tin email vào bảng email_logs
                    try {
                        \App\Models\EmailLog::create([
                            'to' => $userEmail,
                            'subject' => 'Xác nhận đặt lịch - Beauty Spa Booking',
                            'template' => 'emails.appointment-confirmation',
                            'data' => json_encode($emailData),
                            'status' => 'sent',
                            'attempts' => 1,
                            'sent_at' => now(),
                        ]);
                    } catch (\Exception $logError) {
                        Log::warning('Không thể lưu log email: ' . $logError->getMessage());
                    }

                } catch (\Exception $mailError) {
                    Log::error('Không thể gửi email: ' . $mailError->getMessage());

                    // Phương thức 2: Sử dụng EmailService nếu phương thức 1 thất bại
                    try {
                        $emailService = new EmailService();
                        $result = $emailService->sendAppointmentConfirmation($appointmentWithRelations);

                        if ($result) {
                            Log::info('Đã gửi email xác nhận đặt lịch thành công (fallback) cho: ' . $userEmail);
                        } else {
                            Log::error('Không thể gửi email xác nhận đặt lịch (fallback): ' . $userEmail);
                        }
                    } catch (\Exception $serviceError) {
                        Log::error('Không thể gửi email qua service: ' . $serviceError->getMessage());
                    }
                }
            } catch (\Exception $e) {
                Log::error('Không thể gửi email xác nhận đặt lịch: ' . $e->getMessage());
            }

            return redirect()->route('customer.appointments.index')
                ->with('success', 'Đặt lịch thành công. Vui lòng chờ xác nhận từ nhân viên.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $appointment = Appointment::with(['service', 'employee', 'customer', 'timeAppointment'])
            ->where('customer_id', Auth::id())
            ->findOrFail($id);

        return view('customer.appointments.show', compact('appointment'));
    }

    public function cancel($id)
    {
        $appointment = Appointment::where('customer_id', Auth::id())
            ->findOrFail($id);

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Không thể hủy lịch hẹn này.');
        }

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Giảm số lượng đặt chỗ cho khung giờ này
            $timeSlot = Time::findOrFail($appointment->time_appointments_id);
            if ($timeSlot->booked_count > 0) {
                $timeSlot->decrement('booked_count');
            }

            $appointment->update(['status' => 'cancelled']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi hủy lịch hẹn: ' . $e->getMessage());
        }

        return redirect()->route('customer.appointments.index')
            ->with('success', 'Hủy lịch hẹn thành công.');
    }
}
