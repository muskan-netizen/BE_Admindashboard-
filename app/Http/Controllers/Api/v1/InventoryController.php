<?php

namespace App\Http\Controllers\Api\v1;

use Config, DB;
use App\Http\Controllers\Controller;
use App\Jobs\SyncToDispatcher;
use App\Models\Category;
use App\Models\ClientPreference;
use App\Models\Vendor;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function getUnAssignedOrderCategory(Request $request)
    {
       try{
            if(isset($request->assigned_order_side_vendor_id) && is_array($request->assigned_order_side_vendor_id)){
                $unAssignedOrderCategory = Vendor::select('id', 'name')->whereNotIn('id', $request->assigned_order_side_vendor_id)->where('status', 1)->get();

                return response()->json([
                    'status' => 200,
                    'message' => 'fetched succesfully',
                    'data' => $unAssignedOrderCategory
                ]);
            }else{
                throw new \ErrorException('parameter missing', 400);
            }
            
       }catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
                'data' => []
            ]);
       }
           
    }

    public function getOrderVendorById(Request $request)
    {
        try{
            if(@$request->vendor_id){
                $vendor = Vendor::select('id', 'name')->where('id', $request->vendor_id)->first();
                return response()->json([
                    'status' => 200,
                    'message' => 'fetched succesfully',
                    'data' => $vendor
                ]);
            }else{
                throw new \ErrorException('parameter missing', 400);
            }
                
        }catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function getOrderVendors(Request $request)
    {
        try{
           
                $vendors = Vendor::select('id', 'name')->where('status', 1)->get();;
                return response()->json([
                    'status' => 200,
                    'message' => 'fetched succesfully',
                    'data' => $vendors
                ]);
            
                
        }catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function getOrderCategories(Request $request)
    {
        try{
           
                $categories = Category::where('id', '>', '1')->where('is_core', 1)->orderBy('parent_id', 'asc')->orderBy('position', 'asc')->where('deleted_at', NULL)->where('status', 1)->get();
                return response()->json([
                    'status' => 200,
                    'message' => 'fetched succesfully',
                    'data' => $categories
                ]);
            
                
        }catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function getOrderVendorCategories(Request $request)
    {
        try{
            if(@$request->vendor_id){
           
                $order_categories = \DB::table('categories')
                    ->select('categories.slug', 'categories.id')
                    ->leftjoin('vendor_categories', 'categories.id', '=', 'vendor_categories.category_id')
                    ->where('categories.id', '>', '1')
                    ->where('categories.is_core', 1)->orderBy('categories.parent_id', 'asc')
                    ->orderBy('categories.position', 'asc')
                    ->where('categories.deleted_at', NULL)
                    ->where('categories.status', 1)
                    ->where('vendor_categories.status', 1)
                    ->where('vendor_categories.vendor_id',$request->vendor_id)
                    ->get();
                return response()->json([
                    'status' => 200,
                    'message' => 'fetched succesfully',
                    'data' => $order_categories
                ]);
            }else{
                throw new \ErrorException('parameter missing', 400);
            }
                
        }catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }
    

    public function syncVendorCategoryProducts(Request $request)
    {
        try{

            return response()->json([
                'status' => 200,
                'message' => 'fetched succesfully',
                'data' => $request->all()
            ]);
            if(@$request->vendor_id){
           
                
                return response()->json([
                    'status' => 200,
                    'message' => 'fetched succesfully',
                    // 'data' => $order_categories
                ]);
            }else{
                throw new \ErrorException('parameter missing', 400);
            }
                
        }catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

}
