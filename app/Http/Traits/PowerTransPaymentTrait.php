<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\PaymentOption;
use Illuminate\Support\Str;

trait PowerTransPaymentTrait
{
    use ApiResponser;

    public $powertrans_id;
    public $powertrans_password;
    public $test_mode;

    public function __construct()
    {
        $this->creds = PaymentOption::where('code', 'powertrans')->where('status', 1)->first();
        $this->creds_arr = json_decode($this->creds->credentials);
        $this->test_mode = $this->creds->test_mode;
        $this->powertrans_id = $this->creds_arr->powertrans_id;
        $this->powertrans_password = $this->creds_arr->powertrans_password;
    }

   

    public function powerTransApi(Request $request,$description)
    {
        $redirct_url = route('payment.powertrans.success');

        $url = $this->test_mode ? 'https://staging.ptranz.com/api/auth' : 'https://tbd.ptranz.com/api/auth';
        $name = explode(' ',auth()->user()->name);


        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'PowerTranz-PowerTranzId' => $this->powertrans_id, 
            'PowerTranz-PowerTranzPassword' => $this->powertrans_password
        ])->post($url,[
            "TransactionIdentifier" => Str::uuid(),
            "TotalAmount" => 1,
            "CurrencyCode" => "978",
            "ThreeDSecure" => false,
            "Source" => [
                "CardPan" => "5115010000000001",
                "CardCvv" => "1",
                "CardExpiration" => "2512",
                "CardholderName" => "John Doe"
            ],
            "OrderIdentifier" => "INT-c339a65d-0c21-42e7-9942-f3cd675531af-Orc 3569",
            "BillingAddress" => [
                "FirstName" => $name[0] ?? '',
                "LastName" => $name[1] ?? '',
                "Line1" => "",
                "Line2" => "",
                "City" => "",
                "State" => "",
                "PostalCode" => "200341",
                "CountryCode" => "840",
                "EmailAddress" => auth()->user()->email ?? '',
                "PhoneNumber" => auth()->user()->phone_number ?? ''
            ],
            "AddressMatch"=> false
        ])->json();
        $response['redirect_url'] = $redirct_url;
        return $response;
    }

    public function powerTransPayment(Request $request)
    {
        if ($request->payment_from == 'cart') {
            $description = "Oder-".$request->order_number;
          
        } elseif ($request->payment_from == 'pickup_delivery') {
            $description = "Pickup Delivery";
          
        } elseif ($request->payment_from == 'wallet') {
            $description = "Wallet-Credit";
            $request->merge(['order_number' => time() ]);

        } elseif ($request->payment_from == 'tip') {
            $description = "Tip Amount";
            $request->merge(['order_number' => $request->order_number.'-'.time() ]);
  
        } elseif ($request->payment_from == 'subscription') {
            $description = "subscription";      
        }

       return $this->powerTransApi($request,$description);
    }
}