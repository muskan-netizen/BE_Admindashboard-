@php
$clientData = \App\Models\Client::select('id', 'logo','dark_logo')->where('id', '>', 0)->first();
if(Session::get('config_theme') == 'dark'){
    $urlImg = $clientData ? $clientData->dark_logo['original'] : ' ';
}else{
    $urlImg = $clientData ? $clientData->logo['original'] : ' ';
}
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp
<style>.cab-booking-header{display: none;}</style>
<header class="site-header">
   @include('layouts.store/topbar-template-two')
   <!-- Start Cab Booking Header From Here -->
   <div class="cab-booking-header">
      <div class="container">
         <div class="row d-flex justify-content-start align-items-center">
            <div class="col-6"> <a class="navbar-brand mr-0" style="height:60px" href="{{route('userHome')}}"><img alt="" src="{{$urlImg}}"></a> </div>
            <div class="col-6 text-right top-header bg-transparent d-md-block d-none">
               <ul class="header-dropdown ml-auto">
                  @if(count($languageList) > 1)
                  <li class="onhover-dropdown change-language">
                     <a href="javascript:void(0)">{{session()->get('locale')}}<span class="icon-ic_lang align-middle"></span> <span class="language ml-1 align-middle">{{__('language')}}</span> </a>
                     <ul class="onhover-show-div">
                        @foreach($languageList as $key=> $listl)
                        <li class="{{session()->get('locale')==$listl->language->sort_code ? 'active' : ''}}"> <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}</a> </li>
                        @endforeach
                     </ul>
                  </li>
                  @endif
                  @if(count($currencyList) > 1)
                  <li class="onhover-dropdown change-currency">
                     <a href="javascript:void(0)">{{session()->get('iso_code')}}<span class="icon-ic_currency align-middle"></span> <span class="currency ml-1 align-middle">{{('currency')}}</span> </a>
                     <ul class="onhover-show-div">
                        @foreach($currencyList as $key=> $listc)
                        <li class="{{session()->get('iso_code')==$listc->currency->iso_code ? 'active' : ''}}"> <a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr" currSymbol="{{$listc->currency->symbol}}">{{$listc->currency->iso_code}}</a> </li>
                        @endforeach
                     </ul>
                  </li>
                  @endif
                  @if(Auth::guest())
                  <li class="onhover-dropdown mobile-account">
                     <i class="fa fa-user" aria-hidden="true"></i>{{__('Account')}}
                     <ul class="onhover-show-div">
                        <li> <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a> </li>
                        <li> <a href="{{route('customer.register')}}" data-lng="es">{{__('Register')}}</a> </li>
                     </ul>
                  </li>
                  @else
                  <li class="onhover-dropdown mobile-account">
                     <i class="fa fa-user" aria-hidden="true"></i>{{__('Account')}}
                     <ul class="onhover-show-div">
                        @if(Auth::user()->is_superadmin==1 || Auth::user()->is_admin==1)
                        <li> <a href="{{route('client.dashboard')}}" data-lng="en">{{__('Control Panel')}}</a> </li>
                        @endif
                        <li> <a href="{{route('user.profile')}}" data-lng="en">{{__('Profile')}}</a> </li>
                        <li> <a href="{{route('user.logout')}}" data-lng="es">{{__('Logout')}}</a> </li>
                     </ul>
                  </li>
                  @endif
               </ul>
            </div>
            <div class="al_mobile_menu al_new_mobile_header">
               <a class="al_toggle-menu" href="#">
                    <i></i>
                    <i></i>
                    <i></i>
               </a>
               <div class="al_menu-drawer">
                  <ul class="header-dropdown">
                     @if(count($languageList) > 1)
                     <li class="onhover-dropdown change-language">
                        <a href="javascript:void(0)">{{session()->get('locale')}}<span class="icon-ic_lang align-middle"></span> <span class="language ml-1 align-middle">{{__('language')}}</span> </a>
                        <ul class="onhover-show-div">
                           @foreach($languageList as $key=> $listl)
                           <li class="{{session()->get('locale')==$listl->language->sort_code ? 'active' : ''}}"> <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}</a> </li>
                           @endforeach
                        </ul>
                     </li>
                     @endif
                     @if(count($currencyList) > 1)
                     <li class="onhover-dropdown change-currency">
                        <a href="javascript:void(0)">{{session()->get('iso_code')}}<span class="icon-ic_currency align-middle"></span> <span class="currency ml-1 align-middle">{{('currency')}}</span> </a>
                        <ul class="onhover-show-div">
                           @foreach($currencyList as $key=> $listc)
                           <li class="{{session()->get('iso_code')==$listc->currency->iso_code ? 'active' : ''}}"> <a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr" currSymbol="{{$listc->currency->symbol}}">{{$listc->currency->iso_code}}</a> </li>
                           @endforeach
                        </ul>
                     </li>
                     @endif
                     @if(Auth::guest())
                     <li class="onhover-dropdown mobile-account">
                        <i class="fa fa-user" aria-hidden="true"></i>{{__('Account')}}
                        <ul class="onhover-show-div">
                           <li> <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a> </li>
                           <li> <a href="{{route('customer.register')}}" data-lng="es">{{__('Register')}}</a> </li>
                        </ul>
                     </li>
                     @else
                     <li class="onhover-dropdown mobile-account">
                        <i class="fa fa-user" aria-hidden="true"></i>{{__('Account')}}
                        <ul class="onhover-show-div">
                           @if(Auth::user()->is_superadmin==1 || Auth::user()->is_admin==1)
                           <li> <a href="{{route('client.dashboard')}}" data-lng="en">{{__('Control Panel')}}</a> </li>
                           @endif
                           <li> <a href="{{route('user.profile')}}" data-lng="en">{{__('Profile')}}</a> </li>
                           <li> <a href="{{route('user.logout')}}" data-lng="es">{{__('Logout')}}</a> </li>
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
               <div class="al_menu-drawer">
                  <ul class="header-dropdown">
                     @if(count($languageList) > 1)
                     <li class="onhover-dropdown change-language">
                        <a href="javascript:void(0)">{{session()->get('locale')}}<span class="icon-ic_lang align-middle"></span> <span class="language ml-1 align-middle">{{__('language')}}</span> </a>
                        <ul class="onhover-show-div">
                           @foreach($languageList as $key=> $listl)
                           <li class="{{session()->get('locale')==$listl->language->sort_code ? 'active' : ''}}"> <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}</a> </li>
                           @endforeach
                        </ul>
                     </li>
                     @endif
                     @if(count($currencyList) > 1)
                     <li class="onhover-dropdown change-currency">
                        <a href="javascript:void(0)">{{session()->get('iso_code')}}<span class="icon-ic_currency align-middle"></span> <span class="currency ml-1 align-middle">{{('currency')}}</span> </a>
                        <ul class="onhover-show-div">
                           @foreach($currencyList as $key=> $listc)
                           <li class="{{session()->get('iso_code')==$listc->currency->iso_code ? 'active' : ''}}"> <a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr" currSymbol="{{$listc->currency->symbol}}">{{$listc->currency->iso_code}}</a> </li>
                           @endforeach
                        </ul>
                     </li>
                     @endif
                     @if(Auth::guest())
                     <li class="onhover-dropdown mobile-account">
                        <i class="fa fa-user" aria-hidden="true"></i>{{__('Account')}}
                        <ul class="onhover-show-div">
                           <li> <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a> </li>
                           <li> <a href="{{route('customer.register')}}" data-lng="es">{{__('Register')}}</a> </li>
                        </ul>
                     </li>
                     @else
                     <li class="onhover-dropdown mobile-account">
                        <i class="fa fa-user" aria-hidden="true"></i>{{__('Account')}}
                        <ul class="onhover-show-div">
                           @if(Auth::user()->is_superadmin==1 || Auth::user()->is_admin==1)
                           <li> <a href="{{route('client.dashboard')}}" data-lng="en">{{__('Control Panel')}}</a> </li>
                           @endif
                           <li> <a href="{{route('user.profile')}}" data-lng="en">{{__('Profile')}}</a> </li>
                           <li> <a href="{{route('user.logout')}}" data-lng="es">{{__('Logout')}}</a> </li>
                        </ul>
                     </li>
                     @endif
                  </ul>
               </div>
            </div>
      </div>
   </div>
   <!-- End Cab Booking Header From Here -->

   <div class="container main-menu d-block ">
      <div class="row align-items-center py-md-2 position-initial">
         @include('frontend.home_page_2.main_menu')
      </div></div>
      {{--@if(count($navCategories) > 0)--}}
   <div class="menu-navigation al_template_two_menu"> <div class="container-fluid"> <div class="row"> <div class="col-12"> 
      @include('frontend.home_page_2.sub_menu')
   </div></div></div></div>
   {{--@endif--}}
</header>
<div class="offset-top_al @if((\Request::route()->getName() != 'userHome') || ($client_preference_detail->show_icons == 0)) inner-pages-offset @else al_offset-top-home @endif @if($client_preference_detail->hide_nav_bar == 1) set-hide-nav-bar @endif"></div>
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
      <li> <a href="{{route('categoryDetail')}}/<%=category.slug %>"> @if($client_preference_detail->show_icons==1 && \Request::route()->getName()=='userHome') <img class="blur-up lazyload"  data-icon_two="<%=icon_two_url %>" data-src="<%=category.icon.image_fit %>200/200<%=category.icon.image_path %>" data-icon="<%=category.icon.image_fit %>200/200<%=category.icon.image_path %>" alt="" onmouseover='changeImage(this,1)' onmouseout='changeImage(this,0)'> @endif <%=category.name %> </a> <% if(category.children){%> <ul> <% _.each(category.children, function(childs, key1){%> <li> <a href="{{route('categoryDetail')}}/<%=childs.slug %>"><span class="new-tag"><%=childs.name %></span></a> <% if(childs.children){%> <ul> <% _.each(childs.children, function(chld, key2){%> <li><a href="{{route('categoryDetail')}}/<%=chld.slug %>"><%=chld.name %></a></li><%}); %> </ul> <%}%> </li><%}); %> </ul> <%}%> </li>
   <% }); %>
</script>
@if( $client_preference_detail)
    @if( $client_preference_detail->is_hyperlocal == 1 )
<div class="modal fade edit_address" id="edit-address" tabindex="-1" aria-labelledby="edit-addressLabel" aria-hidden="true"> <div class="modal-dialog modal-dialog-centered"> <div class="modal-content"> <div class="modal-body p-0"> <div id="address-map-container"> <div id="address-map"></div></div><div class="delivery_address p-2 mb-2 position-relative"> <button type="button" class="close edit-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <div class="form-group"> <label class="delivery-head mb-2">{{__('SELECT YOUR LOCATION')}}</label> <div class="address-input-field d-flex align-items-center justify-content-between"> <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.848633 6.15122C0.848633 2.7594 3.60803 0 6.99985 0C10.3917 0 13.1511 2.7594 13.1511 6.15122C13.1511 8.18227 12.1614 9.98621 10.6392 11.107L7.46151 15.7563C7.3573 15.9088 7.18455 16 6.99985 16C6.81516 16 6.64237 15.9088 6.5382 15.7563L3.36047 11.107C1.8383 9.98621 0.848633 8.18227 0.848633 6.15122ZM6.99981 10.4225C7.23979 10.4225 7.47461 10.4072 7.70177 10.3806C9.73302 10.0446 11.2871 8.27613 11.287 6.15122C11.287 3.78725 9.36375 1.86402 6.99977 1.86402C4.6358 1.86402 2.71257 3.78725 2.71257 6.15122C2.71257 8.27613 4.26665 10.0446 6.29786 10.3806C6.52498 10.4072 6.75984 10.4225 6.99981 10.4225ZM9.23683 6.15089C9.23683 7.38626 8.23537 8.38772 7.00001 8.38772C5.76464 8.38772 4.76318 7.38626 4.76318 6.15089C4.76318 4.91552 5.76464 3.91406 7.00001 3.91406C8.23537 3.91406 9.23683 4.91552 9.23683 6.15089Z" fill="white"/></svg> <input class="form-control border-0 map-input" type="text" name="address-input" id="address-input" value="{{session('selectedAddress')}}"> <input type="hidden" name="address_latitude" id="address-latitude" value="{{session('latitude')}}"/> <input type="hidden" name="address_longitude" id="address-longitude" value="{{session('longitude')}}"/> <input type="hidden" name="address_place_id" id="address-place-id" value="{{session('selectedPlaceId')}}"/> </div></div><div class="text-center"> <button type="button" class="btn btn-solid ml-auto confirm_address_btn w-100">{{__('Confirm And Proceed')}}</button> </div></div></div></div></div></div>
@endif
@endif
<div class="modal fade remove-cart-modal" id="remove_cart_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_cartLabel" style="background-color: rgba(0,0,0,0.8);"> <div class="modal-dialog modal-dialog-centered"> <div class="modal-content"> <div class="modal-header pb-0"> <h5 class="modal-title" id="remove_cartLabel">{{__('Remove Cart')}}</h5> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button> </div><div class="modal-body"> <h6 class="m-0">{{__('This change will remove all your cart products. Do you really want to continue ?')}}</h6> </div><div class="modal-footer flex-nowrap justify-content-center align-items-center"> <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button> <button type="button" class="btn btn-solid" id="remove_cart_button" data-cart_id="">{{__('Remove')}}</button> </div></div></div></div>
