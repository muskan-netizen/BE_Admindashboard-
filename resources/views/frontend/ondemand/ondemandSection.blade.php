
@section('customcss')
<link defer type="text/css" href="{{asset('css/ondemand.css')}}" rel="stylesheet" id="bs-default-stylesheet" />
<style>
.home-serivces .step-indicator .step1 p{
    width: max-content;
}   
</style>
@endsection
@php
$add_to_cart =  route('addToCart') ;
$additionalPreference = getAdditionalPreference(['is_service_product_price_from_dispatch','is_service_price_selection']);
$getOnDemandPricingRule = getOnDemandPricingRule(Session::get('vendorType'), (@Session::get('onDemandPricingSelected') ?? ''),$additionalPreference);

$is_service_product_price_from_dispatch_forOnDemand = 0;
$category_type_idForNotShowshPlusMinus = ['12'];
if($getOnDemandPricingRule['is_price_from_freelancer']==1){
    $is_service_product_price_from_dispatch_forOnDemand =1;
    array_push($category_type_idForNotShowshPlusMinus,8);
}

@endphp
<section class="home-serivces" id="alSixHomeServices">
    <div class="container">
        <div class="row mb-lg-5 mb-md-4 mb-3">
        <!-- class="col-xl-8 offset-xl-2 replace class to col-md-12" -->
            <div class="col-md-12">
                <div class="step-indicator">

                    <div class="step step1 @if(app('request')->input('step') >= '1' || empty(app('request')->input('step'))) active @endif">
                        <div class="step-icon">1</div>
                        <p>{{__('Service Details')}}</p>
                    </div>

                    <div class="indicator-line  @if(app('request')->input('step') >= '1' && !empty(app('request')->input('step'))) active @endif"></div>

                    <div class="step step2  @if(app('request')->input('step') >= '2' && !empty(app('request')->input('step'))) active @endif">
                        <div class="step-icon">2</div>
                        <p>{{__('Date & Time')}}</p>
                    </div>

                    <div class="indicator-line  @if(app('request')->input('step') == '3' && !empty(app('request')->input('step'))) active @endif"></div>

                    <div class="step step3   @if(app('request')->input('step') == '3' && !empty(app('request')->input('step'))) active @endif">
                        <div class="step-icon">3</div>
                        <p>{{__('Payment')}}</p>
                    </div>

                </div>

                <div class="row mt-4">
                        <div class="col-md-8">
                        @if((app('request')->input('step') == '1' || empty(app('request')->input('step'))) && app('request')->input('addons') != 1)

                         <!-- Start Main Nav -->
                            @if(!empty($category->childs) && count($category->childs) > 0)
                            <nav id='main-nav'>
                                <ul id='main-nav-list'>
                                @if(!empty($category->childs) && count($category->childs) > 0)
                                        @foreach ($category->childs as $key => $childs)

                                            @if( in_array($childs->type_id , [8,12]))
                                            <li><a href="#section_set{{$key}}">{{ $childs['translation_name'] ?? ''}}</a></li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </nav>
                            @endif
                        <!-- End Main Nav -->

                        @endif

                        <div class="card-box">
                                     <!-- static html -->

                                @if(app('request')->input('step') == '1' || empty(app('request')->input('step')))
                                     @if(!empty($category->childs) && count($category->childs) > 0)

                                    <!-- Start Conent Wrapper -->
                                    <div id='main-wrapper '  class="@if(app('request')->input('addons') == 1) d-none @endif">
                                                @foreach ($category->childs as $key => $childs)
                                               
                                                @if( in_array($childs->type_id , [8,12]))

                                                <h4><b>{{ $childs->translation_name }}</b></h4>
                                                    <div class='' id='section_set{{$key}}'>
                                                        @if(!empty($childs))
                                                        <div class="service-img mb-3">
                                                            <img class="img-fluid" src="{{$childs->image['proxy_url'] . '1000/300' . $childs->image['image_path']}}" alt="">
                                                        </div>
                                                        @endif


                                                            @foreach ($childs->products as $data)

                                                            @php
                                                                $data->translation_title = (!empty($data->translation->first())) ? $data->translation->first()->title : $data->sku;
                                                                $data->translation_description = (!empty($data->translation->first())) ? $data->translation->first()->body_html : $data->sku;
                                                                $data->variant_multiplier = (!empty($clientCurrency)) ? $clientCurrency->doller_compare : 1;
                                                                $data->variant_price = (!empty($data->variant->first())) ? $data->variant->first()->price : 0;
                                                                $productInquiryCheck = $data->inquiry_only  ; 
                                                                $redirec = ($data->is_recurring_booking ==1) ? route('productDetail', [@$data->vendor->slug, $data->url_slug]) : 'javascript:void(0)' ;
                                                                $class = ($data->is_recurring_booking ==1) ? 'add_on_demand_btn' : 'add_on_demand' ;
                                                                $data->category_type_id = $childs->type_id
                                                            @endphp

                                                            <div class="row classes_wrapper no-gutters align-items-center" href="#">
                                                                <div class="col-md-9 col-sm-8 pr-md-2 ">
                                                                    <h5 class="mb-1"><b>{!! (!empty($data->translation->first())) ? $data->translation->first()->title : $data->sku !!}</b></h5>
                                                                    <div class="productDetails pr-2">
                                                                        <p class="mb-1 ">{!! (!empty($data->translation->first())) ? $data->translation->first()->body_html : $data->sku !!}</p>
                                                                    </div>
                                                                    
                                                                </div>




                                                                <div class="col-md-3 col-sm-4 mb-sm-0 mb-3">
                                                                    <?php $imagePath = $imagePath2 = '';
                                                                        $mediaCount = count($data->media);
                                                                        for ($i = 0; $i < $mediaCount && $i < 2; $i++) {
                                                                            if($i == 0){
                                                                                $imagePath = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                                            }
                                                                            $imagePath2 = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                                        } ?>
                                                                    <div class="class_img">
                                                                        @if($imagePath != '')
                                                                        <img src="{{$imagePath}}" alt="">
                                                                        @else

                                                                        @endif

                                                                    </div>
                                                                </div>
                                                                <div class="col-12 ac-royo-btn">
                                                                    <div class="d-flex align-items-center justify-content-between productBookingBtns">
                                                                        @if($productInquiryCheck == 0)
                                                                    
                                                                            <h5 class="my-sm-0 my-3">
                                                                                @if($is_service_product_price_from_dispatch_forOnDemand !=1)
                                                                                    {{Session::get('currencySymbol').(decimal_format($data->variant_price * $data->variant_multiplier))}}
                                                                                    <span class="alProductViewPriceMin"> {{ $data->minimum_duration_min > 0 ? $data->minimum_duration_min . __(' min') : '' }}</span>
                                                                                @endif
                                                                            </h5>
                                                                       

                                                                            @if( (isset($data->variant[0]->checkIfInCart) && count($data->variant[0]->checkIfInCart) > 0) )
                                                                            @php
                                                                                $cartcount = 1;
                                                                            @endphp
                                                                            @if(   $is_service_product_price_from_dispatch_forOnDemand ==1)


                                                                                                                                                            
                                                                            <a class="btn btn-solid btn btn-solid view_on_demand_price"  style="display:none;" id="add_button_href{{$data->variant[0]->checkIfInCart['0']['id']}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('view Price') }}</a>
                                                                            @else
                                                                                <a class="btn btn-solid {{  $class }}" style="display:none;" id="add_button _href{{$data->variant[0]->checkIfInCart['0']['id']}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="{{ $redirec }}">Add <i class="fa fa-plus"></i></a>
                                                                            @endif

                                                                            @if(
                                                                                isset($data->category_type_id) && 
                                                                                ( 
                                                                                    (!in_array($data->category_type_id,$category_type_idForNotShowshPlusMinus)) 
                                                                                )  
                                                                            )
                                                                                <div class="number" id="show_plus_minus{{$data->variant[0]->checkIfInCart['0']['id']}}">
                                                                                    <span class="minus qty-minus-ondemand sd"  data-parent_div_id="show_plus_minus{{$data->variant[0]->checkIfInCart['0']['id']}}" data-id="{{$data->variant[0]->checkIfInCart['0']['id']}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                                                    </span>
                                                                                    <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="{{$data->variant[0]->checkIfInCart['0']['quantity']}}" class="input-number" step="0.01" id="quantity_ondemand_{{$data->variant[0]->checkIfInCart['0']['id']}}" readonly>
                                                                                    <span class="plus qty-plus-ondemand"  data-id="{{$data->variant[0]->checkIfInCart['0']['id']}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                                    </span>
                                                                                </div>
                                                                                @else
                                                                                    <a class="btn btn-solid " id="added_button_href{{$data->variant[0]->checkIfInCart['0']['id']}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('Added') }}</a>
                                                                                @endif

                                                                            @else
                                                                            

                                                                            @if(   $is_service_product_price_from_dispatch_forOnDemand ==1)
                                                                                    
                                                                                <a class="btn btn-solid btn btn-solid view_on_demand_price"  id="add_button_href{{$data->id }}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('view Price') }}</a>
                                                                            @else
                                                                              <a class="btn btn-solid {{  $class }}" id="add_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="{{ $redirec }}">{{ __('Add') }} <i class="fa fa-plus"></i></a>
                                                                            @endif 
                                                                            @if(
                                                                                isset($data->category_type_id) && 
                                                                                ( 
                                                                                    (!in_array($data->category_type_id,$category_type_idForNotShowshPlusMinus)) 
                                                                                )  
                                                                            )
                                                                                <div class="number" style="display:none;" id="ashow_plus_minus{{$data->id}}">
                                                                                    <span class="minus qty-minus-ondemand 132"  data-parent_div_id="show_plus_minus{{$data->id}}" readonly data-id="{{$data->id}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                                                    </span>
                                                                                    <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d{{$data->id}}" readonly placeholder="1" type="text" value="1" class="input-number input_qty" step="0.01">
                                                                                    <span class="plus qty-plus-ondemand"  data-id="" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                                    </span>
                                                                                </div>
                                                                            @else
                                                                                <a class="btn btn-solid "  style="display:none;" id="added_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('Added') }}</a>
                                                                            @endif 

                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <hr>
                                                            @endforeach

                                                        </div>
                                                @endif
                                                @endforeach

                                    </div>
                                    <!-- End Content Wrapper -->
                                    @endif
                                @endif



                            <!-- end statis html -->

                                    <!-- for single level category -->

                            @if(!empty($category->childs) && count($category->childs) == 0)

                                        @if(app('request')->input('step') == '1' || empty(app('request')->input('step')))
                                        <div class="outter-scroller  service-data-wrapper al @if(app('request')->input('addons') == 1) d-none @endif"  id="step-1-ondemand" >
                                            <div class="service-data">
                                                @if($category->translation_name !='')<h4><b>{{ $category->translation_name }}</b></h4>@endif


                                                @if(!empty($category->image))
                                                <div class="service-img mb-3">
                                                    <img class="img-fluid" src="{{$category->image['proxy_url'] . '1000/300' . $category->image['image_path']}}" alt="">
                                                </div>
                                                @endif
                                                @if($listData->isNotEmpty())
                                                @foreach($listData as $key => $data)

                                                {{-- new product design  --}}
                                                <div class="row classes_wrapper no-gutters align-items-center bg-primary" >

                                                    <div class="col-md-9 col-sm-8 pr-md-2" onclick="handleServiceClick()">
                                                        <h5 class="mb-1"><b>{!! $data->translation_title !!}</b></h5>
                                                        <span class="mb-1 font-weight-bold">{!! $data->vendor->name !!}</span>
                                                        <div class="productDetails pr-2">
                                                            <p class="mb-1">{!! $data->translation_description !!}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-4 mb-3" onclick="handleServiceClick()">
                                                        <?php $imagePath = $imagePath2 = '';
                                                            $mediaCount = count($data->media);
                                                            for ($i = 0; $i < $mediaCount && $i < 2; $i++) {
                                                                if($i == 0){
                                                                    $imagePath = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                                }
                                                                $imagePath2 = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                            } 
                                                            $productInquiryCheck = $data->inquiry_only  ; 
                                                            ?>
                                                        <div class="class_img">
                                                            @if($imagePath != '')
                                                            <img src="{{$imagePath}}" alt="">
                                                            @else

                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12 ac-royo-btn">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            @if($productInquiryCheck == 0)
                                                            @php
                                                                $cartcount = 0;
                                                                $redirec = ($data->is_recurring_booking ==1) ? route('productDetail', [@$data->vendor->slug, $data->url_slug]) : 'javascript:void(0)' ;
                                                                $class = ($data->is_recurring_booking ==1) ? 'add_on_demand_btn' : 'add_on_demand' ;
                                                            @endphp
                                                            @if(isset($data->variant[0]->checkIfInCart) && count($data->variant[0]->checkIfInCart) > 0)
                                                                @php
                                                                    $cartcount = 1;
                                                                @endphp
                                                            @endif
                                                                @if($is_service_product_price_from_dispatch_forOnDemand==1)
                                                                @if($cartcount > 0)
                                                                        <h5 class="my-sm-0 my-3 "></h5>
                                                                        <a class="btn btn-solid float-right"  id="added_button_href{{$data->variant[0]->checkIfInCart['0']['id']}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('Added') }}</a>
                                                                    <a class="btn btn-solid view_on_demand_price"  style="display:none;" id="add_button_href{{$data->variant[0]->checkIfInCart['0']['id']}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('view Price') }}</a>
                                                                    @else
                                                                    <h5 class="my-sm-0 my-3 "></h5>
                                                                    <a class="btn btn-solid view_on_demand_price"  id="add_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('view Price') }}</a>
                                                                    <a class="btn btn-solid float-right"  style="display:none;" id="added_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('Added') }}</a>
                                                                    @endif
                                                                
                                                                @else {{-- else is_service_product_price_from_dispatch --}}
                                                                  
                                                                    <h5 class="my-sm-0 my-3 ">
                                                                        {{Session::get('currencySymbol').(decimal_format($data->variant_price * $data->variant_multiplier))}}
                                                                    
                                                                        <span class="alProductViewPriceMin"> {{ $data->minimum_duration_min > 0 ? $data->minimum_duration_min . __(' min') : '' }}</span>
                                                                    </h5>

                                                                    @if($cartcount > 0)
                                                                    
                                                                        @if(isset($data->category_type_id) && (!in_array($data->category_type_id,[12])) )
                                                                            <a class="btn btn-solid  {{$class}} " style="display:none;" id="add_button_href{{$data->variant[0]->checkIfInCart['0']['id']}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="{{ $redirec }}">Add <i class="fa fa-plus"></i></a>
                                                                            <div class="number" id="show_plus_minus{{$data->variant[0]->checkIfInCart['0']['id']}}">
                                                                                <span class="minus qty-minus-ondemand 245"  data-parent_div_id="show_plus_minus{{$data->variant[0]->checkIfInCart['0']['id']}}" data-id="{{$data->variant[0]->checkIfInCart['0']['id']}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                                                </span>
                                                                                <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="{{$data->variant[0]->checkIfInCart['0']['quantity']}}" class="input-number" step="0.01" id="quantity_ondemand_{{$data->variant[0]->checkIfInCart['0']['id']}}" readonly>
                                                                                <span class="plus qty-plus-ondemand"  data-id="{{$data->variant[0]->checkIfInCart['0']['id']}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                                </span>
                                                                            </div>
                                                                        @else
                                                                            <a class="btn btn-solid " id="add_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('Added') }}</a>

                                                                        @endif
                                                                    @else
                                                                    <a class="btn btn-solid  {{$class}}" id="add_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="{{ $redirec }}">Add <i class="fa fa-plus"></i></a>
                                                                        @if(isset($data->category_type_id) && (!in_array($data->category_type_id,[12])) )
                                                                        <div class="number" style="display:none;" id="ashow_plus_minus{{$data->id}}">
                                                                            <span class="minus qty-minus-ondemand 256"  data-parent_div_id="show_plus_minus{{$data->id}}" readonly data-id="{{$data->id}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                                                            </span>
                                                                            <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d{{$data->id}}" readonly placeholder="1" type="text" value="1" class="input-number input_qty" step="0.01">
                                                                            <span class="plus qty-plus-ondemand"  data-id="" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                                            </span>
                                                                        </div>
                                                                        @else
                                                                                <a class="btn btn-solid " style="display:none;"   id="add_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('Added') }}</a>
                                                                        @endif
                                                                    @endif
                                                                
                                                                @endif {{-- end is_service_product_price_from_dispatch --}}
                                                            @endif

                                                            </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                @endforeach
                                                @if(count($listData))
                                                <div class="pagination pagination-rounded justify-content-end mb-0 page-m-20">
                                                    {{ $listData->links() }}
                                                </div>
                                                @endif
                                            @else
                                                <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{ __('No Product Found') }}</h5></div>
                                            @endif



                                            </div>
                                        </div>

                                        @endif
                            @endif
                            <!-- end single level category -->

                             <!-- Step if addons avilable  Html -->

                            @if(app('request')->input('addons') == '1' && app('request')->input('dateset') != '1')
                             <div id="step-4-ondemand">
                                @foreach ($cartData as $key => $cart_data)
                                    <!-- show add ons -->
                                     @if(!empty($cart_data->product->addOn) && $cart_data->product->addOn->count() > 0)
                                        <div class="border-product" id="addon_div{{$cart_data->id}}">
                                            <h4 class="mb-2"><b>{!! (!empty($cart_data->product->translation->first())) ? $cart_data->product->translation->first()->title : $cart_data->product->sku !!}</b></h4>
                                            @foreach($cart_data->product->addOn as $row => $addon)
                                            <div class="add-on-main-div">
                                                <h6 class="product-title">{{ $addon->addOnName->translation_one->title }}
                                                        @php
                                                            $min_select = '';
                                                            if($addon->addOnName->min_select > 0){
                                                                $min_select = 'Minimum '.$addon->addOnName->min_select;
                                                            }
                                                            $max_select = '';
                                                            $type_input = 'checkbox';
                                                            if($addon->addOnName->max_select > 0){
                                                                $max_select = 'Maximum '.$addon->addOnName->max_select;

                                                                if($addon->addOnName->max_select > 1)
                                                                $type_input = 'checkbox';
                                                                else
                                                                $type_input = 'radio';
                                                            }
                                                            if( ($min_select != '') && ($max_select != '') ){
                                                                $min_select = $min_select.' and ';
                                                            }
                                                        @endphp
                                                </h6>
                                                <span class="productAddonSetOptions" data-min="{{$addon->addOnName->min_select}}" data-cart_id="{{$cart_data->cart_id}}" data-cart_product_id="{{$cart_data->id}}" data-max="{{$addon->addOnName->max_select}}" data-addonset-title="{{$addon->addOnName->title}}">

                                                <div class="booking-time radio-btns long-radio mb-0">
                                                        @foreach($addon->setoptions as $k => $option)
                                                            @php $checked = ''; @endphp
                                                            @foreach ($cart_data->addon as $value)
                                                               @if($checked != 'checked')
                                                                    @if($addon->addon_id == $value->addon_id && $value->option_id == $option->id  && $value->cart_product_id  == $cart_data->id)
                                                                    @php $checked = 'checked'; @endphp
                                                                    @else
                                                                    @php $checked = ''; @endphp
                                                                    @endif
                                                               @endif

                                                            @endforeach
                                                            <div>
                                                                <div class="radios">
                                                                <input type="{{$type_input}}" class="productAddonOption " {{ $checked }} id="inlineCheckbox_{{$key}}{{$row.'_'.$k}}"  class="productAddonOption"  name="addonData{{$row}}[{{$cart_data->id}}][]" addonId="{{$addon->addon_id}}" addonOptId="{{$option->id}}"/>
                                                                    <label for='inlineCheckbox_{{$key}}{{$row.'_'.$k}}'>
                                                                        <span class="customCheckbox productAddonOptionspan_{{ $checked }}" aria-hidden="true">{{$option->translation_one->title .' ('.Session::get('currencySymbol').decimal_format($option->price,',').')' }} </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                </div>
                                                </span>
                                            </div>
                                        @endforeach
                                        <hr>
                                        </div>

                                     @endif
                                     <!-- end show add ons -->

                                @endforeach
                                  </div>
                            @endif
                             <!--Step if addons avilable  Html -->


                            <!-- Step Two Html -->


                            @if(app('request')->input('step') == '2')
                             
                                <div id="step-2-ondemand">
                                   @php
                                   $lastKey = count($cartData) - 1;
                                   $last_cart_product_id = '';
                                   @endphp
                                   {{ Arr::last($cartData)}}
                                    @foreach ($cartData as $cd => $cart_data)
                                        @php
                                         $last_cart_product_id =  $cart_data->id
                                         @endphp
                                        
                                        @if(in_array($cart_data->cateTypeId , [8,12]) && ($additionalPreference['is_service_product_price_from_dispatch'] !=1))
                                          
                                            @if(!empty($cart_data->product->mode_of_service) && $cart_data->product->mode_of_service == 'schedule')
                                                @php
                                                    $productDate = trim(date('Y-m-d', strtotime($cart_data->scheduled_date_time)));
                                                @endphp

                                               
                                            <div  id="date_time_set_div{{$cart_data->id}}" class="booking_date_section">

                                                <h4 class="mb-2" ><b>{{ __('When would you like your service?')}}</b></h4>
                                                @if(count($cart_data->period)>0)
                                                <div class="date-items radio-btns hide">

                                                    @foreach ($cart_data->period as $key => $date)
                                                        <div>
                                                            @php
                                                         
                                                            $checked = '';
                                                            $singleDate =  trim(date('Y-m-d', strtotime($date)));
                                                            if($productDate == $singleDate && !empty($productDate)){
                                                                $checked = "checked";
                                                            }
                                                            $dateRandNo = rand(10,100);
                                                            @endphp
                                                            <div class="radios">
                                                                <p>{{date('D', strtotime($date))}}</p>
                                                                <div class="alCustomHomeServiceRadio ">
                                                                    <input type="radio"  class="check-time-slots booking_date ondemand-time-slots ondemand_{{ $checked }}" data-product_vendor_id="{{$cart_data->vendor_id}}" data-cart_product_id = "{{$cart_data->id}}" data-product_id ="{{$cart_data->product->id}}" data-product_tag ="{{$cart_data->product->tags}}" data-product_category_type ="{{$cart_data->product->productcategory->type_id}}"  value='{{date('Y-m-d', strtotime($date))}}' name='booking_date_{{$cart_data->id}}' id='radio{{$cd}}{{$key}}{{ $dateRandNo }}' {{$checked }} @if(($key == 0 && $checked == "")) checked @endif />
                                                                    <label for='radio{{$cd}}{{$key}}{{$dateRandNo  }}'>
                                                                    <span class="customCheckbox" aria-hidden="true" >{{date('d', strtotime($date))}}</span>
                                                                    </label>
                                                                    <input type="hidden" name="productid" id="productid" value="{{$cart_data->id}}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                                @else
                                                <h5 class="text-center">{{ __("Vendor has not created slots for this Date yet.") }}</h5>
                                                @endif
                                               
                                                @if($cart_data->is_dispatch_slot == 1)
                                                @php

                                                $dispatch_agents = $cart_data->dispatchAgents ?? [];
                                                $cart_product_id = $cart_data->id;
                                                $dispatch_agent_id = @$cart_data->dispatch_agent_id;
                                                $show_dispatcher_agent = @$cart_data->product->is_show_dispatcher_agent;
                                                $selected_agent_id = @$cart_data->dispatch_agent_id;
                                                $schedule_slot = $cart_data->schedule_slot;

                                                @endphp
                                                <div class="booking-time-wrapper" id="show-all-time-slots{{$cart_data->id}}" >
                                                    {{-- style="@if($cart_data->schedule_slot != '')  @else display: none; @endif " --}}
                                                    @include('frontend.ondemand.dispatcher_agent_slots')
                                                </div>
                                                @else
                                                    @php
                                                    $time_slots = [];
                                                    $cart_product_id = $cart_data->id;
                                                    $schedule_slot = $cart_data->schedule_slot;
                                                    if(!empty($cart_data->timeSlots)){
                                                        $time_slots = $cart_data->timeSlots;
                                                    }
                                                    @endphp
                                                    <div class="booking-time-wrapper" id="show-all-time-slots{{$cart_data->id}}" ">
                                                        {{-- style="@if($cart_data->schedule_slot != '')  @else display: none; @endif   --}}
                                                        @include('frontend.ondemand.time-slots-for-date')
                                                    </div>
                                                    {{-- <div class="booking-time-wrapper" id="show-all-time-slots{{$cart_data->id}}" style="@if($cart_data->schedule_slot != '')  @else display: none; @endif ">
                                                        <h4 class="mt-4 mb-2"><b>{{__('What time would you like us to start?')}}</b></h4>

                                                        <div class="booking-time radio-btns long-radio mb-0">
                                                            @php
                                                            if(!empty($cart_data->timeSlots)){
                                                                $time_slots = $cart_data->timeSlots;
                                                            }
                                                            @endphp
                                                            @foreach ($time_slots as $key => $date)
                                                            @if($key+1 < count($time_slots))
                                                            @php
                                                            $checked='';
                                                                $slotTime = $date.' - '.@$time_slots[$key+1];
                                                                if(isset($cart_data->schedule_slot) && $cart_data->schedule_slot == $slotTime){
                                                                    echo $checked="checked";
                                                                }
                                                            @endphp
                                                            <div>
                                                                <div class="radios">
                                                                    <div class="alCustomHomeServiceRadio">
                                                                        <input type="radio" value='{{$date}} - {{@$time_slots[$key+1]}}' name='booking_time' id='time{{$cart_data->id}}{{$key+1}}' @if($checked) {{$checked}} @endif class="ondemand_{{$checked }}" />
                                                                        <label for='time{{$cart_data->id}}{{$key+1}}'>
                                                                            <span class="customCheckbox selected-time" aria-hidden="true"  data-value='{{$date}} - {{@$time_slots[$key+1]}}' data-cart_product_id='{{$cart_data->id}}'>{{$date}} - {{@$time_slots[$key+1]}}</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            @endforeach
                                                        </div>
                                                        <P id="message_of_time{{$cart_data->id}}"></P>
                                                    </div> --}}
                                                @endif


                                                <input type="hidden" class="custom-control-input check" id="taskschedule" name="task_type" value="schedule" checked>
                                            </div>
                                            <hr>
                                            @endif
                                        @endif   
                                    @endforeach

                                        <div class="booking-time-wrapper">
                                            <input type="hidden" id="last_cart_product_id" value="{{ $last_cart_product_id }}">
                                            <h4 class="mt-4 mb-2"><b>{{__('Do you have any specific instructions?')}}</b></h4>
                                            <textarea class="form-control" name="specific_instructions" id="specific_instructions" cols="30" rows="7"></textarea>
                                        </div>


                                </div>
                            @endif
                            <!--end step 2 html -->



                            @if(app('request')->input('step') == '3')
                            <!-- step 3 payment page -->
                            <form method="post" action="" id="placeorder_form_ondemand">
                                    @csrf
                                    <div class="card-box">
                                        <div class="row d-flex justify-space-around">
                                            @if(!$guest_user)
                                                <div class="col-lg-8 left_box">

                                                </div>
                                            @endif

                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-sm-6 text-md-right">
                                                <button id="order_placed_btn" class="btn btn-solid d-none" type="button" {{$addresses->count() == 0 ? 'disabled': ''}}>{{__('Continue')}}</button>
                                            </div>
                                        </div>
                                    </div>

                                </form>

                                <div class="col-sm-6 text-md-right">
                                    <button id="order_placed_btn" class="btn btn-solid d-none" type="button" {{$addresses->count() == 0 ? 'disabled': ''}}>{{__('Continue')}}</button>
                                </div>

                            @endif
                        </div>
                    </div>
                    
                    <!-- Cart Section -->
                    <div class="col-md-4 side-card">
                        <div class="card-box">
                            <div class="product-order">
                                <div class="total-sec border-0 py-0 my-0">
                                    {{-- <h5 class="d-flex align-items-center justify-content-between pb-2 border-bottom"><b>City</b><b>Dubai</b></h5> --}}
                                    <h5 class="d-flex align-items-center justify-content-between pb-2">{{__('SERVICE DETAILS')}} </h5>
                                </div>
                                <div class="spinner-box">
                                    <div class="circle-border">
                                        <div class="circle-core"></div>
                                    </div>
                                </div>

                                <script type="text/template" id="header_cart_template_ondemand">
                                        <% _.each(cart_details.products, function(product, key){%>
                                            <li class="alVendorName">
                                                <h6 class="d-flex align-items-center justify-content-between"> <%= product.vendor.name %> </h6>
                                            </li>

                                            <% if( (product.isDeliverable != undefined) && (product.isDeliverable == 0) ) { %>
                                                <li class="border_0">
                                                    <th colspan="7">
                                                        <div class="text-danger">
                                                        {{__('Products for this vendor are not deliverable at your area. Please change address or remove product.')}}
                                                        </div>
                                                    </th>
                                                </li>
                                                <% } %>
                                            <% _.each(product.vendor_products, function(vendor_product, vp){%>
                                                <li class="alVendorProductDetails" id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                                                        <div class='media-body'>
                                                            <h6 class="d-flex align-items-center justify-content-between">
                                                                <span class="ellips"><%= vendor_product.quantity %>x <%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></span>
                                                                <span>{{Session::get('currencySymbol')}}<%= Helper.formatPrice(vendor_product.pvariant.price) %></span>
                                                            </h6>
                                                        </div>

                                                    <div class='close-circle'>
                                                        <a  class="action-icon d-block mb-3 remove_product_via_cart" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>" data-product_id="<%= vendor_product.product.id %>">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                </li>

                                                <% if(vendor_product.addon.length != 0) { %>
                                                    <div class="row align-items-md-center m-0">
                                                        <div class="col-12 alVendorProductDetails">
                                                            <h6 class="m-0 pl-0"><b>{{__('Add Ons')}}</b></h6>
                                                        </div>
                                                    </div>
                                                    <% _.each(vendor_product.addon, function(addon, ad){%>
                                                    <div class="row alVendorProductDetails m-0">
                                                        <div class="col-md-3 col-sm-4 items-details text-left">
                                                            <p class="p-0 m-0"><%= addon.option.title %></p>
                                                        </div>
                                                        <div class="col-md-2 col-sm-4 text-center">
                                                            <div class="extra-items-price">{{Session::get('currencySymbol')}}<%=  Helper.formatPrice(addon.option.price_in_cart) %></div>
                                                        </div>
                                                        <div class="col-md-7 col-sm-4 text-right">
                                                            <div class="extra-items-price">{{Session::get('currencySymbol')}}<%=  Helper.formatPrice(addon.option.quantity_price) %></div>
                                                        </div>
                                                    </div>
                                                    <% }); %>
                                                <% } %>
                                                <hr class="my-2">


                                            <% }); %>
                                        <% }); %>


                                        @foreach ($cartData as $cd => $cart_data)
                                        @if(!empty($cart_data->product->mode_of_service) && $cart_data->product->mode_of_service == 'schedule')
                                        <h4 class="mb-2"><b>{!! (!empty($cart_data->product->translation->first())) ? $cart_data->product->translation->first()->title : $cart_data->product->sku !!}</b></h4>

                                        <h5 class="d-flex align-items-center justify-content-between pb-2">{{__('DATE & TIME')}} </h5>
                                        <li class="alVendorProductTotals">
                                            <div class='media-body'>
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Date')}}</span>
                                                    <span id="show_date{{$cart_data->id}}">@if(isset($cart_data->scheduled_date_time)) {{ date('d-m-Y', strtotime($cart_data->scheduled_date_time)) }}  @else -- @endif </span>
                                                </h6>
                                            </div>
                                        </li>

                                        <li class="alVendorProductTotals">
                                            <div class='media-body'>
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Start Time')}}</span>
                                                    <span id="show_time{{$cart_data->id}}">@if(isset($cart_data->scheduled_date_time)) {{ $cart_data->schedule_slot }}  @else -- @endif</span>
                                                </h6>
                                            </div>
                                        </li>
                                        @endif
                                        @endforeach

                                        <h5 class="d-flex align-items-center justify-content-between pb-2">{{__('PRICE DETAILS')}} </h5>
                                        <li class="alVendorProductTotals">
                                            <div class='media-body'>
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Price')}}</span>
                                                    <span>{{Session::get('currencySymbol')}}<%=  Helper.formatPrice(cart_details.sub_total) %></span>
                                                </h6>
                                            </div>
                                        </li>

                                        <li class="alVendorProductTotals">
                                            <div class='media-body'>
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Delivery Charges')}}</span>
                                                    <span>{{Session::get('currencySymbol')}}<%=  Helper.formatPrice(cart_details.delivery_charges) %></span>
                                                </h6>
                                            </div>
                                        </li>

                                        <li class="alVendorProductTotals">
                                            <div class='media-body'>
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Tax')}}</span>
                                                    <span>{{Session::get('currencySymbol')}}<%= cart_details.total_taxable_amount %></span>
                                                </h6>
                                            </div>
                                        </li>

                                        <% if(cart_details.loyalty_amount > 0) { %>
                                        <li class="alVendorProductTotals">
                                            <div class='media-body'>
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Loyalty Amount')}} </span>
                                                    <span>{{Session::get('currencySymbol')}}<%=  Helper.formatPrice(cart_details.loyalty_amount) %></span>
                                                </h6>
                                            </div>
                                        </li>
                                        <% } %>

                                        <li class="alVendorProductTotals">
                                            <div class='media-body'>
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Total')}}</span>
                                                    <span>{{Session::get('currencySymbol')}}<%=  Helper.formatPrice(cart_details.total_payable_amount) %></span>
                                                </h6>
                                            </div>
                                        </li>

                                 </script>
                                 <ul class="show-div shopping-cart d-none" id="header_cart_main_ul_ondemand">
                                 </ul>


                            </div>
                        </div>
                        <div class="footer-card">
                            @if((app('request')->input('step') == '1' || empty(app('request')->input('step'))) && empty(app('request')->input('addons')))
                                <a href="?step=2" id="next-button-ondemand-2" style="display: none;"><span class="btn btn-solid float-right">{{__('Next')}}</span></a>
                                @elseif(app('request')->input('step') == '1' && app('request')->input('addons') == '1')
                                    <a href="?step=1"><span class="btn btn-solid float-left"><</span></a>
                                    <a href="?step=2&dateset=1&addons=1" id="next-button-ondemand-2"><span class="btn btn-solid float-right">{{__('Next')}}</span></a>
                                @elseif(app('request')->input('step') == '2' && empty(app('request')->input('addons')))
                                    <a href="?step=1"><span class="btn btn-solid float-left"><</span></a>
                                    @if(Auth::guest())
                                        <a href="{{route('customer.login')}}" id="next-button-ondemand-3"><span class="btn btn-solid float-right">Continue</span></a>
                                    @else
                                        <a href="#" id="next-button-ondemand-3"><span class="btn btn-solid float-right">Continue</span></a>
                                    @endif
                                @elseif(app('request')->input('step') == '2' && !empty(app('request')->input('dateset')))
                                        <a href="?step=1"><span class="btn btn-solid float-left"><</span></a>
                                        @if(Auth::guest())
                                            <a href="{{route('customer.login')}}" id="next-button-ondemand-3" ><span class="btn btn-solid float-right">Continue</span></a>
                                        @else
                                            <a href="#" id="next-button-ondemand-3"><span class="btn btn-solid float-right">Continue</span></a>
                                        @endif
                                @elseif(app('request')->input('step') == '3')
                                    <a href="?step=2"><span class="btn btn-solid"><</span></a>
                                    <a href="?step=3" id="next-button-ondemand-4"><span class="btn btn-solid float-right">Continue</span></a>
                                @else
                            @endif
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


{{-- Add this modal at the end of your section, before the closing </section> tag --}}
<!-- Product Detail Modal -->

<!-- <div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    
    <div>

    </div>
    <div>

    </div>
</div> -->
<div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productDetailModalLabel">Service Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Product Image Section -->
                        <div class="col-md-6 mb-4">
                            <div id="productCarousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner" id="modalCarouselInner">
                                    <!-- Images will be loaded here dynamically -->
                                </div>
                                <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Product Details Section -->
                        <div class="col-md-6">
                            <h4 id="modalProductTitle" class="mb-3"></h4>
                            <p id="modalProductVendor" class="text-muted mb-3"></p>
                           
                            
                            <!-- Pricing Section -->
                            <div class="price-section mb-4">
                                <h5 id="modalProductPrice" class="text-primary mb-2"></h5>
                                <p id="modalProductDuration" class="text-muted"></p>
                            </div>
                            
                            <!-- Add to Cart Button -->
                            <div id="modalProductButtons" class="mt-4">
                                <!-- Buttons will be loaded here dynamically -->
                            </div>
                             <div id="modalProductDescription" class="mb-4"></div>
                            <!-- Additional Information -->
                            <div class="additional-info mt-4 pt-4 border-top">
                                <h6 class="mb-3">Service Information:</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> Professional Service</li>
                                    <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> Verified Professionals</li>
                                    <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> Quality Guaranteed</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="#" id="modalBookNowBtn" class="btn btn-primary">Book Now</a>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Modal Styles */
#productDetailModal .modal-lg {
    max-width: 900px;
}
#productDetailModal .carousel-inner {
    border-radius: 8px;
    overflow: hidden;
}
#productDetailModal .carousel-item img {
    width: 100%;
    height: 300px;
    object-fit: cover;
}
#productDetailModal .price-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}
#productDetailModal .additional-info {
    font-size: 14px;
}
#productDetailModal .modal-footer {
    border-top: 1px solid #dee2e6;
}
.product-clickable {
    cursor: pointer;
    transition: all 0.3s ease;
}
.product-clickable:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>

@section('custom-js')
<script src="{{ asset('js/onDemand/GetDispatcherPrice.js') }}"></script>
<script src="{{ asset('js/onDemand/AgentSlot.js') }}"></script>

<script>
// Define decimal_format function if it doesn't exist
if (typeof decimal_format === 'undefined') {
    function decimal_format(number, decimals = 2, decimal_separator = '.', thousands_separator = ',') {
        number = parseFloat(number) || 0;
        const fixed = number.toFixed(decimals);
        const parts = fixed.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_separator);
        return parts.join(decimal_separator);
    }
}

// Function to extract price from text
function extractPriceFromText(text) {
    if (!text) return 0;
    
    // Remove currency symbol and commas, then parse
    text = text.replace(/[^\d.-]/g, '');
    const price = parseFloat(text);
    return isNaN(price) ? 0 : price;
}

// Function to extract duration from text
function extractDurationFromText(text) {
    if (!text) return 0;
    const match = text.match(/(\d+)\s*min/);
    return match ? parseInt(match[1]) : 0;
}

function handleServiceClick(event) {
    // Get the clicked product row
    const productRow = $(event.currentTarget).closest('.classes_wrapper');
    if (!productRow.length) return;
    
    // Extract product data from the row
    extractProductDataFromRow(productRow);
}

function extractProductDataFromRow($productRow) {
    try {
        console.log('Extracting data from row:', $productRow);
        
        // Extract title
        const title = $productRow.find('h5 b').first().text().trim() || 
                     $productRow.find('h5').first().text().trim() || 
                     'Service';
        
        console.log('Title:', title);
        
        // Extract vendor name
        let vendorName = 'Service Provider';
        const vendorElement = $productRow.find('.font-weight-bold');
        if (vendorElement.length) {
            vendorName = vendorElement.text().trim();
        }
        console.log('Vendor:', vendorName);
        
        // Extract description
        let description = 'No description available';
        const descriptionElement = $productRow.find('.productDetails p');
        if (descriptionElement.length) {
            description = descriptionElement.html() || descriptionElement.text() || 'No description available';
        }
        console.log('Description:', description);
        
        // Extract price - look in multiple places
        let variant_price = 0;
        
        // First try to get price from h5 in the productBookingBtns section
        const priceElement = $productRow.find('.productBookingBtns h5');
        if (priceElement.length) {
            const priceText = priceElement.text();
            console.log('Price text found:', priceText);
            variant_price = extractPriceFromText(priceText);
        }
        
        // If not found, check other h5 elements
        if (!variant_price) {
            $productRow.find('h5').each(function() {
                const text = $(this).text();
                const price = extractPriceFromText(text);
                if (price && price > variant_price) {
                    variant_price = price;
                }
            });
        }
        
        console.log('Price extracted:', variant_price);
        
        // Extract duration
        let minimum_duration_min = 0;
        const durationSpan = $productRow.find('.alProductViewPriceMin');
        if (durationSpan.length) {
            minimum_duration_min = extractDurationFromText(durationSpan.text());
        }
        console.log('Duration:', minimum_duration_min);
        
        // Extract product ID and other attributes from buttons
        let productId = '';
        let variantId = '';
        let vendorId = '';
        let isRecurring = 0;
        let isPriceFromDispatch = 0;
        let inquiryOnly = 0;
        
        // Try to get from add button
        const addButton = $productRow.find('.add_on_demand, .view_on_demand_price, .btn-solid');
        if (addButton.length) {
            productId = addButton.data('product_id') || 
                       addButton.attr('id')?.replace('add_button_href', '')?.replace('added_button_href', '') || '';
            variantId = addButton.data('variant_id') || '';
            vendorId = addButton.data('vendor_id') || '';
            
            console.log('Button data:', {productId, variantId, vendorId});
            
            // Check button type
            if (addButton.hasClass('add_on_demand_btn')) {
                isRecurring = 1;
            }
            if (addButton.hasClass('view_on_demand_price')) {
                isPriceFromDispatch = 1;
            }
            
            // Check for inquiry button
            const inquiryButton = $productRow.find('a[href*="inquiry"]');
            if (inquiryButton.length) {
                inquiryOnly = 1;
            }
        }
        
        // Extract images
        const media = [];
        const imageElements = $productRow.find('.class_img img');
        imageElements.each(function() {
            const src = $(this).attr('src');
            if (src && src.trim() !== '') {
                media.push({
                    image: {
                        path: {
                            proxy_url: '',
                            image_path: src
                        }
                    }
                });
            }
        });
        
        console.log('Images found:', media.length);
        
        // If no images found, add a placeholder
        if (media.length === 0) {
            media.push({
                image: {
                    path: {
                        proxy_url: '',
                        image_path: 'https://via.placeholder.com/600x400?text=No+Image+Available'
                    }
                }
            });
        }
        
        // Get vendor slug from button href
        let vendorSlug = '';
        let urlSlug = '';
        const bookButton = $productRow.find('.add_on_demand_btn');
        if (bookButton.length && bookButton.attr('href')) {
            const href = bookButton.attr('href');
            const pathParts = href.split('/').filter(p => p);
            if (pathParts.length >= 2) {
                vendorSlug = pathParts[pathParts.length - 2];
                urlSlug = pathParts[pathParts.length - 1];
            }
        }
        
        // Prepare product data object
        const productData = {
            id: productId || 'temp_' + Date.now(),
            title: title,
            vendor: {
                name: vendorName,
                slug: vendorSlug
            },
            description: description,
            variant_price: variant_price,
            variant_multiplier: 1,
            currencySymbol: '{{ Session::get("currencySymbol") }}',
            minimum_duration_min: minimum_duration_min,
            is_recurring_booking: isRecurring,
            url_slug: urlSlug,
            media: media,
            variant_id: variantId,
            vendor_id: vendorId,
            is_service_product_price_from_dispatch_forOnDemand: isPriceFromDispatch,
            inquiry_only: inquiryOnly
        };
        
        console.log('Final product data:', productData);
        populateModal(productData);
        $('#productDetailModal').modal('show');
        
    } catch (error) {
        console.error('Error extracting product data:', error);
        showError('Error loading product details. Please try again.');
        $('#productDetailModal').modal('show');
    }
}

function populateModal(product) {
    try {
        console.log('Populating modal with:', product);
        
        // Set title
        $('#modalProductTitle').text(product.title || 'Service');
        
        // Set vendor
        $('#modalProductVendor').text('By ' + (product.vendor?.name || 'Service Provider'));
        
        // Set description
        if (product.description && product.description !== 'No description available') {
            // Clean HTML and ensure proper formatting
            let cleanDescription = product.description
                .replace(/<br\s*\/?>/gi, '<br>')
                .replace(/<p><\/p>/gi, '')
                .trim();
            
            if (cleanDescription === '' || cleanDescription === '<p></p>') {
                cleanDescription = '<p class="text-muted">No detailed description available.</p>';
            }
            
            $('#modalProductDescription').html(cleanDescription);
        } else {
            $('#modalProductDescription').html('<p class="text-muted">No detailed description available.</p>');
        }
        
        // Set price
        if (product.variant_price && product.variant_price > 0) {
            const price = product.variant_price * (product.variant_multiplier || 1);
            const formattedPrice = decimal_format(price);
            $('#modalProductPrice').html(`<span class="h4">${product.currencySymbol || '₹'}${formattedPrice}</span>`);
        } else if (product.is_service_product_price_from_dispatch_forOnDemand == 1) {
            $('#modalProductPrice').html('<span class="h4">Price on Dispatch</span>');
        } else {
            $('#modalProductPrice').html('<span class="h4">Contact for Price</span>');
        }
        
        // Set duration
        if (product.minimum_duration_min && product.minimum_duration_min > 0) {
            $('#modalProductDuration').text('Minimum duration: ' + product.minimum_duration_min + ' minutes');
        } else {
            $('#modalProductDuration').text('Flexible duration');
        }
        
        // Load images
        loadProductImages(product.media || []);
        
        // Set up buttons
        setupModalButtons(product);
        
        // Set book now button
        const bookBtn = $('#modalBookNowBtn');
        if (product.id && product.vendor_id) {
            let bookUrl = 'javascript:void(0)';
            let btnClass = 'add_on_demand';
            let btnText = 'Book Now';
            
            if (product.is_recurring_booking == 1 && product.vendor?.slug && product.url_slug) {
                bookUrl = '/product/' + product.vendor.slug + '/' + product.url_slug;
                btnClass = 'add_on_demand_btn';
                btnText = 'View Details';
            }
            
            if (product.is_service_product_price_from_dispatch_forOnDemand == 1) {
                btnClass = 'view_on_demand_price';
                btnText = 'View Price';
            }
            
            if (product.inquiry_only == 1) {
                btnText = 'Inquiry Only';
                bookUrl = 'javascript:void(0)';
            }
            
            bookBtn
                .attr('href', bookUrl)
                .attr('data-product_id', product.id)
                .attr('data-variant_id', product.variant_id || '')
                .attr('data-vendor_id', product.vendor_id || '')
                .attr('data-add_to_cart_url', '{{ $add_to_cart }}')
                .removeClass('add_on_demand add_on_demand_btn view_on_demand_price btn-primary btn-secondary')
                .addClass(btnClass + ' btn-primary')
                .text(btnText);
                
            // Add click handler for non-redirecting buttons
            if (bookUrl === 'javascript:void(0)' && product.inquiry_only != 1 && product.is_service_product_price_from_dispatch_forOnDemand != 1) {
                bookBtn.off('click').on('click', function(e) {
                    e.preventDefault();
                    addToCartFromModal(this);
                });
            }
        }
        
    } catch (error) {
        console.error('Error populating modal:', error);
        showError('Error displaying product details. Please try again.');
    }
}

function loadProductImages(mediaArray) {
    const carouselInner = $('#modalCarouselInner');
    carouselInner.empty();
    
    if (mediaArray && mediaArray.length > 0) {
        let hasValidImages = false;
        
        mediaArray.forEach((media, index) => {
            let imageUrl = '';
            
            // Handle different media object structures
            if (media.image?.path?.proxy_url && media.image.path.image_path) {
                imageUrl = media.image.path.proxy_url + media.image.path.image_path;
            } else if (media.image?.path?.image_path) {
                imageUrl = media.image.path.image_path;
            } else if (media.image_path) {
                imageUrl = media.image_path;
            } else if (media.url) {
                imageUrl = media.url;
            } else if (typeof media === 'string') {
                imageUrl = media;
            }
            
            console.log('Processing image:', imageUrl);
            
            // If we have a valid image URL
            if (imageUrl && imageUrl.trim() !== '') {
                const itemClass = hasValidImages ? 'carousel-item' : 'carousel-item active';
                const imgHtml = `
                    <div class="${itemClass}">
                        <img class="d-block w-100" src="${imageUrl}" alt="Product Image" 
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/600x400?text=Image+Not+Available'">
                    </div>
                `;
                carouselInner.append(imgHtml);
                hasValidImages = true;
            }
        });
        
        if (hasValidImages) {
            // Show carousel controls if multiple images
            const itemsCount = $('.carousel-item', carouselInner).length;
            if (itemsCount > 1) {
                $('.carousel-control-prev, .carousel-control-next').show();
            } else {
                $('.carousel-control-prev, .carousel-control-next').hide();
            }
        } else {
            // Show placeholder if no valid images
            showPlaceholderImage();
        }
    } else {
        // Show placeholder if no images
        showPlaceholderImage();
    }
    
    function showPlaceholderImage() {
        carouselInner.html(`
            <div class="carousel-item active">
                <img class="d-block w-100" src="https://via.placeholder.com/600x400?text=No+Image+Available" alt="No Image Available">
            </div>
        `);
        $('.carousel-control-prev, .carousel-control-next').hide();
    }
}

function setupModalButtons(product) {
    const buttonsContainer = $('#modalProductButtons');
    buttonsContainer.empty();
    
    // Only show buttons if not inquiry only
    if (product.inquiry_only != 1) {
        let buttonHtml = '';
        const addToCartUrl = '{{ $add_to_cart }}';
        
        if (product.is_service_product_price_from_dispatch_forOnDemand == 1) {
            buttonHtml = `
                <button class="btn btn-primary btn-lg btn-block view_on_demand_price"
                        data-variant_id="${product.variant_id || ''}"
                        data-add_to_cart_url="${addToCartUrl}"
                        data-vendor_id="${product.vendor_id || ''}"
                        data-product_id="${product.id}">
                    View Price
                </button>
            `;
        } else {
            const redirectUrl = product.is_recurring_booking == 1 && product.vendor?.slug && product.url_slug
                ? '/product/' + product.vendor.slug + '/' + product.url_slug
                : 'javascript:void(0)';
                
            const buttonClass = product.is_recurring_booking == 1 
                ? 'btn-primary add_on_demand_btn' 
                : 'btn-primary add_on_demand';
            
            const onClickHandler = product.is_recurring_booking == 1 
                ? `window.location.href='${redirectUrl}'`
                : 'addToCartFromModal(this)';
            
            buttonHtml = `
                <button class="btn btn-lg btn-block ${buttonClass}"
                        data-variant_id="${product.variant_id || ''}"
                        data-add_to_cart_url="${addToCartUrl}"
                        data-vendor_id="${product.vendor_id || ''}"
                        data-product_id="${product.id}"
                        onclick="${onClickHandler}">
                    ${product.is_recurring_booking == 1 ? 'View Details' : 'Add to Cart'} <i class="fa fa-plus ml-2"></i>
                </button>
            `;
        }
        
        buttonsContainer.html(buttonHtml);
    } else {
        buttonsContainer.html(`
            <div class="alert alert-info">
                <i class="fa fa-info-circle mr-2"></i>
                This service requires an inquiry. Please contact us for more details.
            </div>
        `);
    }
}

function addToCartFromModal(button) {
    const $button = $(button);
    const productId = $button.data('product_id');
    const variantId = $button.data('variant_id');
    const vendorId = $button.data('vendor_id');
    const addToCartUrl = $button.data('add_to_cart_url');
    
    if (!productId || !vendorId) {
        showToast('Missing product information. Please try again.', 'error');
        return;
    }
    
    // Show loading state
    $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-2"></i> Adding...');
    
    // Add to cart logic here (same as your existing add to cart)
    $.ajax({
        url: addToCartUrl,
        type: 'POST',
        data: {
            product_id: productId,
            variant_id: variantId || '',
            vendor_id: vendorId,
            quantity: 1,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Add to cart response:', response);
            if (response.success) {
                showToast('Product added to cart successfully!', 'success');
                
                // Update cart UI if function exists
                if (typeof updateCartUI === 'function') {
                    updateCartUI();
                }
                
                // Close modal after delay
                setTimeout(function() {
                    $('#productDetailModal').modal('hide');
                    $button.prop('disabled', false).html('Add to Cart <i class="fa fa-plus ml-2"></i>');
                }, 1500);
            } else {
                showToast(response.message || 'Failed to add product to cart', 'error');
                $button.prop('disabled', false).html('Add to Cart <i class="fa fa-plus ml-2"></i>');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText);
            showToast('Error adding product to cart. Please try again.', 'error');
            $button.prop('disabled', false).html('Add to Cart <i class="fa fa-plus ml-2"></i>');
        }
    });
}

function showToast(message, type = 'success') {
    // Remove existing toasts
    $('.toast-alert').remove();
    
    // Create toast element
    const bgColor = type === 'success' ? '#28a745' : '#dc3545';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const toast = $(`
        <div class="toast-alert">
            <i class="fa ${icon} mr-2"></i>
            ${message}
        </div>
    `);
    
    $('body').append(toast);
    
    // Style the toast
    toast.css({
        'position': 'fixed',
        'top': '20px',
        'right': '20px',
        'background': bgColor,
        'color': 'white',
        'padding': '15px 20px',
        'border-radius': '5px',
        'z-index': '99999',
        'box-shadow': '0 4px 12px rgba(0,0,0,0.15)',
        'animation': 'slideInRight 0.3s ease',
        'font-size': '14px',
        'max-width': '300px'
    });
    
    // Auto remove after 3 seconds
    setTimeout(function() {
        toast.animate({opacity: 0, right: '-100px'}, 300, function() {
            toast.remove();
        });
    }, 3000);
}

function showError(message) {
    const errorDiv = $(`
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle mr-2"></i>
            ${message}
        </div>
    `);
    
    $('#modalProductDescription').html(errorDiv);
}

// Add CSS for animation
$(document).ready(function() {
    // Add animation styles
    if (!$('#modal-styles').length) {
        const style = document.createElement('style');
        style.id = 'modal-styles';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .toast-alert {
                animation: slideInRight 0.3s ease;
            }
            
            .product-clickable {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                cursor: pointer;
            }
            
            .product-clickable:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
        `;
        document.head.appendChild(style);
    }
    
    // Make product rows clickable
    $('.classes_wrapper').addClass('product-clickable').on('click', function(e) {
        // Don't trigger if clicking on buttons, links, or specific elements
        const $target = $(e.target);
        const isClickableElement = $target.is('a, button, .add_on_demand, .view_on_demand_price, .btn, .btn-solid, .number, .minus, .plus, .input-number, .qty-minus-ondemand, .qty-plus-ondemand, i') ||
                                  $target.closest('a, button, .add_on_demand, .view_on_demand_price, .btn, .btn-solid, .number, .minus, .plus, .input-number, .qty-minus-ondemand, .qty-plus-ondemand').length;
        
        if (!isClickableElement) {
            e.preventDefault();
            e.stopPropagation();
            extractProductDataFromRow($(this));
        }
    });
    
    // Handle modal buttons
    $(document).on('click', '#modalBookNowBtn.add_on_demand', function(e) {
        e.preventDefault();
        addToCartFromModal(this);
    });
    
    // Handle view price button in modal
    $(document).on('click', '#modalBookNowBtn.view_on_demand_price', function(e) {
        e.preventDefault();
        // Trigger your existing view price functionality
        const $button = $(this);
        const productId = $button.data('product_id');
        
        // Find and click the original view price button
        const originalButton = $(`.view_on_demand_price[data-product_id="${productId}"]`);
        if (originalButton.length) {
            originalButton.click();
        }
        
        $('#productDetailModal').modal('hide');
    });
    
    // Initialize carousel
    $('#productCarousel').carousel();
    
    // Debug: Log all product rows
    console.log('Found product rows:', $('.classes_wrapper').length);
    $('.classes_wrapper').each(function(index) {
        console.log('Row', index, ':', {
            title: $(this).find('h5 b').text(),
            price: $(this).find('.productBookingBtns h5').text(),
            button: $(this).find('.add_on_demand, .view_on_demand_price').attr('id')
        });
    });
});
</script>
@endsection



</section>
@include('frontend.ondemand.productPriceModel')
@section('custom-js')

<script src="{{ asset('js/onDemand/GetDispatcherPrice.js') }}"></script>
<script src="{{ asset('js/onDemand/AgentSlot.js') }}"></script>

<!-- <script>
    function handleServiceClick() {
    window.location.href = 'https://www.google.com';
}
</script> -->
@endsection
