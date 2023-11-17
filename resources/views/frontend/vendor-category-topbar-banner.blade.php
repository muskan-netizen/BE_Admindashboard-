 
@if (@$vendor->banner)
    $url = $vendor->banner['image_fit'] . '1920/1080' . @$vendor->banner['image_path'];
    if(!imageExists($url))
    {
      $url = loadDefaultImage();   
     }    
    <div class="common-banner my-banner"><img class="blur-up lazyload" alt="" data-src="{{ $url ?? ''}}" >


</div>
@elseif(@$brand)
$url = $brand->image_banner['image_fit'] . '1920/1080' . @$brand->image_banner['image_path'];
if(!imageExists($url))
{
  $url = loadDefaultImage();   

}
    <div class="common-banner my-banner"><img class="blur-up lazyload" alt="" data-src="{{$brand->image_banner['image_fit'] . '1920/1080' . $brand->image_banner['image_path']}}" ></div>
@elseif(@$celebrity)
$url = $celebrity->avatar['image_fit'] . '1920/1080' . @$celebrity->avatar['image_path'];
if(!imageExists($url))
{
  $url = loadDefaultImage();   

}
    <div class="common-banner my-banner"><img class="blur-up lazyload" data-src="{{$celebrity->avatar['image_fit'] . '1920/1080' . $celebrity->avatar['image_path']}}" alt=""></div>
@elseif(@$category)

@php
$url = $category->image['image_fit'] . '1920/1080' . @$category->image['image_path'];
if(!imageExists($url))
{
  $url = loadDefaultImage();   

}

@endphp
    <div class="common-banner my-banner"><img alt="" class="blur-up lazyload" data-src="{{ $url ?? ''}}"></div>
@else
    <div class="common-banner my-banner"><img alt="" class="blur-up lazyload" data-src="{{ $url ?? '' }}" ></div>
@endif