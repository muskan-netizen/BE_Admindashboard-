<?php

namespace App\Exports;
use App\Models\OrderVendor;
use App\Models\OrderStatusOption;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;



class OrderVendorListTaxExport implements FromCollection,WithHeadings,WithMapping{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $data;

    public function __construct($request)
    {
        $this->data = (object)$request->input();
    }

    public function collection(){
        $user = Auth::user();
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $vendor_orders =  OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment'])->orderBy('id', 'DESC');
        if (Auth::user()->is_superadmin == 0){
            $vendor_orders = $vendor_orders->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        if(isset($this->data->date_range)){
            $date = explode(' to ',$this->data->date_range);
            $dateF = $date[0];
            $dateT = $date[1] ?? $date[0];
            $vendor_orders = $vendor_orders->whereDate('created_at', '>=', $dateF)
            ->whereDate('created_at', '<=', $dateT);
        }
        if(isset($this->data->vendor)){
            $vendor = $this->data->vendor;
            $vendor_orders = $vendor_orders->where('vendor_id',$vendor);
        }
        if(isset($this->data->order_status)){
            $status = $this->data->order_status;
            if($this->data->order_status =='Placed'){
                $status = '1';
            }elseif($this->data->order_status =='Accepted'){
                $status = '2';
            }elseif($this->data->order_status =='Out For Delivery'){
                $status = '5';
            }elseif($this->data->order_status =='Rejected'){
                $status = '3';
            }elseif($this->data->order_status =='Processing'){
                $status = '4';
            }elseif($this->data->order_status =='Delivered'){
                $status = '6';
            }
            $vendor_orders = $vendor_orders->where('order_status_option_id',$status);
        }
        $vendor_orders = $vendor_orders->get();  

        foreach ($vendor_orders as $vendor_order) {
            $vendor_order->created_date = dateTimeInUserTimeZone($vendor_order->created_at, $timezone);
            $vendor_order->user_name = $vendor_order->user ? $vendor_order->user->name : '';
            $order_status = '';
            if($vendor_order->orderstatus){
                $order_status_detail = $vendor_order->orderstatus->where('order_id', $vendor_order->order_id)->orderBy('id', 'DESC')->first();
                if($order_status_detail){
                    $order_status_option = OrderStatusOption::where('id', $order_status_detail->order_status_option_id)->first();
                    if($order_status_option){
                        $order_status = $order_status_option->title;
                    }
                }
            }
            $vendor_order->order_status = $order_status;
        }
        return $vendor_orders;
    }

    public function headings(): array{
        if(Auth::user()->is_superadmin)
        {
            return [
                'Customer ID',
                'Order ID',
                'Transaction ID',
                'Date & Time',
                'Customer Name',
                'Vendor Name',
                'Subtotal Amount',
                'Tip',
                'Promo Code Used',
                'Promo Code Discount',
                'Service Fee',
                'Delivery Fee',
                'Sales Tax',
                'Store Earning',
                'Admin Commission [Fixed]',
                'Admin Commission [%Age]',
                'Final Amount',
                'Redeemed Loyality Points',
                'Rewarded Loyality Points',
                'Platform Revenue',
                'Payment Method',
                'Order Status',
                'Delivery Mode',
                'Pickup Address',
                'Delivery Address'
            ];
        }else{
            return [
                'Customer ID',
                'Order ID',
                'Transaction ID',
                'Date & Time',
                'Customer Name',
                'Vendor Name',
                'Subtotal Amount',
                'Promo Code Used',
                'Promo Code Discount',
                'Service Fee',
                'Delivery Fee',
                'Sales Tax',
                'Store Earning',
                'Admin Commission [Fixed]',
                'Admin Commission [%Age]',
                'Final Amount',
                'Payment Method',
                'Order Status',
                'Delivery Mode',
                'Delivery Address'
            ];

        }
    }

    public function map($order_vendors): array
    {
        if(Auth::user()->is_superadmin)
        {
            return [
                $order_vendors->user_id,
                $order_vendors->orderDetail ? $order_vendors->orderDetail->order_number : '',
                $order_vendors->payment ? $order_vendors->payment->transaction_id : '',
                $order_vendors->created_date,
                $order_vendors->user_name,
                $order_vendors->vendor ? $order_vendors->vendor->name : '',
                number_format($order_vendors->subtotal_amount, 2),
                number_format($order_vendors->orderDetail ? $order_vendors->orderDetail->tip_amount : 0, 2),
                $order_vendors->coupon_code,
                number_format($order_vendors->discount_amount, 2),
                number_format($order_vendors->service_fee_percentage_amount,2),
                number_format($order_vendors->delivery_fee,2),
                number_format($order_vendors->taxable_amount,2),
                number_format($order_vendors->payable_amount - ($order_vendors->admin_commission_percentage_amount + $order_vendors->admin_commission_fixed_amount + $order_vendors->delivery_fee),2),
                number_format($order_vendors->admin_commission_fixed_amount),
                number_format($order_vendors->admin_commission_percentage_amount),
                number_format($order_vendors->payable_amount),
                $order_vendors->orderDetail ? $order_vendors->orderDetail->loyalty_points_used : '',
                $order_vendors->orderDetail ? $order_vendors->orderDetail->loyalty_points_earned : '',
                number_format($order_vendors->admin_commission_percentage_amount + $order_vendors->admin_commission_fixed_amount + $order_vendors->service_fee_percentage_amount,2),
                $order_vendors->orderDetail ? $order_vendors->orderDetail->paymentOption->title : '',
                $order_vendors->order_status,
                $order_vendors->orderDetail->shipping_delivery_type == 'L' ?'Lalamove' :'Dispatcher',
                $order_vendors->orderDetail ? ($order_vendors->orderDetail->address)? $order_vendors->orderDetail->address->house_number.','.$order_vendors->orderDetail->address->city.', '.$order_vendors->orderDetail->address->state : '' : '',
                $order_vendors->vendor ? $order_vendors->vendor->address ?? '' : '',
            ];
        }else{
            return [
                $order_vendors->user_id,
                $order_vendors->orderDetail ? $order_vendors->orderDetail->order_number : '',
                $order_vendors->payment ? $order_vendors->payment->transaction_id : '',
                $order_vendors->created_date,
                $order_vendors->user_name,
                $order_vendors->vendor ? $order_vendors->vendor->name : '',
                number_format($order_vendors->subtotal_amount, 2),
                $order_vendors->coupon_code,
                number_format($order_vendors->discount_amount, 2),
                number_format($order_vendors->service_fee_percentage_amount,2),
                number_format($order_vendors->delivery_fee,2),
                number_format($order_vendors->taxable_amount,2),
                number_format($order_vendors->payable_amount - ($order_vendors->admin_commission_percentage_amount + $order_vendors->admin_commission_fixed_amount),2),
                number_format($order_vendors->admin_commission_fixed_amount),
                number_format($order_vendors->admin_commission_percentage_amount),
                number_format($order_vendors->payable_amount),
                $order_vendors->orderDetail ? $order_vendors->orderDetail->paymentOption->title : '',
                $order_vendors->order_status,
                $order_vendors->orderDetail->shipping_delivery_type == 'L' ?'Lalamove' :'Dispatcher',
                $order_vendors->orderDetail ? ($order_vendors->orderDetail->address)? $order_vendors->orderDetail->address->house_number.','.$order_vendors->orderDetail->address->city.', '.$order_vendors->orderDetail->address->state : '' : '',
            ];

        }
    }
}
