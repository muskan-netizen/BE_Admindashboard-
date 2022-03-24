<?php

namespace App\Http\Traits;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use XeroAPI\XeroPHP\AccountingObjectSerializer;
use App\Http\Traits\XeroStorageClass as StorageClass;
use XeroAPI, GuzzleHttp, Session;


trait XeroManager{

    public function authorization()
    {
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => '606F415E61A546ECA45DF07BD1ADD617',   
            'clientSecret'            => 'XiRg2XlrA-8F8srm_8GT6vM--BYw2JuDnRkgIDE2dWn3ouOg',
            'redirectUri'             => 'https://e541-103-72-170-243.ngrok.io/auth/callback/xero',
            'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
            'urlAccessToken'          => 'https://identity.xero.com/connect/token',
            'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
        ]);

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
        // $_SESSION['oauth2state'] = $provider->getState();
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

        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => '606F415E61A546ECA45DF07BD1ADD617',   
            'clientSecret'            => 'XiRg2XlrA-8F8srm_8GT6vM--BYw2JuDnRkgIDE2dWn3ouOg',
            'redirectUri'             => 'https://e541-103-72-170-243.ngrok.io/auth/callback/xero',
            'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
            'urlAccessToken'          => 'https://identity.xero.com/connect/token',
            'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
        ]);
   
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
              $this->authorizedResource();           
              // header('Location: ' . './authorizedResource.php');
              exit();
             
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                dd($e);
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
  
        $accountingApi = new XeroAPI\XeroPHP\Api\AccountingApi(
            new GuzzleHttp\Client(),
            $config
        );

        $assetApi = new XeroAPI\XeroPHP\Api\AssetApi(
            new GuzzleHttp\Client(),
            $config
        );  

        $identityApi = new XeroAPI\XeroPHP\Api\IdentityApi(
            new GuzzleHttp\Client(),
            $config
        );  

        $projectApi = new XeroAPI\XeroPHP\Api\ProjectApi(
            new GuzzleHttp\Client(),
            $config
        );  

        $message = "no API calls";
        if (isset($_GET['action'])){
            if ($_GET["action"] == 1) {
              // Get Organisation details
              $apiResponse = $accountingApi->getOrganisations($xeroTenantId);
              $message = 'Organisation Name: ' . $apiResponse->getOrganisations()[0]->getName();
            } else if ($_GET["action"] == 2) {
            // Create Contact
                try {
                    $person = new XeroAPI\XeroPHP\Models\Accounting\ContactPerson;
                    $person->setFirstName("John")
                            ->setLastName("Smith")
                            ->setEmailAddress("john.smith@24locks.com")
                            ->setIncludeInEmails(true);

                    $arr_persons = [];
                    array_push($arr_persons, $person);

                    $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
                    $contact->setName('FooBar')
                            ->setFirstName("Foo")
                            ->setLastName("Bar")
                            ->setEmailAddress("ben.bowden@24locks.com")
                            ->setContactPersons($arr_persons);
                    
                    $arr_contacts = [];
                    array_push($arr_contacts, $contact);
                    $contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
                    $contacts->setContacts($arr_contacts);

                    $apiResponse = $accountingApi->createContacts($xeroTenantId,$contacts);
                    $message = 'New Contact Name: ' . $apiResponse->getContacts()[0]->getName();
                } catch (\XeroAPI\XeroPHP\ApiException $e) {
                    $error = AccountingObjectSerializer::deserialize(
                      $e->getResponseBody(),
                      '\XeroAPI\XeroPHP\Models\Accounting\Error',
                      []
                    );
                    $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
                }
            } else if ($_GET["action"] == 3) {
                $if_modified_since = new \DateTime("2019-01-02T19:20:30+01:00"); // \DateTime | Only records created or modified since this timestamp will be returned
                $if_modified_since = null;
                $where = 'Type=="ACCREC"'; // string
                $where = null;
                $order = null; // string
                $ids = null; // string[] | Filter by a comma-separated list of Invoice Ids.
                $invoice_numbers = null; // string[] |  Filter by a comma-separated list of Invoice Numbers.
                $contact_ids = null; // string[] | Filter by a comma-separated list of ContactIDs.
                $statuses = array("DRAFT", "SUBMITTED");;
                $page = 1; // int | e.g. page=1 – Up to 100 invoices will be returned in a single API call with line items
                $include_archived = null; // bool | e.g. includeArchived=true - Contacts with a status of ARCHIVED will be included
                $created_by_my_app = null; // bool | When set to true you'll only retrieve Invoices created by your app
                $unitdp = null; // int | e.g. unitdp=4 – You can opt in to use four decimal places for unit amounts
                try {
                    $apiResponse = $accountingApi->getInvoices($xeroTenantId, $if_modified_since, $where, $order, $ids, $invoice_numbers, $contact_ids, $statuses, $page, $include_archived, $created_by_my_app, $unitdp);
                    if ( count($apiResponse->getInvoices()) > 0 ) {
                        $message = 'Total invoices found: ' . count($apiResponse->getInvoices());
                    } else {
                        $message = "No invoices found matching filter criteria";
                    }
                } catch (Exception $e) {
                    echo 'Exception when calling AccountingApi->getInvoices: ', $e->getMessage(), PHP_EOL;
                }
            } else if ($_GET["action"] == 4) {
                // Create Multiple Contacts
                try {
                    $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
                    $contact->setName('George Jetson')
                            ->setFirstName("George")
                            ->setLastName("Jetson")
                            ->setEmailAddress("george.jetson@aol.com");

                    // Add the same contact twice - the first one will succeed, but the
                    // second contact will throw a validation error which we'll catch.
                    $arr_contacts = [];
                    array_push($arr_contacts, $contact);
                    array_push($arr_contacts, $contact);
                    $contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
                    $contacts->setContacts($arr_contacts);

                    $apiResponse = $accountingApi->createContacts($xeroTenantId,$contacts,false);
                    $message = 'First contacts created: ' . $apiResponse->getContacts()[0]->getName();

                    if ($apiResponse->getContacts()[1]->getHasValidationErrors()) {
                      $message = $message . '<br> Second contact validation error : ' . $apiResponse->getContacts()[1]->getValidationErrors()[0]["message"];
                    }
                } catch (\XeroAPI\XeroPHP\ApiException $e) {
                    $error = AccountingObjectSerializer::deserialize(
                      $e->getResponseBody(),
                      '\XeroAPI\XeroPHP\Models\Accounting\Error',
                      []
                    );
                    $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
                }
            } else if ($_GET["action"] == 5) {
                // DELETE the org FIRST Connection returned
                $connections = $identityApi->getConnections();
                $id = $connections[0]->getId();
                $result = $identityApi->deleteConnection($id);
            }
        }
    }
}