<?php

namespace App\Http\Traits;

use App\Models\AppStylingOption;
use App\Models\WebStylingOption;

trait ResetConfiguration
{

    public function  updateStylingsAndPreferences($web_styling_id, $app_styling_id)
    {

        // app stylings from 34 to 41
        $web_font = WebStylingOption::where('id', $web_styling_id)->first();
        $option_change = WebStylingOption::where('web_styling_id', '=', $web_font->web_styling_id)->update(array('is_selected' => 0));
        $web_font->is_selected = 1;
        $web_font->save();

        $app_font = AppStylingOption::where('id', $app_styling_id)->first();
        $option_change = AppStylingOption::where('app_styling_id', '=', $app_font->app_styling_id)->update(array('is_selected' => 0));
        $app_font->is_selected = 1;
        $app_font->save();
    }

    public function  resetDeliveryConfiguration()
    {
        $this->updateStylingsAndPreferences(3,37);
        \Illuminate\Support\Facades\Redis::flushall();
    }

    public function  resetRentalConfiguration()
    {
        $this->updateStylingsAndPreferences(1,34);
        \Illuminate\Support\Facades\Redis::flushall();

    }
    public function  resetPickDropConfiguration()
    {
        $this->updateStylingsAndPreferences(3,35);
        \Illuminate\Support\Facades\Redis::flushall();

    }
    public function  resetOnDemandConfiguration()
    {
        $this->updateStylingsAndPreferences(5,39);
        \Illuminate\Support\Facades\Redis::flushall();

    }
    public function  resetLaundryConfiguration()
    {
        $this->updateStylingsAndPreferences(5,39);
        \Illuminate\Support\Facades\Redis::flushall();

    }

    public function  resetP2PConfiguration()
    {
        $this->updateStylingsAndPreferences(7,38);
        \Illuminate\Support\Facades\Redis::flushall();

    }
    public function  resetEmartConfiguration()
    {
        $this->updateStylingsAndPreferences(6,40);
        \Illuminate\Support\Facades\Redis::flushall();

    }
    public function  resetSuperAppConfiguration()
    {
        $this->updateStylingsAndPreferences(3,34);
        \Illuminate\Support\Facades\Redis::flushall();

    }
}
