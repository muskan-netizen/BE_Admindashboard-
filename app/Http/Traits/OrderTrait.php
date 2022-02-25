<?php
namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use HttpRequest;
use App\Models\{Order,ProductVariant};

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
   

}
