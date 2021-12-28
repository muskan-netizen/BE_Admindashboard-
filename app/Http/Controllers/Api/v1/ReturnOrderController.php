<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Config;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\Web\OrderProductRatingRequest;
use App\Http\Requests\Web\OrderProductReturnRequest;
use App\Models\{Client, ClientPreference, EmailTemplate, NotificationTemplate, Order,OrderProductRating,VendorOrderStatus,OrderProduct,OrderProductRatingFile,ReturnReason,OrderReturnRequest,OrderReturnRequestFile, OrderVendor, OrderVendorProduct, User, UserDevice, UserVendor};
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Session;
use App\Models\Client as CP;
use App\Models\Transaction;
use App\Models\AutoRejectOrderCron;

class ReturnOrderController extends BaseController{
	
    use ApiResponser;
    
    /**
     * order details in modal
    */
    public function getOrderDatainModel(Request $request){
        try { 
            $user = Auth::user();
            $lang_id = $user->language;
            $order_details = Order::with(['vendors.products.productReturn','products.productRating', 'user', 'address',
            'vendors'=>function($qw)use($request){
                $qw->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
            },'vendors.products'=>function($qw)use($request){
                $qw->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
            },'vendors.products.translation'=>function($qw)use($lang_id){
                $qw->where('language_id', $lang_id);
            },'products'=>function($qw)use($request){
                $qw->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
            }])->whereHas('vendors',function($q)use($request){
                $q->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
            })
            ->where('orders.user_id', Auth::user()->id)->where('orders.id', $request->id)->orderBy('orders.id', 'DESC')->first();
           
            if(isset($order_details)){
                return $this->successResponse($order_details,'Return Data.');
           }
           return $this->errorResponse('Invalid order', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    /**
     * order details in for return order
    */
    public function getReturnProducts(Request $request, $domain = ''){
        try {
            $reasons = ReturnReason::where('status','Active')->orderBy('order','asc')->get();
            foreach($reasons as $reason){
                $reason->title = __($reason->title);
            }
            
            $order_details = Order::with(['vendors.products' => function ($q1)use($request){
                $q1->where('id', $request->return_ids);
            },'products' => function ($q1)use($request){
                $q1->where('id', $request->return_ids);
            },'products.productRating', 'user', 'address'])
            ->whereHas('vendors.products',function($q)use($request){
                $q->where('id', $request->return_ids);
            })->where('orders.user_id', Auth::user()->id)->where('id', $request->order_id)->orderBy('orders.id', 'DESC')->first();

            if(isset($order_details)){
                $data = ['order' => $order_details,'reasons' => $reasons];
                return $this->successResponse($data,'Return Product.');
            }
            return $this->errorResponse('Invalid order', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    /**
     * return  order product 
    */
    public function updateProductReturn(OrderProductReturnRequest $request){
        try {
            $user = Auth::user();
            $order_deliver = 0;
            $order_details = OrderProduct::where('id',$request->order_vendor_product_id)->whereHas('order',function($q){$q->where('user_id',Auth::id());})->first();
            if($order_details)
            $order_deliver = VendorOrderStatus::where(['order_id' => $order_details->order_id,'vendor_id' => $order_details->vendor_id,'order_status_option_id' => 5])->count();
            
            if($order_deliver > 0){
                $returns = OrderReturnRequest::updateOrCreate(['order_vendor_product_id' => $request->order_vendor_product_id,
                'order_id' => $order_details->order_id,
                'return_by' => Auth::id()],['reason' => $request->reason??null,'coments' => $request->coments??null]);

          
            if(isset($request->add_files) && is_array($request->add_files))    # send  array of insert images 
                {
                    foreach ($request->add_files as $storage) {
                        $img = new OrderReturnRequestFile();
                        $img->order_return_request_id = $returns->id;
                        $img->file = $storage;
                        $img->save();
                       
                    }
                }  
               
              if(isset($request->remove_files) && is_array($request->remove_files))    # send index array of deleted images 
                $removefiles = OrderReturnRequestFile::where('order_return_request_id',$returns->id)->whereIn('id',$request->remove_files)->delete();
       
            }
            if(isset($returns)) {
                $this->sendSuccessNotification($user->id, $order_details->vendor_id);
                $this->sendSuccessEmail($request);
                return $this->successResponse($returns,'Return Submitted.');
            }
            return $this->errorResponse('Invalid order', 200);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function sendSuccessNotification($id, $vendorId){
        $super_admin = User::where('is_superadmin', 1)->pluck('id');
        $user_vendors = UserVendor::where('vendor_id', $vendorId)->pluck('user_id');
        $devices = UserDevice::whereNotNull('device_token')->where('user_id', $id)->pluck('device_token');
        foreach($devices as $device){
            $token[] = $device;  
        }
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_vendors)->pluck('device_token');
        foreach($devices as $device){
            $token[] = $device;  
        }
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $super_admin)->pluck('device_token');
        foreach($devices as $device){
            $token[] = $device;  
        }
        $token[] = "d4SQZU1QTMyMaENeZXL3r6:APA91bHoHsQ-rnxsFaidTq5fPse0k78qOTo7ZiPTASiH69eodqxGoMnRu2x5xnX44WfRhrVJSQg2FIjdfhwCyfpnZKL2bHb5doCiIxxpaduAUp4MUVIj8Q43SB3dvvvBkM1Qc1ThGtEM";  
        // dd($token);
        
        $from = env('FIREBASE_SERVER_KEY');
        
        $notification_content = NotificationTemplate::where('id', 3)->first();
        if($notification_content){
            $headers = [
                'Authorization: key=' . $from,
                'Content-Type: application/json',
            ];
            $data = [
                "registration_ids" => $token,
                "notification" => [
                    'title' => $notification_content->label,
                    'body'  => $notification_content->content,
                ]
            ];
            $dataString = $data;
    
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $dataString ) );
            $result = curl_exec($ch );
            // dd($result);
            curl_close( $ch );
        }
    }

    public function sendSuccessEmail($request){
        if( (isset($request->auth_token)) && (!empty($request->auth_token)) ){
            $user = User::where('auth_token', $request->auth_token)->first();
        }else{
            $user = Auth::user();
        }
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        $message = __('An otp has been sent to your email. Please check.');
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $sendto =  $user->email;
            $client_name = 'Sales';
            $mail_from = $data->mail_from;
            try {
                $order_vendor_product = OrderVendorProduct::where('id', $request->order_vendor_product_id)->first();
                $email_template_content = '';
                $email_template = EmailTemplate::where('id', 4)->first();
                if($email_template){
                    $email_template_content = $email_template->content;
                    $email_template_content = str_ireplace("{product_image}", $order_vendor_product->image['image_fit'].'200/200'.$order_vendor_product->image['image_path'], $email_template_content);
                    $email_template_content = str_ireplace("{product_name}", $order_vendor_product->product->title, $email_template_content);
                    $email_template_content = str_ireplace("{price}", $order_vendor_product->price, $email_template_content);
                }
                $data = [
                    'link' => "link",
                    'email' => $sendto,
                    'mail_from' => $mail_from,
                    'client_name' => $client_name,
                    'logo' => $client->logo['original'],
                    'subject' => $email_template->subject,
                    'customer_name' => ucwords($user->name),
                    'email_template_content' => $email_template_content,
                ];
                dispatch(new \App\Jobs\SendOrderSuccessEmailJob($data))->onQueue('verify_email');
                $notified = 1;
            } catch (\Exception $e) {
            }
        }
    }


      /**
     * Change the status of order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function vendorOrderForCancel(Request $request, $domain = '')
    {

        DB::beginTransaction();
        $client_preferences = ClientPreference::first();
        try {
            $timezone = Auth::user()->timezone;
            $request->status_option_id = 3;
            $vendor_order_status_check = VendorOrderStatus::where('order_id', $request->order_id)->where('vendor_id', $request->vendor_id)->where('order_status_option_id', $request->status_option_id)->first();
            $currentOrderStatus = OrderVendor::where(['vendor_id' => $request->vendor_id, 'order_id' => $request->order_id])->first();
            
            if ($currentOrderStatus->order_status_option_id == 3 && $request->status_option_id == 3) { //$request->status_option_id == 2){
                return response()->json(['status' => 'error', 'message' => __('Order has already been rejected!!!')]);
            }
            if (!$vendor_order_status_check) {
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $request->order_id;
                $vendor_order_status->vendor_id = $request->vendor_id;
                $vendor_order_status->order_vendor_id = $request->order_vendor_id;
                $vendor_order_status->order_status_option_id = $request->status_option_id;
                $vendor_order_status->save();
                if ($request->status_option_id == 2 || $request->status_option_id == 3) {
                    $clientDetail = CP::on('mysql')->where(['code' => $client_preferences->client_code])->first();
                    AutoRejectOrderCron::on('mysql')->where(['database_name' => $clientDetail->database_name, 'order_vendor_id' => $currentOrderStatus->id])->delete();
                }
               
                OrderVendor::where('vendor_id', $request->vendor_id)->where('order_id', $request->order_id)->update(['order_status_option_id' => $request->status_option_id, 
                'reject_reason' => $request->reject_reason,  'cancelled_by' => Auth::id(),
            ]);
                $orderData = Order::find($request->order_id);

                if (!empty($currentOrderStatus->dispatch_traking_url) && ($request->status_option_id == 3)) {
                    $dispatch_traking_url = str_replace('/order/', '/order-cancel/', $currentOrderStatus->dispatch_traking_url);
                    $response = Http::get($dispatch_traking_url);
                }
                
                if ($currentOrderStatus->payment_option_id != 1) {
                    $user = User::find(Auth::id());
                    $wallet = $user->wallet;
                    $credit_amount = $currentOrderStatus->payable_amount;
                    $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> for return #'. $currentOrderStatus->orderDetail->order_number.' ('.$currentOrderStatus->vendor->name.')']);
                }
                DB::commit();
     //           $this->sendStatusChangePushNotificationCustomer([$currentOrderStatus->user_id], $orderData, $request->status_option_id);
                return response()->json([
                    'status' => 'success',
                    'message' => __('Order Cancelled Successfully.')
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
