<?php
namespace App\Http\Traits;

use DB;
use HttpRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\{Order,ProductVariant,OrderVendor,VendorOrderCancelReturnPayment};

trait OrderTrait{

    public function ProductVariantStock($order_id)
    {

        $order = Order::with(['vendors.products.pvariant'])->find($order_id);
        if( isset($order->vendors )){
            foreach ($order->vendors as $vendor) {
                foreach ($vendor->products as $product) {
                    $ProductVariant = ProductVariant::find($product->variant_id);
                    if ($ProductVariant) {
                        $ProductVariant->quantity  = $ProductVariant->quantity - $product->quantity;
                        $ProductVariant->save();
                    }
                }
            }
        }
        return 1;
    }

    public function GetVendorReturnAmount($request, $order){
       
        $order_vendor_paybel_amount = OrderVendor::where('order_id',$order->id)->where('order_status_option_id',"!=",'3')->select(DB::raw('sum(payable_amount) AS sum_of_order_payable_amount'))->first();
        $order_total_amount=  $order_vendor_paybel_amount->sum_of_order_payable_amount;
        
        $canceld_order_payments =VendorOrderCancelReturnPayment::where('order_id',$order->id)->select(DB::raw('sum(wallet_amount) AS sum_of_wallet_amount'),DB::raw('sum(online_payment_amount) AS sum_of_online_payment_amount'))->first();
        //pr($canceld_order_payments->toArray());
        $vendor_payble_amount = $order->vendors->first()->payable_amount;
        // vendor contribution in order
        $vendor_contribution_percentage = ($vendor_payble_amount / $order_total_amount) * 100;

        $vendor_loyalty_amount =  $vendor_loyalty_points = $vendor_wallet_amount = $vendor_loyalty_points_earned = $vendor_online_payment_amount = 0;   

        if($order->loyalty_points_used > 0){
            // get loyalty for vendor
            $total_loyalty_amount = $order->loyalty_amount_saved ;
          
            // get loyalty points as pr 1 rup (primery Currency)
            $redeem_points_per_primary_currency =  $order->loyalty_points_used /  $order->loyalty_amount_saved;
            
            // vendot loyalty amount in order
            $vendor_loyalty_amount =  ($total_loyalty_amount * $vendor_contribution_percentage ) / 100;

            // vendor loyalty points in order
            $vendor_loyalty_points  =  ($vendor_loyalty_amount * $redeem_points_per_primary_currency);
        }
        if($order->loyalty_points_earned > 0){
            $total_loyalty_points_earned = $order->loyalty_points_earned ;
            // get perticuler vendor loyalty point earnd 
            $vendor_loyalty_points_earned   =($total_loyalty_points_earned * $vendor_contribution_percentage ) / 100;
        }

        if($order->wallet_amount_used > 0){
            $order_total_wallet_amount =  $order->wallet_amount_used;
            // deduction  canceld order waller amount
            $order_total_wallet_amount = $order_total_wallet_amount -  $canceld_order_payments->sum_of_wallet_amount;

            $vendor_wallet_amount = ($order_total_wallet_amount * $vendor_contribution_percentage ) / 100;
        }
        if($order->payment_status == 1  ){
            $order_total_payable_amount =  $order->payable_amount;
             // deduction  canceld order online payment  amount
             $order_total_payable_amount = $order_total_payable_amount - $canceld_order_payments->sum_of_online_payment_amount;
            //vendo online payment contributuin in order
            $vendor_online_payment_amount = ($order_total_payable_amount * $vendor_contribution_percentage ) / 100;
        }
        
        $vendor_total_sum = $vendor_loyalty_amount +  $vendor_wallet_amount +  $vendor_online_payment_amount ;  

        $data['vendor_return_amount']           = $vendor_wallet_amount + $vendor_online_payment_amount;
        $data['vendor_loyalty_amount']          = $vendor_loyalty_amount;
        $data['vendor_wallet_amount']           = $vendor_wallet_amount;
        $data['vendor_online_payment_amount']   = $vendor_online_payment_amount;
        $data['vendor_total_sum']               = $vendor_total_sum;
        $data['vendor_contribution_percentage'] = $vendor_contribution_percentage;
        $data['vendor_loyalty_points']          = $vendor_loyalty_points;
        $data['vendor_loyalty_points_earned']   = $vendor_loyalty_points_earned;
       // pr($data);
        return  $data;

    }
   

}
