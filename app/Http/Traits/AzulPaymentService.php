<?php
namespace App\Http\Traits;
use App\Models\PaymentOption;
use Paytabscom\Laravel_paytabs\Facades\paypage; 
use Auth, Log, Config;
trait AzulPaymentService{

  
    public function __construct()
    {
         $this->MAIN_URL      = 'https://pagos.azul.com.do/webservices/JSON/Default.aspx';
         $this->ALTERNATE_URL = 'https://contpagos.azul.com.do/Webservices/JSON/default.aspx';
         $this->TEST_URL      = 'https://pruebas.azul.com.do/webservices/JSON/Default.aspx';
    
         $this->SAVE_TO_DATAVAULT         = 1;
         $this->DONT_SAVE_TO_DATAVAULT    = 2;
         $this->HOLD_TRANSACTION          = 'Hold';
         $this->REFUND_TRANSACTION        = 'Refund';
         $this->PAYMENT_CHANNEL           = 'EC';
         $this->OK_RESPONSE_CODE          = '00';
         $this->AZUL_OK_RESPONSE_CODE     = 'ISO8583';
         $this->MERCHANT_ID               = 39921720001;
         $this->POST_INPUT_MODE           = 'E-Commerce';
         $this->AUTH_1_HEADER             = 'SPEEDY';
         $this->AUTH_2_HEADER             = '#vnCnKF5#DyK';
    
         $this->errors = [
            'INSUF FONDOS' => 'Tu tarjeta no tiene fondos suficientes para completar la transacción'
        ];
    
      // $this->paytab_creds = PaymentOption::select('credentials')->where('code', 'paytab')->where('status', 1)->first();
      // $this->creds_arr = json_decode($this->paytab_creds->credentials);
      // $this->profile_id = $this->creds_arr->profile_id ?? '';
      // $this->client_key = $this->creds_arr->client_key ?? '';
      // $this->server_key = $this->creds_arr->server_key ?? '';
      //   Config::set('Paytabs.profile_id', $this->profile_id);
      //   Config::set('Paytabs.server_key', $this->server_key); 
  }
    /**
     * Make a payment(on Hold) with a given card.
     * 
     * @param App\Entities\CreditCardEntity $card
     *  
     * @return array
     */
    public function payWithCard($card=[]): array
    {
         //Log::info('on payWithCard'.'order_id '.'8778787');

        //$order = Order::find($card->getOrderNumber());

        // if(is_null($order)){
        //     return [
        //         'message'   => 'Order not found',
        //         'ok'        => true,
        //         'data'      => null
        //     ];
        // }

        $request = [
            'Channel'               => $this->PAYMENT_CHANNEL,
            'Store'                 => $this->MERCHANT_ID,
            'CardNumber'            => "4242424242424242",
            'Expiration'            => "12/34",
            'CVC'                   => "1234",
            'PosInputMode'          => $this->POST_INPUT_MODE,
            'TrxType'               => 'Sale',
            'Amount'                => "10.00",
            'Itbis'                 => '',
            'CurrencyPosCode'       => '',
            'Payments'              => '1',
            'Plan'                  => '0',
            'AcquirerRefData'       => '1',
            'CustomerServicePhone'  => '8092223344',
            'OrderNumber'           => "786786768678",
            'ECommerceUrl'          => 'https://speedy.do/',
            'CustomOrderId'         => "8557575785857",
            'SaveToDataVault'       => $this->SAVE_TO_DATAVAULT,
            'AltMerchantName'       => "768768767868",
            'DataVaultToken'        => [
                'SaveToDataVault'   => 0,
                'ForceNo3DS'        => 0,
            ],
            'DataVaultToken'        => '',
            'AuthHash'              => '',
            "ForceNo3DS"            => '1'
        ];

        $response = $this->sendRequest($request);
        // Checks if azul_payWithCard response is OK.
        dd($response);
        // if($response['code'] != 200){
        //     Log::info('error http payWithCard', 'order_id: '.json_encode($response['message']));
        //     return [
        //         'message'   => $response['message'],
        //         'ok'        => false
        //     ];
        // }

        // if($response['data']->ResponseCode !== self::AZUL_OK_RESPONSE_CODE){
        //      Log::info('error on payWithCard', 'order_id: '.json_encode($response['data']));
        //     return [
        //         'message'   => $response['data']->ErrorDescription,
        //         'ok'        => false
        //     ];
        // }

        // $order->update([
        //     'azul_order_id' => $response['data']->AzulOrderId
        // ]);

        //  Log::info('payWithCard OK', json_encode($response['data']));

        // return [
        //     'message'   => 'ok',
        //     'ok'        => true,
        //     'data'      => $response['data']
        // ];
    }

    
    /**
     * Make a payment(on Hold) with data vault.
     * 
     * @param string $amount
     * @param integer $order_id
     * @param App\Models\UserDataVault $datavault
     *  
     * @return array
     */
    public function payWithDatavault($amount, $order_id, UserDataVault $datavault)
    {
         //Log::info('AzulPaymentService.payWithDatavault', 'order_id: '.$order_id);

        $order = Order::find($order_id);

        if(is_null($order)){
            return [
                'message'   => 'Order not found',
                'ok'        => true,
                'data'      => null
            ];
        }

        $request = [
            'Channel'               => self::PAYMENT_CHANNEL,
            'Store'                 => self::MERCHANT_ID,
            'CardNumber'            => '',
            'Expiration'            => '',
            'PosInputMode'          => self::POST_INPUT_MODE,
            'TrxType'               => self::HOLD_TRANSACTION,
            'Amount'                => $this->parseAmount( $amount ),
            'Itbis'                 => '',
            'CurrencyPosCode'       => '',
            'Payments'              => '1',
            'Plan'                  => '0',
            'AcquirerRefData'       => '1',
            'CustomerServicePhone'  => '8092223344',
            'OrderNumber'           => $order_id,
            'ECommerceUrl'          => 'https://speedy.do/',
            'CustomOrderId'         => $order_id,
            'DataVaultToken'        => $datavault->token,
            "ForceNo3DS"            => '1'
        ];

        $response = $this->sendRequest($request);
        
        if($response['code'] != 200){
            // Log::info('error http AzulPaymentService.payWithDatavault', 'order_id: '.$order_id.' '.json_encode($response['message']));
            return [
                'message'   => $response['message'],
                'ok'        => false
            ];
        }

        if($response['data']->ResponseCode !== self::AZUL_OK_RESPONSE_CODE){
             //Log::info('error on azul AzulPaymentService.payWithDatavault', 'order_id: '.$order_id.' '.json_encode($response['data']));
            return [
                'message'   => $response['data']->ErrorDescription,
                'ok'        => false
            ];
        }

        if($response['data']->IsoCode == 51){
             //Log::info('error on azul AzulPaymentService.payWithDatavault', 'order_id: '.$order_id.' '.json_encode($response['data']));
            return [
                'message'   => $this->formattErrorMessage( $response['data']->ResponseMessage ),
                'ok'        => false
            ];
        }

         //Log::info('AzulPaymentService.payWithDatavault ok', 'request: '.json_encode($request).' response: '.json_encode($response['data']));

        return [
            'message'   => 'ok',
            'ok'        => true,
            'data'      => $response['data']
        ];

    }

    /**
     * Cancel a transaction
     * @param $azul_order_id
     */
    public function voidTransaction($azul_order_id)
    {
        // Log::info('on voidTransaction', 'params: '.$azul_order_id);

        $request = [
            'Channel'       => self::PAYMENT_CHANNEL,
            'Store'         => self::MERCHANT_ID,
            'AzulOrderId'   => $azul_order_id,
        ];

        $response = $this->sendRequest($request, '?processvoid');
        
        if($response['code'] != 200){
             Log::info('error http voidTransaction', json_encode($response));
            return [
                'message'   => $response['message'],
                'ok'        => false
            ];
        }

        if($response['data']->IsoCode !== self::OK_RESPONSE_CODE){
             Log::info('error on voidTransaction', json_encode($response['data']));
            return [
                'message'   => $response['data']->ResponseMessage.' '.$response['data']->ErrorDescription,
                'ok'        => false
            ];
        }

         Log::info('voidTransaction ok', 'request: '.json_encode($request).' response: '.json_encode($response['data']));

        return [
            'message'   => 'ok',
            'ok'        => true,
            'data'      => $response['data']
        ];
    }

    /**
     * Refund a transaction
     * @param $azul_order_id
     */
    public function refundTransaction($azul_order_id, $amount, $order_id, $order_date)
    {
         Log::info('on refundTransaction', "$azul_order_id, $amount, $order_id, $order_date");

        $request = [
            'Channel'               => self::PAYMENT_CHANNEL,
            'Store'                 => self::MERCHANT_ID,
            'CardNumber'            => '',
            'Expiration'            => '',
            'CVC'                   => '',
            'PosInputMode'          => self::POST_INPUT_MODE,
            'TrxType'               => self::REFUND_TRANSACTION,
            'Amount'                => $this->parseAmount( $amount ),
            'Itbis'                 => '',
            'CurrencyPosCode'       => '',
            'Payments'              => '1',
            'Plan'                  => '0',
            'OriginalDate'          => $order_date,
            'OriginalTrxTicketNr'   => '',
            'AuthorizationCode'     => '',
            'ResponseCode'          => '',
            'AcquirerRefData'       => '',
            'RRN'                   => null,
            'AzulOrderId'           => $azul_order_id,
            'CustomerServicePhone'  => '8092223344',
            'OrderNumber'           => $order_id,
            'ECommerceUrl'          => 'https://speedy.do/',
            'CustomOrderId'         => $order_id,
            'DataVaultToken'        => '',
            'SaveToDataVault'       => '0',
            'ForceNo3DS'            => '1'
        ];

        $response = $this->sendRequest($request);
        
        if($response['code'] != 200){
             Log::info('error http refundTransaction', json_encode($response));
            return [
                'message'   => $response['message'],
                'ok'        => false
            ];
        }

        if($response['data']->IsoCode !== self::OK_RESPONSE_CODE){
             Log::info('error on refundTransaction', json_encode($response['data']));
            return [
                'message'   => $response['data']->ResponseMessage,
                'ok'        => false
            ];
        }

         Log::info('refundTransaction ok', 'request: '.json_encode($request).' response: '.json_encode($response['data']));

        return [
            'message'   => 'ok',
            'ok'        => true,
            'data'      => $response['data']
        ];
    }

    /**
     * Confirm a transaction in hold.
     * @param int $azul_order_id
     * @param double $amount
     * @param double #itbis
     * @return array
     */
    public function confirmTransaction($azul_order_id, $amount, $itbis=""): array
    {
         Log::info('on confirmTransaction', 'params: '.$azul_order_id.', '.$amount.' ,'.$itbis);
        $itbis = (int)$amount * 0.18;
        $request = [
            'Channel'       => self::PAYMENT_CHANNEL,
            'Store'         => self::MERCHANT_ID,
            'Amount'        => $this->parseAmount( $amount ),
            'Itbis'         => $this->parseAmount( $itbis ),
            'AzulOrderId'   => $azul_order_id,
        ];

        $response = $this->sendRequest($request, '?processpost');
        
        if($response['code'] != 200){
             Log::info('error http confirmTransaction', json_encode($response));
            return [
                'message'   => $response['message'],
                'ok'        => false
            ];
        }

        if($response['data']->IsoCode !== self::OK_RESPONSE_CODE){
             Log::info('error on confirmTransaction', json_encode($response['data']));
            return [
                'message'   => $response['data']->ResponseMessage,
                'ok'        => false
            ];
        }
         Log::info('confirmTransaction ok', 'request: '.json_encode($request).' response: '.json_encode($response['data']));

        return [
            'message'   => 'ok',
            'ok'        => true,
            'data'      => $response['data']
        ];
    }

    /**
     * Make http request to provider.
     * @param array $req
     * @param string $url_params
     */
    private function sendRequest($req, $url_params = null)
    {
        $response = [
            'code'      => '',
            'message'   => '',
            'data'      => null,
        ];

        try{

            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => !is_null($url_params) ? $this->MAIN_URL . $url_params : $this->MAIN_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                
                CURLOPT_SSLCERT => public_path('certs/cert-speedy.crt'),
                CURLOPT_SSLKEY => public_path('certs/portal.speedy.do.key'),

                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($req),
                CURLOPT_HTTPHEADER => array(
                    "Auth1: ".$this->AUTH_1_HEADER,
                    "Auth2: ".$this->AUTH_2_HEADER,
                    "Content-Type: application/json"
                ),
            ));

             Log::info('AzulPaymentService.sendRequest'. json_encode($req));

            $result = curl_exec($curl);

            if (curl_errno($curl)) {
                $response['message'] = curl_error($curl);
            }

            $response['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            //If server err, use alternate URL
            if($response['code'] == 500){
                curl_setopt_array($curl, [
                    CURLOPT_URL => !is_null($url_params) ? $this->ALTERNATE_URL . $url_params : $this->ALTERNATE_URL,
                ]);        
                
                $result = curl_exec($curl);     
                if (curl_errno($curl)) {
                    $response['message'] = curl_error($curl);
                }
                $response['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $response['data'] = json_decode($result);      

            }else{
                $response['data'] = json_decode($result);
            }
                
            curl_close($curl);

        }catch (\Exception $e){
            $response['message'] = $e->getMessage();
        }
        
        return $response;
    }

    /**
     * Save user card to provider datavault.
     * @param integet $user_id
     * @param string $card_number
     * @param string $expiration_date
     * @param integet $cvc
     * @return array
     * 
     */
    public function saveCardToDatavault($user_id, $card_number, $expiration_date, $cvc):array
    {
         //Log::info('on saveCardToDatavault', 'try to save card');

        $request = [
            'Channel'               => self::PAYMENT_CHANNEL,
            'Store'                 => self::MERCHANT_ID,
            'CardNumber'            => $card_number,
            'Expiration'            => $expiration_date,
            'CVC'                   => $cvc,
            'TrxType'               => 'CREATE',
        ];

        $response = $this->sendRequest($request, '?ProcessDatavault');

        if($response['code'] != 200){
            // Log::info('error http saveCardToDatavault', json_encode($response['message']));
            return [
                'message'   => $response['message'],
                'ok'        => false
            ];
        }

        if($response['data']->IsoCode !== self::OK_RESPONSE_CODE){
             //Log::info('error on azul saveCardToDatavault', json_encode($response['data']));
            return [
                'message'   => $response['data']->ErrorDescription,
                'ok'        => false
            ];
        }
 
        $datavault = UserDataVault::create([
            'user_id'       => $user_id,
            'token'         => $response['data']->DataVaultToken,
            'expiration'    => $response['data']->Expiration,
            'brand'         => $response['data']->Brand,
            'card_hint'     => $response['data']->CardNumber,
        ]);

        // Log::info('saveCardToDatavault ok!', 'datavault_id '.$datavault->id.' full_response '.json_encode($response['data']));
 
        return [
            'ok' => true,
            'data_vault' => $datavault,
            'full_response' => json_encode($response['data'])
        ];
    }
    
    /**
     * Retrieve user stored cards.
     */
    public function getUserCards($user_id): Collection
    {
        //return UserDataVault::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Delete user datavault.
     * @param \App\Models\UserDataVault $datavault
     * @return array
     */
    public function deleteDatavault(UserDataVault $datavault):array
    {
         Log::info('on deleteDatavault', 'datavault_id: '.$datavault->id);

        $request = [
            'Channel'               => self::PAYMENT_CHANNEL,
            'Store'                 => self::MERCHANT_ID,
            'TrxType'               => 'DELETE',
            'DataVaultToken'        => $datavault->token
        ];

        $response = $this->sendRequest($request, '?ProcessDatavault');

        if($response['code'] != 200){
             Log::info('error http deleteDatavault', json_encode($response['message']));
            return [
                'message'   => $response['message'],
                'ok'        => false
            ];
        }

        if($response['data']->IsoCode !== self::OK_RESPONSE_CODE){
             Log::info('error on azul deleteDatavault', json_encode($response['data']));
            return [
                'message'   => $response['data']->ErrorDescription,
                'ok'        => false
            ];
        }
 
        $datavault->delete();

         Log::info('deleteDatavault ok!', 'request: '.json_encode($request).' response: '.json_encode($response['data']));
 
        return [
            'message'   => 'ok',
            'ok'        => true
        ];
    }

    /**
     * Parse amount value to be acceptable by provider.
     */
    public function parseAmount($amount)
    {
        if(strpos($amount, '.') !== false){
            $decimals = explode('.', $amount)[1];
            if(strlen($decimals) == 1){
                $amount .= '0';
            }
            return str_replace('.', '', $amount);
        }
        return $amount.'00';
    }


    /**
     * Verify a transaction
     * @param $azul_order_id
     */
    public function verifyTransaction($order_id)
    {
         Log::info('on verifyTransaction', 'order_id: '.$order_id);

        $request = [
            'Channel'       => self::PAYMENT_CHANNEL,
            'Store'         => self::MERCHANT_ID,
            'CustomOrderId' => $order_id,
        ];

        $response = $this->sendRequest($request);
        
        if($response['code'] != 200){
             Log::info('error http verifyTransaction', json_encode($response));
            return [
                'message'   => $response['message'],
                'ok'        => false
            ];
        }

        if($response['data']->IsoCode !== self::OK_RESPONSE_CODE){
             Log::info('error on verifyTransaction', json_encode($response['data']));
            return [
                'message'   => $response['data']->ResponseMessage,
                'ok'        => false
            ];
        }
         Log::info('verifyTransaction ok', 'order_id: '.$order_id);

        return [
            'message'   => 'ok',
            'ok'        => true,
            'data'      => $response['data']
        ];
    }

    private function formattErrorMessage($error_code): string
    {
        return isset($this->errors[$error_code]) ? $this->errors[$error_code] : $error_code;
    }
}

