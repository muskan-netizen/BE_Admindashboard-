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
        $pickup = $request->pickup;
        $dropOff = $request->dropOff;
        $data = $this->productSearch($request, (object) $pickup, (object) $dropOff);

        $fields = [];
        foreach ($data['products'] as $products) {
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
                $products->cabins = $fields['Cabins'] ?? '';
                $products->baths = $fields['Baths'] ?? '';
                $products->Berths = $fields['Berths'] .' Berths'?? '';
            }
        }
        return response()->json(['status' => 200, 'message' => 'Product List', 'data' => $data]);
    }
}
