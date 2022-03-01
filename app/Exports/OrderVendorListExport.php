<?php

namespace App\Exports;

use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderVendorListExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $vendors = Vendor::with(['orders'])->where('status', '!=', '2')->orderBy('id', 'desc');
        
        if (Auth::user()->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }

        $vendors = $vendors->get();
        foreach ($vendors as $vendor) {
            $vendor->total_paid = 0.00;
            $vendor->delivery_fee = decimal_format($vendor->orders->sum('delivery_fee'));
            $vendor->order_value = decimal_format($vendor->orders->sum('payable_amount'));
            $vendor->payment_method = decimal_format($vendor->orders->whereIn('payment_option_id', [2,3, 4])->sum('payable_amount'));
            $vendor->promo_admin_amount = decimal_format($vendor->orders->where('coupon_paid_by', 1)->sum('discount_amount'));
            $vendor->promo_vendor_amount = decimal_format($vendor->orders->where('coupon_paid_by', 0)->sum('discount_amount'));
            $vendor->cash_collected_amount = decimal_format($vendor->orders->where('payment_option_id', 1)->sum('payable_amount'));
            $vendor->admin_commission_amount = decimal_format($vendor->orders->sum('admin_commission_percentage_amount') + $vendor->orders->sum('admin_commission_percentage_amount'));
            $admin_commission_amount = $vendor->orders->sum('admin_commission_percentage_amount')+ $vendor->orders->sum('admin_commission_percentage_amount');
            $vendor->vendor_earning = decimal_format(($vendor->orders->sum('payable_amount') - $vendor->promo_vendor_amount - $vendor->promo_admin_amount - $admin_commission_amount));
        }
        return $vendors;
    }

    public function headings(): array{
        return [
            'Vendor Name',
            'Order Value (Without Delivery Fee)',
            'Delivery Fees',
            'Admin Commissions',
            'Promo [Vendor]',
            'Promo [Admin]',
            'Cash Collected',
            'Payment Gateway',
            'Vendor Earning'
        ];
    }

    public function map($orders): array
    {
        return [
            $orders->name ? $orders->name : '',
            $orders->order_value,
            $orders->delivery_fee,
            $orders->admin_commission_amount,
            $orders->promo_vendor_amount,
            $orders->promo_admin_amount,
            $orders->cash_collected_amount,
            $orders->payment_method,
            $orders->vendor_earning
        ];
    }

}
