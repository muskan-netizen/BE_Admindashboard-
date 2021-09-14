<div class="tap-top top-cls">
    <div>
        <i class="fa fa-angle-double-up"></i>
    </div>
</div>

<div class="modal fade single-vendor-order-modal" id="single_vendor_order_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="s_vendor_remove_cartLabel" style="background-color: rgba(0,0,0,0.8);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="s_vendor_remove_cartLabel">{{__('Remove Cart')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="m-0">{{__('You can only buy products for single vendor. Do you want to remove all your cart products to continue?')}}</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button>
        <button type="button" class="btn btn-solid" id="single_vendor_remove_cart_btn" data-cart_id="">{{__('Remove')}}</button>
      </div>
    </div>
  </div>
</div>

@php 
    $mapKey = '1234';
    $theme = \App\Models\ClientPreference::where(['id' => 1])->first();
    if($theme && !empty($theme->map_key)){
        $mapKey = $theme->map_key;
    }
    $webColor = '#ff4c3b';
    $darkMode = '';
    if(isset(Session::get('preferences')->theme_admin) && ucwords(session('preferences')->theme_admin) == 'Dark'){
        $darkMode = 'dark';
    }
@endphp
<script src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('front-assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
    var is_hyperlocal = 0;
    var selected_address = 0;
    var vendor_type = "delivery";
    @if(Session::has('vendorType'))
        vendor_type = "{{Session::get('vendorType')}}";
    @endif
    var autocomplete_url = "{{ route('autocomplete') }}";
    let stripe_publishable_key = '{{ $stripe_publishable_key }}';
    var login_url = "{{ route('customer.login') }}";
    var home_page_url = "{{ route('userHome') }}";
    let home_page_url2 = home_page_url.concat("/");
    var add_to_whishlist_url = "{{ route('addWishlist') }}";
    var show_cart_url = "{{ route('showCart') }}";
    var home_page_data_url = "{{ route('homePageData') }}";
    var client_preferences_url = "{{ route('getClientPreferences') }}";
    var check_isolate_single_vendor_url = "{{ route('checkIsolateSingleVendor') }}";
    let empty_cart_url = "{{route('emptyCartData')}}";
    var cart_details_url = "{{ route('cartDetails') }}";
    var delete_cart_url = "{{ route('emptyCartData') }}";
    var user_checkout_url= "{{ route('user.checkout') }}";
    var cart_product_url= "{{ route('getCartProducts') }}";
    var delete_cart_product_url= "{{ route('deleteCartProduct') }}";
    var change_primary_data_url = "{{ route('changePrimaryData') }}";
    var url1 = "{{ route('config.update') }}";
    var url2 = "{{ route('config.get') }}";
    var featured_product_language = "{{ __('Featured Product') }}";
    var new_product_language = "{{ __('New Product') }}";
    var on_sale_product_language = "{{ __('On Sale') }}";
    var best_seller_product_language = "{{ __('Best Seller') }}";
    var vendor_language = "{{ __('Vendors') }}";
    var brand_language = "{{ __('Brands') }}";
    // if((home_page_url != window.location.href) && (home_page_url2 != window.location.href)){
    //     $('.vendor_mods').hide();}
    // else{
    //     $('.vendor_mods').show();}
        
    @if(Session::has('selectedAddress'))
        selected_address = 1;
    @endif
    @if( Session::has('preferences') )
        @if( (isset(Session::get('preferences')->is_hyperlocal)) && (Session::get('preferences')->is_hyperlocal == 1) ) 
            is_hyperlocal = 1;
        @endif;
    @endif;
</script>
<script src="{{asset('assets/js/constants.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$mapKey}}&v=3.exp&libraries=places,drawing"></script>
<script src="{{asset('front-assets/js/popper.min.js')}}"></script>
<script src="{{asset('front-assets/js/slick.js')}}"></script>
<script src="{{asset('front-assets/js/menu.js')}}"></script>
<script src="{{asset('front-assets/js/lazysizes.min.js')}}"></script>
<script src="{{asset('front-assets/js/bootstrap.js')}}"></script>
<script src="{{asset('front-assets/js/jquery.elevatezoom.js')}}"></script>
<script src="{{asset('front-assets/js/underscore.min.js')}}"></script>
<script src="{{asset('front-assets/js/script.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
<script src="{{asset('js/location.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>

<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
