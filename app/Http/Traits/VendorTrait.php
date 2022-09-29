<?php
namespace App\Http\Traits;

use DB;
use Auth;
use HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Client as CP;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

use App\Models\{VendorMultiBanner,WebStylingOption};


trait VendorTrait{

    /**
     * getMultiBanner
     *
     * @param  mixed $vendor_id
     * @return void
     */

    public function getMultiBanner($vendor_id){
        $webStyle =   WebStylingOption::where(['is_selected'=>'1'])->first('id');
        $banner = [];
        if($webStyle && ($webStyle->id == 6)){
            $banner = VendorMultiBanner::where(['vendor_id'=> $vendor_id])->whereNotNull('image')->get();
        }
        $respons=[
            'webStyleId' => $webStyle->id,
            'banner' => $banner,
        ];
        return $respons;
     
    }


}
