<?php

namespace Database\Seeders;
use App\Models\AppStyling;
use App\Models\AppStylingOption;
use Illuminate\Database\Seeder;

class AddAppStylingFontFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){ 
        $app_styling = AppStyling::where([
            'name' => 'Regular Font',
            'type' => '2'
        ])->first();
        if(!empty($app_styling)){
           $exist =  AppStylingOption::where([
                'app_styling_id' => $app_styling->id,
                'name' => 'Poppins-Regular',
            ])->first();
            if(empty($exist)){
                $app_styling_option = AppStylingOption::insert([
                    'app_styling_id' => $app_styling->id,
                    'name' => 'Poppins-Regular',
                    'is_selected' => '0'
                ]);
            }
        }
        $app_styling = AppStyling::where([
            'name' => 'Medium Font',
            'type' => '2'
        ])->first();
        if(!empty($app_styling)){
            $exist =  AppStylingOption::where([
                'app_styling_id' => $app_styling->id,
                'name' => 'Poppins-Medium',
                ])->first();
            if(empty($exist)){
                $app_styling_option = AppStylingOption::insert([
                    'app_styling_id' => $app_styling->id,
                    'name' => 'Poppins-Medium',
                    'is_selected' => '0'
                ]);
            }
        }
        $app_styling = AppStyling::where([
            'name' => 'Bold Font',
            'type' => '2'
        ])->first();
        if(!empty($app_styling)){
            $exist =  AppStylingOption::where([
                'app_styling_id' => $app_styling->id,
                'name' => 'Poppins-Bold',
                ])->first();
            if(empty($exist)){
                $app_styling_option = AppStylingOption::insert([
                    'app_styling_id' => $app_styling->id,
                    'name' => 'Poppins-Bold',
                    'is_selected' => '0'
                ]);
            }
        }
    }
}
