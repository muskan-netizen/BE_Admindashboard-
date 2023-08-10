<style>
    .alInfoIocn .tooltiptext {
        visibility: hidden;
        width: 200px;
        background-color: black;
        color: #fff;
        text-align: center;
        padding: 5px 0;
        border-radius: 6px;
        position: absolute;
        z-index: 1;
        margin-left: 5px;
        margin-top: 5px;
    }

    .alInfoIocn {
        position: absolute;
        top: 0;
        right: 0;
        cursor: pointer;
    }

    .alInfoIocn:hover .tooltiptext {
        visibility: visible;
    }

    .cross-sell .img-outer-box.position-relative img,
    .upsell-sell .img-outer-box.position-relative img {
        position: absolute;
        height: 100%;
        width: 100%;
        object-fit: cover;
    }

    .cross-sell .img-outer-box.position-relative,
    .upsell-sell .img-outer-box.position-relative {
        padding-bottom: 100%;
    }

    .cross-sell .media-body,
    .upsell-sell .media-body {
        padding: 0 10px;
    }

    .cross-sell .media-body .product-description,
    .upsell-sell .media-body .product-description {
        text-align: left;
        padding: 0;
    }

    .cross-sell .slick-slide>div {
        margin: 0 12px;
    }

    .order-user-name p {
        display: inline-block;
    }

    .order-user-name {
        background: #eeeeee;
        padding: 6px 6px;
        border-radius: 4px;
    }

    .cart-checkout_btn #order_placed_btn {
        padding: 10px 5px !important;
        display: inline-block;
        font-size: 14px !important;
    }

</style>

@php
$serviceType = Session::get('vendorType');

$additionalPreference = $getAdditionalPreference;
$is_service_product_price_from_dispatch_forOnDemand = 0;
$hidden_token = '';
if ($additionalPreference['is_token_currency_enable'] == 1) {
$hidden_token = 'd-none';
}
$getOnDemandPricingRule = getOnDemandPricingRule($serviceType, (@Session::get('onDemandPricingSelected') ?? ''),$additionalPreference);
// if(($additionalPreference['is_service_product_price_from_dispatch'] == 1) && ( Session::get('vendorType') == 'on_demand')){
// $is_service_product_price_from_dispatch_forOnDemand =1;
// }
$is_service_product_price_from_dispatch_forOnDemand = $getOnDemandPricingRule['is_price_from_freelancer'] ?? 0;

@endphp

@if ($cart_details->totalQuantity <= 0) <div class="container">
    <div class="row mt-2 mb-4 mb-lg-5">
        <div class="col-12 text-center">
            <div class="cart_img_outer" style="height:200px;">
                <img class="blur-up lazyload" data-src="{{ asset('front-assets/images/empty_cart.png') }}">
            </div>
            <h3>{{ __('Your Cart Is Empty!') }}</h3>
            <p>{{ __('Add items to it now.') }}</p>
            <a class="btn btn-solid" href="{{ url('/') }}">{{ __('Continue Shopping') }}</a>
        </div>
    </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-8 left-item">
            <div class="item">
                <div class="select_product">
                    <h2>Select Protection</h2>
                    <h5>Included in your booking</h5>
                    <ul class="d-flex">
                        <li><img src="/yacht-images/check.png" alt="">Unlimited kilometers.</li>
                        <li><img src="/yacht-images/check.png" alt="">Included 24/7 breakdown assistance.</li>
                        <li><img src="/yacht-images/check.png" alt="">Included Third party insurance.</li>
                    </ul>
                    <div class="select_product_form">
                        <form>
                            <div class="form-group">
                                <input type="radio" name="product_select" checked name="" id="inclusive">
                                <label for="inclusive">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3><img src="/yacht-images/inclusive.png" alt="">All Inclusive</h3>
                                        <div class="price">
                                            <p><span>AED</span> 59.01 /day</p>
                                        </div>
                                    </div>
                                    <p>Financial Responsibility:<span> $0.00</span></p>
                                    <ul>
                                        <li><img src="/yacht-images/check.png" alt="">Loss damage waiver for collision damages, scratches, bumps and theft.</li>
                                        <li><img src="/yacht-images/check.png" alt="">Tire and Windshield Protection.</li>
                                        <li><img src="/yacht-images/check.png" alt="">Interior Protection.</li>
                                        <li><img src="/yacht-images/check.png" alt="">Personal accident protection.</li>
                                        <li><img src="/yacht-images/check.png" alt="">Mobility service.</li>
                                        <li><img src="/yacht-images/check.png" alt="">24/7 breakdown assistance.</li>
                                    </ul>
                                </label>
                                <span></span>
                            </div>

                            <div class="form-group">
                                <input type="radio" name="product_select" name="" id="smart">
                                <label for="smart">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3><img src="/yacht-images/inclusive.png" alt="">Smart</h3>
                                        <div class="price">
                                            <p><span>AED</span> 100/day</p>
                                        </div>
                                    </div>
                                    <p>Financial Responsibility: <span> $0.00</span></p>
                                    <ul>
                                        <li><img src="/yacht-images/check.png" alt="">Loss damage waiver for collision damages, scratches, bumps and theft.</li>
                                        <li><img src="/yacht-images/check.png" alt="">Tire and Windshield Protection.</li>
                                        <li><img src="/yacht-images/check.png" alt="">Interior Protection.</li>
                                        <li><img src="/yacht-images/check.png" alt="">Personal accident protection.</li>
                                        <li><img src="/yacht-images/check.png" alt="">Mobility service.</li>
                                        <li><img src="/yacht-images/check.png" alt="">24/7 breakdown assistance.</li>
                                    </ul>
                                </label>
                                <span></span>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="select_product mt-5 adone_item">
                    <h2>Choose Ad-Ons</h2>
                    <div class="grid">
                        <!-- 1 -->
                        <div class="item">
                            <div class="image">
                                <img src="/yacht-images/adone/1.png" alt="">
                            </div>
                            <div class="text">
                                <h6>Additional Driver</h6>
                                <p>Booking for 2 days</p>
                                <div class="d-flex justify-content-between">
                                    <span>AED 11.42 /day & driver</span>
                                    <div class="addcart_cta">
                                        <span class="minus">-</span>
                                        <span class="num"></span>
                                        <span class="plus" id="cart_plus">+</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 2 -->
                        <div class="item">
                            <div class="image">
                                <img src="/yacht-images/adone/2.png" alt="">
                            </div>
                            <div class="text">
                                <h6>Refueling/Recharging service</h6>
                                <p>Save time when you return your vehicle. Drop it off and we'll refuel (or recharge) for you and add the cost to your total.</p>
                                <div class="d-flex justify-content-between">
                                    <span>AED 20.77/one-time</span>
                                </div>
                            </div>
                        </div>
                        <!-- 3 -->
                        <div class="item">
                            <div class="image">
                                <img src="/yacht-images/adone/3.png" alt="">
                            </div>
                            <div class="text">
                                <h6>Interior protection</h6>
                                <p>Zero financial responsibility for damage to the Interiors</p>
                                <div class="d-flex justify-content-between">
                                    <span>$5.47/day</span>
                                </div>
                            </div>
                        </div>
                        <!-- 4 -->
                        <div class="item">
                            <div class="image">
                                <img src="/yacht-images/adone/4.png" alt="">
                            </div>
                            <div class="text">
                                <h6>Roadside Assistance</h6>
                                <p>Driving somewhere new? Guaranteed GPS navigation in your vehicle.</p>
                                <div class="d-flex justify-content-between">
                                    <!-- <span>AED 11.42 /day & driver</span> -->
                                </div>
                            </div>
                        </div>
                        <!-- 5 -->
                        <div class="item">
                            <div class="image">
                                <img src="/yacht-images/adone/5.png" alt="">
                            </div>
                            <div class="text">
                                <h6>Infant seat</h6>
                                <p>Infant seat protection and safety for infants and young children while traveling.</p>
                                <div class="d-flex justify-content-between">
                                    <span>$15 /day</span>
                                </div>
                            </div>
                        </div>
                        <!-- 6 -->
                        <div class="item">
                            <div class="image">
                                <img src="/yacht-images/adone/6.png" alt="">
                            </div>
                            <div class="text">
                                <h6>Toddler seat</h6>
                                <p>Toddler seat protection and safety for your older children while traveling.</p>
                                <div class="d-flex justify-content-between">
                                    <span>$26 /day</span>
                                </div>
                            </div>
                        </div>
                        <!-- 7 -->
                        <div class="item">
                            <div class="image">
                                <img src="/yacht-images/adone/7.png" alt="">
                            </div>
                            <div class="text">
                                <h6>Personal Accident Protection</h6>
                                <p>This insurance coverage that provides financial compensation.</p>
                                <div class="d-flex justify-content-between">
                                    <span>$50 /day</span>
                                </div>
                            </div>
                        </div>
                        <!-- 8 -->
                        <div class="item">
                            <div class="image">
                                <img src="/yacht-images/adone/8.png" alt="">
                            </div>
                            <div class="text">
                                <h6>Pick up and drop off</h6>
                                <p>The customer has the option to request for their car to be picked up at their location and reserved for drop off at the airport.</p>
                                <div class="d-flex justify-content-between">
                                    <span>$50 /day</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="select_product mt-5 adone_item">
                    <h2>Booking Option</h2>
                    <div class="bokking_form">
                        <form>
                            <div class="form-group">
                                <input type="radio" name="booking" value="" class="Booking" id="bestproce">
                                <label for="bestproce">
                                    <div class="image">
                                        <img src="/yacht-images/aed.png" alt="">
                                    </div>
                                    <div class="text">
                                        <h3>Best Price</h3>
                                        <p>Save money by paying now. Cancellation charges apply:</p>
                                        <ul>
                                            <li><img src="/yacht-images/check.png" alt="">Before scheduled pick-up time: AED 99</li>
                                            <li><img src="/yacht-images/check.png" alt="">After scheduled pick-up time: No refund</li>
                                        </ul>
                                        <span>Included</span>
                                    </div>
                                </label>
                                <span></span>
                            </div>

                            <div class="form-group">
                                <input type="radio" name="booking" value="" id="flexible">
                                <label for="flexible">
                                    <div class="image">
                                        <img src="/yacht-images/aed.png" alt="">
                                    </div>
                                    <div class="text">
                                        <h3>Flexible</h3>
                                        <p>Stay flexible by paying later.</p>
                                        <ul>
                                            <li><img src="/yacht-images/check.png" alt="">Pay at pick-up</li>
                                            <li><img src="/yacht-images/check.png" alt="">Free cancellation</li>
                                        </ul>
                                        <span>+ $14.63</span>
                                    </div>
                                </label>
                                <span></span>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-4 right">
            <div class="item ">
                <div class="booking_day">
                    <div class="text">
                        <div class="">
                            <h3>Ford Endeavour</h3>
                            <div class="productList">
                                <ul>
                                    <li><a href="">Manual</a></li>
                                    <li><a href="">Petrol</a></li>
                                    <li><a href="">5 Seats</a></li>
                                </ul>
                            </div>
                            <span>Booking for 2 days <i class="fa fa-angle-up	"></i></span>
                        </div>
                    </div>
                    <div class="image">
                        <img src="/yacht-images/product-cart.png" alt="">
                    </div>
                </div>
                <div class="booking_date">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="start_time inner_item">
                            <h6>Start :<span>2 PM</span></h6>
                            <h6>28 Nov’22</h6>
                            <p>28 Nov’22</p>
                        </div>
                        <div class="seleed_date">
                            <span>1 day</span>
                        </div>
                        <div class="end_time inner_item">
                            <h6>End :<span>2 PM</span></h6>
                            <h6>29 Nov’22</h6>
                            <p>29 Nov’22</p>
                        </div>
                    </div>
                    <ul>
                        <img src="/yacht-images/6.png">
                        <li><img src="/yacht-images/4.png" alt=""><span>Cheese Avenue, Chandigarh</span> Pickup</li>
                        <li><img src="/yacht-images/5.png" alt=""><span>CDCL, sector 28b, Chandigarh </span>DROP</li>
                    </ul>
                </div>
                <div class="rentalcharges Booking">
                    <h3>Booking Option</h3>
                    <div class="inner_item d-flex justify-content-between align-items-center">
                        <p>3 Rental Days > AED 90</p>
                        <span>AED 90</span>
                    </div>
                </div>
                <div class="rentalcharges rental border-0 protection d-none">
                    <h3>Select Protection</h3>
                    <div class="inner_item d-flex justify-content-between align-items-center">
                        <p>3 Rental Days > AED 90</p>
                        <span>AED 90</span>
                    </div>
                </div>
                <div class="rentalcharges d-none">
                    <h3>Rental Charges</h3>
                    <div class="inner_item d-flex justify-content-between align-items-center">
                        <p>3 Rental Days > AED 90</p>
                        <span>AED 90</span>
                    </div>
                </div>

                <div class="taxes_fees">
                    <h3>Taxes and Fees</h3>
                    <ul>
                        <li>WLTP Supplement <span>AED 1.96</span></li>
                        <li>Premium Location Fee <span>AED 48.18</span></li>
                        <li>Taxes <span>AED 45.92</span></li>
                        <li>Total(incl.tax) <span>AED 90</span></li>
                    </ul>
                </div>


            </div>
            <div class="confirm_cta">
                <a href="" title="">Confirm and Pay <i class="fa fa-angle-right"></i></a>
            </div>
        </div>
   @endif