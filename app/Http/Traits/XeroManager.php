<?php

namespace App\Http\Traits;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use XeroAPI\XeroPHP\AccountingObjectSerializer;
use App\Http\Traits\XeroStorageClass as StorageClass;
use XeroAPI, GuzzleHttp, Session;


trait XeroManager{

    public function setProvider()
    {
        return new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => '606F415E61A546ECA45DF07BD1ADD617',   
            'clientSecret'            => 'XiRg2XlrA-8F8srm_8GT6vM--BYw2JuDnRkgIDE2dWn3ouOg',
            'redirectUri'             => 'https://e541-103-72-170-243.ngrok.io/auth/callback/xero',
            'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
            'urlAccessToken'          => 'https://identity.xero.com/connect/token',
            'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
        ]);
    }

    public function authorization()
    {
        $provider = $this->setProvider();

        // Scope defines the data your app has permission to access.
        // Learn more about scopes at https://developer.xero.com/documentation/oauth2/scopes
        $options = [
            'scope' => ['openid email profile offline_access accounting.settings accounting.transactions accounting.contacts accounting.journals.read accounting.reports.read accounting.attachments']
        ];

        // This returns the authorizeUrl with necessary parameters applied (e.g. state).
        $authorizationUrl = $provider->getAuthorizationUrl($options);
        // dd($authorizationUrl);

        // Save the state generated for you and store it to the session.
        // For security, on callback we compare the saved state with the one returned to ensure they match.
        Session::put('oauth2state', $provider->getState());
        // dd(Session::get('oauth2state'));

        // Redirect the user to the authorization URL.
        header('Location: ' . $authorizationUrl);
        exit();
    }

    public function callback($data)
    {
        // Storage Classe uses sessions for storing token > extend to your DB of choice
        $storage = new StorageClass();  

        $provider = $this->setProvider();
   
        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {
            echo "Something went wrong, no authorization code found";
            exit("Something went wrong, no authorization code found");

        // Check given state against previously stored one to mitigate CSRF attack
        // } elseif (empty($_GET['state']) || ($_GET['state'] !== Session::get('oauth2state'))) {
        //     echo "Invalid State";
        //     unset($_SESSION['oauth2state']);
        //     exit('Invalid state');
        }else{
            try {
              // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);
                   
                $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$accessToken->getToken() );
                $identityApi = new XeroAPI\XeroPHP\Api\IdentityApi(
                    new GuzzleHttp\Client(),
                    $config
                );
               
                $result = $identityApi->getConnections();

                // Save my tokens, expiration tenant_id
                $storage->setToken(
                    $accessToken->getToken(),
                    $accessToken->getExpires(),
                    $result[0]->getTenantId(),  
                    $accessToken->getRefreshToken(),
                    $accessToken->getValues()["id_token"]
                );  

                exit();
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                echo "Callback failed";
                exit();
            }
        }
    }
    public function authorizedResource()
    {
        // Storage Classe uses sessions for storing token > extend to your DB of choice
        $storage = new StorageClass();
        $xeroTenantId = (string)$storage->getSession()['tenant_id'];

        if ($storage->getHasExpired()) {
            $provider = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId'                => '606F415E61A546ECA45DF07BD1ADD617',   
                'clientSecret'            => 'XiRg2XlrA-8F8srm_8GT6vM--BYw2JuDnRkgIDE2dWn3ouOg',
                'redirectUri'             => 'https://e541-103-72-170-243.ngrok.io/auth/callback/xero',
                'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
                'urlAccessToken'          => 'https://identity.xero.com/connect/token',
                'urlResourceOwnerDetails' => 'https://identity.xero.com/resources'
            ]);

            $newAccessToken = $provider->getAccessToken('refresh_token', [
              'refresh_token' => $storage->getRefreshToken()
            ]);

            // Save my token, expiration and refresh token
            $storage->setToken(
                $newAccessToken->getToken(),
                $newAccessToken->getExpires(),
                $xeroTenantId,
                $newAccessToken->getRefreshToken(),
                $newAccessToken->getValues()["id_token"]
            );
        }

        $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$storage->getSession()['token'] );
        $apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
            new GuzzleHttp\Client(),
            $config
        );
        return $apiInstance;   
    }
    public function createAccount()
    {
        $apiInstance = $this->authorizedResource();
        $storage = new StorageClass();
        $xeroTenantId = (string)$storage->getSession()['tenant_id'];

        $account = new XeroAPI\XeroPHP\Models\Accounting\Account;
        $account->setCode('123456');
        $account->setName('FooBar');
        $account->setType(XeroAPI\XeroPHP\Models\Accounting\AccountType::EXPENSE);
        $account->setDescription('Hello World');

        try {
          $result = $apiInstance->createAccount($xeroTenantId, $account);
        } catch (Exception $e) {
          echo 'Exception when calling AccountingApi->createAccount: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function createContact()
    {
        $apiInstance = $this->authorizedResource();
        $storage = new StorageClass();
        $xeroTenantId = (string)$storage->getSession()['tenant_id'];
        $summarizeErrors = true;

        $phone = new XeroAPI\XeroPHP\Models\Accounting\Phone;
        $phone->setPhoneNumber('+918059272673');
        $phone->setPhoneType(XeroAPI\XeroPHP\Models\Accounting\Phone::PHONE_TYPE_MOBILE);
        $phones = [];
        array_push($phones, $phone);

        $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
        $contact->setName('Sujata Saneja');
        $contact->setEmailAddress('sujatacodebrew@gmail.com');
        $contact->setPhones($phones);

        $contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
        $arr_contacts = [];
        array_push($arr_contacts, $contact);
        $contacts->setContacts($arr_contacts);

        try {
          $result = $apiInstance->updateOrCreateContacts($xeroTenantId, $contacts, $summarizeErrors);
          return $result;
        } catch (Exception $e) {
            dd($e);
          echo 'Exception when calling AccountingApi->createContacts: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function createInvoice($data)
    {
        $apiInstance = $this->authorizedResource();
        $storage = new StorageClass();
        $xeroTenantId = (string)$storage->getSession()['tenant_id'];

        $summarizeErrors = true;
        $unitdp = 4;
        $dateValue = new DateTime('2020-10-10');
        $dueDateValue = new DateTime('2020-10-28');

        $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
        $contact->setContactID($data['contact_id']);


        $lineItem = new XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setDescription('Foobar');
        $lineItem->setQuantity(1.0);
        $lineItem->setUnitAmount(20.0);
        $lineItem->setAccountCode('000');
        $lineItems = [];
        array_push($lineItems, $lineItem);

        $invoice = new XeroAPI\XeroPHP\Models\Accounting\Invoice;
        $invoice->setType(XeroAPI\XeroPHP\Models\Accounting\Invoice::TYPE_ACCREC);
        $invoice->setContact($contact);
        $invoice->setDate($dateValue);
        $invoice->setDate($dueDateValue);
        $invoice->setLineItems($lineItems);
        $invoice->setReference('Website Design');
        $invoice->setStatus(XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_DRAFT);

        $invoices = new XeroAPI\XeroPHP\Models\Accounting\Invoices;
        $arr_invoices = [];
        array_push($arr_invoices, $invoice);
        $invoices->setInvoices($arr_invoices);

        try {
          $result = $apiInstance->updateOrCreateInvoices($xeroTenantId, $invoices, $summarizeErrors, $unitdp);
        } catch (Exception $e) {
          echo 'Exception when calling AccountingApi->updateOrCreateInvoices: ', $e->getMessage(), PHP_EOL;
        } 
    } 
}