<?php
namespace App\Http\Traits;

use DB;
use HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\{Order,ProductVariant,OrderVendor,VendorOrderCancelReturnPayment,UserDevice,ClientPreference};
use Auth;
trait ChatTrait{

    public function OrderVendorDetail($request)
    {
        $data = $request->all();
        $order_vendor_id = $data['order_vendor_id'];
        $order_id = $data['order_id'];
        $order = Order::with(array(
            'vendors' => function ($query) use ($order_vendor_id) {
                $query->where('id', $order_vendor_id);
            },'vendors.vendor'
        ))->findOrFail($order_id);
        return  $order;
    }

    public function sendNotification($request)
    {

        // echo "<pre>";
        // print_r($request->all()['user_ids']);
        $username =  Auth::user()->name;
        $auid =  Auth::user()->id;
        
        $result = array_values(array_column($request->all()['user_ids'], 'auth_user_id'));
        $removeAuth = array_values(array_diff($result, array($auid)));
        $client_preferences = ClientPreference::select('fcm_server_key','favicon')->first();
        $devices            = UserDevice::whereNotNull('device_token')->whereIn('user_id',$removeAuth)->pluck('device_token') ?? [];
        if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
            $SERVER_API_KEY = $client_preferences->fcm_server_key;
            $data = [
                "registration_ids" => $devices,
                "notification" => [
                    "title" => $username,
                    "body"  => $request->text_message,
                    'sound' => "default",
                    "icon"  => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                    "android_channel_id" => "sound-channel-id"
                ],
                "data" => [
                    "title" => $username,
                    "body"  => $request->text_message,
                    'data'  => '',
                    'type'  => ""
                ],
                "priority" => "high"
            ];
            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($response); 
            return $result;
        }
    }
}
