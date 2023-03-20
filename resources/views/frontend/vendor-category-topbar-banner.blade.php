 
@if (@$vendor->banner)
    <div class="common-banner my-banner"><img class="blur-up lazyload" alt="" data-src="{{ $vendor->banner['image_fit'] . '1920/1080' . $vendor->banner['image_path'] }}" >


</div>
@elseif(@$brand)
    <div class="common-banner my-banner"><img class="blur-up lazyload" alt="" data-src="{{$brand->image_banner['image_fit'] . '1920/1080' . $brand->image_banner['image_path']}}" ></div>
@elseif(@$celebrity)
    <div class="common-banner my-banner"><img class="blur-up lazyload" data-src="{{$celebrity->avatar['image_fit'] . '1920/1080' . $celebrity->avatar['image_path']}}" alt=""></div>
@elseif(@$category)
    <div class="common-banner my-banner"><img alt="" class="blur-up lazyload" data-src="{{@$category->image['image_fit'] . '1920/1080' . @$category->image['image_path']}}"></div>
@else
    <div class="common-banner my-banner"><img alt="" class="blur-up lazyload" data-src="{{@$category->image['image_fit'] . '1920/1080' . @$category->image['image_path']}}" ></div>
@endif