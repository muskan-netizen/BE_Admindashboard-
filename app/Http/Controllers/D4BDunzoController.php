<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Traits\D4BDunzo;
use App\Models\Order;
use App\Models\OrderVendor;
use App\Models\OrderVendorProduct;
use App\Models\ShippingOption;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
use App\Models\VendorOrderDispatcherStatus;
use App\Models\VendorOrderStatus;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
class D4BDunzoController extends Controller
{
    use D4BDunzo;

    public function quote($vendor_id)
    {
        try{
            $customer = User::find(Auth::id());
            $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
            if ($cus_address && $this->status==1){
                $vendor_details = Vendor::find($vendor_id);
                $response = Http::withHeaders([
                    'client-id' => $this->client_id,
                    'Authorization' => $this->token,
                    'Accept-Language' => 'en_US',
                    'Content-Type' => 'application/json',
                ])->post($this->app_url.'/v2/quote', [
                    'pickup_details' => [
                        [
                            'lat' => floatval($vendor_details->latitude),
                            'lng' => floatval($vendor_details->longitude),
                            'reference_id' => 'pickup-ref-abcd123'.strtotime(now()),
                        ],
                    ],
                    'optimised_route' => true,
                    'drop_details' => [
                        [
                            'lat' =>  floatval($cus_address->latitude),
                            'lng' => floatval($cus_address->longitude),
                            'reference_id' => 'drop-ref1-abcd887'.strtotime(now()),
                            // 'payment_data' => [
                            //     'payment_method' => 'COD',
                            //     'amount' => 101,
                            // ],
                        ]

                            ],
                    'delivery_type' => 'SCHEDULED',
                    'schedule_time' => Carbon::now()->addMinutes($vendor_details->order_pre_time??10)->timestamp,
                ]);

                if($response->successful()){
                    return $response->json();
                }else{
                    $response = 2;
                }
            }
        }catch(\Exception $e){
            return 0;
        }
    }

    public function createOrderRequestD4BDunzo($user_id,$orderVendor)
    {
        $order = Order::find($orderVendor->order_id);
        $customer = User::find($user_id);
        $vendor_details = Vendor::find($orderVendor->vendor_id);

        $cus_address = UserAddress::find($order->address_id);
        $orderProducts = OrderVendorProduct::where(['order_id'=>$orderVendor->order_id,'order_vendor_id'=>$orderVendor->id])->get();
        // create order
        return $this->createOrder($orderVendor,$vendor_details,$cus_address,$customer,$order);
    }

    public function d4bdunzoWebhook(Request $request)
    {
        try {
            $jsonData = json_decode($request->getContent());
            $taskId = $jsonData->task_id;
            $details =OrderVendor::where('web_hook_code',$taskId)->first();
            $trackingStatus = $this->getTrackInfo($taskId);
            $dispatcherStatusOptionId = null;
            switch ($jsonData->state) {
                case 'queued':
                    $dispatcherStatusOptionId = '1';
                    break;
                case 'runner_accepted/reached_for_pickup':
                    $dispatcherStatusOptionId = '2';
                    break;
                case 'pickup_complete/started_for_delivery/reached_for_delivery':
                    $dispatcherStatusOptionId = '4';
                    break;
                case 'delivered':
                    $dispatcherStatusOptionId = '6';
                    break;
                case 'cancelled':
                case 'location_cancelled':
                    $dispatcherStatusOptionId = '3';
                    break;
                // Add more cases as needed
                // Default case if the state doesn't match any known values
                default:
                    // Handle unknown state if necessary
            }

            if ($dispatcherStatusOptionId !== null) {

            //   VendorOrderStatus::where([
            //         'order_id' => $details->order_id,
            //         'vendor_id' => $details->vendor_id,

            //     ])->update(['order_status_option_id'=>$dispatcherStatusOptionId]);
                OrderVendor::where([
                    'order_id' => $details->order_id,
                    'vendor_id' => $details->vendor_id,


                ])->update(['order_status_option_id'=>$dispatcherStatusOptionId]);

                // dd(VendorOrderStatus::where([
                //     'order_id' => $details->order_id,
                //     'vendor_id' => $details->vendor_id,

                // ])->first());
                // VendorOrderStatus::Create(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'order_status_option_id'=>$dispatcherStatusOptionId]);
                VendorOrderDispatcherStatus::create([
                    'order_id' => $details->order_id,
                    'vendor_id' => $details->vendor_id,
                    'dispatcher_status_option_id' => $dispatcherStatusOptionId
                ]);
            }
            return response([], 200);
        } catch (\Exception $e) {
            // Handle exceptions here
            return response(['error' => $e->getMessage()], 500);
        }
    }

    public function getTrackInfo($task_id)
    {
        $traking_res = Http::withHeaders([
            'client-id' => $this->client_id,
            'Authorization' => $this->token,
            'Accept-Language' => 'en_US',
            'Content-Type' => 'application/json',
        ])
        ->get( $this->app_url.'/v1/tasks/'.$task_id.'/status');

        // You can then handle the traking_res as needed
        return $traking_res->json();
    }

	public function getDistance($vendorId)
    {
		$this->configuration();
		if($this->status == 1){
			$customer = User::find(Auth::id());
			$cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
			$vendor_details = Vendor::find($vendorId);
			$latitude[] =  $vendor_details->latitude ?? 30.71728880;
			$latitude[] =  $cus_address->latitude ?? 30.717288800000;
			$longitude[] =  $vendor_details->longitude ?? 76.803508700000;
			$longitude[] =  $cus_address->longitude ?? 76.803508700000;
			$distance =  GoogleDistanceMatrix($latitude,$longitude);
			return $distance['distance'];
		}
		return false;
    }
    # get delivery fee getDunzoBaseFee
    public function getDunzoBaseFee($vendorId,$distance = null)
    {
        $fees = 0;
        $this->configuration();
        if($this->status == 1 && $this->base_price>0){
                if(!$distance){
                    $distance = $this->getDistance($vendorId);
                }
            if($distance){
                //Helper Function
                $fees =   getBaseprice($distance,'dunzo');
            }
        }
        return $fees;
    }

    # get delivery fee Dunzo Courier Service
    public function getQuotations($vendor_id,$address)
    {
        $this->configuration();
        $vendor_details = Vendor::find($vendor_id);
        $data =array(
            'pickup_lat'=>$vendor_details->latitude ?? '',
            'pickup_long'=>$vendor_details->longitude ?? '',
            'delivery_lat' => $address->latitude, //Required
            'delivery_long' => $address->longitude, //Required
        );
        $status =  $this->getfees($data);
        if($this->status){
            if(($this->base_price>0) && $status->status == true){
            return $this->getDunzoBaseFee($vendor_id,$status->data->distance);
        }else{
                if($status->status){
                    return $status->data->estimated_price??0;
                }
                return 0;
            }
        }
        return 0;
    }

    # get delivery fee Courier Service
    public function getCourierService($vendorId)
    {
        $this->configuration();
        $vendor_details = Vendor::find($vendorId);
        $data =array(
            'pickup_lat'=>$vendor_details->latitude ?? '',
            'pickup_long'=>$vendor_details->longitude ?? ''
        );
        $status =  $this->checkAvilabilty($data);
        if($this->status == 1){
            if(($this->base_price>0) && $status->status == true){
            return $this->getDunzoBaseFee($vendorId);
        }else{
                if($status->status){
                    return $this->getDunzoBaseFee($vendorId);
                }
                return 0;
            }
        }
    }

    public function cancelOrderRequestD4BDunzo($task_id,$reason)
    {
		if($this->status){
            $data =array('cancellation_reason'=>$reason??'No Need');
			return $cancel_order= $this->cancelOrder($data,$task_id);
		}
    }

}
