<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Passbase\Configuration;
use Passbase\api\IdentityApi;
use GuzzleHttp\Client;
use Log;
trait PassbaseManager{ 

  public function init()
  {
    $config = Configuration::getDefaultConfiguration()->setApiKey('X-API-KEY', $this->secret_key);
    $apiInstance = new IdentityApi(new Client(),$config);
    return $apiInstance;
  }
  public function getIdentity($id)
  {
    try{
      $apiInstance = $this->init();
      $result = $apiInstance->getIdentityById($id);
      return $result;
    } catch (Exception $e) {
      dd($e->getMessage());
      echo 'Exception when calling IdentityApi->getIdentityById: ', $e->getMessage(), PHP_EOL;
    }
  }

  

}
