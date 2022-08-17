{{-- <div class="product-card-box position-relative">
    <a class="suppliers-box d-block" href="{{route('vendorDetail')}}/{{$product["slug"]}}">
        <div class="suppliers-img-outer position-relative">
            @if ($product["is_vendor_closed" == 1])
            <img class="fluid-img mx-auto blur-up lazyload grayscale-image" data-src="<%=vendor.logo.image_fit %>200/200<%=vendor.logo['image_path'] %>" alt="" title="">
            @else
            @endif
            <% if(vendor.is_vendor_closed==1){%> <img class="fluid-img mx-auto blur-up lazyload grayscale-image" data-src="<%=vendor.logo.image_fit %>200/200<%=vendor.logo['image_path'] %>" alt="" title="">
                <%}else{%> <img class="fluid-img mx-auto blur-up lazyload" data-src="<%=vendor.logo.image_fit %>200/200<%=vendor.logo['image_path'] %>" alt="" title="">
                    <%}%>
                        <% if(vendor.timeofLineOfSightDistance !=undefined){%>
                            <div class="pref-timing"> <span><%=vendor.timeofLineOfSightDistance %></span> </div>
                            <%}%>
        </div>
        <div class="supplier-rating">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="mb-1 ellips"><%=vendor.name %></h6> @if($client_preference_detail) @if($client_preference_detail->rating_check==1)
                <% if(vendor.vendorRating > 0){%> <span class="rating-number"><%=vendor.vendorRating %></span>
                    <%}%> @endif @endif </div>
            <p title="<%=vendor.categoriesList %>" class="vendor-cate mb-1 ellips">
                <%=vendor.categoriesList %>
            </p>
        </div>
    </a>
</div> --}}

<div class="product-card-box al_box_third_template position-relative al">
    <div class="add-to-fav 12">
        <input id="fav_pro_one" type="checkbox">
        <label for="fav_pro_one"><i class="fa fa-heart-o fav-heart" aria-hidden="true"></i></label>
    </div>
    {{-- {{dd($product)}} --}}
    <a class="common-product-box text-center" href="{{route('vendorDetail')}}/{{$product["slug"]}}">
        <div class="img-outer-box position-relative"> <img class="blur-up lazyload" data-src="{{ $product["image_url"] }}" alt="" title="">
            <div class="pref-timing"> </div>
        </div>
        <div class="media-body align-self-center">
            <div class="inner_spacing px-0">
                <div class="product-description">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="card_title ellips">{{ $product["title"] }}</h6> 
                        @if($client_preference_detail && $client_preference_detail->rating_check==1) 
                        @if($product["averageRating"] >0)
                            <span class="rating-number">{{ $product["averageRating"] }}</span>
                        @endif 
                        @endif 
                    </div>
                    <p class="al_productText ellips">
                        {{ $product["vendor_name"] }}
                    </p>
                    <p class="border-bottom pb-1">
                        <span>{{__('In') . $product["category"]}} </span>
                    </p>
                    <div class="d-flex align-items-center justify-content-between al_clock"> 
                        <b>{{ $product["price   "] }}</b>
                        <!-- <p><i class="fa fa-clock-o"></i> 30-40 min</p>  -->
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>