<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class AppStylingAddOptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('app_styling_options')->insert(array (
            0 => 
            array (
                'app_styling_id' => 8,
                'name' => 'Home Page 7',
                'image' => 'home_seven.png',
                'template_id' => 5,
                'is_selected' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
    }
}
