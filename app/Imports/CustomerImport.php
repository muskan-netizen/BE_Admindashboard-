<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use Spatie\Geocoder\Facades\Geocoder;
use App\Models\{CsvCustomerImport, User};
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomerImport implements ToCollection
{
    public function  __construct($csv_vendor_import_id){
        $this->csv_vendor_import_id= $csv_vendor_import_id;
    }
    public function collection(Collection $rows){
        try {
            $data = array();
            $error = array();
            $i = 0;
            try {
                foreach ($rows as $row) {
                    $row = $row->toArray();
                    $checker = 0;
                    if ($row[0] != ""){
                            $error[] = "Row " . $i . " : Name cannot be empty";
                            $checker = 1;
                        }
                        if($row[1] != ""){
                            if(!is_numeric($row[8])) {
                                $error[] = "Row " . $i . " : Phone no is required";
                                $checker = 1;
                            }
                        }
                        if($row[2] != ""){
                            if(!is_numeric($row[9])) {
                                $error[] = "Row " . $i . " : E-Mail no is required";
                                $checker = 1;
                            }
                        }
                        
                        if($checker == 0) {
                            $data[] = $row;
                        }
                    
                    $i++;
                }
                if (!empty($data)) {
                    $insert_vendor_details = [];
                    foreach ($data as $da) {
                        if (!User::where('name', $da[0])->exists()) {
                           
                            $insert_vendor_details[] = array(
                                'name' => $da[0],
                                'phone_no' => ($da[1] == "") ? NULL : $da[1],
                                'email' => ($da[2] == "") ? NULL : $da[2],
                                //'country' => ($da[2] == "") ? NULL : $da[2],
                                'is_email_verified' => ($da[4] == "N") ? '0' : '1',
                                'is_phone_verified' => ($da[5] == "N") ? '0' : '1',
                            );
                        }
                    }
                    User::insert($insert_vendor_details);
                }
            } catch(\Exception $ex){
                $error[] = "Other: " .$ex->getMessage();
                \Log::info($ex->getMessage()."".$ex->getLine());
            }
            $csv_vendor_import = CsvCustomerImport::where('id', $this->csv_vendor_import_id)->first();
            if (!empty($error)) {
                $csv_vendor_import->status = 3;
                $csv_vendor_import->error = json_encode($error, true);
            }else{
                $csv_vendor_import->status = 2;
            }
            $csv_vendor_import->save();
        } catch (Exception $e) {
            pr($e->getCode());
        }

    }
}
