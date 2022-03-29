<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\AppStylingOption;

class AppStylingAddOptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_styling_option = AppStylingOption::updateOrCreate([ 
            'image' => 'home_seven.png',
        ],[
            'app_styling_id' => 7,
            'name' => 'Home Page 7',
            'template_id' => 5,
            'is_selected' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ]);

        $app_styling_option = AppStylingOption::updateOrCreate([ 
            'image' => 'home_eight.png',
        ],[
            'app_styling_id' => 8,
            'name' => 'Home Page 8',
            'template_id' => 6,
            'is_selected' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ]);
    }
}
