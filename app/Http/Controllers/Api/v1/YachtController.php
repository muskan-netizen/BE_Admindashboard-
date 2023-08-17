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
        return response()->json(['status' => 200, 'message' => 'Product List', 'data' => $data]);
    }
}
