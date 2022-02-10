<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\VendorRegistrationDocument;

class VendorSimpelExport implements FromCollection, WithHeadings, WithMapping{

    public function collection(){
        $vendor_registration_documents = VendorRegistrationDocument::with(['primary','options','options.translation' => function($query)  {
            $query->where('language_id', session()->get('customerLanguage'));
        }])->get();
        $dataArra = [
                "file",
                "file",
                "KFC",
                "",
                "kfc@mail.com",
                "7508983302",
                "Samadhan, 18 Sahar Road, Samadhan Agarkar Chowk, Andheri (e)",
                "TRUE/FALSE",
                "TRUE/FALSE",
                "TRUE/FALSE",
                "12",
                "1",
                "100",
                "TRUE/FALSE",
                "10",
                "15",
                "0",
            ];
        foreach ($vendor_registration_documents as $vendor_registration_document) {
            if($vendor_registration_document->file_type == "selector"){
                $selectorValue = '';
                foreach ($vendor_registration_document->options as $key =>$value ){
                    $selectorValue .= ($selectorValue ? '/' : '') . ($value->translation? $value->translation->name: "");
                }
               // echo  $selectorValue;
                array_push($dataArra , $selectorValue);
            }else{
                array_push($dataArra , $vendor_registration_document->file_type);
            }

        }
        $collection = collect();
        return $collection->push($dataArra);
        //pr(collect($dataArra));
    }

    public function headings(): array{
        $vendor_registration_documents = VendorRegistrationDocument::with('primary')->get();
        $heding = [
            'Upload logo',
            'Upload Banner Image',
            'Name',
            'Description',
            'Email',
            'Phone Number',
            'Address',
            'Dine_In',
            'Takeaway',
            'Delivery',
            'Order_Prepare_Time (In minutes)',
            'Auto_Reject_Time (In minutes, 0 for no rejection)',
            'Order Min Amount',
            '24*7 Availability',
            'Commission Percent',
            'Commission Fixed Per Order',
            'Commission Monthly',
        ];
        foreach ($vendor_registration_documents as $vendor_registration_document) {
            array_push($heding , $vendor_registration_document->primary->slug);
        }
        return $heding;
    }

    public function map($dataArra): array
    {

        //pr($dataArra);
        return $dataArra;
    }

}
