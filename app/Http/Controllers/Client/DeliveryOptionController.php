<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Client\BaseController;
use App\Http\Traits\ToasterResponser;
use App\Models\{Client, ClientPreference,ShippingOption};
use Illuminate\Http\Request;

class DeliveryOptionController extends Controller
{
    
    use ToasterResponser;

    public function index()
    {
        $shipping_codes = ['lalamove'];
        $delOption = ShippingOption::where('code', $shipping_codes)->first();

        $shipping_codes = ['shiprocket'];
        $shipingOption = ShippingOption::whereIn('code', $shipping_codes)->first();

        return view('backend/deliveryoption/index')->with(['delOption' => $delOption,'opt'=>$shipingOption]);
    }


    //Set new lalamove configuration details function
    public function store(Request $request)
    {
        try{
        //dd($request->input());
        $msg = 'Delivery option have been saved successfully!';
        $id = base64_decode($request->method_id);
        $method_name_arr = $request->input('method_name');
        $active_arr = $request->input('active');
        $base_active = $request->input('base_active');
        $test_mode_arr = $request->input('sandbox');

        
        $saved_creds = ShippingOption::select('credentials')->where('id', $id)->first();
        if ((isset($saved_creds)) && (!empty($saved_creds->credentials))) {
                $json_creds = $saved_creds->credentials;
            } else {
                $json_creds = NULL;
        }

            $status = 0;
            $test_mode = 0;
            if ((isset($active_arr)) && ($active_arr == 'on')) {
                $status = 1;

                if ((isset($test_mode_arr)) && ($test_mode_arr == 'on')) {
                    $test_mode = 1;
                }

                if ((isset($method_name_arr)) && (strtolower($method_name_arr) == 'lalamove')) {
                    $validatedData = $request->validate([
                        'api_key'               => 'required',
                        'secret_key'            => 'required',
                        'country_key'           => 'required',
                        'country_region'        => 'required',
                        'locale_key'            => 'required',
                        'service_type'          => 'required',
                    ]);
                    $json_creds = array(
                        'api_key'               => $request->api_key,
                        'secret_key'            => $request->secret_key,
                        'country_key'           => $request->country_key,
                        'country_region'        => $request->country_region,
                        'locale_key'            => $request->locale_key,
                        'service_type'          => $request->service_type
                    );

                    if ((isset($base_active)) && ($base_active == 'on')) {
                        $json_creds['base_price'] = $request->base_price;
                        $json_creds['distance'] = $request->distance;
                        $json_creds['amount_per_km'] = $request->amount_per_km;
                    }else{
                        $json_creds['base_price'] = '0';
                        $json_creds['distance'] = '0';
                        $json_creds['amount_per_km'] = '0';
                    }

                    $json_creds = json_encode($json_creds);

                }
            }
            ShippingOption::where('id', $id)->update(['status' => $status, 'credentials' => $json_creds, 'test_mode' => $test_mode]);
            $toaster = $this->successToaster(__('Success'), $msg);

        }catch(\Exception $e)
        {
            $toaster = $this->errorToaster(__('Error'), $e->getMessage());
        }

        return redirect()->back()->with('toaster', $toaster);
    }


}
