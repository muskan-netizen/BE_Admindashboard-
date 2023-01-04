<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\{BidRequest,Bid, BidProduct};
use App\Models\Cart;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\{ApiResponser,BiddingCartTrait};
class BiddingController extends Controller
{
    use ApiResponser,BiddingCartTrait;
    public function uploadBiddingPrescription(Request $request, $domain = '')
    {
        $user = Auth::user();
        if ($user) {
            if ($request->hasFile('prescriptions')) {
                    
                $file = $request->file('prescriptions');
                //pr($file);
                $folder = 'bid/prescriptions';
                //foreach ($files as $file) {
                    $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                    $s3filePath = '/assets/'.$folder.'/orders' . $file_name;
                    $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                    $url = Storage::disk('s3')->url($path);
                    $BidRequest = new BidRequest();
                    $BidRequest->user_id   =  $user->id; 
                    $BidRequest->description = $request->description;
                    $BidRequest->prescription =  $url;
                    $BidRequest->bid_number = time();
                    $BidRequest->save();
               // }
            }
        }
        return response()->json(['status' => 'success', 'message' => "Uploaded Successfully"]);
    }

    public function getVendorPrescription(Request $request){
        if(!empty($request->prescriptionId) && $request->requestType == 'delete_prescription'){
            BidRequest::where('id', $request->prescriptionId)->delete();
            return response()->json(['status' => 'success', 'message' => "Prescription remove Successfully"]);
        }
        $bidPrescription = BidRequest::get();
        return response()->json($bidPrescription);
    }


    public function getUserPrescription(Request $request){
        $user = Auth::user();
        $langId     = Auth::user()->language;
        $bidPrescription = BidRequest::where('user_id' ,$user->id)->withCount('bids')->with(['bids.vendor','bids.bidProducts.product.translation_one' => function ($q) use ($langId) {
            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
            $q->where('language_id', $langId);
        }])->get();
        return response()->json($bidPrescription);
    }

    public function getbidList($bid_id){
        $user       = Auth::user();
        $langId     = Auth::user()->language;
        $bidPrescription = Bid::where('prescription_id' ,$bid_id)->with(['vendor','bidProducts.product.translation_one' => function ($q) use ($langId) {
            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
            $q->where('language_id', $langId);
        }])->get();
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

            $response  = $this->searchProduct($langId,$keyword,$vendor_ids);
            // $products = Product::byProductCategoryServiceType($action)->join('product_translations as pt', 'pt.product_id', 'products.id')
            //     ->select('products.id', 'products.sku', 'pt.title', 'pt.body_html', 'pt.meta_title', 'pt.meta_keyword', 'pt.meta_description')
            //     ->where('pt.language_id', $langId)
            //     ->whereHas('vendor', function ($query) use ($action) {
            //         $query->where($action, 1);
            //     })
            //     ->where(function ($q) use ($keyword) {
            //         $q->where('products.sku', ' LIKE', '%' . $keyword . '%')
            //             ->orWhere('products.url_slug', 'LIKE', '%' . $keyword . '%')
            //             ->orWhere('pt.title', 'LIKE', '%' . $keyword . '%');
            //     });
            //     $products = $products->where('products.is_live', 1)
            //             ->whereIn('vendor_id', $vendor_ids)
            //             ->whereNull('deleted_at')->groupBy('products.id')
            //             ->paginate($limit, $page);
            // foreach ($products as $product) {
            //     $product->response_type = 'product';
            //     $response[] = $product;
            // }
            return $this->successResponse($response);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    public function addBidProductToCart(Request $request)
    {
        $this->biddingCart($request->bid);
        return response()->json(['status' => 'success', 'message' => __("Bid  product Added successfully")]);
       
    }

    public function bidReject(Request $request)
    {
        $accept = Bid::where('id', $request->bid)->update(['status'=>2]);
        return response()->json(['status' => 'success', 'message' => __("Bid  reject successfully")]);
    }

    public function bidAccept(Request $request,$domain="",$id)
    {
        \Log::info('$request->bid--'.$id);
        $bid_products = BidProduct::where('bid_id', $request->bid)->with('product.variant')->get();
        \Log::info($request->all());
        \Log::info(json_encode($bid_products));
        $CartController  = new CartController();
        foreach($bid_products as $product) {
            $newRequest = new Request();
            $newRequest->merge(['product_id'=> $product->product_id, 'quantity'=>$product->quantity, 'variant_id'=>$product->product->variant[0]->id, 'vendor_id'=>$product->product->vendor_id,'bid_number'=>$vid,'bid_discount'=>$product->bids->discount]);
            $data = $CartController->postAddToCart($newRequest);
        }

        return response()->json(['status' => 'success', 'message' => __("Bid accept successfully")]);

    }

}
