<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\BidRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BiddingController extends Controller
{
    public function uploadBiddingPrescription(Request $request, $domain = '')
    {
        $user = Auth::user();
        if ($user) {
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            foreach ($request->prescriptions as $prescription) {
                $cart_product_prescription = new BidRequest();
                $cart_product_prescription->description = $request->description;
                $cart_product_prescription->prescription = Storage::disk('s3')->put('prescription', $prescription, 'public');
                $cart_product_prescription->save();
            }
        }
        return response()->json(['status' => 'success', 'message' => "Uploaded Successfully"]);
    }

    public function getVendorPrescription(Request $request){
        if(!empty($request->prescriptionId) && $request->requestType == 'delete_prescription'){
            BidRequest::where('id', $request->prescriptionId)->delete();
            return response()->json(['status' => 'success', 'message' => "Prescription remove Successfully"]);
        }
        $bidPrescription = bidRequest::get()->toArray();
        return response()->json($bidPrescription);
    }


    public function getUserPrescription(Request $request){
        $user = Auth::user();

        if(!empty($request->prescriptionId) && $request->requestType == 'delete_prescription'){
            BidRequest::where('id', $request->prescriptionId)->delete();
            return response()->json(['status' => 'success', 'message' => "Prescription remove Successfully"]);
        }

        $bidPrescription = bidRequest::where('id' ,$user->id)->get()->toArray();
        return response()->json($bidPrescription);
    }

    public function deleteProductPrescription(Request $request){
        if(!empty($request->prescription_id)){
            BidRequest::where('id', $request->prescription_id)->delete();
            return response()->json(['status' => 'success', 'message' => "Prescription remove successfully"]);
        }
    }

    public function search(Request $request, $for = 'all', $dataId = 0)
    {
       // return 1;
        try {
            $keyword = $request->keyword;
            $langId = Auth::user()->language;
            $curId = Auth::user()->language;
            $limit = $request->has('limit') ? $request->limit : 10;
            $page = $request->has('page') ? $request->page : 1;
            $action = $request->has('type') && $request->type ? $request->type : null;

            $vendors = Vendor::where('status','1');
            if (Auth::user()->is_superadmin == 0) {
                $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
            $vendor_ids =  $vendors->pluck('id');


            $products = Product::byProductCategoryServiceType($action)->join('product_translations as pt', 'pt.product_id', 'products.id')
                ->select('products.id', 'products.sku', 'pt.title', 'pt.body_html', 'pt.meta_title', 'pt.meta_keyword', 'pt.meta_description')
                ->where('pt.language_id', $langId)
                ->whereHas('vendor', function ($query) use ($action) {
                    $query->where($action, 1);
                })
                ->where(function ($q) use ($keyword) {
                    $q->where('products.sku', ' LIKE', '%' . $keyword . '%')
                        ->orWhere('products.url_slug', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('pt.title', 'LIKE', '%' . $keyword . '%');
                });
                $products = $products->where('products.is_live', 1)
                        ->whereIn('vendor_id', $vendor_ids)
                        ->whereNull('deleted_at')->groupBy('products.id')
                        ->paginate($limit, $page);
            foreach ($products as $product) {
                $product->response_type = 'product';
                $response[] = $product;
            }
            return $this->successResponse($response);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

}
