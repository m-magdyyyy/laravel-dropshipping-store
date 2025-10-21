<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Notifications\NewOrderTelegram;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendOrderTelegramNotification
{
    // Removed ShouldQueue to send notifications immediately
    // This fixes:
    // 1. Delay in sending notifications
    // 2. Duplicate notifications
    // 3. Queue worker dependency

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;

        // Prevent duplicate notifications using cache lock
        $cacheKey = "telegram_notification_sent_{$order->id}";
        if (cache()->has($cacheKey)) {
            Log::info('Telegram notification already sent for this order, skipping duplicate', [
                'order_id' => $order->id
            ]);
            return;
        }

        // Get Telegram chat IDs from config
        $chatIds = explode(',', config('services.telegram.recipients', ''));
        $chatIds = array_filter(array_map('trim', $chatIds));

        if (empty($chatIds)) {
            Log::warning('No Telegram recipients configured', [
                'order_id' => $order->id
            ]);
            return;
        }

        Log::info('Sending Telegram notification for order', [
            'order_id' => $order->id,
            'customer' => $order->customer_name,
            'recipients_count' => count($chatIds)
        ]);

        $successCount = 0;
        $failedCount = 0;
        $results = [];

        foreach ($chatIds as $chatId) {
            try {
                Notification::route('telegram', $chatId)
                    ->notify(new NewOrderTelegram($order));
                
                $results[$chatId] = 'success';
                $successCount++;
                
                Log::info('Telegram notification sent successfully', [
                    'order_id' => $order->id,
                    'chat_id' => $chatId
                ]);
            } catch (\Exception $e) {
                $results[$chatId] = 'failed';
                $failedCount++;
                
                Log::error('Telegram notification failed', [
                    'order_id' => $order->id,
                    'chat_id' => $chatId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Log final results
        Log::info('Telegram notifications completed', [
            'order_id' => $order->id,
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'total_count' => count($chatIds),
            'results' => $results
        ]);

        if ($successCount === 0 && count($chatIds) > 0) {
            Log::error('All Telegram notifications failed', [
                'order_id' => $order->id
            ]);
        } else if ($successCount > 0) {
            // Mark notification as sent to prevent duplicates (cache for 24 hours)
            cache()->put($cacheKey, true, now()->addDay());
            
            Log::info('Notification marked as sent', [
                'order_id' => $order->id,
                'cache_key' => $cacheKey
            ]);
        }
    }


}
