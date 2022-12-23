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
    }
// }
