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

        $CartController  = new CartController();
        foreach($bid_products as $product) {
            $newRequest = new Request();

            $newRequest->merge(['product_id'=> $product->product_id, 'quantity'=>$product->quantity, 'variant_id'=>$product->product->variant[0]->id, 'vendor_id'=>$product->product->vendor_id]);
            $data = $CartController->postAddToCart($newRequest);
        }

        return response()->json(['status' => 'success', 'message' => 'Product Added Successfully!',]);
    }
}
