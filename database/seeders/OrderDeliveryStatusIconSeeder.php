<?php

namespace Database\Seeders;

use App\Models\OrderDeliveryStatusIcon;
use Illuminate\Database\Seeder;
use DB;

class OrderDeliveryStatusIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
     
        $count = DB::table('order_delivery_status_icon')->count();
        $options = array(
              array('id' => '1','name' => __('Step 1'),'image' => 'assets/icons/order_status_icons/deriver_1.png'),
              array('id' => '2','name' => __('Step 2'),'image' => 'assets/icons/order_status_icons/deriver_2.png'),
              array('id' => '3','name' => __('Step 3'),'image' => 'assets/icons/order_status_icons/deriver_3.png'),
              array('id' => '4','name' => __('Step 4'),'image' => 'assets/icons/order_status_icons/deriver_4.png'),
              array('id' => '5','name' => __('Step 5'),'image' => 'assets/icons/order_status_icons/deriver_5.png'),
              array('id' => '6','name' => __('Step 6'),'image' => 'assets/icons/order_status_icons/deriver_6.png')
          );
         
        if($count == 0)
        {
          DB::statement('SET FOREIGN_KEY_CHECKS=0;');
          DB::table('order_delivery_status_icon')->truncate();
          DB::statement('SET FOREIGN_KEY_CHECKS=1;');
  
          DB::table('order_delivery_status_icon')->insert($options);
        }
        else{
        
            foreach ($options as $option) {
                $check = OrderDeliveryStatusIcon::where('id', $option['id'])->first();
  
                if ($check !== null) {
                    $check->update(['id' => $option['id'], 'name' => $option['name']]);
                } else {
                    $payop = OrderDeliveryStatusIcon::create([
                      'id'      => $option['id'],
                      'name'    => $option['name'],
                      'image'   => $option['image']
                    ]);
                }
            }
        }
      }
}
