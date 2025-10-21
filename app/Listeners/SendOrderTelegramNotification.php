<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Notifications\NewOrderTelegram;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendOrderTelegramNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'high';

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

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
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(OrderPlaced $event, \Throwable $exception): void
    {
        Log::error('Telegram notification listener failed', [
            'order_id' => $event->order->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
