
    <div class="col-md-2">
        <div class="category-block">
            <a class="brand-box d-block black-box" href="{{-- $category->redirect_url --}}">
                <div class="bg-outter">
                    <img class="" src="{{asset('images/template-8/bg-shape.png')}}" alt="" title=""> 
                </div>
                <div class="brand-ing">
                    <img class="blur-up lazyload" data-src="{{$category->icon['proxy_url'].'200/200'.$category->icon['image_path']}}" alt="" title="">
                </div>
                <h6>{{-- $category->title --}}</h6>
            </a>
        </div>
    </div>