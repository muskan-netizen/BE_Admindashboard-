<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use App\Models\{WebStyling,WebStylingOption};

class WebStylingOptionSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $web_styling = WebStyling::where(['name' => 'Home Page Style', 'type' => '1'])->value('id');
        if($web_styling > 0){
            $web_styling_options_count = DB::table('web_styling_options')->count();
            $web_styling_options = array(
                array('web_styling_id' => $web_styling, 'name' => 'Home Page 1', 'is_selected' => '0', 'template_id' => '1', 'image' => 'template-one.png'),
                array('web_styling_id' => $web_styling, 'name' => 'Home Page 2', 'is_selected' => '0', 'template_id' => '2', 'image' => 'template-two.png'),
                array('web_styling_id' => $web_styling, 'name' => 'Food Delivery', 'is_selected' => '0', 'template_id' => '3', 'image' => 'template-three.png'),
                array('web_styling_id' => $web_styling, 'name' => 'E-Commerce', 'is_selected' => '0', 'template_id' => '4', 'image' => 'template-four.png'),
                array('web_styling_id' => $web_styling, 'name' => 'Pickup & Drop', 'is_selected' => '0', 'template_id' => '5', 'image' => 'template-five.png'),
                array('web_styling_id' => $web_styling, 'name' => 'On Demand Service', 'is_selected' => '0', 'template_id' => '6', 'image' => 'template-six.png')
            );
            if($web_styling_options_count == 0)
            {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table('web_styling_options')->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');

                DB::table('web_styling_options')->insert($web_styling_options);
            }
            else{
                foreach ($web_styling_options as $option) {
                    $webStylingOption = WebStylingOption::where('image', $option['image'])->first();
                    if ($webStylingOption !== null) {
                        $webStylingOption->update(['web_styling_id' => $web_styling, 'name' => $option['name'], 'template_id' => $option['template_id']]);
                    } else {
                        WebStylingOption::create([
                            'name' => $option['name'],
                            'image' => $option['image'],
                            'is_selected' => $option['is_selected'],
                            'template_id' => $option['template_id'],
                            'web_styling_id' => $option['web_styling_id']
                        ]);
                    }
                }
            }
        }
    }
}