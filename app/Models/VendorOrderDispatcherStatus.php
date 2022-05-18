<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorOrderDispatcherStatus extends Model
{
    protected $table = 'vendor_order_dispatcher_statuses';

    protected $fillable = [
        'dispatcher_id', 'order_id', 'dispatcher_status_option_id', 'vendor_id','type'
    ];



    protected $appends = ['status_data'];
    public function getStatusDataAttribute()
    {
       $dispatcher_status_option = $this->attributes['dispatcher_status_option_id'];
       $type = $this->attributes['type'];

       $check = ClientPreference::orderBy('id','asc')->select('business_type')->first();
       $business_type = $check->business_type ?? null;

       $status_data = [];

       switch ($dispatcher_status_option) {
        case 1:
            if($business_type == 'home_service'){
                $status_data['icon'] = asset('assets/icons/driver_1_1.png');
                $status_data['driver_status'] = __('Service Accepted');
            }else{
                if ($type == '1') {
                    $status_data['icon'] = asset('assets/icons/driver_1_1.png');
                    $status_data['driver_status'] = __('Order Accepted');
                } else {
                }
            }
            
        break;
        case 2:
            if($business_type == 'home_service'){
                $status_data['icon'] = asset('assets/icons/driver_2_1.png');
                $status_data['driver_status'] = __('Driver assigned');
            }else{
                if ($type == '1') {
                    $status_data['icon'] = asset('assets/icons/driver_2_1.png');
                    $status_data['driver_status'] = __('Driver assigned');
                } else {
                }
            }
        
        break;
        case 3:
            if($business_type == 'home_service'){
                $status_data['icon'] = asset('assets/icons/driver_3_1.png');
                $status_data['driver_status'] = __('Driver heading to  you');
            }else{
                if ($type == '1') {
                    $status_data['icon'] = asset('assets/icons/driver_3_1.png');
                    $status_data['driver_status'] = __('Driver heading to the store');
                } else {
                    $status_data['icon'] = asset('assets/icons/driver_3_2.png');
                    $status_data['driver_status'] = __('Driver heading to you');
                }
            }
            
        break;
        case 4:
            if($business_type == 'home_service'){
                $status_data['icon'] = asset('assets/icons/driver_4_2.png');
                $status_data['driver_status'] = __('Driver arrived at your location');
            }else{
                if ($type == '1') {
                    $status_data['icon'] = asset('assets/icons/driver_4_1.png');
                    $status_data['driver_status'] = __('Driver waiting for your order');
                } else {
                    $status_data['icon'] = asset('assets/icons/driver_4_2.png');
                    $status_data['driver_status'] = __('Driver arrived at your location');
                }
            }
            
        break;
        case 5:
            if($business_type == 'home_service'){
                $status_data['icon'] = asset('assets/icons/driver_5_1.png');
                $status_data['driver_status'] = __('Service Completed');
            }else{
                if ($type == '1') {
                    $status_data['icon'] = asset('assets/icons/driver_5_1.png');
                    $status_data['driver_status'] = __('Order delivered');
                } else {
                    $status_data['icon'] = asset('assets/icons/driver_5_2.png');
                    $status_data['driver_status'] = __('Order delivered');
                }
            }
            
        break;
        default:
        $status_data['icon'] = '';
        $status_data['driver_status'] = '';
       }

       return $status_data;

    }
}
