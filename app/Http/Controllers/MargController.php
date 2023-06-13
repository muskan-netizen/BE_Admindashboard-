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



}
