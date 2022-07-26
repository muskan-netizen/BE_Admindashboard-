<?php
namespace App\Http\Traits;

use DB;
use HttpRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\{Order,ProductVariant,OrderVendor,VendorOrderCancelReturnPayment,ClientPreference};

trait ChatTrait{

    public function OrderVendorDetail($request)
    {
        $data = $request->all();
        $order_vendor_id = $data['order_vendor_id'];
        $order_id = $data['order_id'];
        $order = Order::with(array(
            'vendors' => function ($query) use ($order_vendor_id) {
                $query->where('id', $order_vendor_id);
            },'vendors.vendor'
        ))->findOrFail($order_id);
        return  $order;
    }
}
