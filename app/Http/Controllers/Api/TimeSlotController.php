<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;

class TimeSlotController extends Controller
{
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
            $validated = $request->validate([
                'service_id' => 'required|exists:services,id',
                'date' => 'required|date|after_or_equal:today',
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
            $bookedTimeSlots = Appointment::where('date_appointments', $request->date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->pluck('time_appointments_id')
                ->toArray();
            
            Log::info('Các time slots đã đặt: ' . count($bookedTimeSlots), [
                'slot_ids' => $bookedTimeSlots
            ]);
            
            // Lọc ra các khung giờ còn trống và nếu là ngày hôm nay, chỉ lấy các giờ trong tương lai
            $availableSlots = $allTimeSlots->filter(function($timeSlot) use ($bookedTimeSlots, $isToday, $currentTime) {
                // Kiểm tra nếu khung giờ đã bị đặt
                if (in_array($timeSlot->id, $bookedTimeSlots)) {
                    Log::info("Khung giờ {$timeSlot->id} đã được đặt", ['time' => $timeSlot->started_time]);
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
                ];
            })->values();
            
            Log::info('Các time slots khả dụng: ' . $availableSlots->count());
            
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách khung giờ khả dụng thành công',
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
                    'booked_slot_ids' => $bookedTimeSlots
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
