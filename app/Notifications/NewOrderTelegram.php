<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class NewOrderTelegram extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Order $order
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * Get the Telegram representation of the notification.
     */
    public function toTelegram(object $notifiable): TelegramMessage
    {
        $orderId = $this->order->id;
        $totalPrice = $this->order->formatted_total_price;
        $customerName = $this->order->customer_name;
        
        // Generate admin URL for the order
        $url = url("/admin/orders/{$orderId}");

        $message = "🎉 *طلب جديد في فكرة ستور!* 🎉\n\n";
        $message .= "*رقم الطلب:* {$orderId}\n";
        $message .= "*إجمالي المبلغ:* {$totalPrice}\n";
        $message .= "*اسم العميل:* {$customerName}";

        return TelegramMessage::create()
            ->to($notifiable->routes['telegram'])
            ->content($message)
            ->button('عرض الطلب', $url);
    }
}
