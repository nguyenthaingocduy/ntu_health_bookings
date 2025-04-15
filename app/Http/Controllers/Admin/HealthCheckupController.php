<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\HealthRecord;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HealthCheckupController extends Controller
{
    /**
     * Display a listing of all health check-up appointments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'service', 'doctor', 'timeSlot'])
            ->whereHas('service', function ($query) {
                $query->where('is_health_checkup', true);
            });
            
        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('staff_id', 'like', "%{$search}%");
            });
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(15);
        
        // Get statistics for dashboard
        $stats = [
            'total' => Appointment::whereHas('service', function ($q) {
                $q->where('is_health_checkup', true);
            })->count(),
            'pending' => Appointment::whereHas('service', function ($q) {
                $q->where('is_health_checkup', true);
            })->where('status', 'pending')->count(),
            'confirmed' => Appointment::whereHas('service', function ($q) {
                $q->where('is_health_checkup', true);
            })->where('status', 'confirmed')->count(),
            'completed' => Appointment::whereHas('service', function ($q) {
                $q->where('is_health_checkup', true);
            })->where('status', 'completed')->count(),
            'cancelled' => Appointment::whereHas('service', function ($q) {
                $q->where('is_health_checkup', true);
            })->where('status', 'cancelled')->count(),
        ];
        
        return view('admin.health-checkups.index', compact('appointments', 'stats'));
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
            
        $staff = User::whereHas('role', function ($query) {
            $query->where('name', 'staff');
        })->get();
        
        $doctors = User::whereHas('role', function ($query) {
            $query->where('name', 'doctor');
        })->get();
        
        $timeSlots = TimeSlot::orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        
        return view('admin.health-checkups.create', compact('services', 'staff', 'doctors', 'timeSlots'));
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
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'doctor_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,confirmed',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Create the appointment
            $timeSlot = TimeSlot::findOrFail($request->time_slot_id);
            $appointmentDate = Carbon::parse($request->appointment_date);
            
            $appointment = Appointment::create([
                'id' => Str::uuid(),
                'user_id' => $request->user_id,
                'service_id' => $request->service_id,
                'appointment_date' => $appointmentDate->format('Y-m-d') . ' ' . $timeSlot->start_time->format('H:i:s'),
                'status' => $request->status,
                'notes' => $request->notes,
                'doctor_id' => $request->doctor_id,
                'time_slot_id' => $request->time_slot_id,
            ]);
            
            return redirect()->route('admin.health-checkups.index')
                ->with('success', 'Lịch hẹn khám sức khỏe đã được tạo thành công.');
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
        $appointment = Appointment::with(['user', 'service', 'doctor', 'timeSlot', 'healthRecord'])
            ->findOrFail($id);
            
        return view('admin.health-checkups.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified health check-up appointment.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $appointment = Appointment::with(['user', 'service', 'doctor', 'timeSlot'])
            ->findOrFail($id);
            
        $services = Service::where('is_active', true)
            ->where('is_health_checkup', true)
            ->get();
            
        $doctors = User::whereHas('role', function ($query) {
            $query->where('name', 'doctor');
        })->get();
        
        $timeSlots = TimeSlot::orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        
        return view('admin.health-checkups.edit', compact('appointment', 'services', 'doctors', 'timeSlots'));
    }

    /**
     * Update the specified health check-up appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'doctor_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,confirmed,completed,cancelled,no-show',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $appointment = Appointment::findOrFail($id);
            $timeSlot = TimeSlot::findOrFail($request->time_slot_id);
            $appointmentDate = Carbon::parse($request->appointment_date);
            
            $appointment->update([
                'service_id' => $request->service_id,
                'appointment_date' => $appointmentDate->format('Y-m-d') . ' ' . $timeSlot->start_time->format('H:i:s'),
                'status' => $request->status,
                'notes' => $request->notes,
                'doctor_id' => $request->doctor_id,
                'time_slot_id' => $request->time_slot_id,
            ]);
            
            if ($request->status === 'completed' && !$appointment->is_completed) {
                $appointment->is_completed = true;
                $appointment->check_out_time = now();
                $appointment->save();
            }
            
            return redirect()->route('admin.health-checkups.index')
                ->with('success', 'Lịch hẹn khám sức khỏe đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for recording health check-up results.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function recordForm($id)
    {
        $appointment = Appointment::with(['user', 'service', 'doctor'])
            ->findOrFail($id);
            
        // Check if a health record already exists
        $healthRecord = HealthRecord::where('appointment_id', $id)->first();
        
        return view('admin.health-checkups.record-form', compact('appointment', 'healthRecord'));
    }

    /**
     * Store or update health check-up results.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveRecord(Request $request, $id)
    {
        $request->validate([
            'height' => 'nullable|numeric|min:0|max:300',
            'weight' => 'nullable|numeric|min:0|max:500',
            'blood_pressure' => 'nullable|string|max:20',
            'heart_rate' => 'nullable|integer|min:0|max:300',
            'blood_type' => 'nullable|string|max:10',
            'allergies' => 'nullable|string|max:1000',
            'medical_history' => 'nullable|string|max:2000',
            'diagnosis' => 'nullable|string|max:2000',
            'recommendations' => 'nullable|string|max:2000',
            'next_check_date' => 'nullable|date',
            'doctor_notes' => 'nullable|string|max:2000',
        ]);

        try {
            $appointment = Appointment::findOrFail($id);
            
            // Find or create health record
            $healthRecord = HealthRecord::firstOrNew(['appointment_id' => $id]);
            
            // Set or update health record data
            $healthRecord->user_id = $appointment->user_id;
            $healthRecord->check_date = now();
            $healthRecord->height = $request->height;
            $healthRecord->weight = $request->weight;
            $healthRecord->blood_pressure = $request->blood_pressure;
            $healthRecord->heart_rate = $request->heart_rate;
            $healthRecord->blood_type = $request->blood_type;
            $healthRecord->allergies = $request->allergies;
            $healthRecord->medical_history = $request->medical_history;
            $healthRecord->diagnosis = $request->diagnosis;
            $healthRecord->recommendations = $request->recommendations;
            $healthRecord->next_check_date = $request->next_check_date;
            $healthRecord->doctor_notes = $request->doctor_notes;
            
            if (!$healthRecord->id) {
                $healthRecord->id = Str::uuid();
            }
            
            $healthRecord->save();
            
            // Update appointment status to completed
            $appointment->status = 'completed';
            $appointment->is_completed = true;
            $appointment->check_out_time = now();
            $appointment->save();
            
            // Update user's last health check date
            $user = User::find($appointment->user_id);
            $user->last_health_check = now();
            $user->save();
            
            return redirect()->route('admin.health-checkups.show', $id)
                ->with('success', 'Kết quả khám sức khỏe đã được lưu thành công.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of all health records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function healthRecords(Request $request)
    {
        $query = HealthRecord::with(['user', 'appointment', 'appointment.service']);
            
        // Apply filters if provided
        if ($request->filled('date_from')) {
            $query->whereDate('check_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('check_date', '<=', $request->date_to);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('staff_id', 'like', "%{$search}%");
            });
        }
        
        $healthRecords = $query->orderBy('check_date', 'desc')->paginate(15);
        
        return view('admin.health-checkups.records', compact('healthRecords'));
    }

    /**
     * Display a specific health record.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function showHealthRecord($id)
    {
        $healthRecord = HealthRecord::with(['user', 'appointment', 'appointment.service', 'appointment.doctor'])
            ->findOrFail($id);
            
        return view('admin.health-checkups.record-details', compact('healthRecord'));
    }
}
