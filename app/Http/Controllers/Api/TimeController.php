<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;
use Carbon\Carbon;

class TimeController extends Controller
{
    /**
     * Get all available times for a specific date
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAllTimes(Request $request)
    {
        // Validate request
        $request->validate([
            'date' => 'nullable|date',
        ]);

        // Get date from request or use today
        // Biến $date được giữ lại để sử dụng trong tương lai nếu cần lọc theo ngày
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // Get all times
        $times = Time::orderBy('started_time')
            ->get();

        // Loại bỏ các khung giờ trùng lặp bằng cách sử dụng unique() trên started_time
        $uniqueTimes = $times->unique('started_time');

        // Chuyển collection thành mảng và đánh số lại các key
        $uniqueTimes = $uniqueTimes->values();

        // Return times as JSON
        return response()->json($uniqueTimes);
    }
}
