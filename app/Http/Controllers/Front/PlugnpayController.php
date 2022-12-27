<?php
namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Front\{FrontController, OrderController, PickupDeliveryController};
use App\Http\Traits\ApiResponser;
use App\Http\Traits\PlugnpaypaymentManager;

class PlugnpayController extends FrontController
{
    use PlugnpaypaymentManager;
    use ApiResponser;

    public function beforePayment(Request $request)
    {
        // dd($request->all());
    	$response = $this->createPaymentRequest($request->all());
    	// return Redirect::to($response->data->checkouturl);

        // if ($pnp_handle_post_process != "no") {
        //     if ($pnp_transaction_array['FinalStatus'] == "success") {
        //       echo("success.html");
        //     }
        //     elseif ($pnp_transaction_array['FinalStatus'] == "badcard") {
        //       echo("badcard.html");
        //     }
        //     elseif ($pnp_transaction_array['FinalStatus'] == "fraud") {
        //       echo("fraud.html");
        //     }
        //     elseif ($pnp_transaction_array['FinalStatus'] == "problem") {
        //       echo("problem.html");
        //     }
        //     else {
        //       // this should not happen
        //       echo("error.html");
        //     }
        //   }
    }



}
