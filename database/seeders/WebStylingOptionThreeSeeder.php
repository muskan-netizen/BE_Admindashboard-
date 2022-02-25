<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\WebStylingOption;

<<<<<<< HEAD:database/seeders/WebStylingOptionTempThreeSeeder.php
class WebStylingOptionTempThreeSeeder extends Seeder
=======
class WebStylingOptionThreeSeeder extends Seeder
>>>>>>> f9223c08b9b174a9f199d7c83588054c52c74356:database/seeders/WebStylingOptionThreeSeeder.php
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_styling_option = WebStylingOption::updateOrCreate([
        	'image' => 'template-three.png',
        ],[
            'web_styling_id' => 1,
            'name' => 'Home Page 3',
            'is_selected' => '0',
            'template_id' => '3',
        ]);
    }
}
