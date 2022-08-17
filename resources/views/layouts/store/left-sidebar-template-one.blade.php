@php
$clientData = \App\Models\Client::select('id', 'logo', 'dark_logo')
->where('id', '>', 0)
->first();
if(Session::get('config_theme') == 'dark'){
    $urlImg = $clientData ? $clientData->dark_logo['original'] : ' ';
}else{
    $urlImg = $clientData ? $clientData->logo['original'] : ' ';
}

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
<article class="site-header @if ($client_preference_detail->business_type == 'taxi') taxi-header @endif">
    @include('layouts.store/topbar-template-one')

    @if($client_preference_detail->business_type == 'taxi')
    <!-- Start Cab Booking Header From Here -->
    <div class="cab-booking-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-3 col-md-2">
                    <a class="navbar-brand mr-0" style="height:60px" href="{{ route('userHome') }}"><img height="60" alt="" src="{{ $urlImg }}"></a>
                </div>
                <div class="col-sm-9 col-md-10 top-header bg-transparent">
                    <ul class="header-dropdown d-flex align-items-center justify-content-md-end justify-content-center">
                        @if ($client_preference_detail->header_quick_link == 1)
                        <li class="onhover-dropdown quick-links quick-links">
                            <span class="quick-links ml-1 align-middle">{{ __('Quick Links') }}</span>
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
                                    <a href="{{ route('extrapage', ['slug' => $page->slug]) }}" target="_blank">
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
                        <li class="onhover-dropdown change-currency">
                            <a href="javascript:void(0)">{{ session()->get('iso_code') }}
                                <span class="icon-ic_currency align-middle"></span>
                                <span class="currency ml-1 align-middle">{{ __('currency') }}</span>
                            </a>
                            <ul class="onhover-show-div">
                                @foreach ($currencyList as $key => $listc)
                                <li
                                    class="{{ session()->get('iso_code') == $listc->currency->iso_code ? 'active' : '' }}">
                                    <a href="javascript:void(0)" currId="{{ $listc->currency_id }}" class="customerCurr"
                                        currSymbol="{{ $listc->currency->symbol }}">
                                        {{ $listc->currency->iso_code }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @if (Auth::guest())
                        <li class="onhover-dropdown mobile-account d-block">
                            <i class="fa fa-user mr-1" aria-hidden="true"></i>{{ __('Account') }}
                            <ul class="onhover-show-div">
                                <li>
                                    <a href="{{ route('customer.login') }}" data-lng="en">{{ __('Login') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.register') }}" data-lng="es">{{ __('Register') }}</a>
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
    <!-- End Cab Booking Header From Here -->

    @else
    <div class="main-menu al_template_one_menu">
        <div class="container-fluid d-block p-0">
            <div class="container p-0 align-items-center justify-content-center position-initial">
                <div class="col-lg-12">
                    <div class="row mobile-header align-items-center justify-content-between my-sm-2">
                        @include('frontend.home_page_1.main_menu')
                    </div>
                </div>
            </div>
            <div class="menu-navigation al">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                @include('frontend.home_page_1.sub_menu')
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    @endif
</article>
<div class=" @if((\Request::route()->getName() != 'userHome') || ($client_preference_detail->show_icons == 0)) inner-pages-offset @else al_offset-top-home @endif @if($client_preference_detail->hide_nav_bar == 1) set-hide-nav-bar @endif">
</div>
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
   @if ($client_preference_detail->view_get_estimation_in_category == 1 && $client_preference_detail->business_type == "laundry")
   <li class="al_main_category"> <a href="/get-estimation#<%=category.slug %>"> @if($client_preference_detail->show_icons==1 && \Request::route()->getName()=='userHome') <div class="nav-cate-img"> <img class="blur-up lazyload" data-src="<%=category.icon.image_fit %>200/200<%=category.icon.image_path %>" alt=""> </div>@endif <%=category.name %> </a> <% if(category.children){%> <ul class="al_main_category_list"> <% _.each(category.children, function(childs, key1){%> <li> <a href="/get-estimation#<%=category.slug %>"><span class="new-tag"><%=childs.name %></span></a> <% if(childs.children){%> <ul class="al_main_category_sub_list"> <% _.each(childs.children, function(chld, key2){%> <li><a href="/get-estimation#<%=category.slug %>"><%=chld.name %></a></li><%}); %> </ul> <%}%> </li><%}); %> </ul> <%}%> </li>
   @else
    <li class="al_main_category"> <a href="{{route('categoryDetail')}}/<%=category.slug %>" >
            @if($client_preference_detail->show_icons==1 && \Request::route()->getName()=='userHome') <div
                class="nav-cate-img"> <img class="blur-up lazyload" data-icon_two="<%=icon_two_url %>" data-icon="<%=category.icon.image_fit %>200/200<%=category.icon.image_path %>"
                    data-src="<%=category.icon.image_fit %>200/200<%=category.icon.image_path %>" alt="" onmouseover='changeImage(this,1)' onmouseout='changeImage(this,0)'> </div>@endif
            <%=category.name %> </a> <% if(category.children){%> <ul class="al_main_category_list">
            <% _.each(category.children, function(childs, key1){%> <li> <a
                    href="{{route('categoryDetail')}}/<%=childs.slug %>"><span
                        class="new-tag"><%=childs.name %></span></a> <% if(childs.children){%> <ul
                    class="al_main_category_sub_list"> <% _.each(childs.children, function(chld, key2){%> <li><a
                            href="{{route('categoryDetail')}}/<%=chld.slug %>"><%=chld.name %></a></li><%}); %> </ul>
                <%}%> </li><%}); %> </ul> <%}%> </li>
    @endif
        <% }); %>
</script>
@if($client_preference_detail)
@if($client_preference_detail->is_hyperlocal == 1 )
<div class="modal fade edit_address" id="edit-address" tabindex="-1" aria-labelledby="edit-addressLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div id="address-map-container">
                    <div id="address-map"></div>
                </div>
                <div class="delivery_address p-2 mb-2 position-relative">
                    <button type="button" class="close edit-close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <div class="form-group address-input-group">
                        <label class="delivery-head mb-2">{{__('SELECT YOUR LOCATION')}}</label>
                        <div class="address-input-field d-flex align-items-center justify-content-between"> <i
                                class="fa fa-map-marker" aria-hidden="true"></i> <input
                                class="form-control border-0 map-input" type="text" name="address-input"
                                id="address-input" value="{{session('selectedAddress')}}"> <input type="hidden"
                                name="address_latitude" id="address-latitude" value="{{session('latitude')}}" /> <input
                                type="hidden" name="address_longitude" id="address-longitude"
                                value="{{session('longitude')}}" /> <input type="hidden" name="address_place_id"
                                id="address-place-id" value="{{session('selectedPlaceId')}}" /> </div>
                    </div>
                    <div class="text-center"> <button type="button"
                            class="btn btn-solid ml-auto confirm_address_btn w-100">{{__('Confirm And Proceed')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif
<div class="modal fade remove-cart-modal" id="remove_cart_modal" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="remove_cartLabel" style="background-color: rgba(0,0,0,0.8);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title" id="remove_cartLabel">{{__('Remove Cart')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                        aria-hidden="true">×</span> </button>
            </div>
            <div class="modal-body">
                <h6 class="m-0">
                    {{__('This change will remove all your cart products. Do you really want to continue ?')}}</h6>
            </div>
            <div class="modal-footer flex-nowrap justify-content-center align-items-center"> <button type="button"
                    class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button> <button type="button"
                    class="btn btn-solid" id="remove_cart_button" data-cart_id="">{{__('Remove')}}</button> </div>
        </div>
    </div>
</div>
@php
                $applocale = 'en';
                if(session()->has('applocale')){
                    $applocale = session()->get('applocale');
                }
                @endphp
<!-- Modal -->
<div class="modal fade mobile-setting" id="setting_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="setting-modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title" id="setting-modalLabel">Language & Currency</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-0">
        <div class="show-div setting">
            <h6 class="mb-1">{{ __("language") }}</h6>
            <ul>
                @foreach($languageList as $key => $listl)
                    <li class="{{$applocale ==  $listl->language->sort_code ?  'active' : ''}}">
                        <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}@if($listl->language->id != 1)
                            ({{$listl->language->nativeName}})
                            @endif </a>
                    </li>
                @endforeach
            </ul>
            <h6 class="mb-1">{{ __("currency") }}</h6>
            <ul class="list-inline">
                @foreach($currencyList as $key => $listc)
                    <li class="{{session()->get('iso_code') ==  $listc->currency->iso_code ?  'active' : ''}}">
                        <a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr " currSymbol="{{$listc->currency->symbol}}">{{$listc->currency->iso_code}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
      </div>
    </div>
  </div>
</div>
