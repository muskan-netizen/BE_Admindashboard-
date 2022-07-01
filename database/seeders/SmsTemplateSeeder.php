<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsTemplate;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SmsTemplate::updateOrCreate(['id' => 1],['label' => "Order Placed Successfully", 'slug' => 'order-place-Successfully', 'subject' => "Order Placed Successfully", 'content' => "Hi {user_name} Your order of amount {amount} for order number {order_number}", 'tags' => "{user_name},{amount},{order_number}"]);
    }
}
