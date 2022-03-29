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

<header id="al_four_design" class="site-header @if ($client_preference_detail->business_type == 'taxi') taxi-header @endif">
    @include('layouts.store/topbar-template-four')
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
   @if($client_preference_detail->business_type != 'taxi')
    <div class="main-menu @if((\Request::route()->getName() != 'userHome')) no-category-image @endif">
        <div class="container-fluid text-center pt-3" >
            <div class="row align-items-center justify-content-center position-initial">
                <div class="al_count_tabs_fourdesign d-none d-sm-block" data-aos="zoom-in">
                            @if($mod_count > 1)
                            <ul class="nav nav-tabs navigation_tab_al nav-material tab-icons vendor_mods" id="top-tab" role="tablist">
                                @if($client_preference_detail->delivery_check==1) @php $Delivery=getNomenclatureName('Delivery', true); $Delivery=($Delivery==='Delivery') ? __('Delivery') : $Delivery; @endphp
                                <li class="navigation-tab-item" role="presentation">
                                    <a class="nav-link al_delivery {{($mod_count==1 || (Session::get('vendorType')=='delivery') || (Session::get('vendorType')=='')) ? 'active' : ''}}" id="delivery_tab" data-toggle="tab" href="#delivery_tab" role="tab" aria-controls="profile" aria-selected="false">
                                        {{$Delivery}}
                                    </a>
                                </li>
                                @endif @if($client_preference_detail->dinein_check==1) @php $Dine_In=getNomenclatureName('Dine-In', true); $Dine_In=($Dine_In==='Dine-In') ? __('Dine-In') : $Dine_In; @endphp
                                <li class="navigation-tab-item " role="presentation">
                                    <a class="nav-link al_dinein {{($mod_count==1 || (Session::get('vendorType')=='dine_in')) ? 'active' : ''}}" id="dinein_tab" data-toggle="tab" href="#dinein_tab" role="tab" aria-controls="dinein_tab" aria-selected="false">
                                        {{$Dine_In}}
                                    </a>
                                </li>
                                @endif @if($client_preference_detail->takeaway_check==1)
                                <li class="navigation-tab-item " role="presentation">
                                    @php $Takeaway=getNomenclatureName('Takeaway', true); $Takeaway=($Takeaway==='Takeaway') ? __('Takeaway') : $Takeaway; @endphp
                                    <a class="nav-link al_takeway {{($mod_count==1 || (Session::get('vendorType')=='takeaway')) ? 'active' : ''}}" id="takeaway_tab" data-toggle="tab" href="#takeaway_tab" role="tab" aria-controls="takeaway_tab" aria-selected="false">
                                        {{$Takeaway}}
                                    </a>
                                </li>
                                @endif
                                <div class="navigation-tab-overlay_alnew_design"></div>
                            </ul>
                            @endif
                        </div>

                        <div class="al_count_tabs_new_design al_tab_mobile position-fixed d-block d-sm-none">
                            @if($mod_count > 1)
                            <ul class="nav nav-tabs navigation-tab_al nav-material tab-icons mr-lg-3 vendor_mods d-flex justify-content-around" id="top-tab" role="tablist">
                                @if($client_preference_detail->delivery_check==1) @php $Delivery=getNomenclatureName('Delivery', true); $Delivery=($Delivery==='Delivery') ? __('Delivery') : $Delivery; @endphp
                                <li class="navigation-tab-item pr-lg-3" role="presentation">
                                    <a class="nav-link al_delivery {{($mod_count==1 || (Session::get('vendorType')=='delivery') || (Session::get('vendorType')=='')) ? 'active' : ''}}" id="delivery_tab" data-toggle="tab" href="#delivery_tab" role="tab" aria-controls="profile" aria-selected="false">
                                        <span><img src="{{asset('images/al_custom3.png')}}" alt=""></span>
                                        {{$Delivery}}
                                    </a>
                                </li>
                                @endif @if($client_preference_detail->dinein_check==1) @php $Dine_In=getNomenclatureName('Dine-In', true); $Dine_In=($Dine_In==='Dine-In') ? __('Dine-In') : $Dine_In; @endphp
                                <li class="navigation-tab-item pr-lg-3 " role="presentation">
                                    <a class="nav-link al_dinein {{($mod_count==1 || (Session::get('vendorType')=='dine_in')) ? 'active' : ''}}" id="dinein_tab" data-toggle="tab" href="#dinein_tab" role="tab" aria-controls="dinein_tab" aria-selected="false">
                                        <span><img src="{{asset('images/al_custom1.png')}}" alt=""></span>
                                        {{$Dine_In}}
                                    </a>
                                </li>
                                @endif @if($client_preference_detail->takeaway_check==1)
                                <li class="navigation-tab-item  pr-lg-3" role="presentation">
                                    @php $Takeaway=getNomenclatureName('Takeaway', true); $Takeaway=($Takeaway==='Takeaway') ? __('Takeaway') : $Takeaway; @endphp
                                    <a class="nav-link al_takeway {{($mod_count==1 || (Session::get('vendorType')=='takeaway')) ? 'active' : ''}}" id="takeaway_tab" data-toggle="tab" href="#takeaway_tab" role="tab" aria-controls="takeaway_tab" aria-selected="false">
                                        <span><img src="{{asset('images/al_custom2.png')}}" alt=""></span>
                                        {{$Takeaway}}
                                    </a>
                                </li>
                                @endif
                            </ul>
                            @endif
                        </div>
            </div>
        </div>
    </div>

   @endif

    {{-- @endif --}}
</header>

<div class=" @if((\Request::route()->getName() != 'userHome') || ($client_preference_detail->show_icons == 0)) inner-pages-offset al_offset-top @else al_offset-top-home @endif @if($client_preference_detail->hide_nav_bar == 1) set-hide-nav-bar @endif"></div>
<script type="text/template" id="nav_categories_template">
    <!-- <li>
       <div class="mobile-back text-end">Back<i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
   </li> -->
    <% _.each(nav_categories, function(category, key){ %>
    <% var icon_2_url = null;
      if(category.icon_two != null){
         icon_2_url =  category.icon_two.image_fit + '200/200' + category.icon_two.image_path;
      }else{
         icon_2_url =  category.icon.image_fit + '200/200' + category.icon.image_path;
      }
    %>
    <li class="al_main_category" data-aos="zoom-in">
        <a href="{{route('categoryDetail')}}/<%=category.slug %>">
            <%=category.name %>
        </a>
        <% if(category.children){%>
        <ul class="al_main_category_list">
            <% _.each(category.children, function(childs, key1){%>
            <li>
                <a href="{{route('categoryDetail')}}/<%=childs.slug %>">
                    <span class="new-tag"><%=childs.name %></span>
                </a>
                <% if(childs.children){%>
                <ul class="al_main_category_sub_list">
                    <% _.each(childs.children, function(chld, key2){%>
                    <li>
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