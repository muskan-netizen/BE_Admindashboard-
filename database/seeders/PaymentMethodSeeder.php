<?php
namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;
class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

      $option_count = DB::table('payment_methods')->count();

      $payment_options = array(
            array('id' => '1','name' => __('Visa'),'image' => 'visa.png', 'slug' => 'visa', 'is_show' => '1'),
            array('id' => '2','name' => __('Discover'),'image' => 'discover.png', 'slug' => 'discover','is_show' => '1'),
            array('id' => '3','name' => __('American Express'),'image' => 'american-express.png','slug' => 'american-express', 'is_show' => '1'),
            array('id' => '4','name' => __('Master Card'),'image' => 'master.png','slug' => 'master', 'is_show' => '1'),
            array('id' => '5','name' => __('Mobile Money'),'image' => 'mobile-money.png','slug' => 'mobile-money', 'is_show' => '1'),
        );
       
      if($option_count == 0)
      {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payment_methods')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('payment_methods')->insert($payment_options);
      }
      else{
          foreach ($payment_options as $option) {
              $payop = PaymentMethod::where('id', $option['id'])->first();

              if ($payop !== null) {
                  $payop->update(['id' => $option['id'], 'name' => $option['name'],'image' => $option['image']]);
              } else {
                  $payop = PaymentMethod::create([
                    'id'      => $option['id'],
                    'name'    => $option['name'],
                    'image'   => $option['image'], 
                    'slug'    => $option['slug'], 
                    'is_show' => $option['is_show'],
                  ]);
              }
          }
      }
    }
}
