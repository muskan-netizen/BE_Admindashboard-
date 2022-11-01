{{-- @dd($category) --}}
<div class="col-md-3">
    <div class="category-block">
        <a class="brand-box d-block black-box" href="{{route('categoryDetail', $category->slug)}}">
            <div class="brand-img"> 
                <img class="blur-up lazyload" data-src="{{$category->icon['proxy_url'].'200/200'.$category->icon['image_path']}}" alt="" title="">
            </div>
            <h6 class="text-center">{{ $category->name }}</h6> 
        </a>
    </div>
</div>