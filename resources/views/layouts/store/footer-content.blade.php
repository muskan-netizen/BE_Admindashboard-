@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
$urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
$pages = \App\Models\Page::with(['translations' => function($q) {$q->where('language_id', session()->get('customerLanguage'));}])->get();
@endphp
<footer class="footer-light">
    <section class="section-b-space light-layout py-4">
        <div class="container">
            <div class="row footer-theme partition-f">
                <div class="col-lg-2 col-md-6 d-flex align-items-center">
                    <div class="footer-logo mb-0">
                        <a href="{{ route('userHome') }}">
                            <img class="img-fluid blur-up lazyload" src="{{$urlImg}}">
                        </a>
                    </div>
                </div>
                @if(count($pages))
                    <div class="col-lg-3 col-md-6 pl-lg-5">
                        <div class="sub-title">
                            <div class="footer-title">
                                <h4>{{ __('Links') }}</h4>
                            </div>
                            <div class="footer-contant">
                                <ul>
                                    @foreach($pages as $page)
                                        <li><a href="{{route('extrapage',['slug' => $page->slug])}}">{{$page->translations->first() ? $page->translations->first()->title : $page->primary->title}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                @if(count($social_media_details))
                    <div class="col-lg-4 col-md-6 pl-lg-5">
                        <div class="sub-title">
                            <div class="footer-title">
                                <h4>{{ __('Connect') }}</h4>
                            </div>
                        </div>
                        <div class="footer-contant">
                            <div class="footer-social">
                                <ul>
                                    @foreach($social_media_details as $social_media_detail)
                                        <li class="d-block mb-2">
                                            <a href="{{http_check($social_media_detail->url)}}" target="_blank">
                                                <i class="fa fa-{{$social_media_detail->icon}}" aria-hidden="true"></i>
                                                <span>{{$social_media_detail->title ? $social_media_detail->title : "Facebook"}}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>  
                        </div>   
                    </div>
                @endif
                @if($client_preference_detail->show_contact_us == 1)
                <div class="col-lg-3 col-md-6 mb-md-0 mb-3">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>{{ __('Contact Us') }}</h4>
                        </div>
                        
                        <div class="footer-contant">
                            <ul class="contact-list">
                                <li><i class="fa fa-map-marker"></i>Multikart Demo Store, Demo store India 345-659
                                </li>
                                <li><i class="fa fa-phone"></i>123-456-7898</li>
                                <li><i class="fa fa-envelope-o"></i><a href="#">Support@Fiot.com</a></li>
                                <li><i class="fa fa-fax"></i>123456</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif                  
            </div>
        </div>
    </section>
    <div class="sub-footer">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="footer-end">
                            <p><i class="fa fa-copyright" aria-hidden="true"></i> 2020-21</p>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="payment-card-bottom">
                            <ul>
                                <li>
                                    <a href="#"><img src="{{asset('assets/images/visa.png')}}" alt=""></a>
                                </li>
                                <li>
                                    <a href="#"><img src="{{asset('assets/images/mastercard.png')}}" alt=""></a>
                                </li>
                                <li>
                                    <a href="#"><img src="{{asset('assets/images/paypal.png')}}" alt=""></a>
                                </li>
                                <li>
                                    <a href="#"><img src="{{asset('assets/images/american-express.png')}}" alt=""></a>
                                </li>
                                <li>
                                    <a href="#"><img src="{{asset('assets/images/discover.png')}}" alt=""></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</footer>