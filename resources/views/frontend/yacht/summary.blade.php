@extends('layouts.car-rental', [
'title' => 'Summary',
])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/azul.css')}}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
@section('content')
@php
    
@endphp
	<section class="product_cart">
		<div class="container" id="mycart">
			{{-- <div class="row">
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
												<input type="radio" name="booking" value="" class="Booking"  id="bestproce">
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
			</div> --}}
		</div>
	</section>
@endsection
<script>
var guest_cart = {{ $guest_user ? 1 : 0 }};
var business_type = "<?= $client_preference_detail->business_type; ?>";
    var scheduling_with_slots = "<?= $client_preference_detail->scheduling_with_slots; ?>";
    var off_scheduling_at_cart = "<?= $client_preference_detail->off_scheduling_at_cart; ?>";
	var update_cart_schedule = "{{route('cart.updateSchedule')}}";
	var payment_option_list_url = "{{route('payment.option.list')}}";
</script>

<script type="text/template" id="payment_method_tab_pane_template">
    <% if(payment_options == '') { %>
        <h6>{{__('Payment Options Not Avaialable')}}</h6>
    <% }else{ %>
        <div class="modal-body pb-0">
            <h5 class="text-17 mb-2">{{__('Debit From')}}</h5>
            <form method="POST" id="cart_payment_form">
                @csrf
                @method('POST')
                <% _.each(payment_options, function(payment_option, k){%>
                    <div class="" id="" role="tabpanel">
                        <label class="radio mt-2">
                            <%= payment_option.title %>
                            <input type="radio" name="cart_payment_method" id="radio-<%= payment_option.slug %>" value="<%= payment_option.id %>" data-payment_option_id="<%= payment_option.id %>">
                            <span class="checkround"></span>
                        </label>
                        <% if(payment_option.slug == 'stripe') { %>
                            <div class="col-md-12 mt-3 mb-3 stripe_element_wrapper option-wrapper d-none">
                                <div class="form-control">
                                    <label class="mb-0">
                                        <div id="stripe-card-element"></div>
                                    </label>
                                </div>
                                <span class="error text-danger" id="stripe_card_error"></span>
                            </div>
                        <% } %>
                        <% if(payment_option.slug == 'stripe_fpx') { %>
                            <div class="col-md-12 mt-3 mb-3 stripe_fpx_element_wrapper option-wrapper d-none">
                                <label for="fpx-bank-element">
                                    FPX Bank
                                </label>
                                <div class="form-control">
                                    <div id="fpx-bank-element">
                                      <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                </div>
                                <span class="error text-danger" id="stripe_fpx_error"></span>
                            </div>
                        <% } %>

                        <% if(payment_option.slug == 'stripe_ideal' ) { %>
                            <div class="col-md-12 mt-3 mb-3 stripe_ideal_element_wrapper option-wrapper d-none">
                                <label for="ideal-bank-element">
                                    iDEAL Bank
                                </label>
                                <div class="form-control">
                                    <div id="ideal-bank-element">
                                      <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                </div>

                                <span class="error text-danger"id="error-message"></span>
                            </div>
                        <% } %>
                        <% if(payment_option.slug == 'yoco') { %>
                            <div class="col-md-12 mt-3 mb-3 yoco_element_wrapper option-wrapper d-none">
                                <div class="form-control">
                                    <div id="yoco-card-frame">
                                    <!-- Yoco Inline form will be added here -->
                                    </div>
                                </div>
                                <span class="error text-danger" id="yoco_card_error"></span>
                            </div>
                        <% } %>
                        <% if(payment_option.slug == 'checkout') { %>
                            <div class="col-md-12 mt-3 mb-3 checkout_element_wrapper option-wrapper d-none">
                                <div class="form-control card-frame">
                                    <!-- form will be added here -->
                                </div>
                                <span class="error text-danger" id="checkout_card_error"></span>
                            </div>
                        <% } %>

                        <% if(payment_option.slug == 'payphone') { %>
                            <div class="col-md-12 mt-3 mb-3">
                                <div id="pp-button"></div>
                            </div>
                        <% } %>

                        <% if(payment_option.slug == 'plugnpay') { %>
                            <div class="col-md-12 mt-3 mb-3 plugnpay_element_wrapper option-wrapper d-none">
                                <div class="row no-gutters">
                                    <div class="col-12">
                                        <input type="text" min="4" max="32" style=" border-right: none;" class="form-control" id="plugnpay-name-element" placeholder="Enter card holder name" />
                                    </div>
                                    <div class="col-6">
                                        <input type="number" min="16" max="16" style=" border-right: none;" class="form-control" id="plugnpay-card-element" placeholder="Enter card Number" />
                                    </div>
                                    <div class="col-3">
                                        <input type="text" style=" border-left: none; border-right: none;" class="form-control" max="5"  id="plugnpay-date-element" placeholder="MM/YY" />
                                    </div>
                                    <div class="col-3">
                                        <input type="password" max="3" style=" border-left: none;"  class="form-control" id="plugnpay-cvv-element" placeholder="CVV" />
                                    </div>
                                     <div class="col-6">
                                        <input type="text" min="4" max="32" style=" border-right: none;" class="form-control" id="plugnpay-addr1-element" placeholder="Enter address"/>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" min="4" max="32" style=" border-right: none;" class="form-control" id="plugnpay-addr2-element" placeholder="Enter alternate address (optional)" />
                                    </div>
                                    <div class="col-6">
                                        <input type="text" min="4" max="32" style=" border-right: none;" class="form-control" id="plugnpay-zip-element" placeholder="Enter zip code"/>
                                    </div>
<div class="col-6">
                                        <input type="text" min="4" max="32" style=" border-right: none;" class="form-control" id="plugnpay-city-element" placeholder="Enter city name"/>
                                    </div>
<div class="col-6">
                                        <input type="text" min="4" max="32" style=" border-right: none;" class="form-control" id="plugnpay-state-element" placeholder="Enter state code e.g. NY"/>
                                    </div>
<div class="col-6">
                                        <input type="text" min="4" max="32" style=" border-right: none;" class="form-control" id="plugnpay-country-element" placeholder="Enter country code e.g. US"/>
                                    </div>
                                </div>

                                <span class="error text-danger" id="plugnpay_card_error"></span>
                            </div>
                        <% } %>

                <% if(payment_option.slug == 'azulpay') { %>
                    <div class="col-md-12 mt-3 mb-3 azulpay_element_wrapper option-wrapper d-none">
                        <div class="tab">
    <a class="tablinks active" onclick="clickHandle(event, 'Add-Card')" href="javascript:void(0);">Add Card</a>
    <a class="tablinks" onclick="clickHandle(event, 'Card-List')" href="javascript:void(0);">Card List</a>
  </div>

  <div id="Add-Card" class="tabcontent show" style="display:block">
     <div class="row no-gutters">
                            <div class="col-6">
                                <input type="text"  maxlength="16" style=" border-right: none;" class="form-control demoInputBox" id="azul-card-element" placeholder="Enter Card Number" />
                            </div>
                            <div class="col-3">
                                <input type="text" style=" border-left: none; border-right: none;" class="form-control demoInputBox" onkeyup="addSlashes(this)" maxlength=7  id="azul-date-element" placeholder="MM/YYYY" />
                            </div>
                            <div class="col-3">
                                <input type="password" max="4" style=" border-left: none;"  class="form-control demoInputBox" id="azul-cvv-element" placeholder="CVV" />
                            </div>
                        </div>
<div class="row">
<div class="col-md-4 save-card-custom">
                     <input type="checkbox" name="save_card" class="form-check-input" id="azul-save_card" value="1">
                                    <label for="azul-save_card" class="">{{ __('Save Card') }}</label>
            </div>
</div>
                        <span class="error text-danger" id="azul_card_error"></span>
  </div>
  <div id="Card-List" class="tabcontent">
  </div>
                    </div>
                <% } %>

            <% if(payment_option.slug == 'nmi') { %>
            <div class="col-md-12 mt-3 mb-3 nmi_element_wrapper option-wrapper d-none">
                <div class="row no-gutters">
                    <div class="col-6">
                    <input type="text"  maxlength="16" style=" border-right: none;" class="form-control demoInputBox" id="card-element-nmi" placeholder="Enter Card Number" />
                    </div>
                    <div class="col-3">
                    <input type="text" style=" border-left: none; border-right: none;" class="form-control demoInputBox" onkeyup="addSlashes(this)" maxlength=7  id="date-element-nmi" placeholder="MM/YYYY" />
                    </div>
                    <div class="col-3">
                    <input type="password" max="4" style=" border-left: none;"  class="form-control demoInputBox" id="cvv-element-nmi" placeholder="CVV" />
                    </div>
                    <span class="error text-danger" id="card_error_nmi"></span>
                </div>
            </div>
            <% } %>

            <% if(payment_option.slug == 'powertrans') { %>
                <div class="col-md-12 mt-3 mb-3 powertrans_element_wrapper option-wrapper d-none">
                    <div class="row no-gutters">
                        <div class="col-6">
                            <input type="number" min="16" maxlength="16" style=" border-right: none;" class="form-control" id="card-element-powertrans" placeholder="Enter card Number" required 
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                        </div>
                        <div class="col-3">
                            <input type="number" style=" border-left: none; border-right: none;" class="form-control" maxLength="4"  id="date-element-powertrans" placeholder="YYMM" required 
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                        </div>
                        <div class="col-3">
                            <input type="password" maxLength="4" style=" border-left: none;"  class="form-control" id="cvv-element-powertrans" placeholder="CVV" required />
                        </div>
                    </div>

                    <span class="error text-danger" id="card_error_powertrans"></span>
                </div>
            <% } %>

                    </div>
                <% }); %>
                {{-- <div class="" id="" role="tabpanel">
                    <label class="radio mt-2">
                        Apple Pay
                        <input type="radio" name="cart_payment_method" id="radio-paytab_apple_pay" value="100" data-payment_option_id="100">
                        <span class="checkround"></span>
                    </label>
                    <div class="col-md-12 mt-3 mb-3 paytab_apple_pay_element_wrapper option-wrapper d-none">
                        <button type="button" id="applepay-btn">Pay Now</button>
                        <span class="error text-danger" id="paytab_apple_pay_error"></span>
                    </div>
                </div> --}}
                <div class="payment_response">
                    <div class="alert p-0 m-0" role="alert"></div>
                </div>
            </form>
        </div>
        <div class="modal-footer d-block text-center pt-0">
            <div class="row">
                <div class="col-12 grn_popop-total_amt">
                    <label>{{ __('By placing this order I accept the') }}
                        <a href="{{ $terms ? route('extrapage', $terms->slug) : '#' }}"
                            target="_blank">{{ __('Terms And Conditions') }} </a>
                        {{ __('and have read the') }}
                        <a href="{{ $privacy ? route('extrapage', $privacy->slug) : '#' }}"
                            target="_blank">
                            {{ __('Privacy Policy') }}.
                        </a>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 p-0 d-flex flex-fill">
                    <button type="button" style="width:100%;" class="btn btn-solid ml-1 proceed_to_pay">{{__('Place Order')}}
                        <img style="width:5%; display:none;" id="proceed_to_pay_loader" src="{{asset('assets/images/loader.gif')}}"/>
                    </button>
                </div>
            </div>
        </div>
    <% } %>
</script>

