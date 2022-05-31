@php
$clientData = \App\Models\Client::select('id', 'logo')
    ->where('id', '>', 0)
    ->first();
$urlImg = $clientData ? $clientData->logo['original'] : ' ';
$languageList = \App\Models\ClientLanguage::with('language')
    ->where('is_active', 1)
    ->orderBy('is_primary', 'desc')
    ->get();
$currencyList = \App\Models\ClientCurrency::with('currency')
    ->orderBy('is_primary', 'desc')
    ->get();
$pages = \App\Models\Page::with([
    'translations' => function ($q) {
        $q->where('language_id', session()->get('customerLanguage') ?? 1);
    },
])
    ->whereHas('translations', function ($q) {
        $q->where(['is_published' => 1, 'language_id' => session()->get('customerLanguage') ?? 1]);
    })
    ->orderBy('order_by', 'ASC')
    ->get();
@endphp
@section('css')

@endsection
@php
    $applocale = 'en';
    if(session()->has('applocale')){
        $applocale = session()->get('applocale');
    }
    @endphp
@if((\Request::route()->getName() != 'customer.login') && (\Request::route()->getName() != 'customer.register') && (\Request::route()->getName() != 'user.verify'))
<header id="al_four_design" class="site-header @if ($client_preference_detail->business_type == 'taxi') taxi-header @endif">

    <!-- Start Cab Booking Header From Here -->
    <div class="cab-booking-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-9 col-md-10 top-header bg-transparent">
                    <ul class="header-dropdown d-flex align-items-center justify-content-md-end justify-content-center">
                        @if ($client_preference_detail->header_quick_link == 1)
                            <li class="onhover-dropdown quick-links quick-links">

                                <span class="quick-links ml-1 align-middle">{{ __('Quick Links') }}</span>
                                </a>
                                <ul class="onhover-show-div">
                                    @foreach ($pages as $page)
                                        @if (isset($page->primary->type_of_form) && $page->primary->type_of_form == 2)
                                            @if (isset($last_mile_common_set) && $last_mile_common_set != false)
                                                <li>
                                                    <a href="{{ route('extrapage', ['slug' => $page->slug]) }}">
                                                        @if (isset($page->translations) && $page->translations->first()->title != null)
                                                            {{ $page->translations->first()->title ?? '' }}
                                                        @else
                                                            {{ $page->primary->title ?? '' }}
                                                        @endif
                                                    </a>
                                                </li>
                                            @endif
                                        @else
                                            <li>
                                                <a href="{{ route('extrapage', ['slug' => $page->slug]) }}"
                                                    target="_blank">
                                                    @if (isset($page->translations) && $page->translations->first()->title != null)
                                                        {{ $page->translations->first()->title ?? '' }}
                                                    @else
                                                        {{ $page->primary->title ?? '' }}
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
                            <a href="javascript:void(0)">{{ session()->get('locale') }}
                                <span class="icon-ic_lang align-middle"></span>
                                <span class="language ml-1 align-middle">{{ __('language') }}</span>
                            </a>
                            <ul class="onhover-show-div">
                                @foreach ($languageList as $key => $listl)
                                    <li
                                        class="{{ session()->get('locale') == $listl->language->sort_code ? 'active' : '' }}">
                                        <a href="javascript:void(0)" class="customerLang"
                                            langId="{{ $listl->language_id }}">{{ $listl->language->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                        @if(count($currencyList) > 1)
                        <li class="onhover-dropdown change-currency">
                            <a href="javascript:void(0)">{{ session()->get('iso_code') }}
                                <span class="icon-ic_currency align-middle"></span>
                                <span class="currency ml-1 align-middle">{{ __('currency') }}</span>
                            </a>
                            <ul class="onhover-show-div">
                                @foreach ($currencyList as $key => $listc)
                                    <li
                                        class="{{ session()->get('iso_code') == $listc->currency->iso_code ? 'active' : '' }}">
                                        <a href="javascript:void(0)" currId="{{ $listc->currency_id }}"
                                            class="customerCurr" currSymbol="{{ $listc->currency->symbol }}">
                                            {{ $listc->currency->iso_code }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                        @if (Auth::guest())
                            <li class="onhover-dropdown mobile-account d-block">
                                <i class="fa fa-user mr-1" aria-hidden="true"></i>{{ __('Account') }}
                                <ul class="onhover-show-div">
                                    <li>
                                        <a href="{{ route('customer.login') }}" data-lng="en">{{ __('Login') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('customer.register') }}"
                                            data-lng="es">{{ __('Register') }}</a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="onhover-dropdown mobile-account d-block">
                                <i class="fa fa-user mr-1" aria-hidden="true"></i>{{ __('Account') }}
                                <ul class="onhover-show-div">
                                    @if (Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1)
                                        <li>
                                            <a href="{{ route('client.dashboard') }}"
                                                data-lng="en">{{ __('Control Panel') }}</a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ route('user.profile') }}" data-lng="en">{{ __('Profile') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('user.logout') }}" data-lng="es">{{ __('Logout') }}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
         </div>
      </div>
   </div>
   <!-- End Cab Booking Header From Here -->

    <div class="al_four_custom_head">
        <nav class="navbar navbar-expand-lg p-0 ">
            <div class="container-fluid d-flex justify-content-center">
                <div class="col-xl-10 col-lg-12">
                    <div class="row d-flex align-items-center justify-content-between">
                        <div class="col-lg-2">
                            <div class="logo_area d-none d-lg-block">
                                <a class="navbar-brand m-0" href="{{ route('userHome') }}"><img alt="" class="w-100" src="{{$urlImg}}"></a>
                            </div>
                            <div class="col-12 d-block d-lg-none p-0">
                                <div class="row no-gutters">
                                    <div class="col-2">
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
                                                        {{__('Wishlists')}}
                                                        </a>
                                                    </li>
                                                    @endif

                                                    @if($client_preference_detail->cart_enable == 1)
                                                    <li class="onhover-dropdown_al onhover-div mobile-cart">
                                                        <a href="{{route('showCart')}}" style="position: relative">
                                                        {{__('Viewcart')}}
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
                                                                    <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}@if($listl->language->id != 1)    
                                                                        ({{$listl->language->nativeName}})
                                                                        @endif </a>
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
                                    </div>
                                    <div class="col-8">
                                        <a class="navbar-brand m-0" href="{{ route('userHome') }}"><img alt="" class="w-100" src="{{$urlImg}}"></a>
                                    </div>

                                    <div class="col-2 d-flex justify-content-end align-items-center">
                                        <div class="onhover-div pl-0 ml-xl-3 ml-lg-1 shake-effect d-block d-md-none">
                                            @if($client_preference_detail) @if($client_preference_detail->cart_enable==1)
                                            <a class="addToCardBtn d-flex align-items-center " href="{{route('showCart')}}">
                                                <i class="fa fa-shopping-cart mr-1 " aria-hidden="true"></i>
                                                <span id="cart_qty_span"></span>
                                            </a> @endif @endif
                                            <script type="text/template" id="header_cart_template"> <% _.each(cart_details.products, function(product, key){%> <% _.each(product.vendor_products, function(vendor_product, vp){%> <li id="cart_product_<%=vendor_product.id %>" data-qty="<%=vendor_product.quantity %>"> <a class='media' href='<%=show_cart_url %>'> <% if(vendor_product.pvariant.media_one){%> <img class='mr-2 blur-up lazyload' data-src="<%=vendor_product.pvariant.media_one.pimage.image.path.proxy_url %>200/200<%=vendor_product.pvariant.media_one.pimage.image.path.image_path %>"> <%}else if(vendor_product.pvariant.media_second && vendor_product.pvariant.media_second.image != null){%> <img class='mr-2 blur-up lazyload' data-src="<%=vendor_product.pvariant.media_second.image.path.proxy_url %>200/200<%=vendor_product.pvariant.media_second.image.path.image_path %>"> <%}else{%> <img class='mr-2 blur-up lazyload' data-src="<%=vendor_product.image_url %>"> <%}%> <div class='media-body'> <h4><%=vendor_product.product.translation_one ? vendor_product.product.translation_one.title : vendor_product.product.sku %></h4> <h4> <span><%=vendor_product.quantity %> x <%=Helper.formatPrice(vendor_product.pvariant.price * vendor_product.pvariant.multiplier) %></span> </h4> </div></a> <div class='close-circle'> <a href="javascript::void(0);" data-product="<%=vendor_product.id %>" class='remove-product'> <i class='fa fa-times' aria-hidden='true'></i> </a> </div></li><%}); %> <%}); %> <li><div class='total'><h5>{{__('Subtotal')}}: <span id='totalCart'>{{Session::get('currencySymbol')}}<%=Helper.formatPrice(cart_details.gross_amount) %></span></h5></div></li><li><div class='buttons'><a href="<%=show_cart_url %>" class='view-cart'>{{__('View Cart')}}</a> </script>
                                            <ul class="show-div shopping-cart " id="header_cart_main_ul"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-sm-12 p-lg-0 d-flex align-items-center justify-content-md-around justify-content-between  pr-lg-3 pl-lg-0">
                                    @if(isset($client_preference_detail))
                                    @if(($client_preference_detail->is_hyperlocal) && ($client_preference_detail->is_hyperlocal == 1))
                                    <div class="location-bar d-inline-flex align-items-center position-relative mr-xl-3 mr-lg-1" href="#edit-address" data-toggle="modal">
                                        <span class="al_icons_mapPin mr-lg-2"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_544_2)"><path d="M6.83602 0C3.70982 0 1.1665 2.54332 1.1665 5.66952C1.1665 6.95948 1.58924 8.17433 2.38902 9.18275C3.4246 10.4884 6.43607 13.5886 6.56374 13.7199L6.83598 14L7.10826 13.72C7.23601 13.5886 10.2484 10.4877 11.2841 9.18161C12.0832 8.17399 12.5055 6.95956 12.5055 5.66952C12.5055 2.54332 9.96222 0 6.83602 0ZM10.689 8.7097C9.85524 9.76111 7.6161 12.1 6.83602 12.909C6.05602 12.1 3.81767 9.76183 2.98409 8.7108C2.29189 7.83799 1.926 6.78635 1.926 5.66952C1.926 2.96211 4.12861 0.759495 6.83602 0.759495C9.54339 0.759495 11.746 2.96211 11.746 5.66952C11.746 6.78643 11.3805 7.83772 10.689 8.7097Z" fill=""/><path d="M6.83645 2.25098C4.98594 2.25098 3.48047 3.75645 3.48047 5.60696C3.48047 7.45747 4.98598 8.96294 6.83645 8.96294C8.68692 8.96294 10.1924 7.45743 10.1924 5.60696C10.1924 3.75649 8.68696 2.25098 6.83645 2.25098ZM6.83645 8.20344C5.40473 8.20344 4.23996 7.03864 4.23996 5.60696C4.23996 4.17523 5.40476 3.01047 6.83645 3.01047C8.26814 3.01047 9.4329 4.17527 9.43294 5.60696C9.43294 7.03868 8.26817 8.20344 6.83645 8.20344Z" fill=""/></g><defs><clipPath id="clip0_544_2"><rect width="14" height="14" fill="white"/></clipPath></defs></svg></span>
                                        <h2 class="homepage-address"><span data-placement="top" data-toggle="tooltip" title="{{session('selectedAddress')}}">{{session('selectedAddress')}}</span></h2>
                                    </div>
                                    @endif
                                    @endif
                                    <div class="d-inline-flex al_searchType border align-items-center justify-content-start px-xl-2 px-lg-1 position-relative">
                                        <button class="btn px-lg-0"><i class="fa fa-search" aria-hidden="true"></i></button>
                                            @php $searchPlaceholder=getNomenclatureName('Search', true); $searchPlaceholder=($searchPlaceholder==='Search product, vendor, item') ? __('Search product, vendor, item') : $searchPlaceholder; @endphp
                                        <input class="form-control border-0 typeahead" type="search" placeholder="{{$searchPlaceholder}}" id="main_search_box" autocomplete="off" >
                                        <div class="list-box style-4" style="display:none;" id="search_box_main_div"> </div>
                                    </div>
                                    <script type="text/template" id="search_box_main_div_template">
                                    <a class="text-right al_search_viewall d-block mr-2 mb-1" id="search_viewall" href="#">{{__("View All")}}</a>
                                    <div class="row mx-0">
                                        <% _.each(results, function(result, k){%>
                                        <a class="col-12 text-center al_search_results list-items pt-2" href="<%=result.redirect_url %>">
                                            <img class="blur-up lazyload" data-src="<%=result.image_url%>" alt="">
                                            <div class="result-item-name">
                                                <b><%=result.name %></b>
                                            </div>
                                        </a> <%}); %>
                                    </div>
                                    </script>
                                    <ul class="d-lg-flex align-items-center m-0 al_addCart daad d-md-block d-none">
                                        <li class="onhover-div pl-0 ml-xl-3 ml-lg-1 shake-effect">
                                            @if($client_preference_detail) @if($client_preference_detail->cart_enable==1)
                                            <a class="btn btn-solid d-flex align-items-center " href="{{route('showCart')}}">
                                                <i class="fa fa-shopping-cart mr-1 " aria-hidden="true"></i>
                                                <span>{{__('Cart')}}</span>
                                                <span id="cart_qty_span"></span>
                                            </a> @endif @endif
                                            <script type="text/template" id="header_cart_template"> <% _.each(cart_details.products, function(product, key){%> <% _.each(product.vendor_products, function(vendor_product, vp){%> <li id="cart_product_<%=vendor_product.id %>" data-qty="<%=vendor_product.quantity %>"> <a class='media' href='<%=show_cart_url %>'> <% if(vendor_product.pvariant.media_one){%> <img class='mr-2 blur-up lazyload' data-src="<%=vendor_product.pvariant.media_one.pimage.image.path.proxy_url %>200/200<%=vendor_product.pvariant.media_one.pimage.image.path.image_path %>"> <%}else if(vendor_product.pvariant.media_second && vendor_product.pvariant.media_second.image != null){%> <img class='mr-2 blur-up lazyload' data-src="<%=vendor_product.pvariant.media_second.image.path.proxy_url %>200/200<%=vendor_product.pvariant.media_second.image.path.image_path %>"> <%}else{%> <img class='mr-2 blur-up lazyload' data-src="<%=vendor_product.image_url %>"> <%}%> <div class='media-body'> <h4><%=vendor_product.product.translation_one ? vendor_product.product.translation_one.title : vendor_product.product.sku %></h4> <h4> <span><%=vendor_product.quantity %> x <%=Helper.formatPrice(vendor_product.pvariant.price * vendor_product.pvariant.multiplier) %></span> </h4> </div></a> <div class='close-circle'> <a href="javascript::void(0);" data-product="<%=vendor_product.id %>" class='remove-product'> <i class='fa fa-times' aria-hidden='true'></i> </a> </div></li><%}); %> <%}); %> <li><div class='total'><h5>{{__('Subtotal')}}: <span id='totalCart'>{{Session::get('currencySymbol')}}<%=Helper.formatPrice(cart_details.gross_amount) %></span></h5></div></li><li><div class='buttons'><a href="<%=show_cart_url %>" class='view-cart'>{{__('View Cart')}}</a> </script>
                                            <ul class="show-div shopping-cart " id="header_cart_main_ul"></ul>
                                        </li>
                                    </ul>

                                    <ul class="header-dropdown ml-auto d-lg-block d-none">
                                        @if($client_preference_detail->header_quick_link == 1)
                                        <li class="onhover-dropdown quick-links quick-links pr-2 mr-2">

                                            <span class="quick-links mr-1 align-middle">{{ __('Quick Links') }}</span>
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
                                        @if(count($currencyList) > 1)
                                        <li class="onhover-dropdown change-currency pr-2 mr-2">
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
                                        @if(count($languageList) > 1)
                                        <li class="onhover-dropdown change-language pr-2 mr-2">
                                            <div class="input-group">
                                                <div class="input-group-prepend al_BGcolor">
                                                    <label class="input-group-text" for="inputGroupSelect01"><i class="fa fa-globe" aria-hidden="true"></i></label>
                                                </div>
                                                <select class="custom-select change_language_selector" id="inputGroupSelect01">
                                                    <option>Language</option>
                                                     @foreach ($languageList as $key => $listl)

                                                    <option class="customerLang" value="{{ $listl->language_id }}" {{ session()->get('locale') == $listl->language->sort_code ? 'selected' : '' }}>{{ $listl->language->name }}</option>
                                                   @endforeach
                                                </select>
                                            </div>
                                        </li>

                                   <!--      @foreach ($languageList as $key => $listl)
                                    <li
                                        class="{{ session()->get('locale') == $listl->language->sort_code ? 'active' : '' }}">
                                        <a href="javascript:void(0)" class="customerLang"
                                            langId="{{ $listl->language_id }}">{{ $listl->language->name }}</a>
                                    </li>
                                @endforeach -->
                                        @endif



                                        <li class="onhover-dropdown mobile-account pr-2">
                                            <span class="al_BGcolor"><i class="fa fa-user" aria-hidden="true"></i></span>
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

                                <div class="col-sm-12 p-0 d-flex align-items-center alOverflow">
                                    <div class="menu_navigation_al_four mt-lg-3">
                                        <ul id="main-menu" class="sm pixelstrap sm-horizontal menu-slider d-flex justify-content-center" >
                                            @foreach($navCategories as $cate)
                                            @if($cate['name'])
                                            <li class="alMainMenu @if(!empty($cate['children'])) has-children @endif ">
                                                <a class="alMainLink" href="{{route('categoryDetail', $cate['slug'])}}">

                                                    {{$cate['name']}}
                                                </a>
                                                @if(!empty($cate['children']))
                                                <ul class="alInnerMenu">
                                                    @foreach($cate['children'] as $childs)
                                                    <li class="alInnerLink">
                                                        <a href="{{route('categoryDetail', $childs['slug'])}}"><span class="new-tag">{{$childs['name']}}</span></a>
                                                        @if(!empty($childs['children']))
                                                        <ul class="alInnerSubMenu">
                                                            @foreach($childs['children'] as $chld)
                                                            <li class="alInnerSublink"><a href="{{route('categoryDetail', $chld['slug'])}}">{{$chld['name']}}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </nav>
    </div>


    {{-- @endif --}}
</header>
@endif

<div class=" @if((\Request::route()->getName() != 'userHome') || ($client_preference_detail->show_icons == 0)) inner-pages-offset al_offset-top @else al_offset-top-home @endif @if($client_preference_detail->hide_nav_bar == 1) set-hide-nav-bar @endif"></div>
<script type="text/template" id="nav_categories_template">
    <!-- <li>
       <div class="mobile-back text-end">Back<i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
   </li> -->
    <% _.each(nav_categories, function(category, key){ %>
    <% var icon_two_url = null;
      if(category.icon_two != null){
        icon_two_url =  category.icon_two.image_fit + '200/200' + category.icon_two.image_path;
      }else{
        icon_two_url =  category.icon.image_fit + '200/200' + category.icon.image_path;
      }
    %>
    <li class="alMainMenu  <% if(category.children){%> has-children <%}%> "  >
        <a class="alMainLink" href="{{route('categoryDetail')}}/<%=category.slug %>">
            <%=category.name %>
        </a>
        <% if(category.children){%>
        <ul class="alInnerMenu">
            <% _.each(category.children, function(childs, key1){%>
            <li class="alInnerLink">
                <a href="{{route('categoryDetail')}}/<%=childs.slug %>">
                    <span class="new-tag"><%=childs.name %></span>
                </a>
                <% if(childs.children){%>
                <ul class="alInnerSubMenu">
                    <% _.each(childs.children, function(chld, key2){%>
                    <li class="alInnerSublink">
                        <a href="{{route('categoryDetail')}}/<%=chld.slug %>">
                            <%=chld.name %>
                        </a>
                    </li>
                    <%}); %>
                </ul>
                <%}%>
            </li>
            <%}); %>
        </ul>
        <%}%>
    </li>
    <% }); %>
</script>
@if($client_preference_detail)
    @if($client_preference_detail->is_hyperlocal == 1 )
        <div class="modal fade edit_address" id="edit-address" tabindex="-1" aria-labelledby="edit-addressLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div id="address-map-container">
                        <div id="address-map"></div>
                        </div>
                        <div class="delivery_address p-2 mb-2 position-relative">
                        <button type="button" class="close edit-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="form-group address-input-group">
                            <label class="delivery-head mb-2">{{__('SELECT YOUR LOCATION')}}</label>
                            <div class="address-input-field d-flex align-items-center justify-content-between"> <i class="fa fa-map-marker" aria-hidden="true"></i> <input class="form-control border-0 map-input" type="text" name="address-input" id="address-input" value="{{session('selectedAddress')}}"> <input type="hidden" name="address_latitude" id="address-latitude" value="{{session('latitude')}}"/> <input type="hidden" name="address_longitude" id="address-longitude" value="{{session('longitude')}}"/> <input type="hidden" name="address_place_id" id="address-place-id" value="{{session('selectedPlaceId')}}"/> </div>
                        </div>
                        <div class="text-center"> <button type="button" class="btn btn-solid ml-auto confirm_address_btn w-100">{{__('Confirm And Proceed')}}</button> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
<div class="modal fade remove-cart-modal" id="remove_cart_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_cartLabel" style="background-color: rgba(0,0,0,0.8);">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header pb-0">
            <h5 class="modal-title" id="remove_cartLabel">{{__('Remove Cart')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <h6 class="m-0">{{__('This change will remove all your cart products. Do you really want to continue ?')}}</h6>
         </div>
         <div class="modal-footer flex-nowrap justify-content-center align-items-center"> <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button> <button type="button" class="btn btn-solid" id="remove_cart_button" data-cart_id="">{{__('Remove')}}</button> </div>
      </div>
   </div>
</div>