<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Auth;
use Session;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Traits\ChatTrait;
use App\Http\Traits\GlobalFunction;
use Illuminate\Support\Facades\Http;


use App\Models\{Client, Order, UserVendor, ClientPreference, LoyaltyCard,OrderProductRating};

class ChatController extends BaseController
{
    use GlobalFunction;
    use ChatTrait;
    /**
     * Display a listing of the country resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $client_data;
    public function __construct()
    {
        
        $this->middleware(function ($request, $next) {
            $this->id = Auth::user()->id;
            $data = Client::find(1);
            $this->client_data =  $data;
            if ($data->socket_url == null) {
                abort(404);
            }
    
            return $next($request);
        });
    }
    public function getChatRoom($vendor_id,$type,$sub_domain){
        $clientData = $this->client_data;
        $server_name = $sub_domain;
        $response =   Http::post($clientData->socket_url.'/api/room/fetchRoomByVendor', [
            'vendor_id' => $vendor_id, 
            'sub_domain' =>$server_name,
            'type'=>$type,
            'db_name'=>$clientData->database_name,
            'client_id'=>$clientData->id
        ]);
        $statusCode = $response->getStatusCode();
        if($statusCode == 200) {
            $roomData = $response['roomData'];
            return ['status' => true, 'roomData' => $roomData , 'message' => __('Room list !!!')];
        } else {

            return ['status' => false, 'message' => __('Something went wrong!!!')];
        }

    }

    public function getChatRoomForUser($order_user_id,$type,$sub_domain){
        $clientData = $this->client_data;
        $server_name = $sub_domain;
        $response =   Http::post($clientData->socket_url.'/api/room/fetchRoomByUserId', [
            'order_user_id' => $order_user_id, 
            'sub_domain' =>$server_name,
            'type'=>$type,
            'db_name'=>$clientData->database_name,
            'client_id'=>$clientData->id
        ]);
        
        // echo "<pre>";
        // print_r($response['roomData']);
        // die;
        $statusCode = $response->getStatusCode();
        if($statusCode == 200) {
            $roomData = $response['roomData'];
           
            return ['status' => true, 'roomData' => $roomData , 'message' => __('Room list !!!')];
        } else {

            return ['status' => false, 'message' => __('Something went wrong!!!')];
        }
    

    }
    public function vendorUserChatRoom(Request $request){
        die;
        $user = Auth::user();
        $data = $request->all();
        $sub_domain = $data['sub_domain'];
        $vendor_id = UserVendor::where('user_id',$user->id)->pluck('vendor_id');
        $this->client_data['vendor_id'] = $vendor_id;
        $roomData = $this->getChatRoom($vendor_id,'vendor_to_user',$sub_domain);
        if($roomData['status']){
            $chatroom = $roomData['roomData'];
        } else {
            $chatroom = [];
        }
        return response()->json([ 'chatrooms'=>$chatroom , 'status' => true, 'message' => __('list fetched!!!')]);

    }


    public function userVendorChatRoom(Request $request){
        $user = Auth::user();
        $data = $request->all();
        $sub_domain = $data['sub_domain'];
        $roomData = $this->getChatRoomForUser($user->id,'vendor_to_user',$sub_domain);
        if($roomData['status']){
            $chatroom = $roomData['roomData'];
        } else {
            $chatroom = [];
        }

    
        return response()->json([ 'chatrooms'=>$chatroom , 'status' => true, 'message' => __('list fetched!!!')]);

    }

    /**
     * start Chat.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function startChat(Request $request)
    {
        $data = $request->all();

        $vendor_id = $data['vendor_id'];
        $vendor_order_id = $data['vendor_order_id'];
        $order_id = $data['order_id'];
       
        $langId = 1;
        $server_name = $_SERVER['SERVER_NAME'];
        
        $order = Order::with(array(
            'vendors' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            },
            'vendors.products.prescription' => function ($query) use ($vendor_id, $order_id) {
                $query->where('vendor_id', $vendor_id)->where('order_id', $order_id);
            },
            'vendors.products' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            },
            'vendors.products.addon',
            'vendors.products.addon.set',
            'vendors.products.addon.option',
            'vendors.products.addon.option.translation' => function ($q) use ($langId) {
                $q->select('addon_option_translations.id', 'addon_option_translations.addon_opt_id', 'addon_option_translations.title', 'addon_option_translations.language_id');
                $q->where('addon_option_translations.language_id', $langId);
                $q->groupBy('addon_option_translations.addon_opt_id', 'addon_option_translations.language_id');
            },
            'vendors.dineInTable.translations' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendors.dineInTable.category',
            'vendors.cancel_request',
            'reports'
        ))->findOrFail($order_id);
        if($order){
            $socket_url = $this->client_data->socket_url;
            $room_id = $order->order_number;
            $room_name = 'OrderNo-'.$order->order_number.'-orderId-'.$order->id.'-oderVendor-'.$vendor_id;
            $order_vendor_id = $vendor_order_id;
            $order_id = $order->id;
            $vendor_id = $vendor_id;
            $orderby_user_id = $order->user_id;
            //$response = $client->request('Post', 'https://chat.royoorders.com/api/room', ['body' => [
            $response =   Http::post($socket_url.'/api/room/createRoom', [
                'room_id' => $room_id, 
                'room_name' => $room_name,
                'order_vendor_id'=>$order_vendor_id,
                'order_id'=>$order_id,
                'vendor_id'=>$vendor_id,
                'sub_domain' =>$data['sub_domain'],
                'vendor_user_id' =>$data['user_id'],
                'order_user_id' =>$orderby_user_id,
                'type'=>$data['type'],
                'db_name'=>$data['db_name'],
                'client_id'=>$data['client_id']
            ]);
            $statusCode = $response->getStatusCode();
            if($statusCode == 200) {
                $roomData = $response['roomData'];
                return response()->json(['status' => true, 'roomData' => $roomData , 'message' => __('Room created successfully !!!')]);
            } else {

                return response()->json(['status' => false, 'message' => __('Something went wrong!!!')]);
            }
        
        } else {
            return response()->json(['status' => false, 'message' => __('Something went wrong!!!')]);
        }

    }

    public function fetchOrderDetail(Request $request){
        try {
            $orderData = $this->OrderVendorDetail($request);
            return response()->json(['status' => true, 'orderData' => $orderData , 'message' => __('Data fetched !!!')]);
            
            //code...
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'orderData' => [] , 'message' => __('No Data found !!!')]);
        }
            
    }


    public function userAgentChatRoom(Request $request){
        $user = Auth::user();
        $data = $request->all();
        $sub_domain = $data['sub_domain'];
        $roomData = $this->getChatRoomForUser($user->id,'agent_to_user',$sub_domain);
        if($roomData['status']){
            $chatroom = $roomData['roomData'];
        } else {
            $chatroom = [];
        }

    
        return response()->json([ 'chatrooms'=>$chatroom , 'status' => true, 'message' => __('list fetched!!!')]);

    }


}

