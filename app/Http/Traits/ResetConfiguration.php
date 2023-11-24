<?php

namespace App\Http\Traits;

use App\Models\AppStylingOption;
use App\Models\Client;
use App\Models\ClientPreference;
use App\Models\ClientPreferenceAdditional;
use App\Models\WebStylingOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait ResetConfiguration
{
    public $additional_preference;

    public function  updateStylingsAndPreferences($web_styling_id, $app_styling_id)
    {

        // app stylings from 34 to 41
        try {
            DB::beginTransaction();
        
            $web_font = WebStylingOption::where('id', $web_styling_id)->first();
            WebStylingOption::where('id', '!=', $web_font->id)->update(['is_selected' => 0]);
            $web_font->is_selected = 1;
            $web_font->save();
        
            $app_font = AppStylingOption::where('id', $app_styling_id)->first();
            AppStylingOption::where('app_styling_id','!=', $app_font->app_styling_id)->update(['is_selected' => 0]);
            $app_font->is_selected = 1;
            $app_font->save();
        
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // Handle the exception (log or rethrow, depending on your needs)
            throw $e;
        }
    }

    public function initialize()
    {
       
            $client_preference = ClientPreference::select('business_type')->first();
            $client_preference->hide_order_address = 1;
            $client_preference->save();
            $additional_preference= getAdditionalPreference([
                'update_order_product_price',
                'is_bid_enable',
                'is_attribute',
                'is_long_term_service',
                'is_gst_required_for_vendor_registration',
                'is_baking_details_required_for_vendor_registration',
                'is_advance_details_required_for_vendor_registration',
                'is_vendor_category_required_for_vendor_registration',
                'is_seller_module',
                'is_gift_card', 
                'is_tracking_url',
                'is_tracking_sms_url',
                'is_place_order_delivery_zero',
                'is_user_kyc_for_registration',
                'is_cust_success_signup_email',
                'is_file_cart_instructions',
                'is_admin_vendor_rating',
                'is_enable_compare_product',
                'is_service_product_price_from_dispatch',
                'is_service_price_selection',
                'is_particular_driver',
                'is_recurring_booking',
                'is_rental_weekly_monthly_price',
                'is_share_ride_users',
                'is_enable_curb_side',
                'is_enable_allergic_items',
                'is_vendor_marg_configuration',
                'is_role_and_permission_enable',
                'is_car_rental_enable',
                'is_cab_pooling',
                'is_hourly_pickup_rental',
                'is_same_day_delivery',
                'is_next_day_delivery',
                'is_hyper_local_delivery',
                'is_hubspot_enable',
                'stock_notification_before',
                'is_hubspot_enable',
                'is_free_delivery_by_roles',
                'pickup_notification_before'
            ]);

            
            $client = Client::first();

        
            
            foreach ($additional_preference as $key => $value) {
                $preferenceValue = 0; // Default value

                // Set values based on conditions
                switch ($key) {
                    case 'is_cab_pooling':
                        if (in_array($client_preference->business_type, ['taxi', 'super_app'])) {
                            $preferenceValue = 1;
                        }
                        break;
                    case 'is_attribute':
                        if (in_array($client_preference->business_type, ['emart', 'super_app','p2p','rental'])) {
                            $preferenceValue = 1;
                        }
                        break;
                    case 'is_tracking_url':
                        if (in_array($client_preference->business_type, ['taxi', 'super_app','emart'])) {
                            $preferenceValue = 1;
                        }
                        break;

                    case 'is_tracking_sms_url':
                        if (in_array($client_preference->business_type, ['taxi', 'emart', 'super_app'])) {
                            $preferenceValue = 1;
                        }
                        break;
                    case 'is_place_order_delivery_zero':
                        if (in_array($client_preference->business_type, ['taxi', 'emart', 'super_app','rental','food_grocery_ecommerce'])) {
                            $preferenceValue = 1;
                        }
                        break;

                    case 'is_enable_compare_product':
                        if (in_array($client_preference->business_type, ['emart', 'super_app'])) {
                            $preferenceValue = 1;
                        }
                        break;

                    default:
                        break;
                }

                // Update or create the ClientPreferenceAdditional record
                ClientPreferenceAdditional::updateOrCreate(
                    ['key_name' => $key, 'client_code' => $client->code],
                    ['key_name' => $key, 'key_value' => $preferenceValue, 'client_code' => $client->code, 'client_id' => $client->id]
                );
            }
    
    }

    public function  resetDeliveryConfiguration()
    {
        $this->initialize();


        $this->updateStylingsAndPreferences(3,37);

        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();



        $data = [
            'pharmacy_check' => 0,
            'enquire_mode' => 0,
            'isolate_single_vendor_order' => 1,
            'subscription_mode' => 1,
            'subscription_tab_taxi' => 0,
            'tip_before_order' => 1,
            'tip_after_order' => 0,
            'auto_implement_5_percent_tip' => 0,
            'product_order_form' => 0,
            'gifting' => 0,
            'pickup_delivery_service_area' => 0,
            'minimum_order_batch' => 0,
            'static_delivey_fee' => 0,
            'get_estimations' => 0,
            'view_get_estimation_in_category' => 0,
            'max_safety_mod' => 0,
            'hide_order_address' => 1,
            'category_kyc_documents' => 0,
            'vendor_return_request' => 0,
            'hide_order_prepare_time' => 0,
            'is_cancel_order_user' => 1,
            'book_for_friend' => 0,
            'is_static_dropoff' => 0,
            'is_scan_qrcode_bag' => 0,
            'is_vendor_tags' => 0,
            'is_service_area_for_banners' => 0,
            'stop_order_acceptance_for_users' => 0,
            'map_on_search_screen' => 0,
            'slots_with_service_area' => 0,
        ];

        $preference->update($data);

        \Illuminate\Support\Facades\Redis::flushall();
    }

    public function  resetRentalConfiguration()
    {
        $this->initialize();
        $this->updateStylingsAndPreferences(1,34);

        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();


        $data = [
            'enquire_mode' => 0,
            'pharmacy_check' => 0,
            'isolate_single_vendor_order' => 1,
            'subscription_mode' => 0,
            'subscription_tab_taxi' => 0,
            'tip_before_order' => 0,
            'tip_after_order' => 0,
            'auto_implement_5_percent_tip' => 0,
            'product_order_form' => 0,
            'gifting' => 0,
            'pickup_delivery_service_area' => 0,
            'minimum_order_batch' => 0,
            'static_delivey_fee' => 0,
            'get_estimations' => 0,
            'view_get_estimation_in_category' => 0,
            'max_safety_mod' => 0,
            'hide_order_address' => 0,
            'category_kyc_documents' => 0,
            'vendor_return_request' => 0,
            'hide_order_prepare_time' => 0,
            'is_cancel_order_user' => 0,
            'book_for_friend' => 0,
            'is_static_dropoff' => 0,
            'is_scan_qrcode_bag' => 0,
            'is_vendor_tags' => 0,
            'is_service_area_for_banners' => 0,
            'stop_order_acceptance_for_users' => 0,
            'map_on_search_screen' => 0,
            'slots_with_service_area' => 0,
        ];

        $preference->update($data);

        \Illuminate\Support\Facades\Redis::flushall();

    }
    public function  resetPickDropConfiguration()
    {
        $this->initialize();
        $this->updateStylingsAndPreferences(3,35);

        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();


        $data = [
            'enquire_mode' => 0,
            'pharmacy_check' => 0,
            'isolate_single_vendor_order' => 1,
            'subscription_mode' => 0,
            'subscription_tab_taxi' => 0,
            'tip_before_order' => 0,
            'tip_after_order' => 0,
            'auto_implement_5_percent_tip' => 0,
            'product_order_form' => 0,
            'gifting' => 0,
            'pickup_delivery_service_area' => 0,
            'minimum_order_batch' => 0,
            'static_delivey_fee' => 0,
            'get_estimations' => 0,
            'view_get_estimation_in_category' => 0,
            'max_safety_mod' => 0,
            'hide_order_address' => 0,
            'category_kyc_documents' => 0,
            'vendor_return_request' => 0,
            'hide_order_prepare_time' => 0,
            'is_cancel_order_user' => 0,
            'book_for_friend' => 1,
            'is_static_dropoff' => 0,
            'is_scan_qrcode_bag' => 0,
            'is_vendor_tags' => 0,
            'is_service_area_for_banners' => 0,
            'stop_order_acceptance_for_users' => 0,
            'map_on_search_screen' => 0,
            'slots_with_service_area' => 0,
        ];

        $preference->update($data);

        \Illuminate\Support\Facades\Redis::flushall();

    }
    public function  resetOnDemandConfiguration()
    {
        $this->initialize();
        $this->updateStylingsAndPreferences(5,39);

        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();

        
        $data = [
            'enquire_mode' => 0,
            'pharmacy_check' => 0,
            'isolate_single_vendor_order' => 1,
            'subscription_mode' => 0,
            'subscription_tab_taxi' => 0,
            'tip_before_order' => 0,
            'tip_after_order' => 0,
            'auto_implement_5_percent_tip' => 0,
            'product_order_form' => 0,
            'gifting' => 0,
            'pickup_delivery_service_area' => 0,
            'minimum_order_batch' => 0,
            'static_delivey_fee' => 0,
            'get_estimations' => 0,
            'view_get_estimation_in_category' => 0,
            'max_safety_mod' => 0,
            'hide_order_address' => 0,
            'category_kyc_documents' => 0,
            'vendor_return_request' => 0,
            'hide_order_prepare_time' => 0,
            'is_cancel_order_user' => 0,
            'book_for_friend' => 0,
            'is_static_dropoff' => 0,
            'is_scan_qrcode_bag' => 0,
            'is_vendor_tags' => 0,
            'is_service_area_for_banners' => 0,
            'stop_order_acceptance_for_users' => 0,
            'map_on_search_screen' => 0,
            'slots_with_service_area' => 0,
        ];

        $preference->update($data);

        \Illuminate\Support\Facades\Redis::flushall();

    }
    public function  resetLaundryConfiguration()
    {
        $this->initialize();
        $this->updateStylingsAndPreferences(5,39);

        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();


        $data = [
            'enquire_mode' => 0,
            'pharmacy_check' => 0,
            'isolate_single_vendor_order' => 1,
            'subscription_mode' => 0,
            'subscription_tab_taxi' => 0,
            'tip_before_order' => 0,
            'tip_after_order' => 0,
            'auto_implement_5_percent_tip' => 0,
            'product_order_form' => 0,
            'gifting' => 0,
            'pickup_delivery_service_area' => 0,
            'minimum_order_batch' => 0,
            'static_delivey_fee' => 0,
            'get_estimations' => 0,
            'view_get_estimation_in_category' => 0,
            'max_safety_mod' => 0,
            'hide_order_address' => 0,
            'category_kyc_documents' => 0,
            'vendor_return_request' => 0,
            'hide_order_prepare_time' => 0,
            'is_cancel_order_user' => 0,
            'book_for_friend' => 0,
            'is_static_dropoff' => 0,
            'is_scan_qrcode_bag' => 0,
            'is_vendor_tags' => 0,
            'is_service_area_for_banners' => 0,
            'stop_order_acceptance_for_users' => 0,
            'map_on_search_screen' => 0,
            'slots_with_service_area' => 0,
        ];

        $preference->update($data);

        \Illuminate\Support\Facades\Redis::flushall();

    }

    public function  resetP2PConfiguration()
    {
        $this->initialize();
        $this->updateStylingsAndPreferences(7,38);

        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();


        $data = [
            'enquire_mode' => 0,
            'pharmacy_check' => 0,
            'isolate_single_vendor_order' => 1,
            'subscription_mode' => 0,
            'subscription_tab_taxi' => 0,
            'tip_before_order' => 0,
            'tip_after_order' => 0,
            'auto_implement_5_percent_tip' => 0,
            'product_order_form' => 0,
            'gifting' => 0,
            'pickup_delivery_service_area' => 0,
            'minimum_order_batch' => 0,
            'static_delivey_fee' => 0,
            'get_estimations' => 0,
            'view_get_estimation_in_category' => 0,
            'max_safety_mod' => 0,
            'hide_order_address' => 0,
            'category_kyc_documents' => 0,
            'vendor_return_request' => 0,
            'hide_order_prepare_time' => 0,
            'is_cancel_order_user' => 0,
            'book_for_friend' => 0,
            'is_static_dropoff' => 0,
            'is_scan_qrcode_bag' => 0,
            'is_vendor_tags' => 0,
            'is_service_area_for_banners' => 0,
            'stop_order_acceptance_for_users' => 0,
            'map_on_search_screen' => 0,
            'slots_with_service_area' => 0,
        ];

        $preference->update($data);

        \Illuminate\Support\Facades\Redis::flushall();

    }
    public function  resetEmartConfiguration()
    {
        $this->initialize();
        $this->updateStylingsAndPreferences(6,40);

        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();
       


        $data = [
            'enquire_mode' => 0,
            'pharmacy_check' => 0,
            'isolate_single_vendor_order' => 1,
            'subscription_mode' => 0,
            'subscription_tab_taxi' => 0,
            'tip_before_order' => 1,
            'tip_after_order' => 0,
            'auto_implement_5_percent_tip' => 0,
            'product_order_form' => 0,
            'gifting' => 0,
            'pickup_delivery_service_area' => 0,
            'minimum_order_batch' => 0,
            'static_delivey_fee' => 0,
            'get_estimations' => 0,
            'view_get_estimation_in_category' => 0,
            'max_safety_mod' => 0,
            'hide_order_address' => 0,
            'category_kyc_documents' => 0,
            'vendor_return_request' => 1,
            'hide_order_prepare_time' => 0,
            'is_cancel_order_user' => 1,
            'book_for_friend' => 0,
            'is_static_dropoff' => 0,
            'is_scan_qrcode_bag' => 0,
            'is_vendor_tags' => 0,
            'is_service_area_for_banners' => 0,
            'stop_order_acceptance_for_users' => 0,
            'map_on_search_screen' => 0,
            'slots_with_service_area' => 0,
        ];

        $preference->update($data);

        \Illuminate\Support\Facades\Redis::flushall();

    }
    public function  resetSuperAppConfiguration()
    {
 
        
        $this->initialize();
        $this->updateStylingsAndPreferences(3,34);

        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();

          $data = [
            'enquire_mode' => 0,
            'pharmacy_check' => 0,
            'isolate_single_vendor_order' =>1,
            'subscription_mode' => 0,
            'subscription_tab_taxi' => 0,
            'tip_before_order' => 1,
            'tip_after_order' => 0,
            'auto_implement_5_percent_tip' => 0,
            'product_order_form' => 0,
            'gifting' => 0,
            'pickup_delivery_service_area' => 0,
            'minimum_order_batch' => 0,
            'static_delivey_fee' => 0,
            'get_estimations' => 0,
            'view_get_estimation_in_category' => 0,
            'max_safety_mod' => 0,
            'hide_order_address' => 0,
            'category_kyc_documents' => 0,
            'vendor_return_request' => 1,
            'hide_order_prepare_time' => 0,
            'is_cancel_order_user' => 1,
            'book_for_friend' => 1,
            'is_static_dropoff' => 0,
            'is_scan_qrcode_bag' => 0,
            'is_vendor_tags' => 0,
            'is_service_area_for_banners' => 0,
            'stop_order_acceptance_for_users' => 0,
            'map_on_search_screen' => 0,
            'slots_with_service_area' => 0,
        ];

        $preference->update($data);

        \Illuminate\Support\Facades\Redis::flushall();

    }


}
