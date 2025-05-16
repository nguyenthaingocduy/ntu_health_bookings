<?php

namespace App\Helpers;

class TimeHelper
{
    /**
     * Định dạng thời gian để hiển thị
     * Đảm bảo trả về chuỗi thông thường không có ký tự Unicode đặc biệt
     *
     * @param string $time Thời gian cần định dạng (HH:MM:SS hoặc HH:MM)
     * @return string Thời gian đã định dạng (HH:MM)
     */
    public static function formatTime($time)
    {
        // Lấy giờ và phút từ chuỗi thời gian
        $parts = explode(':', $time);
        if (count($parts) >= 2) {
            $hour = $parts[0];
            $minute = $parts[1];
            
            // Đảm bảo giờ và phút là số nguyên
            $hour = intval($hour);
            $minute = intval($minute);
            
            // Định dạng lại thời gian
            $formattedTime = sprintf('%02d:%02d', $hour, $minute);
            
            // Loại bỏ bất kỳ ký tự Unicode đặc biệt nào
            $formattedTime = preg_replace('/[^\x20-\x7E]/','', $formattedTime);
            
            return $formattedTime;
        }
        
        // Nếu không thể phân tích cú pháp, trả về chuỗi gốc sau khi loại bỏ ký tự đặc biệt
        return preg_replace('/[^\x20-\x7E]/','', $time);
    }
    
    /**
     * Định dạng khoảng thời gian để hiển thị
     * Đảm bảo trả về chuỗi thông thường không có ký tự Unicode đặc biệt
     *
     * @param string $startTime Thời gian bắt đầu (HH:MM:SS hoặc HH:MM)
     * @param string $endTime Thời gian kết thúc (HH:MM:SS hoặc HH:MM)
     * @return string Khoảng thời gian đã định dạng (HH:MM - HH:MM)
     */
    public static function formatTimeRange($startTime, $endTime)
    {
        $formattedStartTime = self::formatTime($startTime);
        $formattedEndTime = self::formatTime($endTime);
        
        $timeRange = $formattedStartTime . ' - ' . $formattedEndTime;
        
        // Loại bỏ bất kỳ ký tự Unicode đặc biệt nào
        $timeRange = preg_replace('/[^\x20-\x7E]/','', $timeRange);
        
        return $timeRange;
    }
}
