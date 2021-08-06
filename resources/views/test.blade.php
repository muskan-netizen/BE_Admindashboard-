@extends('layouts.store', ['title' => 'Product'])
@section('content')



    <section class="cab-booking pt-0">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d4850.865733603189!2d76.82393041076074!3d30.716149768967526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1627015845978!5m2!1sen!2sin" width="100%" height="100vh" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <div class="booking-experience ds bc">
            <form class="address-form">
                <div class="location-box">
                    <ul class="location-inputs position-relative pl-2">
                        <li class="d-block mb-3 dots">
                            <input class="form-control pickup-text" type="text" placeholder="From CH Devi Lal Centre of Learning" />
                            <i class="fa fa-times ml-1" aria-hidden="true"></i>
                        </li>
                        <li class="d-block mb-3 dots">
                            <input class="form-control pickup-text" type="text" placeholder="To Sector 14" />
                            <i class="fa fa-times ml-1" aria-hidden="true"></i>
                        </li>
                    </ul>
                    <a class="add-more-location position-relative pl-2" href="javascript:void(0)">Add Destination</a>
                </div>

                <div class="location-list style-4 d-none">
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
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Haryana General Store</b></h4>
                        <p class="ellips mb-0">Sector 14, BEL Colony, Budanpur, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Sector 14</b></h4>
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
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Haryana General Store</b></h4>
                        <p class="ellips mb-0">Sector 14, BEL Colony, Budanpur, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Sector 14</b></h4>
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
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Haryana General Store last</b></h4>
                        <p class="ellips mb-0">Sector 14, BEL Colony, Budanpur, Panchkula, Haryana, India</p>
                    </a>
                </div>
                
                <div class="cab-button d-flex align-items-center py-2">
                    <a class="btn btn-solid ml-2" href="#">uber</a>
                    <a class="btn btn-solid ml-2" href="#">ola</a>
                </div>
                
                <div class="vehical-container style-4">
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
                        </div>
                        <div class="col-9">
                            <div class="row no-gutters">
                                <div class="col-8 vehicle-details">
                                    <h4 class="m-0"><b>Go Intercity</b></h4>
                                    <p class="station-rides ellips">Affordable outstation rides</p>
                                    <p class="waiting-time m-0"><span class="mr-1">In 2 mins.</span><span>03:04 pm</span></p>
                                </div>
                                <div class="col-4 ride-price pl-2">
                                    <p class="mb-0"><b>₹2,634.37</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr class="m-0">
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
                        </div>
                        <div class="col-9">
                            <div class="row no-gutters">
                                <div class="col-8 vehicle-details">
                                    <h4 class="m-0"><b>Go Intercity</b></h4>
                                    <p class="station-rides ellips">Affordable outstation rides</p>
                                    <p class="waiting-time m-0"><span class="mr-1">In 2 mins.</span><span>03:04 pm</span></p>
                                </div>
                                <div class="col-4 ride-price pl-2">
                                    <p class="mb-0"><b>₹2,634.37</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr class="m-0">
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
                        </div>
                        <div class="col-9">
                            <div class="row no-gutters">
                                <div class="col-8 vehicle-details">
                                    <h4 class="m-0"><b>Go Intercity</b></h4>
                                    <p class="station-rides ellips">Affordable outstation rides</p>
                                    <p class="waiting-time m-0"><span class="mr-1">In 2 mins.</span><span>03:04 pm</span></p>
                                </div>
                                <div class="col-4 ride-price pl-2">
                                    <p class="mb-0"><b>₹2,634.37</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr class="m-0">
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
                        </div>
                        <div class="col-9">
                            <div class="row no-gutters">
                                <div class="col-8 vehicle-details">
                                    <h4 class="m-0"><b>Go Intercity</b></h4>
                                    <p class="station-rides ellips">Affordable outstation rides</p>
                                    <p class="waiting-time m-0"><span class="mr-1">In 2 mins.</span><span>03:04 pm</span></p>
                                </div>
                                <div class="col-4 ride-price pl-2">
                                    <p class="mb-0"><b>₹2,634.37</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr class="m-0">
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
                        </div>
                        <div class="col-9">
                            <div class="row no-gutters">
                                <div class="col-8 vehicle-details">
                                    <h4 class="m-0"><b>Go Intercity</b></h4>
                                    <p class="station-rides ellips">Affordable outstation rides</p>
                                    <p class="waiting-time m-0"><span class="mr-1">In 2 mins.</span><span>03:04 pm</span></p>
                                </div>
                                <div class="col-4 ride-price pl-2">
                                    <p class="mb-0"><b>₹2,634.37</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr class="m-0">
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
                        </div>
                        <div class="col-9">
                            <div class="row no-gutters">
                                <div class="col-8 vehicle-details">
                                    <h4 class="m-0"><b>Go Intercity</b></h4>
                                    <p class="station-rides ellips">Affordable outstation rides</p>
                                    <p class="waiting-time m-0"><span class="mr-1">In 2 mins.</span><span>03:04 pm</span></p>
                                </div>
                                <div class="col-4 ride-price pl-2">
                                    <p class="mb-0"><b>₹2,634.37</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr class="m-0">
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
                        </div>
                        <div class="col-9">
                            <div class="row no-gutters">
                                <div class="col-8 vehicle-details">
                                    <h4 class="m-0"><b>Go Intercity</b></h4>
                                    <p class="station-rides ellips">Affordable outstation rides</p>
                                    <p class="waiting-time m-0"><span class="mr-1">In 2 mins.</span><span>03:04 pm</span></p>
                                </div>
                                <div class="col-4 ride-price pl-2">
                                    <p class="mb-0"><b>₹2,634.37</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </form>
        </div>
    </section>
    
    <div class="search-list">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="radius-bar w-100">
                        <form class="search_form d-flex align-items-center justify-content-between" action="">
                            <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                            <input class="form-control border-0" type="text" placeholder="Search">
                        </form>
                    </div>
                    <div class="list-box">
                        <div class="mb-4">
                            <h4>Categories List</h4>
                            <div class="table-responsive style-4">
                                <div class="row flex-nowrap mx-0 mb-2">
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h4>Vendor List</h4>
                            <div class="table-responsive style-4">
                                <div class="row flex-nowrap mx-0 mb-2">
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h4>Product List</h4>
                            <div class="table-responsive style-4">
                                <div class="row flex-nowrap mx-0 mb-2">
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                    <div class="col-1 text-center list-items">
                                        <img src="https://d4p17acsd5wyj.cloudfront.net/shortcuts/deals.png" alt="">
                                        <span>Deal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  

    <!-- Vendor Sign Up Form -->
    <section class="vendor-signup">
        <div class="container">

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                18+ popup
            </button>

            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    
                <div class="row">
                    <div class="col-12">
                        <h2>Personal Details.</h2>
                    </div>    
                </div>

                <form class="needs-validation vendor-signup" novalidate>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom01">Full name</label>
                            <input type="text" class="form-control" id="validationCustom01" value="Mark" required>
                            <div class="valid-feedback">
                                Enter Full Name!
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom02">Phone No.</label>
                            <input type="text" class="form-control" id="validationCustom02" value="Otto" required>
                            <div class="valid-feedback">
                                Enter Vaild Number!
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom01">Email</label>
                            <input type="text" class="form-control" id="validationCustom03" value="Mark" required>
                            <div class="valid-feedback">
                                Enter Vaild E-mail!
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom02">Password</label>
                            <input type="text" class="form-control" id="validationCustom04" value="Otto" required>
                            <div class="valid-feedback">
                                Enter Correct Password!
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h2>Store Details.</h2>
                        </div>    
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="">Upload Logo</label>
                            <div class='file file--upload'>
                                <label for='input-file'>
                                    <span class="update_pic">
                                        <img src="" alt="" id="output">
                                    </span>
                                        <span class="plus_icon"><i class="fas fa-plus"></i></span>
                                </label>
                                <input id='input-file' type='file' name="profile_image" accept="image/*" onchange="loadFile(event)"/>
                            </div>
                        </div>      
                        <div class="col-md-8 mb-3">
                            <label for="">Upload Banner</label>
                            <div class='file file--upload'>
                                <label for='input-file'>
                                    <span class="update_pic">
                                        <img src="" alt="" id="banner">
                                    </span>
                                        <span class="plus_icon"><i class="fas fa-plus"></i></span>
                                </label>
                                <input id='input-file' type='file' name="profile_image" accept="image/*" onchange="loadFile(event)"/>
                            </div>
                        </div>      
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="validationCustom01">Name</label>
                            <input type="text" class="form-control" id="validationCustom05" value="Mark" required>
                            <div class="valid-feedback">
                                Enter Full Name!
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="validationCustom02">Description</label>
                            <textarea class="form-control" name="" id="validationCustom06" cols="30" rows="3"></textarea>
                            <div class="valid-feedback">
                                Enter Vaild Number!
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom01">Address</label>
                            <input type="text" class="form-control" id="validationCustom07" value="Mark" required>
                            <div class="valid-feedback">
                                Enter Full Name!
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom02">Website</label>
                            <input type="text" class="form-control" id="validationCustom08" value="Otto" required>
                            <div class="valid-feedback">
                                Enter Vaild Number!
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2 mb-3">
                            <label for="">Dine In</label>
                            <div class="toggle-icon">
                                <input type="checkbox" id="dine-in" /><label for="dine-in">Toggle</label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="">Takeaway</label>
                            <div class="toggle-icon">
                                <input type="checkbox" id="takeaway" /><label for="takeaway">Toggle</label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="">Delivery</label>
                            <div class="toggle-icon">
                                <input type="checkbox" id="delivery" /><label for="delivery">Toggle</label>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-solid mt-3 w-100" type="submit">Submit form</button>
                </form>
                
                </div>
            </div>
        </div>
    </section>

    <!-- Order Return Page -->
    <section class="return-page">
        <div class="container">
            <h2 >Choose items to return</h2>
            <form class="" action="">
                <div class="row rating_files">
                    <div class="col-12">
                    <label>Upload Images</label>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="file file--upload">
                            <label for="input-file">
                                <span class="plus_icon"><i class="fa fa-plus" aria-hidden="true"></i></span>
                            </label>
                            <input id="input-file" type="file" name="profile_image" accept="image/*" onchange="loadFile(event)">
                        </div>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <span class="update_pic">
                            <img src="" alt="" id="output">
                        </span>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <span class="update_pic">
                            <img src="" alt="" id="output">
                        </span>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <span class="update_pic">
                            <img src="" alt="" id="output">
                        </span>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <span class="update_pic">
                            <img src="" alt="" id="output">
                        </span>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <span class="update_pic">
                            <img src="" alt="" id="output">
                        </span>
                    </div>
                </div>

                
                <div class="row form-group">
                    <div class="col-md-6">
                        <label>Resoan for return product.</label>
                        <select class="form-control" name="" id=""></select>
                    </div>    
                </div>
                <div class="form-group">
                    <label>Comments (Optional):</label>
                    <textarea class="form-control" name="" id="" cols="30" rows="10"></textarea>
                </div>
                <button class="btn btn-solid mt-3 ">Request</button>
            </form>
        </div>
    </section>

    <!-- Return List Page -->
    <section class="return-list-page">
        <div class="container">
            <h2></h2>
            <div class="row">
                <div class="col-12">

                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid px-0 py-5">
        <div class="row no-gutters">
            <div class="col-12">
                <div class="full-banner custom-space p-right text-end">
                    <img src="{{asset('assets/images/baner.jpg')}}" alt="" class="bg-img blur-up lazyload">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-11">
                                <div class="banner-contain custom-size">
                                    <h2>2018</h2>
                                    <h3>fashion trends</h3>
                                    <h4>special offer</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="wrapper-main mb-5 py-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="accordion" id="accordionExample">
                        <div class="card border-0 bg-transparent">
                            <div class="card-header bg-transparent border-0 p-0" id="headingOne">
                            <h2 class="my-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Dairy & Eggs
                                </button>
                            </h2>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body pr-0 pl-2 pb-0 pt-2">
                                <ul class="category-list">
                                    <li><a href="#">Packaged Cheese</a></li>
                                    <li><a href="#">Milk</a></li>
                                    <li><a href="#">Yogurt</a></li>
                                    <li><a href="#">Eggs</a></li>
                                    <li><a href="#">Cream</a></li>
                                    <li><a href="#">Other Creams & Cheeses</a></li>
                                    <li><a href="#">Butter</a></li> 
                                </ul>
                            </div>
                            </div>
                        </div>
                        <div class="card border-0 bg-transparent">
                            <div class="card-header bg-transparent border-0 p-0" id="headingTwo">
                                <h2 class="my-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Bakery
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body pr-0 pl-2 pb-0 pt-2">
                                    Some placeholder content for the second accordion panel. This panel is hidden by default.
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 bg-transparent">
                            <div class="card-header bg-transparent border-0 p-0" id="headingThree">
                            <h2 class="my-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Snacks
                                </button>
                            </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body pr-0 pl-2 pb-0 pt-2">
                                And lastly, the placeholder content for the third and final accordion panel. This panel is hidden by default.
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/pYuRTyCq1V0zAdMX5kakYkWKmO81TEkyprg4Cqgp.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/IDGuBlBZ0JaFok1JCLntxzDvDZqBE86Nu28zcCh9.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/WXBAjSXzudaQeoEfXtEaOgVqtCetzGexwmLbWFNX.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/WXBAjSXzudaQeoEfXtEaOgVqtCetzGexwmLbWFNX.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/pYuRTyCq1V0zAdMX5kakYkWKmO81TEkyprg4Cqgp.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/IDGuBlBZ0JaFok1JCLntxzDvDZqBE86Nu28zcCh9.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/pYuRTyCq1V0zAdMX5kakYkWKmO81TEkyprg4Cqgp.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/IDGuBlBZ0JaFok1JCLntxzDvDZqBE86Nu28zcCh9.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/WXBAjSXzudaQeoEfXtEaOgVqtCetzGexwmLbWFNX.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="product-pagination">
                        <div class="theme-paggination-block">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">3</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="product-search-count-bottom">
                                        <h5>Showing Products 1-24 of 10 Result</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> 

    <section class="order-detail-page">
        <div class="container">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Order Detail</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 mb-3 mb-lg-4">
                            <div class="card mb-0 h-100">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">Track Order</h4>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-4">
                                                <h5 class="mt-0">Order ID:</h5>
                                                <p>#43985703</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row track-order-list">
                                        <div class="col-12">
                                            <ul class="list-unstyled" id="order_statuses">
                                                <li class="">
                                                    <h5 class="mt-0 mb-1">Placed</h5>
                                                    <p class="text-muted" id="text_muted">
                                                            <small class="text-muted">Monday, June 28, 2021, 06:18 AM</small>
                                                    </p>
                                                </li>
                                                <li class="">
                                                    <h5 class="mt-0 mb-1">Placed</h5>
                                                    <p class="text-muted" id="text_muted">
                                                            <small class="text-muted">Monday, June 28, 2021, 06:18 AM</small>
                                                    </p>
                                                </li>
                                                <li class="">
                                                    <h5 class="mt-0 mb-1">Placed</h5>
                                                    <p class="text-muted" id="text_muted">
                                                            <small class="text-muted">Monday, June 28, 2021, 06:18 AM</small>
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 mb-3 mb-lg-4">
                            <div class="card mb-0 h-100">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">Items from Order #43985703</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-centered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Product name</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                                <tbody>
                                                     <th scope="row">Roll 
                                                        </th>
                                                    <td>
                                                        <img src="https://imgproxy.royoorders.com/insecure/fill/32/32/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/RQAO9fhVSoquNYpVIN0aui9XpEBeyyWBXr9ncVVV.png" alt="product-img" height="32">
                                                    </td>
                                                    <td>1</td>
                                                    <td>$100.00</td>
                                                    <td>$100.00</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" colspan="4" class="text-end">Sub Total :</th>
                                                    <td>
                                                        <div class="fw-bold">$100.00</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" colspan="4" class="text-end">Total Discount :</th>
                                                    <td>$0.00</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" colspan="4" class="text-end">Estimated Tax :</th>
                                                    <td>$5.00</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" colspan="4" class="text-end">Total :</th>
                                                    <td>
                                                        <div class="fw-bold">$105.00</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <div class="card mb-0 h-100">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">Shipping Information</h4>
                                    <h5 class="font-family-primary fw-semibold">accountsqq</h5>
                                    <p class="mb-2"><span class="fw-semibold me-2">Address:</span> Dolphin Mall, Northwest 12th Street, Miami, FL, USA</p>
                                    <p class="mb-0"><span class="fw-semibold me-2">Mobile:</span> 1234567890</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 mb-3">
                            <div class="card mb-0 h-100">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">Delivery Info</h4>
                                    <div class="text-center">
                                        <i class="mdi mdi-truck-fast h2 text-muted"></i>
                                        <h5><b>UPS Delivery</b></h5>
                                        <p class="mb-1"><span class="fw-semibold">Order ID :</span> #43985703</p>
                                        <p class="mb-0"><span class="fw-semibold">Payment Mode :</span> Stripe</p>
                                    </div>
                                    <div class="text-center mt-2">
                                        <a href="javascript::void(0);" class="btn btn-solid" id="delivery_info_button">Delivery Info</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="exampleModalLabel">Verify your age</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{asset('assets/images/18.png')}}" alt="">
                <p class="mb-0 mt-3">Are you 18 or older?</p>
            </div>
            <div class="modal-footer d-block">
                <div class="row no-gutters">
                    <div class="col-6 pr-1">
                        <button type="button" class="btn btn-solid w-100" data-dismiss="modal">Yes</button>
                    </div>
                    <div class="col-6 pl-1">
                        <button type="button" class="btn btn-solid w-100" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade edit_address" id="edit-address" tabindex="-1" aria-labelledby="edit-addressLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-body p-0">
                <div id="address-map-container">
                    <div id="address-map"></div>
                </div>
                <div class="delivery_address p-2 mb-2 position-relative">
                    <button type="button" class="close edit-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="form-group">
                        <label class="delivery-head mb-2">SELECT YOUR LOCATION</label>
                        <div class="address-input-field d-flex align-items-center justify-content-between">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <input class="form-control border-0 map-input" type="text" name="address-input" id="address-input" value="{{session('selectedAddress')}}">
                            <input type="hidden" name="address_latitude" id="address-latitude" value="{{session('latitude')}}" />
                            <input type="hidden" name="address_longitude" id="address-longitude" value="{{session('longitude')}}" />
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-solid ml-auto confirm_address_btn w-100">Confirm And Proceed</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
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

    <script>
        
    </script>
    
@endsection