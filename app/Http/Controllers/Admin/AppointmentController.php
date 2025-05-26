<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Time;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Appointment::with(['user', 'service', 'employee', 'timeAppointment']);

            // Tìm kiếm theo từ khóa
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%")
                                   ->orWhere('phone', 'like', "%{$search}%");
                      })
                      ->orWhereHas('service', function($serviceQuery) use ($search) {
                          $serviceQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Lọc theo trạng thái
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Lọc theo ngày
            if ($request->filled('date_from')) {
                $query->whereDate('date_appointments', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('date_appointments', '<=', $request->date_to);
            }

            // Lọc theo dịch vụ
            if ($request->filled('service_id')) {
                $query->where('service_id', $request->service_id);
            }

            // Lọc theo nhân viên
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            // Sắp xếp
            $sortBy = $request->get('sort_by', 'date_appointments');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Phân trang với số lượng có thể tùy chỉnh
            $perPage = $request->get('per_page', 10);
            $appointments = $query->paginate($perPage);

            // Calculate statistics
            $statistics = [
                'total' => Appointment::count(),
                'pending' => Appointment::where('status', 'pending')->count(),
                'confirmed' => Appointment::where('status', 'confirmed')->count(),
                'completed' => Appointment::where('status', 'completed')->count(),
                'cancelled' => Appointment::where('status', 'cancelled')->count(),
            ];

            // Lấy danh sách dịch vụ và nhân viên cho filter
            $services = Service::orderBy('name')->get() ?? collect();
            $employees = User::whereHas('role', function($query) {
                $query->whereIn('name', ['Technician', 'Receptionist']);
            })->orderBy('first_name')->get() ?? collect();

            return view('admin.appointments.index', compact('appointments', 'statistics', 'services', 'employees'));
        } catch (\Exception $e) {
            Log::error('Error in AppointmentController@index: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
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

        return view('admin.appointments.create', compact('services', 'times', 'service', 'customers'));
    }

    public function show($id)
    {
        $appointment = Appointment::with(['user', 'service', 'employee', 'timeAppointment'])
            ->findOrFail($id);

        // Get customer history (other appointments by the same user)
        $customerHistory = Appointment::where('customer_id', $appointment->customer_id)
            ->where('id', '!=', $appointment->id)
            ->with('service')
            ->orderBy('date_appointments', 'desc')
            ->get();

        return view('admin.appointments.show', compact('appointment', 'customerHistory'));
    }

    public function edit($id)
    {
        $appointment = Appointment::with(['user', 'service', 'employee', 'timeAppointment'])
            ->findOrFail($id);
        $employees = Employee::all();
        $times = Time::orderBy('started_time')->get();

        return view('admin.appointments.edit', compact('appointment', 'employees', 'times'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled,completed',
            'date_appointments' => 'required|date',
            'time_appointments_id' => 'required|exists:times,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $appointment = Appointment::findOrFail($id);

        // Kiểm tra xem nhân viên có lịch trùng không
        $existingAppointment = Appointment::where('employee_id', $request->employee_id)
            ->where('date_appointments', $request->date_appointments)
            ->where('time_appointments_id', $request->time_appointments_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingAppointment) {
            return back()->with('error', 'Nhân viên đã có lịch hẹn khác trong thời gian này.');
        }

        // Bắt đầu transaction
        \Illuminate\Support\Facades\DB::beginTransaction();

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

                // Giảm số lượng sử dụng mã khuyến mãi nếu có
                if (!empty($appointment->promotion_code)) {
                    $promotion = \App\Models\Promotion::where('code', strtoupper($appointment->promotion_code))
                        ->first();

                    if ($promotion && $promotion->usage_count > 0) {
                        // Giảm số lượng sử dụng đi 1
                        $promotion->decrement('usage_count');

                        // Log để debug
                        \Illuminate\Support\Facades\Log::info('Đã giảm số lượng sử dụng mã khuyến mãi khi thay đổi trạng thái lịch hẹn', [
                            'promotion_id' => $promotion->id,
                            'promotion_code' => $promotion->code,
                            'old_usage_count' => $promotion->usage_count + 1,
                            'new_usage_count' => $promotion->usage_count,
                            'appointment_id' => $appointment->id,
                            'new_status' => $newStatus
                        ]);
                    }
                }
            }

            $appointment->update([
                'status' => $request->status,
                'date_appointments' => $request->date_appointments,
                'time_appointments_id' => $request->time_appointments_id,
                'employee_id' => $request->employee_id,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Lịch hẹn đã được cập nhật thành công.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);

            // Admin có thể xóa mọi lịch hẹn
            // Nếu lịch hẹn đang active (pending/confirmed), cần giải phóng slot
            if (in_array($appointment->status, ['pending', 'confirmed'])) {
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
                        $promotion->decrement('usage_count');
                    }
                }
            }

            $appointment->delete();

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Lịch hẹn đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'Đã xảy ra lỗi khi xóa lịch hẹn: ' . $e->getMessage());
        }
    }

    /**
     * Xóa nhiều lịch hẹn cùng lúc
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'appointment_ids' => 'required|array',
            'appointment_ids.*' => 'exists:appointments,id'
        ]);

        try {
            DB::beginTransaction();

            $appointments = Appointment::whereIn('id', $request->appointment_ids)->get();
            $deletedCount = 0;

            foreach ($appointments as $appointment) {
                // Admin có thể xóa mọi lịch hẹn
                // Nếu lịch hẹn đang active (pending/confirmed), cần giải phóng slot
                if (in_array($appointment->status, ['pending', 'confirmed'])) {
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
                            $promotion->decrement('usage_count');
                        }
                    }
                }

                $appointment->delete();
                $deletedCount++;
            }

            DB::commit();

            return redirect()->route('admin.appointments.index')
                ->with('success', "Đã xóa {$deletedCount} lịch hẹn thành công.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.appointments.index')
                ->with('error', 'Đã xảy ra lỗi khi xóa lịch hẹn: ' . $e->getMessage());
        }
    }

    /**
     * Export danh sách lịch hẹn
     */
    public function export(Request $request)
    {
        $query = Appointment::with(['user', 'service', 'employee', 'timeAppointment']);

        // Áp dụng các filter giống như trong index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%")
                               ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('service', function($serviceQuery) use ($search) {
                      $serviceQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_appointments', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_appointments', '<=', $request->date_to);
        }

        $appointments = $query->orderBy('date_appointments', 'desc')->get();

        $filename = 'lich_hen_' . date('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, [
                'Mã lịch hẹn',
                'Khách hàng',
                'Email',
                'Điện thoại',
                'Dịch vụ',
                'Ngày hẹn',
                'Giờ hẹn',
                'Nhân viên',
                'Trạng thái',
                'Ngày tạo'
            ]);

            // Data
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    'APT-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
                    $appointment->user->first_name . ' ' . $appointment->user->last_name,
                    $appointment->user->email,
                    $appointment->user->phone ?? '',
                    $appointment->service->name ?? '',
                    $appointment->date_appointments,
                    $appointment->timeAppointment->started_time ?? '',
                    ($appointment->employee->first_name ?? '') . ' ' . ($appointment->employee->last_name ?? ''),
                    $appointment->status,
                    $appointment->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Hiển thị form phân công nhân viên kỹ thuật
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignStaff($id)
    {
        $appointment = Appointment::with(['service', 'customer', 'timeAppointment', 'timeSlot'])
            ->findOrFail($id);

        // Lấy danh sách nhân viên kỹ thuật
        $technicians = \App\Models\User::whereHas('role', function($query) {
            $query->where('name', 'Technician');
        })->get();

        // Kiểm tra nhân viên nào có lịch trống vào thời điểm này
        $availableTechnicians = [];
        foreach ($technicians as $technician) {
            // Kiểm tra xem nhân viên đã có lịch hẹn khác trong cùng thời gian chưa
            $hasConflict = Appointment::where('employee_id', $technician->id)
                ->where('date_appointments', $appointment->date_appointments)
                ->where('time_appointments_id', $appointment->time_appointments_id)
                ->where('id', '!=', $appointment->id)
                ->whereIn('status', ['confirmed', 'in_progress'])
                ->exists();

            if (!$hasConflict) {
                $availableTechnicians[] = $technician;
            }
        }

        return view('admin.appointments.assign-staff-tailwind', compact('appointment', 'availableTechnicians'));
    }

    /**
     * Xác nhận lịch hẹn và phân công nhân viên kỹ thuật
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
        ]);

        $appointment = Appointment::findOrFail($id);

        try {
            // Cập nhật trạng thái và nhân viên phục vụ
            $appointment->status = 'confirmed';
            $appointment->employee_id = $request->employee_id;
            $appointment->updated_by = Auth::id();
            $appointment->save();

            // Tạo lịch làm việc cho nhân viên kỹ thuật
            if ($appointment->timeSlot) {
                \App\Models\WorkSchedule::updateOrCreate(
                    [
                        'user_id' => $request->employee_id,
                        'date' => $appointment->date_appointments->format('Y-m-d'),
                        'time_slot_id' => $appointment->timeSlot->id,
                    ],
                    [
                        'status' => 'scheduled',
                        'notes' => 'Tự động phân công từ lịch hẹn #' . $appointment->id,
                    ]
                );
            }

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Lịch hẹn đã được xác nhận và phân công nhân viên thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xác nhận lịch hẹn: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Kiểm tra trạng thái hiện tại của lịch hẹn
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Chỉ có thể hủy lịch hẹn đang chờ xác nhận hoặc đã xác nhận.');
        }

        // Bắt đầu transaction
        \Illuminate\Support\Facades\DB::beginTransaction();

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
                    \Illuminate\Support\Facades\Log::info('Đã giảm số lượng sử dụng mã khuyến mãi khi admin hủy lịch', [
                        'promotion_id' => $promotion->id,
                        'promotion_code' => $promotion->code,
                        'old_usage_count' => $promotion->usage_count + 1,
                        'new_usage_count' => $promotion->usage_count
                    ]);
                }
            }

            $appointment->update(['status' => 'cancelled']);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->back()->with('success', 'Lịch hẹn đã được hủy thành công.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi hủy lịch hẹn: ' . $e->getMessage());
        }
    }

    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Lịch hẹn đã được hoàn thành thành công.');
    }
}
