@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
$urlImg = $clientData->logo['image_fit'].'150/60'.$clientData->logo['image_path'];
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
$pages = \App\Models\Page::with(['translations' => function($q) {$q->where('language_id', session()->get('customerLanguage') ??1);}])->whereHas('translations', function($q) {$q->where(['is_published' => 1, 'language_id' => session()->get('customerLanguage') ??1]);})->orderBy('order_by','ASC')->get();
$preference = $client_preference_detail;
$applocale = 'en';
if(session()->has('applocale')){
$applocale = session()->get('applocale');
}
@endphp
<!-- alSpaMenuCard start -->
<section class="alSpaMenuCard">
   <div class="container-fluid d-flex align-items-center justify-content-center h-100">
      <button class="alMenuClose">×</button>
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
               @else
               <li>
                  <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a>
               </li>
               <li>
                  <a href="{{route('customer.register')}}" data-lng="es">{{__('Register')}}</a>
               </li>
               @endif
               @if($client_preference_detail->show_wishlist == 1)
               <li class="onhover-dropdown_al mobile-wishlist_al">
                  <a href="{{route('user.wishlists')}}">
                  {{__('Wishlist')}}
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
                  <a href="javascript:void(0)">
                  <span class="alLanguageSign">{{$applocale}}</span>
                  <span class="lnr-earth align-middle"></span>
                  <span class="language ml-1">{{ __("Language") }}</span>
                  </a>
                  <ul class="onhover-show-div">
                     @foreach($languageList as $key => $listl)
                     <li class="{{$applocale ==  $listl->language->sort_code ?  'active' : ''}}">
                        <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}
                        @if($listl->language->id != 1)
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
                        {{$listc->Currency->iso_code}}
                        </a>
                     </li>
                     @endforeach
                  </ul>
               </li>
               @if(Auth::user())
               <li>
                  <a href="{{route('user.logout')}}" data-lng="es">{{__('Logout')}}</a>
               </li>
               @endif
            </ul>
         </li>
      </ul>
   </div>
</section>
<!-- alSpaMenuCard end -->
<div class="top-header  al_custom_head">
   <!-- site-topbar -->
   <!-- <nav class="navbar navbar-expand-lg p-0 ">
      <div class="container ">
          <div class="row d-flex align-items-center justify-content-between w-100">
              <div class="col-lg-6 p-0 d-md-flex align-items-center justify-content-start"   >
                  <a class="navbar-brand mr-xl-3 mr-0" style="height:60px;" href="{{ route('userHome') }}"><img alt="" src="{{$urlImg}}"></a>
                  <div class="al_custom_head_map_box px-2 py-1 d-md-inline-flex  d-flex align-items-center justify-content-start">
                      @if(isset($preference))
                      @if(($preference->is_hyperlocal) && ($preference->is_hyperlocal == 1))
                      <div class=" col location-bar d-inline-flex align-items-center position-relative p-0 mr-2" href="#edit-address" data-toggle="modal">
                          <i class="fa fa-map-marker" aria-hidden="true"></i>
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

              <div class="col-lg-6 text-right ml-auto al_z_index p-0"  >
                  <ul class="header-dropdown ml-auto">
                      @if($client_preference_detail->header_quick_link == 1)
                      <li class="onhover-dropdown quick-links quick-links">
                          <span class="quick-links ml-1 align-middle">{{ __('Quick Links') }}</span>
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
                          <a href="javascript:void(0)">
                              <span class="alLanguageSign">{{$applocale}}</span>
                              <span class="icon-icLang align-middle"><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.59803 0H15.3954C16.3301 0 17.0449 0.714786 17.0449 1.64951V7.6977C17.0449 8.63242 16.3301 9.3472 15.3954 9.3472H9.3472V13.1961H5.66331L2.19934 16.0002V13.1961H1.64951C0.714786 13.1961 0 12.4813 0 11.5465V5.49836C0 4.56364 0.714786 3.84885 1.64951 3.84885H8.79737V2.74918H6.59803V0ZM5.66331 10.062L5.93822 10.9417H7.25783L5.44337 6.04819H4.12377L2.30931 10.9417H3.62891L3.95882 10.062H5.66331ZM12.1514 7.14786C12.8112 7.47776 13.5809 7.6977 14.2957 7.6977V6.59803C13.9658 6.59803 13.6359 6.54304 13.251 6.43308C14.0758 5.60832 14.5157 4.45367 14.4607 3.29901L14.4057 2.74918H12.5912V1.64951H11.4916V2.74918H9.84206V3.84885H13.1411C13.0861 4.6736 12.7012 5.38839 12.0964 5.88324C11.7115 5.55334 11.3816 5.16845 11.2166 4.6736H10.062C10.2269 5.33341 10.5568 5.93822 11.0517 6.43308C10.6668 6.54304 10.2819 6.59803 9.89704 6.59803L9.95202 7.6977C10.7218 7.64271 11.4916 7.47776 12.1514 7.14786ZM4.23384 9.12727L4.78368 7.42278L5.33351 9.12727H4.23384Z" fill="#777777"/></svg></span>
                              <span class="language ml-1">{{ __("Language") }}</span>
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
                      @endif

                      @if(count($currencyList) > 1)
                      <li class="onhover-dropdown change-currency">
                          <a href="javascript:void(0)">{{session()->get('iso_code')}}
                          <span class="icon-icCurrency align-middle"><svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.39724 0.142578H1.69458C1.26547 0.142578 0.917597 0.490456 0.917597 0.919564V2.05797C0.917597 2.48705 1.26547 2.83496 1.69458 2.83496H9.39724C9.82635 2.83496 10.1742 2.48708 10.1742 2.05797V0.919564C10.1742 0.490456 9.82638 0.142578 9.39724 0.142578ZM1.08326 4.57899H8.78588C9.21502 4.57899 9.56287 4.92687 9.5629 5.35598V5.94463C8.76654 6.24743 8.08369 6.70273 7.51822 7.2682L7.51514 7.27137H1.08326C0.654151 7.27137 0.306273 6.92349 0.306273 6.49439V5.35598C0.306273 4.92687 0.654151 4.57899 1.08326 4.57899ZM6.31719 9.0156H2.18655C1.75744 9.0156 1.40956 9.36347 1.40956 9.79258V10.931C1.40956 11.3601 1.75744 11.708 2.18655 11.708H5.8268C5.77784 10.8364 5.91763 9.95693 6.2739 9.11453C6.28796 9.08133 6.30256 9.04848 6.31719 9.0156ZM6.20036 13.452H0.776986C0.347878 13.452 0 13.7999 0 14.229V15.3674C0 15.7965 0.347878 16.1444 0.776986 16.1444H8.3093C7.38347 15.4994 6.63238 14.5788 6.20036 13.452ZM6.85635 11.3741C6.85635 8.74051 8.99127 6.60557 11.6249 6.60557C14.2584 6.60557 16.3933 8.74048 16.3934 11.3741C16.3934 14.0077 14.2585 16.1426 11.6249 16.1426C8.99127 16.1426 6.85635 14.0076 6.85635 11.3741Z" fill="#777777"/></svg></span>
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
                          <span class="alAccount">{{__('My Account')}}</span>
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
      </nav> -->
   <nav class="navbar navbar-expand-lg fixed-top px-0">
      <div class="container-fluid ">
         <div class="col-12  px-md-5 px-3">
            <div class="row d-flex align-items-center justify-content-between">
               <!-- leftHead start -->
               <div class="leftHead d-flex align-items-center col-sm-6">
                  <!-- logo start -->
                  <div class="logo">
                     <a class="navbar-brand position-relative" style="height:60px;" href="{{ route('userHome') }}">
                     <img class="alspalogo lightLogo" alt="" src="{{$urlImg}}"></a>
                     </a>
                  </div>
                  <!-- logo end -->
                  <!-- alFindSec start -->
                  <div class="alFindSec d-none d-lg-block">
                     <ul class="p-0 m-0 d-flex align-items-center justify-content-between">
                        @if(isset($preference))
                        @if(($preference->is_hyperlocal) && ($preference->is_hyperlocal == 1))
                        <li class="border-right mr-3 pr-3">
                           <div class="alLocation homepage-address" href="#edit-address" data-toggle="modal" >
                              <!-- data-toggle="modal" data-target="#googleMapModal" -->
                              <span data-placement="top" data-toggle="tooltip" title="{{session('selectedAddress')}}">{{session('selectedAddress')}}</span>
                           </div>
                        </li>
                        @endif
                        @endif
                        <li class="pr-4">
                           <div class="alChooseDate">
                              <div class='input-group date' id='datetimepicker2'>
                                 <input type='text' class="form-control" placeorder="Choose date" />
                                 <span class="input-group-addon">Choose date</span>
                              </div>
                           </div>
                        </li>
                        <li class="pr-0">
                           <div class="alFindGo">
                              <button class="btn">Go</button>
                           </div>
                        </li>
                     </ul>
                  </div>
                  <!-- alFindSec end -->
               </div>
               <!-- leftHead end -->
               <!-- rightHead start -->
               <div class="rightHead d-flex align-items-end col-sm-6">
                  <ul class="p-0 m-0 d-flex align-items-center ml-auto">
                     <!-- alUserIcon start -->
                     <li class="alUserIcon onhover-dropdown">
                        <a href="#">
                           <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M13.0151 9.27363C14.2948 8.34031 15.1282 6.82984 15.1282 5.1282C15.1282 2.30051 12.8277 0 9.99998 0C7.17228 0 4.87177 2.30051 4.87177 5.1282C4.87177 6.82984 5.70509 8.34031 6.98486 9.27363C3.80361 10.491 1.53845 13.5754 1.53845 17.1795C1.53845 18.7347 2.80373 20 4.35896 20H15.641C17.1962 20 18.4615 18.7347 18.4615 17.1795C18.4615 13.5754 16.1963 10.491 13.0151 9.27363ZM6.41025 5.1282C6.41025 3.14883 8.0206 1.53848 9.99998 1.53848C11.9794 1.53848 13.5897 3.14883 13.5897 5.1282C13.5897 7.10758 11.9794 8.71797 9.99998 8.71797C8.0206 8.71797 6.41025 7.10758 6.41025 5.1282ZM15.641 18.4615H4.35896C3.65205 18.4615 3.07693 17.8864 3.07693 17.1795C3.07693 13.362 6.18255 10.2564 10 10.2564C13.8175 10.2564 16.9231 13.362 16.9231 17.1795C16.9231 17.8864 16.3479 18.4615 15.641 18.4615Z" fill="white"/>
                           </svg>
                        </a>
                        <ul class="onhover-show-div p-2">
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
                     <!-- alUserIcon end -->
                     <!-- alShoppingBag start -->
                     @if($client_preference_detail)
                     @if($client_preference_detail->cart_enable==1)
                     <li class="alShoppingBag mx-4 dropdown">
                        <a href="#" class="alShopIcon" href="{{route('showCart')}}">
                           <span class="navbar-tool-label" id="cart_qty_span"> </span>
                           <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <g clip-path="url(#clip0_160_410)">
                                 <path d="M16 4H14C14 1.79 12.21 0 10 0C7.79 0 6 1.79 6 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H16C17.1 20 18 19.1 18 18V6C18 4.9 17.1 4 16 4ZM10 2C11.1 2 12 2.9 12 4H8C8 2.9 8.9 2 10 2ZM16 18H4V6H6V8C6 8.55 6.45 9 7 9C7.55 9 8 8.55 8 8V6H12V8C12 8.55 12.45 9 13 9C13.55 9 14 8.55 14 8V6H16V18Z" fill="white"/>
                              </g>
                              <defs>
                                 <clipPath id="clip0_160_410">
                                    <rect width="20" height="20" fill="white"/>
                                 </clipPath>
                              </defs>
                           </svg>
                        </a>
                        <script type="text/template" id="header_cart_template">
                           <div class='alShoppingList'>
                              <div class='widget widget-cart px-3 pt-2 pb-0' style="width: 20rem">
                                 <div class='simplebar-wrapper' style="height: 15rem">
                                       <div class='simplebar-content' style="padding: 0;">
                                          <% _.each(cart_details.products, function(product, key){%> <% _.each(product.vendor_products, function(vendor_product, vp){%>
                                          <li class='widget-cart-item pb-2 border-bottom' id="cart_product_<%=vendor_product.id %>" data-qty="<%=vendor_product.quantity %>">
                                             <div class='close-circle btn-close'>
                                                   <a href="javascript::void(0);" data-product="<%=vendor_product.id %>" class='remove-product text-danger'> <span aria-hidden="true">×</span></a>
                                             </div>
                                             <div class="d-flex align-items-center">
                                                   <a class='media flex-shrink-0' href='<%=show_cart_url %>'> <% if(vendor_product.pvariant.media_one){%>
                                                      <img class='mr-2 blur-up lazyload' data-src="<%=vendor_product.pvariant.media_one.pimage.image.path.proxy_url %>60/60<%=vendor_product.pvariant.media_one.pimage.image.path.image_path %>"> <%}
                              else if(vendor_product.pvariant.media_second && vendor_product.pvariant.media_second.image != null){%>
                                                      <img class='mr-2 blur-up lazyload' data-src="<%=vendor_product.pvariant.media_second.image.path.proxy_url %>60/60<%=vendor_product.pvariant.media_second.image.path.image_path %>"> <%}else{%>
                                                      <img class='mr-2 blur-up lazyload' data-src="<%=vendor_product.image_url %>"> <%}%>
                                                      <div class='media-body ps-2'>
                                                         <h4 class='widget-product-title'><%=vendor_product.product.translation_one ? vendor_product.product.translation_one.title : vendor_product.product.sku %></h4>
                                                         <h4 class='widget-product-meta'> <span class='text-muted'><%=vendor_product.quantity %> x <%=Helper.formatPrice(vendor_product.pvariant.price * vendor_product.pvariant.multiplier) %></span> </h4>
                                                      </div>
                                                   </a>
                                             </div>
                                          </li>
                                          <%}); %> <%}); %>
                                       </div>
                                 </div>
                                 <div class='d-flex flex-wrap justify-content-between align-items-center pt-3'>
                                       <div class='total'>
                                          <h5>{{__('Subtotal')}}: <span class='text-muted' id='totalCart'>{{Session::get('currencySymbol')}}<%=Helper.formatPrice(cart_details.gross_amount) %></span></h5>
                                       </div>
                                       <div class='buttons'><a href="<%=show_cart_url %>" class='view-cart'>{{__('View Cart')}}</a></div>
                                 </div>
                              </div>
                           </div>
                        </script>
                        <ul class="show-div shopping-cart " id="header_cart_main_ul"></ul>
                     </li>
                     <!-- alShoppingBag end -->
                     @endif @endif
                     <!-- alMenuIcon start -->
                     <li class="alMenuIcon">
                        <a href="#" class="alHamBurgerIcon">
                           <svg width="34" height="16" viewBox="0 0 34 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M34 1C34 1.55228 33.5523 2 33 2H1C0.447715 2 0 1.55228 0 1C0 0.447715 0.447716 0 1 0H33C33.5523 0 34 0.447715 34 1Z" fill="white"/>
                              <path class="alSmMenu" fill-rule="evenodd" clip-rule="evenodd" d="M27.8109 8C27.8109 8.55228 27.3632 9 26.8109 9H7.18921C6.63692 9 6.18921 8.55228 6.18921 8C6.18921 7.44772 6.63692 7 7.18921 7H26.8109C27.3632 7 27.8109 7.44772 27.8109 8Z" fill="white"/>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M34 15C34 15.5523 33.5523 16 33 16H1C0.447715 16 0 15.5523 0 15C0 14.4477 0.447716 14 1 14H33C33.5523 14 34 14.4477 34 15Z" fill="white"/>
                           </svg>
                        </a>
                     </li>
                     <!-- alMenuIcon end -->
                  </ul>
               </div>
               <!-- rightHead end -->
            </div>

         </div>
      </div>
   </nav>
</div>