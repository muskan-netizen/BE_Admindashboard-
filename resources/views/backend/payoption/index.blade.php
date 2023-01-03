@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Payment Options'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid alpaymentOptionPage">

    <!-- start page title -->
    <div class="row">
        <!-- <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Payment Options</h4>
            </div>
        </div> -->
        <div class="col-12">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert mt-2 mb-0 alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @endif
                @if ( ($errors) && (count($errors) > 0) )
                <div class="alert mt-2 mb-0 alert-danger">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>

    <form method="POST" id="payment_option_form" action="{{route('payoption.updateAll')}}">
        @csrf
        @method('POST')
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title">{{ __("Payment Options") }}</h4>
                    <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
                </div>
            </div>
        </div>
        <div class="row">

            @foreach($payOption as $key => $opt)
            <div class="col-6 col-md-3 col-xl-2 mb-3">

                <input type="hidden" name="method_id[]" id="{{$opt->id}}" value="{{$opt->id}}">
                <input type="hidden" name="method_name[]" id="{{$opt->code}}" value="{{$opt->code}}">

                <?php
                $creds = json_decode($opt->credentials);
                $id = (isset($creds->id)) ? $creds->id : '';
                $easypaisa_store_id = (isset($creds->easypaisa_store_id)) ? $creds->easypaisa_store_id : '';
                $token = (isset($creds->token)) ? $creds->token : '';
                $cod_min_amount = (isset($creds->cod_min_amount)) ? $creds->cod_min_amount : '';
                $username = (isset($creds->username)) ? $creds->username : '';
                $password = (isset($creds->password)) ? $creds->password : '';
                $signature = (isset($creds->signature)) ? $creds->signature : '';
                $app_id = (isset($creds->app_id)) ? $creds->app_id : '';
                $api_key = (isset($creds->api_key)) ? $creds->api_key : '';
                $location_id= (isset($creds->location_id)) ? $creds->location_id : '';
                $application_id = (isset($creds->application_id)) ? $creds->application_id : '';
                $api_access_token = (isset($creds->api_access_token)) ? $creds->api_access_token : '';
                $api_secret_key = (isset($creds->api_secret_key)) ? $creds->api_secret_key : '';
                $publishable_key = (isset($creds->publishable_key)) ? $creds->publishable_key : '';
                $secret_key = (isset($creds->secret_key)) ? $creds->secret_key : '';
                $public_key = (isset($creds->public_key)) ? $creds->public_key : '';
                $private_key = (isset($creds->private_key)) ? $creds->private_key : '';
                $site_code = (isset($creds->site_code)) ? $creds->site_code : '';
                $merchant_id = (isset($creds->merchant_id)) ? $creds->merchant_id : '';
                $merchant_key = (isset($creds->merchant_key)) ? $creds->merchant_key : '';
                $passphrase = (isset($creds->passphrase)) ? $creds->passphrase : '';
                $merchant_account = (isset($creds->merchant_account)) ? $creds->merchant_account : '';
                $merchant_salt_v1 = (isset($creds->merchant_salt_v1)) ? $creds->merchant_salt_v1 : '';
                $merchant_salt_v2 = (isset($creds->merchant_salt_v2)) ? $creds->merchant_salt_v2 : '';
                $multiplier = (isset($creds->multiplier)) ? $creds->multiplier : '';
                $login_id = (isset($creds->login_id)) ? $creds->login_id : '';
                $profile_id = (isset($creds->profile_id)) ? $creds->profile_id : '';
                $transaction_key = (isset($creds->transaction_key)) ? $creds->transaction_key : '';
                $client_id = (isset($creds->client_id)) ? $creds->client_id : '';
                $client_key = (isset($creds->client_key)) ? $creds->client_key : '';
                $server_key = (isset($creds->server_key)) ? $creds->server_key : '';
                $mobile_client_key = (isset($creds->mobile_client_key)) ? $creds->mobile_client_key : '';
                $mobile_server_key = (isset($creds->mobile_server_key)) ? $creds->mobile_server_key : '';
                $access_code = (isset($creds->access_code)) ? $creds->access_code : '';
                $enc_key = (isset($creds->enc_key)) ? $creds->enc_key : '';
                $easypaisa_store_id = (isset($creds->easypaisa_store_id)) ? $creds->easypaisa_store_id : '';
                $toyyibpay_api_key = (isset($creds->toyyibpay_api_key)) ? $creds->toyyibpay_api_key : '';
                $toyyibpay_redirect_uri = (isset($creds->toyyibpay_redirect_uri)) ? $creds->toyyibpay_redirect_uri : '';
                $easebuzz_merchant_key = (isset($creds->easebuzz_merchant_key)) ? $creds->easebuzz_merchant_key : '';
                $easebuzz_salt = (isset($creds->easebuzz_salt)) ? $creds->easebuzz_salt : '';
                $easebuzz_Sub_merchant = (isset($creds->easebuzz_Sub_merchant)) ? $creds->easebuzz_Sub_merchant : '';
                $vnpay_website_id = (isset($creds->vnpay_website_id)) ? $creds->vnpay_website_id : '';
                $vnpay_server_key = (isset($creds->vnpay_server_key)) ? $creds->vnpay_server_key : '';
                $merchant_phone = (isset($creds->merchant_phone)) ? $creds->merchant_phone : '';
                $manule_payment_title = (isset($creds->manule_payment_title)) ? $creds->manule_payment_title : '';
                $userede_Rede_PV = (isset($creds->userede_Rede_PV)) ? $creds->userede_Rede_PV : '';
                $userede_Rede_token = (isset($creds->userede_Rede_token)) ? $creds->userede_Rede_token : '';
                $openpay_merchant_id = (isset($creds->openpay_merchant_id)) ? $creds->openpay_merchant_id : '';
                $openpay_private_key = (isset($creds->openpay_private_key)) ? $creds->openpay_private_key : '';
                $openpay_public_key = (isset($creds->openpay_public_key)) ? $creds->openpay_public_key : '';
                $openpay_verification_key = (isset($creds->openpay_verification_key)) ? $creds->openpay_verification_key : '';

                $company_token = (isset($creds->company_token)) ? $creds->company_token : '';
                $service_type = (isset($creds->service_type)) ? $creds->service_type : '';
                $aes_key = (isset($creds->aes_key)) ? $creds->aes_key : '';
                $uuid_key = (isset($creds->uuid_key)) ? $creds->uuid_key : '';
                ?>

                <div class="card-box h-100 mb-0">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0"><span class="alPaymentImage" style="height:24px;width:24px;display:inline-block;"> <img style="width:100%;" src="{{asset('paymentsLogo/'.$opt->code.'.png')}}" alt=""></span> {{$opt->title}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo">
                                <label for="" class="mr-0 d-block">{{ __("Enable") }}</label>
                                <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->code}}" data-plugin="switchery" name="active[{{$opt->id}}]" class="chk_box all_select" data-color="#43bee1" @if($opt->status == 1) checked @endif>
                            </div>
                        </div>
                        @if ( (strtolower($opt->code) != 'dpo') &&  (strtolower($opt->code) != 'cod') &&  (strtolower($opt->code) != 'razorpay') &&  (strtolower($opt->code) != 'simplify') && (strtolower($opt->code)!= 'kongapay') && (strtolower($opt->code)!= 'windcave') && (strtolower($opt->code)!= 'payphone') && (strtolower($opt->code)!= 'offline_manual') && (strtolower($opt->code) != 'khalti'))
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo">
                                <label for="" class="mr-0 d-block">{{ __('Sandbox') }}</label>
                                <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->code}}" data-plugin="switchery" name="sandbox[{{$opt->id}}]" class="chk_box" data-color="#43bee1" @if($opt->test_mode == 1) checked @endif>
                            </div>
                        </div>
                        @endif
                    </div>


                    @if ( (strtolower($opt->code) == 'cod') )
                    <div class="mt-2" id="cod_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="cod_min_amount" class="mr-3">{{ __("Minimum Amount For Cod") }}</label>
                                    <input type="text" name="cod_min_amount" id="cod_min_amount" class="form-control" value="{{@$cod_min_amount}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'easypaisa') )
                    <div class="mt-2" id="easypaisa_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="easypaisa_merchant" class="mr-3">{{ __("Store Id") }}</label>
                                    <input type="text" name="easypaisa_store_id" id="easypaisa_store_id" class="form-control" value="{{$easypaisa_store_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'viva_wallet') )
                    <div class="mt-2" id="viva_wallet_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="viva_wallet_merchant" class="mr-3">{{ __("Merchant Id") }}</label>
                                    <input type="text" name="viva_wallet_merchant_id" id="viva_wallet_merchant_id" class="form-control" value="{{$merchant_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="viva_wallet_merchant" class="mr-3">{{ __("Encryption Key") }}</label>
                                    <input type="text" name="viva_wallet_merchant_key" class="form-control" value="{{$merchant_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <h6 class="ml-2"><u>{{__('Smart Checkout Credentials')}}</u></h6>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="viva_wallet_merchant" class="mr-3">{{ __("Client Id") }}</label>
                                    <input type="text" name="viva_wallet_client_id" class="form-control" value="{{$client_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="viva_wallet_merchant" class="mr-3">{{ __("Client Key") }}</label>
                                    <input type="text" name="viva_wallet_client_key" class="form-control" value="{{$client_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'ccavenue') )
                    <div class="mt-2" id="ccavenue_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="ccavenue_merchant" class="mr-3">{{ __("Merchant Id") }}</label>
                                    <input type="text" name="ccavenue_merchant_id" id="ccavenue_merchant_id" class="form-control" value="{{$merchant_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                             <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="ccavenue_access_code" class="mr-3">{{ __("Access Code") }}</label>
                                    <input type="text" name="ccavenue_access_code" id="ccavenue_access_code" class="form-control" value="{{$access_code}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="ccavenue_merchant" class="mr-3">{{ __("Encryption Key") }}</label>
                                    <input type="text" name="ccavenue_enc_key" id="ccavenue_enc_key" class="form-control" value="{{$enc_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif


                    @if ( (strtolower($opt->code) == 'kongapay') )
                    <div class="mt-2" id="kongapay_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="kongapay_api_key" class="mr-3">{{ __("API Key") }}</label>
                                    <input type="text" name="kongapay_api_key" id="kongapay_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="kongapay_merchant" class="mr-3">{{ __("Merchant Id") }}</label>
                                    <input type="text" name="kongapay_merchant_id" id="kongapay_merchant_id" class="form-control" value="{{$merchant_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'stripe') )
                    <div class="mt-2" id="stripe_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_api_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="stripe_api_key" id="stripe_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_publishable_key" class="mr-3">{{ __("Publishable Key") }}</label>
                                    <input type="password" name="stripe_publishable_key" id="stripe_publishable_key" class="form-control" value="{{$publishable_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'stripe_fpx') )
                    <div class="mt-2" id="stripe_fpx_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_fpx_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="stripe_fpx_secret_key" id="stripe_fpx_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_fpx_publishable_key" class="mr-3">{{ __("Publishable Key") }}</label>
                                    <input type="password" name="stripe_fpx_publishable_key" id="stripe_fpx_publishable_key" class="form-control" value="{{$publishable_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'paypal') )
                    <div class="mt-2" id="paypal_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paypal_username" class="mr-3">{{ __("Username") }}</label>
                                    <input type="textbox" name="paypal_username" id="paypal_username" class="form-control" value="{{$username}}" @if($opt->status == 1) value="" required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paypal_password" class="mr-3">{{ __("Password") }}</label>
                                    <input type="password" name="paypal_password" id="paypal_password" class="form-control" value="{{$password}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paypal_signature" class="mr-3">{{ __("Signature") }}</label>
                                    <input type="password" name="paypal_signature" id="paypal_signature" class="form-control" value="{{$signature}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'paystack') )
                    <div class="mt-2" id="paystack_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paystack_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paystack_public_key" class="mr-3">{{ __("Publishable Key") }}</label>
                                    <input type="password" name="paystack_public_key" id="paystack_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'payfast') )
                    <div class="mt-2" id="payfast_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payfast_merchant_id" class="mr-3">{{ __("Merchant ID") }}</label>
                                    <input type="text" name="payfast_merchant_id" id="payfast_merchant_id" class="form-control" value="{{$merchant_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payfast_merchant_key" class="mr-3">{{ __("Merchant Key") }}</label>
                                    <input type="password" name="payfast_merchant_key" id="payfast_merchant_key" class="form-control" value="{{$merchant_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payfast_passphrase" class="mr-3">{{ __("Passphrase") }}</label>
                                    <input type="text" name="payfast_passphrase" id="payfast_passphrase" class="form-control" value="{{$passphrase}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'mobbex') )
                    <div class="mt-2" id="mobbex_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="mobbex_api_key" class="mr-3">{{ __("API Key") }}</label>
                                    <input type="text" name="mobbex_api_key" id="mobbex_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="mobbex_api_access_token" class="mr-3">{{ __("API Access Token") }}</label>
                                    <input type="password" name="mobbex_api_access_token" id="mobbex_api_access_token" class="form-control" value="{{$api_access_token}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'yoco') )
                    <div class="mt-2" id="yoco_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="yoco_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="yoco_secret_key" id="yoco_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="yoco_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="password" name="yoco_public_key" id="yoco_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'paylink') )
                    <div class="mt-2" id="paylink_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paylink_api_key" class="mr-3">{{ __("API Key") }}</label>
                                    <input type="password" name="paylink_api_key" id="paylink_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paylink_api_secret_key" class="mr-3">{{ __("API Secret Key") }}</label>
                                    <input type="password" name="paylink_api_secret_key" id="paylink_api_secret_key" class="form-control" value="{{$api_secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'razorpay') )
                    <div class="2" id="razorpay_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="razorpay_api_key" class="mr-3">{{ __("API Key") }}</label>
                                    <input type="text" name="razorpay_api_key" id="razorpay_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="razorpay_api_secret_key" class="mr-3">{{ __("API Secret Key") }}</label>
                                    <input type="text" name="razorpay_api_secret_key" id="razorpay_api_secret_key" class="form-control" value="{{$api_secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'gcash') )
                    <div class="mt-2" id="gcash_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="gcash_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="text" name="gcash_public_key" id="gcash_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'simplify') )
                    <div class="mt-2" id="simplify_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="simplify_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="text" name="simplify_public_key" id="simplify_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="simplify_private_key" class="mr-3">{{ __("Private Key") }}</label>
                                    <input type="password" name="simplify_private_key" id="simplify_private_key" class="form-control" value="{{$private_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'square') )
                    <div class="mt-2" id="square_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="square_application_id" class="mr-3">{{ __("Application ID") }}</label>
                                    <input type="text" name="square_application_id" id="square_application_id" class="form-control" value="{{$application_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="square_access_token" class="mr-3">{{ __("Access Token") }}</label>
                                    <input type="password" name="square_access_token" id="square_access_token" class="form-control" value="{{$api_access_token}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="square_location_id" class="mr-3">{{ __("Location ID") }}</label>
                                    <input type="text" name="square_location_id" id="square_location_id" class="form-control" value="{{$location_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'ozow') )
                    <div class="mt-2" id="ozow_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="ozow_site_code" class="mr-3">{{ __("Site Code") }}</label>
                                    <input type="text" name="ozow_site_code" id="ozow_site_code" class="form-control" value="{{$site_code}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="ozow_private_key" class="mr-3">{{ __("Private Key") }}</label>
                                    <input type="password" name="ozow_private_key" id="ozow_private_key" class="form-control" value="{{$private_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="ozow_api_key" class="mr-3">{{ __("API Key") }}</label>
                                    <input type="text" name="ozow_api_key" id="ozow_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'pagarme') )
                    <div class="mt-2" id="pagarme_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="pagarme_api_key" class="mr-3">{{ __("API Key") }}</label>
                                    <input type="text" name="pagarme_api_key" id="pagarme_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="pagarme_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="pagarme_secret_key" id="pagarme_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="pagarme_multiplier" class="mr-3">{{ __("Multiplier") }}</label>
                                    <input type="number" name="pagarme_multiplier" id="pagarme_multiplier" class="form-control" value="{{$multiplier}}" step="0.01" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'checkout') )
                    <div class="mt-2" id="checkout_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="checkout_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="checkout_secret_key" id="checkout_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="checkout_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="password" name="checkout_public_key" id="checkout_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'authorize_net') )
                    <div class="mt-2" id="authorize_net_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="authorize_net_login_id" class="mr-3">{{ __("Login ID") }}</label>
                                    <input type="text" name="authorize_net_login_id" id="authorize_net_login_id" class="form-control" value="{{$login_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="authorize_net_transaction_key" class="mr-3">{{ __("Transaction Key") }}</label>
                                    <input type="password" name="authorize_net_transaction_key" id="authorize_net_transaction_key" class="form-control" value="{{$transaction_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="authorize_net_client_key" class="mr-3">{{ __("Public Client Key") }}</label>
                                    <input type="text" name="authorize_net_client_key" id="authorize_net_client_key" class="form-control" value="{{$client_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'cashfree') )
                    <div class="mt-2" id="cashfree_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="cashfree_app_id" class="mr-3">{{ __("App ID") }}</label>
                                    <input type="password" name="cashfree_app_id" id="cashfree_app_id" class="form-control" value="{{$app_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="cashfree_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="cashfree_secret_key" id="cashfree_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'toyyibpay') )
                        <div class="mt-2" id="toyyibpay_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label for="toyyibpay_api_key" class="mr-3">{{ __("Secret Key") }}</label>
                                        <input type="password" name="toyyibpay_api_key" id="toyyibpay_api_key" class="form-control" value="{{$toyyibpay_api_key??''}}" @if($opt->status == 1) required @endif>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label for="toyyibpay_redirect_uri" class="mr-3">{{ __("Redirect URL") }}</label>
                                        <input type="password" name="toyyibpay_redirect_uri" id="toyyibpay_redirect_uri" class="form-control" value="{{$toyyibpay_redirect_uri??''}}" @if($opt->status == 1) required @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'easebuzz') )
                    <div class="mt-2" id="easebuzz_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                    <h6 class="mt-3">
                        <span>{{ __('Webhook Url') }} : </span>
                        <a href="javascript:;" class="webhook_url"><span id="pwd_spn" class="password-span">{{route('payment.easebuzz.easybuzzNotify')}}</span></a>
                    </h6>
                        <div class="row">
                        <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="easebuzz_merchant_key" class="mr-3">{{ __("Sub Merchant") }}</label>
                                    <input type="checkbox"   data-plugin="switchery" name="easebuzz_Sub_merchant" class="chk_box" data-color="#43bee1" @if($easebuzz_Sub_merchant == 1) checked @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="easebuzz_merchant_key" class="mr-3">{{ __("Merchant Key") }}</label>
                                    <input type="text" name="easebuzz_merchant_key" id="easebuzz_merchant_key" class="form-control" value="{{$easebuzz_merchant_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="easebuzz_salt" class="mr-3">{{ __("Salt") }}</label>
                                    <input type="text" name="easebuzz_salt" id="easebuzz_salt" class="form-control" value="{{$easebuzz_salt}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'paytab') )
                    <div class="mt-2" id="paytab_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paytab_profile_id" class="mr-3">{{ __("Profile ID") }}</label>
                                    <input type="text" name="paytab_profile_id" id="paytab_profile_id" class="form-control" value="{{$profile_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paytab_server_key" class="mr-3">{{ __("Standard Server Key") }}</label>
                                    <input type="text" name="paytab_server_key" id="paytab_server_key" class="form-control" value="{{$server_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paytab_client_key" class="mr-3">{{ __("Standard Client Key") }}</label>
                                    <input type="text" name="paytab_client_key" id="paytab_client_key" class="form-control" value="{{$client_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paytab_mobile_server_key" class="mr-3">{{ __("Mobile Server Key") }}</label>
                                    <input type="text" name="paytab_mobile_server_key" id="paytab_mobile_server_key" class="form-control" value="{{$mobile_server_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paytab_mobile_client_key" class="mr-3">{{ __("Mobile Client Key") }}</label>
                                    <input type="text" name="paytab_mobile_client_key" id="paytab_mobile_client_key" class="form-control" value="{{$mobile_client_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'vnpay') )
                    <div class="mt-2" id="vnpay_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="vnpay_website_id" class="mr-3">{{ __("Website ID") }}</label>
                                    <input type="text" name="vnpay_website_id" id="vnpay_website_id" class="form-control" value="{{$vnpay_website_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="vnpay_server_key" class="mr-3">{{ __("Server Key") }}</label>
                                    <input type="text" name="vnpay_server_key" id="vnpay_server_key" class="form-control" value="{{$vnpay_server_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif


                    @if ( (strtolower($opt->code) == 'mvodafone') )
                    <div class="mt-2" id="mvodafone_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="mvodafone_client_id" class="mr-3">{{ __("Client ID") }}</label>
                                    <input type="text" name="mvodafone_client_id" id="mvodafone_client_id" class="form-control" value="{{$client_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="mvodafone_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="mvodafone_secret_key" id="mvodafone_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'flutterwave') )
                    <div class="mt-2" id="flutterwave_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="flutterwave_client_id" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="text" name="flutterwave_client_id" id="flutterwave_client_id" class="form-control" value="{{$client_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="flutterwave_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="flutterwave_enc_key" class="mr-3">{{ __("Encryption Key") }}</label>
                                    <input type="password" name="flutterwave_enc_key" id="flutterwave_enc_key" class="form-control" value="{{$enc_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'payu') )
                    <div class="mt-2" id="payu_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payu_merchant_key" class="mr-3">{{ __("Merchant Key") }}</label>
                                    <input type="text" name="payu_merchant_key" id="payu_merchant_key" class="form-control" value="{{$merchant_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payu_merchant_salt_v1" class="mr-3">{{ __("Merchant Salt V1") }}</label>
                                    <input type="password" name="payu_merchant_salt_v1" id="payu_merchant_salt_v1" class="form-control" value="{{$merchant_salt_v1}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="pay_merchant_salt_v2" class="mr-3">{{ __("Merchant Salt V2") }}</label>
                                    <input type="password" name="payu_merchant_salt_v2" id="payu_merchant_salt_v2" class="form-control" value="{{$merchant_salt_v2}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'braintree') )
                    <div class="mt-2" id="braintree_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="braintree_merchant_id" class="mr-3">{{ __("Merchant ID") }}</label>
                                    <input type="text" name="braintree_merchant_id" id="braintree_merchant_id" class="form-control" value="{{$merchant_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="braintree_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="text" name="braintree_public_key" id="braintree_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="braintree_private_key" class="mr-3">{{ __("Private Key") }}</label>
                                    <input type="text" name="braintree_private_key" id="braintree_private_key" class="form-control" value="{{$private_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'payphone'))
                    <div class="mt-2" id="payphone_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payphone_id" class="mr-3">{{ __("ID") }}</label>
                                    <input type="text" name="payphone_id" id="payphone_id" class="form-control" value="{{$id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payphone_client_id" class="mr-3">{{ __("Client ID") }}</label>
                                    <input type="text" name="payphone_client_id" id="payphone_client_id" class="form-control" value="{{$client_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payphone_token" class="mr-3">{{ __("Token") }}</label>
                                    <input type="text" name="payphone_token" id="payphone_token" class="form-control" value="{{$token}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'windcave'))
                    <div class="mt-2" id="windcave_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="windcave_id" class="mr-3">{{ __("User ID") }}</label>
                                    <input type="text" name="windcave_id" id="windcave_id" class="form-control" value="{{$app_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="windcave_key" class="mr-3">{{ __("User Key") }}</label>
                                    <input type="text" name="windcave_key" id="windcave_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'paytech'))
                    <div class="mt-2" id="paytech_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paytech_key" class="mr-3">{{ __("Api Key") }}</label>
                                    <input type="text" name="paytech_key" id="windcave_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paytech_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="text" name="paytech_secret_key" id="paytech_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'mycash'))
                    <div class="mt-2" id="mycash_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="mycash_api_key" class="mr-3">{{ __("Api Key") }}</label>
                                    <input type="text" name="mycash_api_key" id="mycash_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="mycash_username" class="mr-3">{{ __("Username") }}</label>
                                    <input type="text" name="mycash_username" id="mycash_username" class="form-control" value="{{$username}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="mycash_password" class="mr-3">{{ __("Password") }}</label>
                                    <input type="password" name="mycash_password" id="mycash_password" class="form-control" value="{{$password}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="mycash_merchant_phone" class="mr-3">{{ __("Mobile Number") }}</label>
                                    <input type="text" name="mycash_merchant_phone" id="mycash_merchant_phone" class="form-control" value="{{$merchant_phone}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'stripe_oxxo') )
                    <div class="mt-2" id="stripe_oxxo_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_oxxo_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="stripe_oxxo_secret_key" id="stripe_oxxo_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_oxxo_publishable_key" class="mr-3">{{ __("Publishable Key") }}</label>
                                    <input type="password" name="stripe_oxxo_publishable_key" id="stripe_oxxo_publishable_key" class="form-control" value="{{$publishable_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'stripe_ideal') )
                    <div class="mt-2" id="stripe_ideal_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_ideal_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="stripe_ideal_secret_key" id="stripe_ideal_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_ideal_publishable_key" class="mr-3">{{ __("Publishable Key") }}</label>
                                    <input type="password" name="stripe_ideal_publishable_key" id="stripe_ideal_publishable_key" class="form-control" value="{{$publishable_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'offline_manual') )
                    <div class="mt-2" id="offline_manual_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="manule_payment_title" class="mr-3">{{ __("Manual payment title") }}</label>
                                    <input type="text" name="manule_payment_title" id="manule_payment_title" class="form-control" value="{{$manule_payment_title}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'userede') )
                    <div class="mt-2" id="userede_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="userede_Rede_PV" class="mr-3">{{ __("Rede PV") }}</label>
                                    <input type="text" name="userede_Rede_PV" id="userede_Rede_PV" class="form-control" value="{{$userede_Rede_PV}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="userede_Rede_token" class="mr-3">{{ __("Rede token") }}</label>
                                    <input type="text" name="userede_Rede_token" id="userede_Rede_token" class="form-control" value="{{$userede_Rede_token}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'openpay') )
                    <div class="mt-2" id="openpay_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                    <h6 class="mt-3">
                        <span>{{ __('Webhook Url') }} : </span>
                        <a href="javascript:;" class="webhook_url"><span id="pwd_spn" class="password-span">{{route('payment.webhook.opnepay')}}</span></a>
                    </h6>
                    <div class="row">
                            <div class="col-12">
                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label for="openpay_verification_key" class="mr-3">{{ __("Webhook Verification") }}</label>
                                        <input type="text" name="openpay_verification_key" id="openpay_verification_key" class="form-control" value="{{$openpay_verification_key}}" >
                                    </div>
                                </div>

                                <div class="form-group mb-2">
                                    <label for="openpay_merchant_id" class="mr-3">{{ __("Merchant Id") }}</label>
                                    <input type="text" name="openpay_merchant_id" id="openpay_merchant_id" class="form-control" value="{{$openpay_merchant_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="openpay_private_key" class="mr-3">{{ __("Private Key") }}</label>
                                    <input type="text" name="openpay_private_key" id="openpay_private_key" class="form-control" value="{{$openpay_private_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="openpay_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="text" name="openpay_public_key" id="openpay_public_key" class="form-control" value="{{$openpay_public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'dpo') )
                    <div class="mt-2" id="dpo_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="company_token" class="mr-3">{{ __("Company Token") }}</label>
                                    <input type="text" name="company_token" id="company_token" class="form-control" value="{{$company_token}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="service_type" class="mr-3">{{ __("Service Type") }}</label>
                                    <input type="text" name="service_type" id="service_type" class="form-control" value="{{$service_type}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'upay') )
                    <div class="mt-2" id="upay_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="company_token" class="mr-3">{{ __("UIDD") }}</label>
                                    <input type="text" name="uuid_key" id="uuid_key" class="form-control" value="{{$uuid_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="service_type" class="mr-3">{{ __("AES Key") }}</label>
                                    <input type="text" name="aes_key" id="aes_key" class="form-control" value="{{$aes_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'conekta') )
                    <div class="mt-2" id="conekta_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="company_token" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="text" name="conekta_public_key" id="conekta_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="service_type" class="mr-3">{{ __("Private Key") }}</label>
                                    <input type="password" name="conekta_private_key" id="conekta_private_key" class="form-control" value="{{$private_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'telr') )
                    <div class="mt-2" id="telr_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="telr_merchant_id" class="mr-3">{{ __("Store ID") }}</label>
                                    <input type="text" name="telr_merchant_id" id="telr_merchant_id" class="form-control" value="{{$merchant_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="telr_api_key" class="mr-3">{{ __("Api Key") }}</label>
                                    <input type="text" name="telr_api_key" id="telr_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'khalti') )
                    <div class="mt-2" id="khalti_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="khalti_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="text" name="khalti_public_key" id="khalti_public_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="khalti_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="khalti_secret_key" id="khalti_secret_key" class="form-control" value="{{$api_secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endforeach
        </div>
    </form>
@if(count($payoutOption) > 0)
    <form method="POST" id="payout_option_form" action="{{route('payoutOption.payoutUpdateAll')}}">
        @csrf
        @method('POST')
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-title-box  d-flex align-items-center justify-content-between">
                    <h4 class="page-title">{{ __("Payout Options") }}</h4>
                    <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($payoutOption as $key => $opt)
            <div class="col-6 col-md-3 col-xl-2 mb-3">

                <input type="hidden" name="method_id[]" id="{{$opt->id}}" value="{{$opt->id}}">
                <input type="hidden" name="method_name[]" id="{{$opt->code}}" value="{{$opt->code}}">

                <?php
                $creds = json_decode($opt->credentials);
                $api_key = (isset($creds->api_key)) ? $creds->api_key : '';
                $secret_key = (isset($creds->secret_key)) ? $creds->secret_key : '';
                $multiplier = (isset($creds->multiplier)) ? $creds->multiplier : '';
                $publishable_key = (isset($creds->publishable_key)) ? $creds->publishable_key : '';
                $client_id = (isset($creds->client_id)) ? $creds->client_id : '';
                ?>

                <div class="card-box h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0"> <span class="alPaymentImage" style="height:24px;width:24px;display:inline-block;"> <img style="width:100%;" src="{{asset('paymentsLogo/'.$opt->code.'.png')}}" alt=""></span>  {{$opt->title}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo">
                                <label for="" class="mr-0 d-block">{{ __("Enable") }}</label>
                                <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->code}}" data-plugin="switchery" name="active[{{$opt->id}}]" class="chk_box payout_all_select" data-color="#43bee1" @if($opt->status == 1) checked @endif>
                            </div>
                        </div>
                        @if ( (strtolower($opt->code) != 'cash') )
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo">
                                <label for="" class="mr-0 d-block">{{ __('Sandbox') }}</label>
                                <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->code}}" data-plugin="switchery" name="sandbox[{{$opt->id}}]" class="chk_box" data-color="#43bee1" @if($opt->test_mode == 1) checked @endif>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if ( (strtolower($opt->code) == 'stripe') )
                    <div class="mt-2" id="stripe_payout_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_payout_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="stripe_payout_secret_key" id="stripe_payout_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_payout_publishable_key" class="mr-3">{{ __("Publishable Key") }}</label>
                                    <input type="password" name="stripe_payout_publishable_key" id="stripe_payout_publishable_key" class="form-control" value="{{$publishable_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_payout_client_id" class="mr-3">{{ __("Client ID") }}</label>
                                    <input type="password" name="stripe_payout_client_id" id="stripe_payout_client_id" class="form-control" value="{{$client_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'pagarme') )
                    <div class="mt-2" id="pagarme_payout_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="pagarme_payout_api_key" class="mr-3">{{ __("API Key") }}</label>
                                    <input type="text" name="pagarme_payout_api_key" id="pagarme_payout_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="pagarme_payout_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="pagarme_payout_secret_key" id="pagarme_payout_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="pagarme_payout_multiplier" class="mr-3">{{ __("Multiplier") }}</label>
                                    <input type="number" name="pagarme_payout_multiplier" id="pagarme_payout_multiplier" class="form-control" value="{{$multiplier}}" step="0.01" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif


                    @if ( (strtolower($opt->code) == 'razorpay') )
                    <div class="2" id="razorpay_payout_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="razorpay_payout_api_key" class="mr-3">{{ __("API Key") }}</label>
                                    <input type="text" name="razorpay_payout_api_key" id="razorpay_payout_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="razorpay_payout_secret_key" class="mr-3">{{ __("API Secret Key") }}</label>
                                    <input type="text" name="razorpay_payout_secret_key" id="razorpay_payout_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>

                            <h6 class="mt-3">
                                <span>{{ __('Webhook Url') }} : </span>
                                <a href="javascript:;" class="webhook_url"><span id="pwd_spn" class="password-span">{{route('payment.razorpay.payout.notify')}}</span></a>
                            </h6>
                        </div>
                    </div>
                    @endif


                </div>
            </div>
            @endforeach
        </div>
    </form>
@endif

</div>

@endsection

@section('script')
<script type="text/javascript">
    $('.applyVendor').click(function() {
        $('#applyVendorModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('.all_select').change(function() {
        var id = $(this).data('id');
        // console.log(id);
        var title = $(this).data('title');
        var code = title.toLowerCase();
        if ($(this).is(":checked")) {
            $("#" + code + "_fields_wrapper").show();
            $("#" + code + "_fields_wrapper").find('input').attr('required', true);
        } else {
            $("#" + code + "_fields_wrapper").hide();
            $("#" + code + "_fields_wrapper").find('input').removeAttr('required');
        }

        // if( title.toLowerCase() == 'stripe' ){
        //     if($(this).is(":checked")){
        //         $("#stripe_fields_wrapper").show();
        //         $("#stripe_fields_wrapper").find('input').attr('required', true);
        //     }
        //     else{
        //         $("#stripe_fields_wrapper").hide();
        //         $("#stripe_fields_wrapper").find('input').removeAttr('required');
        //     }
        // }
        // if( title.toLowerCase() == 'paypal' ){
        //     if($(this).is(":checked")){
        //         $("#paypal_fields_wrapper").show();
        //         $("#paypal_fields_wrapper").find('input').attr('required', true);
        //     }
        //     else{
        //         $("#paypal_fields_wrapper").hide();
        //         $("#paypal_fields_wrapper").find('input').removeAttr('required');
        //     }
        // }
        // if( title.toLowerCase() == 'paystack' ){
        //     if($(this).is(":checked")){
        //         $("#paystack_fields_wrapper").show();
        //         $("#paystack_fields_wrapper").find('input').attr('required', true);
        //     }
        //     else{
        //         $("#paystack_fields_wrapper").hide();
        //         $("#paystack_fields_wrapper").find('input').removeAttr('required');
        //     }
        // }
        // if( title.toLowerCase() == 'payfast' ){
        //     if($(this).is(":checked")){
        //         $("#payfast_fields_wrapper").show();
        //         $("#payfast_fields_wrapper").find('input').attr('required', true);
        //     }
        //     else{
        //         $("#payfast_fields_wrapper").hide();
        //         $("#payfast_fields_wrapper").find('input').removeAttr('required');
        //     }
        // }

        // $('#form_'+id).submit();

        //$('.vendorRow').toggle();
    });

    $('.payout_all_select').change(function() {
        var id = $(this).data('id');
        // console.log(id);
        var title = $(this).data('title');
        var code = title.toLowerCase();
        if ($(this).is(":checked")) {
            $("#" + code + "_payout_fields_wrapper").show();
            $("#" + code + "_payout_fields_wrapper").find('input').attr('required', true);
        } else {
            $("#" + code + "_payout_fields_wrapper").hide();
            $("#" + code + "_payout_fields_wrapper").find('input').removeAttr('required');
        }
    });
</script>
@endsection
