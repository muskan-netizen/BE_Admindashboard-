<?php
namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;

class PaymentOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){ 
      DB::table('payment_options')->truncate();
      $payment_options = array(
        array('id' => '1','code' => 'cod','path' => '','title' => 'Cash On Delivery', 'off_site' => '0', 'status' => '0'),
        // array('id' => '2','code' => 'loyalty-points','path' => '','title' => 'loyalty Points', 'offsite' => '0', 'status' => '1'),
        array('id' => '3', 'path' => 'omnipay/paypal', 'code' => 'paypal',  'title' => 'PayPal', 'off_site' => '1', 'status' => '0'),
        array('id' => '4', 'path' => 'omnipay/stripe', 'code' => 'stripe', 'title' => 'Stripe', 'off_site' => '0', 'status' => '0'),
        array('id' => '5', 'path' => 'paystackhq/omnipay-paystack', 'code' => 'paystack', 'title' => 'Paystack', 'off_site' => '0', 'status' => '0')
      ); 
      DB::table('payment_options')->insert($payment_options);
    }
}