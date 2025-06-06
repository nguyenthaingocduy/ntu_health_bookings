<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;
use App\Models\TimeSlot;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;

class TimeSlotController extends Controller
{
    /**
     * Get available time slots for a given date and service
     */
    public function getAvailableTimeSlots(Request $request)
    {
        return $this->checkAvailableSlots($request);
    }

    /**
     * Get a specific time slot.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimeSlot($id)
    {
        $timeSlot = Time::find($id);

        if (!$timeSlot) {
            return response()->json([
                'success' => false,
                'message' => 'Time slot not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'time_slot' => $timeSlot
        ]);
    }

    /**
     * Get all time slots.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllTimeSlots(Request $request)
    {
        if ($request->has('day')) {
            // Lấy các khung giờ theo ngày trong tuần
            $dayOfWeek = $request->day;
            $date = $request->has('date') ? $request->date : date('Y-m-d');

            $timeSlots = TimeSlot::where('day_of_week', $dayOfWeek)
                ->where('is_available', true)
                ->orderBy('start_time')
                ->get()
                ->map(function($slot) use ($date) {
                    // Đếm số lượng cuộc hẹn hiện tại trong khung giờ này
                    $appointmentsCount = $slot->appointments()
                        ->whereDate('date_appointments', $date)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->count();

                    // Tính số lượng chỗ trống còn lại
                    $availableSlots = max(0, $slot->max_appointments - $appointmentsCount);

                    return [
                        'id' => $slot->id,
                        'start_time' => $slot->start_time->format('H:i'),
                        'end_time' => $slot->end_time->format('H:i'),
                        'max_appointments' => $slot->max_appointments,
                        'booked_count' => $appointmentsCount,
                        'available_slots' => $availableSlots,
                        'is_full' => $availableSlots <= 0
                    ];
                });

            return response()->json($timeSlots);
        } else {
            // Lấy tất cả các khung giờ từ bảng Time (cũ)
            $timeSlots = Time::orderBy('started_time')->get();
            return response()->json($timeSlots);
        }
    }

    /**
     * Kiểm tra và trả về các khung giờ khả dụng cho ngày và dịch vụ được chọn
     */
    public function checkAvailableSlots(Request $request)
    {
        try {
            // Log request
            Log::info('API được gọi: TimeSlotController@checkAvailableSlots', [
                'service_id' => $request->service_id,
                'date' => $request->date,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'request_all' => $request->all()
            ]);

            // Validate request
            $request->validate([
                'service_id' => 'required|exists:services,id',
                'date' => 'required|date|after_or_equal:today',
                'exclude_appointment_id' => 'nullable|string',
            ]);

            // Debug log
            Log::info('Validation passed, tìm các time slots');

            // Kiểm tra xem ngày đã chọn có phải ngày hôm nay không
            $isToday = date('Y-m-d', strtotime('now +7 hours')) === $request->date;
            $currentTime = now()->setTimezone('Asia/Ho_Chi_Minh');

            Log::info('Kiểm tra thời gian', [
                'isToday' => $isToday,
                'currentTime' => $currentTime->format('H:i:s'),
                'date_requested' => $request->date,
                'today_date' => date('Y-m-d', strtotime('now +7 hours')),
                'timezone' => config('app.timezone'),
                'timezone_vietnam' => 'Asia/Ho_Chi_Minh',
                'local_time' => now()->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s')
            ]);

            // Lấy tất cả các khung giờ
            $allTimeSlots = Time::orderBy('started_time')->get();

            Log::info('Tổng số time slots: ' . $allTimeSlots->count());

            // Nếu không có khung giờ nào, trả về thông báo lỗi
            if ($allTimeSlots->isEmpty()) {
                Log::warning('Không tìm thấy time slots nào trong hệ thống');
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy khung giờ nào trong hệ thống',
                    'available_slots' => []
                ]);
            }

            // Lấy các khung giờ đã được đặt trong ngày đã chọn
            $query = Appointment::where('date_appointments', $request->date)
                ->whereIn('status', ['pending', 'confirmed']);

            // Exclude the specified appointment if provided
            if ($request->has('exclude_appointment_id')) {
                $query->where('id', '!=', $request->exclude_appointment_id);
            }

            // Lấy danh sách các khung giờ đã được đặt và dịch vụ tương ứng
            $bookedAppointments = $query->select('time_appointments_id', 'service_id', 'customer_id')->get();

            // Tạo mảng chứa các khung giờ đã được đặt
            $bookedTimeSlots = $bookedAppointments->pluck('time_appointments_id')->toArray();

            // Tạo mảng chứa thông tin về khung giờ đã được đặt cho dịch vụ nào
            $bookedTimeSlotServices = [];
            foreach ($bookedAppointments as $appointment) {
                $bookedTimeSlotServices[$appointment->time_appointments_id] = $appointment->service_id;
            }

            // Tạo mảng chứa thông tin về khung giờ đã được đặt bởi khách hàng nào
            $customerBookedTimeSlots = [];
            foreach ($bookedAppointments as $appointment) {
                if (!isset($customerBookedTimeSlots[$appointment->customer_id])) {
                    $customerBookedTimeSlots[$appointment->customer_id] = [];
                }
                $customerBookedTimeSlots[$appointment->customer_id][] = $appointment->time_appointments_id;
            }

            Log::info('Các time slots đã đặt: ' . count($bookedTimeSlots), [
                'slot_ids' => $bookedTimeSlots,
                'booked_time_slot_services' => $bookedTimeSlotServices
            ]);

            // Lọc ra các khung giờ còn trống và nếu là ngày hôm nay, chỉ lấy các giờ trong tương lai
            $availableSlots = $allTimeSlots->filter(function($timeSlot) use ($isToday, $currentTime, $bookedTimeSlots, $bookedTimeSlotServices, $customerBookedTimeSlots, $request) {
                // Kiểm tra nếu khung giờ đã đầy
                if ($timeSlot->isFull()) {
                    Log::info("Khung giờ {$timeSlot->id} đã đầy", ['time' => $timeSlot->started_time, 'booked' => $timeSlot->booked_count, 'capacity' => $timeSlot->capacity]);
                    return false;
                }

                // Kiểm tra xem khung giờ này đã được đặt cho dịch vụ khác chưa
                if (in_array($timeSlot->id, $bookedTimeSlots)) {
                    // Nếu khung giờ đã được đặt, kiểm tra xem có phải cho cùng dịch vụ không
                    $bookedServiceId = $bookedTimeSlotServices[$timeSlot->id] ?? null;

                    // Nếu đã có dịch vụ khác đặt trong khung giờ này, không cho phép đặt thêm
                    if ($bookedServiceId !== null && $bookedServiceId != $request->service_id) {
                        Log::info("Khung giờ {$timeSlot->id} đã được đặt cho dịch vụ khác", [
                            'time' => $timeSlot->started_time,
                            'booked_service_id' => $bookedServiceId,
                            'requested_service_id' => $request->service_id
                        ]);
                        return false;
                    }
                }

                // Kiểm tra xem khách hàng hiện tại đã đặt dịch vụ trong khung giờ này chưa
                if ($request->has('customer_id') && isset($customerBookedTimeSlots[$request->customer_id]) && in_array($timeSlot->id, $customerBookedTimeSlots[$request->customer_id])) {
                    Log::info("Khách hàng đã đặt dịch vụ trong khung giờ {$timeSlot->id}", [
                        'time' => $timeSlot->started_time,
                        'customer_id' => $request->customer_id
                    ]);
                    return false;
                }

                // Nếu là ngày hôm nay, kiểm tra xem khung giờ có nằm trong tương lai không
                if ($isToday) {
                    // Lấy giờ và phút từ started_time
                    $slotTime = \Carbon\Carbon::parse($timeSlot->started_time);
                    $slotHour = $slotTime->hour;
                    $slotMinute = $slotTime->minute;

                    // Lấy giờ và phút hiện tại
                    $currentHour = $currentTime->hour;
                    $currentMinute = $currentTime->minute;

                    // Ghi chi tiết thông tin so sánh thời gian
                    Log::info("So sánh khung giờ {$timeSlot->id}", [
                        'slot_time' => $slotTime->format('H:i'),
                        'current_time' => $currentTime->format('H:i'),
                        'slot_hour' => $slotHour,
                        'current_hour' => $currentHour,
                        'slot_minute' => $slotMinute,
                        'current_minute' => $currentMinute,
                        'is_future' => ($slotHour > $currentHour || ($slotHour == $currentHour && $slotMinute > $currentMinute)) ? 'true' : 'false'
                    ]);

                    // So sánh theo giờ và phút
                    if ($slotHour > $currentHour) {
                        return true; // Giờ sau hiện tại
                    } elseif ($slotHour == $currentHour && $slotMinute > $currentMinute) {
                        return true; // Cùng giờ nhưng phút sau hiện tại
                    } else {
                        return false; // Thời gian đã qua
                    }
                }

                return true;
            })->map(function($timeSlot) {
                return [
                    'id' => $timeSlot->id,
                    'time' => $timeSlot->started_time,
                    'booked_count' => $timeSlot->booked_count,
                    'capacity' => $timeSlot->capacity,
                    'available_slots' => $timeSlot->available_slots
                ];
            })->values();

            Log::info('Các time slots khả dụng: ' . $availableSlots->count());

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách khung giờ khả dụng thành công',
                'note' => 'Mỗi khung giờ chỉ phục vụ một dịch vụ. Mỗi khách hàng chỉ đặt được 1 dịch vụ/khung giờ.',
                'available_slots' => $availableSlots,
                'total_slots' => $availableSlots->count(),
                'date_requested' => $request->date,
                'debug_info' => [
                    'is_today' => $isToday,
                    'current_time' => $currentTime->format('H:i:s'),
                    'timezone' => config('app.timezone'),
                    'timezone_vietnam' => 'Asia/Ho_Chi_Minh',
                    'local_time' => $currentTime->format('Y-m-d H:i:s'),
                    'total_time_slots' => $allTimeSlots->count(),
                    'booked_slots_count' => count($bookedTimeSlots),
                    'available_slots_count' => $availableSlots->count(),
                    'booked_slot_ids' => $bookedTimeSlots,
                    'booked_time_slot_services' => $bookedTimeSlotServices,
                    'requested_service_id' => $request->service_id,
                    'one_service_per_time_slot' => true
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy khung giờ khả dụng: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                'available_slots' => [],
                'error_type' => get_class($e),
                'error_line' => $e->getLine()
            ], 500);
        }
    }
}
