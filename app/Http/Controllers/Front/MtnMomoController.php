<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use Auth;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;

use Illuminate\Http\Request;
use App\Models\PaymentOption;
use App\Models\CaregoryKycDoc;
use Illuminate\Support\Carbon;
use App\Http\Traits\ApiResponser;
use App\Models\CartProductPrescription;
use App\Models\ClientCurrency;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\UrlGenerator;
use Log;
use App\Http\Traits\MtnMomoPaymentManager;

class MtnMomoController extends FrontController
{
    use ApiResponser;
    use MtnMomoPaymentManager;

    private $subscription_key;

    private $appUrl;

    private $reference_id;

    private $token;

    private $environment;

    private $api_key;

    private $currency;

    public function __construct()
    {
        $payOpt = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'mtn_momo')
            ->where('status', 1)
            ->first();
        $json = json_decode($payOpt->credentials);
        $this->subscription_key = $json->subscription_key;
        $this->reference_id = $json->reference_id;
        $this->api_key = $json->api_key;
        $this->token = base64_encode($this->reference_id . ':' . $this->api_key);
        if ($payOpt->test_mode == '1') {
            $this->appUrl = 'https://sandbox.momodeveloper.mtn.com/';
            $this->environment = 'sandbox';
        } else {
            $this->appUrl = 'https://payments.stabexinternational.com/api/mtn/Callback';
            $this->environment = 'live';
        }

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'EUR';
    }

    public function createToken(Request $request, UrlGenerator $url)
    {
        self::__init(false);

        if (!self::$_isConfigurationSet) {
            return self::response(500, 'Sorry for inconvinence. Please try again later');
        }

        $data = [];
        $data['environment'] = 'web';
        if ($request->from == 'cart') {
            $data['amt'] = $request->amt;
            $data['order_number'] = $request->order_number;
            $data['from'] = $request->from;
        } else if ($request->from == 'wallet') {
            $data['amt'] = $request->amt;
            $data['from'] = $request->from;
        } else if ($request->from == 'subscription') {
            $data['amt'] = $request->amt;
            $data['from'] = $request->from;
            $data['subsid'] = $request->subsid;
        } else if ($request->from == 'tip') {
            $data['amt'] = $request->amt;
            $data['from'] = $request->from;
            $data['order_number'] = $request->order_number;
        }

        // generate AccessToken
        self::GenerateAccressToken();

        if (empty(self::$_accessToken)) {
            return self::response(500, 'Sorry for inconvinence. Please try again later');
        }
        // request to pay 
        $response = self::RequestToPay(self::$_accessToken, $data);

        if ($response['status'] == 202) {
            //check transaction status 
            if (!self::$_isSandbox) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Payment request has been sent successfully'
                ], 200);
            }

            //For Sandbox only
            $response = self::getTransactionStatus(self::$_referenceId);
            if (!empty($response) && !empty($response['status']) && ($response['status'] == 'SUCCESSFUL' || $response['status'] == 'PENDING')) {
                $url =  self::sucessPayment($data, $response['financialTransactionId']);
                if ($url) {
                    return response()->json([
                        'status' => 'Success',
                        'message' => 'Payment Successful',
                        'url' => $url
                    ], 200);
                }
            } else {
                $message = 'Payment Failed';
                if (!empty($response['reason'])) {
                    if (is_array($response['reason'])) {
                        $message = $response['reason']['message'];
                    } else {
                        $message = $response['reason'];
                        $currency = self::$_currency;
                        switch ($message) {
                            case 'APPROVAL_REJECTED';
                                $message = "Payment request of $currency $request->amt has been rejected";
                                break;
                            case 'INTERNAL_PROCESSING_ERROR':
                                $message = "Your payment request of $currency $request->amt has been Failed.";
                                break;
                            case 'EXPIRED':
                                $message = "Your payment request of $currency $request->amt has been Expired.";
                                break;
                            default:
                                $message = 'This is default message';
                                break;
                        }
                    }
                }
                return response()->json([
                    'status' => 'PAYMENT FAILED',
                    'message' => $message,
                    'response' => $response
                ], 500);
            }
        }
        return response()->json([
            'status' => 'PAYMENT FAILED',
            'message' => 'Payment Failed',
            'response' => !empty($response['response']) ? json_decode($response['response']->getBody()->getContents(), true) : ''
        ], 500);
    }
}
