<?php
namespace App\Http\Traits;

use App\Models\Client;
use App\Models\ClientPreferenceAdditional;
use Facade\Ignition\Exceptions\InvalidConfig;
use GuzzleHttp\Client as GClient;
use PhpParser\Node\Stmt\Switch_;

trait GoFrugal
{
    private $_client = null;

    private $_transport = null;

    private $_clientPreference =  null;

    public function __construct()
    {
        if($this->_transport == null){
            $this->_transport = new GClient();
        }
        if($this->_client == null){
            $this->_client = Client::first();
        }
        if($this->_clientPreference == null){
            $clientPreferenceAdditional = ClientPreferenceAdditional::where('key_name','gofrugal_credentials')->where('client_code' ,$this->_client->code)->first();
            if(empty($clientPreferenceAdditional)){
                throw new \Exception('Configure API Key for GoFrugal POS Integration');
            }
            $this->_clientPreference = json_decode($clientPreferenceAdditional['key_value'], true);
        }
    }

    private function createRequest(string $url, string $method, array $headers = null, array $data = null){
        try{
            $headers['X-Auth-Token'] = $this->_clientPreference['api_key'];
            $request = $this->_transport->request($method, $url, ['headers' => $headers]);
            $response = $request->send();
            return [
                'status' =>  $response->isSuccessful(),
                'message' => 'success',
                'data' => $response->getBody()->getContents()
            ];
        }catch(\Exception $e){
            \Log::info($e->getMessage());
            switch($e->getCode()){
                case 404:
                    $message = "Given Url is not Found";
                    break;
                case 500:
                    $message = "Intenal Server Error";
                    break;
            }
            return [
                'status' => false,
                'message' => $message
            ];
        }
    }

    public function syncAllProducts(){
        $url = $this->_clientPreference['domain_url'];
        $response = $this->createRequest($url, 'GET',[], []);
        return $response;
    }

    public function getVendors(){
        $url = $this->_clientPreference['domain_url']. 'supplierMaster';
        $response = $this->createRequest($url, 'GET',[], []);
        return $response;
    }
}