<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingOption;
use DB;

class ShippingOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$option_count = DB::table('shipping_options')->count();

      	$shipping_options = array(
        	array('id' => '1', 'path' => '', 'code' => 'shiprocket',  'title' => 'ShipRocket', 'status' => '0'),
      	); 

      	if($option_count == 0)
      	{
        	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        	DB::table('payment_options')->truncate();
        	DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        	DB::table('shipping_options')->insert($shipping_options);
      	}
      else{
          foreach ($shipping_options as $option) {
              $shipop = ShippingOption::where('code', $option['code'])->first();
 
              if ($shipop !== null) {
                  $shipop->update(['title' => $option['title']]);
              } else {
                  $shipop = ShipmentOption::create([
                    'title' => $option['title'],
                    'code' => $option['code'],
                    'path' => $option['path'],
                    'status' => $option['status'],
                  ]);
              }
          }
      }
    }
}
