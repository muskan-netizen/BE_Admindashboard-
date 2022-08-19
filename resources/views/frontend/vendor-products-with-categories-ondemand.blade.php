@php
$checkSlot = findSlot('', $vendor->id, '');
@endphp
@extends('layouts.store', ['title' => $vendor->name])
@section('css')
<style type="text/css">
    .main-menu .brand-logo{display:inline-block;padding-top:20px;padding-bottom:20px}.productVariants .firstChild{min-width:150px;text-align:left!important;border-radius:0!important;margin-right:10px;cursor:default;border:none!important}.product-right .color-variant li,.productVariants .otherChild{height:35px;width:35px;border-radius:50%;margin-right:10px;cursor:pointer;border:1px solid #f7f7f7;text-align:center}.productVariants .otherSize{height:auto!important;width:auto!important;border:none!important;border-radius:0}.product-right .size-box ul li.active{background-color:inherit}.product-box .product-detail h4,.product-box .product-info h4{font-size:16px}select.changeVariant{color:#343a40;border:1px solid #bbb;border-radius:5px;font-size:14px}.counter-container{border:1px solid var(--theme-deafult);border-radius:5px;padding:2px}.switch{opacity:0;position:absolute;z-index:1;width:18px;height:18px;cursor:pointer}.switch+.lable{position:relative;display:inline-block;margin:0;line-height:20px;min-height:18px;min-width:18px;font-weight:400;cursor:pointer}.switch+.lable::before{cursor:pointer;font-family:fontAwesome;font-weight:400;font-size:12px;color:#32a3ce;content:"\a0";background-color:#fafafa;border:1px solid #c8c8c8;box-shadow:0 1px 2px rgba(0,0,0,.05);border-radius:0;display:inline-block;text-align:center;height:16px;line-height:14px;min-width:16px;margin-right:1px;position:relative;top:-1px}.switch:checked+.lable::before{display:inline-block;content:'\f00c';background-color:#f5f8fc;border-color:#adb8c0;box-shadow:0 1px 2px rgba(0,0,0,.05),inset 0 -15px 10px -12px rgba(0,0,0,.05),inset 15px 10px -12px rgba(255,255,255,.1)}.switch+.lable{margin:0 4px;min-height:24px}.switch+.lable::before{font-weight:400;font-size:11px;line-height:17px;height:20px;overflow:hidden;border-radius:12px;background-color:#f5f5f5;-webkit-box-shadow:inset 0 1px 1px 0 rgba(0,0,0,.15);box-shadow:inset 0 1px 1px 0 rgba(0,0,0,.15);border:1px solid #ccc;text-align:left;float:left;padding:0;width:52px;text-indent:-21px;margin-right:0;-webkit-transition:text-indent .3s ease;-o-transition:text-indent .3s ease;transition:text-indent .3s ease;top:auto}.switch.switch-bootstrap+.lable::before{font-family:FontAwesome;content:"\f00d";box-shadow:none;border-width:0;font-size:16px;background-color:#a9a9a9;color:#f2f2f2;width:52px;height:22px;line-height:21px;text-indent:32px;-webkit-transition:background .1s ease;-o-transition:background .1s ease;transition:background .1s ease}.switch.switch-bootstrap+.lable::after{content:'';position:absolute;top:2px;left:3px;border-radius:12px;box-shadow:0 -1px 0 rgba(0,0,0,.25);width:18px;height:18px;text-align:center;background-color:#f2f2f2;border:4px solid #f2f2f2;-webkit-transition:left .2s ease;-o-transition:left .2s ease;transition:left .2s ease}.switch.switch-bootstrap:checked+.lable::before{content:"\f00c";text-indent:6px;color:#fff;border-color:#b7d3e5}.switch-primary>.switch.switch-bootstrap:checked+.lable::before{background-color:#337ab7}.switch-success>.switch.switch-bootstrap:checked+.lable::before{background-color:#5cb85c}.switch-danger>.switch.switch-bootstrap:checked+.lable::before{background-color:#d9534f}.switch-info>.switch.switch-bootstrap:checked+.lable::before{background-color:#5bc0de}.switch-warning>.switch.switch-bootstrap:checked+.lable::before{background-color:#f0ad4e}.switch.switch-bootstrap:checked+.lable::after{left:32px;background-color:#fff;border:4px solid #fff;text-shadow:0 -1px 0 rgba(0,200,0,.25)}.switch-square{opacity:0;position:absolute;z-index:1;width:18px;height:18px;cursor:pointer}.switch-square+.lable{position:relative;display:inline-block;margin:0;line-height:20px;min-height:18px;min-width:18px;font-weight:400;cursor:pointer}.switch-square+.lable::before{cursor:pointer;font-family:fontAwesome;font-weight:400;font-size:12px;color:#32a3ce;content:"\a0";background-color:#fafafa;border:1px solid #c8c8c8;box-shadow:0 1px 2px rgba(0,0,0,.05);border-radius:0;display:inline-block;text-align:center;height:16px;line-height:14px;min-width:16px;margin-right:1px;position:relative;top:-1px}.switch-square:checked+.lable::before{display:inline-block;background-color:#f5f8fc;border-color:#adb8c0;box-shadow:0 1px 2px rgba(0,0,0,.05),inset 0 -15px 10px -12px rgba(0,0,0,.05),inset 15px 10px -12px rgba(255,255,255,.1)}.switch-square+.lable{margin:0 4px;min-height:24px}.switch.switch-bootstrap+.lable::before,.switch.switch-bootstrap:checked+.lable::before{content:"";width:40px;height:18px;line-height:21px}.switch.switch-bootstrap+.lable::after{width:14px;height:14px}.switch+.lable{line-height:14px}.switch.switch-bootstrap:checked+.lable::after{left:23px}.switch-square+.lable::before{font-weight:400;font-size:11px;line-height:17px;height:20px;overflow:hidden;border-radius:2px;background-color:#f5f5f5;-webkit-box-shadow:inset 0 1px 1px 0 rgba(0,0,0,.15);box-shadow:inset 0 1px 1px 0 rgba(0,0,0,.15);border:1px solid #ccc;text-align:left;float:left;padding:0;width:52px;text-indent:-21px;margin-right:0;-webkit-transition:text-indent .3s ease;-o-transition:text-indent .3s ease;transition:text-indent .3s ease;top:auto}.switch-square.switch-bootstrap+.lable::before{font-family:FontAwesome;box-shadow:none;border-width:0;font-size:16px;background-color:#a9a9a9;color:#f2f2f2;width:52px;height:22px;line-height:21px;text-indent:32px;-webkit-transition:background .1s ease;-o-transition:background .1s ease;transition:background .1s ease}.switch-square.switch-bootstrap+.lable::after{content:'';position:absolute;top:2px;left:3px;border-radius:12px;box-shadow:0 -1px 0 rgba(0,0,0,.25);width:18px;height:18px;text-align:center;background-color:#f2f2f2;border:4px solid #f2f2f2;-webkit-transition:left .2s ease;-o-transition:left .2s ease;transition:left .2s ease}.switch-square.switch-bootstrap:checked+.lable::before{text-indent:6px;color:#fff;border-color:#b7d3e5}.switch-primary>.switch-square.switch-bootstrap:checked+.lable::before{background-color:#337ab7}.switch-success>.switch-square.switch-bootstrap:checked+.lable::before{background-color:#5cb85c}.switch-danger>.switch-square.switch-bootstrap:checked+.lable::before{background-color:#d9534f}.switch-info>.switch-square.switch-bootstrap:checked+.lable::before{background-color:#5bc0de}.switch-warning>.switch-square.switch-bootstrap:checked+.lable::before{background-color:#f0ad4e}.switch-square.switch-bootstrap:checked+.lable::after{left:32px;background-color:#fff;border:4px solid #fff;text-shadow:0 -1px 0 rgba(0,200,0,.25)}.switch-square.switch-bootstrap+.lable::after{border-radius:2px}
</style>
@endsection
@section('css-links')
<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/price-range.css') }}">
@endsection
@section('content')
    <!-- section start -->
    <section class="section-b-space ratio_asos">
        <div class="collection-wrapper">

        <!-- section hero start -->
        <section class="heroArea" id="heroMembershipPage">
            <div class="container-fluid">
                <div class="row">
                    <div class="slider regular">
                        @if (!empty($vendor->banner))
                            <div><img src="{{ $vendor->banner['image_fit'] . '1920/1080' . $vendor->banner['image_path'] }}" alt=""></div>
                        @endif
                        <div><img src="{{asset('frontend/template_six/spaimages/membershipBanner.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/singleP.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/membershipBanner.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/singleP.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/membershipBanner.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/singleP.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/membershipBanner.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/singleP.png')  }}" alt=""></div>
                    </div>


                    <div class="slider regular-nav">
                        @if (!empty($vendor->banner))
                        <div><img src="{{ $vendor->banner['image_fit'] . '1920/1080' . $vendor->banner['image_path'] }}" alt=""></div>
                    @endif
                        <div><img src="{{asset('frontend/template_six/spaimages/membershipBanner.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/singleP.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/membershipBanner.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/singleP.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/membershipBanner.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/singleP.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/membershipBanner.png')  }}" alt=""></div>
                        <div><img src="{{asset('frontend/template_six/spaimages/singleP.png')  }}" alt=""></div>
                    </div>
                 </div>
            </div>

        </section>
        <!-- section hero end -->

        <!-- single products content start -->
        <section class="singleProductContent pb-5">
        	<div class="container">
        		<div class="row">
	        		<!-- breadcrumb start -->
	        		<nav class="col-12" aria-label="breadcrumb">
					  <ol class="breadcrumb p-0">
                        @if( $vendor->country)
					        <li class="breadcrumb-item pr-3"><a href="javascript:void(0)">{{  $vendor->country ?? '' }}</a></li>
                        @endif
                        @if( $vendor->state)
					        <li class="breadcrumb-item active pl-4" aria-current="page">{{ $vendor->state ?? '' }}</li>
                        @endif
					  </ol>
					</nav><!-- breadcrumb end -->
					<div class="col-12">
						<div class="row">
							<div class="col-md-7">
								<div class="alSpaListHead text-center text-lg-left mb-4">
			                        <p class="alLgFontSize pr-5">{{ $vendor->name }}</p>
			                        <p class="alShareLink alBodyText d-flex align-items-center"><a href="javascript:void(0)">{{ $vendor->address }}
                                        {{-- <span class="ml-3"><img src="{{asset('frontend/template_six/spaimages/share.svg')  }}"></span> --}}
                                    </a></p>
			                    </div>
							</div>
							<div class="col-md-5">
								<div class="alSpaListHead text-center text-lg-left mt-3">
			                        <ul class="d-flex align-items-center">
                                        @if ($vendor->vendorRating > 0)
		                        		    <li class="d-flex align-items-center"><img class="mr-2" src="{{asset('frontend/template_six/spaimages/Star.svg')  }}">  {{ $vendor->vendorRating }} {{ __('Very Good') }}</li>

		                        		<li class="border-left ml-3 pl-3">
                                            <a href="javascript:void(0)">{{ $vendor->review_count . ' customer reviews' }}</a>
                                        </li>
                                        @endif
		                        	</ul>
			                    </div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
                    <div class="col-md-12">
                        {{ $vendor->desc }}
                    </div>
					{{-- <div class="col-md-6">
						<p class="alBodyText">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Cursus sit amet dictum sit amet justo. Quis hendrerit dolor magna eget est lorem. Sed faucibus turpis in eu. Mattis vulputate enim nulla aliquet porttitor lacus luctus accumsan tortor. Elementum facilisis leo vel fringilla est. Nunc non blandit massa enim nec dui nunc mattis. Ac placerat vestibulum lectus mauris ultrices. Sagittis purus sit amet volutpat consequat.
						</p>
					</div>
					<div class="col-md-6">
						<p class="alBodyText">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Cursus sit amet dictum sit amet justo. Quis hendrerit dolor magna eget est lorem. Sed faucibus turpis in eu. Mattis vulputate enim nulla aliquet porttitor lacus luctus accumsan tortor. Elementum facilisis leo vel fringilla est. Nunc non blandit massa enim nec dui nunc mattis. Ac placerat vestibulum lectus mauris ultrices. Sagittis purus sit amet volutpat consequat. <a class="readMore" href="#"><u> Read more </u></a></p>
					</div> --}}
				</div>
                @if($client_preference_detail->is_vendor_tags == '1')
				<!-- Spa and hotel facilities start -->
				<div class="row py-5 border-bottom">
					<div class="col-lg-12 hotelFacilities">
						<h3 class="mb-4">{{ getNomenclatureName('vendors', false).' ' . __('facilities') }} </h3>
						<ul class="p-0 m-0 d-flex align-items-center">
                            @php
                             $total_facilty =count($vendor->facilty);
                            @endphp
                            @foreach($vendor->facilty  as $key => $facilty)
                            @if($key ==6)
                                <li class="mr-0" id="show_facilty">
                                    <a href="javascript:void(0)" ><u>{{ __('View all facilities') }}</u></a>
                                </li>
                                <div class="more-show_facilty d-none" id="show_facilty_more" >
                             @endif
                                <li class="mr-4">
                                    <img class="mr-2" src="{{ $facilty->image['proxy_url'].'30/30'.$facilty->image['image_path']}}">
                                    <span>{{ $facilty->translations->first() ? $facilty->translations->first()->name : 'NA' }}</span>



                                </li>
                                @if($key ==6 && $key == ($total_facilty-1) )
                                </div>
                                @endif
                            @endforeach

						</ul>
					</div>
				</div>
				<!-- Spa and hotel facilities end -->
                @endif
        	</div>
        </section><!-- single products content end -->


        <section class="home-serivces" id="alSixHomeServices">
            <div class="container">
                <div class="row mb-lg-5 mb-md-4 mb-3">
                    <div class="col-xl-8 offset-xl-2">
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
                                <nav id='main-nav'>
                                    <ul id='main-nav-list'>
                                       @if(!empty($category->childs) && count($category->childs) > 0)
                                            @foreach ($category->childs as $key => $childs)
                                                @if($childs->type_id == 8 || $childs->type_id == 12 )
                                                <li><a href="#section_set{{$key}}">{{ $childs['translation_name'] ?? ''}}</a></li>
                                                @endif
                                            @endforeach
                                        @endif
                                    </ul>
                                </nav>
                                <!-- End Main Nav -->

                                @endif

                                <div class="card-box">


                                    @if(app('request')->input('step') == '1' || empty(app('request')->input('step')))
                                            @if(!empty($category->childs) && count($category->childs) > 0)
                                            <!-- static html -->

                                        <!-- Start Conent Wrapper -->
                                        <div id='main-wrapper'  class="@if(app('request')->input('addons') == 1) d-none @endif">
                                                    @foreach ($category->childs as $key => $childs)
                                                        @if($childs->type_id == 8 || $childs->type_id == 12 )

                                                            <h4><b>{{ $childs->translation_name }}</b></h4>
                                                            <div class='' id='section_set{{$key}}'>
                                                                @if(!empty($childs))
                                                                <div class="service-img mb-3">
                                                                    <img class="img-fluid" src="{{$childs->image['proxy_url'] . '1000/200' . $childs->image['image_path']}}" alt="">
                                                                </div>
                                                                @endif


                                                                    @foreach ($childs->products as $data)

                                                                    @php
                                                                        $data->translation_title = (!empty($data->translation->first())) ? $data->translation->first()->title : $data->sku;
                                                                        $data->translation_description = (!empty($data->translation->first())) ? $data->translation->first()->body_html : $data->sku;
                                                                        $data->variant_multiplier = (!empty($clientCurrency)) ? $clientCurrency->doller_compare : 1;
                                                                        $data->variant_price = (!empty($data->variant->first())) ? $data->variant->first()->price : 0;
                                                                    @endphp

                                                                    <div class="row classes_wrapper no-gutters align-items-center" href="#">
                                                                        <div class="col-md-9 col-sm-8 pr-md-2 ">
                                                                            <div class="alSixHomeServiceSteps">
                                                                                <h5 class="mb-1"><b>{!! (!empty($data->translation->first())) ? $data->translation->first()->title : $data->sku !!}</b></h5>
                                                                                <p class="mb-1">{!! (!empty($data->translation->first())) ? $data->translation->first()->body_html : $data->sku !!}</p>
                                                                            </div>
                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                <h5 class="my-sm-0 my-3">@if($data->inquiry_only == 0)
                                                                                    {{Session::get('currencySymbol').(decimal_format($data->variant_price * $data->variant_multiplier))}}
                                                                                @endif</h5>


                                                                                @if(isset($data->variant[0]->checkIfInCart) && count($data->variant[0]->checkIfInCart) > 0)
                                                                                @php
                                                                                    $cartcount = 1;
                                                                                @endphp
                                                                                <a class="btn btn-solid add_on_demand" style="display:none;" id="add_button_href{{$data->variant[0]->checkIfInCart['0']['id']}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ route('addToCart') }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">Add <i class="fa fa-plus"></i></a>
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
                                                                                <a class="btn btn-solid add_on_demand" id="aadd_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ route('addToCart') }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">Add <i class="fa fa-plus"></i></a>
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
                                                                                <img src="{{$imagePath}}" alt="">
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
                                            <!-- end statis html -->
                                        @endif
                                    @endif


                                    @if(app('request')->input('step') == '1' || empty(app('request')->input('step')))
                                        <!-- for single level category -->
                                        <div class="service-data-wrapper al @if(app('request')->input('addons') == 1) d-none @endif"  id="step-1-ondemand" >
                                                <!--  service data -->
                                            <div class="service-data">
                                                <h4><b>{{ $vendor->name }}</b></h4>

                                                @if (!empty($vendor->banner))
                                                    <div class="service-img mb-3">
                                                        <img class="img-fluid" src="{{$vendor->banner['proxy_url'] . '1000/200' . $vendor->banner['image_path']}}" alt="">
                                                    </div>
                                                @endif


                                                @forelse($listData as $key => $data)
                                                    <!-- scrolling_section -->
                                                    <section class="scrolling_section " id="{{ $data->category->slug }}">
                                                            <!-- viewAllProductSec -->
                                                            <div class="viewAllProductSec" >

                                                                @forelse($data->products as $prod)

                                                                    <div class="card mb-3 product_row"  data-p_sku="{{ $prod->sku }}"
                                                                        data-slug="{{ $prod->url_slug }}">
                                                                        <div class="card-body">
                                                                            <div class="d-flex align-items-center justify-content-between border-bottom">
                                                                                <p class="m-0 productTitle"> {{ $prod->translation_title }}</p>
                                                                                <ul class="m-0 p-0 d-flex align-items-center">
                                                                                    <li>From</li>

                                                                                    <li class="ml-2"><span class="productsPrice">
                                                                                        {{ Session::get('currencySymbol') . decimal_format($prod->variant_price * $prod->variant_multiplier,',') }}
                                                                                        @if ($prod->variant[0]->compare_at_price > 0)
                                                                                            <span
                                                                                                class="org_price ml-1 font-14">{{ Session::get('currencySymbol') .decimal_format($prod->variant[0]->compare_at_price * $prod->variant_multiplier) }}</span>
                                                                                        @endif</span><br> <sup>per person (min. 1)</sup></li>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="productDetails pl-0 pr-lg-5 m-0 position-relative">
                                                                                <p class="position-relative px-3 py-2">{!! $prod->translation_description !!} </p>
                                                                            </div>
                                                                            {{-- <ul class="productDetails pl-0 pr-lg-5 m-0 position-relative">
                                                                                <li class="position-relative px-3 py-2">
                                                                                    <p class="m-0">One night bed and breakfast</p>
                                                                                </li>
                                                                                <li class="position-relative px-3 py-2">
                                                                                    <p class="m-0">Use of the leisure facilities</p>
                                                                                </li>
                                                                                <li class="position-relative px-3 py-2">
                                                                                    <p class="m-0">Bottle of sparkling wine</p>
                                                                                </li>
                                                                                <li class="position-relative px-3 py-2">
                                                                                    <p class="m-0">Robe, towel and slippers provided</p>
                                                                                </li>
                                                                            </ul> --}}
                                                                            {{-- <ul class="p-0 m-0 productBookingBtns position-relative d-flex align-items-center justify-content-between">
                                                                                <li><a href="#"><img src="{{asset('frontend/template_six/spaimages/flash_on.svg') }}"> Instant book</a></li>
                                                                                <li class="d-flex align-items-center"><button class="alProductBtns mr-2">More details and book</button> <button class="alProductBtns outlineBtn">Buy as a gift</button></li>
                                                                            </ul> --}}
                                                                            <div class="productBookingBtns product_variant_quantity_wrapper">
                                                                                @php
                                                                                    $data = $prod;
                                                                                    $productVariantInCart = 0;
                                                                                    $productVariantIdInCart = 0;
                                                                                    $productVariantInCartWithDifferentAddons = 0;
                                                                                    $cartProductId = 0;
                                                                                    $cart_id = 0;
                                                                                    $vendor_id = 0;
                                                                                    $product_id = $data->id;
                                                                                    $variant_id = $data->variant[0] ? $data->variant[0]->id : 0;
                                                                                    $variant_price = 0;
                                                                                    $variant_quantity = $prod->variant_quantity;
                                                                                    $isAddonExist = 0;
                                                                                    $minimum_order_count = $data->minimum_order_count == 0 ? 1 : $data->minimum_order_count;
                                                                                    $batch_count = $data->batch_count;
                                                                                    if (count($data->addOn) > 0) {
                                                                                        $isAddonExist = 1;
                                                                                    }
                                                                                @endphp

                                                                                @foreach ($data->variant as $var)
                                                                                    @if (isset($var->checkIfInCart) && count($var->checkIfInCart) > 0)
                                                                                        @php
                                                                                            $productVariantInCart = 1;
                                                                                            $productVariantIdInCart = $var->checkIfInCart['0']['variant_id'];
                                                                                            $cartProductId = $var->checkIfInCart['0']['id'];
                                                                                            $cart_id = $var->checkIfInCart['0']['cart_id'];
                                                                                            // $variant_quantity = $var->checkIfInCart['0']['quantity'];
                                                                                            $variant_quantity = 0;
                                                                                            $vendor_id = $data->vendor_id;
                                                                                            $product_id = $data->id;
                                                                                            $batch_count = $data->batch_count;
                                                                                            $variant_price = decimal_format($var->price * $data->variant_multiplier);
                                                                                            if (count($var->checkIfInCart) > 1) {
                                                                                                $productVariantInCartWithDifferentAddons = 1;
                                                                                            }
                                                                                            foreach ($var->checkIfInCart as $cartVar) {
                                                                                                $variant_quantity = $variant_quantity + $cartVar['quantity'];
                                                                                            }
                                                                                        @endphp
                                                                                    @break

                                                                                    ;
                                                                                @endif
                                                                            @endforeach

                                                                            @if ($vendor->is_vendor_closed == 0 || ($vendor->closed_store_order_scheduled != 0 && $checkSlot != 0))
                                                                                @php
                                                                                    $is_customizable = false;
                                                                                    if ($isAddonExist > 0 && ($variant_quantity > 0 || $prod->sell_when_out_of_stock == 1)) {
                                                                                        $is_customizable = true;
                                                                                    }
                                                                                @endphp

                                                                                @if ($productVariantInCart > 0)
                                                                                    {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a>  alProductBtns--}}
                                                                                    <a class="add-cart-btn add_vendor_product "
                                                                                        style="display:none;"
                                                                                        id="add_button_href{{ $cartProductId }}"
                                                                                        data-variant_id="{{ $productVariantIdInCart }}"
                                                                                        data-add_to_cart_url="{{ route('addToCart') }}"
                                                                                        data-vendor_id="{{ $vendor_id }}"
                                                                                        data-product_id="{{ $product_id }}"
                                                                                        data-aaddon="{{ $isAddonExist }}"
                                                                                        data-minimum_order_count="{{ $minimum_order_count }}"
                                                                                        data-batch_count="{{ $batch_count }}"
                                                                                        href="javascript:void(0)">{{ __('Add') }}
                                                                                        @if ($minimum_order_count > 0)
                                                                                            ({{ $minimum_order_count }})
                                                                                        @endif
                                                                                    </a>
                                                                                    <div class="number"
                                                                                        id="show_plus_minus{{ $cartProductId }}">
                                                                                        <span
                                                                                            class="minus qty-minus-product {{ $productVariantInCartWithDifferentAddons ? 'remove-customize' : '' }}"
                                                                                            data-variant_id="{{ $productVariantIdInCart }}"
                                                                                            data-parent_div_id="show_plus_minus{{ $cartProductId }}"
                                                                                            data-id="{{ $cartProductId }}"
                                                                                            data-base_price="{{ $variant_price }}"
                                                                                            data-vendor_id="{{ $vendor_id }}"
                                                                                            data-product_id="{{ $product_id }}"
                                                                                            data-cart="{{ $cart_id }}"
                                                                                            data-aaddon="{{ $isAddonExist }}"
                                                                                            data-minimum_order_count="{{ $minimum_order_count }}"
                                                                                            data-batch_count="{{ $batch_count }}">
                                                                                            <i class="fa fa-minus"
                                                                                                aria-hidden="true"></i>
                                                                                        </span>
                                                                                        <input
                                                                                            style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;"
                                                                                            placeholder="1" type="text"
                                                                                            value="{{ $variant_quantity }}"
                                                                                            class="input-number"
                                                                                            id="quantity_ondemand_{{ $cartProductId }}"
                                                                                            readonly>
                                                                                        <span
                                                                                            class="plus qty-plus-product {{ $is_customizable ? 'repeat-customize' : '' }}"
                                                                                            data-variant_id="{{ $productVariantIdInCart }}"
                                                                                            data-id="{{ $cartProductId }}"
                                                                                            data-base_price="{{ $variant_price }}"
                                                                                            data-vendor_id="{{ $vendor_id }}"
                                                                                            data-product_id="{{ $product_id }}"
                                                                                            data-cart="{{ $cart_id }}"
                                                                                            data-aaddon="{{ $isAddonExist }}"
                                                                                            data-batch_count="{{ $batch_count }}">
                                                                                            <i class="fa fa-plus"
                                                                                                aria-hidden="true"></i>
                                                                                        </span>
                                                                                    </div>
                                                                                @else
                                                                                    @if ($prod->has_inventory == 0 || ($variant_quantity > 0 || $prod->sell_when_out_of_stock == 1))
                                                                                        {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                                                                                        <a class="add-cart-btn add_vendor_product"
                                                                                            id="aadd_button_href{{ $data->id }}"
                                                                                            data-variant_id="{{ $data->variant[0]->id }}"
                                                                                            data-add_to_cart_url="{{ route('addToCart') }}"
                                                                                            data-vendor_id="{{ $data->vendor_id }}"
                                                                                            data-product_id="{{ $data->id }}"
                                                                                            data-aaddon="{{ $isAddonExist }}"
                                                                                            data-batch_count="{{ $batch_count }}"
                                                                                            data-minimum_order_count="{{ $minimum_order_count }}"
                                                                                            href="javascript:void(0)">{{ __('Add') }}
                                                                                            @if ($minimum_order_count > 1)
                                                                                                ({{ $minimum_order_count }})
                                                                                            @endif
                                                                                        </a>
                                                                                        <div class="number"
                                                                                            style="display:none;"
                                                                                            id="ashow_plus_minus{{ $data->id }}">
                                                                                            <span
                                                                                                class="minus qty-minus-product"
                                                                                                data-parent_div_id="show_plus_minus{{ $data->id }}"
                                                                                                data-id="{{ $data->id }}"
                                                                                                data-base_price="{{ decimal_format($data->variant_price * $data->variant_multiplier) }}"
                                                                                                data-vendor_id="{{ $data->vendor_id }}"
                                                                                                data-batch_count="{{ $batch_count }}"
                                                                                                data-minimum_order_count="{{ $minimum_order_count }}">
                                                                                                <i class="fa fa-minus"
                                                                                                    aria-hidden="true"></i>
                                                                                            </span>
                                                                                            <input
                                                                                                style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;"
                                                                                                id="quantity_ondemand_d{{ $data->id }}"
                                                                                                readonly
                                                                                                placeholder="{{ $minimum_order_count }}"
                                                                                                type="text"
                                                                                                value="{{ $minimum_order_count }}"
                                                                                                class="input-number input_qty"
                                                                                                step="0.01">
                                                                                            <span
                                                                                                class="plus qty-plus-product"
                                                                                                data-id=""
                                                                                                data-base_price="{{ decimal_format($data->variant_price * $data->variant_multiplier) }}"
                                                                                                data-vendor_id="{{ $data->vendor_id }}"
                                                                                                data-batch_count="{{ $batch_count }}"
                                                                                                data-minimum_order_count="{{ $minimum_order_count }}">
                                                                                                <i class="fa fa-plus"
                                                                                                    aria-hidden="true"></i>
                                                                                            </span>
                                                                                        </div>
                                                                                    @else
                                                                                        <span class="text-danger">{{ __('Out of stock') }}</span>
                                                                                    @endif
                                                                                @endif
                                                                                {{-- @if ($is_customizable)
                                                                                    <div class="customizable-text">
                                                                                        {{ __('customizable') }}
                                                                                    </div>
                                                                                @endif --}}
                                                                            @endif
                                                                        </div>

                                                                        </div>
                                                                    </div>
                                                                @empty
                                                                    <h4 class="mt-3 mb-3 text-center">No product found</h4>
                                                                    @endforelse
                                                            </div>
                                                                <!-- end viewAllProductSec -->
                                                    </section>
                                                        <!-- end scrolling_section -->
                                                @empty
                                                    <h4 class="mt-3 mb-3 text-center">No product found</h4>
                                                @endforelse



                                            </div>
                                            <!-- end service data -->
                                            <!-- end single level category -->
                                        </div>
                                    @endif



                                    @if(app('request')->input('addons') == '1' && app('request')->input('dateset') != '1')
                                         <!-- Step if addons avilable  Html -->

                                     <div id="step-4-ondemand">
                                        @foreach ($cartData as $key => $cart_data)
                                            <!-- show add ons -->
                                             @if(!empty($cart_data->product->addOn) && $cart_data->product->addOn->count() > 0)
                                                <div class="border-product" id="addon_div{{$cart_data->id}}">
                                                    <h4 class="mb-2"><b>{!! (!empty($cart_data->product->translation->first())) ? $cart_data->product->translation->first()->title : $cart_data->product->sku !!}</b></h4>
                                                    @foreach($cart_data->product->addOn as $row => $addon)
                                                    <div class="add-on-main-div">
                                                        <h6 class="product-title">{{ $addon->addOnName->title }}
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
                                                                                <span class="customCheckbox productAddonOptionspan_{{ $checked }}" aria-hidden="true">{{$option->title .' ('.Session::get('currencySymbol').decimal_format($option->price,',').')' }} </span>
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
                                      <!--Step if addons avilable  Html -->
                                    @endif



                                    @if(app('request')->input('step') == '2')
                                      <!-- Step Two Html -->
                                        <div id="step-2-ondemand">
                                           @php
                                           $lastKey = count($cartData) - 1;
                                           @endphp
                                           {{ Arr::last($cartData)}}
                                            @foreach ($cartData as $cd => $cart_data)
                                              @if(!empty($cart_data->product->mode_of_service) && $cart_data->product->mode_of_service == 'schedule')

                                              @php
                                                $productDate = trim(date('Y-m-d', strtotime($cart_data->scheduled_date_time)));
                                              @endphp


                                                <div  id="date_time_set_div{{$cart_data->id}}" @if(count($cartData)>1 && ($cd !=  $lastKey)) style="pointer-events:none" @endif>

                                                    <h4 class="mb-2"><b>{{__('When would you like your service?')}}</b></h4>
                                                    <div class="date-items radio-btns hide">
                                                        @foreach ($period as $key => $date)
                                                            <div>
                                                                @php
                                                                $checked = '';
                                                                $singleDate =  trim(date('Y-m-d', strtotime($date)));
                                                                if($productDate == $singleDate && !empty($productDate)){
                                                                    $checked = "checked";
                                                                }
                                                                @endphp
                                                                <div class="radios">
                                                                    <p>{{date('D', strtotime($date))}}</p>
                                                                    <div class="alCustomHomeServiceRadio">
                                                                        <input type="radio" class="check-time-slots ondemand-time-slots" data-product_vendor_id="{{$cart_data->vendor_id}}" data-cart_product_id = "{{$cart_data->id}}" value='{{date('Y-m-d', strtotime($date))}}' name='booking_date' id='radio{{$cd}}{{$key}}' @if($key == 0) checked @endif />
                                                                        <label for='radio{{$cd}}{{$key}}'>
                                                                        <span class="customCheckbox" aria-hidden="true" >{{date('d', strtotime($date))}}</span>
                                                                        </label>
                                                                        <input type="hidden" name="productid" id="productid" value="{{$cart_data->id}}" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <div class="booking-time-wrapper" id="show-all-time-slots{{$cart_data->id}}" style="@if($cart_data->schedule_slot != '')  @else display: none; @endif ">
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
                                                                        <input type="radio" value='{{$date}} - {{@$time_slots[$key+1]}}' name='booking_time' id='time{{$cart_data->id}}{{$key+1}}' @if($checked) {{$checked}} @endif/>
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
                                                    </div>

                                                    <input type="hidden" class="custom-control-input check" id="taskschedule" name="task_type" value="schedule" checked>
                                                </div>
                                                <hr>
                                                @endif
                                            @endforeach

                                                <div class="booking-time-wrapper">
                                                    <h4 class="mt-4 mb-2"><b>{{__('Do you have any specific instructions?')}}</b></h4>
                                                    <textarea class="form-control" name="specific_instructions" id="specific_instructions" cols="30" rows="7"></textarea>
                                                </div>

                                        </div>
                                         <!--end step 2 html -->
                                    @endif


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

                            <div class="col-md-4">
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
                                        <ul class="show-div shopping-cart d-none" id="header_cart_main_ul_ondemand">
                                        </ul>
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
                                                                <a  class="action-icon d-block mb-3 remove_product_via_cart" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </li>

                                                        <% if(vendor_product.addon.length != 0) { %>
                                                            <div class="row align-items-md-center">
                                                                <div class="col-12 alVendorProductDetails">
                                                                    <h6 class="m-0 pl-0"><b>{{__('Add Ons')}}</b></h6>
                                                                </div>
                                                            </div>
                                                            <% _.each(vendor_product.addon, function(addon, ad){%>
                                                            <div class="row alVendorProductDetails">
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
                                                <li class="alVendorProductDetails">
                                                    <div class='media-body'>
                                                        <h6 class="d-flex align-items-center justify-content-between">
                                                            <span class="ellips">{{__('Date')}}</span>
                                                            <span id="show_date{{$cart_data->id}}">@if(isset($cart_data->scheduled_date_time)) {{ date('d-m-Y', strtotime($cart_data->scheduled_date_time)) }}  @else -- @endif </span>
                                                        </h6>
                                                    </div>
                                                </li>

                                                <li class="alVendorProductDetails">
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
                                                            <span>{{Session::get('currencySymbol')}}<%=  Helper.formatPrice(cart_details.gross_amount) %></span>
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



                                    </div>
                                </div>
                                <div class="footer-card">
                                    @if((app('request')->input('step') == '1' || empty(app('request')->input('step'))) && empty(app('request')->input('addons')))
                                        <a href="?step=2" id="next-button-ondemand-2" style="display: none;"><span class="btn btn-solid float-right">Next</span></a>
                                        @elseif(app('request')->input('step') == '1' && app('request')->input('addons') == '1')
                                            <a href="?step=1"><span class="btn btn-solid float-left"><</span></a>
                                            <a href="?step=2&dateset=1&addons=1" id="next-button-ondemand-2"><span class="btn btn-solid float-right">Next</span></a>
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
        </section>

        

        <!-- Why people visit here start -->
        {{-- <p>{!! $vendor->dynamic_html !!}</p> --}}
        @foreach ($vendor->vendor_section as $key=>$vendorSection)
        <section class="whyPeopleVisit py-5" id="vendor_section_{{ $vendorSection->id }}">
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
        				<div class="alSpaListHead text-center text-lg-left col-12 mb-5">
	                        <p class="alLgFontSize">{{ $vendorSection->headingTranslation->first() ?  $vendorSection->headingTranslation->first()->heading : 'NA' }}</p>
	                    </div>
        			</div>
                    @foreach ($vendorSection->SectionTranslation as $subkey=>$SectionTranslation)
                    <div class="col-md-6">
        				<div class="whyPeopleVisitDetails mb-4">
	        				<p>{{ $SectionTranslation->first() ?  $SectionTranslation->first()->title : 'NA'  }}</p>
	        				<p class="alBodyText">{{ $SectionTranslation->first() ?  $SectionTranslation->first()->description : 'NA'  }}</p>
        				</div>
        			</div>
                    @endforeach
        		</div>
        	</div>
        </section>
        @endforeach
    
        <!-- Why people visit here end -->

        <!-- More spas nearby start -->
        <section class="moreSpasNearby py-5">
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12">
        				<div class="alSpaListHead text-center text-lg-left col-12 mb-5">
	                        <p class="alLgFontSize">{{ __('More ') . getNomenclatureName('vendors', false). __(' nearby')}}</p>
	                        <p class="alBodyText">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt dolore magna aliqua.</p>
	                    </div>
        			</div>
        		</div>
        	</div>
        	<div class="container-fluid">
        		<div class="row">
        			<div class="googleMapArea col-md-12 p-0">
        				<!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13720.904154980397!2d76.81441854999998!3d30.71204525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1657101273720!5m2!1sen!2sin" width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
                        <div id="vendor-map-container">
                            <div id="vendor-map" class="w-100" style="height:400px"></div>
                        </div>
        			</div>
        		</div>
        	</div>
        </section>
        <!-- More spas nearby end -->

        <!-- sections SpasRelated start -->
        <section class="SpasRelated py-5">
            <div class="container">
                <div class="row">
                    <!-- alSpaListSlider start -->
                    <div class="Spasslider w-100" id="Spasslider">
                        @foreach($Map_vendors as $key => $value)
                            <div>
                                <div class="SpasRelatedItems mx-2">
                                    <div class="SpasRelatedItemsImageBox">
                                        <img class="rounded" src="{{ $value->banner['image_fit'] . '400/400' . $value->banner['image_path'] }}">
                                    </div>
                                    <div class="SpasRelatedDetails p-2">
                                        <p class="text-left m-0">{{ $value->name }}</p>
                                        <a href="javascript:void(0)">10 Excellent (2 reviews)</a>
                                        <p class="alBodyText m-0 d-flex align-items-center"><span class="border-right pr-2 mr-2">{{ $value->state ?? 'NA' }}</span><span>{{ number_format($value->vendorToUserDistance ,2) }} {{ (!empty($client_preference_detail->distance_unit_for_time)) ? ($client_preference_detail->distance_unit_for_time ==  'kilometer' ? 'KM' : 'miles') : 'KM' }} {{ __('away') }}</span></p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div><!-- alSpaListSlider start -->
                </div>
            </div>
        </section>
        @if(session('vendorType') != 'on_demand')
            <script type="text/template" id="header_cart_template_ondemand">
                <ul class="pl-2 pr-2 pb-2 pt-0 ">
                    <div class="dcpj" >
                    <% _.each(cart_details.products, function(product, key){%>
                        <li class="p-0">
                            <h6 class="d-flex justify-content-center badge badge-light font-14"><b><%= product.vendor.name %></b></h6>
                        </li>

                        <% if( (product.isDeliverable != undefined) && (product.isDeliverable == 0) ) { %>
                            <li class="border_0">
                                <th colspan="7">
                                    <div class="text-danger">
                                        {{ __('Products for this vendor are not deliverable at your area. Please change address or remove product.') }}
                                    </div>
                                </th>
                            </li>
                            <% } %>
                        <% _.each(product.vendor_products, function(vendor_product, vp){%>
                            <li class="p-0" id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                                <div class='media-body'>
                                    <h6 class="d-flex align-items-center justify-content-between m-0">
                                        <span class="ellips"><%= vendor_product.quantity %>x <%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></span>
                                        <span>

                                            {{ Session::get('currencySymbol') }}<%=  Helper.formatPrice(vendor_product.quantity_price) %>

                                        </span>
                                        <a class="action-icon remove_product_via_cart text-danger" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                    </h6>
                                </div>
                            </li>

                            <% if(vendor_product.addon.length != 0) { %>
                                <div class="row align-items-md-center">
                                    <div class="col-12">
                                        <h6 class="m-0 font-12"><b>{{ __('Add Ons') }}</b></h6>
                                    </div>
                                </div>
                                <% _.each(vendor_product.addon, function(addon, ad){%>
                                <div class="row mb-1">
                                    <div class="col-md-6 col-sm-4 items-details text-left">
                                        <p class="m-0 font-14 p-0"><%= vendor_product.quantity %>x <%= addon.option.title %></p>
                                    </div>
                                    <div class="col-md-3 col-sm-4 text-center">
                                        <div class="extra-items-price font-14">{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(addon.option.price_in_cart) %></div>
                                    </div>
                                    <div class="col-md-3 col-sm-4 text-right">
                                        <div class="extra-items-price font-14 mr-xl-3">{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(addon.option.quantity_price) %></div>
                                    </div>
                                </div>
                                <% }); %>
                            <% } %>

                        <% }); %>
                        <% if(product.delivery_fee_charges > 0) { %>
                            <div class="row justify-content-between">
                                <div class="col-md-6 col-sm-6 text-left">
                                    <h6 class="m-0 font-14"><b>{{ __('Delivery fee') }}</b></h6>
                                </div>
                                <div class="col-md-6 col-sm-6 text-right">
                                    <div class="extra-items-price font-14 mr-xl-3">{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(product.delivery_fee_charges) %></div>
                                </div>
                            </div>
                        <% } %>
                        <hr class="my-2">
                    <% }); %>

                    <h5 class="d-flex align-items-center justify-content-between pb-2">{{ __('PRICE DETAILS') }} </h5>
                    <li class="p-0 alSixCart">
                        <div class='media-body'>
                            <h6 class="d-flex align-items-center justify-content-between">
                                <span class="ellips">{{ __('Price') }}</span>
                                <span >{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(cart_details.gross_amount) %></span>
                            </h6>
                        </div>
                    </li>

                    <li class="p-0 alSixCart">
                        <div class='media-body'>
                            <h6 class="d-flex align-items-center justify-content-between">
                                <span class="ellips">{{ __('Tax') }}</span>
                                <span>{{ Session::get('currencySymbol') }}<%= cart_details.total_taxable_amount %></span>
                            </h6>
                        </div>
                    </li>

                    <% if(cart_details.total_subscription_discount != undefined) { %>
                        <li class="p-0 alSixCart">
                            <div class='media-body'>
                                <h6 class="d-flex align-items-center justify-content-between">
                                    <span class="ellips">{{ __('Subscription Discount') }}</span>
                                    <span>{{ '-'.Session::get('currencySymbol') }}<%= cart_details.total_subscription_discount %></span>
                                </h6>
                            </div>
                        </li>
                    <% } %>

                    <% if(cart_details.loyalty_amount > 0) { %>
                    <li class="p-0 alSixCart">
                        <div class='media-body'>
                            <h6 class="d-flex align-items-center justify-content-between">
                                <span class="ellips">{{ __('Loyalty Amount') }} </span>
                                <span>{{'-'.Session::get('currencySymbol') }}<%= cart_details.loyalty_amount %></span>
                            </h6>
                        </div>
                    </li>
                    <% } %>

                    <% if(cart_details.wallet_amount_used > 0) { %>
                    <li class="p-0 alSixCart">
                        <div class='media-body'>
                            <h6 class="d-flex align-items-center justify-content-between">
                                <span class="ellips"> {{ __('Wallet Amount') }} </span>
                                 <span>{{ '-'.Session::get('currencySymbol') }}<%= cart_details.wallet_amount_used %></span>
                            </h6>
                        </div>
                    </li>
                    <% } %>
                    </ul>
                    <div class="cart-sub-total d-flex align-items-center justify-content-between">
                        <span>{{ __('Total') }}</span>
                        <span>{{ Session::get('currencySymbol') }}<%= cart_details.total_payable_amount %></span>
                    </div>
                    <a class="checkout-btn text-center d-block" href="{{ route('showCart') }}">{{ __('Checkout') }}</a>
                    </div>
            </script>
        @endif
    <script type="text/template" id="empty_cart_template">
        <div class="row">
                                    <div class="col-12 text-center pb-3">
                                        <img class="w-50 pt-3 pb-1" src="{{ asset('front-assets/images/ic_emptycart.svg') }}" alt="">
                                        <h5>Your cart is empty<br/>{{ __('Add an item to begin') }}</h5>
                                    </div>
                                </div>
                            </script>
    <script type="text/template" id="variant_image_template">
        <img src="<%= media.image_fit %>300/300<%= media.image_path %>" alt="">
                            </script>
    <script type="text/template" id="variant_template">
        <% if(variant.product.inquiry_only == 0) { %>
                                    <%= variant.productPrice %>
                                    <% if(variant.compare_at_price > 0 ) { %>
                                        <span class="org_price ml-1 font-14">{{ Session::get('currencySymbol') }}<%= variant.compare_at_price %></span>
                                    <% } %>
                                <% } %>
                            </script>
    <script type="text/template" id="variant_quantity_template">
        <% if(variant.quantity > 0){ %>
                                    <%
                                    var is_customizable = false;
                                    if(variant.isAddonExist > 0){
                                        is_customizable = true;
                                    }
                                    %>
                                    <% if(variant.check_if_in_cart != '') { %>
                                        {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                                        <a class="add-cart-btn add_vendor_product" style="display:none;" id="add_button_href<%= variant.check_if_in_cart.id %>" data-variant_id="<%= variant.id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>" data-product_id="<%= variant.product_id %>" href="javascript:void(0)">{{ __('Add') }}</a>
                                        <div class="number" id="show_plus_minus<%= variant.check_if_in_cart.id %>">
                                            <span class="minus qty-minus-product <% if(is_customizable){ %> remove-customize <% } %>"  data-parent_div_id="show_plus_minus<%= variant.check_if_in_cart.id %>" data-id="<%= variant.check_if_in_cart.id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>" data-product_id="<%= variant.product_id %>" data-cart="<%= variant.check_if_in_cart.cart_id %>">
                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                            </span>
                                            <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="<%= variant.check_if_in_cart.quantity %>" class="input-number" step="0.01" id="quantity_ondemand_<%= variant.check_if_in_cart.id %>" readonly>
                                            <span class="plus qty-plus-product <% if(is_customizable){ %> repeat-customize <% } %>"  data-id="<%= variant.check_if_in_cart.id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>" data-product_id="<%= variant.product_id %>" data-cart="<%= variant.check_if_in_cart.cart_id %>">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    <% }else{ %>
                                        {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                                        <a class="add-cart-btn add_vendor_product" id="aadd_button_href<%= variant.product_id %>" data-variant_id="<%= variant.id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= variant.product.vendor_id %>" data-product_id="<%= variant.product_id %>" data-addon="<%= variant.isAddonExist %>" href="javascript:void(0)">{{ __('Add') }}</a>
                                        <div class="number" style="display:none;" id="ashow_plus_minus<%= variant.product_id %>">
                                            <span class="minus qty-minus-product"  data-parent_div_id="show_plus_minus<%= variant.product_id %>" readonly data-id="<%= variant.product_id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.product.vendor_id %>">
                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                            </span>
                                            <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d<%= variant.product_id %>" readonly placeholder="1" type="text" value="2" class="input-number input_qty" step="0.01">
                                            <span class="plus qty-plus-product"  data-id="" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.product.vendor_id %>">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </span>spa_slider_custom
                                        </div>
                                    <% } %>
                                    {{-- <% if(is_customizable){ %>
                                        <div class="customizable-text">customizable</div>
                                    <% } %> --}}
                                <% }else{ %>
                                    <span class="text-danger">Out of stock</span>
                                <% } %>
                            </script>
    <script type="text/template" id="addon_template">
        <% if(addOnData != ''){ %>
                                    <% if(addOnData.product_image){ %>
                                        <div class="d-flex" style="height:200px">
                                            <img class="w-100" src="<%= addOnData.product_image %>" alt=""  style="object-fit:cover">
                                        </div>
                                    <% } %>
                                    <div class="modal-header">
                                        <div class="d-flex flex-column">
                                            <h5 class="modal-title" id="product_addonLabel"><%= addOnData.translation_title %></h5>
                                            <% if(addOnData.averageRating > 0){ %>
                                            <div class="rating-text-box justify-content-start" style="width: max-content;">
                                                <span><%= addOnData.averageRating %></span>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
                                            <% } %>
                                            <span><small><%= addOnData.translation_description %></small></span>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <% _.each(addOnData.add_on, function(addon, key1){ %>
                                            <div class="border-product border-top">
                                                <div class="addon-product" style="padding: 16px;">
                                                    <h4 addon_id="<%= addon.addon_id %>" class="header-title productAddonSet mb-0"><%= addon.title %></h4>
                                                    <div class="addonSetMinMax mb-2">
                                                        <%
                                                            var min_select = '';
                                                            if(addon.min_select > 0){
                                                                min_select = 'Minimum ' + addon.min_select;
                                                            }
                                                            var max_select = '';
                                                            if(addon.max_select > 0){
                                                                max_select = 'Maximum ' + addon.max_select;
                                                            }
                                                            if( (min_select != '') && (max_select != '') ){
                                                                min_select = min_select + ' and ';
                                                            }
                                                        %>
                                                        <% if( (min_select != '') || (max_select != '') ) { %>
                                                            <small><%=min_select + max_select %> Selections allowed</small>
                                                        <% } %>
                                                    </div>
                                                    <div class="productAddonSetOptions" data-min="<%= addon.min_select %>" data-max="<%= addon.max_select %>" data-addonset-title="<%= addon.title %>">
                                                        <% _.each(addon.setoptions, function(option, key2){ %>
                                                            <% if(key2 == '5')  { %>
                                                                <div class="d-flex justify-content-end">
                                                                    <a class="show_subet_addeon" data-div_id_show="subOption<%= addon.addon_id  %>_<%= key2  %>"  href="javascript:void(0)">{{ __('Show more') }}</a>
                                                                </div>
                                                                <div class="more-subset d-none" id="subOption<%= addon.addon_id %>_<%= key2 %>" >
                                                            <% } %>
                                                            <div class="checkbox-success d-flex mb-1 " <%= key2  %> >
                                                                <label class="pr-2 mb-0 flex-fill font-14" for="inlineCheckbox_<%= key1 %>_<%= key2 %>">
                                                                    <%= option.title %>
                                                                </label>
                                                                <div>
                                                                    <span class="addon_price mr-1 font-14">{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(option.price) %></span>
                                                                    <input type="checkbox" id="inlineCheckbox_<%= key1 %>_<%= key2 %>" class="product_addon_option" name="addonData[<%= key1 %>][]" addonId="<%= addon.addon_id %>" addonOptId="<%= option.id %>" addonPrice="<%= option.price %>">
                                                                </div>
                                                            </div>
                                                            <% if((key2 > 5) && (key2 == (_.size(addon.setoptions) - 1 )) ){ %>
                                                            </div>
                                                            <% } %>
                                                        <% }); %>
                                                    </div>
                                                </div>
                                            </div>
                                        <% }); %>
                                        <div class="addon_response text-danger font-14 d-none" style="padding:0 16px"></div>
                                    </div>
                                    <div class="modal-footer flex-nowrap align-items-center">
                                        <div class="counter-container d-flex align-items-center">
                                            <span class="minus qty-action" >
                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                            </span>
                                            <input style="text-align:center; width:60px; height:24px; padding-bottom: 3px; border:none" placeholder="1" type="text" value="1" class="addon-input-number" step="1" readonly>
                                            <span class="plus qty-action" >
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                        <input type="hidden" id="addonVariantPriceVal" value="<%= addOnData.variant_price %>">
                                        <a class="btn btn-solid add-cart-btn flex-fill add_vendor_addon_product" id="add_vendor_addon_product" href="javascript:void(0)" data-variant_id="<%= addOnData.variant[0].id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= addOnData.vendor_id %>" data-product_id="<%= addOnData.id %>">{{ __('Add') }} {{ Session::get('currencySymbol') }}<span class="addon_variant_price"><%= addOnData.variant_price %></span></a>
                                    </div>
                                <% } %>
                            </script>
    <div class="modal fade remove-item-modal" id="remove_item_modal" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-labelledby="remove_itemLabel" aria-hidden="true"
        style="background-color: rgba(0,0,0,0.8); z-index: 1051">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="remove_itemLabel">{{ __('Remove Item') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="vendor_id" value="">
                    <input type="hidden" id="product_id" value="">
                    <input type="hidden" id="cartproduct_id" value="">
                    <h6 class="m-0">{{ __('Are You Sure You Want To Remove This Item?') }}</h6>
                </div>
                <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                    <button type="button" class="btn btn-solid black-btn"
                        data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-solid" id="remove_product_button">{{ __('Remove') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade product-addon-modal" id="product_addon_modal" tabindex="-1" aria-labelledby="product_addonLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <div class="modal fade repeat-item-modal" id="repeat_item_modal" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-labelledby="repeat_itemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="repeat_itemLabel">{{ __('Repeat last used customization') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="last_cart_product_id" value="">
                    <input type="hidden" class="curr_variant_id" value="">
                    <input type="hidden" class="curr_vendor_id" value="">
                    <input type="hidden" class="curr_product_id" value="">
                    <input type="hidden" class="curr_product_has_addons" value="">
                    <input type="hidden" add_to_cart_url="cart" value="{{ route('addToCart') }}">
                </div>
                <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                    <button type="button" class="btn btn-solid black-btn"
                        data-dismiss="modal">{{ __('Add new') }}</button>
                    <button type="button" class="btn btn-solid" id="repeat_item_btn">{{ __('Repeat last') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade customize-repeated-item-modal" id="customize_repeated_item_modal" data-backdrop="static"
        data-keyboard="false" tabindex="-1" aria-labelledby="customize_repeated_itemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <!-- vendorStories -->
    <!-- <div id="vendorStories" class="modal fade" tabindex="-1" aria-labelledby="vendorStoriesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
                <img class="modal-content" id="img01">
        </div>
    </div> -->

@endsection
@section('script')

    <script src="{{ asset('front-assets/js/rangeSlider.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/my-sliders.js') }}"></script>
    <script>
        var update_cart_product_schedule = "{{route('cart.updateProductSchedule')}}";
        // @if(!empty($vendor->banner))
        //     $(document).ready(function() {
        //         $("body").addClass("homeHeader");
        //     });
        // @endif

         //Get the modal vendorStories
        var modal = document.getElementById("vendorStories");

         //Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById("vendorStoriesImg");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        if(document.getElementById("vendorStoriesImg")){
            img.onclick = function(){
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.alt;
            }
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

         //When the user clicks on <span> (x), close the modal
        span.onclick = function() {
        modal.style.display = "none";
        }
    </script>
    <script>
        var get_product_addon_url = "{{ route('vendorProductAddons') }}"

        jQuery(window).scroll(function() {

            var scroll = jQuery(window).scrollTop();
            var categories_list_height = $('.vendor-products-wrapper').height() +400;

            if (scroll >= 400) {
                jQuery(".categories-product-list").addClass("fixed-bar");
            } else {
                jQuery(".categories-product-list").removeClass("fixed-bar");
            }
            if(scroll >= categories_list_height){
                jQuery(".categories-product-list").removeClass("fixed-bar");
            }
        });

        var addonids = [];
        var addonoptids = [];
        var showChar = 136;
        var ellipsestext = "...";
        var moretext = "Read more";
        var lesstext = "Read less";

        function addReadMoreLink(){
            $('.price_head .member_no span').each(function() {
                var content = $(this).html();
                if (content.length > showChar) {

                    var firstContent = content.substr(0, showChar);
                    var lastContent = content.substr(showChar, content.length - showChar);

                    var html = firstContent + '<span class="moreellipses">' + ellipsestext +
                        '&nbsp;</span><span class="morecontent"><span style="display:none;">' + lastContent +
                        '</span><a href="" class="morelink">' + moretext + '</a></span>';

                    $(this).html(html);
                }

            });
        }
        addReadMoreLink();

        $(document).on('click', '.morelink', function() {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });

        $(document).delegate(".product_tag_filter", "change", function() {
            vendorProductsSearchResults();
        });
    </script>
    <script>
        var base_url = "{{ url('/') }}";
        var place_order_url = "{{ route('user.placeorder') }}";
        var payment_stripe_url = "{{ route('payment.stripe') }}";
        var user_store_address_url = "{{ route('address.store') }}";
        var promo_code_remove_url = "{{ route('remove.promocode') }}";
        var payment_paypal_url = "{{ route('payment.paypalPurchase') }}";
        var update_qty_url = "{{ url('product/updateCartQuantity') }}";
        var promocode_list_url = "{{ route('verify.promocode.list') }}";
        var payment_option_list_url = "{{ route('payment.option.list') }}";
        var apply_promocode_coupon_url = "{{ route('verify.promocode') }}";
        var payment_success_paypal_url = "{{ route('payment.paypalCompletePurchase') }}";
        var getTimeSlotsForOndemand = "{{ route('getTimeSlotsForOndemand') }}";
        var update_cart_schedule = "{{ route('cart.updateSchedule') }}";
        var showCart = "{{ route('showCart') }}";
        var update_addons_in_cart = "{{ route('addToCartAddons') }}";
        var vendor_products_page_search_url = "{{ route('vendorProductsSearchResults') }}";
        var get_last_added_product_variant_url = "{{ route('getLastAddedProductVariant') }}";
        var get_product_variant_with_different_addons_url = "{{ route('getProductVariantWithDifferentAddons') }}"
        var addonids = [];
        var addonoptids = [];
        var ajaxCall = 'ToCancelPrevReq';


        $(document).on('click', '.show_subet_addeon', function(e) {
            e.preventDefault();
            var show_class = $(this).data("div_id_show");
            $(this).addClass("d-none");
            $("#" + show_class).removeClass("d-none");
        });
        $(document).on('click', '#show_facilty', function(e) {
            e.preventDefault();
            console.log('asd');
            $(this).addClass("d-none");
            $('#show_facilty_more').removeClass("d-none");

        });

        $(document).delegate('.changeVariant', 'change', function() {
            var variants = [];
            var options = [];
            var product_variant_url = "{{ route('productVariant', ':sku') }}";
            var sku = $(this).parents('.product_row').attr('data-p_sku');
            var that = this;
            $(that).parents('.product_row').find('.changeVariant').each(function() {
                if (this.val != '') {
                    variants.push($(this).attr('vid'));
                    options.push($(this).val());
                }
            });
            // console.log(variants);
            // console.log(options);
            // return 0;
            ajaxCall = $.ajax({
                type: "post",
                dataType: "json",
                url: product_variant_url.replace(":sku", sku),
                data: {
                    "_token": "{{ csrf_token() }}",
                    "variants": variants,
                    "options": options,
                },
                beforeSend: function() {
                    if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                        ajaxCall.abort();
                    }
                },
                success: function(response) {
                    if (response.status == 'Success') {
                        response = response.data;
                        $(that).parents('.product_row').find(".variant_response span").html('');
                        if (response.variant != '') {

                            $(that).parents('.product_row').find(".add-cart-btn").attr(
                                'data-variant_id', response.variant.id);

                            $(that).parents('.product_row').find('.product_price').html('');
                            let variant_template = _.template($('#variant_template').html());
                            $(that).parents('.product_row').find('.product_price').append(
                                variant_template({
                                    variant: response.variant
                                }));

                            $(that).parents('.product_row').find('.product_variant_quantity_wrapper')
                                .html('');
                            let variant_quantity_template = _.template($('#variant_quantity_template')
                                .html());
                            $(that).parents('.product_row').find('.product_variant_quantity_wrapper')
                                .append(variant_quantity_template({
                                    variant: response.variant
                                }));

                            let variant_image_template = _.template($('#variant_image_template')
                                .html());

                            $(that).parents('.product_row').find('.product_image').html('');
                            $(that).parents('.product_row').find('.product_image').append(
                                variant_image_template({
                                    media: response.variant
                                }));
                        }
                    } else {
                        $(that).parents('.product_row').find(".variant_response span").html(response
                            .message);
                        $(that).parents('.product_row').find(".add-cart-btn").hide();
                        $(that).parents('.product_row').find(
                            ".product_variant_quantity_wrapper .text-danger").remove();
                    }
                },
                error: function(data) {

                },
            });
        });

        $(document).delegate("#vendor_search_box", "input", function() {
            let keyword = $(this).val();
            vendorProductsSearchResults();
        });

        function vendorProductsSearchResults() {
            let keyword = $("#vendor_search_box").val();
            let order_type = $("#order_type").val();
            var checkboxesChecked = [];
            $("input:checkbox[name=tag_id]:checked").each(function() {
                checkboxesChecked.push($(this).val());
            });
            var checkedvalus = checkboxesChecked.length > 0 ? checkboxesChecked : null;
            // if (keyword.length > 2 || keyword.length == 0) {
            ajaxCall = $.ajax({
                type: "post",
                dataType: 'json',
                url: vendor_products_page_search_url,
                data: {
                    tag_id: checkedvalus,
                    keyword: keyword,
                    order_type: order_type,
                    vendor: "{{ $vendor->id }}",
                    vendor_template_id: "6",
                    vendor_category: "{{ $vendor_category ?? '' }}"
                },
                beforeSend: function() {
                    if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                        ajaxCall.abort();
                    }
                },
                success: function(response) {
                    if (response.status == 'Success') {
                        var cart_html = $('.vendor-products-wrapper #header_cart_main_ul_ondemand').html();
                        $('.vendor-products-wrapper').html(response.html);
                        $('.vendor-products-wrapper #header_cart_main_ul_ondemand').html(cart_html);
                        $('#header_cart_main_ul_ondemand').removeClass('d-none');
                        addReadMoreLink();
                    }
                }
            });
            // }
        }

        vendorAllOnMap();

     function vendorAllOnMap() {
         var latitude = "{{ $vendor->latitude }}";
         var longitude = "{{ $vendor->longitude }}";
         var latlng = new google.maps.LatLng(latitude, longitude);

         map = new google.maps.Map(document.getElementById('vendor-map'), {
             center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
             zoom: 12
         });

         var url = window.location.origin;
         var vendorData = {!!json_encode($Map_vendors)!!};
           //vendor  markers
         for (let i = 0; i < vendorData.length; i++) {
             vendor = vendorData[i];

             if(vendor.address != null && vendor.latitude != "0.00000000" && vendor.longitude != "0.00000000" ){
                var contentString = '';

                contentString =
                         '<div id="content">' +
                         '<div id="siteNotice">' +
                         "</div>" +
                         '<h5 id="firstHeading" class="firstHeading">'+vendor.name+'</h5>' +
                         '<div id="bodyContent">' +
                         "<p><b>Address :- </b> " +vendor.address+ " " +
                         ".</p>" +
                         '<p><b>Contact: +'+ vendor?.dial_code +vendor?.phone_no+' </p>' +
                         "</div>" +
                         "</div>";


                const infowindow = new google.maps.InfoWindow({
                                    content: contentString,
                                    minWidth: 250,
                                    minheight: 250,
                                });
                images = "{{ asset('assets/images/mapVendoricon.png') }}";

                var image = {
                        url: images, // url
                        scaledSize: new google.maps.Size(30, 40), // scaled size
                        origin: new google.maps.Point(0,0), // origin
                        anchor: new google.maps.Point(22,22) // anchor
                    };
                const marker = new google.maps.Marker({
                            icon: image,
                            map: map,
                            position: { lat: parseFloat(vendor.latitude), lng: parseFloat(vendor.longitude) },
                        });
                marker.addListener("click", () => {
                    infowindow.open(map, marker);
                });

             }

         }
     }
    </script>

@endsection
