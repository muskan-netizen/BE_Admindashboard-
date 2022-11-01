<div class="col-md-3">
    <div class="deals-product product-card-box position-relative text-center al_custom_vendors_sec"  >
        <a class="suppliers-box d-block" href="{{route('vendorDetail')}}/{{ $vendor->slug }}">
            <div class="suppliers-img-outer position-relative ">
                <img  class="fluid-img mx-auto blur-up lazyload" data-src="{{ $product['image_url']  }}" alt="" title="">
            </div>
            <div class="supplier-rating">
                <h4>{{  $product["title"] }}</h4>
                
                <h5>{{ $product['discount_percentage'] ?? 0}}% OFF</h5>
                <a href="#">SHOP NOW</a>
                <!-- {{--<p title="<%=vendor.categoriesList %>" class="vendor-cate mb-1 ellips d-none">
                    <%=vendor.categoriesList %>
                </p>--}}
                    {{-- <% if(vendor.timeofLineOfSightDistance !=undefined){%>
                        <div class="pref-timing"> <span><%=vendor.timeofLineOfSightDistance %></span> </div>
                    <%}%> --}} -->
            </div>
            @if($client_preference_detail && $client_preference_detail->rating_check==1)
            @if($vendor->vendorRating >0) <span class="rating-number">{{ $vendor->vendorRating }} </span>
            @endif @endif 
        </a>
    </div>
</div>