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
         <div class="row d-none">
            <div class="col-sm-3 collection-filter">
               <div class="collection-filter-block">
                  <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i>{{__('Back')}}</span></div>
                  <div class="collection-collapse-block open">
                     @if(!empty($brands) && count($brands) > 0)
                     <h3 class="collapse-block-title">brand</h3>
                     <div class="collection-collapse-block-content">
                        <div class="collection-brand-filter">
                           @foreach($brands as $key => $val)
                           <div class="custom-control custom-checkbox collection-filter-checkbox">
                              <input type="checkbox" class="custom-control-input productFilter" fid="{{$val->brand_id}}" used="brands" id="brd{{$val->brand_id}}">
                              @foreach($val->brand->translation as $k => $v)
                              <label class="custom-control-label" for="brd{{$val->brand_id}}">{{$v->title}}</label>
                              @endforeach
                           </div>
                           @endforeach
                        </div>
                     </div>
                     @endif
                  </div>
                  @if(!empty($variantSets) && count($variantSets) > 0)
                  @foreach($variantSets as $key => $sets)
                  <div class="collection-collapse-block border-0 open">
                     @php
                     $slug = $sets->variantDetail->varcategory->cate ? $sets->variantDetail->varcategory->cate->slug.' > ' : '';
                     @endphp
                     @if($slug)
                     <h3 class="collapse-block-title"> {{$slug . $sets->title}}</h3>
                     <div class="collection-collapse-block-content">
                        <div class="collection-brand-filter">
                           @if($sets->type == 2)
                           @foreach($sets->options as $ok => $opt)
                           <div class="chiller_cb small_label d-inline-block color-selector">
                              <?php $checkMark = ($key == 0) ? 'checked' : ''; ?>
                              <input class="custom-control-input productFilter" type="checkbox" {{$checkMark}} id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" used="variants" optid="{{$opt->id}}">
                              <label for="Opt{{$key.'-'.$opt->id}}"></label>
                              @if(strtoupper($opt->hexacode) == '#FFF' || strtoupper($opt->hexacode) == '#FFFFFF')
                              <span style="background: #FFFFFF; border-color:#000;" class="check_icon white_check"></span>
                              @else
                              <span class="check_icon" style="background:{{$opt->hexacode}}; border-color: {{$opt->hexacode}};"></span>
                              @endif
                           </div>
                           @endforeach
                           @else
                           @foreach($sets->options as $ok => $opt)
                           <div class="custom-control custom-checkbox collection-filter-checkbox">
                              <input type="checkbox" class="custom-control-input productFilter" id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" type="variants" optid="{{$opt->id}}">
                              <label class="custom-control-label" for="Opt{{$key.'-'.$opt->id}}">{{$opt->title}}</label>
                           </div>
                           @endforeach
                           @endif
                        </div>
                     </div>
                     @endif
                  </div>
                  @endforeach
                  @endif
                  @if($show_range == 1)
                  <div class="collection-collapse-block border-0 open">
                     <h3 class="collapse-block-title">{{__('Price')}}</h3>
                     <div class="collection-collapse-block-content">
                        <div class="wrapper mt-3">
                           <div class="range-slider">
                              <input type="text" class="js-range-slider rangeSliderPrice" value="" />
                           </div>
                        </div>
                     </div>
                  </div>
                  @endif
               </div>
               <div class="theme-card">
                  <h5 class="title-border">{{__('New Product')}}</h5>
                  <div class="offer-slider slide-1">
                     @if(!empty($newProducts) && count($newProducts) > 0)
                     @foreach($newProducts as $newProds)
                     <div>
                        @foreach($newProds as $new)
                        <?php $imagePath = '';
                           foreach ($new['media'] as $k => $v) {
                               $imagePath = $v['image']['path']['proxy_url'] . '300/300' . $v['image']['path']['image_fit'];
                           } ?>
                        <div class="media">
                           <a href="{{route('productDetail', $new['url_slug'])}} "><img class="img-fluid blur-up lazyload" style="max-width: 200px;" src="{{$imagePath}}" alt=""></a>
                           <div class="media-body align-self-center">
                              <div class="inner_spacing">
                                 <a href="{{route('productDetail', $new['url_slug'])}}">
                                    <h3>{{ $new['translation_title'] }}</h3>
                                    @if($new['inquiry_only'] == 0)
                                    <h4 class="mt-1">
                                       <?php $multiply = $new['variant_multiplier']; ?>
                                       {{ Session::get('currencySymbol').' '.(number_format($new['variant_price'] * $multiply,2))}}
                                    </h4>
                                    @endif
                                    @if($client_preference_detail)
                                    @if($client_preference_detail->rating_check == 1)
                                    @if($new['averageRating'] > 0)
                                    <span class="rating">{{ $new['averageRating'] }} <i class="fa fa-star text-white p-0"></i></span>
                                    @endif
                                    @endif
                                    @endif
                                 </a>
                              </div>
                           </div>
                        </div>
                        @endforeach
                     </div>
                     @endforeach
                     @endif
                  </div>
               </div>
               <!-- side-bar banner end here -->
            </div>
            <div class="collection-content col">
               <div class="page-main-content">
                  <div class="row">
                     <div class="col-sm-12">
                        <div class="top-banner-wrapper mb-4">
                           @if(!empty($vendor->banner))
                           <div class="common-banner text-center"><img alt="" src="{{$vendor->banner['proxy_url'] . '1000/200' . $vendor->banner['image_path']}}" class="img-fluid blur-up lazyload"></div>
                           @endif
                           <div class="row mt-n4">
                              <div class="col-12">
                                 <form action="">
                                    <div class="row">
                                       <div class="col-sm-12 text-center">
                                          <div class="file file--upload">
                                             <label>
                                             <span class="update_pic border-0">
                                             <img src="{{$vendor->logo['image_fit'] . '1000/200' . $vendor->logo['image_path']}}" alt="">
                                             </span>
                                             </label>
                                          </div>
                                          <div class="name_location d-block py-0">
                                             <h4 class="mt-0 mb-1"><b>{{$vendor->name}}</b></h4>
                                          </div>
                                          @if($vendor->is_show_vendor_details == 1)
                                          <div class="">
                                             @if($vendor->email)
                                             <a href="{{$vendor->email}}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->email}}"><i class="fa fa-envelope"></i></a>
                                             @endif
                                             <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->address}}"><i class="fa fa-address-card mx-1"></i></a>
                                             @if($vendor->website)
                                             <a href="{{http_check($vendor->website) }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->website}}"><i class="fa fa-home"></i></a>
                                             @endif
                                          </div>
                                          @endif
                                       </div>
                                       @if($vendor->desc)
                                       <div class="col-md-12 text-center">
                                          <p>{{$vendor->desc}}</p>
                                       </div>
                                       @endif
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                        <div class="collection-product-wrapper">
                           <div class="product-top-filter">
                              <div class="row">
                                 <div class="col-xl-12">
                                    <div class="filter-main-btn"><span class="filter-btn btn btn-theme"><i class="fa fa-filter" aria-hidden="true"></i>{{__('Filter')}}</span></div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-12">
                                    <div class="product-filter-content border-left">
                                       <div class="collection-view">
                                          <ul>
                                             <li><i class="fa fa-th grid-layout-view"></i></li>
                                             <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                          </ul>
                                       </div>
                                       <div class="collection-grid-view">
                                          <ul>
                                             <li><img src="{{asset('front-assets/images/icon/2.png')}}" alt="" class="product-2-layout-view"></li>
                                             <li><img src="{{asset('front-assets/images/icon/3.png')}}" alt="" class="product-3-layout-view"></li>
                                             <li><img src="{{asset('front-assets/images/icon/4.png')}}" alt="" class="product-4-layout-view"></li>
                                             <li><img src="{{asset('front-assets/images/icon/6.png')}}" alt="" class="product-6-layout-view"></li>
                                          </ul>
                                       </div>
                                       <div class="product-page-per-view">
                                          <?php $pnum = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 8; ?>
                                          <select class="customerPaginate">
                                          <option value="8" @if($pnum==8) selected @endif>Show 8
                                          </option>
                                          <option value="12" @if($pnum==12) selected @endif>Show 12 </option>
                                          <option value="24" @if($pnum==24) selected @endif>Show 24
                                          </option>
                                          <option value="48" @if($pnum==48) selected @endif>Show 48
                                          </option>
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="displayProducts">
                              <div class="product-wrapper-grid">
                                 <div class="row margin-res">
                                    @if($listData->isNotEmpty())
                                    @foreach($listData as $key => $data)
                                    <?php $imagePath = $imagePath2 = '';
                                       $mediaCount = count($data->media);
                                       for ($i = 0; $i < $mediaCount && $i < 2; $i++) {
                                           if ($i == 0) {
                                               $imagePath = $data->media[$i]->image->path['proxy_url'] . '300/300' . $data->media[$i]->image->path['image_path'];
                                           }
                                           $imagePath2 = $data->media[$i]->image->path['proxy_url'] . '300/300' . $data->media[$i]->image->path['image_path'];
                                       } ?>
                                    <div class="col-xl-4 col-6 col-grid-box mt-3">
                                       <a href="{{route('productDetail', $data->url_slug)}}" class="product-box scale-effect d-block mt-0">
                                          <div class="product-image p-0">
                                             <img class="img-fluid blur-up lazyload" src="{{$imagePath}}" alt="">
                                          </div>
                                          <div class="media-body align-self-center">
                                             <div class="inner_spacing w-100">
                                                <h3 class="d-flex align-items-center justify-content-between">
                                                   <label class="mb-0">{{ $data->translation_title }}</label>
                                                   @if($client_preference_detail)
                                                   @if($client_preference_detail->rating_check == 1)  
                                                   @if($data->averageRating > 0)
                                                   <span class="rating">{{ number_format($data->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                                   @endif
                                                   @endif
                                                   @endif
                                                </h3>
                                                <p>{{$data->description}}</p>
                                                @if($data['inquiry_only'] == 0)
                                                <h4 class="mt-1">{{Session::get('currencySymbol').(number_format($data->variant_price * $data->variant_multiplier,2))}}</h4>
                                                @endif
                                             </div>
                                          </div>
                                       </a>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="col-xl-12 col-12 mt-4">
                                       <h5 class="text-center">{{__('No Product Found')}}</h5>
                                    </div>
                                    @endif
                                 </div>
                              </div>
                              <div class="pagination pagination-rounded justify-content-end mb-0">
                                 {{ $listData->links() }}
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-12">
               <div class="product-banner-img">
                  <img src="https://industrymeetings.iese.edu/wp-content/uploads/alimentacion_home-1-1500x430.jpg" alt="">
               </div>
               <div class="product-bottom-bar">
                  <div class="row">
                     <div class="col-sm-8 col-lg-5 order-0">
                        <div class="card-box vendor-details-left px-2 py-3">
                           <h3>Dominic Pizza</h3>
                           <ul class="vendor-info">
                              <li class="d-block food-items">
                              <i class="icon-ic_eat"></i>
                                 <a href="#">Pizza</a>, <a href="#">Fast Food</a>, <a href="#">Beverages</a>
                              </li>
                              <li class="d-block vendor-location">
                                 <a href="#"><i class="icon-location"></i> Sector 19, Chandigarh, Chandigarh</a>
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
                                 <div class="rating-text-box ml-sm-auto">
                                    <span>4.1</span>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                 </div>
                                 <div class="review-text">
                                    <div class="reviw-number">409</div>
                                    <div class="reviews-text">Delivery Reviews</div>
                                 </div>
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
                                    <li><a href="#one">Best in Pizza (10)</a></li>
                                    <li><a href="#two">Recommended (35)</a></li>
                                    <li><a href="#three">Match Day Combos (20)</a></li>
                                    <li><a href="#four">Pizza Mania (16)</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-md-8 col-lg-6 border-left">
                            <section class="scrolling_section mb-4" id="one">
                                <h2 class="category-head mt-0 mb-3">Best in Pizza (10)</h2>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                        <div class="class_img">
                                            <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                        <div class="row price_head pl-md-3 pl-2">
                                            <div class="col-sm-9 pl-2">
                                                <h5 class="mt-0">Veg Supreme Pizza </h5>
                                                <div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                                <p class="mb-1">₹209</p>
                                                <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                <a href="#" class="add-cart-btn">Add</a>
                                            </div>
                                        </div>
                                    </div>
                                </div><div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                        <div class="class_img">
                                            <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                        <div class="row price_head pl-md-3 pl-2">
                                            <div class="col-sm-9 pl-2">
                                                <h5 class="mt-0">Veg Supreme Pizza </h5>
                                                <div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                                <p class="mb-1">₹209</p>
                                                <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                <a href="#" class="add-cart-btn">Add</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                        <div class="class_img">
                                            <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                        <div class="row price_head pl-md-3 pl-2">
                                            <div class="col-sm-9 pl-2">
                                                <h5 class="mt-0">Veg Supreme Pizza </h5>
                                                <div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                                <p class="mb-1">₹209</p>
                                                <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                <a href="#" class="add-cart-btn">Add</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                        <div class="class_img">
                                            <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                        <div class="row price_head pl-md-3 pl-2">
                                            <div class="col-sm-9 pl-2">
                                                <h5 class="mt-0">Veg Supreme Pizza </h5>
                                                <div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                                <p class="mb-1">₹209</p>
                                                <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                <a href="#" class="add-cart-btn">Add</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                        <div class="class_img">
                                            <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                        <div class="row price_head pl-md-3 pl-2">
                                            <div class="col-sm-9 pl-2">
                                                <h5 class="mt-0">Veg Supreme Pizza </h5>
                                                <div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                                <p class="mb-1">₹209</p>
                                                <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                <a href="#" class="add-cart-btn">Add</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                        <div class="class_img">
                                            <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                        <div class="row price_head pl-md-3 pl-2">
                                            <div class="col-sm-9 pl-2">
                                                <h5 class="mt-0">Veg Supreme Pizza </h5>
                                                <div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                                <p class="mb-1">₹209</p>
                                                <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                <a href="#" class="add-cart-btn">Add</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section class="scrolling_section" id="two">
                                <h2 class="category-head mt-0 mb-3">Recommended (35)</h2>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </section>
                            <section class="scrolling_section" id="three">
                                <h2 class="category-head mt-0 mb-3">Match Day Combos (20)</h2>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </section>
                            <section class="scrolling_section" id="four">
                                <h2 class="category-head mt-0 mb-3">Pizza Mania (16)</h2>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="row classes_wrapper no-gutters mb-2">
                                    <div class="col-md-2 col-sm-3 mb-3">
                                    <div class="class_img">
                                        <img src="https://easybook.co/easybook_html/images/class-1.jpg" alt="">
                                    </div>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                    <div class="row price_head pl-md-3 pl-2">
                                        <div class="col-sm-9 pl-2">
                                            <h5 class="mt-0 mb-1">Veg Supreme Pizza </h5>
                                             <p class="mb-1">₹209</p><div class="rating-text-box">
                                                    <span>4.1</span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            <label class="member_no d-block mb-0">Onion capsicum, tomato, mushroom, jalapenos sweet corn and extra cheese.</label>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <a href="#" class="add-cart-btn">Add</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="col-4 col-lg-3 d-md-inline-block d-none">
                            <div class="card-box p-0 cart-main-box">
                                <div class="p-2 d-flex align-items-center justify-content-between">
                                    <h4 class="right-card-title">Basket</h4>
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </div>
                                <div class="p-2 border-top">
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
                                </div>
                                <a class="checkout-btn text-center d-block" href="#">Checkout</a>
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
@endsection
@section('script')
<script src="{{asset('front-assets/js/rangeSlider.min.js')}}"></script>
<script src="{{asset('front-assets/js/my-sliders.js')}}"></script>
<script>
   jQuery(window).scroll(function() {    
   var scroll = jQuery(window).scrollTop();
   
   if(scroll >= 720) {
   jQuery(".categories-product-list").addClass("fixed-bar");
   } else {
   jQuery(".categories-product-list").removeClass("fixed-bar");
   }
   }); //
</script>
<script>
   $('.js-range-slider').ionRangeSlider({
       type: 'double',
       grid: false,
       min: "{{$range_products->last() ? $range_products->last()->price * (!empty(Session::get('currencyMultiplier'))?Session::get('currencyMultiplier'):1) : 0}}",
       max: "{{$range_products->first() ? $range_products->first()->price * (!empty(Session::get('currencyMultiplier'))?Session::get('currencyMultiplier'):1) : 1000}}",
       from: "{{$range_products->last() ? $range_products->last()->price * (!empty(Session::get('currencyMultiplier'))?Session::get('currencyMultiplier'):1) : 0}}",
       to: "{{$range_products->first() ? $range_products->first()->price * (!empty(Session::get('currencyMultiplier'))?Session::get('currencyMultiplier'):1) : 1000}}",
       prefix: ""
   });
   
   var ajaxCall = 'ToCancelPrevReq';
   $('.js-range-slider').change(function() {
       filterProducts();
   });
   
   $('.productFilter').click(function() {
       filterProducts();
   });
   
   function filterProducts() {
       var brands = [];
       var variants = [];
       var options = [];
       $('.productFilter').each(function() {
           var that = this;
           if (this.checked == true) {
               var forCheck = $(that).attr('used');
               if (forCheck == 'brands') {
                   brands.push($(that).attr('fid'));
               } else {
                   variants.push($(that).attr('fid'));
                   options.push($(that).attr('optid'));
               }
           }
       });
       var range = $('.rangeSliderPrice').val();
   
       ajaxCall = $.ajax({
           type: "post",
           dataType: "json",
           url: "{{ route('vendorProductFilters', $vendor->id) }}",
           data: {
               "_token": "{{ csrf_token() }}",
               "brands": brands,
               "variants": variants,
               "options": options,
               "range": range
           },
           beforeSend: function() {
               if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                   ajaxCall.abort();
               }
           },
           success: function(response) {
               $('.displayProducts').html(response.html);
           },
           error: function(data) {
               //location.reload();
           },
       });
   }
</script>
@endsection