<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;
    protected $type;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Appointment  $appointment
     * @param  string  $type  (created, confirmed, reminder, completed, cancelled)
     * @return void
     */
    public function __construct(Appointment $appointment, string $type)
    {
        $this->appointment = $appointment;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database']; // Always send to database
        
        // Check if email notifications are enabled for this user
        if ($notifiable->email_notifications_enabled) {
            // Check specific notification preferences based on type
            if ($this->type === 'confirmed' && $notifiable->notify_appointment_confirmation) {
                $channels[] = 'mail';
            } elseif ($this->type === 'reminder' && $notifiable->notify_appointment_reminder) {
                $channels[] = 'mail';
            } elseif ($this->type === 'cancelled' && $notifiable->notify_appointment_cancellation) {
                $channels[] = 'mail';
            } elseif ($this->type !== 'confirmed' && $this->type !== 'reminder' && $this->type !== 'cancelled') {
                // For other types like 'created' or 'completed', send email by default
                $channels[] = 'mail';
            }
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $service = $this->appointment->service;
        $date = date('d/m/Y', strtotime($this->appointment->date_appointments));
        $time = $this->appointment->timeAppointment ? $this->appointment->timeAppointment->started_time : 'N/A';
        
        $mailMessage = (new MailMessage)
            ->subject($this->getEmailSubject())
            ->greeting('Xin chào ' . $notifiable->first_name . '!');
            
        switch ($this->type) {
            case 'created':
                $mailMessage->line('Lịch hẹn của bạn đã được tạo và đang chờ xác nhận.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $service->name)
                    ->line('- Ngày hẹn: ' . $date)
                    ->line('- Giờ hẹn: ' . $time)
                    ->line('Chúng tôi sẽ thông báo cho bạn khi lịch hẹn được xác nhận.');
                break;
                
            case 'confirmed':
                $mailMessage->line('Lịch hẹn của bạn đã được xác nhận.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $service->name)
                    ->line('- Ngày hẹn: ' . $date)
                    ->line('- Giờ hẹn: ' . $time)
                    ->line('Vui lòng đến trước giờ hẹn 15 phút để làm thủ tục.');
                break;
                
            case 'reminder':
                $mailMessage->line('Nhắc nhở: Bạn có lịch hẹn vào ngày mai.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $service->name)
                    ->line('- Ngày hẹn: ' . $date)
                    ->line('- Giờ hẹn: ' . $time)
                    ->line('Vui lòng đến trước giờ hẹn 15 phút và mang theo giấy tờ tùy thân.');
                break;
                
            case 'completed':
                $mailMessage->line('Lịch hẹn của bạn đã hoàn thành.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $service->name)
                    ->line('- Ngày hẹn: ' . $date)
                    ->line('- Giờ hẹn: ' . $time)
                    ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.');
                break;
                
            case 'cancelled':
                $mailMessage->line('Lịch hẹn của bạn đã bị hủy.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $service->name)
                    ->line('- Ngày hẹn: ' . $date)
                    ->line('- Giờ hẹn: ' . $time);
                
                if ($this->appointment->cancellation_reason) {
                    $mailMessage->line('Lý do hủy: ' . $this->appointment->cancellation_reason);
                }
                
                $mailMessage->line('Bạn có thể đặt lịch hẹn mới trên hệ thống của chúng tôi.');
                break;
                
            default:
                $mailMessage->line('Có cập nhật về lịch hẹn của bạn.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $service->name)
                    ->line('- Ngày hẹn: ' . $date)
                    ->line('- Giờ hẹn: ' . $time);
        }
        
        return $mailMessage
            ->action('Xem chi tiết lịch hẹn', url('/customer/appointments/' . $this->appointment->id))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'service_id' => $this->appointment->service_id,
            'service_name' => $this->appointment->service->name,
            'date_appointments' => $this->appointment->date_appointments,
            'time_appointments' => $this->appointment->timeAppointment ? $this->appointment->timeAppointment->started_time : null,
            'status' => $this->appointment->status,
            'type' => $this->type,
            'message' => $this->getNotificationMessage(),
        ];
    }
    
    /**
     * Get the email subject based on type.
     *
     * @return string
     */
    protected function getEmailSubject(): string
    {
        $appName = config('app.name');
        
        switch ($this->type) {
            case 'created':
                return "[{$appName}] Lịch hẹn mới đã được tạo";
            case 'confirmed':
                return "[{$appName}] Lịch hẹn đã được xác nhận";
            case 'reminder':
                return "[{$appName}] Nhắc nhở lịch hẹn ngày mai";
            case 'completed':
                return "[{$appName}] Lịch hẹn đã hoàn thành";
            case 'cancelled':
                return "[{$appName}] Lịch hẹn đã bị hủy";
            default:
                return "[{$appName}] Cập nhật lịch hẹn";
        }
    }
    
    /**
     * Get the notification message based on type.
     *
     * @return string
     */
    protected function getNotificationMessage(): string
    {
        switch ($this->type) {
            case 'created':
                return 'Lịch hẹn của bạn đã được tạo và đang chờ xác nhận.';
            case 'confirmed':
                return 'Lịch hẹn của bạn đã được xác nhận.';
            case 'reminder':
                return 'Nhắc nhở: Bạn có lịch hẹn vào ngày mai.';
            case 'completed':
                return 'Lịch hẹn của bạn đã hoàn thành.';
            case 'cancelled':
                return 'Lịch hẹn của bạn đã bị hủy.';
            default:
                return 'Có cập nhật về lịch hẹn của bạn.';
        }
    }
}
