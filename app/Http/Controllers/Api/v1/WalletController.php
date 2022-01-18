<?php

namespace App\Http\Controllers\Api\v1;
use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\{User, Transaction};
use App\Http\Controllers\Controller;

class WalletController extends Controller{
    use ApiResponser;

    # get my wallet details 
    public function getFindMyWalletDetails(Request $request){
    	$user = Auth::user();
        $user = User::with('country')->find($user->id);
        $paginate = $request->has('limit') ? $request->limit : 12;
        $transactions = Transaction::where('payable_id', $user->id)->orderBy('id', 'desc')->paginate($paginate);
        foreach($transactions as $trans){
            $trans->meta = json_decode($trans->meta);
            $trans->amount = sprintf("%.2f", $trans->amount / 100);
        }
        $data = ['wallet_amount' => $user->balanceFloat, 'transactions' => $transactions];
        return $this->successResponse($data, '', 200);
    }


    # credit wallet set 
    public function creditMyWallet(Request $request)
    {   
        if($request->has('auth_token')){
            $user = User::whereHas('device',function  ($qu) use ($request){
                $qu->where('access_token', $request->auth_token);
            })->first();
        }
        else{
            $user = Auth::user();
        }
       
        if($user){
            $credit_amount = $request->amount;
            $wallet = $user->wallet;
            if ($credit_amount > 0) {
                $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> by transaction reference <b>'.$request->transaction_id.'</b>']);
                $transactions = Transaction::where('payable_id', $user->id)->get();
                $response['wallet_balance'] = $wallet->balanceFloat;
                $response['transactions'] = $transactions;
                $message = 'Wallet has been credited successfully';
                return $this->successResponse($response, $message, 201);
            }
            else{
                return $this->errorResponse('Amount is not sufficient', 402);
            }
        }
        else{
            return $this->errorResponse('Invalid User', 402);
        }
    }

    /**
     * user verification for wallet transfer
     *
     * @return \Illuminate\Http\Response
     */
    public function walletTransferUserVerify(Request $request){
        try{
            $user = Auth::user();
            $username = $request->username;
            $user_exists = User::select('image', 'name')->where(function($q) use($username){
                $q->where('email', $username)->orWhereRaw("CONCAT(`dial_code`, `phone_number`) = ?", $username);
            })
            ->where('status', 1)->where('id', '!=', $user->id)->first();
            if($user_exists){
                return $this->successResponse($user_exists, __('User is verified'), 201);
            }else{
                return $this->errorResponse('User does not exist', 422);   
            }
        }
        catch(Exception $ex){
            return $this->errorResponse($ex->getMessage(), $ex->getCode);
        }
    }

    /**
     * transfer wallet balance to user
     *
     * @return \Illuminate\Http\Response
     */
    public function walletTransferConfirm(Request $request){
        try{
            $first_user = Auth::user();
            $first_user_balance = $first_user->balanceFloat;
            $username = $request->username;
            $transfer_amount = $request->amount;

            if($transfer_amount < 0){
                return $this->errorResponse(__('Invalid Amount'), 422);
            }
            if($transfer_amount > $first_user_balance){
                return $this->errorResponse(__('Insufficient funds in wallet'), 422);
            }
            
            $transaction_reference = generateWalletTransactionReference();
            
            $second_user = User::where(function($q) use($username){
                $q->where('email', $username)->orWhereRaw("CONCAT(`dial_code`, `phone_number`) = ?", $username);
            })
            ->where('status', 1)->where('id', '!=', $first_user->id)->first();
            if($second_user){
                $first_user->transferFloat($second_user, $transfer_amount, ['Wallet has been transferred with reference <b>'.$transaction_reference.'</b>']);
                $message = __('Amount has been transferred successfully');
                return $this->successResponse('', $message, 201);
            }else{
                return $this->errorResponse('User does not exist', 422);
            }
        }
        catch(Exception $ex){
            return $this->errorResponse($ex->getMessage(), $ex->getCode);
        }
    }
}
