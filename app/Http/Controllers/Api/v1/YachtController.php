<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\YachtTrait;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class YachtController extends Controller
{
    use YachtTrait;
    public function productsSearchResult(Request $request)
    {
        $data = [];
        $pickup = $request->pickup ?? (object) [];
        $dropOff = $request->dropOff ?? (object) [];
        $data = $this->productSearch($request, (object) $pickup, (object) $dropOff);

        $fields = [];
        foreach ($data['products'] as $products) {
            $allReviews = array_column($products->vendor->products()->with('reviews')->get()->toArray(),'reviews');
            $rating = array_sum(array_column($allReviews,'rating'));
            $products->rating = $rating;
    
            foreach ($products->ProductAttribute as $productAttribute) {
                if ($productAttribute->attributeOption()->exists()) {
                    if(!empty($title = $productAttribute->attributeOption->title)){
                        $fields[$productAttribute->key_name] = $title;
                    }else{
                        $fields[$productAttribute->key_name] = $productAttribute->key_value;
                    }
                }
            }
            if($request->service == 'rental'){
                $products->transmission = $fields['Transmission'] ?? '';
                $products->fuel_type = $fields['Fuel Type'] ?? '';
                $products->Seats = $fields['Seats'] .' Seats'?? '';
            }else{
                $products->cabins = $fields['Cabins']. ' Cabins' ?? '0' ;
                $products->baths = $fields['Baths']. ' Baths' ?? '0' ;
                $products->berths = $fields['Berths'].' Berths' ?? '0';
            }
        }
        return response()->json(['status' => 200, 'message' => 'Product List', 'data' => $data]);
    }
}
