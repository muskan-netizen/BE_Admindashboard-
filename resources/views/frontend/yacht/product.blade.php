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
                <div class="product_slider">
                    <div class="item">
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
                    </div>
                </div>
            </div>
            <div class="right col-md-5">
                <div class="text">
                    <h2>{{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : ''}}</h2>
                    <h6 class="sold-by mt-2">
                        <b> <img class="blur-up lazyload" data-src="{{$product->vendor->logo['image_fit']}}200/200{{$product->vendor->logo['image_path']}}" alt="{{$product->vendor->Name}}"></b> <a href="{{ route('vendorDetail', $product->vendor->slug) }}"><b> {{$product->vendor->name}} </b></a>
                    </h6>
                    <div class="product_price">
                        <p>{{Session::get('currencySymbol')}}{{decimal_format($product->variant[0]->price)}}</p>
                        {{-- <p>AED<span> 599.00/day</span></p> --}}
                    </div>
                    <div class="productList">
                        <ul>
                            <li><a href="">{{$fields['Transmission'] ?? 'Manual'}}</a></li>
                            <li><a href="">{{$fields['Fuel Type'] ?? 'Petrol'}}</a></li>
                            <li><a href="">{{$fields['Seats'] ?? ''}} Seats</a></li>
                        </ul>
                    </div>

                    <div class="product_location">
                        <p><img src=""> 84746 O'Connell Station</p>
                    </div>

                    <div class="product_iteslist">
                        <ul>
                            
                            @foreach ($product->ProductAttribute as $productAttribute)
								@php
									$attribute = $productAttribute->attribute;
									$img = $attribute->icon['proxy_url'] . '100/100' . $attribute->icon['image_path']
								@endphp

								<li><img src="/yacht-images/download-speed.png"> <span>{{$productAttribute->key_name}} <b>{{$productAttribute->key_value}}</b> </span></li>
                            @endforeach
                            
                            
                            {{-- <li><img src="/yacht-images/download-speed.png"> <span>Mileage (upto) <b>18.97 kmpl</b> </span></li>
                            <li><img src="/yacht-images/download-speed.png"> <span>Mileage (upto) <b>18.97 kmpl</b> </span></li>
                            <li><img src="/yacht-images/download-speed.png"> <span>Mileage (upto) <b>18.97 kmpl</b> </span></li>
                            <li><img src="/yacht-images/download-speed.png"> <span>Mileage (upto) <b>18.97 kmpl</b> </span></li>
                            <li><img src="/yacht-images/download-speed.png"> <span>Mileage (upto) <b>18.97 kmpl</b> </span></li> --}}

                        </ul>
                    </div>
                    <div class="product_cta">
                        <a href="">Next</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="single_product_description">
    <div class="container">
        <h3>Description</h3>
        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publis packages and web page editors now use Lorem Ipsum as their default .</p>
        <p> a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publis packages and web page editors now use Lorem Ipsum as their default .</p>
    </div>
</section>

<section class="product_dis_list">
    <div class="container">
        <h3>Car specifications</h3>
        <ul class="specifications_list">
            <li>Stunning swimming pool and Gym access</li>
            <li>Full Marina View</li>
            <li>Allocated Parking</li>
            <li>Balcony</li>
            <li>Free high-speed </li>
            <li>WiFi</li>
            <li>Kitchen with full appliances</li>
            <li>5 Star Hotel facilities</li>
        </ul>
    </div>
</section>

<section class="product_dis_list">
    <div class="container">
        <h3>Additional Features</h3>
        <ul class="specifications_list additional_list">
            <li>Body Type <span>Sedan</span></li>
            <li>No. of cylinders <span>12</span></li>
            <li>max Torque (nm@rpm) <span>900Nm@1700pm</span></li>
            <li>Fuel Tank Capacity <span>100.0</span></li>
            <li>Ground Clearance <span>164mm</span></li>
        </ul>
    </div>
</section>
@endsection
