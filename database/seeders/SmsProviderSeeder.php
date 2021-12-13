<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class SmsProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sms_providers')->delete();
 
        $maps = array(
            array(
                'id' => 1,
                'provider' => 'Twilio Service',
                'keyword' => 'twilio',
                'status' => '1'
            ),
            array(
                'id' => 2,
                'provider' => 'mTalkz Service',
                'keyword' => 'mTalkz',
                'status' => '1'
            ),
            array(
                'id' => 3,
                'provider' => 'Mazinhost Service',
                'keyword' => 'mazinhost',
                'status' => '1'
            ),
        ); 
        \DB::table('sms_providers')->insert($maps);
    }
}
