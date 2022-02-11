@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
$urlImg = $clientData->logo['image_fit'].'150/60'.$clientData->logo['image_path'];
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
$pages = \App\Models\Page::with(['translations' => function($q) {$q->where('language_id', session()->get('customerLanguage') ??1);}])->whereHas('translations', function($q) {$q->where(['is_published' => 1, 'language_id' => session()->get('customerLanguage') ??1]);})->orderBy('order_by','ASC')->get();

@endphp
<style>
	.top-header.site-topbar.al_custom_head {
        background-color: #fff;
    }
    .al_custom_head_map_box{
        background: rgb(255, 255, 255);
        border-radius: 0.8rem;
        box-shadow: rgb(28 28 28 / 8%) 0px 2px 8px;
        border: 1px solid rgb(232, 232, 232);
    }
    .al_custom_head.site-topbar .location-bar i{
        color: #000 !important;
    }
    .al_custom_head.site-topbar .location-bar h2 {
        color: #000;
        font-size: 14px !important;
        width: 100%;
        padding-right: 40px;
    }
    .al_custom_head.site-topbar .location-bar span {
        color: #000 !important;
        border-right: 1px solid #777;
    }

    .al_custom_head .onhover-dropdown span:before,
    .al_custom_head.top-header .header-dropdown li a,
    .al_custom_head.top-header .header-dropdown li{
        color: #777;
    }
    .al_custom_head.site-topbar .location-bar h2:after {
        color: #000;
        margin-left: 0;
        position: absolute;
        right: 0;
    }
    .al_custom_head .homepage-address {
        position: relative;
        width: calc(100% - 146px);
    }
    .al_custom_head.site-topbar .location-bar h2:after {
        position: absolute;
        border-width: 7px;
        border-style: solid;
        border-color: #000 transparent transparent;
        right: 20px;
        top: 15px;
        color: #000;
        margin-left: 0;
        content: "";
    }
    .location-bar.d-inline-flex.align-items-center.position-relative {
    width: 100%;
    border-right: 1px solid;
}
.al_custom_head_map_box button.btn {
    padding-right: 0;
}
h2.homepage-address {
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    font-size: 14px;
    line-height: 50px;
    margin: 0;
    cursor: pointer!important;
}

</style>
<div class="top-header site-topbar al_custom_head">
    <div class="container">
        <div class="row d-flex align-items-center justify-content-between">
            <div class="col-6 d-flex align-items-center justify-content-start">
                <a class="navbar-brand mr-3" style="min-width:150px;" href="{{ route('userHome') }}"><img class="img-fluid" alt="" src="{{$urlImg}}" ></a>
                <div class=" al_custom_head_map_box p-2 d-inline-flex align-items-center justify-content-start">
                    @if( (Session::get('preferences')))
                        @if( (isset(Session::get('preferences')->is_hyperlocal)) && (Session::get('preferences')->is_hyperlocal == 1) )
                            <div class=" col location-bar d-inline-flex align-items-center position-relative p-0" href="#edit-address" data-toggle="modal">
                                    <i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
                                    <h2 class="homepage-address"><span data-placement="top" data-toggle="tooltip" title="{{session('selectedAddress')}}">{{session('selectedAddress')}}</span></h2>
                            </div>
                            <div class="col d-inline-flex align-items-center justify-content-start p-0">
                                <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    @php $searchPlaceholder=getNomenclatureName('Search', true); $searchPlaceholder=($searchPlaceholder==='Search product, vendor, item') ? __('Search product, vendor, item') : $searchPlaceholder; @endphp
                                <input class="form-control border-0 typeahead" type="search" placeholder="{{$searchPlaceholder}}" id="main_search_box" autocomplete="off">
                            </div>


                        @endif
                    @endif
                </div>
                <!-- <div class="radius-bar d-xl-inline al_custom_search mr-2">

                           <div class="list-box style-4" style="display:none;" id="search_box_main_div"> </div>
                        </div> -->
            </div>

            <div class="col-4 d-none text-right pr-0">
                <div class="icon-nav">
                    <ul>
                        <li class="d-inline-block d-lg-none"><div class="toggle-nav p-0 d-inline-block"><i class="fa fa-bars sidebar-bar"></i></div></li>
                    </ul>
                </div>
            </div>
            <div class="col-6 text-right d-sm-inline d-none">
            @php
            $applocale = 'en';
            if(session()->has('applocale')){
                $applocale = session()->get('applocale');
            }
            @endphp
                <ul class="header-dropdown">
                    @if($client_preference_detail->header_quick_link == 1)
                    <li class="onhover-dropdown quick-links quick-links">

                        <span class="quick-links ml-1 align-middle">{{ __('Quick Links') }}</span>
                        </a>
                        <ul class="onhover-show-div">


                            @foreach($pages as $page)
                                @if(isset($page->primary->type_of_form) && ($page->primary->type_of_form == 2))
                                @if(isset($last_mile_common_set) && $last_mile_common_set != false)
                                <li>
                                    <a href="{{route('extrapage',['slug' => $page->slug])}}">
                                        @if(isset($page->translations) && $page->translations->first()->title != null)
                                        {{ $page->translations->first()->title ?? ''}}
                                        @else
                                        {{ $page->primary->title ?? ''}}
                                        @endif
                                    </a>
                                </li>
                                @endif
                                @else
                                <li>
                                    <a href="{{route('extrapage',['slug' => $page->slug])}}" target="_blank">
                                        @if(isset($page->translations) && $page->translations->first()->title != null)
                                        {{ $page->translations->first()->title ?? ''}}
                                        @else
                                        {{ $page->primary->title ?? ''}}
                                        @endif
                                    </a>
                                </li>
                                @endif
                                @endforeach
                        </ul>
                    </li>
                    @endif
                    <li class="onhover-dropdown change-language">
                        <a href="javascript:void(0)">{{$applocale}}
                        <span class="icon-ic_lang align-middle"></span>
                        <span class="language ml-1 align-middle">{{ __("language") }}</span>
                        </a>
                        <ul class="onhover-show-div">
                            @foreach($languageList as $key => $listl)
                                <li class="{{$applocale ==  $listl->language->sort_code ?  'active' : ''}}">
                                    <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="onhover-dropdown change-currency">
                        <a href="javascript:void(0)">{{session()->get('iso_code')}}
                        <span class="icon-ic_currency align-middle"></span>
                        <span class="currency ml-1 align-middle">{{ __("currency") }}</span>
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

    <div class="mobile-menu main-menu d-block d-sm-none">
        <div class="menu-right">
            <ul class="header-dropdown icon-nav">
                <li class="onhover-div mobile-setting">
                    <div><i class="ti-settings"></i></div>
                    <div class="show-div setting">
                        <h6>language</h6>
                        <ul>
                            <li><a href="#">english</a></li>
                            <li><a href="#">french</a></li>
                        </ul>
                        <h6>currency</h6>
                        <ul class="list-inline">
                            @foreach($currencyList as $key => $listc)
                                <li class="{{session()->get('iso_code') ==  $listc->currency->iso_code ?  'active' : ''}}">
                                    <a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr " currSymbol="{{$listc->currency->symbol}}">{{$listc->currency->iso_code}}</a>
                                </li>
                            @endforeach
                        </ul>
                        <h6>Change Theme</h6>
                        @if($client_preference_detail->show_dark_mode == 1)
                        <ul class="list-inline">
                            <li><a class="theme-layout-version" href="javascript:void(0)">Dark</a></li>
                        </ul>
                        @endif
                    </div>
                </li>

                <li class="onhover-dropdown mobile-account  d-inline d-sm-none"> <i class="fa fa-user" aria-hidden="true"></i>
                    {{__('My Account')}}
                    <ul class="onhover-show-div">
                        <li>
                            <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a>
                        </li>

                    </ul>
                </li>
                @if($client_preference_detail->show_wishlist == 1)
                <li class="mobile-wishlist d-inline d-sm-none">
                    <a href="{{route('user.wishlists')}}">
                        <i class="fa fa-heart" aria-hidden="true"></i>
                    </a>
                </li>
                @endif
                <li class="onhover-div mobile-search">
                    <a href="javascript:void(0);" id="mobile_search_box_btn"><i class="ti-search"></i></a>
                    <div id="search-overlay" class="search-overlay">
                        <div> <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
                            <div class="overlay-content">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <form>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Search a Product">
                                                </div>
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                @if($client_preference_detail->cart_enable == 1)
                <li class="onhover-div mobile-cart">
                    <a href="{{route('showCart')}}" style="position: relative">
                        <i class="ti-shopping-cart"></i>
                        <span class="cart_qty_cls" style="display:none"></span>
                    </a>
                    {{--<span class="cart_qty_cls" style="display:none"></span>--}}
                    <ul class="show-div shopping-cart">
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>

</div>


