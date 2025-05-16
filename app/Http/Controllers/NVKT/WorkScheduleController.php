<?php

namespace App\Http\Controllers\NVKT;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WorkScheduleController extends Controller
{
    /**
     * Hiển thị lịch làm việc của nhân viên kỹ thuật
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            // Lấy ngày từ request hoặc sử dụng ngày hiện tại
            $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek = $date->copy()->endOfWeek();
            
            // Tạo mảng các ngày trong tuần
            $dates = [];
            for ($day = 0; $day < 7; $day++) {
                $dates[] = $startOfWeek->copy()->addDays($day);
            }
            
            // Lấy danh sách khung giờ làm việc, loại bỏ trùng lặp
            $timeSlots = TimeSlot::select('id', 'start_time', 'end_time')
                ->orderBy('start_time')
                ->get()
                ->unique(function ($item) {
                    return $item->start_time . '-' . $item->end_time;
                });
            
            // Lấy lịch làm việc của nhân viên kỹ thuật hiện tại
            $workSchedules = WorkSchedule::where('user_id', Auth::id())
                ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                ->with('timeSlot')
                ->get()
                ->groupBy(['date', 'time_slot_id']);
            
            // Tạo dữ liệu lịch làm việc theo định dạng ngày và khung giờ
            $schedule = [];
            
            foreach ($dates as $date) {
                $dateStr = $date->format('Y-m-d');
                $schedule[$dateStr] = [
                    'date' => $date,
                    'time_slots' => []
                ];
                
                foreach ($timeSlots as $timeSlot) {
                    $isScheduled = isset($workSchedules[$dateStr][$timeSlot->id]);
                    
                    $startTime = is_string($timeSlot->start_time) 
                        ? substr($timeSlot->start_time, 0, 5) 
                        : $timeSlot->start_time->format('H:i');
                        
                    $endTime = is_string($timeSlot->end_time) 
                        ? substr($timeSlot->end_time, 0, 5) 
                        : $timeSlot->end_time->format('H:i');
                    
                    $schedule[$dateStr]['time_slots'][] = [
                        'id' => $timeSlot->id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'is_scheduled' => $isScheduled,
                        'work_schedule' => $isScheduled ? $workSchedules[$dateStr][$timeSlot->id]->first() : null
                    ];
                }
            }
            
            return view('nvkt.work_schedules.index', compact('schedule', 'startOfWeek', 'endOfWeek', 'dates'));
            
        } catch (\Exception $e) {
            Log::error('Error in NVKT WorkScheduleController@index: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi tải lịch làm việc: ' . $e->getMessage());
        }
    }
}
