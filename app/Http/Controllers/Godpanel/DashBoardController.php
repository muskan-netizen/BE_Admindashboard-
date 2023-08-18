<?php

namespace App\Http\Controllers\Godpanel;

use DB;
use App\Http\Controllers\Controller;
use App\Models\{BillingPlan, BillingPlanType, BillingTimeframe, BillingPricing, BillingSubscription, Client, BillingPaymentTransation};
use App\Http\Controllers\Client\BaseController;
use App\Http\Traits\BillingPlanManager;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('godpanel/dashboard');
        
    }

    public function dashboard()
    {
        $onboardclients = Client::where('status', 1)->where('is_deleted',0)->where('is_blocked', 0)->count();
        $allclients = Client::select(DB::Raw("GROUP_CONCAT(id) as ids"))->where('status', 1)->where('is_deleted',0)->where('is_blocked', 0)->first()->ids??0;

        $activeSubs  = BillingSubscription::join('clients', 'clients.id', '=', 'billing_subscriptions.client_id')
                                                    ->where('clients.status', 1)->where('clients.is_deleted',0)->where('clients.is_blocked', 0)
                                                    ->where(function ($q) {
                                                        $q->where('end_date', '>=', date('Y-m-d',time()))
                                                              ->orWhereNull('end_date');
                                                    })->count();
        
        $expSofSubs  = BillingSubscription::join('billing_pricings', 'billing_pricings.id', '=', 'billing_subscriptions.billing_price_id')
                                            ->join('billing_plans', 'billing_plans.id', '=', 'billing_pricings.billing_plan_id')
                                            ->where('billing_plans.plan_type', 1);
                                            if(@$allclients && !empty($allclients)){
                                                $expSofSubs = $expSofSubs->whereRaw("client_id in ('".$allclients."') and billing_subscriptions.id in (select MAX(id) from billing_subscriptions GROUP BY client_id) and date(end_date)< date(NOW())");
                                            }
                                            $expSofSubs = $expSofSubs->count();

        $expHosSubs  = BillingSubscription::join('billing_pricings', 'billing_pricings.id', '=', 'billing_subscriptions.billing_price_id')
                                            ->join('billing_plans', 'billing_plans.id', '=', 'billing_pricings.billing_plan_id')
                                            ->where('billing_plans.plan_type', 2);
                                            if(@$allclients && !empty($allclients)){
                                                $expHosSubs = $expHosSubs->whereRaw("client_id in ('".$allclients."') and billing_subscriptions.id in (select MAX(id) from billing_subscriptions GROUP BY client_id) and date(end_date)< date(NOW())");
                                                }
                                            $expHosSubs = $expHosSubs->count();

        $clientwithnosubs = Client::where('status', 1)->where('is_deleted',0)->where('is_blocked', 0)->whereRaw("(select count(*) from billing_subscriptions where billing_subscriptions.client_id = clients.id)=0")->count();
        
        return view('godpanel/dashboard')->with(['onboardclients'=>$onboardclients, 'activeSubs'=>$activeSubs, 'expSofSubs'=>$expSofSubs, 'expHosSubs'=>$expHosSubs, 'clientwithnosubs'=>$clientwithnosubs]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
}
