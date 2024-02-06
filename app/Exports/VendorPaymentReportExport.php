<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\OrderVendor;
use Auth;
use Carbon\Carbon;
class VendorPaymentReportExport implements WithMapping,WithHeadings,FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment']);
        $vendor_orders = $vendor_orders->where('created_at', '>=', Carbon::now()->subHours(24))->groupBy('vendor_id')->selectRaw('vendor_id, COUNT(*) as order_count, SUM(payable_amount) as total_payable_amount')->get();
        return $vendor_orders;
    }

    public function headings(): array{
        return [
            'Vendor Id',
            'Vendor Name',
            'Total Orders',
            'Total Amount',
        ];
    }
    public function map($order_vendors): array
    {
            return [
                $order_vendors->vendor_id,
                $order_vendors->vendor ? $order_vendors->vendor->name : '',
                $order_vendors->order_count,
                $order_vendors->total_payable_amount,
            ];

    }
}
