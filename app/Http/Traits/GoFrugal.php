<?php
namespace App\Http\Traits;

use App\Models\Client;
use App\Models\ClientPreferenceAdditional;
use Facade\Ignition\Exceptions\InvalidConfig;
use GuzzleHttp\Client as GClient;

class GoFrugal
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
            $this->_clientPreference = json_decode($clientPreferenceAdditional, true);
        }
    }

    private function createRequest(string $url, string $method, array $headers = null, array $data = null){
        $request = $this->_client->request($method, $url, ['headers' => $headers]);
        $response = $request->send();
        if($response->isSuccessful()){
           return $response->getBody()->getContents();
        }
        return $response;
    }

    public function syncAllProducts(){
        $url = $this->_clientPreference['domain_url'];
        $response = $this->createRequest($url, 'GET',[], []);
        
    }
}