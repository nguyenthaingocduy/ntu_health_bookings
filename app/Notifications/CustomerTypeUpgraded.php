<?php

namespace App\Notifications;

use App\Models\CustomerType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerTypeUpgraded extends Notification implements ShouldQueue
{
    use Queueable;

    protected $customerType;

    /**
     * Create a new notification instance.
     */
    public function __construct(CustomerType $customerType)
    {
        $this->customerType = $customerType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Chúc mừng! Bạn đã được nâng cấp lên ' . $this->customerType->type_name)
            ->greeting('Xin chào ' . $notifiable->first_name . '!')
            ->line('Chúng tôi vui mừng thông báo rằng tài khoản của bạn đã được nâng cấp lên ' . $this->customerType->type_name . '.')
            ->line('Với tư cách là khách hàng ' . $this->customerType->type_name . ', bạn sẽ được hưởng các đặc quyền sau:')
            ->line('- Giảm giá ' . $this->customerType->formatted_discount . ' cho tất cả dịch vụ')
            ->line('- ' . $this->customerType->description)
            ->action('Xem tài khoản của bạn', url('/customer/profile'))
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
            'title' => 'Nâng cấp loại khách hàng',
            'message' => 'Bạn đã được nâng cấp lên ' . $this->customerType->type_name,
            'type' => 'customer_upgrade',
            'customer_type' => [
                'id' => $this->customerType->id,
                'name' => $this->customerType->type_name,
                'discount' => $this->customerType->discount_percentage,
                'color' => $this->customerType->color_code,
            ],
        ];
    }
}
