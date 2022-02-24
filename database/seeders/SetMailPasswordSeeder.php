<?php

namespace Database\Seeders;
use DB;
use App\Models\{ClientPreference};
use Illuminate\Database\Seeder;

class SetMailPasswordSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
       

      
        $update_pass = ClientPreference::where('mail_username','noreply@royoorders2.com')->update(['mail_password' => 'bbc693b9921c114cc743a597fb53b7d1-c3d1d1eb-e107f155']);


     
    }
}
