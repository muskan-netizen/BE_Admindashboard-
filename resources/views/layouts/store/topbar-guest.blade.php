@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();

$urlImg = $clientData->logo['proxy_url'].'200/80'.$clientData->logo['image_path'];
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp
<div class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
            @if($client_preference_detail->show_contact_us == 1)
                <div class="header-contact">
                    <ul>
                        <li>{{session('client_config') ? session('client_config')->company_name : ''}}</li>
                        <!-- <li><i class="fa fa-phone" aria-hidden="true"></i>{{__('Call Us')}}: {{session('client_config') ? session('client_config')->phone_number : ''}}</li> -->
                    </ul>
                </div>
            @endif
            </div>
            <div class="col-lg-5 text-right">
                <ul class="header-dropdown">
                    <li class="onhover-dropdown change-language">
                        <a href="javascript:void(0)">{{session()->get('locale')}} 
                            <img src="{{asset('front-assets/images/icon/translation.png')}}" class="img-fluid">
                        </a>
                        <ul class="onhover-show-div">
                            @foreach($languageList as $key => $listl)
                                <li class="{{session()->get('locale') ==  $listl->language->sort_code ?  'active' : ''}}">
                                    <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="onhover-dropdown change-currency">
                        <a href="javascript:void(0)">{{session()->get('iso_code')}}
                            <img src="{{asset('front-assets/images/icon/exchange.png')}}" class="img-fluid">
                        </a>
                        <ul class="onhover-show-div">
                            @foreach($currencyList as $key => $listc)
                            <li class="{{session()->get('iso_code') ==  $listc->currency->iso_code ?  'active' : ''}}">
                                <a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr" currSymbol="{{$listc->currency->symbol}}">
                                    {{$listc->currency->iso_code}}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="onhover-dropdown mobile-account">
                        <i class="fa fa-user" aria-hidden="true"></i>{{__('Account')}}
                        <ul class="onhover-show-div">
                            <li>
                                <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a>
                            </li>
                            <li>
                                <a href="{{route('customer.register')}}" data-lng="es">{{__('Register')}}</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>