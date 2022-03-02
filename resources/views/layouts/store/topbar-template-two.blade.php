@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
$urlImg = $clientData->logo['image_fit'].'150/60'.$clientData->logo['image_path'];
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
$pages = \App\Models\Page::with(['translations' => function($q) {$q->where('language_id', session()->get('customerLanguage') ??1);}])->whereHas('translations', function($q) {$q->where(['is_published' => 1, 'language_id' => session()->get('customerLanguage') ??1]);})->orderBy('order_by','ASC')->get();
$preference = $client_preference_detail;
@endphp
@php
    $applocale = 'en';
    if(session()->has('applocale')){
        $applocale = session()->get('applocale');
    }
    @endphp
<div class="top-header site-topbar al_custom_head">
    <nav class="navbar navbar-expand-lg p-0 ">
        <div class="container ">
            <div class="row d-flex align-items-center justify-content-between w-100">

                <div class="col-lg-6 d-md-flex align-items-center justify-content-start"  data-aos="zoom-in">

                    <a class="navbar-brand mr-xl-3 mr-0" style="min-width:150px;" href="{{ route('userHome') }}"><img class="img-fluid" alt="" src="{{$urlImg}}" height="50"></a>
                    <div class="al_custom_head_map_box px-2 py-1 d-md-inline-flex  d-flex align-items-center justify-content-start">
                        @if(isset($preference))
                        @if(($preference->is_hyperlocal) && ($preference->is_hyperlocal == 1))
                                <div class=" col location-bar d-inline-flex align-items-center position-relative p-0 mr-2" href="#edit-address" data-toggle="modal">
                                        <i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
                                        <h2 class="homepage-address"><span data-placement="top" data-toggle="tooltip" title="{{session('selectedAddress')}}">{{session('selectedAddress')}}</span></h2>
                                </div>
                            @endif
                        @endif
                        <div class="col d-inline-flex align-items-center justify-content-start p-0 position-relative">
                            <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                @php $searchPlaceholder=getNomenclatureName('Search', true); $searchPlaceholder=($searchPlaceholder==='Search product, vendor, item') ? __('Search product, vendor, item') : $searchPlaceholder; @endphp
                            <input class="form-control border-0 typeahead" type="search" placeholder="{{$searchPlaceholder}}" id="main_search_box" autocomplete="off">
                            <div class="list-box style-4" style="display:none;" id="search_box_main_div"> </div>
                        </div>

                    </div>
                </div>

                <div class="col-2 col-lg-6 text-right ml-auto al_z_index p-0" data-aos="zoom-in">


                    <ul class="header-dropdown ml-auto">
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
                        @if(count($languageList) > 1)
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
                        @endif

                        @if(count($currencyList) > 1)
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
                        @endif

                        <li class="onhover-dropdown mobile-account"> <i class="fa fa-user" aria-hidden="true"></i>
                            {{__('My Account')}}
                            <ul class="onhover-show-div">
                                @if(Auth::user())
                                    @if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1)
                                        <li>
                                            <a href="{{route('client.dashboard')}}" data-lng="en">{{__('Control Panel')}}</a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{route('user.profile')}}" data-lng="en">{{__('Profile')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('user.logout')}}" data-lng="es">{{__('Logout')}}</a>
                                    </li>
                                @else
                                <li>
                                    <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('customer.register')}}" data-lng="es">{{__('Register')}}</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>



            </div>
        </div>
    </nav>


    <div class="mobile-menu main-menu position-fixed d-none">
        <div class="menu-right_">
            <ul class="header-dropdown icon-nav d-flex justify-content-around">
                <li class="onhover-div mobile-setting">
                    <div data-toggle="modal" data-target="#setting_modal"><i class="ti-settings"></i></div>
                </li>

                <li class="onhover-dropdown mobile-account  d-inline d-sm-none"> <i class="fa fa-user" aria-hidden="true"></i>
                    {{__('My Account')}}
                    <ul class="onhover-show-div">
                        @if(Auth::user())
                            @if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1)
                                <li>
                                    <a href="{{route('client.dashboard')}}" data-lng="en">{{__('Control Panel')}}</a>
                                </li>
                            @endif
                            <li>
                                <a href="{{route('user.profile')}}" data-lng="en">{{__('Profile')}}</a>
                            </li>
                            <li>
                                <a href="{{route('user.logout')}}" data-lng="es">{{__('Logout')}}</a>
                            </li>
                        @else
                        <li>
                            <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a>
                        </li>
                        <li>
                            <a href="{{route('customer.register')}}" data-lng="es">{{__('Register')}}</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @if($client_preference_detail->show_wishlist == 1)
                <li class="mobile-wishlist d-inline d-sm-none">
                    <a href="{{route('user.wishlists')}}">
                        <i class="fa fa-heart" aria-hidden="true"></i>
                    </a>
                </li>
                @endif
                <li class="onhover-div al_mobile-search">
                    <a href="javascript:void(0);" id="mobile_search_box_btn" onClick="$('.search-overlay').css('display','block');"><i class="ti-search"></i></a>
                    <div id="search-overlay" class="search-overlay">
                        <div> <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
                        <div class="overlay-content w-100">
                            <form>
                                <div class="form-group m-0">
                                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Search a Product">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </form>
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
                    <ul class="show-div shopping-cart">
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<div class="al_mobile_menu al_new_mobile_header">
                <a class="al_toggle-menu" href="#">
                    <i></i>
                    <i></i>
                    <i></i>
                </a>
                <div class="al_menu-drawer" id="navbarsfoodTemplate">
                    <ul class="header-dropdown ml-auto">
                        <li class="onhover-dropdown_al mobile-account_al">
                            <ul class="onhover-show-div">
                                @if(Auth::user())
                                    @if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1)
                                    <li>
                                        <a href="{{route('client.dashboard')}}" data-lng="en">{{__('Control Panel')}}</a>
                                    </li>
                                    @endif
                                    <li>
                                        <a href="{{route('user.profile')}}" data-lng="en">{{__('Profile')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('user.logout')}}" data-lng="es">{{__('Logout')}}</a>
                                    </li>
                                @else
                                <li>
                                    <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('customer.register')}}" data-lng="es">{{__('Register')}}</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @if($client_preference_detail->show_wishlist == 1)
                        <li class="onhover-dropdown_al mobile-wishlist_al">
                            <a href="{{route('user.wishlists')}}">
                                Wishlists
                            </a>
                        </li>
                        @endif

                        @if($client_preference_detail->cart_enable == 1)
                        <li class="onhover-dropdown_al onhover-div mobile-cart">
                            <a href="{{route('showCart')}}" style="position: relative">
                                Viewcart
                                <span class="cart_qty_cls" style="display:none"></span>
                            </a>
                            <ul class="show-div shopping-cart"></ul>
                        </li>
                        @endif

                        @if($client_preference_detail->header_quick_link == 1)
                        @foreach($pages as $page)
                        @if(isset($page->primary->type_of_form) && ($page->primary->type_of_form == 2))
                        @if(isset($last_mile_common_set) && $last_mile_common_set != false)
                        <li class="onhover-dropdown_al">
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
                        <li class="onhover-dropdown_al">
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
                    </ul>
                </div>
            </div>


