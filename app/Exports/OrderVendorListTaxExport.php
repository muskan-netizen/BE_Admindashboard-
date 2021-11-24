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
    public function collection(){
        $user = Auth::user();
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $vendor_orders =  OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment'])->orderBy('id', 'DESC');
        if (Auth::user()->is_superadmin == 0) {
            $vendor_orders = $vendor_orders->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
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
        return [
            'Order Id',
            'Date & Time',
            'Customer Name',
            'Vendor Name',
            'Subtotal Amount',
            'Promo Code Discount',
            'Admin Commission [Fixed]',
            'Admin Commission [%Age]',
            'Final Amount',
            'Payment Method',
            'Order Status'
        ];
    }

    public function map($order_vendors): array
    {
        return [
            $order_vendors->orderDetail ? $order_vendors->orderDetail->order_number : '',
            $order_vendors->created_date,
            $order_vendors->user_name,
            $order_vendors->vendor ? $order_vendors->vendor->name : '',
            number_format($order_vendors->subtotal_amount, 2),
            number_format($order_vendors->discount_amount, 2),
            number_format($order_vendors->admin_commission_fixed_amount),
            number_format($order_vendors->admin_commission_fixed_amount),
            number_format($order_vendors->payable_amount),
            $order_vendors->orderDetail ? $order_vendors->orderDetail->paymentOption->title : '',
            $order_vendors->order_status,
        ];
    }
}
