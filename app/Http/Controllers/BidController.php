<?php

namespace App\Http\Controllers;

use App\Models\BidRequest;
use App\Models\ClientPreference;
use App\Models\NotificationTemplate;
use App\Models\UserDevice;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BidController extends Controller
{
    public function index()
    {
        $prescriptions = BidRequest::where('id', Auth::user()->id)->get();
        return view('frontend.bidding_module.index', compact('prescriptions',$prescriptions));
    }

    public function create()
    {
        return view('frontend.bidding_module.create');
    }

    public function uploadPrescription(Request $request, $domain = '')
    {
        // dd($request->prescriptions);
        $user = Auth::user();
        if ($user) {
            foreach ($request->prescriptions as $prescription) {
                $bid_request_prescription = new bidRequest();
                $bid_request_prescription->prescription =  Storage::disk('s3')->put('prescription', $prescription, 'public');
                $bid_request_prescription->save();
            }

        }
        return redirect()->back()->with(['status' => 'success', 'message' => "Uploaded Successfully"]);
    }

    public function getPrescription(Request $request){
        if(!empty($request->prescriptionId) && $request->requestType == 'delete_prescription'){
            BidRequest::where('id', $request->prescriptionId)->delete();
            return response()->json(['status' => 'success', 'message' => "Prescription remove Successfully"]);
        }
        $bidPrescription = bidRequest::get()->toArray();
        return response()->json($bidPrescription);
    }

    public function sendBidPushNotificationVendors($user_ids, $prescriptionData)
    {
        $devices = UserDevice::where('is_vendor_app', 0)->whereNotNull('device_token')->whereIn('user_id', $user_ids)->pluck('device_token')->toArray();

        $from = '';
        $client_preferences = ClientPreference::select('fcm_server_key', 'favicon', 'vendor_fcm_server_key')->first();
        if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
            $from = $client_preferences->fcm_server_key;
        }

        $notification_content = NotificationTemplate::where('id', 11)->first();
        if ($notification_content) {
            $body_content = str_ireplace("{prescription}", "#" . $prescriptionData->id, $notification_content->content);
            $data = [
                "registration_ids" => $devices,
                "notification" => [
                    'title' => $notification_content->subject,
                    'body'  => $body_content,
                    'sound' => "notification.wav",
                    "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                    //'click_action' => route('order.index'),
                    "android_channel_id" => "sound-channel-id"
                ],
                "data" => [
                    'title' => $notification_content->subject,
                    'body'  => $notification_content->content,
                    'data' => $prescriptionData,
                    'prescription_id' => $prescriptionData->id,
                    'type' => "bid_request_created"
                ],
                "priority" => "high"
            ];
             if(!empty($from)){
                // helper function
                sendFcmCurlRequest($data);
            }

            // Individual Vendor App User Token
            $vendorAppUserDevices = UserDevice::where('is_vendor_app', 1)->whereNotNull('device_token')->whereIn('user_id', $user_ids)->pluck('device_token')->toArray();


            if(!empty($vendorAppUserDevices) && !empty($client_preferences->vendor_fcm_server_key)) {

                $from = $client_preferences->vendor_fcm_server_key;
                $data['registration_ids'] = $vendorAppUserDevices;

                $result = sendFcmCurlRequest($data);
                //Log::info($result);
            }
        }
    }

}
