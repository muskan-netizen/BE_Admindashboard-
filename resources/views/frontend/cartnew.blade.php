@extends('layouts.store', ['title' => __('Cart')])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<style type="text/css">
    .swal2-title {
        margin: 0px;
        font-size: 26px;
        font-weight: 400;
        margin-bottom: 28px;
    }

    .discard_price {
        text-decoration: line-through;
        color: #6c757d;
    }
</style>
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store.left-sidebar')
</header>
<script type="text/template" id="address_template">
    <div class="col-md-12">
        <div class="delivery_box px-0">
            <label class="radio m-0"><%= address.address %> <%= address.city %><%= address.state %> <%= address.pincode %>
                <input type="radio" checked="checked" name="address_id" value="<%= address.id %>">
                <span class="checkround"></span>
            </label>
        </div>
    </div>
</script>
<script type="text/template" id="empty_cart_template">
    <div class="row mt-2 mb-4 mb-lg-5">
        <div class="col-12 text-center">
            <div class="cart_img_outer">
                <img src="{{asset('front-assets/images/empty_cart.png')}}">
            </div>
            <h3>{{__('Your Cart Is Empty!')}}</h3>
            <p>Add items to it now.</p>
            <a class="btn btn-solid" href="{{url('/')}}">{{__('Continue Shopping')}}</a>
        </div>
    </div>
</script>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title text-uppercase">{{__('Cart')}}</h4>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="cart_template">
    <% _.each(cart_details.products, function(product, key){%>
        <thead id="thead_<%= product.vendor.id %>">
            <tr>
                <th colspan="3">
                    <%= product.vendor.name %>
                </th>
                <th colspan="4">
                    <div class="countdownholder alert-danger" id="min_order_validation_error_<%= product.vendor.id %>" style="display:none;">Your cart will be expired in
                </th>
            </tr>
            <% if( (product.isDeliverable != undefined) && (product.isDeliverable == 0) ) { %>
            <tr class="border_0">
                <th colspan="7">
                    <div class="text-danger">
                        Products for this vendor are not deliverable at your area. Please change address or remove product.
                    </div>
                </th>
            </tr>
            <% } %>
        </thead>
        <tbody id="tbody_<%= product.vendor.id %>">
            <% _.each(product.vendor_products, function(vendor_product, vp){%>
                <tr class="padding-bottom vendor_products_tr" id="tr_vendor_products_<%= vendor_product.id %>">
                    <td style="width:100px" <%= vendor_product.length > 0 ? 'rowspan=2' : '' %>>
                        <div class="product-img pb-2">
                           <% if(vendor_product.pvariant.media_one) { %>
                                <img src="<%= vendor_product.pvariant.media_one.image.path.proxy_url %>100/70<%= vendor_product.pvariant.media_one.image.path.image_path %>" alt="">
                            <% }else{ %>
                                <img class='mr-2' src="<%= vendor_product.pvariant.media_second.image.path.proxy_url %>200/200<%= vendor_product.pvariant.media_second.image.path.image_path %>">
                            <% } %>
                        </div>
                    </td>
                    <td class="items-details text-left">
                        <h4><%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></h4>
                        <% _.each(vendor_product.pvariant.vset, function(vset, vs){%>
                            <% if(vset.variant_detail.trans) { %>
                                <label><span><%= vset.variant_detail.trans.title %>:</span> <%= vset.option_data.trans.title %></label>
                            <% } %>
                        <% }); %>
                    </td>
                    <td>
                        <div class="items-price mb-3">{{Session::get('currencySymbol')}}<%= vendor_product.pvariant.price %></div>
                    </td>
                    <td>
                        <div class="number">
                            <span class="minus qty-minus" data-id="<%= vendor_product.id %>" data-base_price=" <%= vendor_product.pvariant.price %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </span>
                            <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="<%= vendor_product.quantity %>" class="input-number" step="0.01" id="quantity_<%= vendor_product.id %>">
                            <span class="plus qty-plus" data-id="<%= vendor_product.id %>" data-base_price=" <%= vendor_product.pvariant.price %>">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </span>
                        </div>
                    </td>
                    <td>
                    <% if(cart_details.pharmacy_check == 1){ %>
                        <% if(vendor_product.product.pharmacy_check == 1){ %>
                            <button type="button" class="btn btn-solid prescription_btn" data-product="<%= vendor_product.product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">Add</button>
                        <% } %>
                    <% } %>
                    </td>
                    <td class="text-center">
                        <a  class="action-icon d-block mb-3 remove_product_via_cart" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td class="text-right pl-lg-2">
                        <div class="items-price mb-3">{{Session::get('currencySymbol')}}<%= vendor_product.pvariant.quantity_price %></div>
                    </td>
                </tr>
                <% if(vendor_product.addon.length != 0) { %>
                    <tr>
                         <td colspan="7" class="border_0 p-0 border-0">
                           <h6 class="m-0 pl-0"><b>{{__('Add Ons')}}</b></h6>
                        </td>
                    </tr>
                    <% _.each(vendor_product.addon, function(addon, ad){%>
                    <tr>
                         
                            <td></td>
                            <td class="items-details text-left">
                                <p class="m-0"><%= addon.option.title %></p>
                            </td>
                            <td>
                                <div class="extra-items-price">{{Session::get('currencySymbol')}}<%= addon.option.price_in_cart %></div>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right pl-lg-2">
                                <div class="extra-items-price">{{Session::get('currencySymbol')}}<%= addon.option.quantity_price %></div>
                            </td>
                    </tr> 

                        <% }); %>
                <% } %>
            <% }); %>
            <tr class="vertical-top">
                <td colspan="3">
                    <div class="d-flex w-100 ">
                    <div class="coupon_box d-flex w-75 align-items-center">
                        <img src="{{ asset('assets/images/discount_icon.svg') }}">
                        <label class="mb-0 ml-2"><%= product.coupon ? product.coupon.promo.name : '' %></label>
                    </div>
                    <% if(!product.coupon) { %>
                            <a class="btn btn-solid promo_code_list_btn" data-vendor_id="<%= product.vendor.id %>" data-cart_id="<%= cart_details.id %>" data-amount="<%= product.product_total_amount %>">{{__('Apply')}}</a>
                        <% }else{ %>
                        <i class="fa fa-times ml-4 remove_promo_code_btn" data-coupon_id="<%= product.coupon ? product.coupon.promo.id : '' %>" data-cart_id="<%= cart_details.id %>"></i>
                        <% } %>
                        </div>
                </td> 
                <td colspan="2"></td>
                <td class="text-center">
                    <p class="total_amt m-0">{{__('Delivery Fee')}} :</p>
                </td>
                <td class="text-right pl-lg-2">
                    <p class="total_amt mb-1 <% if(product.delivery_fee_charges > 0) { %>{{ ((in_array(1, $subscription_features)) ) ? 'discard_price' : '' }}<% } %>">{{Session::get('currencySymbol')}} <%= product.delivery_fee_charges %></p>
                    <p class="total_amt m-0">{{Session::get('currencySymbol')}} <%= product.product_total_amount %></p>
                </td>
            </tr>
        </tbody>
    <% }); %>
    <tfoot>
        <tr>
            <td colspan="3"></td>
            <td class="pr-0 pb-0">
               <p class="mb-1"></p>{{__('Sub Total')}}  
               <hr class="mt-2 mb-0">
            </td>
            <td class="text-right pl-0 pb-0" colspan="3">
               <p class="mb-1"></p> {{Session::get('currencySymbol')}}<%= cart_details.gross_amount %>
               <hr class="mt-2 mb-0">
            </td>
        </tr>
        <tr class="border_0">
            <td colspan="3"></td>
            <td class="pr-0 pb-0">
               <p class="mb-1"></p>{{__('Tax')}}
               <hr class="mt-2 mb-0">
            </td>
            <td class="text-right pl-0 pb-0" colspan="3">
               <p class="mb-1"></p> {{Session::get('currencySymbol')}}<%= cart_details.total_taxable_amount %>
               <hr class="mt-2 mb-0">
            </td>
        </tr>
        <% if(cart_details.total_subscription_discount != undefined) { %>
            <tr class="border_0">
                <td colspan="3"></td>
                <td class="pr-0 pb-0">
                <p class="mb-1"></p>{{__('Subscription Discount')}} 
                <hr class="mt-2 mb-0">
                </td>
                <td class="text-right pl-0 pb-0" colspan="3">
                <p class="mb-1"></p> {{Session::get('currencySymbol')}}<%= cart_details.total_subscription_discount %>
                <hr class="mt-2 mb-0">
                </td>
            </tr>
        <% } %>
        <% if(cart_details.loyalty_amount > 0) { %>
        <tr class="border_0">
            <td colspan="3"></td>
            <td class="pr-0 pb-0">
                <p class="mb-1"></p>{{__('Loyalty Amount')}} 
                <hr class="mt-2 mb-0">
            </td>
            <td class="text-right pl-0 pb-0" colspan="3">
                <p class="mb-1"></p> {{Session::get('currencySymbol')}}<%= cart_details.loyalty_amount %>
                <hr class="mt-2 mb-0">
            </td>
        </tr>
        <% } %>
        <tr class="border_0">
            <td colspan="3"></td>
            <td colspan="4" class="pr-0 pb-0">
                <div class="mb-2">{{__('Do you want to give a tip?')}}</div>
                <div class="tip_radio_controls">
                    <% if(cart_details.total_payable_amount > 0) { %>
                        <input type="radio" class="tip_radio" id="control_01" name="select" value="<%= cart_details.tip_5_percent %>">
                        <label class="tip_label" for="control_01">
                            <h5 class="m-0" id="tip_5">{{Session::get('currencySymbol')}}<%= cart_details.tip_5_percent %></h5>
                            <p class="m-0">5%</p>
                        </label>
                    
                        <input type="radio" class="tip_radio" id="control_02" name="select" value="<%= cart_details.tip_10_percent %>" >
                        <label class="tip_label" for="control_02">
                            <h5 class="m-0" id="tip_10">{{Session::get('currencySymbol')}}<%= cart_details.tip_10_percent %></h5>
                            <p class="m-0">10%</p>
                        </label>
                    
                        <input type="radio" class="tip_radio" id="control_03" name="select" value="<%= cart_details.tip_15_percent %>" >
                        <label class="tip_label" for="control_03">
                            <h5 class="m-0" id="tip_15">{{Session::get('currencySymbol')}}<%= cart_details.tip_15_percent %></h5>
                            <p class="m-0">15%</p>
                        </label>

                        <input type="radio" class="tip_radio" id="custom_control" name="select" value="custom" >
                        <label class="tip_label" for="custom_control">
                            <h5 class="m-0">{{__('Custom')}}<br>{{__('Amount')}}</h5>
                        </label>
                    <% } %>
                </div>
                <div class="custom_tip mb-3 <% if(cart_details.total_payable_amount > 0) { %> d-none <% } %>">
                    <input class="input-number form-control" name="custom_tip_amount" id="custom_tip_amount" placeholder="{{__('Enter Custom Amount')}}" type="number" value="" step="0.1">
                </div>
            </td>
        </tr>
        <tr class="border_0">
            <td colspan="3"></td>
            <td colspan="2" class="pt-0 pr-0">
                <hr class="mt-0 mb-2">
                <p class="total_amt m-0">{{__('Amount Payable')}}</p>
            </td>
            <td colspan="2" class="pt-0 pl-0 text-right">
                <hr class="mt-0 mb-2">
                <p class="total_amt m-0" id="cart_total_payable_amount" data-cart_id="<%= cart_details.id %>">{{Session::get('currencySymbol')}}<%= cart_details.total_payable_amount %></p>
                <div>
                    <input type="hidden" name="cart_tip_amount" id="cart_tip_amount" value="0">
                    <input type="hidden" name="cart_total_payable_amount" value="<%= cart_details.total_payable_amount %>">
                    <input type="hidden" name="cart_payable_amount_original" id="cart_payable_amount_original" data-curr="{{Session::get('currencySymbol')}}" value="<%= cart_details.total_payable_amount %>">
                </div>
            </td>
        </tr>
        <tr class="border_0">
            <td colspan="3"></td>
            <td colspan="4" class="pt-0 pr-0">
                <div class="row d-flex align-items-center arabic-lng no-gutters" id="dateredio">
                    <div class="col-md-5 pr-2">
                        <div class="login-form">
                            <ul class="list-inline">
                                <li class="d-inline-block mr-1">
                                    <input type="radio" class="custom-control-input check" id="tasknow"
                                    name="task_type" value="now" checked>
                                    <label class="custom-control-label" for="tasknow">Now</label>
                                </li>
                                <li class="d-inline-block">
                                    <input type="radio" class="custom-control-input check" id="taskschedule"
                                    name="task_type" value="schedule" >
                                    <label class="custom-control-label" for="taskschedule">Schedule</label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-7 datenow align-items-center justify-content-between" id="schedule_div" style="display:flex!important">
                            <input type="datetime-local" id="schedule_datetime" class="form-control" placeholder="Inline calendar" value=" ">
                        <!-- <button type="button" class="btn btn-solid"><i class="fa fa-check" aria-hidden="true"></i></button> -->
                    </div>
                </div>
            </td>
        </tr>
    </tfoot>
</script>
<script type="text/template" id="promo_code_template">
    <% _.each(promo_codes, function(promo_code, key){%>
        <div class="col-lg-6 mt-3">
            <div class="coupon-code mt-0">
                <div class="p-2">
                    <img src="<%= promo_code.image.proxy_url %>100/35<%= promo_code.image.image_path %>" alt="">
                    <h6 class="mt-0"><%= promo_code.title %></h6>
                </div>
                <hr class="m-0">
                <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                    <label class="m-0"><%= promo_code.name %></label>
                    <a class="btn btn-solid apply_promo_code_btn" data-vendor_id="<%= vendor_id %>" data-cart_id="<%= cart_id %>" data-coupon_id="<%= promo_code.id %>" data-amount="<%= amount %>" style="cursor: pointer;">{{__('Apply')}}</a>
                </div>
                <hr class="m-0">
                <div class="offer-text p-2">
                    <p class="m-0"><%= promo_code.short_desc %></p>
                </div>
            </div>
        </div>
    <% }); %>
</script>

<script type="text/template" id="no_promo_code_template">
    <div class="col-12 no-more-coupon text-center">
        <p>{{__('No Other Coupons Available.')}}</p>
    </div>
</script>
<div class="container" id="cart_main_page">
    @if($cartData)
    <form method="post" action="" id="placeorder_form">
        @csrf
        <div class="card-box">
            <div class="row">
                <div class="col-sm-4 left_box">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <h4 class="page-title">{{__('Delivery Address')}}</h4>
                        </div>
                    </div>
                    <div class="row mb-4" id="address_template_main_div">
                        @forelse($addresses as $k => $address)
                        <div class="col-md-12">
                            <div class="delivery_box px-0">
                                <label class="radio m-0">{{$address->address}}, {{$address->state}} {{$address->pincode}}
                                    @if($address->is_primary)
                                    <input type="radio" name="address_id" value="{{$address->id}}" checked="checked">
                                    @else
                                    <input type="radio" name="address_id" value="{{$address->id}}" {{$k == 0? 'checked="checked""' : '' }}>
                                    @endif
                                    <span class="checkround"></span>
                                </label>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 address-no-found">
                            <p>{{__('Address not available.')}}</p>
                        </div>
                        @endforelse
                    </div>
                    <div class="row">
                        <div class="col-12 mt-4 text-center" id="add_new_address_btn">
                            <a class="btn btn-solid w-100 mx-auto mb-4">
                                <i class="fa fa-plus mr-1" aria-hidden="true"></i>{{__('Add New Address')}}
                            </a>
                        </div>
                        <div class="col-md-12" id="add_new_address_form" style="display:none;">
                            <div class="theme-card w-100">
                                <div class="form-row no-gutters">
                                    <div class="col-12">
                                        <label for="type">{{__('Address Type')}}</label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="delivery_box pt-0 pl-0  pb-3">
                                            <label class="radio m-0">{{__('Home')}}
                                                <input type="radio" checked="checked" name="address_type" value="1">
                                                <span class="checkround"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="delivery_box pt-0 pl-0  pb-3">
                                            <label class="radio m-0">{{__('Office')}}
                                                <input type="radio" name="address_type" value="2">
                                                <span class="checkround"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="delivery_box pt-0 pl-0  pb-3">
                                            <label class="radio m-0">{{__('Others')}}
                                                <input type="radio" name="address_type" value="3">
                                                <span class="checkround"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="latitude">
                                <input type="hidden" id="longitude">
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="address">{{__('Address')}}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="address" placeholder="{{__('Address')}}" aria-label="Recipient's Address" aria-describedby="button-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">
                                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <span class="text-danger" id="address_error"></span>
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="city">{{__('City')}}</label>
                                        <input type="text" class="form-control" id="city" placeholder="{{__('City')}}" value="">
                                        <span class="text-danger" id="city_error"></span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="state">{{__('State')}}</label>
                                        <input type="text" class="form-control" id="state" placeholder="{{__('State')}}" value="">
                                        <span class="text-danger" id="state_error"></span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="country">{{__('Country')}}</label>
                                        <select name="country" id="country" class="form-control">
                                            @foreach($countries as $co)
                                            <option value="{{$co->id}}">{{$co->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" id="country_error"></span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="pincode">{{__('Pincode')}}</label>
                                        <input type="text" class="form-control" id="pincode" placeholder="{{__('Pincode')}}" value="">
                                        <span class="text-danger" id="pincode_error"></span>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <button type="button" class="btn btn-solid" id="save_address">{{__('Save Address')}}</button>
                                        <button type="button" class="btn btn-solid black-btn" id="cancel_save_address_btn">{{__('Cancel')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="spinner-box">
                        <div class="circle-border">
                            <div class="circle-core"></div>
                        </div>
                    </div>
                    <div class="table-responsive h-100">
                        <table class="table table-centered table-nowrap mb-0 h-100" id="cart_table">
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-6 mb-2 mb-sm-0">
                    <a class="btn btn-solid" href="{{ url('/') }}">{{__('Continue Shopping')}}</a>
                </div>
                <div class="col-sm-6 text-md-right">
                    <button id="order_palced_btn" class="btn btn-solid" type="button" {{$addresses->count() == 0 ? 'disabled': ''}}>{{__('Continue')}}</button>
                </div>
            </div>
        </div>

    </form>
    @else
    <div class="row mt-2 mb-4 mb-lg-5">
        <div class="col-12 text-center">
            <div class="cart_img_outer">
                <img src="{{asset('front-assets/images/empty_cart.png')}}">
            </div>
            <h3>{{__('Your Cart Is Empty!')}}</h3>
            <p>{{__('Add items to it now.')}}</p>
            <a class="btn btn-solid" href="{{url('/')}}">{{__('Continue Shopping')}}</a>
        </div>
    </div>
    @endif
</div>
<div class="modal fade refferal_modal" id="refferal-modal" tabindex="-1" aria-labelledby="refferal-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="refferal-modalLabel">{{__('Apply Coupon Code')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mt-0 pt-0">
                <div class="coupon-box">
                    <div class="row" id="promo_code_list_main_div">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade remove-item-modal" id="remove_item_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_itemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title" id="remove_itemLabel">{{__('Remove Item')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="vendor_id" value="">
                <input type="hidden" id="cartproduct_id" value="">
                <h6 class="m-0">{{__('Are You Sure You Want To Remove This Item?')}}</h6>
            </div>
            <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button>
                <button type="button" class="btn btn-solid" id="remove_product_button">{{__('Remove')}}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="payment_method_template">
    <% _.each(payment_options, function(payment_option, k){%>
        <a class="nav-link <%= payment_option.slug == 'cash_on_delivery' ? 'active': ''%>" id="v-pills-<%= payment_option.slug %>-tab" data-toggle="pill" href="#v-pills-<%= payment_option.slug %>" role="tab" aria-controls="v-pills-wallet" aria-selected="true" data-payment_option_id="<%= payment_option.id %>"><%= payment_option.title %></a>
    <% }); %>
</script>
<script type="text/template" id="payment_method_tab_pane_template">
    <% _.each(payment_options, function(payment_option, k){%>
        <div class="tab-pane fade <%= payment_option.slug == 'cash_on_delivery' ? 'active show': ''%>" id="v-pills-<%= payment_option.slug %>" role="tabpanel" aria-labelledby="v-pills-<%= payment_option.slug %>-tab">
            <form method="POST" id="<%= payment_option.slug %>-payment-form">
            @csrf
            @method('POST')
                <div class="payment_response mb-3">
                    <div class="alert p-0" role="alert"></div>
                </div>
                <div class="form_fields">
                    <div class="row">
                        <div class="col-md-12">
                            <% if(payment_option.slug == 'stripe') { %>
                                <div class="form-control">
                                    <label class="d-flex flex-row pt-1 pb-1 mb-0">
                                        <div id="stripe-card-element"></div>
                                    </label>
                                </div>
                                <span class="error text-danger" id="stripe_card_error"></span>
                            <% } %>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12 text-md-right">
                            <button type="button" class="btn btn-solid" data-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="button" class="btn btn-solid ml-1 proceed_to_pay">{{__('Place Order')}}</button>
                            <!-- <button type="button" class="btn btn-solid ml-1 proceed_to_pay">Scheduled Now</button> -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <% }); %>
</script>
<div class="modal fade" id="proceed_to_pay_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="pay-billLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row no-gutters">
                    <div class="col-4">
                        <div class="nav flex-column nav-pills" id="v_pills_tab" role="tablist" aria-orientation="vertical"></div>
                    </div>
                    <div class="col-8">
                        <div class="tab-content-box px-3">
                            <div class="d-flex align-items-center justify-content-between pt-3">
                                <h5 class="modal-title" id="pay-billLabel">{{__('Total Amount')}}: <span id="total_amt"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="tab-content h-100" id="v_pills_tabContent">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="prescription_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{__('Add Prescription')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_prescription_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="AddCardBox">
                    <div class="row">
                        <div class="col-sm-6" id="imageInput">
                            <input type="hidden" id="vendor_idd" name="vendor_idd" value="" />
                            <input type="hidden" id="product_id" name="product_id" value="" />
                            <input data-default-file="" accept="image/*" type="file" data-plugins="dropify" name="prescriptions[]" class="dropify" multiple />
                            <p class="text-muted text-center mt-2 mb-0">{{__('Upload Prescription')}}</p>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitPrescriptionForm">{{__('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var base_url = "{{url('/')}}";
    var place_order_url = "{{route('user.placeorder')}}";
    var payment_stripe_url = "{{route('payment.stripe')}}";
    var user_store_address_url = "{{route('address.store')}}";
    var promo_code_remove_url = "{{ route('remove.promocode') }}";
    var payment_paypal_url = "{{route('payment.paypalPurchase')}}";
    var update_qty_url = "{{ url('product/updateCartQuantity') }}";
    var promocode_list_url = "{{ route('verify.promocode.list') }}";
    var payment_option_list_url = "{{route('payment.option.list')}}";
    var apply_promocode_coupon_url = "{{ route('verify.promocode') }}";
    var payment_success_paypal_url = "{{route('payment.paypalCompletePurchase')}}";
</script>
@endsection