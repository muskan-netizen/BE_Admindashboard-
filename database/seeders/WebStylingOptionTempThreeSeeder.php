<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\WebStylingOption;

class WebStylingOptionTempThreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WebStylingOption::updateOrCreate([
        	'image' => 'template-three.png',
        ],[
            'web_styling_id' => 1,
            'name' => 'Home Page 3',
            'is_selected' => '0',
            'template_id' => '3',
        ]);

        WebStylingOption::updateOrCreate([
        	'image' => 'template-four.png',
        ],[
            'web_styling_id' => 1,
            'name' => 'Home Page 4',
            'is_selected' => '0',
            'template_id' => '4',
        ]);
    }
}
