<div class="left-side-menu">
    <div class="logo-box m-hide d-lg-block">
        @php
            $urlImg = URL::to('/').'/assets/images/users/user-1.jpg';
            $clientData = \App\Models\Client::select('id', 'logo','socket_url')->where('id', '>', 0)->first();
            if($clientData){
                $urlImg = $clientData ? $clientData->logo['original'] : ' ';
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

                                {{-- <li>
                                    <a href="#sidebarorders" data-toggle="collapse">
                                    <span class="icon-accounting"></span>
                                        <span> {{ __('Orders') }} </span>
                                    </a>
                                    <div class="collapse" id="sidebarorders">
                                        <ul class="nav-second-level">

                                                <li>
                                                    <a href="{{route('order.index')}}">{{ __('All Orders') }}</a>
                                                </li>

                                                <li>
                                                    <a href="{{route('backend.order.returns',['Pending'])}}">{{ __("Return Request") }}</a>
                                                </li>

                                                <li>
                                                    <a href="{{route('cancel-order.requests')}}">{{ __("Cancel Order Request") }}</a>
                                                </li>
                                        </ul>
                                    </div>
                                </li> --}}
                            @endif
                            @if(in_array('vendors',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('vendor.index')}}">
                                    <span class="icon-vendor"></span>
                                    @php
                                        $vendormenu = getNomenclatureName('Vendors', true);
                                        $vendormenulabel = ($vendormenu=="Vendors")?__('Vendors'):__($vendormenu);

                                    @endphp
                                        {{-- <span>{{getNomenclatureName('Vendors', true)}}</span> --}}
                                        <span>{{ __($vendormenulabel) }}</span>
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
                                                    <a href="{{route('account.vendor')}}">{{ __($VendorsTrans) }}</a>
                                                </li>
                                            @endif
                                            @if(Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('account.vendor.payout.requests')}}">{{ __('Payout Requests') }}</a>
                                                </li>
                                                <li>
                                                    <a href="{{route('backend.order.refund')}}">{{ __('Order Refunds') }}</a>
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
                                                        <a href="{{route('subscription.plans.vendor')}}">{{ __($VendorsTrans) }}</a>
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

                            @if(Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="#sidebarreports" data-toggle="collapse">
                                <span class="mdibookoutline"><?xml version="1.0" encoding="iso-8859-1"?><!-- Generator: Adobe Illustrator 16.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0) --><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="41.833px" height="41.833px" viewBox="0 0 41.833 41.833" style="enable-background:new 0 0 41.833 41.833;" xml:space="preserve"><g><g><path d="M2.5,31.458h15.792v3.333H15.75c-0.276,0-0.5,0.226-0.5,0.5v3.167c0,0.275,0.224,0.5,0.5,0.5h10.333c0.274,0,0.5-0.225,0.5-0.5v-3.167c0-0.274-0.226-0.5-0.5-0.5h-2.541v-3.333h15.791c1.379,0,2.5-1.122,2.5-2.5V5.375c0-1.378-1.121-2.5-2.5-2.5H2.5c-1.378,0-2.5,1.122-2.5,2.5v23.583C0,30.336,1.122,31.458,2.5,31.458z M16.25,37.958v-0.583h9.333v0.583H16.25z M25.583,36.375H16.25v-0.583h2.542h4.25h2.543L25.583,36.375L25.583,36.375z M22.542,34.792h-3.25v-3.333h3.25V34.792z M39.333,30.458H23.042h-4.25H2.5c-0.827,0-1.5-0.673-1.5-1.5v-1.75h39.833v1.75C40.833,29.786,40.159,30.458,39.333,30.458z M2.5,3.875h36.833c0.826,0,1.5,0.673,1.5,1.5v20.833H1V5.375C1,4.547,1.673,3.875,2.5,3.875z"/><path d="M7.667,25.125c0.138,0,0.276-0.059,0.375-0.169l6.642-7.512l3.457,3.57c0.003,0.004,0.006,0.004,0.009,0.006c0.003,0.004,0.004,0.008,0.006,0.01c0.031,0.029,0.068,0.047,0.103,0.064c0.019,0.012,0.035,0.026,0.055,0.035c0.06,0.023,0.123,0.037,0.187,0.037s0.128-0.014,0.189-0.038c0.02-0.008,0.036-0.024,0.056-0.036c0.035-0.021,0.072-0.037,0.103-0.066c0.003-0.002,0.003-0.006,0.006-0.01c0.003-0.002,0.006-0.002,0.009-0.006l5.24-5.519l4.084,2.119c0.008,0.004,0.019,0.003,0.025,0.006c0.064,0.03,0.135,0.05,0.205,0.05c0.08,0,0.158-0.025,0.23-0.064c0.021-0.012,0.039-0.031,0.063-0.047c0.029-0.022,0.063-0.038,0.088-0.067l6.834-8.125c0.179-0.211,0.148-0.527-0.063-0.705c-0.211-0.177-0.523-0.15-0.703,0.061l-6.574,7.818l-4.064-2.108c-0.01-0.005-0.02-0.004-0.024-0.008c-0.033-0.015-0.065-0.022-0.104-0.029c-0.027-0.006-0.058-0.014-0.086-0.015c-0.031-0.001-0.063,0.006-0.097,0.011c-0.03,0.005-0.063,0.009-0.095,0.021c-0.026,0.01-0.053,0.027-0.075,0.042c-0.031,0.018-0.063,0.036-0.088,0.061c-0.009,0.007-0.019,0.009-0.022,0.017l-5.141,5.414l-3.47-3.583c-0.005-0.005-0.012-0.007-0.017-0.011c-0.005-0.005-0.007-0.011-0.012-0.015c-0.02-0.018-0.045-0.025-0.067-0.039c-0.029-0.019-0.056-0.039-0.088-0.051c-0.03-0.011-0.06-0.014-0.091-0.019c-0.032-0.005-0.062-0.013-0.095-0.013c-0.033,0.001-0.065,0.01-0.097,0.017c-0.03,0.007-0.059,0.01-0.088,0.023c-0.031,0.013-0.057,0.035-0.086,0.054c-0.022,0.015-0.047,0.023-0.067,0.042c-0.005,0.005-0.007,0.012-0.011,0.017c-0.005,0.005-0.011,0.007-0.015,0.012l-7,7.917C7.109,24.5,7.128,24.814,7.335,25C7.432,25.083,7.549,25.125,7.667,25.125z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span>
                                <span> {{ __('Reports') }} </span>
                                </a>
                                <div class="collapse" id="sidebarreports">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{route('review.index')}}">{{ __('Product Reviews') }}</a>
                                        </li>

                                        <li>
                                            <a href="{{route('report.productperformance')}}">{{ __("Product Performance Report") }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>


                            @endif
                            @if(@$clientData->socket_url)
                                <li>
                                    <a href="#chat" data-toggle="collapse">
                                        <span class="mdichat"><?xml version="1.0" encoding="iso-8859-1"?><!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0) --><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 224.376 224.376" style="enable-background:new 0 0 224.376 224.376;" xml:space="preserve"><g><g><g><path d="M168.024,46.027c-9.418,0-18.59,2.312-26.789,6.723c-9.18-16.859-26.973-27.531-46.496-27.531c-24.988,0-46.648,17.703-51.73,41.703C19.043,68.399,0,88.251,0,112.441c0,25.148,20.582,45.609,45.879,45.609h10.516v41.106l36.59-41.106l75.926-0.008c30.582-0.465,55.465-25.59,55.465-56.004C224.375,71.152,199.098,46.027,168.024,46.027z M168.848,150.051h-79.45l-25.004,28.09v-28.09H45.879C24.992,150.051,8,133.18,8,112.441c0-20.738,16.992-37.609,37.879-37.609h3.961l0.496-3.43c3.129-21.77,22.219-38.184,44.402-38.184c17.863,0,34.012,10.508,41.144,26.774l1.887,4.301l3.945-2.547c7.832-5.051,16.926-7.719,26.308-7.719c26.66,0,48.352,21.539,48.352,48.012C216.375,128.105,195.027,149.644,168.848,150.051z"/><path d="M78.188,90.052c-7.719,0-14,6.281-14,14c0,7.719,6.281,14,14,14s14-6.281,14-14C92.188,96.332,85.906,90.052,78.188,90.052z M78.188,110.052c-3.308,0-6-2.692-6-6c0-3.308,2.692-6,6-6c3.308,0,6,2.692,6,6C84.188,107.36,81.496,110.052,78.188,110.052z"/><path d="M118.188,90.052c-7.719,0-14,6.281-14,14c0,7.719,6.281,14,14,14s14-6.281,14-14C132.188,96.332,125.906,90.052,118.188,90.052z M118.188,110.052c-3.308,0-6-2.692-6-6c0-3.308,2.692-6,6-6c3.308,0,6,2.692,6,6C124.188,107.36,121.496,110.052,118.188,110.052z"/><path d="M158.188,90.052c-7.719,0-14,6.281-14,14c0,7.719,6.281,14,14,14s14-6.281,14-14C172.188,96.332,165.906,90.052,158.188,90.052z M158.188,110.052c-3.308,0-6-2.692-6-6c0-3.308,2.692-6,6-6c3.308,0,6,2.692,6,6C164.188,107.36,161.496,110.052,158.188,110.052z"/></g></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span>
                                        <span> {{ __('Chat') }} </span>
                                    </a>
                                    <div class="collapse" id="chat">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{route('chat.VendorUserChat')}}">{{ __('User/Vendor') }}</a>
                                            </li>
                                            @if(Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('chat.userAgentChatRoom')}}">{{ __('User/Driver') }}</a>
                                                </li>
                                                @endif
                                            {{-- <li>
                                                <a href="{{route('report.productperformance')}}">{{ __("Product Performance Report") }}</a>
                                            </li> --}}
                                        </ul>
                                    </div>
                                </li>
                            @endif
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
                    <ul class="nav-second-level p-0 mx-2">
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
                                    @if(in_array('cms_sms',$allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{route('cms.sms')}}">{{ __('SMS') }}</a>
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
                        {{-- @if(Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('verifyoption.index')}}">
                                    <span class="icon-verification-options">
                                        <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill="#555" d="M2.72727273,3.33333333 C1.97415716,3.33333333 1.36363636,3.930287 1.36363636,4.66666667 L1.36363636,15.3333333 C1.36363636,16.069713 1.97415716,16.6666667 2.72727273,16.6666667 L17.2727273,16.6666667 C18.0258428,16.6666667 18.6363636,16.069713 18.6363636,15.3333333 L18.6363636,4.66666667 C18.6363636,3.930287 18.0258428,3.33333333 17.2727273,3.33333333 L2.72727273,3.33333333 Z M17.2727273,2 C18.7789584,2 20,3.19390733 20,4.66666667 L20,15.3333333 C20,16.8060927 18.7789584,18 17.2727273,18 L2.72727273,18 C1.22104159,18 0,16.8060927 0,15.3333333 L0,4.66666667 C0,3.19390733 1.22104159,2 2.72727273,2 L17.2727273,2 Z M8.16666667,13.1333333 L4,13.1333333 C3.62344222,13.1333333 3.31818182,13.4318102 3.31818182,13.8 C3.31818182,14.1681898 3.62344222,14.4666667 4,14.4666667 L4,14.4666667 L8.16666667,14.4666667 C8.54322445,14.4666667 8.84848485,14.1681898 8.84848485,13.8 C8.84848485,13.4318102 8.54322445,13.1333333 8.16666667,13.1333333 L8.16666667,13.1333333 Z M13.75,5.33333333 C12.2256209,5.33333333 10.9848485,6.52447482 10.9848485,8 C10.9848485,8.77302759 11.3254047,9.46800006 11.8688812,9.95452977 C10.8391408,10.5627758 10.1515152,11.6575022 10.1515152,12.9090402 C10.1515152,13.2662051 10.2078201,13.6168504 10.3172774,13.9516079 C10.4319483,14.3023104 10.8156694,14.4957175 11.1743424,14.3835948 C11.5330155,14.2714721 11.7308182,13.8962782 11.6161473,13.5455757 C11.5494668,13.3416441 11.5151515,13.1279418 11.5151515,12.9090402 C11.5151515,11.7335986 12.5129554,10.7757069 13.75,10.7757069 C14.9870446,10.7757069 15.9848485,11.7335986 15.9848485,12.9090402 C15.9848485,13.0815383 15.9635437,13.2508273 15.9218367,13.4147606 C15.8309344,13.7720611 16.0534757,14.1337638 16.4188968,14.2226461 C16.7843178,14.3115284 17.154241,14.0939324 17.2451433,13.7366318 C17.3135782,13.4676418 17.3484848,13.1902722 17.3484848,12.9090402 C17.3484848,11.6575022 16.6608592,10.5627758 15.6313396,9.95339218 C16.1745953,9.46800006 16.5151515,8.77302759 16.5151515,8 C16.5151515,6.52447482 15.2743791,5.33333333 13.75,5.33333333 Z M8.16666667,9.63333333 L4,9.63333333 C3.62344222,9.63333333 3.31818182,9.93181017 3.31818182,10.3 C3.31818182,10.6681898 3.62344222,10.9666667 4,10.9666667 L4,10.9666667 L8.16666667,10.9666667 C8.54322445,10.9666667 8.84848485,10.6681898 8.84848485,10.3 C8.84848485,9.93181017 8.54322445,9.63333333 8.16666667,9.63333333 L8.16666667,9.63333333 Z M13.75,6.66666667 C14.5268073,6.66666667 15.1515152,7.26638618 15.1515152,8 C15.1515152,8.73361382 14.5268073,9.33333333 13.75,9.33333333 C12.9731927,9.33333333 12.3484848,8.73361382 12.3484848,8 C12.3484848,7.26638618 12.9731927,6.66666667 13.75,6.66666667 Z M8.16666667,6.13333333 L4,6.13333333 C3.62344222,6.13333333 3.31818182,6.43181017 3.31818182,6.8 C3.31818182,7.16818983 3.62344222,7.46666667 4,7.46666667 L4,7.46666667 L8.16666667,7.46666667 C8.54322445,7.46666667 8.84848485,7.16818983 8.84848485,6.8 C8.84848485,6.43181017 8.54322445,6.13333333 8.16666667,6.13333333 L8.16666667,6.13333333 Z"/></svg>

                                    </span>
                                    <span> {{ __('Verification Options') }} </span>
                                </a>
                            </li>
                        @endif --}}
                    </ul>
                </li>
                @endif
                @if(count(array_intersect($marketing_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                <li>
                    <a class="menu-title pl-1" href="#">
                        <!-- <span class="icon-marketing"></span> -->
                        <span>{{ __('MARKETING') }}</span>
                    </a>
                    <ul class="nav-second-level p-0 mx-2">

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
                        @if(Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{ route('campaign.index')}}">
                                        <span class="icon-celebrity"></span>
                                        <span> {{ __("Campaigns") }} </span>
                                    </a>
                                </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(count(array_intersect($extra_permissions, $allowed)) || Auth::user()->is_superadmin == 1 || in_array('tools',$allowed))
                    <li>
                        <a class="menu-title pl-1">
                            <!-- <span class="icon-extra"></span> -->
                            <span>{{ __("EXTRA") }}</span>
                        </a>
                        <ul class="nav-second-level p-0 mx-2">
                            @if(Auth::user()->is_superadmin == 1 && $client_preference->celebrity_check == 1)
                                @if(in_array('celebrity',$allowed) || Auth::user()->is_superadmin == 1)
                                    <li>
                                        <a href="{{ route('celebrity.index') }}">
                                            <span class="icon-celebrity"></span>
                                            <span> {{ __("Influencer") }} </span>
                                        </a>
                                    </li>
                                @endif
                            @endif

                            @if($client_preference_detail->get_estimations == 1)
                            <li>
                                <a href="{{route('estimations.index')}}">
                                    <span class="icon-estimation"><?xml version="1.0" encoding="iso-8859-1"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 363 363" style="enable-background:new 0 0 363 363;" xml:space="preserve"><g><path d="M149.475,0H20.525C9.208,0,0,9.208,0,20.525v128.949C0,160.792,9.208,170,20.525,170h128.949c11.317,0,20.525-9.208,20.525-20.525V20.525C170,9.208,160.792,0,149.475,0z M155,149.475c0,3.047-2.479,5.525-5.525,5.525H20.525c-3.047,0-5.525-2.479-5.525-5.525V20.525C15,17.479,17.479,15,20.525,15h128.949c3.047,0,5.525,2.479,5.525,5.525V149.475z"/><path d="M149.475,193H20.525C9.208,193,0,202.208,0,213.525v128.949C0,353.792,9.208,363,20.525,363h128.949c11.317,0,20.525-9.208,20.525-20.525V213.525C170,202.208,160.792,193,149.475,193z M155,342.475c0,3.047-2.479,5.525-5.525,5.525H20.525c-3.047,0-5.525-2.479-5.525-5.525V213.525c0-3.047,2.479-5.525,5.525-5.525h128.949c3.047,0,5.525,2.479,5.525,5.525V342.475z"/><path d="M342.475,0H213.525C202.208,0,193,9.208,193,20.525v128.949c0,11.318,9.208,20.525,20.525,20.525h128.949c11.317,0,20.525-9.208,20.525-20.525V20.525C363,9.208,353.792,0,342.475,0z M348,149.475c0,3.047-2.479,5.525-5.525,5.525H213.525c-3.047,0-5.525-2.479-5.525-5.525V20.525c0-3.047,2.479-5.525,5.525-5.525h128.949c3.047,0,5.525,2.479,5.525,5.525V149.475z"/><path d="M342.475,193H213.525C202.208,193,193,202.208,193,213.525v128.949c0,11.318,9.208,20.525,20.525,20.525h128.949c11.317,0,20.525-9.208,20.525-20.525V213.525C363,202.208,353.792,193,342.475,193z M348,342.475c0,3.047-2.479,5.525-5.525,5.525H213.525c-3.047,0-5.525-2.479-5.525-5.525V213.525c0-3.047,2.479-5.525,5.525-5.525h128.949c3.047,0,5.525,2.479,5.525,5.525V342.475z"/><path d="M130,77.5H92.5V40c0-4.142-3.357-7.5-7.5-7.5s-7.5,3.358-7.5,7.5v37.5H40c-4.143,0-7.5,3.358-7.5,7.5s3.357,7.5,7.5,7.5h37.5V130c0,4.142,3.357,7.5,7.5,7.5s7.5-3.358,7.5-7.5V92.5H130c4.143,0,7.5-3.358,7.5-7.5S134.143,77.5,130,77.5z"/><path d="M315.123,47.877c-2.93-2.929-7.678-2.929-10.607,0L278,74.393l-26.516-26.516c-2.93-2.929-7.678-2.929-10.607,0c-2.929,2.929-2.929,7.678,0,10.606L267.393,85l-26.517,26.517c-2.929,2.929-2.929,7.678,0,10.606c1.465,1.464,3.385,2.197,5.304,2.197s3.839-0.732,5.304-2.197L278,95.607l26.516,26.516c1.465,1.464,3.385,2.197,5.304,2.197s3.839-0.732,5.304-2.197c2.929-2.929,2.929-7.678,0-10.606L288.606,85l26.517-26.517C318.052,55.555,318.052,50.806,315.123,47.877z"/><path d="M323,270.5h-90c-4.143,0-7.5,3.358-7.5,7.5s3.357,7.5,7.5,7.5h90c4.143,0,7.5-3.358,7.5-7.5S327.143,270.5,323,270.5z"/><path d="M126,250.5H44c-4.143,0-7.5,3.358-7.5,7.5s3.357,7.5,7.5,7.5h82c4.143,0,7.5-3.358,7.5-7.5S130.143,250.5,126,250.5z"/><path d="M126,290.5H44c-4.143,0-7.5,3.358-7.5,7.5s3.357,7.5,7.5,7.5h82c4.143,0,7.5-3.358,7.5-7.5S130.143,290.5,126,290.5z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span>
                                    <span> {{ __('Estimations') }} </span>
                                </a>
                            </li>
                            @if($client_preference_detail->is_scan_qrcode_bag == 1)
                            <li>
                                <a href="{{route('estimations.barcode')}}">
                                    <span class="icon-estimation"><?xml version="1.0" encoding="iso-8859-1"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 363 363" style="enable-background:new 0 0 363 363;" xml:space="preserve"><g><path d="M149.475,0H20.525C9.208,0,0,9.208,0,20.525v128.949C0,160.792,9.208,170,20.525,170h128.949c11.317,0,20.525-9.208,20.525-20.525V20.525C170,9.208,160.792,0,149.475,0z M155,149.475c0,3.047-2.479,5.525-5.525,5.525H20.525c-3.047,0-5.525-2.479-5.525-5.525V20.525C15,17.479,17.479,15,20.525,15h128.949c3.047,0,5.525,2.479,5.525,5.525V149.475z"/><path d="M149.475,193H20.525C9.208,193,0,202.208,0,213.525v128.949C0,353.792,9.208,363,20.525,363h128.949c11.317,0,20.525-9.208,20.525-20.525V213.525C170,202.208,160.792,193,149.475,193z M155,342.475c0,3.047-2.479,5.525-5.525,5.525H20.525c-3.047,0-5.525-2.479-5.525-5.525V213.525c0-3.047,2.479-5.525,5.525-5.525h128.949c3.047,0,5.525,2.479,5.525,5.525V342.475z"/><path d="M342.475,0H213.525C202.208,0,193,9.208,193,20.525v128.949c0,11.318,9.208,20.525,20.525,20.525h128.949c11.317,0,20.525-9.208,20.525-20.525V20.525C363,9.208,353.792,0,342.475,0z M348,149.475c0,3.047-2.479,5.525-5.525,5.525H213.525c-3.047,0-5.525-2.479-5.525-5.525V20.525c0-3.047,2.479-5.525,5.525-5.525h128.949c3.047,0,5.525,2.479,5.525,5.525V149.475z"/><path d="M342.475,193H213.525C202.208,193,193,202.208,193,213.525v128.949c0,11.318,9.208,20.525,20.525,20.525h128.949c11.317,0,20.525-9.208,20.525-20.525V213.525C363,202.208,353.792,193,342.475,193z M348,342.475c0,3.047-2.479,5.525-5.525,5.525H213.525c-3.047,0-5.525-2.479-5.525-5.525V213.525c0-3.047,2.479-5.525,5.525-5.525h128.949c3.047,0,5.525,2.479,5.525,5.525V342.475z"/><path d="M130,77.5H92.5V40c0-4.142-3.357-7.5-7.5-7.5s-7.5,3.358-7.5,7.5v37.5H40c-4.143,0-7.5,3.358-7.5,7.5s3.357,7.5,7.5,7.5h37.5V130c0,4.142,3.357,7.5,7.5,7.5s7.5-3.358,7.5-7.5V92.5H130c4.143,0,7.5-3.358,7.5-7.5S134.143,77.5,130,77.5z"/><path d="M315.123,47.877c-2.93-2.929-7.678-2.929-10.607,0L278,74.393l-26.516-26.516c-2.93-2.929-7.678-2.929-10.607,0c-2.929,2.929-2.929,7.678,0,10.606L267.393,85l-26.517,26.517c-2.929,2.929-2.929,7.678,0,10.606c1.465,1.464,3.385,2.197,5.304,2.197s3.839-0.732,5.304-2.197L278,95.607l26.516,26.516c1.465,1.464,3.385,2.197,5.304,2.197s3.839-0.732,5.304-2.197c2.929-2.929,2.929-7.678,0-10.606L288.606,85l26.517-26.517C318.052,55.555,318.052,50.806,315.123,47.877z"/><path d="M323,270.5h-90c-4.143,0-7.5,3.358-7.5,7.5s3.357,7.5,7.5,7.5h90c4.143,0,7.5-3.358,7.5-7.5S327.143,270.5,323,270.5z"/><path d="M126,250.5H44c-4.143,0-7.5,3.358-7.5,7.5s3.357,7.5,7.5,7.5h82c4.143,0,7.5-3.358,7.5-7.5S130.143,250.5,126,250.5z"/><path d="M126,290.5H44c-4.143,0-7.5,3.358-7.5,7.5s3.357,7.5,7.5,7.5h82c4.143,0,7.5-3.358,7.5-7.5S130.143,290.5,126,290.5z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span>
                                    <span> {{ __('QR Code') }} </span>
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
                            @if(Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('databaseAuditingLogs')}}">
                                        <span class="icon-tax"></span>
                                        <span> {{ __('DB Audit Logs') }} </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
