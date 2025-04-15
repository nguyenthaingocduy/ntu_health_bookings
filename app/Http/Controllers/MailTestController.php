<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\UserRegistrationMail;
use App\Mail\AppointmentConfirmationMail;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class MailTestController extends Controller
{
    public function index()
    {
        return view('mail.test');
    }
    
    public function sendTestMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'type' => 'required|in:simple,registration,appointment'
        ]);
        
        $email = $request->email;
        $type = $request->type;
        
        try {
            switch ($type) {
                case 'simple':
                    // Gửi email đơn giản
                    Mail::raw('Đây là email kiểm tra từ NTU Health Booking vào ' . now(), function ($message) use ($email) {
                        $message->to($email)
                                ->subject('Test Email từ NTU Health Booking');
                    });
                    $message = 'Đã gửi email đơn giản đến ' . $email;
                    break;
                
                case 'registration':
                    // Lấy người dùng hiện tại hoặc đầu tiên trong database để test
                    $user = Auth::user() ?: User::first();
                    if (!$user) {
                        return back()->with('error', 'Không tìm thấy người dùng để test');
                    }
                    
                    Mail::to($email)->send(new UserRegistrationMail($user));
                    $message = 'Đã gửi email đăng ký mẫu đến ' . $email;
                    break;
                
                case 'appointment':
                    // Lấy appointment đầu tiên trong database để test
                    $appointment = Appointment::with(['service', 'customer', 'timeAppointment'])->first();
                    if (!$appointment) {
                        return back()->with('error', 'Không tìm thấy lịch hẹn để test');
                    }
                    
                    Mail::to($email)->send(new AppointmentConfirmationMail($appointment));
                    $message = 'Đã gửi email xác nhận đặt lịch mẫu đến ' . $email;
                    break;
            }
            
            Log::info($message);
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email: ' . $e->getMessage());
            return back()->with('error', 'Không thể gửi email: ' . $e->getMessage());
        }
    }
}
