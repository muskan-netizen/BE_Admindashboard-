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
        foreach ($data['products']->ProductAttribute as $productAttribute) {
            if ($productAttribute->attributeOption()->exists()) {
                if (!empty($title = $productAttribute->attributeOption->title)) {
                    $fields[$productAttribute->key_name] = $title;
                } else {
                    $fields[$productAttribute->key_name] = $productAttribute->key_value;
                }
            }
        }
        $data['attributes'] = $fields;
        return response()->json(['status' => 200, 'message' => 'Product List', 'data' => $data]);
    }
}
