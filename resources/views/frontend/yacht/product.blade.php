@extends('layouts.car-rental', [
'title' => (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : '',
'meta_title'=>(!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->meta_title:'',
'meta_keyword'=>(!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->meta_keyword:'',
'meta_description'=>(!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->meta_description:'',
])
@section('content')
<section class="single_product_block">
    <div class="container">
        <div class="row">
            <div class="left col-md-7">
                <div class="product_slider prodct_slider">
                @if(!empty($product->media) && count($product->media) > 0)
                    @foreach($product->media as $k => $image)
                        @php
                            if(isset($image->pimage)){
                            $img = $image->pimage->image;
                            }else{
                            $img = $image->image;
                            }
                        @endphp
                        <div class="item">
                                <div class="image">
                                    <img src="{{@$img->path['image_fit'].'1000/1000'.@$img->path['image_path']}}" alt="" data-src="{{@$img->path['image_fit'].'1000/1000'.@$img->path['image_path']}}">
                                </div>
                        </div>
                        {{-- <div class="item">
                            <div class="image">
                                <img src="images/product1.png" alt="">
                            </div>
                        </div> --}}
                    @endforeach
                    @endif
                        {{-- <div class="item">
                            <div class="">
                                @if(!empty($product->media) && count($product->media) > 0)
                                @foreach($product->media as $k => $image)
                                @php
                                if(isset($image->pimage)){
                                $img = $image->pimage->image;
                                }else{
                                $img = $image->image;
                                }
                                @endphp
                                @if(!is_null($img))
                                <img data-src="{{@$img->path['image_fit'].'1000/1000'.@$img->path['image_path']}}" src="{{@$img->path['image_fit'].'1000/1000'.@$img->path['image_path']}}">
                                @endif
                                @endforeach
                                @else
                                <img data-src="{{loadDefaultImage()}}" width="60" height="60" src="{{loadDefaultImage()}}">
                                @endif
                            </div>
                        </div> --}}
                </div>
                <ul class="slider product_list_slider">
						<li><img src="images/product.png" alt=""></li>
						<li><img src="images/product1.png" alt=""></li>
				</ul>
            </div>
            <div class="right col-md-5">
                <div class="text">
                    <h2>{{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : ''}}</h2>
                    <h6 class="sold-by mt-2">
                        <b> <img class="blur-up lazyload" data-src="{{$product->vendor->logo['image_fit']}}100/100{{$product->vendor->logo['image_path']}}" alt="{{$product->vendor->Name}}"></b> <a href="{{ route('vendorDetail', $product->vendor->slug) }}"><b> {{$product->vendor->name}} </b></a>
                    </h6>
                    <div class="product_price">
                    @if($category->slug == 'rental')
                        <p>{{Session::get('currencySymbol')}}{{decimal_format($product->variant[0]->price)}}</p>
                    @elseif($category->slug == 'yacht')
                         @foreach($product->variantSet as $key => $variant)
                                <div class="vset-box mt-5">
                                    <div class="d-flex justify-content-around">
                                        @foreach($variant->option2 as $key => $option)
                                            <input type="radio" name="booking_duration" value="{{$option->product_variant_id}}"/>
                                            <div class="border border">
                                                <h6>{{$option->title}}</h6>
                                                <h6>{{Session::get('currencySymbol')}}{{decimal_format($option->price)}}</h6>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        {{-- <p>AED<span> 599.00/day</span></p> --}}
                    </div>
                    <div class="productList d-flex justify-content-between">
                        @php

                        $fields = [];
                        $desc = [];
                        $detail = [
                        'Mileage',
                        'Engine',
                        'Transmission',
                        'BHP',
                        'Seats',
                        'Boot Space',
                        'Fuel Type',
                        'Cabins',
                        'Baths',
                        'Berths'
                        ];
                        foreach ($product->ProductAttribute as $productAttribute) {
                            $attribute = $productAttribute->attribute;
                            $img = $attribute->icon['proxy_url'] . '100/100' . $attribute->icon['image_path'];
                            if ($productAttribute->attributeOption()->exists()) {
                                $title = $productAttribute->attributeOption->title ?? $productAttribute->key_value;
                                if(in_array($productAttribute->key_name, $detail)){
                                    $fields[$productAttribute->key_name]['title'] = $title;
                                    $fields[$productAttribute->key_name]['img'] = $img;
                                }else{
                                    $desc[$productAttribute->key_name]['title'] = $title;
                                    $desc[$productAttribute->key_name]['img'] = $img;
                                }
                            }
                        }
                        @endphp
                        <ul class="product-features">
                           @if($category->slug == 'rental')
                                <li><a href="javascript:void(0);">{{$fields['Transmission']['title'] ?? ''}}</a></li>
                                <li><a href="javascript:void(0);">{{$fields['Fuel Type']['title'] ?? ''}}</a></li>
                                <li><a href="javascript:void(0);">{{$fields['Seats']['title'] ?? '0'}} Seats</a></li>
                            @elseif($category->slug == 'yacht')
                                <li><a href="javascript:void(0);">{{$fields['Cabins']['title'] ?? '0'}} Cabins</a></li>
                                <li><a href="javascript:void(0);">{{$fields['Baths']['title'] ?? '0'}} Baths</a></li>
                                <li><a href="javascript:void(0);">{{$fields['Berths']['title'] ?? '0'}} Berths</a></li>
                            @endif
                        </ul>
                    </div>

                    <div class="product_iteslist">
                        <ul>
                            @foreach ($fields as $key => $productAttribute)
                            <li><img src="{{$productAttribute['img']}}"> <span>{{$key}} <b>{{$productAttribute['title']}}</b> </span></li>
                            @endforeach
                        </ul>
                        @if($category->slug == 'rental')
                            <div>
                                <p><b>Rental Start Time:-</b> <span>{{$pickup_time}}</span></p>
                                <p><b>Rental End Time:- </b>{{$drop_time}}</p>
                            </div>
                        @endif
                    </div>
                    <div class="product_cta">
                    @php
                        /*$newRequest->merge(['product_id'=> $product->product_id, 'quantity'=>$product->quantity, 'variant_id'=>$product->product->variant[0]->id, 'vendor_id'=>$product->product->vendor_id,'bid_number'=>(($is_bid_enable)?$id:null),'bid_discount'=>(($is_bid_enable)?$product->bids->discount:null)]);
                        $data = $CartController->postAddToCart($newRequest);*/
                    @endphp
                        <a href="javascript:void(0);" id="add_to_cart_btn" class="addToCart">Next</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="single_product_description">
    <div class="container">
        <h3>Description</h3>
        {{strip_tags($product->translation_one->body_html)}}
    </div>
</section>

@if($category->slug == 'yacht')
    <section class="container map-container mt-5">
        <div class="row">
            <div class="col-md-4 border d-none">
                <div id="vendor-detail" class="d-flex justify-content-between">
                    <div class="image-box">
                        <img src="" alt="vendor image"/>
                    </div>
                    <div class="vendor-info">
                        <h4>VEndor name</h4>
                        <p>contant details</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div id="map"></div>
            </div>
        </div>
    </section>
@endif

<section class="product_dis_list d-none">
    <div class="container">
    <input type="hidden" name="available_product_variant" id="available_product_variant" value="{{$product->variant[0]->id}}">
    <input type="hidden" name="start_time" id="start_time" value="">
    <input type="hidden" name="end_time" id="end_time" value="">
    <input type="hidden" name="variant_id" id="prod_variant_id" value="{{$product->variant[0]->id}}">
    <input type="hidden" name="sele_slot_id" id="sele_slot_id" value="" />
    <input type="hidden" name="sele_slot_price" id="sele_slot_price" value="" />
    <input type="hidden" name="product_id" id="product_id" value="{{$product->id}}" />
    <input type="hidden" name="prod_variant_id" id="prod_variant_id" value="" />
    <div id="selected_slot"></div>
    @include('frontend.product-part.booking-slot')
        {{-- <h3>Car specifications</h3>
        <ul class="specifications_list">
            <li>Stunning swimming pool and Gym access</li>
            <li>Full Marina View</li>
            <li>Allocated Parking</li>
            <li>Balcony</li>
            <li>Free high-speed </li>
            <li>WiFi</li>
            <li>Kitchen with full appliances</li>
            <li>5 Star Hotel facilities</li>
        </ul> --}}
    </div>
</section>
@if(!empty($desc))
<section class="product_dis_list">
    <div class="container">
        <h3>Additional Features</h3>
        <ul class="specifications_list additional_list">
            @foreach($desc as $key => $value)
            <li>{{$key}} <span>{{$value['title']}}</span></li>
            @endforeach
        </ul>
    </div>
</section>
@endif
@endsection
@section('script')
<script>
    var addonids = [];
    var addonoptids = [];
    var ajaxCall = 'ToCancelPrevReq';
    let vendor_id = "{{ $product->vendor_id }}";
    let product_id = "{{ $product->id }}";
    var add_to_cart_url = "{{ route('addToCart') }}";
    var serviceType ="{{$category->slug}}";

    var companyLocation = { lat: 37.7749, lng: -122.4194 };
        
    document.addEventListener("DOMContentLoaded", () => {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: companyLocation,
            zoom: 14
        });

        console.log(map);

        var marker = new google.maps.Marker({
            position: companyLocation,
            map: map,
            title: 'Company Location'
        });

            $('.prodct_slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.product_list_slider'
    });

    $('.product_list_slider').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        asNavFor: '.prodct_slider',
        dots: false,
        focusOnSelect: true
    });
    $('a[data-slide]').click(function(e) {
        e.preventDefault();
        var slideno = $(this).data('slide');
        $('.product_list_slider').slick('slickGoTo', slideno - 1);
    });  
    });
</script>

