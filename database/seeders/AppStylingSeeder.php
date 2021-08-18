<?php

namespace Database\Seeders;
use DB;
use App\Models\{AppStyling,AppStylingOption};
use Illuminate\Database\Seeder;

class AppStylingSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('app_stylings')->truncate();    
        DB::table('app_styling_options')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');   
        $app_styling = AppStyling::insertGetId([
            'name' => 'Regular Font',
            'type' => '2'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'CircularStd-Book',
            'is_selected' => '1'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'SFProText-Regular',
            'is_selected' => '0'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Futura-Normal',
            'is_selected' => '0'
        ]);
        $app_styling = AppStyling::insertGetId([
            'name' => 'Medium Font',
            'type' => '2'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'CircularStd-Medium',
            'is_selected' => '1'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'SFProText-Medium',
            'is_selected' => '0'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Futura-Medium',
            'is_selected' => '0'
        ]);
        $app_styling = AppStyling::insertGetId([
            'name' => 'Bold Font',
            'type' => '2'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'CircularStd-Bold',
            'is_selected' => '1'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'SFProText-Bold',
            'is_selected' => '0'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'FuturaBT-Heavy',
            'is_selected' => '0'
        ]);
        $app_styling = AppStyling::insertGetId([
            'name' => 'Primary Color',
            'type' => '4'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => '#41A2E6',
            'is_selected' => '1'
        ]);
        $app_styling = AppStyling::insertGetId([
            'name' => 'Secondary Color',
            'type' => '4'
        ]);
        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => '#fff',
            'is_selected' => '1'
        ]);

        $app_styling = AppStyling::insertGetId([
            'name' => 'Tertiary Color',
            'type' => '4'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => '#fff',
            'is_selected' => '1'
        ]);

        $app_styling = AppStyling::insertGetId([
            'name' => 'Tab Bar Style',
            'type' => '3'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Tab 1',
            'image' => 'bar.png',
            'is_selected' => '1',
            'template_id' => '1',
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Tab 2',
            'image' => 'bar_two.png',
            'is_selected' => '0',
            'template_id' => '2',
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Tab 3',
            'image' => 'bar_three.png',
            'is_selected' => '0',
            'template_id' => '3',
        ]);

        $app_styling = AppStyling::insertGetId([
            'name' => 'Home Page Style',
            'type' => '3'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Home Page 1',
            'image' => 'home.png',
            'is_selected' => '1',
            'template_id' => '1',
        ]);

        // $app_styling_option = AppStylingOption::insert([
        //     'app_styling_id' => $app_styling,
        //     'name' => 'Home Page 2',
        //     'image' => 'home_two.png',
        //     'is_selected' => '0',
        //     'template_id' => '2',
        // ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Home Page 3',
            'image' => 'home_three.png',
            'is_selected' => '0',
            'template_id' => '3',
        ]);
    }
}
