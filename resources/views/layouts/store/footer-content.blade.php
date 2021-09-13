@php
$clientData = \App\Models\Client::where('id', '>', 0)->first();
$urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
$pages = \App\Models\Page::with(['translations' => function($q) {$q->where('language_id', session()->get('customerLanguage'));}])->get();
@endphp
<footer class="footer-light">
    <section class="section-b-space light-layout py-4">
        <div class="container">
            <div class="row footer-theme partition-f">
                <div class="col-lg-2 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
                    <div class="footer-logo mb-0">
                        <a href="{{ route('userHome') }}">
                            <img class="img-fluid blur-up lazyload" src="{{$urlImg}}">
                        </a>
                    </div>
                </div>
                @if(count($pages))
                    <div class="col-lg-3 col-md-6 pl-lg-5">
                        <div class="sub-title">
                            <div class="footer-title mt-0">
                                <h4 class="mt-0">{{ __('Links') }}</h4>
                            </div>
                            <div class="footer-contant">
                                <ul>
                                    @foreach($pages as $page)
                                        <li>
                                            <a href="{{route('extrapage',['slug' => $page->slug])}}">{{$page->translations->first() ? $page->translations->first()->title : $page->primary->title}}</a>
                                        </li>
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
                            <div class="footer-contant">
                                <div class="footer-social">
                                    <ul>
                                        @foreach($social_media_details as $social_media_detail)
                                            <li class="d-block">
                                                <a href="{{http_check($social_media_detail->url)}}" target="_blank">
                                                    <i class="fa fa-{{$social_media_detail->icon}}" aria-hidden="true"></i>
                                                    <span>{{$social_media_detail->icon ? ucfirst($social_media_detail->icon) : "Facebook"}}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>  
                            </div>   
                        </div>   
                    </div>
                @endif
                @if($client_preference_detail->show_contact_us == 1)
                    <div class="col-lg-3 col-md-6 mb-md-0 mb-3">
                        <div class="sub-title">
                            <div class="footer-title mt-0">
                                <h4 class="mt-0">{{ __('Contact Us') }}</h4>
                            </div>
                            <div class="footer-contant">
                                <ul class="contact-list">
                                    <li><img src="{{asset('front-assets/images/ic_location.svg')}}" alt="">{{$clientData ? $clientData->company_address : 'Demo Store, 345-659'}}
                                    </li>
                                    <li><img src="{{asset('front-assets/images/ic_call.svg')}}" alt=""><a href="tel: {{$clientData ? $clientData->phone_number : '123-456-7898'}}">{{$clientData ? $clientData->phone_number : '123-456-7898'}}</a></li>
                                    <li><img src="{{asset('front-assets/images/ic_mail.svg')}}" alt=""><a href="mailto: {{$clientData ? $clientData->email : 'Support@Fiot.com'}}">{{$clientData ? $clientData->email : 'Support@Fiot.com'}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif  
                <div class="col-lg-3 col-md-6 mb-md-0 mb-3 text-right d-none">
                    <div class="store-btn">
                        <a href="#"><img src="{{asset('front-assets/images/app-store.png')}}" alt=""></a>
                        <a class="ml-2" href="#"><img src="{{asset('front-assets/images/google-play.png')}}" alt=""></a>
                    </div>
                    <ul class="social-links ml-md-auto mt-3">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                    </ul>
                </div>                
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
                @if($client_preference_detail->show_payment_icons == 1)
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="payment-card-bottom">
                        <ul>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/visa.png')}}"></a>
                            </li>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/mastercard.png')}}"></a>
                            </li>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/paypal.png')}}"></a>
                            </li>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/american-express.png')}}"></a>
                            </li>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/discover.png')}}"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</footer>