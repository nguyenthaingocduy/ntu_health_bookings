<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HealthCheckupAppointmentNotification extends Notification implements ShouldQueue
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
        $mailMessage = (new MailMessage)
            ->subject($this->getSubject())
            ->greeting('Xin chào ' . $notifiable->full_name . '!');

        switch ($this->type) {
            case 'created':
                $mailMessage->line('Lịch hẹn khám sức khỏe của bạn đã được tạo thành công và đang chờ xác nhận.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $this->appointment->service->name)
                    ->line('- Ngày khám: ' . $this->appointment->appointment_date->format('d/m/Y'))
                    ->line('- Giờ khám: ' . ($this->appointment->timeSlot ? $this->appointment->timeSlot->start_time->format('H:i') . ' - ' . $this->appointment->timeSlot->end_time->format('H:i') : 'Chưa xác định'))
                    ->line('Chúng tôi sẽ thông báo cho bạn khi lịch hẹn được xác nhận.')
                    ->action('Xem chi tiết lịch hẹn', url('/staff/health-checkups/' . $this->appointment->id));
                break;

            case 'confirmed':
                $mailMessage->line('Lịch hẹn khám sức khỏe của bạn đã được xác nhận.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $this->appointment->service->name)
                    ->line('- Ngày khám: ' . $this->appointment->appointment_date->format('d/m/Y'))
                    ->line('- Giờ khám: ' . ($this->appointment->timeSlot ? $this->appointment->timeSlot->start_time->format('H:i') . ' - ' . $this->appointment->timeSlot->end_time->format('H:i') : 'Chưa xác định'))
                    ->line('- Bác sĩ phụ trách: ' . ($this->appointment->doctor ? $this->appointment->doctor->full_name : 'Chưa phân công'))
                    ->line('Vui lòng đến đúng giờ để được phục vụ tốt nhất.')
                    ->action('Xem chi tiết lịch hẹn', url('/staff/health-checkups/' . $this->appointment->id));
                break;

            case 'reminder':
                $mailMessage->line('Nhắc nhở: Bạn có lịch hẹn khám sức khỏe vào ngày mai.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $this->appointment->service->name)
                    ->line('- Ngày khám: ' . $this->appointment->appointment_date->format('d/m/Y'))
                    ->line('- Giờ khám: ' . ($this->appointment->timeSlot ? $this->appointment->timeSlot->start_time->format('H:i') . ' - ' . $this->appointment->timeSlot->end_time->format('H:i') : 'Chưa xác định'))
                    ->line('- Bác sĩ phụ trách: ' . ($this->appointment->doctor ? $this->appointment->doctor->full_name : 'Chưa phân công'))
                    ->line('Vui lòng đến đúng giờ để được phục vụ tốt nhất.')
                    ->action('Xem chi tiết lịch hẹn', url('/staff/health-checkups/' . $this->appointment->id));
                break;

            case 'completed':
                $mailMessage->line('Lịch hẹn khám sức khỏe của bạn đã hoàn thành.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $this->appointment->service->name)
                    ->line('- Ngày khám: ' . $this->appointment->appointment_date->format('d/m/Y'))
                    ->line('- Bác sĩ phụ trách: ' . ($this->appointment->doctor ? $this->appointment->doctor->full_name : 'Không có thông tin'))
                    ->line('Bạn có thể xem kết quả khám sức khỏe của mình trong hồ sơ sức khỏe.')
                    ->action('Xem hồ sơ sức khỏe', url('/staff/health-checkups/records'));
                break;

            case 'cancelled':
                $mailMessage->line('Lịch hẹn khám sức khỏe của bạn đã bị hủy.')
                    ->line('Thông tin lịch hẹn:')
                    ->line('- Dịch vụ: ' . $this->appointment->service->name)
                    ->line('- Ngày khám: ' . $this->appointment->appointment_date->format('d/m/Y'))
                    ->line('- Giờ khám: ' . ($this->appointment->timeSlot ? $this->appointment->timeSlot->start_time->format('H:i') . ' - ' . $this->appointment->timeSlot->end_time->format('H:i') : 'Chưa xác định'))
                    ->line('Lý do hủy: ' . ($this->appointment->cancellation_reason ?? 'Không có thông tin'))
                    ->line('Bạn có thể đặt lịch hẹn mới nếu cần.')
                    ->action('Đặt lịch hẹn mới', url('/staff/health-checkups/create'));
                break;

            default:
                $mailMessage->line('Có cập nhật về lịch hẹn khám sức khỏe của bạn.')
                    ->line('Vui lòng kiểm tra thông tin chi tiết trong hệ thống.')
                    ->action('Xem chi tiết lịch hẹn', url('/staff/health-checkups/' . $this->appointment->id));
                break;
        }

        $mailMessage->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');

        return $mailMessage;
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
            'service_name' => $this->appointment->service->name,
            'appointment_date' => $this->appointment->appointment_date->format('d/m/Y'),
            'appointment_time' => $this->appointment->timeSlot ? $this->appointment->timeSlot->start_time->format('H:i') . ' - ' . $this->appointment->timeSlot->end_time->format('H:i') : null,
            'doctor_name' => $this->appointment->doctor ? $this->appointment->doctor->full_name : null,
            'status' => $this->appointment->status,
            'type' => $this->type,
            'message' => $this->getNotificationMessage(),
        ];
    }

    /**
     * Get the notification subject based on type.
     *
     * @return string
     */
    protected function getSubject(): string
    {
        switch ($this->type) {
            case 'created':
                return 'Lịch hẹn khám sức khỏe đã được tạo';
            case 'confirmed':
                return 'Lịch hẹn khám sức khỏe đã được xác nhận';
            case 'reminder':
                return 'Nhắc nhở: Lịch hẹn khám sức khỏe ngày mai';
            case 'completed':
                return 'Lịch hẹn khám sức khỏe đã hoàn thành';
            case 'cancelled':
                return 'Lịch hẹn khám sức khỏe đã bị hủy';
            default:
                return 'Cập nhật lịch hẹn khám sức khỏe';
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
                return 'Lịch hẹn khám sức khỏe của bạn đã được tạo và đang chờ xác nhận.';
            case 'confirmed':
                return 'Lịch hẹn khám sức khỏe của bạn đã được xác nhận.';
            case 'reminder':
                return 'Nhắc nhở: Bạn có lịch hẹn khám sức khỏe vào ngày mai.';
            case 'completed':
                return 'Lịch hẹn khám sức khỏe của bạn đã hoàn thành.';
            case 'cancelled':
                return 'Lịch hẹn khám sức khỏe của bạn đã bị hủy.';
            default:
                return 'Có cập nhật về lịch hẹn khám sức khỏe của bạn.';
        }
    }
}
