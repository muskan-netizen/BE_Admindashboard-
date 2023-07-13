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
            $data['products'] = Product::with(['variant','media.image'])->where(function($q) use ($request){
                if(isset($request->pickup_time) && isset($request->drop_time)){
                    $q->where('pickup_time', '<=', $request->pickup_time)
                    ->where('drop_time', '>=', $request->drop_time);
                }
            })->where(function($q) use ($request){
                if($request->seats){
                    $q->where('seats','>=', $request->seats);
                }
            })->where( function($q) use ($request){
                if(isset($request->location_latitude) && isset($request->location_longitude)){
                    $q->whereHas('vendor.serviceArea',function($q) use ($request){
                        $q->select('id','vendor_id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $request->location_latitude . " " . $request->location_longitude . ")'))");
                    });
                }
            })
            ->with('vendor',function($q) use ($request){
                $q->distanceInMeters($request->location_latitude,$request->location_longitude);
            })
            ->where('category_id',$category->id)->paginate($request->limit ?? 12);
        }else{
            $data['products'] = [];
        }
        $data['service'] = $request->service;
        
        return response()->json(['status' => 200, 'message' => 'Product List', 'data' => $data]);
    }
}
