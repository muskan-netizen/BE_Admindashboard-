<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\MtnMomoPaymentManager;
use App\Models\User;
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
        
        switch ($request->from) {
            case 'cart':
                $data['amt'] = $request->amt;
                $data['order_number'] = $request->order_number;
                $data['from'] = $request->from;
                break;
            case 'pickup_delivery':
                $data['amt'] = $request->amt;
                $data['from'] = $request->from;
                break;
            case 'wallet':
                $data['amt'] = $request->amt;
                $data['from'] = $request->from;
                break;
            case 'subscription':
                $data['amt'] = $request->amt;
                $data['from'] = $request->from;
                $data['subsid'] = $request->subsid;
                break;
            case 'tip':
                $data['amt'] = $request->amt;
                $data['from'] = $request->from;
                $data['order_number'] = $request->order_number;
                break;
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
            if (!self::$_isSandbox) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Payment request has been sent successfully'
                ], 200);
            }
            //For Sandbox only
            return self::getSandboxResponse(self::$_referenceId, $request, $data);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Payment Failed',
            'response' => $response
        ], 500);
    }

    public function mtnCallback()
    {
        $payload = file_get_contents('php://input');
        if (empty($payload))
            return false;
        $payload = json_decode($payload, true);
        if (!empty($payload['payer']['partyId'])) {
            $user = User::where('phone_number', $payload['payer']['partyId'])->first();
            $status = $payload['status'];
            if(!empty($status) && $status == 'SUCCESSFUL'){
                
            }
            $request['amt'] = $payload['amount'];
            $transactionId = $payload['financialTransactionId'];
            return response()->json([
                'user' => $user
            ], 200);
        }
    }
}
