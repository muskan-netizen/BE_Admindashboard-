<?php

namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\{Product,OrderProductRating,ClientPreference};
trait ApiResponser{

    protected function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status'=> 'Success', 
			'message' => $message, 
			'data' => $data
		], $code);
	}

	protected function errorResponse($message = null, $code, $data = null)
	{
		return response()->json([
			'status'=>'Error',
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function updateaverageRating($product_id, $message = null, $code = 200)
	{	
		$ava_rating = OrderProductRating::where(['status' => '1','product_id' => $product_id])->avg('rating');
		$up_rat = Product::where('id',$product_id)->update(['averageRating' => $ava_rating]);
		return response()->json([
			'status'=>'Success',
			'message' => $message,
			'data' => $up_rat
		], $code);
	}



	  # check if last mile delivery on 
	  public function checkIfPickupDeliveryOnCommon(){
        $preference = ClientPreference::select('id','need_dispacher_ride','pickup_delivery_service_key','pickup_delivery_service_key_code','pickup_delivery_service_key_url')->first();
        if($preference->need_dispacher_ride == 1 && !empty($preference->pickup_delivery_service_key) && !empty($preference->pickup_delivery_service_key_code) && !empty($preference->pickup_delivery_service_key_url))
            return $preference;
        else
            return false;
    }



	 # check if on demand service  on 
	 public function checkIfOnDemandOnCommon(){
        $preference = ClientPreference::select('id','need_dispacher_home_other_service','dispacher_home_other_service_key','dispacher_home_other_service_key_code','dispacher_home_other_service_key_url')->first();
        if($preference->need_dispacher_home_other_service == 1 && !empty($preference->dispacher_home_other_service_key) && !empty($preference->dispacher_home_other_service_key_code) && !empty($preference->dispacher_home_other_service_key_url))
            return $preference;
        else
            return false;
    }
	

}