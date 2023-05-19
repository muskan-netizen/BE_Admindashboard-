<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\MtnMomoPaymentManager;
use Illuminate\Http\Request;

class MtnMomoController extends Controller
{
    use MtnMomoPaymentManager;

    public function createToken(Request $request)
    {
        self::__init(false);

        if (!self::$_isConfigurationSet) {
            return self::response(500, 'Sorry for inconvinence. Please try again later');
        }

        $data = [];
        $data['environment'] = 'app';

        if ($request->from == 'cart') {
            $data['amt'] = $request->amount;
            $data['order_number'] = $request->order_number;
            $data['from'] = $request->from;
        } else if ($request->from == 'wallet') {
            $data['amt'] = $request->amount;
            $data['from'] = $request->from;
        } else if ($request->from == 'subscription') {
            $data['amt'] = $request->amount;
            $data['from'] = $request->from;
            $data['subsid'] = $request->subsid;
        } else if ($request->from == 'tip') {
            $data['amt'] = $request->amount;
            $data['from'] = $request->from;
            $data['order_number'] = $request->order_number;
        }

        // generate AccessToken
        self::GenerateAccressToken();

        if (empty(self::$_accessToken)) {
            return response()->json([
                'message' => 'Could not process payment at the moment. Please try again later'
            ], 500);
        }
        // request to pay 
        $response = self::RequestToPay(self::$_accessToken, $data);

        if ($response['status'] == 202) {
            //check transaction status 
            $response = self::getTransactionStatus(self::$_referenceId);
            if (!empty($response)) {
                $url =  self::sucessPayment($data, $response['financialTransactionId']);
                if ($url) {
                    return response()->json([
                        'status' => 'Success',
                        'message' => 'Payment Successful',
                        'url' => $url
                    ], 200);
                }
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Payment Failed',
            'response' => $response
        ], 500);
    }
}
