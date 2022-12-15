<?php

namespace App\Http\Controllers\Front\giftCard;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Session,Auth,DB,Timezonelist;
use App\Http\Traits\Giftcard\GiftCardTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Front\FrontController;
use App\Models\{GiftCard,UserGiftCard,User, Cart, ClientPreference, Client, ClientCurrency , Payment, PaymentOption};

class GiftcardController extends FrontController
{
    use ApiResponser,GiftCardTrait;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $getAdditionalPreference = getAdditionalPreference(['is_gift_card']);
        if(@getAdditionalPreference(['is_gift_card'])['is_gift_card']==0){
            abort(404);
        }
    }

    /**
     * get user subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGiftCard(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $now = Carbon::now()->toDateTimeString();
        $now = convertDateTimeInClientTimeZone($now);
        $currency_id = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        $GiftCard       = GiftCard::orderBy('id', 'asc')->whereDate('expiry_date', '>=', $now)->get();
        $active_giftcard =$this->getUserActiveGiftCard();
        return view('frontend.account.giftcard')->with(['navCategories'=>$navCategories, 'GiftCard'=>$GiftCard, 'active_giftcards'=>$active_giftcard, 'clientCurrency'=>$clientCurrency]);
    }
    
    /**
     * select user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectGiftCardPayment(Request $request, $domain = '', $id = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories  = $this->categoryNav($langId);
        $currency_id    = Session::get('customerCurrency');
        $currencySymbol = Session::get('currencySymbol');
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        $GiftCard       = GiftCard::where('id', $id)->first();
      
        $code = array('stripe');
        $ex_codes = array('cod');
        $payment_options = PaymentOption::select('id', 'code', 'title', 'credentials')->whereIn('code', $code)->where('status', 1)->get();
        foreach ($payment_options as $k => $payment_option) {
            if( (in_array($payment_option->code, $ex_codes)) || (!empty($payment_option->credentials)) ){
                $payment_option->slug = strtolower(str_replace(' ', '_', $payment_option->title));
                if($payment_option->code == 'stripe'){
                    $payment_option->title = 'Credit/Debit Card (Stripe)';
                }elseif($payment_option->code == 'kongapay'){
                    $payment_option->title = 'Pay Now';
                }elseif($payment_option->code == 'mvodafone'){
                    $payment_option->title = 'Vodafone M-PAiSA';
                }elseif($payment_option->code == 'offline_manual'){
                    $json = json_decode($payment_option->credentials);
                    $payment_option->title = $json->manule_payment_title;
                }elseif($payment_option->code == 'mycash'){
                    $payment_option->title = __('Digicel MyCash');
                }elseif($payment_option->code == 'windcave'){
                    $payment_option->title = __('Windcave (Debit/Credit card)');
                }elseif($payment_option->code == 'stripe_ideal'){
                    $payment_option->title = __('iDEAL');
                }elseif($payment_option->code == 'authorize_net'){
                    $payment_option->title = __('Credit/Debit Card');
                }
                $payment_option->title = __($payment_option->title);
                unset($payment_option->credentials);
            }
            else{
                unset($payment_options[$k]);
            }
        }
        return response()->json(["status"=>"Success", "GiftCard" => $GiftCard, "payment_options" => $payment_options, "currencySymbol"=>$currencySymbol]);
    }

    

    /**
     * buy user giftCard.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseGiftCard(Request $request, $domain = '', $gift_card_id = '')
    {
        //pr( $request->all());
        if( (isset($request->user_id)) && (!empty($request->user_id)) ){
            $user = User::find($request->user_id);
        }else{
            $user = Auth::user();
        }
        $GiftCard       = GiftCard::where('id', $gift_card_id)->first();
        // $senderData = !empty($request->senderData) ? json_decode($request->senderData) : '';
        // if(isset($senderData['send_card_to_email']) && !empty($senderData['send_card_to_email'])){
        //     //send mail
        // }
        if( $GiftCard ){
            $UserGiftCard               = new UserGiftCard();
            $UserGiftCard->user_id      = $user->id;
            $UserGiftCard->gift_card_id = $GiftCard->id;
            $UserGiftCard->amount       = $GiftCard->amount;
            $UserGiftCard->expiry_date  = $GiftCard->expiry_date;
            $UserGiftCard->buy_for_data = !empty($request->senderData) ? $request->senderData : ''; 
            $UserGiftCard->save();

            $payment                        = new Payment;
            $payment->user_id               = $user->id;
            $payment->balance_transaction   = $request->amount;
            $payment->transaction_id        = $request->transaction_id;
            $payment->reference_table_id    = $UserGiftCard->id;
            $payment->payment_option_id     = $request->payment_option_id;
            $payment->date                  = Carbon::now()->format('Y-m-d');
            $payment->type                  = 'giftCard';
            $payment->save();
            
            $message = __('Your Gift Card has been activated successfully.');
            Session::put('success', $message);
            return $this->successResponse('', $message);
            
        }else{
            return $this->errorResponse(__('Invalid Data'), 402);
        }
    }

    /**
     * buy user postGiftCardLisTCart.
     *
     * @return \Illuminate\Http\Response
     */
    public function postGiftCardLisTCart(Request $request){
         try {
            $user = Auth::user();
            $langId   = Session::has('customerLanguage') ? Session::get('customerLanguage') : 1;
            $giftCard = new \Illuminate\Database\Eloquent\Collection;
            $giftcardList = $this->getUserActiveGiftCard();
            $currency_id = Session::get('customerCurrency');
            $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
            $returnHTML = view('frontend.cart.giftCard')->with(['giftcardList'=>$giftcardList, 'clientCurrency'=>$clientCurrency])->render();
            return response()->json(array('success' => true, 'html' => $returnHTML));
           
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * buy user verify code.
     *
     * @return \Illuminate\Http\Response
     */
    public function postVerifyGiftCardCode(Request $request){
       // try {
            $user = Auth::user();
            $now = Carbon::now()->toDateTimeString();
            $now = convertDateTimeInClientTimeZone($now);
          // pr( $user);
            $cart_detail = Cart::where('id', $request->cart_id)->first();
            if(!$cart_detail){
                return $this->errorResponse('Invalid Cart Id', 422);
            }
            $giftcard = UserGiftCard::with('giftCard')->whereHas('giftCard',function ($query) use ($now){
                return  $query->whereDate('expiry_date', '>=', $now);
            })->where(['is_used'=>'0','user_id'=>$user->id,'id'=>$request->giftCard_id])->first();
            if($giftcard){
                if($cart_detail->gift_card_id ==  $giftcard->gift_card_id){
                    return $this->errorResponse('Gift Card already applied.', 422);
                }
                $cart_detail->gift_card_id = $giftcard->gift_card_id;
                $cart_detail->save();
                return $this->successResponse($giftcard, 'Gift Card Used Successfully.', 200);
            }
            return $this->errorResponse('Invalid gift Card Id', 422);
           
        // } catch (Exception $e) {
        //     return $this->errorResponse($e->getMessage(), $e->getCode());
        // }
    }

    public function RemoveGiftCardCode(Request $request){
        // try {
            //pr($request->all());
             $user = Auth::user();
             
           // pr( $user);
             $cart_detail = Cart::where('id', $request->cart_id)->first();
             if(!$cart_detail){
                 return $this->errorResponse('Invalid Cart Id', 422);
             }
          
            if($cart_detail){
            // pr($cart_detail);
                $cart_detail->gift_card_id = null;
                $cart_detail->save();
            // pr($cart_detail);
                return $this->successResponse($cart_detail, 'Gift Card Delete Successfully.', 200);
            }
             return $this->errorResponse('Invalid gift Card Id', 422);
            
         // } catch (Exception $e) {
         //     return $this->errorResponse($e->getMessage(), $e->getCode());
         // }
     }
   
}

