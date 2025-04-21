<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Time;
use App\Models\User;
use App\Models\Employee;
use App\Notifications\AppointmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the staff's appointments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all appointments (not just the staff's own appointments)
        $appointments = Appointment::with(['service', 'employee', 'timeAppointment', 'customer'])
            ->orderBy('date_appointments', 'desc')
            ->paginate(10);

        // Get appointment statistics
        $statistics = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        return view('staff.appointments.index_new', compact('appointments', 'statistics'));
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @param int|null $serviceId
     * @return \Illuminate\View\View
     */
    public function create($serviceId = null)
    {
        $service = null;
        if ($serviceId) {
            $service = Service::findOrFail($serviceId);
        }

        // Get all active services
        $services = Service::where('status', 'active')->get();

        // Get all time slots
        $times = Time::orderBy('started_time')->get();

        // Get all customers (users with customer role)
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->get();

        return view('staff.appointments.create_new', compact('services', 'times', 'service', 'customers'));
    }

    /**
     * Show the customer-style form for creating a new appointment.
     *
     * @param int|null $serviceId
     * @return \Illuminate\View\View
     */
    public function createCustomerStyle($serviceId = null)
    {
        $service = null;
        if ($serviceId) {
            $service = Service::findOrFail($serviceId);
        }

        // Get all active services
        $services = Service::where('status', 'active')->get();

        // Get all time slots
        $times = Time::orderBy('started_time')->get();

        return view('staff.appointments.create_customer_style', compact('services', 'times', 'service'));
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the basic required fields
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'time_appointments_id' => 'required|exists:times,id',
            'notes' => 'nullable|string|max:500',
            'status' => 'required|in:pending,confirmed',
            'booking_types' => 'required|array|min:1',
            'booking_types.*' => 'in:day,month,year',
        ]);

        // Process each booking type
        $bookingDates = [];

        // Process day booking if selected
        if (in_array('day', $request->booking_types) && $request->day_date) {
            $dayDate = $request->day_date;
            // Validate the date format
            if (\Carbon\Carbon::hasFormat($dayDate, 'Y-m-d')) {
                $bookingDates[] = [
                    'type' => 'day',
                    'date' => $dayDate,
                ];
            }
        }

        // Process month booking if selected
        if (in_array('month', $request->booking_types) && $request->month_date) {
            $monthDate = $request->month_date;
            // Check if the date is in MM/YYYY format
            if (preg_match('/^\d{1,2}\/\d{4}$/', $monthDate)) {
                // Convert MM/YYYY to a valid date (first day of the month)
                list($month, $year) = explode('/', $monthDate);
                $firstDayOfMonth = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';

                $bookingDates[] = [
                    'type' => 'month',
                    'date' => $firstDayOfMonth,
                    'display' => $monthDate,
                ];
            }
        }

        // Process year booking if selected
        if (in_array('year', $request->booking_types) && $request->year_date) {
            $yearDate = $request->year_date;
            // Check if the date is just a year
            if (preg_match('/^\d{4}$/', $yearDate)) {
                // Convert YYYY to a valid date (first day of the year)
                $firstDayOfYear = $yearDate . '-01-01';

                $bookingDates[] = [
                    'type' => 'year',
                    'date' => $firstDayOfYear,
                    'display' => $yearDate,
                ];
            }
        }

        // If no valid dates were found, return an error
        if (empty($bookingDates)) {
            return back()->withInput()->with('error', 'Vui lòng chọn ít nhất một ngày/tháng/năm hợp lệ.');
        }

        try {
            // Process all booking dates
            $totalSuccessCount = 0;
            $totalFailCount = 0;
            $periodDescriptions = [];

            foreach ($bookingDates as $booking) {
                $type = $booking['type'];
                $date = $booking['date'];
                $display = $booking['display'] ?? $date;

                // Get the start and end dates based on booking type
                $startDate = \Carbon\Carbon::parse($date);

                if ($type === 'day') {
                    // For day booking, start and end are the same
                    $endDate = $startDate->copy();
                    $periodName = "ngày " . $startDate->format('d/m/Y');
                } else if ($type === 'month') {
                    // For month booking, end is the last day of the month
                    $endDate = $startDate->copy()->endOfMonth();
                    $periodName = "tháng " . $display;
                } else { // year booking
                    // For year booking, end is the last day of the year
                    $endDate = $startDate->copy()->endOfYear();
                    $periodName = "năm " . $display;
                }

                // Create appointments for each day in the period
                $currentDay = $startDate->copy();
                $successCount = 0;
                $failCount = 0;

                while ($currentDay <= $endDate) {
                    // Skip weekends
                    if ($currentDay->isWeekend()) {
                        $currentDay->addDay();
                        continue;
                    }

                    // Check if the time slot is already booked for this day
                    $existingAppointment = Appointment::where('date_appointments', $currentDay->format('Y-m-d'))
                        ->where('time_appointments_id', $request->time_appointments_id)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->first();

                    if (!$existingAppointment) {
                        // Create an appointment for this day
                        $this->createAppointment(
                            $request->customer_id,
                            $request->service_id,
                            $currentDay->format('Y-m-d'),
                            $request->time_appointments_id,
                            $request->notes,
                            $request->status
                        );

                        $successCount++;
                    } else {
                        $failCount++;
                    }

                    $currentDay->addDay();
                }

                // Add to total counts
                $totalSuccessCount += $successCount;
                $totalFailCount += $failCount;

                // Add period description
                if ($successCount > 0) {
                    $periodDescriptions[] = "$successCount lịch hẹn cho $periodName";
                }
            }

            // Create success message
            if ($totalSuccessCount > 0) {
                $message = "Đã tạo thành công " . implode(", ", $periodDescriptions);
                if ($totalFailCount > 0) {
                    $message .= ". $totalFailCount ngày đã có người đặt trước.";
                }

                return redirect()->route('staff.appointments.index')
                    ->with('success', $message);
            } else {
                return back()->withInput()->with('error', 'Không thể tạo lịch hẹn. Tất cả các khung giờ đã được đặt trước đó.');
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified appointment.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $appointment = Appointment::with(['service', 'employee', 'customer', 'timeAppointment'])
            ->findOrFail($id);

        return view('staff.appointments.show_new', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $appointment = Appointment::with(['service', 'customer', 'timeAppointment'])
            ->findOrFail($id);

        // Get all active services
        $services = Service::where('status', 'active')->get();

        // Get all time slots
        $times = Time::orderBy('started_time')->get();

        // Get all customers
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->get();

        return view('staff.appointments.edit_new', compact('appointment', 'services', 'times', 'customers'));
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date_appointments' => 'required|date|after_or_equal:today',
            'time_appointments_id' => 'required|exists:times,id',
            'notes' => 'nullable|string|max:500',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        // Check if the time slot is already booked by another appointment
        if ($request->date_appointments != $appointment->date_appointments ||
            $request->time_appointments_id != $appointment->time_appointments_id) {

            $existingAppointment = Appointment::where('id', '!=', $id)
                ->where('date_appointments', $request->date_appointments)
                ->where('time_appointments_id', $request->time_appointments_id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            if ($existingAppointment) {
                return back()->withInput()->with('error', 'Thời gian này đã có người đặt. Vui lòng chọn thời gian khác.');
            }
        }

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Kiểm tra nếu trạng thái được thay đổi thành 'cancelled'
            $originalStatus = $appointment->status;
            $newStatus = $request->status;

            // Nếu trạng thái được thay đổi thành 'cancelled', giảm số lượng đặt chỗ
            if ($newStatus === 'cancelled' && in_array($originalStatus, ['pending', 'confirmed'])) {
                $timeSlot = Time::findOrFail($appointment->time_appointments_id);
                if ($timeSlot->booked_count > 0) {
                    $timeSlot->decrement('booked_count');
                }
            }

            // Update the appointment
            $appointment->update([
                'service_id' => $request->service_id,
                'date_appointments' => $request->date_appointments,
                'time_appointments_id' => $request->time_appointments_id,
                'notes' => $request->notes,
                'status' => $request->status,
            ]);

            DB::commit();

            // Send notification if status changed
            if ($appointment->customer->email_notifications_enabled) {
                if ($newStatus !== $originalStatus) {
                    try {
                        $appointment->customer->notify(new AppointmentNotification($appointment, $newStatus));
                    } catch (\Exception $e) {
                        // Log notification error but continue
                        \Illuminate\Support\Facades\Log::error('Failed to send appointment notification: ' . $e->getMessage());
                    }
                }
            }

            return redirect()->route('staff.appointments.show', $appointment->id)
                ->with('success', 'Lịch hẹn đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Show form for editing customer information for an appointment.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function editCustomer($id)
    {
        $appointment = Appointment::with('customer')->findOrFail($id);
        return view('staff.appointments.edit_customer', compact('appointment'));
    }

    /**
     * Update customer information for an appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCustomer(Request $request, $id)
    {
        $appointment = Appointment::with('customer')->findOrFail($id);
        $customer = $appointment->customer;

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birthday' => 'nullable|date',
        ]);

        $customer->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
        ]);

        return redirect()->route('staff.appointments.show', $appointment->id)
            ->with('success', 'Thông tin khách hàng đã được cập nhật thành công.');
    }

    /**
     * Cancel the specified appointment.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, $id)
    {
        $appointment = Appointment::with('customer')->findOrFail($id);

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Chỉ có thể hủy lịch hẹn đang chờ xác nhận hoặc đã xác nhận.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Giảm số lượng đặt chỗ cho khung giờ này
            $timeSlot = Time::findOrFail($appointment->time_appointments_id);
            if ($timeSlot->booked_count > 0) {
                $timeSlot->decrement('booked_count');
            }

            $appointment->status = 'cancelled';
            $appointment->cancellation_reason = $request->cancellation_reason;
            $appointment->save();

            DB::commit();

            // Send cancellation notification to the customer
            if ($appointment->customer && $appointment->customer->email_notifications_enabled &&
                $appointment->customer->notify_appointment_cancellation) {
                try {
                    $appointment->customer->notify(new AppointmentNotification($appointment, 'cancelled'));
                } catch (\Exception $e) {
                    // Log notification error but continue
                    \Illuminate\Support\Facades\Log::error('Failed to send cancellation notification: ' . $e->getMessage());
                }
            }

            return redirect()->route('staff.appointments.index')
                ->with('success', 'Lịch hẹn đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi hủy lịch hẹn: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to create an appointment
     *
     * @param string $customerId
     * @param string $serviceId
     * @param string $dateAppointments
     * @param string $timeAppointmentsId
     * @param string|null $notes
     * @param string $status
     * @return \App\Models\Appointment
     */
    private function createAppointment($customerId, $serviceId, $dateAppointments, $timeAppointmentsId, $notes = null, $status = 'pending')
    {
        // Find the service
        $service = Service::findOrFail($serviceId);

        // Find the customer
        $customer = User::findOrFail($customerId);

        // Get an employee who works at the clinic that offers this service
        $employee = Employee::whereHas('clinic', function($query) use ($service) {
            $query->where('id', $service->clinic_id);
        })->first();

        // If no employee is found, assign the current staff member
        $employeeId = $employee ? $employee->id : Auth::id();

        // Create UUID for id
        $uuid = Str::uuid()->toString();

        // Create the appointment
        $appointment = Appointment::create([
            'id' => $uuid,
            'customer_id' => $customerId,
            'service_id' => $serviceId,
            'date_register' => now(),
            'status' => $status,
            'date_appointments' => $dateAppointments,
            'time_appointments_id' => $timeAppointmentsId,
            'employee_id' => $employeeId,
            'notes' => $notes,
        ]);

        // Send notification to the customer if enabled
        if ($customer->email_notifications_enabled) {
            try {
                $customer->notify(new AppointmentNotification($appointment, $status === 'confirmed' ? 'confirmed' : 'created'));
            } catch (\Exception $e) {
                // Log notification error but continue
                \Illuminate\Support\Facades\Log::error('Failed to send appointment notification: ' . $e->getMessage());
            }
        }

        return $appointment;
    }
}
