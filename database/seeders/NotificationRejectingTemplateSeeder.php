<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;
use Illuminate\Support\Str;

class NotificationRejectingTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!NotificationTemplate::find(11)){
            NotificationTemplate::updateOrCreate(['id' => 11],['label' => "Order Rejecte (Vendor)", 'slug' => 'order-rejected-vendor', 'subject' => "Order Rejecting", 'content' => "Your order ({order_id}) has been rejecting after 5 min", 'tags' => "{order_id}"]);
        }

    }
}
