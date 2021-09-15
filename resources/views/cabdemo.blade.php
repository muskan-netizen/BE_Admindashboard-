@extends('layouts.store', ['title' => 'Product'])
@section('content')




    <!-- Cab Booking Start From Here -->
    <section class="cab-banner-area p-0">
        <div class="container p-64">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-box mb-0">
                        <h1>Request a ride now</h1>
                        <form action="" class="cab-booking-form">

                            <div class="cab-input">
                                <div class="form-group mb-1 position-relative">
                                    <input class="form-control" type="text" placeholder="Enter pickup location">
                                    <a class="location-btn" href="#">
                                        <img src="{{asset('front-assets/images/arrow.svg')}}" alt="">
                                    </a>
                                </div>
                                <div class="form-group mb-0">
                                    <input class="form-control" type="text" placeholder="Enter pickup location">
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

    <div class="cab-content-area">

        <!-- Royo Business Start From Here -->
        <section class="royo-business p-0">
            <div class="container p-64">
                <div class="row">
                    <div class="col-12">
                        <h2 class="title-36">Royo for Business</h2>
                        <div class="description-text">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, labore!</p>
                        </div>
                        <a class="btn btn-solid new-btn d-inline-block" href="#">See how</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Royo Business Start From Here -->
        <section class="royo-rental p-0">
            <div class="container">                
               
                <div class="row align-items-center p-64">
                    <div class="col-sm-6">
                        <div class="cab-img-box">
                            <img class="img-fluid" src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_1116,h_744/v1624484990/assets/fa/f20c42-425a-4243-866b-b480d3bd68b4/original/gettyimages-1139275491-2048x2048_With-Mask.png" alt="">
                        </div>
                    </div>
                    <div class="offset-md-1 col-sm-6 col-md-5 pl-lg-4">
                        <div class="">
                            <h2 class="title-52">Royo for Business</h2>
                            <div class="description-text">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem nisi officiis numquam!</p>
                            </div>
                            <a class="learn-more bottom-line" href="#">Learn more</a>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center p-64">
                    <div class="col-sm-6 order-md-1">
                        <div class="cab-img-box">
                            <img class="img-fluid" src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_372/v1623719981/assets/4d/b05e4c-7340-40c4-a3e9-da0de41f14fc/original/rentals-iindia.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-sm-6 order-md-0">
                        <div class="pr-lg-5 mr-lg-5">
                            <h2 class="title-52">Royo Intercity </h2>
                            <div class="description-text">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem nisi officiis numquam!</p>
                            </div>
                            <a class="learn-more" href="#">Learn more</a>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>

        <!-- Focused On Safety Start From Here -->
        <section class="focused-on-safety p-0">
            <div class="container p-64">
                <div class="row mb-4 pb-2">
                    <div class="col-12">
                        <div class="title-36">Focused on safety, wherever you go</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="safety-box">
                            <div class="safety-img">
                                <img class="img-fluid" src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_372/v1613520218/assets/3e/e98625-31e6-4536-8646-976a1ee3f210/original/Safety_Home_Img2x.png" alt="">
                            </div>
                            <div class="safety-content">
                                <h3 class="mt-0">Our commitment to your safety</h3>
                                <div class="safety-text">
                                    <p>With every safety feature and every standard in our Community Guidelines, we're committed to helping to create a safe environment for our users.</p>
                                </div>
                                <div class="safety-links">
                                    <a class="bottom-line" href="#">
                                        <span>Read about our Community Guidelines</span>
                                    </a>
                                    <a class="bottom-line" href="#">
                                        <span>See all safety features</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="safety-box">
                            <div class="safety-img">
                                <img class="img-fluid" src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_372/v1613520218/assets/3e/e98625-31e6-4536-8646-976a1ee3f210/original/Safety_Home_Img2x.png" alt="">
                            </div>
                            <div class="safety-content">
                                <h3 class="mt-0">Setting 10,000+ cities in motion</h3>
                                <div class="safety-text">
                                    <p>With every safety feature and every standard in our Community Guidelines, we're committed to helping to create a safe environment for our users.</p>
                                </div>
                                <div class="safety-links">
                                    <a class="bottom-line" href="#">
                                        <span>View all cities</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    


    <script src="{{asset('front-assets/js/popper.min.js')}}"></script>
    <script src="{{asset('front-assets/js/slick.js')}}"></script>
    <script src="{{asset('front-assets/js/menu.js')}}"></script>
    <script src="{{asset('front-assets/js/lazysizes.min.js')}}"></script>
    <script src="{{asset('front-assets/js/bootstrap.js')}}"></script>
    <script src="{{asset('front-assets/js/jquery.elevatezoom.js')}}"></script>
    <script src="{{asset('front-assets/js/underscore.min.js')}}"></script>
    <script src="{{asset('front-assets/js/script.js')}}"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    <script src="{{asset('js/location.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>

    <script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    
    <script type="text/javascript">
        
    </script>

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
            });
        }, false);
        })();
    </script>
    <script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
   };
    </script>
    <script>
  var loadFile = function(event) {
    var banner = document.getElementById('banner');
    banner.src = URL.createObjectURL(event.target.files[0]);
   };

  
    </script>

   
    
@endsection