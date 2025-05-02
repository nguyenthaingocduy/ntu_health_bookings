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

use App\Services\EmailService;
use App\Helpers\UrlHelper;

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

    public function create(Request $request, $serviceId = null)
    {
        $selectedService = null;
        if ($serviceId) {
            $selectedService = Service::findOrFail($serviceId);
        }

        $services = Service::where('status', 'active')
            ->get();

        // Get times with booking counts
        $times = Time::orderBy('started_time')->get();

        // Lấy mã khuyến mãi bổ sung (nếu có)
        $promotionCode = $request->input('promotion_code');

        return view('customer.appointments.create', compact('services', 'times', 'selectedService', 'promotionCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date_appointments' => 'required|date|after_or_equal:today',
            'time_appointments_id' => 'required|exists:times,id',
            'notes' => 'nullable|string|max:500',
            'promotion_code' => 'nullable|string|max:50',
        ]);

        // Kiểm tra xem thời gian đã đầy chưa
        $timeSlot = Time::findOrFail($request->time_appointments_id);

        if ($timeSlot->isFull()) {
            return back()->withInput()->with('error', 'Thời gian này đã đầy. Vui lòng chọn thời gian khác.');
        }

        // Kiểm tra xem khách hàng đã đặt dịch vụ khác trong cùng ngày và khung giờ chưa
        $existingAppointment = Appointment::where('customer_id', Auth::id())
            ->where('date_appointments', $request->date_appointments)
            ->where('time_appointments_id', $request->time_appointments_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingAppointment) {
            return back()->withInput()->with('error', 'Bạn đã đặt dịch vụ trong khung giờ này. Mỗi khách hàng chỉ có thể đặt tối đa 1 dịch vụ trong một khung giờ.');
        }

        try {
            // Kiểm tra dịch vụ tồn tại
            Service::findOrFail($request->service_id);

            // Tìm nhân viên kỹ thuật phù hợp cho dịch vụ và khung giờ này
            $employee = $this->findAvailableEmployee($request->service_id, $request->date_appointments, $request->time_appointments_id);

            // Nếu không tìm thấy employee, thử tìm nhân viên mặc định
            if (!$employee) {
                Log::warning('Không tìm thấy nhân viên phù hợp, đang tìm nhân viên mặc định');

                // Thử tìm nhân viên với nhiều role khác nhau
                $defaultEmployee = \App\Models\User::whereHas('role', function($query) {
                    $query->whereIn('name', ['employee', 'Employee', 'Technician', 'technician', 'Staff', 'staff']);
                })->first();

                if (!$defaultEmployee) {
                    Log::warning('Không tìm thấy nhân viên mặc định, đang tạo nhân viên mặc định mới');

                    // Tìm role nhân viên
                    $employeeRole = \App\Models\Role::where('name', 'Employee')
                        ->orWhere('name', 'employee')
                        ->orWhere('name', 'Technician')
                        ->orWhere('name', 'Staff')
                        ->first();

                    if (!$employeeRole) {
                        // Nếu không có role nhân viên, tạo mới
                        $employeeRole = \App\Models\Role::create([
                            'id' => \Illuminate\Support\Str::uuid()->toString(),
                            'name' => 'Employee',
                            'description' => 'Nhân viên kỹ thuật'
                        ]);

                        Log::info('Đã tạo role nhân viên mới: ' . $employeeRole->id);
                    }

                    // Tạo nhân viên mặc định
                    $defaultEmployee = \App\Models\User::create([
                        'id' => \Illuminate\Support\Str::uuid()->toString(),
                        'first_name' => 'Nhân viên',
                        'last_name' => 'Mặc định',
                        'email' => 'employee@example.com',
                        'password' => bcrypt('password'),
                        'role_id' => $employeeRole->id,
                        'status' => 'active'
                    ]);

                    Log::info('Đã tạo nhân viên mặc định: ' . $defaultEmployee->id);
                }
            }

            // Sử dụng nhân viên tìm thấy hoặc nhân viên mặc định
            $employeeId = $employee ? $employee->id : ($defaultEmployee ? $defaultEmployee->id : null);

            // Tạo UUID cho id
            $uuid = Str::uuid()->toString();

            // Bắt đầu transaction để đảm bảo tính nhất quán dữ liệu
            DB::beginTransaction();

            try {
                // Tăng số lượng đặt chỗ cho khung giờ này
                $timeSlot->increment('booked_count');

                // Lấy mã khuyến mãi (nếu có)
                $promotionCode = $request->promotion_code;

                // Lấy thông tin dịch vụ
                $service = Service::findOrFail($request->service_id);

                // Tính giá sau khuyến mãi
                $originalPrice = $service->price;
                $finalPrice = $originalPrice;

                if (!empty($promotionCode)) {
                    $finalPrice = $service->calculatePriceWithPromotion($promotionCode);
                } else if ($service->hasActivePromotion()) {
                    // Nếu không có mã khuyến mãi nhưng dịch vụ có khuyến mãi
                    $finalPrice = $service->getDiscountedPriceAttribute();
                }

                // Log thông tin đặt lịch
                \Illuminate\Support\Facades\Log::info('Thông tin đặt lịch', [
                    'customer_id' => Auth::id(),
                    'service_id' => $request->service_id,
                    'date_appointments' => $request->date_appointments,
                    'time_appointments_id' => $request->time_appointments_id,
                    'promotion_code' => $promotionCode,
                    'original_price' => $originalPrice,
                    'final_price' => $finalPrice,
                    'discount_amount' => $originalPrice - $finalPrice,
                    'discount_percent' => $originalPrice > 0 ? round(($originalPrice - $finalPrice) / $originalPrice * 100, 2) : 0
                ]);

                // Kiểm tra xem có nhân viên không
                if ($employeeId === null) {
                    throw new \Exception('Không tìm thấy nhân viên phù hợp. Vui lòng thử lại sau hoặc liên hệ với quản trị viên.');
                }

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
                    'promotion_code' => $promotionCode, // Lưu mã khuyến mãi bổ sung
                    'final_price' => $finalPrice, // Lưu giá sau khuyến mãi
                ]);

                // Cập nhật số lượng sử dụng mã khuyến mãi nếu có
                if (!empty($promotionCode)) {
                    $promotion = \App\Models\Promotion::where('code', strtoupper($promotionCode))
                        ->where('is_active', true)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();

                    if ($promotion) {
                        // Tăng số lượng sử dụng lên 1
                        $promotion->increment('usage_count');

                        // Log để debug
                        \Illuminate\Support\Facades\Log::info('Đã cập nhật số lượng sử dụng mã khuyến mãi', [
                            'promotion_id' => $promotion->id,
                            'promotion_code' => $promotion->code,
                            'old_usage_count' => $promotion->usage_count - 1,
                            'new_usage_count' => $promotion->usage_count
                        ]);
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            // Gửi email xác nhận đặt lịch
            try {
                Log::info('Bắt đầu gửi email xác nhận đặt lịch cho: ' . Auth::user()->email);

                // Load appointment with relations
                $appointmentWithRelations = Appointment::with(['service', 'customer', 'timeAppointment', 'employee'])
                    ->find($appointment->id);

                if (!$appointmentWithRelations) {
                    throw new \Exception('Không tìm thấy thông tin lịch hẹn.');
                }

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
                        'user_name' => $appointmentWithRelations->customer ? $appointmentWithRelations->customer->first_name . ' ' . $appointmentWithRelations->customer->last_name : 'Khách hàng',
                        'service_name' => $appointmentWithRelations->service ? $appointmentWithRelations->service->name : 'Dịch vụ',
                        'appointment_date' => $appointmentWithRelations->date_appointments,
                        'appointment_time' => $appointmentWithRelations->timeAppointment ? $appointmentWithRelations->timeAppointment->time : 'Chưa xác định',
                        'employee_name' => $appointmentWithRelations->employee ? $appointmentWithRelations->employee->first_name . ' ' . $appointmentWithRelations->employee->last_name : 'Chưa xác định',
                        'appointment_url' => UrlHelper::emailUrl('customer.appointments.show', $appointmentWithRelations->id),
                        'dashboard_url' => UrlHelper::customerDashboardUrl(),
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

            // Lấy thông tin nhân viên phục vụ
            $employeeName = '';
            if ($appointment && $appointment->employee) {
                $employeeName = $appointment->employee->first_name . ' ' . $appointment->employee->last_name;
            }

            return redirect()->route('customer.appointments.index')
                ->with('success', 'Đặt lịch thành công. ' .
                    ($employeeName ? "Bạn sẽ được phục vụ bởi nhân viên $employeeName. " : '') .
                    'Vui lòng chờ xác nhận từ nhân viên.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $appointment = Appointment::with(['service', 'employee', 'customer', 'timeAppointment'])
            ->where('customer_id', Auth::id())
            ->findOrFail($id);

        // Đảm bảo giá sau khuyến mãi được tính đúng
        if ($appointment->service) {
            // Tính lại giá sau khuyến mãi
            $originalPrice = $appointment->service->price;

            // Nếu có mã khuyến mãi, tính giá với mã khuyến mãi
            if ($appointment->promotion_code) {
                $finalPrice = $appointment->service->calculatePriceWithPromotion($appointment->promotion_code);

                // Lấy thông tin khuyến mãi
                $promotion = \App\Models\Promotion::where('code', $appointment->promotion_code)
                    ->where('is_active', true)
                    ->first();

                if ($promotion) {
                    // Tính phần trăm giảm giá
                    $discountPercent = $promotion->discount_type == 'percentage'
                        ? $promotion->discount_value
                        : round(($promotion->discount_value / $originalPrice) * 100);

                    // Lưu phần trăm giảm giá vào appointment
                    $appointment->direct_discount_percent = $discountPercent;
                }

                // Tính số tiền giảm
                $discountAmount = $originalPrice - $finalPrice;
                $appointment->discount_amount = $discountAmount;
            } else {
                // Nếu không có mã khuyến mãi, kiểm tra xem dịch vụ có khuyến mãi không
                if ($appointment->service->hasActivePromotion()) {
                    $finalPrice = $appointment->service->getDiscountedPriceAttribute();
                } else {
                    $finalPrice = $originalPrice;
                }
            }

            // Cập nhật giá sau khuyến mãi vào cơ sở dữ liệu
            if ($finalPrice != $appointment->final_price) {
                try {
                    $appointment->final_price = $finalPrice;
                    $appointment->save();

                    // Log để debug
                    \Illuminate\Support\Facades\Log::info('Đã cập nhật giá sau khuyến mãi trong show()', [
                        'appointment_id' => $appointment->id,
                        'original_price' => $originalPrice,
                        'final_price' => $finalPrice,
                        'promotion_code' => $appointment->promotion_code,
                        'direct_discount_percent' => $appointment->direct_discount_percent,
                        'discount_amount' => $appointment->discount_amount
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Không thể cập nhật giá sau khuyến mãi: ' . $e->getMessage());
                }
            }
        }

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

            // Giảm số lượng sử dụng mã khuyến mãi nếu có
            if (!empty($appointment->promotion_code)) {
                $promotion = \App\Models\Promotion::where('code', strtoupper($appointment->promotion_code))
                    ->first();

                if ($promotion && $promotion->usage_count > 0) {
                    // Giảm số lượng sử dụng đi 1
                    $promotion->decrement('usage_count');

                    // Log để debug
                    \Illuminate\Support\Facades\Log::info('Đã giảm số lượng sử dụng mã khuyến mãi khi hủy lịch', [
                        'promotion_id' => $promotion->id,
                        'promotion_code' => $promotion->code,
                        'old_usage_count' => $promotion->usage_count + 1,
                        'new_usage_count' => $promotion->usage_count
                    ]);
                }
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

    /**
     * Xóa lịch hẹn khỏi lịch sử
     */
    public function destroy($id)
    {
        $appointment = Appointment::where('customer_id', Auth::id())
            ->findOrFail($id);

        // Chỉ cho phép xóa lịch hẹn đã hoàn thành, đã hủy, hoặc đã tồn tại lâu (ví dụ: 30 ngày)
        $canDelete = in_array($appointment->status, ['completed', 'cancelled']);
        $isOld = $appointment->created_at->diffInDays(now()) >= 30;

        if (!$canDelete && !$isOld) {
            return back()->with('error', 'Chỉ có thể xóa lịch hẹn đã hoàn thành, đã hủy, hoặc đã tồn tại lâu hơn 30 ngày.');
        }

        try {
            // Ghi log trước khi xóa
            Log::info('Xóa lịch hẹn khỏi lịch sử', [
                'appointment_id' => $appointment->id,
                'customer_id' => Auth::id(),
                'service_id' => $appointment->service_id,
                'status' => $appointment->status,
                'date_appointments' => $appointment->date_appointments,
                'created_at' => $appointment->created_at
            ]);

            // Xóa lịch hẹn
            $appointment->delete();

            return redirect()->route('customer.appointments.index')
                ->with('success', 'Đã xóa lịch hẹn khỏi lịch sử của bạn.');

        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa lịch hẹn: ' . $e->getMessage(), [
                'appointment_id' => $appointment->id,
                'customer_id' => Auth::id()
            ]);

            return back()->with('error', 'Đã xảy ra lỗi khi xóa lịch hẹn: ' . $e->getMessage());
        }
    }
    /**
     * Tìm nhân viên kỹ thuật phù hợp cho dịch vụ và khung giờ
     *
     * @param int $serviceId ID của dịch vụ (hiện tại chưa sử dụng, có thể dùng để lọc nhân viên theo kỹ năng)
     * @param string $date Ngày đặt lịch
     * @param int $timeSlotId ID của khung giờ
     * @return \App\Models\User|null
     */
    private function findAvailableEmployee($serviceId, $date, $timeSlotId)
    {
        // Ghi log để debug
        Log::info('Tìm nhân viên phù hợp', [
            'service_id' => $serviceId,
            'date' => $date,
            'time_slot_id' => $timeSlotId
        ]);

        try {
            // Lấy danh sách nhân viên kỹ thuật - thử cả 'employee' và 'Employee'
            $employees = \App\Models\User::whereHas('role', function($query) {
                $query->whereIn('name', ['employee', 'Employee', 'Technician', 'technician', 'Staff', 'staff']);
            })->get();

            // Ghi log số lượng nhân viên tìm thấy
            Log::info('Số lượng nhân viên tìm thấy: ' . $employees->count());

            // Nếu không có nhân viên nào, tạo một nhân viên mặc định
            if ($employees->isEmpty()) {
                // Tìm role nhân viên
                $employeeRole = \App\Models\Role::where('name', 'Employee')
                    ->orWhere('name', 'employee')
                    ->orWhere('name', 'Technician')
                    ->orWhere('name', 'Staff')
                    ->first();

                if (!$employeeRole) {
                    // Nếu không có role nhân viên, tạo mới
                    $employeeRole = \App\Models\Role::create([
                        'id' => \Illuminate\Support\Str::uuid()->toString(),
                        'name' => 'Employee',
                        'description' => 'Nhân viên kỹ thuật'
                    ]);

                    Log::info('Đã tạo role nhân viên mới: ' . $employeeRole->id);
                }

                // Tạo nhân viên mặc định
                $defaultEmployee = \App\Models\User::create([
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'first_name' => 'Nhân viên',
                    'last_name' => 'Mặc định',
                    'email' => 'employee@example.com',
                    'password' => bcrypt('password'),
                    'role_id' => $employeeRole->id,
                    'status' => 'active'
                ]);

                Log::info('Đã tạo nhân viên mặc định: ' . $defaultEmployee->id);

                return $defaultEmployee;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi tìm nhân viên: ' . $e->getMessage());
            return null;
        }

        try {
            // Lấy danh sách lịch hẹn trong ngày và khung giờ đã chọn
            $existingAppointments = Appointment::where('date_appointments', $date)
                ->where('time_appointments_id', $timeSlotId)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->get();

            // Lấy danh sách nhân viên đã được phân công trong khung giờ này
            $busyEmployeeIds = $existingAppointments->pluck('employee_id')->toArray();

            // Lọc ra các nhân viên còn trống lịch
            $availableEmployees = $employees->filter(function($employee) use ($busyEmployeeIds) {
                return !in_array($employee->id, $busyEmployeeIds);
            });

            // Nếu không có nhân viên nào trống lịch, trả về nhân viên đầu tiên
            if ($availableEmployees->isEmpty()) {
                Log::info('Không có nhân viên trống lịch, trả về nhân viên đầu tiên');
                return $employees->first();
            }

            // Lấy danh sách lịch hẹn trong ngày của các nhân viên trống lịch
            $employeeAppointments = Appointment::whereIn('employee_id', $availableEmployees->pluck('id')->toArray())
                ->where('date_appointments', $date)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->get()
                ->groupBy('employee_id');

            // Tính số lịch hẹn của mỗi nhân viên trong ngày
            $employeeWorkload = [];
            foreach ($availableEmployees as $employee) {
                $employeeWorkload[$employee->id] = isset($employeeAppointments[$employee->id])
                    ? count($employeeAppointments[$employee->id])
                    : 0;
            }

            // Sắp xếp nhân viên theo số lịch hẹn tăng dần
            asort($employeeWorkload);

            // Lấy ID nhân viên có ít lịch hẹn nhất
            $leastBusyEmployeeId = array_key_first($employeeWorkload);

            if (!$leastBusyEmployeeId) {
                Log::info('Không tìm thấy nhân viên có ít lịch hẹn nhất, trả về nhân viên đầu tiên');
                return $availableEmployees->first();
            }

            // Trả về nhân viên có ít lịch hẹn nhất
            $selectedEmployee = $availableEmployees->firstWhere('id', $leastBusyEmployeeId);

            if (!$selectedEmployee) {
                Log::info('Không tìm thấy nhân viên với ID ' . $leastBusyEmployeeId . ', trả về nhân viên đầu tiên');
                return $availableEmployees->first();
            }

            Log::info('Đã tìm thấy nhân viên phù hợp: ' . $selectedEmployee->id);
            return $selectedEmployee;
        } catch (\Exception $e) {
            Log::error('Lỗi khi phân công nhân viên: ' . $e->getMessage());

            // Nếu có lỗi, trả về nhân viên đầu tiên nếu có
            if (!$employees->isEmpty()) {
                return $employees->first();
            }

            return null;
        }
    }
}