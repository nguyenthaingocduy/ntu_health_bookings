<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WorkSchedule;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WorkScheduleController extends Controller
{
    /**
     * Các ngày trong tuần
     */
    protected $daysOfWeek = [
        1 => 'Thứ hai',
        2 => 'Thứ ba',
        3 => 'Thứ tư',
        4 => 'Thứ năm',
        5 => 'Thứ sáu',
        6 => 'Thứ bảy',
        0 => 'Chủ nhật',
    ];
    /**
     * Phương thức index chuyển hướng đến trang phân công lịch làm việc theo tuần
     * Đã được xử lý bằng route redirect trong routes/admin/work_schedules.php
     */

    /**
     * Xóa phân công lịch làm việc
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'type' => 'required|in:morning,afternoon,full-day',
        ]);

        // Lấy danh sách khung giờ
        $timeSlots = TimeSlot::orderBy('start_time')->get();

        if ($request->type === 'full-day') {
            // Nếu là cả ngày, xóa tất cả các khung giờ
            WorkSchedule::where([
                'user_id' => $request->user_id,
                'date' => $request->date,
            ])->delete();
        } elseif ($request->type === 'morning') {
            // Nếu là buổi sáng, xóa các khung giờ sáng
            foreach ($timeSlots as $timeSlot) {
                $startTime = is_string($timeSlot->start_time) ? $timeSlot->start_time : $timeSlot->start_time->format('H:i:s');
                if (strtotime($startTime) < strtotime('12:00:00')) {
                    WorkSchedule::where([
                        'user_id' => $request->user_id,
                        'date' => $request->date,
                        'time_slot_id' => $timeSlot->id,
                    ])->delete();
                }
            }
        } elseif ($request->type === 'afternoon') {
            // Nếu là buổi chiều, xóa các khung giờ chiều
            foreach ($timeSlots as $timeSlot) {
                $startTime = is_string($timeSlot->start_time) ? $timeSlot->start_time : $timeSlot->start_time->format('H:i:s');
                if (strtotime($startTime) >= strtotime('12:00:00')) {
                    WorkSchedule::where([
                        'user_id' => $request->user_id,
                        'date' => $request->date,
                        'time_slot_id' => $timeSlot->id,
                    ])->delete();
                }
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Hiển thị trang xem lịch làm việc theo tuần
     */
    public function viewWeek(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        $previousWeek = $startOfWeek->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $startOfWeek->copy()->addWeek()->format('Y-m-d');

        $dates = [];
        for ($day = $startOfWeek; $day->lte($endOfWeek); $day->addDay()) {
            $dates[] = $day->copy();
        }

        $technicians = User::whereHas('role', function($query) {
            $query->where('name', 'Technician');
        })->get();
        $timeSlots = TimeSlot::orderBy('start_time')->get();

        // Lấy lịch làm việc và ghi log để debug
        $workSchedulesQuery = WorkSchedule::whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);

        // Ghi log số lượng bản ghi tìm thấy
        $count = $workSchedulesQuery->count();
        Log::info("Tìm thấy $count lịch làm việc trong khoảng từ {$startOfWeek->format('Y-m-d')} đến {$endOfWeek->format('Y-m-d')}");

        // Lấy dữ liệu và nhóm theo user_id, date, time_slot_id
        $workSchedules = $workSchedulesQuery->get()->groupBy(['user_id', 'date', 'time_slot_id']);

        // Ghi log cấu trúc dữ liệu để debug
        Log::info("Cấu trúc dữ liệu workSchedules:", [
            'has_data' => !$workSchedules->isEmpty(),
            'user_ids' => $workSchedules->keys()->toArray(),
            'first_user' => $workSchedules->isNotEmpty() ? $workSchedules->keys()->first() : null
        ]);

        return view('admin.work_schedules.view_week', compact(
            'dates',
            'technicians',
            'timeSlots',
            'workSchedules',
            'previousWeek',
            'nextWeek'
        ));
    }

    /**
     * Hiển thị trang phân công lịch làm việc theo ngày trong tuần
     */
    public function weeklyAssignment()
    {
        try {
            // Lấy danh sách nhân viên kỹ thuật
            $technicians = User::whereHas('role', function($query) {
                $query->where('name', 'Technician');
            })->get();

            // Lấy danh sách khung giờ làm việc
            $timeSlots = TimeSlot::select('id', 'start_time', 'end_time')
                ->orderBy('start_time')
                ->get()
                ->unique(function ($item) {
                    return $item->start_time . '-' . $item->end_time;
                });

            // Lấy lịch làm việc hiện tại
            $workSchedules = WorkSchedule::all()
                ->groupBy(['user_id', 'date']);

            // Chuẩn bị dữ liệu cho view
            $daysOfWeek = $this->daysOfWeek;

            return view('admin.work_schedules.weekly_assignment', compact(
                'technicians',
                'timeSlots',
                'workSchedules',
                'daysOfWeek'
            ));
        } catch (\Exception $e) {
            Log::error('Error in WorkScheduleController@weeklyAssignment: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi tải trang phân công lịch làm việc: ' . $e->getMessage());
        }
    }

    /**
     * Lưu phân công lịch làm việc theo ngày trong tuần
     */
    public function storeWeeklyAssignment(Request $request)
    {
        try {
            // Xác thực dữ liệu
            $validator = Validator::make($request->all(), [
                'technician_id' => 'required|exists:users,id',
                'days' => 'required|array',
                'days.*' => 'boolean',
                'start_time' => 'required|array',
                'start_time.*' => 'required|date_format:H:i',
                'end_time' => 'required|array',
                'end_time.*' => 'required|date_format:H:i',
                'max_customers' => 'required|array',
                'max_customers.*' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.');
            }

            $technicianId = $request->technician_id;
            $days = $request->days;
            $startTimes = $request->start_time;
            $endTimes = $request->end_time;
            $maxCustomers = $request->max_customers;

            // Xóa tất cả lịch làm việc hiện tại của nhân viên
            WorkSchedule::where('user_id', $technicianId)->delete();

            // Tạo lịch làm việc mới
            $count = 0;
            foreach ($days as $dayOfWeek => $isWorkingDay) {
                if ($isWorkingDay) {
                    // Tạo lịch làm việc cho ngày này
                    $startTime = $startTimes[$dayOfWeek];
                    $endTime = $endTimes[$dayOfWeek];
                    $maxCustomer = $maxCustomers[$dayOfWeek];

                    // Tìm hoặc tạo khung giờ phù hợp
                    $timeSlot = TimeSlot::where([
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'day_of_week' => $dayOfWeek,
                    ])->first();

                    if (!$timeSlot) {
                        $timeSlot = TimeSlot::create([
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'day_of_week' => $dayOfWeek,
                            'is_available' => true,
                            'max_appointments' => $maxCustomer,
                        ]);

                        // Ghi log để debug
                        Log::info("Đã tạo khung giờ mới:", [
                            'id' => $timeSlot->id,
                            'start_time' => $timeSlot->start_time,
                            'end_time' => $timeSlot->end_time,
                            'day_of_week' => $timeSlot->day_of_week
                        ]);
                    } else {
                        // Cập nhật thông tin khung giờ
                        $timeSlot->max_appointments = $maxCustomer;
                        $timeSlot->is_available = true;
                        $timeSlot->save();

                        // Ghi log để debug
                        Log::info("Đã cập nhật khung giờ:", [
                            'id' => $timeSlot->id,
                            'start_time' => $timeSlot->start_time,
                            'end_time' => $timeSlot->end_time,
                            'day_of_week' => $timeSlot->day_of_week
                        ]);
                    }

                    // Tạo lịch làm việc cho 4 tuần tới
                    $today = Carbon::today();
                    $currentDayOfWeek = $today->dayOfWeek;

                    // Tính ngày đầu tiên của loại ngày này
                    $daysToAdd = ($dayOfWeek - $currentDayOfWeek + 7) % 7;
                    $firstDate = $today->copy()->addDays($daysToAdd);

                    // Tạo lịch cho 4 tuần
                    for ($week = 0; $week < 4; $week++) {
                        $date = $firstDate->copy()->addWeeks($week);

                        try {
                            $workSchedule = WorkSchedule::create([
                                'user_id' => $technicianId,
                                'date' => $date->format('Y-m-d'),
                                'time_slot_id' => $timeSlot->id,
                                'status' => 'scheduled',
                                'notes' => 'Lịch làm việc tự động'
                            ]);

                            // Ghi log để debug
                            Log::info("Đã tạo lịch làm việc:", [
                                'id' => $workSchedule->id,
                                'user_id' => $workSchedule->user_id,
                                'date' => $workSchedule->date->format('Y-m-d'),
                                'time_slot_id' => $workSchedule->time_slot_id,
                                'status' => $workSchedule->status
                            ]);

                            $count++;
                        } catch (\Exception $e) {
                            Log::error("Lỗi khi tạo lịch làm việc: " . $e->getMessage(), [
                                'user_id' => $technicianId,
                                'date' => $date->format('Y-m-d'),
                                'time_slot_id' => $timeSlot->id
                            ]);
                        }
                    }
                }
            }

            if ($count > 0) {
                // Chuyển hướng đến trang xem lịch làm việc thay vì trang phân công
                return redirect()->route('admin.work-schedules.view-week')
                    ->with('success', "Đã lưu thành công lịch làm việc cho " . $count . " ngày");
            } else {
                return redirect()->back()
                    ->with('error', 'Không có lịch làm việc nào được lưu. Vui lòng kiểm tra lại.');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi lưu lịch làm việc theo tuần: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi lưu lịch làm việc: ' . $e->getMessage());
        }
    }
}
