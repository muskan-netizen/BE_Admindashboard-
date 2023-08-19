<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\{ClientPreference,ClientPreferenceAdditional,Client, ProductDeliveryFeeByRole, Product};
use GuzzleHttp\Client as GCLIENT;
use Log;
use Storage;

trait ClientPreferenceManager{


  public $client_preference_fillable_key = ['is_price_by_role','is_phone_signup', 'token_currency', 'is_token_currency_enable', 'hubspot_access_token', 'is_hubspot_enable', 'gtag_id', 'fpixel_id','is_long_term_service', 'is_free_delivery_by_roles', 'is_cab_pooling', 'is_attribute', 'is_gst_required_for_vendor_registration', 'is_baking_details_required_for_vendor_registration', 'is_advance_details_required_for_vendor_registration', 'is_vendor_category_required_for_vendor_registration', 'is_seller_module', 'is_same_day_delivery', 'is_next_day_delivery', 'is_hyper_local_delivery', 'is_cod_payment', 'is_prepaid_payment', 'is_partial_payment', 'add_to_cart_btn', 'chat_button', 'call_button', 'seller_sold_title','saller_platform_logo','is_tracking_url','is_tracking_sms_url', 'is_tax_price_inclusive', 'is_postpay_enable', 'is_order_edit_enable', 'order_edit_before_hours','is_gift_card', 'is_place_order_delivery_zero', 'is_cust_success_signup_email','is_influencer_refer_and_earn','is_bid_enable','advance_booking_amount','advance_booking_amount_percentage','update_order_product_price', 'is_bid_ride_enable', 'is_one_push_book_enable', 'bid_expire_time_limit_seconds',  'is_corporate_user', 'is_user_kyc_for_registration','is_service_product_price_from_dispatch','is_recurring_booking','is_file_cart_instructions','is_admin_vendor_rating', 'square_enable_status', 'square_credentials','is_show_vendor_on_subcription','is_enable_compare_product','is_service_price_selection','is_particular_driver', 'pickup_notification_before', 'pickup_notification_before_hours','pickup_notification_before2', 'pickup_notification_before2_hours','is_enable_curb_side','is_map_search_perticular_country','marg_access_token', 'is_marg_enable', 'marg_company_code', 'marg_decrypt_key','stock_notification_before','stock_notification_qunatity','marg_company_url'];



  # get last mile teams
  public function getLastMileTeams(){
    try {
      $dispatch_domain = $this->checkIfLastMileOn();
      if ($dispatch_domain && $dispatch_domain != false) {
        $unique = Auth::user()->code;
        $client = new GCLIENT(['headers' =>
          [
            'personaltoken' => $dispatch_domain->delivery_service_key,
            'shortcode' => $dispatch_domain->delivery_service_key_code,
            'content-type' => 'application/json'
          ]
        ]);
        $url = $dispatch_domain->delivery_service_key_url;
        $res = $client->get($url.'/api/get-all-teams');
        $response = json_decode($res->getBody(), true);
        if($response && $response['message'] == 'success'){
            return $response['teams'];
        }
      }
    }
    catch(\Exception $e){
    }
  }
  # check if last mile delivery on
  public function checkIfLastMileOn(){
    $preference = ClientPreference::first();
    if($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
      return $preference;
    else
      return false;
  }

  /**
   * updatePreferenceAdditional
   *
   * @param  mixed $$request
   * @return void
   * harbans :)
   *
   */
  public function updatePreferenceAdditional($request=[]){
    $validated_keys = $request->only($this->client_preference_fillable_key);
    $client = Client::first();

    foreach($validated_keys as $key => $value){
      if ($key == 'saller_platform_logo') {
        if ($request->hasFile('saller_platform_logo')) { /* upload logo file */
          $file = $request->file('saller_platform_logo');
          $value = $this->uploadFile($file);
        }
      }
   
        ClientPreferenceAdditional::updateOrCreate(
            ['key_name' => $key, 'client_code' => $client->code],
            ['key_name' => $key, 'key_value' => $value,'client_code' => $client->code,'client_id'=> $client->id]);
    }
    return 1;
  }

  public function updateFreeDeliveryForRoles($apply_free_del_arr){

    if(isset($apply_free_del_arr) && count($apply_free_del_arr) > 0){
      $products = Product::where('is_live', 1)->get();

      $save_data = [];
      ProductDeliveryFeeByRole::where('is_free_delivery', 1)->delete();
      foreach($apply_free_del_arr as $apply_free_del){
        

        foreach($products as $product){
          array_push($save_data,[ 'product_id' => $product->id, 'role_id' => $apply_free_del, 'is_free_delivery' => 1 ] );
        }

      }

      ProductDeliveryFeeByRole::insert($save_data);

    }

    return 1;
  }
  public function uploadFile($file){
      return Storage::disk('s3')->put('/vendor', $file, 'public');
  }

  function getAdditionalPreference($key=array()){
    setUserCode();
    $return = [];
    $dbreturn= [];
    if(sizeof($key)){
        $result = ClientPreferenceAdditional::select('key_name','key_value')->whereIn('key_name',$key)->get();
        $return = array_column($result->toArray(), 'key_value', 'key_name');
        if (sizeof($result)) {
            $dbreturn = array_column($result->toArray(), 'key_value', 'key_name');
        }
        $emp = array_diff($key, array_keys($dbreturn));
        $emptyArr = array_fill_keys($emp, 0);
        $return = array_merge($emptyArr, $dbreturn);
    }
    return $return;
}
}
