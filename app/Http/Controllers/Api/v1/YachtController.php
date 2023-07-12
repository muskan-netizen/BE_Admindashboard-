<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class YachtController extends Controller
{
    public function productsSearchResult(Request $request)
    {
        $request->service;
        $category = Category::where('slug',$request->service)->first();
        if($category){
            $data['products'] = Product::with('variant')->where(function($q) use ($request){
                if(isset($request->pickup_time) && isset($request->drop_time)){
                    $q->where('pickup_time', '<=', $request->pickup_time)
                    ->where('drop_time', '>=', $request->drop_time);
                }
            })->where(function($q) use ($request){
                if($request->seats){
                    $q->where('seats','>=', $request->seats);
                }
            })->where('category_id',$category->id)->get();
        }else{
            $data['products'] = [];
        }
        $data['service'] = $request->service;
        
        return response()->json(['status' => 200, 'message' => 'Product List', 'data' => $data]);
    }
}
