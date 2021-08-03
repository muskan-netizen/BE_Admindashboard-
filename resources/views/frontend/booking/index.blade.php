@extends('layouts.store', ['title' => 'Product'])
@section('content')

    

    <section class="cab-booking pt-0">
        
        <div id="booking-map" style="width: 100%; height: 100%;"></div>

       <input id="booking-latitude" type="hidden" value="28.7041">
        <input id="booking-longitude"  type="hidden" value="77.1025">
      
        <div class="booking-experience ds bc">
            <form class="address-form">
                <div class="location-box">
                    <ul class="location-inputs position-relative pl-2">
                        <li class="d-block mb-3 dots">
                          
                            <input class="form-control pickup-text" type="text" placeholder="From CH Devi Lal Centre of Learning" id="addHeader1-input" name="address[]"/>
                            <input type="hidden" name="latitude[]" id="addHeader1-latitude" value="0" class="cust_latitude" />
                            <input type="hidden" name="longitude[]" id="addHeader1-longitude" value="0" class="cust_longitude" />
                            {!! Form::text('post_code[]', null, ['class' => 'form-control address postcode','placeholder' => 'Post Code','id'=>'addHeader1-postcode']) !!}
                            <i class="fa fa-times ml-1" aria-hidden="true"></i>
                           
                        </li>
                        <li class="d-block mb-3 dots">
                            <input class="form-control pickup-text" type="text" placeholder="To Sector 14" />
                            <i class="fa fa-times ml-1" aria-hidden="true"></i>
                        </li>
                    </ul>
                    <a class="add-more-location position-relative pl-2" href="javascript:void(0)">Add Destination</a>
                </div>
                <div class="location-list style-4">
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Sector 14 first</b></h4>
                        <p class="ellips mb-0">Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Haryana General Store</b></h4>
                        <p class="ellips mb-0">Sector 14, BEL Colony, Budanpur, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Sector 14</b></h4>
                        <p class="ellips mb-0">BEL Colony, Sector 14, Panchkula, Haryana, India</p>
                    </a>
                    
                </div>
            </form>
        </div>
    </section>

    
 
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

        var live_location = '{{ URL::asset('/images/live_location.gif') }}';
       
    </script>
  

    
@endsection