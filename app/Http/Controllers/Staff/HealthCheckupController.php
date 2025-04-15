<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\HealthRecord;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use App\Notifications\HealthCheckupAppointmentNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HealthCheckupController extends Controller
{
    /**
     * Display a listing of the staff's health check-up appointments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $appointments = Appointment::with(['service', 'doctor', 'timeSlot'])
            ->where('user_id', Auth::id())
            ->whereHas('service', function ($query) {
                $query->where('is_health_checkup', true);
            })
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('staff.health-checkups.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new health check-up appointment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $services = Service::where('is_active', true)
            ->where('is_health_checkup', true)
            ->get();

        $doctors = User::whereHas('role', function ($query) {
            $query->where('name', 'doctor');
        })->get();

        // Get available dates (next 30 days, excluding weekends)
        $availableDates = [];
        $date = Carbon::today();
        $count = 0;

        while (count($availableDates) < 30) {
            // Skip weekends (0 = Sunday, 6 = Saturday)
            if (!in_array($date->dayOfWeek, [0, 6])) {
                $availableDates[] = $date->format('Y-m-d');
            }

            $date->addDay();

            // Safety check to prevent infinite loop
            if (++$count > 60) break;
        }

        return view('staff.health-checkups.create', compact('services', 'doctors', 'availableDates'));
    }

    /**
     * Store a newly created health check-up appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot_id' => 'required|exists:time_slots,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if the time slot is available for the selected date
        $timeSlot = TimeSlot::findOrFail($request->time_slot_id);
        $appointmentDate = Carbon::parse($request->appointment_date);

        if (!$timeSlot->isAvailableForDate($appointmentDate)) {
            return back()->withInput()->with('error', 'Khung giờ này đã đầy. Vui lòng chọn khung giờ khác.');
        }

        try {
            // Create the appointment
            $appointment = Appointment::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'service_id' => $request->service_id,
                'appointment_date' => $appointmentDate->format('Y-m-d') . ' ' . $timeSlot->start_time->format('H:i:s'),
                'status' => 'pending',
                'notes' => $request->notes,
                'time_slot_id' => $request->time_slot_id,
            ]);

            // Assign a doctor automatically if available
            $doctor = User::whereHas('role', function ($query) {
                $query->where('name', 'doctor');
            })->inRandomOrder()->first();

            if ($doctor) {
                $appointment->doctor_id = $doctor->id;
                $appointment->save();
            }

            // Send notification
            $user = Auth::user();
            $user->notify(new HealthCheckupAppointmentNotification($appointment, 'created'));

            return redirect()->route('staff.health-checkups.index')
                ->with('success', 'Đặt lịch khám sức khỏe thành công. Vui lòng chờ xác nhận từ phòng y tế.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified health check-up appointment.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $appointment = Appointment::with(['service', 'doctor', 'timeSlot', 'healthRecord'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('staff.health-checkups.show', compact('appointment'));
    }

    /**
     * Cancel the specified health check-up appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:255',
        ]);

        $appointment = Appointment::where('user_id', Auth::id())->findOrFail($id);

        // Only allow cancellation if appointment is pending or confirmed
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Không thể hủy lịch hẹn này.');
        }

        $appointment->status = 'cancelled';
        $appointment->cancellation_reason = $request->cancellation_reason;
        $appointment->save();

        // Send notification
        $user = Auth::user();
        $user->notify(new HealthCheckupAppointmentNotification($appointment, 'cancelled'));

        return redirect()->route('staff.health-checkups.index')
            ->with('success', 'Lịch hẹn đã được hủy thành công.');
    }

    /**
     * Display the health records of the authenticated staff.
     *
     * @return \Illuminate\View\View
     */
    public function healthRecords()
    {
        $healthRecords = HealthRecord::with(['appointment', 'appointment.service'])
            ->where('user_id', Auth::id())
            ->orderBy('check_date', 'desc')
            ->paginate(10);

        return view('staff.health-checkups.records', compact('healthRecords'));
    }

    /**
     * Display a specific health record.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function showHealthRecord($id)
    {
        $healthRecord = HealthRecord::with(['appointment', 'appointment.service', 'appointment.doctor'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('staff.health-checkups.record-details', compact('healthRecord'));
    }
}
