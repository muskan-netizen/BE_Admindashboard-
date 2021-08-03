@extends('layouts.store', ['title' => (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : ''])

@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

@endsection

@section('content')
<div class="added-notification">
    <img src="../assets/images/fashion/pro/1.jpg" class="img-fluid" alt="">
    <h3>added to cart</h3>
</div>
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
</style>
<section class="section-b-space">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="filter-main-btn mb-2">
                                    <span class="filter-btn">
                                        <i class="fa fa-filter" aria-hidden="true"></i> filter
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="product-slick">
                                    @if(!empty($product->media))
                                    @foreach($product->media as $k => $img)
                                    <div class="image_mask">
                                        <img class="img-fluid blur-up lazyload image_zoom_cls-{{$k}}" src="{{$img->image->path['proxy_url'].'600/800'.$img->image->path['image_path']}}">
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-12 p-0">
                                        <div class="slider-nav">
                                            @if(!empty($product->media))
                                            @foreach($product->media as $k => $img)
                                            <div>
                                                <img class="img-fluid blur-up lazyload" src="{{$img->image->path['proxy_url'].'300/300'.$img->image->path['image_path']}}">
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 rtl-text">
                                <div class="product-right">
                                    <h2 class="mb-0">
                                        {{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : ''}}
                                    </h2>
                                    @if($client_preference_detail)
                                        @if($client_preference_detail->rating_check == 1)  
                                        <div class="custom_rating">
                                            @if($product->averageRating > 0)
                                                @for($i = 1; $i < 6; $i++)
                                                    <i class="fa fa-star{{ ($i <= $product->averageRating) ? ' filled ' : '' }}"></i>
                                                @endfor
                                            @endif
                                        </div>
                                        @endif
                                    @endif
                                    <div class="description_txt my-3">
                                        <p>{{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->meta_description : ''}}</p>
                                    </div>
                                    <input type="hidden" name="variant_id" id="prod_variant_id" value="{{$product->variant[0]->id}}">
                                    @if($product->inquiry_only == 0)
                                    <h3 id="productPriceValue" class="mb-md-3">
                                        <b class="mr-1">{{Session::get('currencySymbol').(number_format($product->variant[0]->price * $product->variant[0]->multiplier,2))}}</b>
                                        @if($product->variant[0]->compare_at_price > 0 )
                                        <span class="org_price">{{Session::get('currencySymbol').(number_format($product->variant[0]->compare_at_price * $product->variant[0]->multiplier,2))}}</span>
                                        @endif
                                    </h3>
                                    @endif

                                    @if(!empty($product->variantSet))
                                    @php
                                    $selectedVariant = isset($product->variant[0]) ? $product->variant[0]->id : 0;
                                    @endphp
                                    @foreach($product->variantSet as $key => $variant)
                                    @if($variant->type == 1 || $variant->type == 2)
                                    <div class="size-box">
                                        <ul class="productVariants">
                                            <li class="firstChild">{{$variant->title}}</li>
                                            <li class="otherSize">
                                                @foreach($variant->option2 as $k => $optn)
                                                <?php $var_id = $variant->variant_type_id;
                                                $opt_id = $optn->variant_option_id;
                                                $checked = ($selectedVariant == $optn->product_variant_id) ? 'checked' : '';
                                                ?>
                                                <label class="radio d-inline-block txt-14 mr-2">{{$optn->title}}
                                                    <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" vid="{{$var_id}}" optid="{{$opt_id}}" value="{{$opt_id}}" type="radio" {{$checked}} class="changeVariant dataVar{{$var_id}}">
                                                    <span class="checkround"></span>
                                                </label>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>
                                    @else
                                    @endif
                                    @endforeach
                                    @endif

                                    @if($product->inquiry_only == 0)
                                    <div class="product-description border-product">
                                        <h6 class="product-title mt-0">{{__('Quantity')}}:
                                            @if(!$product->variant[0]->quantity > 0)
                                            <span id="outofstock" style="color: red;">{{__('Out of Stock')}}</span>
                                            @endif
                                        </h6>
                                        @if($product->variant[0]->quantity > 0)
                                        <div class="qty-box">
                                            <div class="input-group">
                                                <span class="input-group-prepend">
                                                    <button type="button" class="btn quantity-left-minus" data-type="minus" data-field=""><i class="ti-angle-left"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="quantity" id="quantity" class="form-control input-number quantity_count" value="1">
                                                <span class="input-group-prepend quant-plus">
                                                    <button type="button" class="btn quantity-right-plus " data-type="plus" data-field="">
                                                        <i class="ti-angle-right"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    @if(!empty($product->addOn) && $product->addOn->count() > 0)
                                    <div class="border-product">
                                        <h6 class="product-title">Addon List</h6>
                                        <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                                            <tbody>
                                                @foreach($product->addOn as $row => $addon)
                                                <tr>
                                                    <td>
                                                        <h4 addon_id="{{$addon->addon_id}}" class="header-title productAddonSet">{{$addon->title}} <small>(You can choose max {{$addon->max_select}} options)</small></h4>
                                                    </td>
                                                </tr>
                                                <tr class="productAddonSetOptions" data-min="{{$addon->min_select}}" data-max="{{$addon->max_select}}">
                                                    <td>
                                                        @foreach($addon->setoptions as $k => $option)
                                                        <div class="checkbox checkbox-success form-check-inline">
                                                            <input type="checkbox" id="inlineCheckbox_{{$row.'_'.$k}}" class="productAddonOption" name="addonData[$row][]" addonId="{{$addon->addon_id}}" addonOptId="{{$option->id}}">
                                                            <label class="pl-2" for="inlineCheckbox_{{$row.'_'.$k}}">
                                                                {{$option->title .' ($'.$option->price.')' }}</label>
                                                        </div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                    <div class="product-buttons">
                                        @if($product->variant[0]->quantity > 0)
                                        @if($is_inwishlist_btn)
                                        <button type="button" class="btn btn-solid addWishList" proSku="{{$product->sku}}">
                                            {{ (isset($product->inwishlist) && (!empty($product->inwishlist))) ? __('Remove From Wishlist') : __('Add To Wishlist') }}
                                        </button>
                                        @endif
                                        @if($product->inquiry_only == 0)
                                            <a href="#" data-toggle="modal" data-target="#addtocart" class="btn btn-solid addToCart">{{__('Add To Cart')}}</a>
                                        @else
                                            <a href="#" data-toggle="modal" data-target="#inquiry_form" class="btn btn-solid inquiry_mode">{{ __('Inquire Now')}}</a>
                                        @endif
                                        @endif
                                    </div>
                                    <div class="border-product">
                                        <h6 class="product-title">{{__('Product Details')}}</h6>
                                        <p>{!!(!empty($product->translation) && isset($product->translation[0])) ?
                                            $product->translation[0]->body_html : ''!!}</p>
                                    </div>
                                    <div class="border-product">
                                        <h6 class="product-title">{{__('Share It')}}</h6>
                                        <div class="product-icon w-100">
                                            <ul class="product-social">
                                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="tab-product m-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-toggle="tab" href="#top-home" role="tab" aria-selected="true"><i class="icofont icofont-ui-home"></i>{{__('Description')}}</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <!-- <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-toggle="tab"
                                            href="#top-profile" role="tab" aria-selected="false"><i
                                                class="icofont icofont-man-in-glasses"></i>Details</a>
                                        <div class="material-border"></div>
                                    </li> -->
                                    @if($client_preference_detail)
                                    @if($client_preference_detail->rating_check == 1)
                                    <li class="nav-item"><a class="nav-link" id="review-top-tab" data-toggle="tab" href="#top-review" role="tab" aria-selected="false"><i class="icofont icofont-contacts"></i>Ratings & Reviews</a>
                                        <div class="material-border"></div>
                                    </li>
                                    @endif
                                    @endif
                                </ul>
                                <div class="tab-content nav-material" id="top-tabContent">
                                    <div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                                        <p>{!! (!empty($product->translation) && isset($product->translation[0])) ?
                                            $product->translation[0]->body_html : ''!!}</p>
                                    </div>
                                    <div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                                        <p>{!! (!empty($product->translation) && isset($product->translation[0])) ?
                                            $product->translation[0]->body_html : ''!!}</p>
                                    </div>
                                    <div class="tab-pane fade" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                                        @foreach ($rating_details as $rating)
                                        <div v-for="item in list" class="w-100 d-flex justify-content-between mb-3">
                                            <div class="review-box">

                                                <div class="review-author mb-1">
                                                    <p><strong>{{$rating->user->name??'NA'}}</strong> - <i class="fa fa-star{{ $rating->rating >= 1 ? '' : '-o' }}" aria-hidden="true"></i>
                                                        <i class="fa fa-star{{ $rating->rating >= 2 ? '' : '-o' }}" aria-hidden="true"></i>
                                                        <i class="fa fa-star{{ $rating->rating >= 3 ? '' : '-o' }}" aria-hidden="true"></i>
                                                        <i class="fa fa-star{{ $rating->rating >= 4 ? '' : '-o' }}" aria-hidden="true"></i>
                                                        <i class="fa fa-star{{ $rating->rating >= 5 ? '' : '-o' }}" aria-hidden="true"></i>
                                                    </p>
                                                </div>
                                                <div class="review-comment">
                                                    <p>{{$rating->review??''}}</p>
                                                </div>
                                                <div class="row review-wrapper">
                                                    @if(isset($rating->reviewFiles))
                                                    @foreach ($rating->reviewFiles as $files)
                                                    <a target="_blank" href="{{$files->file['proxy_url'].'900/900'.$files->file['image_path']}}" class="col review-photo mt-2 lightBoxGallery" data-gallery="">
                                                        <img src="{{$files->file['proxy_url'].'300/300'.$files->file['image_path']}}">
                                                    </a>
                                                    @endforeach
                                                    @endif
                                                </div>
                                                <div class="review-date mt-2">
                                                    <time> {{ $rating->time_zone_created_at->diffForHumans();}} </time>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>
@if($product->related_products->count() > 0)
<section class="">
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <h2>Related products</h2>
            </div>
        </div>
    </div>
</section>
@endif
<section class="section-b-space ratio_asos">
    <div class="container">
        <div class="row"></div>
        <div class="row search-product">
            @forelse($product->related_products as $related_product)
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="product-box">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="{{route('productDetail')}}/{{$related_product->url_slug}}">
                                <img src="{{$related_product->media ? $related_product->media->first()->image->path['proxy_url'].'600/800'.$related_product->media->first()->image->path['image_path'] : ''}}" class="img-fluid blur-up lazyload bg-img" alt="">
                            </a>
                        </div>
                    </div>
                    <a href="{{route('productDetail')}}/{{$related_product->url_slug}}">
                        <div class="product-detail">
                            <div class="rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <h6>{{ (!empty($related_product->translation) && $related_product->translation->first())? $related_product->translation->first()->title : ''}}</h6>
                            <h4>{{Session::get('currencySymbol').($related_product->variant->first()->price * $related_product->variant->first()->multiplier)}}</h4>
                            <ul class="color-variant">
                                <li class="bg-light0"></li>
                                <li class="bg-light1"></li>
                                <li class="bg-light2"></li>
                            </ul>
                        </div>
                    </a>
                </div>
            </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<div class="modal fade product-rating" id="product_rating" tabindex="-1" aria-labelledby="product_ratingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="review-rating-form-modal"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="inquiry_form" tabindex="-1" aria-labelledby="inquiry_formLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="inquiry_formLabel">{{__('Inquiry')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php
                $user = Auth::user();
                @endphp
                <form id="inquiry-form">
                    <div class="row">
                        <input type="hidden" name="vendor_id" value="{{$product->vendor_id}}" />
                        <input type="hidden" name="product_id" value="{{$product->id}}" />
                        <div class="col-md-6 form-group">
                            <label>{{__('Name')}}</label>
                            <input class="form-control" name="name" id="name" value="{{$user ? $user->name : '' }}" type="text" placeholder="{{__('Name')}}">
                            <span class="text-danger error-text nameError"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{__('Email')}}</label>
                            <input class="form-control" name="email" id="email" value="{{$user ? $user->email : '' }}" type="text" placeholder="{{__('Email')}}">
                            <span class="text-danger error-text emailError"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{__('Phone Number')}}</label>
                            <input class="form-control" name="number" id="number" value="{{$user ? $user->phone_number : '' }}" type="text" placeholder="{{__('Phone Number')}}">
                            <span class="text-danger error-text numberError"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{__('Company Name')}}</label>
                            <input class="form-control" name="company_name" id="company_name" type="text" placeholder="{{__('Company Name')}}">
                        </div>
                        <div class="col-12 form-group">
                            <label>{{__('Message')}}</label>
                            <textarea class="form-control" name="message" id="message" cols="30" rows="8" placeholder="{{__('Message')}}"></textarea>
                            <span class="text-danger error-texprapt messageError"></span>
                        </div>
                        <div class="col-12 form-group checkbox-input">
                            <input type="checkbox" id="agree" name="agree" required>
                            <label for="agree">{{__('I accept the')}} <a href="{{url('extra-page/terms-conditions')}}" target="_blank">{{__('Terms And Conditions')}}</a> {{__('and have read the')}} <a href="{{url('extra-page/privacy-policy')}}" target="_blank"> {{__('Privacy Policy')}}</a></label>
                            <span class="d-block text-danger error-text agreeError"></span>
                        </div>
                        <div class="col-12 mt-2">
                            <button type="button" class="btn btn-solid w-100 submitInquiryForm">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).on('click', '.submitInquiryForm', function(e) {
        e.preventDefault();
        var formData = new FormData(document.getElementById("inquiry-form"));
        formData.append("variant_id", $('#prod_variant_id').val());
        var submit_url = "{{ route('inquiryMode.store') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: submit_url,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#inquiry_form').modal('hide');
            },
            error: function(response) {
                console.log(response);
                $('.messageError').html(response.responseJSON.errors.message[0]);
                $('.agreeError').html(response.responseJSON.errors.agree[0]);
                $('.numberError').html(response.responseJSON.errors.number[0]);
                $('.emailError').html(response.responseJSON.errors.email[0]);
                $('.nameError').html(response.responseJSON.errors.name[0]);
            },
            complete: function() {}
        });
    });


    var valueHover = 0;

    function calcSliderPos(e, maxV) {
        return (e.offsetX / e.target.clientWidth) * parseInt(maxV, 10);
    }

    $(".starrate").on("click", function() {
        $(this).data('val', valueHover);
        $(this).addClass('saved')
    });

    $(".starrate").on("mouseout", function() {
        upStars($(this).data('val'));
    });


    $(".starrate span.ctrl").on("mousemove", function(e) {
        var maxV = parseInt($(this).parent("div").data('max'))
        valueHover = Math.ceil(calcSliderPos(e, maxV) * 2) / 2;
        upStars(valueHover);
    });


    function upStars(val) {
        var val = parseFloat(val);
        $("#test").html(val.toFixed(1));

        var full = Number.isInteger(val);
        val = parseInt(val);
        var stars = $("#starrate i");

        stars.slice(0, val).attr("class", "fa fa-star");
        if (!full) {
            stars.slice(val, val + 1).attr("class", "fa fa-star-half-o");
            val++
        }
        stars.slice(val, 5).attr("class", "fa fa-star-o");
    }


    $(document).ready(function() {
        $(".starrate span.ctrl").width($(".starrate span.cont").width());
        $(".starrate span.ctrl").height($(".starrate span.cont").height());
    });
</script>

<script type="text/javascript">
    var ajaxCall = 'ToCancelPrevReq';
    var vendor_id = "{{ $product->vendor_id }}";
    var product_id = "{{ $product->id }}";
    var add_to_cart_url = "{{ route('addToCart') }}";
    $('.changeVariant').click(function() {
        var variants = [];
        var options = [];
        $('.changeVariant').each(function() {
            var that = this;
            if (this.checked == true) {
                variants.push($(that).attr('vid'));
                options.push($(that).attr('optid'));
            }
        });
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('productVariant', $product->sku) }}",
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
                var res = response.result;
                console.log(res.id);
                $('#prod_variant_id').val(res.id);
                $('#productPriceValue').html(res.productPrice);
                $('#instock').html("In Stock (" + res.quantity + ")");
            },
            error: function(data) {

            },
        });
    });
</script>
<script>
    var addonids = [];
    var addonoptids = [];
    $(function() {
        $(".productAddonOption").click(function(e) {
            // e.preventDefault();
            var addon_elem = $(this).closest('tr');
            var addon_minlimit = addon_elem.data('min');
            var addon_maxlimit = addon_elem.data('max');
            if(addon_elem.find(".productAddonOption:checked").length > addon_maxlimit) {
                this.checked = false;
            }else{
                var addonId = $(this).attr("addonId");
                var addonOptId = $(this).attr("addonOptId");
                if ($(this).is(":checked")) {
                    addonids.push(addonId);
                    addonoptids.push(addonOptId);
                } else {
                    addonids.splice(addonids.indexOf(addonId), 1);
                    addonoptids.splice(addonoptids.indexOf(addonOptId), 1);
                }
            }
        });
    });
</script>

<!-----  rating product if delivered -->

<script type="text/javascript">
    $(document).ready(function(e) {
        $('.rating-star-click').click(function() {
            $('.rating_files').show();
            $('.form-row').show();
            $('#product_rating').modal('show');
        });
        $('body').on('click', '.add_edit_review', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            $.get('/rating/get-product-rating?id=' + id, function(markup) {
                $('#product_rating').modal('show');
                $('#review-rating-form-modal').html(markup);
            });
        });
    });
</script>

@endsection