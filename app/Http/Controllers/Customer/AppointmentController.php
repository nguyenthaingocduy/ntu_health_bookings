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

                // Gửi email bằng cả ba phương thức để đảm bảo thành công
                $emailSent = false;
                $userEmail = Auth::user()->email;

                // Phương thức 1: Sử dụng EmailService mới
                try {
                    $emailService = new EmailService();
                    $result = $emailService->sendAppointmentConfirmation($appointmentWithRelations);

                    if ($result) {
                        Log::info('Đã gửi email xác nhận đặt lịch thành công (new service) cho: ' . $userEmail);
                        $emailSent = true;
                    }
                } catch (\Exception $newServiceError) {
                    Log::warning('Không thể gửi email qua service mới: ' . $newServiceError->getMessage());
                }

                // Phương thức 2: Sử dụng EmailNotificationService cũ (nếu phương thức 1 thất bại)
                if (!$emailSent) {
                    try {
                        $legacyEmailService = new EmailNotificationService();
                        $notification = $legacyEmailService->sendBookingConfirmation($appointmentWithRelations);

                        if ($notification && $notification->status === 'sent') {
                            Log::info('Đã gửi email xác nhận đặt lịch thành công (legacy service) cho: ' . $userEmail);
                            $emailSent = true;
                        }
                    } catch (\Exception $legacyServiceError) {
                        Log::warning('Không thể gửi email qua service cũ: ' . $legacyServiceError->getMessage());
                    }
                }

                // Phương thức 3: Sử dụng Mail facade trực tiếp (nếu cả hai phương thức trước đều thất bại)
                if (!$emailSent) {
                    try {
                        Mail::to($userEmail)
                            ->send(new \App\Mail\AppointmentConfirmationMail($appointmentWithRelations));
                        Log::info('Đã gửi email xác nhận đặt lịch thành công (direct) cho: ' . $userEmail);
                        $emailSent = true;
                    } catch (\Exception $directMailError) {
                        Log::error('Không thể gửi email trực tiếp: ' . $directMailError->getMessage());
                    }
                }

                // Ghi log kết quả cuối cùng
                if (!$emailSent) {
                    Log::error('Không thể gửi email xác nhận đặt lịch cho: ' . $userEmail . ' bằng cả ba phương thức');
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
