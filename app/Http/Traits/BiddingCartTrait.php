<?php
namespace App\Http\Traits;

use App\Models\Bid;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\ClientCurrency;
use App\Models\BidProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Front\CartController;
trait biddingCartTrait{

    protected function biddingCart($id)
    {
        $user_id = ' ';
        $cartInfo = ' ';
        $user = Auth::user();
        $bid_products = BidProduct::where('bid_id', $id)->with('product.variant')->get();
        $bid_vendors = Bid::where('id',$id)->first();
        $currency = ClientCurrency::where('is_primary', '=', 1)->first();
        // if ($user) {
        //     $user_id = $user->id;
        //     $userFind = Cart::where('user_id', $user_id)->first();
        //     if (!$userFind) {
                $cart = new Cart;
                $cart->status = '0';
                $cart->is_gift = '1';
                $cart->item_count = '1';
                $cart->user_id = $user->id;
                $cart->created_by = $user->id;
                $cart->currency_id = $currency->currency->id;
                $cart->unique_identifier = $user->system_id;
                $cart->save();
                // $cartInfo = $cart;
            // } else {
            //     $cartInfo = $userFind;
            // }

            // foreach($bid_products as $product) {
            //     $checkIfExist = CartProduct::where('product_id', $product->product_id)->where('cart_id', $cartInfo->id)->first();
            //     if ($checkIfExist) {

            //         $checkIfExist->quantity = (int)$checkIfExist->quantity + 1;
            //         $cartInfo->cartProducts()->save($checkIfExist);
            //         return response()->json(['status' => 'success', 'message' => 'Product Added Successfully!']);
            //     } else {
            //     }
            // }
            // if ($checkIfExist) {
            //     $checkIfExist->quantity = (int)$checkIfExist->quantity + 1;
            //     $cartInfo->cartProducts()->save($checkIfExist);
            //     return response()->json(['status' => 'success', 'message' => 'Product Added Successfully!']);
            // } else {
            // }
        // } else {
            // $cart_detail = Cart::where('unique_identifier', session()->get('_token'))->first();
            // if (!$cart_detail) {
            //     $cart = new Cart;
            //     $cart->status = '0';
            //     $cart->is_gift = '1';
            //     $cart->item_count = '1';
            //     $cart->currency_id = $currency->currency->id;
            //     $cart->unique_identifier = session()->get('_token');
            //     $cart->save();
            // }
                $CartController  = new CartController();
            foreach($bid_products as $product) {
                $newRequest = new Request();

                $newRequest->merge(['product_id'=> $product->product_id, 'quantity'=>$product->quantity, 'variant_id'=>$product->product->variant[0]->id, 'vendor_id'=>$product->product->vendor_id]);
                $data = $CartController->postAddToCart($newRequest);
            }

            return response()->json(['status' => 'success', 'message' => 'Product Added Successfully!',]);
    }

    public function searchProduct($language_id=1,$keyword='',$vendor_ids=[]){
        $products = Product::with(['media', 'vendor','variant'])->join('product_translations as pt', 'pt.product_id', 'products.id')->join('vendors', 'vendors.id', 'products.vendor_id')
        ->select('products.id', 'products.sku', 'products.url_slug', 'pt.title  as dataname', 'pt.body_html', 'pt.meta_title', 'pt.meta_keyword', 'pt.meta_description', 'products.vendor_id', 'vendors.slug as vendor_slug')
        ->where('pt.language_id', $language_id)
       // ->where('products.vendor_id', $prod_vendor)
        ->where(function ($q) use ($keyword) {
            $q->where('products.sku', ' LIKE', '%' . $keyword . '%')->orWhere('products.url_slug', 'LIKE', '%' . $keyword . '%')->orWhere('pt.title', 'LIKE', '%' . $keyword . '%');
        })->where('products.is_live', 1);

        //if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
        $products = $products->whereIn('vendor_id', $vendor_ids);
        //}
        $products = $products->whereNull('deleted_at')->groupBy('products.id')->get();

        $product_results = [];
        foreach ($products as $product) {
            $redirect_url = route('productDetail', [$product->vendor_slug, $product->url_slug]);
            $image_url = $product->media->first() ? $product->media->first()->image->path['proxy_url'] . '80/80' . $product->media->first()->image->path['image_path'] : '';
            $product_results[] = ['id' => $product->id, 'name' => $product->dataname , 'price' =>$product->variant[0]->price,];
        }
        $response =[];
        if (@$product_results) {
            $response[] = ['title' => '', 'result' => $product_results];
        }
        return $response;
    }
}
