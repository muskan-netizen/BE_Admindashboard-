@if(!isset($ajax) && empty($ajax))
    @if(@getAdditionalPreference(['is_enable_compare_product'])['is_enable_compare_product'] && 
    (in_array($product->category->category_id,getVendorAdditionalPreference($product->vendor_id,'compare_categories'))))

        <div class="tab-pane show {{(count($rating_details)>0)?'':'active'}}" id="compare-product" role="tabpanel" aria-labelledby="compare-product-tab">
                <form id="compare-form">
                    <input type="hidden" value="{{$product->category->category_id}}" name="category_id" />
                    <input type="hidden" value="" name="comIds" id="comIds" />
                    <input type="hidden" value="{{$product->id}}" name="productId" id="productId" />

                        <div class="row p-2 mb-2">
                            <div class="col-md-6">
                                <select name="compareItems[]" class="form-control select2-multiple-search" id="compare-items" multiple="multiple">
                                    @foreach($suggested_category_products as $compare)
                                        <option value="{{$compare->id}}">{{$compare->title}}</option>
                                    @endforeach
                            </select>
                            </div>    
                            <div class="col-md-6">
                                <input type="button" class="compare-button btn btn-primary d-none" value="Compare"/>
                            </div>    
                        </div> 
                </form>
                <table class="table table-border" id="compare_table">
                        <thead>
                                <tr>
                                    {{-- <td>S.No</td> --}}
                                    <td>Image</td>
                                    <td>Name</td>
                                    <td>Rating</td>
                                    <td>Amount</td>
                                    <td>Description</td>
                                    <td>Seller Name</td>
                                </tr>
                        </thead>
                        <tbody id="htmlAppend">
                        @if(isset($product))
                            <tr>
                                @php

                                    if(isset($product->media->first()->image)){
                                        $image_fit = $product->media->first()->image->path['image_fit'];
                                        $image_path = $product->media->first()->image->path['image_path'];
                                        $product_image = $image_fit . '50/50' . $image_path;
                                    }
                                @endphp
                                {{-- <td>#1</td> --}}
                                <td><a target="_blank"
                                    href="{{ route('productDetail', [$product->vendor->slug, $product->url_slug]) }}"><img src="{{$product_image??'N/A'}}" style="width:50px" /></a></td>
                                
                                <td>{{(($product->translation->first())?$product->translation->first()->title:$product->title)}}</td>
                                <td><span>{{ number_format($product->averageRating, 1, '.', '') }}
                                </span>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </td>
                                <td>{{decimal_format($product->variant->first()->price * $product->variant->first()->multiplier)}}</td>
                                <td> {!!
                                    $product->translation->first()->body_html?? 'N/A'
                                    !!}</td>
                                <td><a href="{{route('vendorDetail',[$product->vendor->slug])}}"> {{$product->vendor->name}}</a></td>

                            </tr>
                            @else
                                <p>{{__('No Compare product found')}}</p>
                            @endif
                        </tbody>
                </table>
        </div>
    @endif

@else
        @if(isset($compareProducts) && count($compareProducts)>0)
            @foreach($compareProducts as $key => $cproduct)
            <tr>
                @php
                    if(isset($cproduct->media->first()->image)){
                        $image_fit = $cproduct->media->first()->image->path['image_fit'];
                        $image_path = $cproduct->media->first()->image->path['image_path'];
                        $product_image = $image_fit . '50/50' . $image_path;
                    }
                @endphp
                {{-- <td>#{{$key+1}}</td> --}}
                <td><a target="_blank"
                    href="{{ route('productDetail', [$cproduct->vendor->slug, $cproduct->url_slug]) }}"><img src="{{$product_image??'N/A'}}" style="width:50px" /></a></td>
                    <td>{{(($cproduct->translation->first())?$cproduct->translation->first()->title:$cproduct->title)}}</td>
                    <td><span>{{ number_format($cproduct->averageRating, 1, '.', '') }}
                    </span>
                        <i class="fa fa-star" aria-hidden="true"></i>
                    </td>
                    <td>{{decimal_format($cproduct->variant->first()->price * $cproduct->variant->first()->multiplier)}}</td>

                    <td> {!!
                    $cproduct->translation->first()->body_html?? 'N/A'
                    !!}</td>
                    <td><a href="{{route('vendorDetail',[$cproduct->vendor->slug])}}"> {{$cproduct->vendor->name}}</a></td>

            </tr>
            @endforeach
        @endif
@endif
@section('script-bottom-js')
<script>

    $('.select2-multiple-search').on("select2:close", function (e) { 
        $('.compare-button').trigger("click"); 
    });

    $('.select2-multiple-search').select2({
            placeholder: 'Select Compare Products',
            // allowClear: true
    });
    
    $(document).on('click', '.compare-button', function(e) {
        e.preventDefault();
        var formData = new FormData(document.getElementById("compare-form"));
        var submit_url = "{{ route('compare.product') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: submit_url,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#htmlAppend').html(response.html);
            }
        });
       
    });
</script>
@endsection