<?php
namespace App\Http\Traits;

use App\Models\PaymentOption;
use Auth, Log, Config;
use GuzzleHttp\Client;
use App\Models\ClientCurrency;

trait MtnMomoPaymentManager
{

    private static $_apiUrl;

    private static $_referenceId;

    private static $_apiKey;

    private static $_subscriptionKey;

    private static $_paymentOption;

    private static $_client;

    private static $_environment;

    private static $_isSandbox;

    private static $_header;

    private static $_domain_name;

    private static $_accessToken;

    private static $_isConfigurationSet = false;

    private static $_currency = 'EUR';

    public function __init($creatingApiKey = true)
    {
        if (self::$_paymentOption == null) {
            self::$_paymentOption = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'mtn_momo')
                ->where('status', 1)
                ->first();
        }
        if (self::$_paymentOption == null || empty(self::$_paymentOption))
            return false;

        if (self::$_client == null) {
            self::$_client = new Client();
        }

        if ((! empty(self::$_paymentOption) && self::$_paymentOption->test_mode == '1') || self::$_isSandbox == 'true') {
            self::$_apiUrl = 'https://sandbox.momodeveloper.mtn.com/v1_0/';
            self::$_environment = 'sandbox';
            self::$_isSandbox = true;
        } else {
            self::$_apiUrl = 'https://proxy.momoapi.mtn.com/collection/';
            self::$_environment = 'mtnuganda';
            self::$_isSandbox = false;
        }

        $credentials = json_decode(self::$_paymentOption->credentials);
        if (! empty($credentials) && ! $creatingApiKey) {
            self::$_subscriptionKey = (isset($credentials->subscription_key)) ? $credentials->subscription_key : '';
            self::$_referenceId = (isset($credentials->reference_id)) ? $credentials->reference_id : '';
            self::$_apiKey = (isset($credentials->api_key)) ? $credentials->api_key : '';
            self::$_header = [
                'Authorization' => 'Basic ' . base64_encode(self::$_referenceId . ':' . self::$_apiKey),
                'Ocp-Apim-Subscription-Key' => self::$_subscriptionKey
            ];
            if (! self::$_isSandbox) {
                $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
                self::$_currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'EUR';
            }
            self::$_isConfigurationSet = true;
        }

        /* Set the callback URL */
        $site_url = url('/');
        self::$_domain_name = self::getDomainName($site_url);
    }

    public static function createApiUser()
    {
        /* Check if payment option avaiable for MOMO API */
        if (empty(self::$_paymentOption)) {
            self::response(404, 'Creadentials for Momo API not found.');
        }

        self::$_header = [
            'X-Reference-Id' => self::$_referenceId,
            'Ocp-Apim-Subscription-Key' => self::$_subscriptionKey,
            'Content-Type' => 'application/json'
        ];

        $params = [
            'providerCallbackHost' => 'https://webhook.site/8e8b0eb4-c068-40b2-a921-dc582f4816e0' // self::$_domain_name
        ];

        /* Check if API is on test mode */
        try {
            $response = self::$_client->request('POST', self::$_apiUrl . 'apiuser', [
                'headers' => self::$_header,
                'body' => json_encode($params)
            ]);

            if (empty($response)) {
                self::response(404, 'Resouce Not Found');
            }
            $code = $response->getStatusCode();
            switch ($code) {
                case 201:
                    return self::response($code, 'API User Added Successfully.');
                    break;
                case 400:
                    return self::response($code, 'There is a Problem with submitted data.');
                    break;
                case 500:
                    return self::response($code, 'Internal Server Error');
                    break;
                case 409:
                    return self::response($code, 'Either User is exists with this reference ID or invalid subscription key');
                    break;
                case 401:
                    return self::response($code, 'Unauthorized');
                    break;
                default:
                    return self::response($code, json_encode($response->getBody()->getContents()));
                    break;
            }
        } catch (\Exception $e) {
            return self::response($e->getCode(), $e->getMessage());
        }
    }

    public static function createApiKey()
    {
        try {
            // Create an apiKey
            $response = self::$_client->request('post', self::$_apiUrl . '/apiuser/' . self::$_referenceId . '/apikey', [
                'headers' => self::$_header
            ]);

            if (empty($response)) {
                self::response(404, 'Resouce Not Found');
            }
            $code = $response->getStatusCode();
            $response = json_decode($response->getBody()->getContents(), true);
            \Log::info($response);
            $apiKey = '';
            if (! empty($response['apiKey'])) {
                $apiKey = $response['apiKey'];
            }

            switch ($code) {
                case 201:
                    return self::response($code, 'Api Key generated Successfully', $response['apiKey']);
                    break;
                case 400:
                    return self::response($code, 'There is a Problem with submitted data.');
                    break;
                case 500:
                    return self::response($code, 'Internal Server Error');
                    break;
                case 409:
                    return self::response($code, 'Something wrong with the keys');
                    break;
                case 401:
                    return self::response($code, 'Unauthorized');
                    break;
                default:
                    return self::response($code, json_encode($response->getBody()->getContents()));
                    break;
            }
        } catch (\Exception $e) {
            return self::response($e->getCode(), $e->getMessage());
        }
    }

    public static function GenerateAccressToken()
    {
        try {
            $response = self::$_client->request('POST', 'https://sandbox.momodeveloper.mtn.com/collection/token/', [
                'headers' => self::$_header
            ]);

            if (empty($response)) {
                self::response(404, 'Resouce Not Found');
            }

            $code = $response->getStatusCode();
            $response = json_decode($response->getBody()->getContents(), true);

            self::$_accessToken = $response['access_token'];

            switch ($code) {
                case 200:
                    return self::response($code, 'Access Token has been generated successfully.');
                    break;
                case 400:
                    return self::response($code, 'There is a Problem with submitted data.');
                    break;
                case 500:
                    return self::response($code, 'Internal Server Error');
                    break;
                case 409:
                    return self::response($code, 'Something wrong with the keys');
                    break;
                case 401:
                    return self::response($code, 'Unauthorized');
                    break;
                default:
                    return self::response($code, json_encode($response->getBody()->getContents()));
                    break;
            }
        } catch (\Exception $e) {
            return self::response($e->getCode(), $e->getMessage());
        }
    }

    public static function paymentRequest($token, $amount, $currency, $order_number, $partyId)
    {
        try {

            /* Generate new reference ID for each new request to pay api call */
            $response = self::$_client->get('https://www.uuidgenerator.net/api/version4');

            if (empty($response)) {
                self::response(404, 'Resouce Not Found');
            }

            self::$_referenceId = $response->getBody()->getContents();

            $headers = [
                'X-Reference-Id' => self::$_referenceId,
                'X-Target-Environment' => self::$_environment,
                'Ocp-Apim-Subscription-Key' => self::$_subscriptionKey,
                'Authorization' => 'Bearer ' . self::$_accessToken,
                'Content-Type' => 'application/json'
            ];
            $params = [
                'amount' => $amount,
                'currency' => $currency,
                'externalId' => $order_number,
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $partyId
                ],
                'payerMessage' => "Paying for Driver tester code",
                'payeeNote' => "Drivers name"
            ];

            $response = self::$_client->request('POST', 'https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay', [
                'headers' => $headers,
                'body' => json_encode($params)
            ]);

            $code = $response->getStatusCode();

            switch ($code) {
                case 202:
                    return self::response($code, 'Request to Pay has successfully generated');
                    break;
                case 400:
                    return self::response($code, 'There is a Problem with submitted data.');
                    break;
                case 500:
                    return self::response($code, 'Internal Server Error');
                    break;
                case 409:
                    return self::response($code, 'Something wrong with the keys');
                    break;
                case 401:
                    return self::response($code, 'Unauthorized');
                    break;
                default:
                    return self::response($code, $response->getBody()->getContents());
                    break;
            }
        } catch (\Exception $e) {
            return self::response($e->getCode(), $e->getMessage());
        }
    }

    public static function getTransactionStatus($referenceId)
    {
        $headers = [
            'X-Target-Environment' => self::$_environment,
            'Ocp-Apim-Subscription-Key' => self::$_subscriptionKey,
            'Authorization' => 'Bearer ' . self::$_accessToken,
            'Content-Type' => 'application/json'
        ];

        $response = self::$_client->get('https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay/' . $referenceId, [
            'headers' => $headers
        ]);

        if (empty($response)) {
            return [
                null,
                false
            ];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    private static function getDomainName($url)
    {
        $disallowed = array(
            'http://',
            'https://'
        );
        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }

    private static function response($code, $message, $apiKey = null, $transactionId = null)
    {
        return json_encode([
            'status' => $code,
            'message' => $message,
            'apiKey' => $apiKey
        ]);
    }
    // public function createPaymentpage($data,$user,$address = null)
    // {
    // if(is_null($address))
    // {
    // $address = (object)[];
    // }
    // $order_number = isset($data['order_number']) ? $data['order_number'] : "";
    // $pay = paypage::sendPaymentCode('all')
    // ->sendTransaction('Auth')
    // ->sendCart(mt_rand(10000000,99999999),(int)$data['amount'],'test1')
    // // ->sendCustomerDetails('Walaa Elsaeed', 'w.elsaeed@paytabs.com', '0101111111', 'test', 'Nasr City', 'Cairo', 'EG', '1234','100.279.20.10')
    // ->sendCustomerDetails($user->name??'', $user->email??'', '0101111111', $address->address??'', $address->city??'', $address->state??'', $address->country_code??'', $address->pincode??'','100.279.20.11')
    // ->sendShippingDetails('same as billing')
    // ->sendURLs(route('payment.paytab.return',['amount' => (int)$data['amount'], 'payment_from' => $data['payment_from'], 'come_from' => $data['come_from'], 'order_number' => $order_number,'auth_token'=>$user->auth_token]), route('payment.paytab.callback'))
    // // ->sendURLs('https://619a-112-196-88-218.ngrok.io/payment/paytab/return?amount='.(int)$data['amount'].'&payment_from='.$data['payment_from'].'&come_from='.$data['come_from'].'&order_number='.$order_number.'&auth_token='.$user->auth_token, 'https://619a-112-196-88-218.ngrok.io/payment/paytab/callback')
    // ->sendLanguage('en')
    // ->create_pay_page();
    // return $pay;
    // }
    // public function capturePayment($data)
    // {
    // return Paypage::capture($data['tranRef'],$data['cartId'],(int)$data['amount'],$data['description']);
    // }
}
