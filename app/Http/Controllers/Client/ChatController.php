<?php

namespace App\Http\Controllers\Client;

use DB;
use Auth;
use Session;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Http\Traits\GlobalFunction;
use Illuminate\Support\Facades\Http;


use App\Models\{Client, Order, UserVendor, ClientPreference, LoyaltyCard,OrderProductRating};

class ChatController extends BaseController
{
    use GlobalFunction;
    /**
     * Display a listing of the country resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $client_data;
    public function __construct()
    {
        $this->middleware('auth');
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
    
    public function getChatRoom($vendor_id,$type){
        $clientData = $this->client_data;
        $server_name = $_SERVER['REMOTE_ADDR'];
        // echo "<pre>";
        // print_r($vendor_id->toArray());
        // die;
        $response =   Http::post($clientData->socket_url.'/api/room/fetchRoomByVendor', [
            'vendor_id' => $vendor_id, 
            //'room_name' => $room_name,
            // 'order_vendor_id'=>$order_vendor_id,
            // 'order_id'=>$order_id,
            // 'vendor_id'=>$vendor_id,
            'sub_domain' =>$server_name,
            // 'vendor_user_id' =>$data['user_id'],
            // 'order_user_id' =>$orderby_user_id,
            'type'=>$type,
            'db_name'=>$clientData->database_name,
            'client_id'=>$clientData->id
        ]);
        //echo "<pre>";
       
        $statusCode = $response->getStatusCode();
        if($statusCode == 200) {
            $roomData = $response['roomData'];
            //print_r($roomData);
            return ['status' => true, 'roomData' => $roomData , 'message' => __('Room list !!!')];
        } else {

            return ['status' => false, 'message' => __('Something went wrong!!!')];
        }
        //die;

    }

    public function getChatRoomForUser($order_user_id,$type){
        $clientData = $this->client_data;
        $server_name = $_SERVER['REMOTE_ADDR'];
        // echo "<pre>";
        // print_r($vendor_id->toArray());
        // die;
        $response =   Http::post($clientData->socket_url.'/api/room/fetchRoomByUserId', [
            'order_user_id' => $order_user_id, 
            //'room_name' => $room_name,
            // 'order_vendor_id'=>$order_vendor_id,
            // 'order_id'=>$order_id,
            // 'vendor_id'=>$vendor_id,
            'sub_domain' =>$server_name,
            // 'vendor_user_id' =>$data['user_id'],
            // 'order_user_id' =>$orderby_user_id,
            'type'=>$type,
            'db_name'=>$clientData->database_name,
            'client_id'=>$clientData->id
        ]);
        //echo "<pre>";
       
        $statusCode = $response->getStatusCode();
        if($statusCode == 200) {
            $roomData = $response['roomData'];
            //print_r($roomData);
            return ['status' => true, 'roomData' => $roomData , 'message' => __('Room list !!!')];
        } else {

            return ['status' => false, 'message' => __('Something went wrong!!!')];
        }
        //die;

    }

    public function index(Request $request){
        // echo "review";
        return view('backend.chat.index',$this->client_data);

    }

    public function VendorUserChat(Request $request){
        $user = Auth::user();
       // if ($user->is_superadmin == 0) {
        $vendor_id = UserVendor::where('user_id',$user->id)->pluck('vendor_id');
        $this->client_data['vendor_id'] = $vendor_id;
        $roomData = $this->getChatRoom($vendor_id,'vendor_to_user');
        if($roomData['status']){
            $chatroom = $roomData['roomData'];
        } else {
            $chatroom = [];
        }
        // echo "<pre>";
        // print_r($this->client_data);
        // die;
        //}
        // echo "review";
        return view('backend.chat.VendorUserChat',$this->client_data)->with([ 'data' => $this->client_data,'chatrooms'=>$chatroom]);

    }


    public function UservendorChat(Request $request){
        $user = Auth::user();
       // if ($user->is_superadmin == 0) {
        //$vendor_id = UserVendor::where('user_id',$user->id)->pluck('vendor_id');
        //$this->client_data['vendor_id'] = $vendor_id;
        $roomData = $this->getChatRoomForUser($user->id,'vendor_to_user');
        if($roomData['status']){
            $chatroom = $roomData['roomData'];
        } else {
            $chatroom = [];
        }
        echo "<pre>";
        print_r($chatroom);
        die;
        //}
        // echo "review";
        return view('backend.chat.UserVenorChat',$this->client_data)->with([ 'data' => $this->client_data,'chatrooms'=>$chatroom]);

    }

    /**
     * Show the form for creating a new country resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created country resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified country resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$domain = '',$product_sku)
    {
       
    }

    /**
     * Show the form for editing the specified country resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
    }

    /**
     * Update the specified country resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        }

    /**
     * Remove the specified country resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$domain = '',$review_id)
    {
       
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
       
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $server_name = $_SERVER['REMOTE_ADDR'];
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
                'sub_domain' =>$server_name,
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
        
        }

        //print_r($order);
    }


}

