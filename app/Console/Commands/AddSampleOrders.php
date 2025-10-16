<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class AddSampleOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:sample';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add sample orders for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = [
            [
                'customer_name' => 'أحمد محمد علي',
                'phone' => '01012345678',
                'address' => 'القاهرة، مدينة نصر، شارع عباس العقاد، مبنى 15، الدور الثالث',
                'status' => 'pending'
            ],
            [
                'customer_name' => 'فاطمة أحمد',
                'phone' => '01123456789',
                'address' => 'الجيزة، المهندسين، شارع البطل أحمد عبد العزيز، برج النيل، شقة 12',
                'status' => 'confirmed'
            ],
            [
                'customer_name' => 'محمد حسن',
                'phone' => '01234567890',
                'address' => 'الإسكندرية، سيدي جابر، شارع سعد زغلول، عمارة 8، الدور الخامس',
                'status' => 'shipped'
            ],
            [
                'customer_name' => 'نورا عبدالرحمن',
                'phone' => '01098765432',
                'address' => 'القاهرة، التجمع الخامس، كمبوند دريم لاند، فيلا 45',
                'status' => 'delivered'
            ]
        ];

        foreach ($orders as $orderData) {
            Order::create($orderData);
        }

        $this->info('Sample orders created successfully!');
    }
}
