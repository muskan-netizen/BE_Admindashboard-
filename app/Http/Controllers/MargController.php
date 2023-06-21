<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;
// use App\Libraries\MyDLLWrapper;
use App\Libraries\DecryptLogic;
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Http\Request;
use App\Http\Traits\MargTrait;
use Illuminate\Support\Facades\Artisan;

class MargController extends Controller
{
    use MargTrait;
    protected $DecryptLogic;

    public function __construct(DecryptLogic $DecryptLogic)
    {
        $this->DecryptLogic = $DecryptLogic;
    }

    public function margcmd()
    {
        Artisan::call('fetch:margdata');
    }

    public function syncmarg()
    {
        $hub_key = @getAdditionalPreference(['marg_access_token','is_marg_enable','marg_decrypt_key', 'marg_company_code']);

        if($hub_key['is_marg_enable'] == 1){
            $decryptionKey  = $hub_key['marg_decrypt_key'];
            $MargID  = $hub_key['marg_access_token'];
            $CompanyCode  = $hub_key['marg_company_code'];
            $detail         = [];
            $MargMST2017 = "https://corporate.margerp.com/api/eOnlineData/MargMST2017";
            $reqData = ["CompanyCode" => $CompanyCode,"MargID" => $MargID,"Datetime" => "", "index" => 0];
        }else{
            return false;
        }
         
        // Get the encrypted data from the request
        $encryptedData = $this->getData($MargMST2017, $reqData);

        // Decrypt the data using the DLL wrapper
        $decryptedData = $this->DecryptLogic->Decrypt($encryptedData, $decryptionKey);
        $collectionData = collect( json_decode($decryptedData));
            //    dd($collectionData["Details"]->pro_N);
        if(!empty($collectionData["Details"]->pro_N)){

        // ---------------------- With Dispatch ---------------------
            // $chunck = array_chunk($collectionData["Details"]->pro_N,100);
            // foreach($chunck as $data){
            //     dispatch(new \App\Jobs\SyncFromMarg($data))->onQueue('sync_data_from_marg');
            // }
        // ---------------------- Without Dispatch ------------------
            foreach($collectionData["Details"]->pro_N as $key => $product){
               $detail = $this->addProduct($product);
            }
        }

        // Return the decrypted data in the API response
        return response()->json(['massage' => "Work in porgress."]);
    }


    // Insert order api url-  https://corporate.margerp.com/api/eOnlineData/InsertOrderDetail
    // Parameters-   { "OrderID":"", "OrderNo": "78789", "CustomerID": "5929958", "MargID": "339157", "Type": "S", "Sid": "194130", "ProductCode": "1000004", "Quantity": "2", "Free": "0,0", "Lat": "", "Lng": "", "Address": "", "GpsID": "0", "UserType": "1", "Points": "0.00", "Discounts": "0", "Transport": "", "Delivery": "", "Bankname": "", "BankAdd1": "", "BankAdd2": "", "shipname": "", "shipAdd1": "", "shipAdd2": "", "shipAdd3": "", "paymentmode": "1", "paymentmodeAmount": "0", "payment_remarks": "", "order_remarks": "","CustName":"ramU" ,"CustMobile": "9289757820", "CompanyCode": "RakeshApi2", "OrderFrom": "RakeshApi2" }

	public function makeInsertOrderMargApi($orderDetails = [])
	{

        $hub_key = @getAdditionalPreference(['marg_access_token','is_marg_enable','marg_decrypt_key', 'marg_company_code']);

        if($hub_key['is_marg_enable'] == 1){
            $decryptionKey  = $hub_key['marg_decrypt_key'];
            $MargID  = $hub_key['marg_access_token'];
            $CompanyCode  = $hub_key['marg_company_code'];
            $detail         = [];
            $MargMST2017 = "https://corporate.margerp.com/api/eOnlineData/InsertOrderDetail";
            $detail = ["OrderID"=>"", "OrderNo"=> "10", "CustomerID"=> "2", "MargID"=> $MargID, "Type"=> "S", "Sid"=> "194130", "ProductCode"=> "R-8987,R-8986", "Quantity"=> "1,2", "Free"=> "0,0", "Lat"=> "", "Lng"=> "", "Address"=> "", "GpsID"=> "0", "UserType"=> "1", "Points"=> "0.00", "Discounts"=> "0", "Transport"=> "", "Delivery"=> "", "Bankname"=> "", "BankAdd1"=> "", "BankAdd2"=> "", "shipname"=> "", "shipAdd1"=> "", "shipAdd2"=> "", "shipAdd3"=> "", "paymentmode"=> "1", "paymentmodeAmount"=> "0", "payment_remarks"=> "", "order_remarks"=> "","CustName"=>"ramU" ,"CustMobile"=> "9289757820", "CompanyCode"=> $CompanyCode, "OrderFrom"=> $CompanyCode];

             // Get the encrypted data from the request
            $encryptedData = $this->getData($MargMST2017, $detail);

            \Log::info('response encryptedData' );
            \Log::info(json_encode($encryptedData));

            $decryptedData = $this->DecryptLogic->Decrypt($encryptedData, $decryptionKey);
            $collectionData = collect( json_decode($decryptedData));

            \Log::info('response');
            \Log::info(json_encode($collectionData));
            dd(json_decode($decryptedData));
            // return $encryptedData;

        }else{
            return false;
        }
		
	}



}
