<?php

namespace Tests\Feature;

use App\Events\OrderPlaced;
use App\Listeners\SendOrderPlacedWhatsApp;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test WhatsApp notification is sent when order is created
     */
    public function test_whatsapp_notification_sent_on_order_creation(): void
    {
        // Fake HTTP requests
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messaging_product' => 'whatsapp',
                'contacts' => [
                    ['input' => '201091450342', 'wa_id' => '201091450342']
                ],
                'messages' => [
                    ['id' => 'wamid.test123456']
                ]
            ], 200)
        ]);

        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test Description',
            'price' => 100.00,
            'is_active' => true,
        ]);

        // Create an order
        $order = Order::create([
            'product_id' => $product->id,
            'quantity' => 2,
            'customer_name' => 'أحمد محمد',
            'phone' => '01091450342',
            'address' => 'القاهرة',
            'governorate' => 'القاهرة',
            'status' => Order::STATUS_PENDING,
        ]);

        // Assert HTTP request was made
        Http::assertSent(function ($request) use ($order) {
            // Check endpoint
            $isCorrectEndpoint = str_contains($request->url(), 'graph.facebook.com');
            
            // Check payload structure
            $data = $request->data();
            $hasCorrectProduct = $data['messaging_product'] === 'whatsapp';
            $hasRecipient = isset($data['to']);
            $hasTemplate = $data['type'] === 'template';
            
            // Check template name
            $templateName = $data['template']['name'] ?? null;
            $isCorrectTemplate = $templateName === config('services.whatsapp.template');
            
            // Check template parameters
            $components = $data['template']['components'] ?? [];
            $hasBodyComponent = !empty($components) && $components[0]['type'] === 'body';
            
            if ($hasBodyComponent) {
                $params = $components[0]['parameters'] ?? [];
                // Template order: {{1}} = Order ID, {{2}} = Total, {{3}} = Customer Name
                $hasOrderId = isset($params[0]) && $params[0]['text'] == $order->id;
                $hasTotal = isset($params[1]);
                $hasCustomerName = isset($params[2]) && $params[2]['text'] === $order->customer_name;
                
                return $isCorrectEndpoint 
                    && $hasCorrectProduct 
                    && $hasRecipient 
                    && $hasTemplate 
                    && $isCorrectTemplate
                    && $hasOrderId
                    && $hasTotal
                    && $hasCustomerName;
            }
            
            return false;
        });
    }

    /**
     * Test WhatsApp service handles API errors gracefully
     */
    public function test_whatsapp_service_handles_errors_gracefully(): void
    {
        // Fake HTTP error response
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'error' => [
                    'message' => 'Invalid parameter',
                    'type' => 'OAuthException',
                    'code' => 100
                ]
            ], 400)
        ]);

        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product-error',
            'description' => 'Test Description',
            'price' => 150.00,
            'is_active' => true,
        ]);

        // Create an order - should not throw exception
        $order = Order::create([
            'product_id' => $product->id,
            'quantity' => 1,
            'customer_name' => 'محمود علي',
            'phone' => '01069430567',
            'address' => 'الجيزة',
            'governorate' => 'الجيزة',
            'status' => Order::STATUS_PENDING,
        ]);

        // Assert order was created successfully despite WhatsApp error
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_name' => 'محمود علي',
        ]);

        // Assert HTTP request was attempted
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'graph.facebook.com');
        });
    }

    /**
     * Test WhatsApp notification includes correct order data
     */
    public function test_whatsapp_notification_contains_correct_order_data(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response(['messages' => [['id' => 'test']]], 200)
        ]);

        $product = Product::create([
            'name' => 'Premium Product',
            'slug' => 'premium-product',
            'description' => 'Premium Description',
            'price' => 500.00,
            'is_active' => true,
        ]);

        $order = Order::create([
            'product_id' => $product->id,
            'quantity' => 3,
            'customer_name' => 'فاطمة أحمد',
            'phone' => '01234567890',
            'address' => 'الإسكندرية',
            'governorate' => 'الإسكندرية',
            'status' => Order::STATUS_PENDING,
        ]);

        $expectedTotal = $product->price * $order->quantity;

        Http::assertSent(function ($request) use ($order, $expectedTotal) {
            $data = $request->data();
            $params = $data['template']['components'][0]['parameters'] ?? [];
            
            // Template order: {{1}} = Order ID, {{2}} = Total, {{3}} = Customer Name
            return $params[0]['text'] == $order->id
                && str_contains($params[1]['text'], (string) $expectedTotal)
                && $params[2]['text'] === $order->customer_name;
        });
    }
}
