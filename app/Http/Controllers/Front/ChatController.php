<?php

namespace App\Http\Controllers\Front;
use Auth;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use App\Http\Traits\GlobalFunction;
use Illuminate\Support\Facades\Http;
use App\Http\Traits\ChatTrait;



use App\Models\{Client, Order, UserVendor};

class ChatController extends FrontController
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
        $server_name = $_SERVER['SERVER_NAME'];
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

    public function getChatRoomForUser($order_user_id,$type){
        $clientData = $this->client_data;
        $server_name = $_SERVER['SERVER_NAME'];
        $response =   Http::post($clientData->socket_url.'/api/room/fetchRoomByUserId', [
            'order_user_id' => $order_user_id, 
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

    public function index(Request $request){
        return view('frontend.chat.index',$this->client_data);

    }

    public function VendorUserChat(Request $request){
        $user = Auth::user();
        $vendor_id = UserVendor::where('user_id',$user->id)->pluck('vendor_id');
        $this->client_data['vendor_id'] = $vendor_id;
        $roomData = $this->getChatRoom($vendor_id,'vendor_to_user');
        if($roomData['status']){
            $chatroom = $roomData['roomData'];
        } else {
            $chatroom = [];
        }
        return view('backend.chat.VendorUserChat',$this->client_data)->with([ 'data' => $this->client_data,'chatrooms'=>$chatroom]);

    }


    public function UservendorChat(Request $request){
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $user = Auth::user();
        $roomData = $this->getChatRoomForUser($user->id,'vendor_to_user');
        if($roomData['status']){
            $chatroom = $roomData['roomData'];
        } else {
            $chatroom = [];
        }
        return view('frontend.chat.UserVenorChat',$this->client_data)->with([ 'data' => $this->client_data,'chatrooms'=>$chatroom,
        'navCategories' => $navCategories
    ]);

    }

    public function joinSocketRoom($data,$user,$type,$userType){
        $clientData = $this->client_data;
        $server_name = $_SERVER['SERVER_NAME'];
        $response =   Http::post($clientData->socket_url.'/api/chat/joinRoomByID', [
            'sub_domain' =>$server_name,
            'room_id' =>$data['room_id'],
            'user_type' =>$userType,
            'type'=>$type,
            'user_id'=>$user->id,
            'email'=>$user->email,
            'user_name'=>$user->name,
            'phone_num'=>'+'.$user->dial_code.' '.$user->phone_number,
            'dipslay_image'=>$user->image
        ]);

        $statusCode = $response->getStatusCode();
        if($statusCode == 200) {
            $roomData = $response['roomData'];
            $roomUser = $response['RoomUser'];
            $message = $response['message'];
            return ['status' => $response['status'],'roomUser' =>$roomUser ,'roomData' => $roomData , 'message' => __($message)];
        } else {

            return ['status' => false, 'message' => __('Something went wrong!!!')];
        }
    }


    public function sendSocketMessage($data,$user,$to_message,$userType,$from_message,$chat_type){
        $clientData = $this->client_data;
        $server_name = $_SERVER['SERVER_NAME'];
        $response =   Http::post($clientData->socket_url.'/api/chat/sendMessageJoin', [
            'sub_domain' =>$server_name,
            'room_id' =>$data['room_id'],
            'message' =>$data['message'],
            'user_type' =>$userType,
            'to_message'=>$to_message,
            'from_message'=>$from_message,
            'user_id'=>$user->id,
            'email'=>$user->email,
            'display_image'=>$user->image,
            'chat_type' =>$chat_type,
           
        ]);

        $statusCode = $response->getStatusCode();
        if($statusCode == 200) {
            $chatData = $response['chatData'];
            //$roomUser = $response['RoomUser'];
            $message = $response['message'];
          
            return ['status' => $response['status'] ,'chatData' => $chatData , 'message' => __($message)];
        } else {

            return ['status' => false, 'message' => __('Something went wrong!!!')];
        }
    }


    public function JoinRoom(Request $request){
        $user = Auth::user();
        $data = $request->all();
        $roomData = $this->joinSocketRoom($data,$user,'vendor_to_user','vendor');
        if($roomData['status']) {
            return $roomData;
        } else {

            return $roomData;
        }

    }


    public function sendMessage(Request $request){
        $user = Auth::user();
        $data = $request->all();
        if($data['from'] == 'vendor') {
            $messageData = $this->sendSocketMessage($data,$user,'to_user','vendor','from_vendor','vendor_to_user');
        } else {
            $messageData = $this->sendSocketMessage($data,$user,'to_vendor','user','from_user','vendor_to_user');
        }
        if($messageData['status']) {
            return $messageData;
        } else {

            return $messageData;
        }

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


}

