<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Timezonelist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\{ApiResponser,PaymentTrait};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Front\FrontController;
use App\Models\{User, UserAddress, ClientPreference, Client, ClientCurrency, SubscriptionPlansUser, SubscriptionFeaturesListUser, SubscriptionInvoicesUser, SubscriptionInvoiceFeaturesUser, Payment, PaymentOption};

class UserSubscriptionController extends FrontController
{
    use ApiResponser,PaymentTrait;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $preferences = ClientPreference::where(['id' => 1])->first();
        if((isset($preferences->subscription_mode)) && ($preferences->subscription_mode == 0)){
            abort(404);
        }
    }

    /**
     * get user subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $currency_id = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        $sub_plans = SubscriptionPlansUser::with('features.feature')->where('status', '1')->orderBy('id', 'asc')->get();
        $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
        $active_subscription = SubscriptionInvoicesUser::with(['plan', 'features.feature'])
                            ->where('user_id', Auth::user()->id)
                            ->orderBy('end_date', 'desc')->first();
        // $active_subscription_plan_ids = array();
        // foreach($active_subscription as $subscription){
        //     $active_subscription_plan_ids[] = $active_subscription->subscription_id;
        // }

        if($sub_plans){
            foreach($sub_plans as $sub){
                $subFeaturesList = array();
                if($sub->features->isNotEmpty()){
                    foreach($sub->features as $feature){
                        $title = $feature->feature->title;
                        if($feature->feature_id == 2){
                            $title = $feature->percent_value . $title;
                        }
                        $subFeaturesList[] = $title;
                    }
                    unset($sub->features);
                }
                $sub->features = $subFeaturesList;
            }
        }
        return view('frontend.account.userSubscriptions')->with(['navCategories'=>$navCategories, 'subscription_plans'=>$sub_plans, 'subscription'=>$active_subscription, 'clientCurrency'=>$clientCurrency]);
    }

    /**
     * select user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectSubscriptionPlan(Request $request, $domain = '', $slug = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $currency_id = Session::get('customerCurrency');
        $currencySymbol = Session::get('currencySymbol');
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        $sub_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
        if($sub_plan){
            $subFeaturesList = '<ul class="list-unstyled">';
            if($sub_plan->features->isNotEmpty()){
                foreach($sub_plan->features as $feature){
                    $title = $feature->feature->title;
                    if($feature->feature_id == 2){
                        $title = $feature->percent_value . $title;
                    }
                    $subFeaturesList = $subFeaturesList.'<li class="d-block"><i class="fa fa-check"></i><span class="ml-1">'.$title.'</span></li>';
                }
                unset($sub_plan->features);
            }
            $subFeaturesList = $subFeaturesList.'<ul>';
            $sub_plan->features = $subFeaturesList;
            $sub_plan->price = $sub_plan->price * $clientCurrency->doller_compare;
        }
        else{
            return response()->json(["status"=>"Error", "message" => __("Subscription plan not active")]);
        }
        $code = $this->paymentOptionArray('Subscription');
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
                }elseif($payment_option->code == 'obo'){
                    $payment_option->title = __("MoMo, Airtel Money, Credit/Debit Cards by O'Pay");
                }elseif($payment_option->code == 'livee'){
                    $payment_option->title = __("Livees");
                }
                $payment_option->title = __($payment_option->title);
                unset($payment_option->credentials);
            }
            else{
                unset($payment_options[$k]);
            }
        }
        return response()->json(["status"=>"Success", "sub_plan" => $sub_plan, "payment_options" => $payment_options, "currencySymbol"=>$currencySymbol]);
    }

    /**
     * check if user has any active subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkActiveSubscription(Request $request, $domain = '', $slug = '')
    {
        $now = Carbon::now()->toDateString();
        $userActiveSubscription = SubscriptionInvoicesUser::with(['plan'])
                                ->whereNull('cancelled_at')
                                ->where('user_id', Auth::user()->id)
                                ->where('end_date', '>=', $now )
                                ->orderBy('end_date', 'desc')->first();
        if( ($userActiveSubscription) && ($userActiveSubscription->plan->slug != $slug) ){
            return $this->errorResponse(__('You cannot buy two subscriptions at the same time'), 402);
        }
        return $this->successResponse('', 'Processing...');
    }

    /**
     * buy user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseSubscriptionPlan(Request $request, $domain = '', $slug = '')
    {
        $currency_id = Session::get('customerCurrency')??63;
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();

        $dollar_compare =  !empty($clientCurrency)?$clientCurrency->doller_compare:1;
        if( (isset($request->user_id)) && (!empty($request->user_id)) ){
            $user = User::find($request->user_id);
        }else{
            $user = Auth::user();
        }
        $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
        $last_subscription = SubscriptionInvoicesUser::with(['plan', 'features.feature'])
            ->where('user_id', $user->id)
            ->where('subscription_id', $subscription_plan->id)
            ->orderBy('end_date', 'desc')->first();
        if( ($user) && ($subscription_plan) ){
            $subscription_invoice = new SubscriptionInvoicesUser;
            $subscription_invoice->user_id = $user->id;
            $subscription_invoice->subscription_id = $subscription_plan->id;
            $subscription_invoice->slug = strtotime(Carbon::now()).'_'.$slug;
            $subscription_invoice->payment_option_id = $request->payment_option_id;
            // $subscription_invoice->status_id = 2;
            $subscription_invoice->frequency = $subscription_plan->frequency;
            $subscription_invoice->payment_option_id = $request->payment_option_id;
            $subscription_invoice->transaction_reference = $request->transaction_id;
            $now = Carbon::now();
            $current_date = $now->toDateString();
            $start_date = $current_date;
            $next_date = NULL;
            $end_date = NULL;

            if($last_subscription){
                if($last_subscription->end_date >= $current_date){
                    $start_date = Carbon::parse($last_subscription->end_date)->addDays(1)->toDateString();
                }
            }
            if($subscription_plan->frequency == 'weekly'){
                $end_date = Carbon::parse($start_date)->addDays(6)->toDateString();
            }elseif($subscription_plan->frequency == 'monthly'){
                $end_date = Carbon::parse($start_date)->addMonths(1)->subDays(1)->toDateString();
            }elseif($subscription_plan->frequency == 'yearly'){
                $end_date = Carbon::parse($start_date)->addYears(1)->subDays(1)->toDateString();
            }
            $next_date = Carbon::parse($end_date)->addDays(1)->toDateString();
            $subscription_invoice->start_date = $start_date;
            $subscription_invoice->next_date = $next_date;
            $subscription_invoice->end_date = $end_date;
            $subscription_invoice->subscription_amount = $request->amount / $dollar_compare;
            $subscription_invoice->save();
            $subscription_invoice_id = $subscription_invoice->id;
            if($subscription_invoice_id){
                $payment = new Payment;
                $payment->user_id = $user->id;
                $payment->balance_transaction = $request->amount / $dollar_compare;
                $payment->transaction_id = $request->transaction_id;
                $payment->user_subscription_invoice_id = $subscription_invoice_id;
                $payment->payment_option_id = $request->payment_option_id;
                $payment->date = Carbon::now()->format('Y-m-d');
                $payment->type = 'subscription';
                $payment->save();

                $subscription_invoice_features = array();
                foreach($subscription_plan->features as $feature){
                    $features_array = array(
                        'user_id' => $user->id,
                        'subscription_id' => $subscription_plan->id,
                        'subscription_invoice_id' => $subscription_invoice_id,
                        'feature_id' => $feature->feature_id,
                        'feature_title' => $feature->feature->title,
                        'percent_value' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    );
                    if($feature->feature_id == 2){
                        $features_array['percent_value'] = $feature->percent_value;
                    }
                    $subscription_invoice_features[] = $features_array;
                }
                if(!empty($subscription_invoice_features)){
                    SubscriptionInvoiceFeaturesUser::insert($subscription_invoice_features);
                }
                $message = __('Your subscription has been activated successfully.');
                Session::put('success', $message);
                return $this->successResponse('', $message);
            }
            else{
                return $this->errorResponse(__('Error in purchasing subscription.'), 402);
            }
        }
        else{
            return $this->errorResponse(__('Invalid Data'), 402);
        }
    }

    /**
     * cancel user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscriptionPlan(Request $request, $domain = '', $slug = '')
    {
        $active_subscription = SubscriptionInvoicesUser::with('plan')
                            ->where('slug', $slug)
                            ->where('user_id', Auth::user()->id)
                            ->orderBy('end_date', 'desc')->first();
        if($active_subscription){
            $active_subscription->cancelled_at = $active_subscription->end_date;
            $active_subscription->updated_at = Carbon::now()->toDateTimeString();
            $active_subscription->save();
            return redirect()->back()->with('success', 'Your '.$active_subscription->plan->title.' subscription has been cancelled successfully');
        }
        else{
            return redirect()->back()->with('error', __('Unable to cancel subscription'));
        }
    }
}
