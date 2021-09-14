 <!-- Cab Booking Start From Here -->
 <section class="cab-banner-area p-0" style="background:url({{\Config::get('app.FIT_URl', '')}}{{\Config::get('app.IMG_URL2', '')}}/1000/600/{{asset('images/CabBANNER.jpg')}})">
    <div class="container p-64">
        <div class="row">
            <div class="col-md-6">
                <div class="card-box mb-0">
                    <h1>Request a ride now</h1>
                    <form action="{{ route('categoryDetail','cabservice')}}" class="cab-booking-form">
                                 <div class="cab-input">
                            <div class="form-group mb-1 position-relative">
                                <input class="form-control edit-other-stop" type="text" placeholder="Enter pickup location" name="pickup_location" id="destination_location_1" data-rel="1" >
                                <input type="hidden" name="pickup_location_latitude" value="" id="destination_location_latitude_home_1" data-rel="1"/>
                                <input type="hidden" name="pickup_location_longitude" value="" id="destination_location_longitude_home_1" data-rel="1"/>
                          
                                <a class="location-btn" href="#">
                                    <img src="{{asset('front-assets/images/arrow.svg')}}" alt="">
                                </a>
                            </div>
                            <div class="form-group mb-0">
                                <input class="form-control edit-other-stop" type="text" name="destination_location" placeholder="Enter drop location" id="destination_location_2" data-rel="2" >
                                <input type="hidden" name="destination_location_latitude" value="" id="destination_location_latitude_home_2" data-rel="2"/>
                                <input type="hidden" name="destination_location_longitude" value="" id="destination_location_longitude_home_2" data-rel="2"/>
                          
                            </div>
                            <div class="input-line"></div>
                        </div>

                        <div class="cab-footer">
                            <button class="btn btn-solid new-btn request-btn">Request now</button>
                            <button class="btn btn-solid new-btn schedule-btn">Schedule for later</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cab Content Area Start From Here -->