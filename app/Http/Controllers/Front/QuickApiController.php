<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderVendor;
use App\Models\VendorOrderStatus;
use App\Models\ShippingOption;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
use App\Models\VendorOrderDispatcherStatus;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log,DB;
use App\Http\Traits\{ApiResponser,KwikApi};


class QuickApiController extends Controller
{
    use KwikApi,ApiResponser;


      public function __construct()
        {
            $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'kwikapi')->where('status', 1)->first();
            if($simp_creds){
                $this->status = $simp_creds->status??'0';
                $creds_arr = json_decode($simp_creds->credentials);
                $this->api_email = $creds_arr->api_email??'';
                $this->api_pass = $creds_arr->api_pass??'';
                $this->app_url = (($simp_creds->test_mode=='1')?'https://staging-api-test.kwik.delivery':'https://staging-api-test.kwik.delivery'); //Live url - 
                $this->test = $simp_creds->test_mode; 
                $this->base_price = $creds_arr->base_price ?? ''; 
                $this->distance = $creds_arr->distance ?? ''; 
                $this->amount_per_km = $creds_arr->amount_per_km ?? '';
            }else{
                return 0;
            }
        }



    public function getDeliveryFeeKwikApi($vendor_id)
    {
    try{    
            $customer = User::find(Auth::id());
            $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
            if ($cus_address && $this->lalamove_status==1){

                $vendor_details = Vendor::find($vendor_id);
                $data = (object) array(
                    'pick_lat' => $vendor_details->latitude,
                    'pick_lng' => $vendor_details->longitude,
                    'pick_address' => $vendor_details->address,
                    'vendor_name' => $vendor_details->name,
                    // 'vendor_contact' => $vendor_details->phone_no,
                    'vendor_contact' => '3768865552',
                    'drop_lat' => $cus_address->latitude,
                    'drop_lng' => $cus_address->longitude,
                    'drop_address' => $cus_address->address,
                    'user_name' => $customer->name,
                    'user_phone' => $customer->phone_number,
                    'remarks' => 'Delivery vendor message remarks'
                );
        
                $quotation = $this->getPriceEstimation($data);
                $actualAmount=0;
                if($quotation['code']!='409')
                { 
                    $json = json_decode($quotation['response']);
                    $distance =  round($json->distance->value/1000);
                    if($this->base_price > 0)
                    {
                        $actualAmount = getBaseprice($distance);
                    }else{
                        $actualAmount = $json->totalFee;
                    }
                }
                //dd($actualAmount);
                return $actualAmount;
            }
        
        }catch(\Exception $e)
        {
            return 0;
        }
    }


    public function webhooks(Request $request)
    {
        try{
           $json = json_decode($request->getContent());
           if(isset($request)){
            Webhook::create(['tracking_order_id'=>'1313','response'=>$request->getContent()]);
           }
        
        }catch(\Exception $e){

            if(isset($request)){
                Webhook::create(['tracking_order_id'=>'1213','response'=>$request->getContent()]);
               }
            return response(['error'=>$e->getMessage()],200);
        }

        return response([],200);

    }

    
}
