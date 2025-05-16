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
     * Hiển thị trang phân công lịch làm việc
     */
    public function index()
    {
        try {
            // Lấy danh sách nhân viên kỹ thuật
            $technicians = User::whereHas('role', function($query) {
                $query->where('name', 'Technician');
            })->get();

            // Lấy danh sách khung giờ làm việc, loại bỏ trùng lặp
            $timeSlots = TimeSlot::select('id', 'start_time', 'end_time')
                ->orderBy('start_time')
                ->get()
                ->unique(function ($item) {
                    return $item->start_time . '-' . $item->end_time;
                });

            // Lấy ngày hiện tại và tạo mảng các ngày trong tuần
            $today = Carbon::today();
            $startOfWeek = $today->copy()->startOfWeek();
            $endOfWeek = $today->copy()->endOfWeek();

            $dates = [];
            for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
                $dates[] = $date->copy();
            }

            // Lấy lịch làm việc hiện tại
            $workSchedules = WorkSchedule::whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                ->get()
                ->groupBy(['user_id', 'date', 'time_slot_id']);

            return view('admin.work_schedules.index', compact('technicians', 'timeSlots', 'dates', 'workSchedules'));
        } catch (\Exception $e) {
            Log::error('Error in WorkScheduleController@index: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi tải lịch làm việc.');
        }
    }

    /**
     * Lưu phân công lịch làm việc
     */
    public function store(Request $request)
    {
        try {
            // Kiểm tra xem có dữ liệu schedules không
            if (!$request->has('schedules') || !is_array($request->schedules)) {
                return redirect()->back()
                    ->with('error', 'Vui lòng chọn ít nhất một khung giờ để phân công lịch làm việc.');
            }

            // Xác thực dữ liệu
            $validator = Validator::make($request->all(), [
                'schedules' => 'required|array',
                'schedules.*.user_id' => 'required|exists:users,id',
                'schedules.*.date' => 'required|date',
                'schedules.*.time_slot_id' => 'required|exists:time_slots,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.');
            }

            // Đếm số lịch làm việc đã được lưu
            $count = 0;

            foreach ($request->schedules as $schedule) {
                // Kiểm tra dữ liệu của mỗi lịch làm việc
                if (isset($schedule['user_id']) && isset($schedule['date']) && isset($schedule['time_slot_id'])) {
                    WorkSchedule::updateOrCreate(
                        [
                            'user_id' => $schedule['user_id'],
                            'date' => $schedule['date'],
                            'time_slot_id' => $schedule['time_slot_id'],
                        ],
                        [
                            'status' => 'scheduled',
                        ]
                    );
                    $count++;
                }
            }

            if ($count > 0) {
                return redirect()->route('admin.work-schedules.index')
                    ->with('success', "Đã lưu thành công $count lịch làm việc");
            } else {
                return redirect()->back()
                    ->with('error', 'Không có lịch làm việc nào được lưu. Vui lòng kiểm tra lại.');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi lưu lịch làm việc: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi lưu lịch làm việc: ' . $e->getMessage());
        }
    }

    /**
     * Xóa phân công lịch làm việc
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
        ]);

        WorkSchedule::where([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'time_slot_id' => $request->time_slot_id,
        ])->delete();

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

        $workSchedules = WorkSchedule::whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get()
            ->groupBy(['user_id', 'date', 'time_slot_id']);

        return view('admin.work_schedules.view_week', compact(
            'dates',
            'technicians',
            'timeSlots',
            'workSchedules',
            'previousWeek',
            'nextWeek'
        ));
    }
}
