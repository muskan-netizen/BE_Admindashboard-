<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\ClientPreference;
use DB;

class SmsProviderTwilioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClientPreference::update(['sms_provider' => 1]);
    }
}
