@extends('layouts.store', ['title' => $vendor->name])
@section('css')
<style type="text/css">
   .main-menu .brand-logo {
   display: inline-block;
   padding-top: 20px;
   padding-bottom: 20px;
   }
</style>
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
@endsection
@section('content')
<header>
   <div class="mobile-fix-option"></div>
   @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
   .productVariants .firstChild {
   min-width: 150px;
   text-align: left !important;
   border-radius: 0% !important;
   margin-right: 10px;
   cursor: default;
   border: none !important;
   }
   .product-right .color-variant li,
   .productVariants .otherChild {
   height: 35px;
   width: 35px;
   border-radius: 50%;
   margin-right: 10px;
   cursor: pointer;
   border: 1px solid #f7f7f7;
   text-align: center;
   }
   .productVariants .otherSize {
   height: auto !important;
   width: auto !important;
   border: none !important;
   border-radius: 0%;
   }
   .product-right .size-box ul li.active {
   background-color: inherit;
   }
   .product-box .product-detail h4, .product-box .product-info h4{
   font-size: 16px;
   }
</style>
<!-- section start -->
<section class="section-b-space ratio_asos">
   <div class="collection-wrapper">
      <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="product-banner-img">
                    @if(!empty($vendor->banner))
                        <img alt="" src="{{$vendor->banner['image_fit'] . '1600/1000' . $vendor->banner['image_path']}}">
                    @endif
                </div>
                <div class="product-bottom-bar">
                  <div class="row">
                    <div class="col-sm-8 col-lg-5 order-0">
                        <div class="card-box vendor-details-left px-2 py-3">
                            <div class="d-flex">
                                <div class="mr-1">
                                    <img src="{{$vendor->logo['image_fit'] . '120/120' . $vendor->logo['image_path']}}" class="rounded-circle avatar-lg" alt="profile-image" style="mini-width:120px">
                                </div>
                                <div class="ml-1">
                                    <h3>{{$vendor->name}}</h3>
                                    <ul class="vendor-info">
                                        <li class="d-block food-items">
                                        <i class="icon-ic_eat"></i>
                                            @forelse($listData as $key => $data)
                                                {{ $data->category->translation_one->name . (( $key !=  count($listData)-1 ) ? ',' : '') }}
                                            @empty
                                            @endforelse
                                            {{--<a href="#">Pizza</a>, <a href="#">Fast Food</a>, <a href="#">Beverages</a>--}}
                                        </li>
                                        <li class="d-block vendor-location">
                                            <i class="icon-location"></i> {{$vendor->address}}
                                        </li>
                                        <li class="d-block vendor-timing">
                                            <span><i class="icon-time"></i> 11am – 11pm (Today)</span>
                                            <span data-toggle="tooltip" data-placement="right" title="Tooltip on right"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                            <span class="tooltip-text d-none">Mon-Sun : 11am - 11pm</span>
                                            </span> 
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-5 order-lg-1 order-2">
                        <div class="vendor-search-bar">
                           <div class="radius-bar w-100">
                              <div class="search_form d-flex align-items-center justify-content-between">
                                 <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                 <input class="form-control border-0 typeahead" type="search" placeholder="{{__('Search')}}" id="main_search_box">
                              </div>
                              <div class="list-box style-4" style="display:none;" id="search_box_main_div">
                              </div>
                           </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-lg-3 col-xl-2 order-xl-2 order-1">
                        <div class="vendor-reviwes">
                           <div class="row">                             
                              <div class="col-12 d-flex align-items-center">
                                  @if($vendor->vendorRating > 0)
                                    <div class="rating-text-box ml-sm-auto">
                                        <span>{{ $vendor->vendorRating }}</span>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                  @endif
                                 {{--<div class="review-text">
                                    <div class="reviw-number">409</div>
                                    <div class="reviews-text">Delivery Reviews</div>
                                 </div>--}}
                              </div>
                           </div>
                        </div>
                    </div>
                  </div>
               </div>
            </div>
        </div>
        <div class="position-relative">
            <div class="categories-product-list">
               <div class="row">
                  <div class="col-md-4"></div>
                  <div class="col-md-8"></div>
                  <div class="col-12">
                    <hr>
                    <div class="row">
                        <div class="col-lg-3">
                            <nav class="scrollspy-menu">
                                <ul>
                                    @forelse($listData as $key => $data)
                                        <li><a href="#{{$data->category->slug}}">{{$data->category->translation_one->name}} ({{$data->products_count}})</a></li>
                                    @empty
                                    @endforelse
                                </ul>
                            </nav>
                        </div>
                        <div class="col-md-8 col-lg-6 border-left">
                            @forelse($listData as $key => $data)
                            <section class="scrolling_section" id="{{$data->category->slug}}">
                                <h2 class="category-head mt-0 mb-2">{{$data->category->translation_one->name}} ({{$data->products_count}})</h2>
                                @if(!empty($data->products))
                                    @forelse($data->products as $prod)
                                    <div class="row classes_wrapper no-gutters mb-2">
                                        <div class="col-md-2 col-sm-3 mb-3">
                                            <div class="class_img">
                                                <img src="{{ $prod['product_image'] }}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-md-10 col-sm-9">
                                            <div class="row price_head pl-2">
                                                <div class="col-sm-8 pl-2">
                                                    <h5 class="mt-0">{{$prod['translation_title']}} </h5>
                                                    @if($prod['averageRating'] > 0)
                                                        <div class="rating-text-box">
                                                            <span>{{$prod['averageRating']}} </span>
                                                            
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                        </div>
                                                    @endif
                                                    <p class="mb-1">{{Session::get('currencySymbol').(number_format($prod->variant_price * $prod->variant_multiplier,2))}}</p>
                                                    <div class="member_no d-block mb-0">{!! $prod->translation_description !!}</div>
                                                </div>
                                                <div class="col-sm-4 text-right">
                                                    <!-- <a href="#" class="add-cart-btn">Add</a> -->

                                                    <!--add to cart page -->  
                                                    @php
                                                        $data = $prod;
                                                    @endphp

                                                    @if(isset($data->variant[0]->checkIfInCart) && count($data->variant[0]->checkIfInCart) > 0)
                                                        <a class="add-cart-btn add_on_demand" style="display:none;" id="add_button_href{{$data->variant[0]->checkIfInCart['0']['id']}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ route('addToCart') }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">Add</a>
                                                        <div class="number" id="show_plus_minus{{$data->variant[0]->checkIfInCart['0']['id']}}">
                                                            <span class="minus qty-minus-ondemand"  data-parent_div_id="show_plus_minus{{$data->variant[0]->checkIfInCart['0']['id']}}" data-id="{{$data->variant[0]->checkIfInCart['0']['id']}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                                            </span>
                                                            <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="{{$data->variant[0]->checkIfInCart['0']['quantity']}}" class="input-number" step="0.01" id="quantity_ondemand_{{$data->variant[0]->checkIfInCart['0']['id']}}" readonly>
                                                            <span class="plus qty-plus-ondemand"  data-id="{{$data->variant[0]->checkIfInCart['0']['id']}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                    @else
                                                        
                                                        <a class="add-cart-btn add_on_demand" id="aadd_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ route('addToCart') }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">Add</a>
                                                        <div class="number" style="display:none;" id="ashow_plus_minus{{$data->id}}">
                                                            <span class="minus qty-minus-ondemand"  data-parent_div_id="show_plus_minus{{$data->id}}" readonly data-id="{{$data->id}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                                            </span>
                                                            <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d{{$data->id}}" readonly placeholder="1" type="text" value="1" class="input-number input_qty" step="0.01">
                                                            <span class="plus qty-plus-ondemand"  data-id="" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        
                                                    @endif
                                                    <!-- end add to cart page -->


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    @endforelse
                                @endif
                            </section>
                            @empty
                            @endforelse
                        </div>
                        <div class="col-4 col-lg-3 d-md-inline-block d-none">
                            <div class="card-box p-0 cart-main-box">                                
                                <div class="p-2 d-flex align-items-center justify-content-between border-bottom">
                                    <h4 class="right-card-title">Cart</h4>
                                </div>
                                <div class="cart-main-box-inside d-flex align-items-center">
                                    <div class="spinner-box">
                                        <div class="circle-border">
                                            <div class="circle-core"></div>
                                        </div>
                                    </div>
                                    <!-- <div class="p-2 border-top">
                                        <h5>Cottonworth Classic Cuvée 75cl</h5>
                                        <div class="qty-box mt-3 mb-2">
                                            <div class="input-group">
                                                <span class="input-group-prepend">
                                                    <button type="button" class="btn quantity-left-minus" data-type="minus" data-field=""><i class="ti-angle-left"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="quantity" id="quantity" class="form-control input-qty-number quantity_count" value="1">
                                                <span class="input-group-prepend quant-plus">
                                                    <button type="button" class="btn quantity-right-plus " data-type="plus" data-field="">
                                                        <i class="ti-angle-right"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-sub-total d-flex align-items-center justify-content-between">
                                        <span>Subtotalll</span>
                                        <span>£ 10.50</span>
                                    </div> -->
                                    
                                    <script type="text/template" id="header_cart_template_ondemand">
                                        <ul class="p-2">
                                        <% _.each(cart_details.products, function(product, key){%>
                                            <li>
                                                <h6 class="d-flex align-items-center justify-content-between"><b> <%= product.vendor.name %> </b></h6>
                                            </li>

                                            <% if( (product.isDeliverable != undefined) && (product.isDeliverable == 0) ) { %>
                                                <li class="border_0">
                                                    <th colspan="7">
                                                        <div class="text-danger">
                                                            Products for this vendor are not deliverable at your area. Please change address or remove product.
                                                        </div>
                                                    </th>
                                                </li>
                                                <% } %>
                                            <% _.each(product.vendor_products, function(vendor_product, vp){%>  
                                                <li class="p-0" id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                                                    <div class='media-body'>                                                                
                                                        <h6 class="d-flex align-items-center justify-content-between">
                                                            <span class="ellips"><%= vendor_product.quantity %>x <%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></span>
                                                            <span>
                                                                {{Session::get('currencySymbol')}}<%= vendor_product.pvariant.price %>
                                                                <a  class="action-icon ml-1 remove_product_via_cart" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                </a>
                                                            </span>
                                                        </h6>
                                                    </div>
                                                </li>

                                                <% if(vendor_product.addon.length != 0) { %>
                                                    <div class="row align-items-md-center">
                                                        <div class="col-12">
                                                            <h6 class="m-0 pl-0"><b>{{__('Add Ons')}}</b></h6>
                                                        </div>
                                                    </div>
                                                    <% _.each(vendor_product.addon, function(addon, ad){%>
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-4 items-details text-left">
                                                            <p class="p-0 m-0"><%= addon.option.title %></p>
                                                        </div>
                                                        <div class="col-md-2 col-sm-4 text-center">
                                                            <div class="extra-items-price">{{Session::get('currencySymbol')}}<%= addon.option.price_in_cart %></div>
                                                        </div>
                                                        <div class="col-md-7 col-sm-4 text-right">
                                                            <div class="extra-items-price">{{Session::get('currencySymbol')}}<%= addon.option.quantity_price %></div>
                                                        </div>
                                                    </div>
                                                    <% }); %>
                                                <% } %>
                                                <hr class="my-2">
                                                    

                                            <% }); %>
                                        <% }); %>

                                        <h5 class="d-flex align-items-center justify-content-between pb-2">{{__('PRICE DETAILS')}} </h5>
                                        <li>
                                            <div class='media-body'>                                                                
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Price')}}</span>
                                                    <span>{{Session::get('currencySymbol')}}<%= cart_details.gross_amount %></span>
                                                </h6>
                                            </div>
                                        </li>

                                        <li>
                                            <div class='media-body'>                                                                
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Tax')}}</span>
                                                    <span>{{Session::get('currencySymbol')}}<%= cart_details.total_taxable_amount %></span>
                                                </h6>
                                            </div>
                                        </li>

                                        <% if(cart_details.loyalty_amount > 0) { %>
                                        <li>
                                            <div class='media-body'>                                                                
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Loyalty Amount')}} </span>
                                                    <span>{{Session::get('currencySymbol')}}<%= cart_details.loyalty_amount %></span>
                                                </h6>
                                            </div>
                                        </li>
                                        <% } %>

                                        <% if(cart_details.wallet_amount_used > 0) { %>
                                        <li>
                                            <div class='media-body'>                                                                
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Wallet Amount')}} </span>
                                                    <span>{{Session::get('currencySymbol')}}<%= cart_details.wallet_amount_used %></span>
                                                </h6>
                                            </div>
                                        </li>
                                        <% } %>
                                        </ul>
                                        <div class="cart-sub-total d-flex align-items-center justify-content-between">
                                            <span>{{__('Total')}}</span>
                                            <span>{{Session::get('currencySymbol')}}<%= cart_details.total_payable_amount %></span>
                                        </div>
                                        <a class="checkout-btn text-center d-block" href="{{route('showCart')}}">Checkout</a>
                                    </script>
                                    <script type="text/template" id="empty_cart_template">
                                        <div class="row">
                                            <div class="col-12 text-center pb-3">
                                                <img class="w-50 pt-3 pb-1" src="{{ asset('front-assets/images/ic_emptycart.svg') }}" alt="">
                                                <h5>Your cart is empty<br/>Add an item to begin</h5>
                                            </div>
                                        </div>
                                    </script>
                                    <div class="show-div shopping-cart flex-fill" id="header_cart_main_ul_ondemand"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
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
@endsection
@section('script')
<script src="{{asset('front-assets/js/rangeSlider.min.js')}}"></script>
<script src="{{asset('front-assets/js/my-sliders.js')}}"></script>
<script>
    jQuery(window).scroll(function() {    
    var scroll = jQuery(window).scrollTop();
   
   if(scroll >= 900) {
   jQuery(".categories-product-list").addClass("fixed-bar");
   } else {
   jQuery(".categories-product-list").removeClass("fixed-bar");
   }
   }); //
</script>
<script>
    
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
    var getTimeSlotsForOndemand = "{{route('getTimeSlotsForOndemand')}}";
    var update_cart_schedule = "{{route('cart.updateSchedule')}}";
    var showCart = "{{route('showCart')}}";
    var update_addons_in_cart = "{{route('addToCartAddons')}}";
    var addonids = [];
    var addonoptids = [];

 
   
</script>
@endsection