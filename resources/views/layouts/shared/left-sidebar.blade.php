<div class="left-side-menu">
    <div class="logo-box m-hide d-lg-block">
        @php
            $urlImg = URL::to('/').'/assets/images/users/user-1.jpg';
            $clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
            if($clientData){
                $urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
            }
            $marketing_permissions = array("banner", "promocode", "loyalty_cards");
            $subscription_permissions = array("subscription_plans_customers", "subscription_plans_vendors");
            $extra_permissions = array("celebrity", "inquiries");
            $setting_permissions = array("profile", "customize", "app_styling", "web_styling", "catalog", "configurations", "tax", "payment");
            $styling_permissions = array("app_styling", "web_styling");
            $order_permissions = array("dashboard", "orders", "vendors", "accounting_orders","accounting_loyality", "accounting_promo_codes", "accounting_taxes","accounting_vendors", "subscriptions_customers", "subscriptions_vendors", "customers");
            $accounting_permissions = array("accounting_orders", "accounting_loyality", "accounting_promo_codes", "accounting_taxes", "accounting_vendors");
        @endphp
        <a href="{{route('client.dashboard')}}" class="logo logo-dark text-center">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="20">
            </span>
        </a>

        <a href="{{route('client.dashboard')}}" class="logo logo-light text-center">
            <span class="logo-sm">
                <img src="{{$urlImg}}"
                    alt="" height="30" style="padding-top: 4px;">
            </span>
            <span class="logo-lg">
                <img src="{{$urlImg}}"
                    alt="" height="50" style="padding-top: 4px;">
            </span>
        </a>
    </div>
    <div class="h-100" data-simplebar>
        <div class="user-box text-center">
            <img src="{{asset('assets/images/users/user-1.jpg')}}" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block" data-toggle="dropdown">User</a>
                <div class="dropdown-menu user-pro-dropdown">
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user mr-1"></i>
                        <span>My Account</span>
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out mr-1"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div>
        <div id="sidebar-menu">
            <?php
            $allowed = [];
            if (Auth::user()->is_superadmin == 0) {
                foreach (Auth::user()->getAllPermissions as $value) {
                    array_push($allowed, $value->permission->slug);
                }
            } else {
                array_push($allowed, '99999');
            }
            ?>
            <ul id="side-menu">
                @php
                    $client_preference = \App\Models\ClientPreference::where(['id' => 1])->first();
                @endphp
                 @if(count(array_intersect($order_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                <li>
                    <a class="menu-title pl-1" href="#">
                        <!-- <span class="icon-orders"></span> -->
                        <span>{{ __('ORDERS') }}</span>
                    </a>
                    <ul class="nav-second-level p-0 mx-2">
                            @if(in_array('dashboard',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('client.dashboard')}}">
                                        <span class="icon-dash"></span>
                                        <span>{{ __('Dashboard') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('orders',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('order.index')}}">
                                        <span class="icon-orders"></span>
                                        <span> {{ __('Orders') }} </span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('vendors',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('vendor.index')}}">
                                    <span class="icon-vendor"></span>
                                    @php
                                        $vendormenu = getNomenclatureName('Vendors', true);
                                        $vendormenulabel = ($vendormenu=="Vendors")?__('Vendors'):$vendormenu;

                                    @endphp
                                        {{-- <span>{{getNomenclatureName('Vendors', true)}}</span> --}}
                                        <span>{{ $vendormenulabel }}</span>
                                    </a>
                                </li>
                            @endif
                            @if(count(array_intersect($accounting_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="#sidebaraccounting" data-toggle="collapse">
                                    <span class="icon-accounting"></span>
                                        <span> {{ __('Accounting') }} </span>
                                    </a>
                                    <div class="collapse" id="sidebaraccounting">
                                        <ul class="nav-second-level">
                                            @if(in_array('accounting_orders',$allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('account.orders')}}">{{ __('Orders') }}</a>
                                                </li>
                                            @endif
                                            @if(in_array('accounting_loyality',$allowed) || Auth::user()->is_superadmin == 1)
                                                <li>

                                                @php
                                                    $loyaltyCards = getNomenclatureName('Loyalty Cards', true);
                                                    $loyaltyCardsLabel = ($loyaltyCards=="Loyalty Cards")?__('Loyalty Cards'):$loyaltyCards;
                                                @endphp
                                                    <a href="{{route('account.loyalty')}}">{{ $loyaltyCardsLabel }}</a>
                                                </li>
                                            @endif
                                            @if(in_array('accounting_promo_codes',$allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('account.promo.code')}}">{{ __('Promo Codes') }}</a>
                                                </li>
                                            @endif
                                            @if(in_array('accounting_taxes',$allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('account.tax')}}">{{ __('Taxes') }}</a>
                                                </li>
                                            @endif
                                            @if(in_array('accounting_vendors',$allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    @php
                                                    $Vendors = getNomenclatureName('Vendors', true);
                                                    $VendorsTrans = ($Vendors=="Vendors")?__('Vendors'):$Vendors;
                                                @endphp
                                                    <a href="{{route('account.vendor')}}">{{ $VendorsTrans }}</a>
                                                </li>
                                            @endif
                                            @if(Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('account.vendor.payout.requests')}}">{{ __('Payout Requests') }}</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if(Auth::user()->is_superadmin == 1)
                            {{-- @if(count(array_intersect($subscription_permissions, $allowed)) || Auth::user()->is_superadmin == 1) --}}
                                @if($client_preference->subscription_mode == 1)
                                    <li>
                                        <a href="#sidebarsubscriptions" data-toggle="collapse">
                                            <span class="icon-subscribe"></span>
                                            <span> {{ __('Subscriptions') }}</span>
                                        </a>
                                        <div class="collapse" id="sidebarsubscriptions">
                                            <ul class="nav-second-level">
                                                @if(in_array('subscription_plans_customers',$allowed) || Auth::user()->is_superadmin == 1)
                                                    <li>
                                                        <a href="{{route('subscription.plans.user')}}">{{ __('Customers') }}</a>
                                                    </li>
                                                @endif
                                                @if(in_array('subscription_plans_vendors',$allowed) || Auth::user()->is_superadmin == 1)
                                                    <li>
                                                        <a href="{{route('subscription.plans.vendor')}}">{{ $VendorsTrans }}</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                            @endif
                            {{-- @if(in_array('customers',$allowed) || Auth::user()->is_superadmin == 1) --}}
                            @if(Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('customer.index')}}">
                                        <span class="icon-customer-2"></span>
                                        <span> {{ __('Customers') }} </span>
                                    </a>
                                </li>
                            @endif
                             <li>
                                <a href="{{route('review.index')}}">
                                    <span class="icon-customer alproduct">
                                        <svg style="height:18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M17.79.43A1.26,1.26,0,0,0,17.16,0H2.74L2.69,0A1,1,0,0,0,1.9,1.13V6.87A1,1,0,0,0,3,7.94h.59a.35.35,0,0,0,.38-.35.37.37,0,0,0-.37-.35H3.06c-.32,0-.46-.13-.46-.44V1.15c0-.31.14-.45.45-.45h13.8c.31,0,.45.14.45.46V6.78c0,.33-.14.46-.46.46-.53,0-1.06,0-1.58,0a1.44,1.44,0,0,0-1.4.59,8.56,8.56,0,0,1-.67.68c-.36-.36-.7-.69-1-1a.7.7,0,0,0-.55-.22H9a1.86,1.86,0,0,0-.89-1,5.36,5.36,0,0,0-.79-.3c-.34-.11-.48,0-.6.3L6,8.2a2.83,2.83,0,0,1-.62,1,5.14,5.14,0,0,1-1.34.87.22.22,0,0,1-.14,0,1,1,0,0,0-.7-.28H1a1,1,0,0,0-1,1V17a1,1,0,0,0,1,1h2.3A.92.92,0,0,0,4,17.67a.2.2,0,0,1,.27-.06A4.11,4.11,0,0,0,5.79,18c1.7,0,3.41,0,5.11,0a1.24,1.24,0,0,0,1.22-1.23,4.53,4.53,0,0,0-.11-.71,1.34,1.34,0,0,0,.77-.76,1.31,1.31,0,0,0-.09-1.11,1.29,1.29,0,0,0,.84-1.26,1.26,1.26,0,0,0-.9-1.21,1.31,1.31,0,0,0-1.2-1.93H8.68c.11-.32.22-.61.3-.91s.16-.6.24-.92h2.31a.31.31,0,0,1,.19.09c.38.37.76.75,1.13,1.13s.42.25.68,0l1.12-1.11a.37.37,0,0,1,.24-.1h2.05a1,1,0,0,0,1-.62L18,7.1V.84C17.93.71,17.87.56,17.79.43ZM3.55,12.61v4.22c0,.38-.09.47-.46.47h-2c-.31,0-.42-.11-.42-.43v-6c0-.32.1-.42.42-.42h.65v4.16a1.94,1.94,0,0,0,0,.24.34.34,0,0,0,.67,0,1.09,1.09,0,0,0,0-.26V10.49h.76c.21,0,.3.14.3.36Zm7.79-2.12a1.34,1.34,0,0,1,.35,0,.56.56,0,0,1,.38.6.54.54,0,0,1-.48.51c-.23,0-.48,0-.72,0H9.56a.32.32,0,0,0-.32.32.33.33,0,0,0,.27.36.82.82,0,0,0,.22,0h2.48a.58.58,0,0,1,.48.21.53.53,0,0,1,.09.6.58.58,0,0,1-.56.36H9.66c-.23,0-.38.1-.41.28a.34.34,0,0,0,.38.42h1.81l.25,0a.58.58,0,0,1,.46.6.55.55,0,0,1-.54.54c-.62,0-1.24,0-1.86,0a.93.93,0,0,0-.24,0,.31.31,0,0,0-.27.32.33.33,0,0,0,.25.34.83.83,0,0,0,.22,0H10.8a.59.59,0,0,1,.63.58.61.61,0,0,1-.62.59h-1c-1.21,0-2.41,0-3.62,0a4.56,4.56,0,0,1-1.89-.41.21.21,0,0,1-.09-.15c0-1.94,0-3.87,0-5.8a.25.25,0,0,1,.11-.17c.34-.2.7-.38,1-.59A3.22,3.22,0,0,0,6.68,8.51l.6-1.64c0-.06,0-.12.07-.2a1.32,1.32,0,0,1,1,1.77c-.16.49-.32,1-.49,1.46s0,.59.43.59Z"/><path class="cls-1" d="M11.44,4.32c.12-.11.23-.22.34-.34a.54.54,0,0,0,.13-.58A.54.54,0,0,0,11.45,3,3.81,3.81,0,0,1,11,3a.38.38,0,0,1-.19-.14c-.08-.12-.14-.27-.21-.4a.56.56,0,0,0-.52-.35.6.6,0,0,0-.53.35l-.13.27A.35.35,0,0,1,9,3c-.11,0-.22,0-.33,0a.55.55,0,0,0-.49.38A.56.56,0,0,0,8.36,4c.11.11.24.21.34.33a.38.38,0,0,1,.07.21c0,.19-.06.38-.08.52a.57.57,0,0,0,.79.58c.19-.09.38-.27.58-.27s.39.18.59.27a.55.55,0,0,0,.78-.55,3,3,0,0,0-.08-.52A.24.24,0,0,1,11.44,4.32Zm-.76.54a.94.94,0,0,0-1.23,0,.93.93,0,0,0-.39-1.17.93.93,0,0,0,1-.73.92.92,0,0,0,1,.72A.94.94,0,0,0,10.68,4.86Z"/><path class="cls-1" d="M7.32,4A.56.56,0,0,0,7,3C6.83,3,6.66,3,6.51,3a.36.36,0,0,1-.19-.13c-.09-.15-.15-.31-.24-.46a.56.56,0,0,0-1,0,3.49,3.49,0,0,0-.19.39A.31.31,0,0,1,4.6,3a2.47,2.47,0,0,0-.38,0,.56.56,0,0,0-.48.38.56.56,0,0,0,.14.6c.12.12.24.22.35.34a.28.28,0,0,1,.06.2,4.86,4.86,0,0,1-.07.51.57.57,0,0,0,.79.6c.16-.07.31-.17.47-.24a.28.28,0,0,1,.22,0c.18.07.34.18.52.25A.54.54,0,0,0,7,5.14c0-.2-.06-.4-.08-.61a.3.3,0,0,1,.06-.18C7.06,4.22,7.2,4.1,7.32,4ZM6.2,4.86A.86.86,0,0,0,5,4.86a1.92,1.92,0,0,0,0-.64,2,2,0,0,0-.36-.52,1.72,1.72,0,0,0,.58-.19A2.28,2.28,0,0,0,5.61,3a.9.9,0,0,0,1,.71A1,1,0,0,0,6.2,4.86Z"/><path class="cls-1" d="M15.92,4.3A2,2,0,0,0,16.25,4c.08-.11.13-.25.2-.37A.62.62,0,0,0,15.94,3,3.81,3.81,0,0,0,15.47,3a.23.23,0,0,1-.22-.16A4.16,4.16,0,0,0,15,2.33a.55.55,0,0,0-.95,0,1.79,1.79,0,0,0-.2.39.33.33,0,0,1-.35.25,1.76,1.76,0,0,0-.39.06.54.54,0,0,0-.45.66.69.69,0,0,0,.23.37.67.67,0,0,1,.29.84.18.18,0,0,0,0,.1.56.56,0,0,0,.2.58.53.53,0,0,0,.61,0,3.17,3.17,0,0,0,.41-.21.26.26,0,0,1,.3,0,3,3,0,0,0,.47.24.56.56,0,0,0,.75-.6c0-.15-.05-.3-.08-.45A.27.27,0,0,1,15.92,4.3Zm-.76.56a.94.94,0,0,0-1.24,0,.9.9,0,0,0-.39-1.15,2.09,2.09,0,0,0,.64-.21A2.35,2.35,0,0,0,14.56,3a.88.88,0,0,0,1,.71A.93.93,0,0,0,15.16,4.86Z"/><path class="cls-1" d="M4.73,7.59a.35.35,0,1,0,.35-.35A.35.35,0,0,0,4.73,7.59Z"/><path class="cls-1" d="M2.12,15.72a.39.39,0,0,0-.35.36.37.37,0,0,0,.36.36.37.37,0,0,0,.35-.36A.38.38,0,0,0,2.12,15.72Z"/></g></g></svg>
                                    </span>
                                    <span> {{ __('Product Reviews') }} </span>
                                </a>
                            </li>
                        </ul>
                </li>
                @endif
                @if(Auth::user()->is_superadmin == 1)
                {{-- @if(count(array_intersect($setting_permissions, $allowed)) || Auth::user()->is_superadmin == 1) --}}
                <li>
                   <a class="menu-title pl-1" href="#">
                        <!-- <span class="icon-settings-1-1"></span> -->
                        <span>{{ __('SETTINGS') }}</span>
                    </a>
                    <ul class="nav-second-level">
                        @if(in_array('profile',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('client.profile')}}">
                                    <span class="icon-profile"></span>
                                    <span> {{ __('Profile') }} </span>
                                </a>
                            </li>
                        @endif
                        @if(in_array('customize',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('configure.customize')}}">
                                    <span class="icon-customzie"></span>
                                    <span> {{ __('Customize') }} </span>
                                </a>
                            </li>
                        @endif
                        @if(count(array_intersect($styling_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="#sidebarstyling" data-toggle="collapse">
                                    <span class="icon-styling"></span>
                                    <span> {{ __('Styling') }} </span>
                                </a>
                                <div class="collapse" id="sidebarstyling">
                                    <ul class="nav-second-level">
                                        @if(in_array('app_styling',$allowed) || Auth::user()->is_superadmin == 1)
                                            <li>
                                                <a href="{{route('appStyling.index')}}">{{ __('App Styling') }}</a>
                                            </li>
                                        @endif
                                        @if(in_array('web_styling',$allowed) || Auth::user()->is_superadmin == 1)
                                            <li>
                                                <a href="{{route('webStyling.index')}}">{{ __('Web Styling') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif
                        <li>
                            <a href="#sidebarcms" data-toggle="collapse">
                                <span class="icon-cms"></span>
                                <span>{{ __("CMS") }}</span>
                            </a>
                            <div class="collapse" id="sidebarcms">
                                <ul class="nav-second-level">
                                    @if(in_array('cms_pages',$allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{route('cms.pages')}}">{{ __('Pages') }}</a>
                                        </li>
                                    @endif
                                    @if(in_array('cms_emails',$allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{route('cms.emails')}}">{{ __('Emails') }}</a>
                                        </li>
                                    @endif
                                    @if(in_array('cms_notifications',$allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{route('cms.notifications')}}">{{ __('Notifications') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @if(in_array('catalog',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('category.index')}}">
                                    <span class="icon-catalogue"></span>
                                    <span> {{ __('Catalog') }}</span>
                                </a>
                            </li>
                        @endif
                        @if(in_array('configurations',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('configure.index')}}">
                                    <span class="icon-configuration"></span>
                                    <span> {{ __('Configurations') }} </span>
                                </a>
                            </li>
                        @endif

                        @if($client_preference_detail->get_estimations == 1)
                            <li>
                                <a href="{{route('estimations.index')}}">
                                    <span class="icon-configuration"></span>
                                    <span> {{ __('Estimations') }} </span>
                                </a>
                            </li>
                        @endif

                        @if(in_array('tax',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('tax.index')}}">
                                    <span class="icon-tax"></span>
                                    <span> {{ __('Tax') }} </span>
                                </a>
                            </li>
                        @endif
                        @if(in_array('payment',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('payoption.index')}}">
                                    <span class="icon-payment-options"></span>
                                    <span> {{ __('Payment Options') }} </span>
                                </a>
                            </li>
                        @endif


                        @if(in_array('DeliveryOption',$allowed) || Auth::user()->is_superadmin == 1)
                        <li>
                            <a href="{{route('deliveryoption.index')}}">
                                <span class="icon-payment-option_s aldelivery">
                                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 210.94 53.84"><defs><style>.cls-1{fill:#6e768e;}.cls-2{fill:#6e768e;}</style></defs><path class="cls-1" d="M292.89,60.22c-1-1.15-.87-2.48-.67-3.83a2.52,2.52,0,0,0,.67.09Z" transform="translate(-81.94 -6.38)"/><path class="cls-2" d="M91.26,27.67H99c.93,0,1.49.48,1.51,1.28a1.32,1.32,0,0,1-1.48,1.38q-7.81,0-15.62,0A1.32,1.32,0,0,1,81.94,29c0-.81.59-1.31,1.55-1.31Z" transform="translate(-81.94 -6.38)"/><path class="cls-2" d="M92.53,19.7H99c1,0,1.54.5,1.54,1.32A1.34,1.34,0,0,1,99,22.37H86.14a1.35,1.35,0,0,1-1.49-1A1.26,1.26,0,0,1,85.12,20a1.43,1.43,0,0,1,1-.27Z" transform="translate(-81.94 -6.38)"/><path class="cls-2" d="M93.89,11.72h5.17a1.34,1.34,0,1,1,0,2.66c-3.46,0-6.93,0-10.39,0a1.32,1.32,0,0,1-1.46-1.34c0-.81.58-1.32,1.53-1.32Z" transform="translate(-81.94 -6.38)"/><path class="cls-2" d="M145.64,24.79a5.14,5.14,0,0,0-.06-.55,12,12,0,0,0-11.94-9.88h-7.28c-.22,0-.45.08-.66-.07.47-2.07.93-4.13,1.4-6.18a1.27,1.27,0,0,0-.1-1,1.54,1.54,0,0,0-1.57-.76h-31c-1.19,0-1.86.5-1.85,1.37s.67,1.34,1.87,1.34h29.1c.43,0,.61,0,.48.56q-1.86,8.08-3.68,16.18c-.71,3.11-1.41,6.21-2.12,9.31-.06.26-.08.53-.51.52-2.08,0-4.16,0-6.23,0-.31,0-.42-.11-.5-.4a6.63,6.63,0,0,0-12.82,0,.5.5,0,0,1-.6.43c-1.19,0-2.37,0-3.55,0a1.24,1.24,0,0,0-.88.26,1.28,1.28,0,0,0-.51,1.37,1.3,1.3,0,0,0,1.31,1c1.21,0,2.41,0,3.62,0,.39,0,.52.13.62.49a6.65,6.65,0,0,0,12.78,0c.11-.38.24-.5.62-.49h15.2c.35,0,.53.06.64.45a6.64,6.64,0,0,0,12.79,0c.1-.33.21-.48.58-.46a12.51,12.51,0,0,0,1.62,0,1.29,1.29,0,0,0,1.37-1.18c.42-2.23.81-4.48,1.26-6.71a29.78,29.78,0,0,1,.74-3.62V25.43C145.6,25.24,145.76,25,145.64,24.79ZM104.55,40.94a4,4,0,1,1,0-7.92,4,4,0,0,1,0,7.92Zm29.21,0a4,4,0,1,1,4-3.91A4,4,0,0,1,133.76,40.94Zm9-12.8c-.43,2.34-.89,4.68-1.32,7-.05.28-.05.54-.45.5s-.62.08-.77-.43a6.63,6.63,0,0,0-6.42-4.91,6.71,6.71,0,0,0-6.39,4.89c-.06.18,0,.46-.37.46-2,0-4.06,0-6.13,0,.46-2.07.92-4.09,1.37-6.11.9-3.95,1.81-7.89,2.69-11.83.1-.47.23-.67.77-.66,2.68,0,5.36,0,8,0A9.31,9.31,0,0,1,143,24.52,8.82,8.82,0,0,1,142.72,28.14Z" transform="translate(-81.94 -6.38)"/></svg>
                                </span>
                                <span> {{ __('Delivery Options') }} </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(count(array_intersect($marketing_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                <li>
                    <a class="menu-title pl-1" href="#">
                        <!-- <span class="icon-marketing"></span> -->
                        <span>{{ __('MARKETING') }}</span>
                    </a>
                    <ul class="nav-second-level">

                        @if(Auth::user()->is_superadmin == 1)
                        <li>
                            <a href="#sidebarbanner" data-toggle="collapse">
                                <span class="icon-styling"></span>
                                <span> {{ __('Banners') }} </span>
                            </a>
                            <div class="collapse" id="sidebarbanner">
                                <ul class="nav-second-level">
                                        @if($client_preference_detail->business_type != 'taxi')
                                        <li>
                                            <a href="{{route('banner.index')}}">{{ __('Web Banners') }}</a>
                                        </li>
                                        @endif
                                         <li>
                                            <a href="{{route('mobilebanner.index')}}">{{ __('Mobile Banners') }}</a>
                                        </li>
                                </ul>
                            </div>
                        </li>
                        @endif



                        @if(in_array('promocode',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('promocode.index')}}">
                                    <span class="icon-discount-voucher"></span>
                                    <span> {{ __('Promocode') }} </span>
                                </a>
                            </li>
                        @endif
                        @if(in_array('loyalty_cards',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('loyalty.index')}}">
                                    <span class="icon-loyaltycard"></span>
                                    @php
                                        $LoyaltyCards =  getNomenclatureName('Loyalty Cards', true)

                                    @endphp
                                    <span> {{ $LoyaltyCards === "Loyalty Cards" ?  __("Loyalty Cards")  : $LoyaltyCards }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(count(array_intersect($extra_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                    @if($client_preference->celebrity_check == 1 || $client_preference->enquire_mode == 1)
                        <li>
                            <a class="menu-title pl-1">
                                <!-- <span class="icon-extra"></span> -->
                                <span>{{ __("EXTRA") }}</span>
                            </a>
                            <ul class="nav-second-level">
                                @if(Auth::user()->is_superadmin == 1 && $client_preference->celebrity_check == 1)
                                    @if(in_array('celebrity',$allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{ route('celebrity.index') }}">
                                                <span class="icon-celebrity"></span>
                                                <span> {{ __("Celebrities") }} </span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                @if(!empty($client_preference) && $client_preference->enquire_mode == 1)
                                    @if(in_array('inquiries',$allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{ route('inquiry.index') }}">
                                                <span class="icon-question"></span>
                                                <span> {{ __("Inquiries") }} </span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                @if(in_array('tools',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('tools.index')}}">
                                        <span class="icon-settings-1-1"></span>
                                        <span> {{ __('Tools') }} </span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
    </div>
</div>
