<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ATLANTIC</title>
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<!---css slider cdn-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick-theme.min.css">
	<!---end here---->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
	<!--js slider cdn -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick.min.js"></script>
	<!--end here--->	
</head>
<body>
	<header id="" class="site_header">
		<div class="container">
			<div class="row  align-items-center justify-content-between">
				<div class="left">
					<div class="logo">
						<div class="image">
							<img src="yacht-images/logo.png" alt="Logo">
						</div>
					</div>
				</div>
				<div class="right">
					<nav class="menu">
						<ul class="d-flex  align-items-center">
							<li><a href="" title="">Home</a></li>
							<li><a href="" title="">Car Rental</a></li>
							<li><a href="" title="">Airport Pickup and Drop</a></li>
							<li><a href="" title="">Sign in / Login</a></li>							
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</header>
	<section class="banner">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="text">
						<h1>Seamless Journeys, Unforgettable Rides </h1>
						<p>Your Trusted Car Rental and Airport Transfer Solution.</p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="image">
						<img src="yacht-images/banner.png" alt="banner">
					</div>
				</div>
			</div>
			<div class="banner_tab">
				<div class="tabs">
				  {{-- <ul id="tabs-nav" class="d-flex align-items-center">
				    <li>
				    	<a href="#tab1">
				    		<img src="yacht-images/icons/1.png" alt="">
				    		Car Rental
				    	</a>
				    </li>
				    <li>
				    	<a href="#tab1">
				    		<img src="yacht-images/icons/2.png" alt="">
				    		Airport Pick and Drop
				    	</a>
				    </li>
					<li>
				    	<a href="#tab1">
				    		<img src="yacht-images/icons/2.png" alt="">
				    		Yacht Pick and Drop
				    	</a>
				    </li>				    
				  </ul> <!-- END tabs-nav --> --}}
				  <form action="{{ route('productSearch') }}" method="POST">
					@csrf
					<div class="tab">
						<div class="d-flex align-items-center">
							<div class="form-group">
								<input type="radio" value="rental" name="service" placeholder="" id="car" checked>
								<label for="car">
									<img src="yacht-images/icons/1.png" alt="">
									Car Rental
								</label>
								<span></span>
							</div>
							<div class="form-group">
								<input type="radio" value="airport" name="service" placeholder="" id="Airport">
								<label for="Airport">
									<img src="yacht-images/icons/2.png" alt="">
									Airport Pick and Drop
								</label>
								<span></span>
							</div>
							<div class="form-group">
								<input type="radio" value="yacht" name="service" placeholder="" id="Yacht">
								<label for="Yacht">
									<img src="yacht-images/icons/2.png" alt="">
									Yacht Pick and Drop
								</label>
								<span></span>
							</div>
						</div>
				  	</div>
				  	<div id="tabs-content">
					    <div id="tab1" class="tab-content">
					      	<div class="row align-items-center">
					      		<div class="col-md-2">
					      			<div class="item">
					      				<h5>Location</h5>
					      				<p><img src="yacht-images/icons/3.png" alt=""> 
											<input class="" type="text" name="location" value="" placeholder="1801 Oak Ridge Ln">
										</p>
					      			</div>
					      		</div>
					      		<div class="col-md-3">
					      			<div class="item">
					      				<h5>Pickup Date & Time</h5>
					      				<p><img src="yacht-images/icons/4.png" alt="">
											<input type="datetime-local" name="pickup_time">
										</p>
					      			</div>
					      		</div>
					      		<div class="col-md-3">
					      			<div class="item">
					      				<h5>Drop Date & Time</h5>
					      				<p><img src="yacht-images/icons/4.png" alt="">
											<input type="datetime-local" name="drop_time">
										</p>
					      			</div>
					      		</div>
								  <div class="col-md-2">
									<div class="item">
										<h5>Seat Number</h5>		
										<p>								
										<input class="pl-0" type="number" name="location" value="" placeholder="04">
									  </p>
									</div>
								</div>
					      		<div class="col-md-2">
					      			<div class="cta">
										<button type="submit">Search</button>
					      			</div>
					      		</div>
					      	</div>
					    </div>
					</div>
				  </form>
				</div>
			</div>
		</div>
	</section>

	<section class="ourServices">
		<div class="container">
			<div class="heading">
				<h2>Our Services</h2>
			</div>
			<div class="row mx-0">
				<!-- 1 -->
				<div class="col-md-4 px-0">
					<div class="item">
						<div class="text">
							<h3>Car Rental</h3>
							<p>Economical, Family or Spacious Cars. Exclusive Offers & Low Prices. Book on our Web instead of Comparison Websites. Best Price Guaranteed.</p>
						</div>
						<div class="image">
							<img src="yacht-images/1.png" alt="">
						</div>
					</div>
				</div>
				<!-- 2 -->
				<div class="col-md-4 px-0">
					<div class="item">
						<div class="image">
							<img src="yacht-images/2.png" alt="">
						</div>
						<div class="text">
							<h3>Airport Pick and Drop</h3>
							<p>Economical, Family or Spacious Cars. Exclusive Offers & Low Prices. Book on our Web instead of Comparison Websites. </p>
						</div>						
					</div>
				</div>
				<!-- 3 -->
				<div class="col-md-4 px-0">
					<div class="item">
						<div class="text">
							<h3>Yacht services</h3>
							<p>Economical, Family or Spacious Cars. Exclusive Offers & Low Prices. Book on our Web instead of Comparison Websites. </p>
						</div>
						<div class="image">
							<img src="yacht-images/3.png" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="ourFleet">
		<div class="container">
			<div class="heading">
				<h2>Our Fleet</h2>
			</div>
			<div class="fleet_slider">
				<!-- 1 -->
				<div class="item">
					<div class="image">
						<img src="yacht-images/fleet/1.png" alt="">
					</div>
					<div class="text">
						<h3>SUVs</h3>
					</div>
				</div>
				<!-- 2 -->
				<div class="item">
					<div class="image">
						<img src="yacht-images/fleet/2.png" alt="">
					</div>
					<div class="text">
						<h3>Convertibles</h3>
					</div>
				</div>
				<!-- 3 -->
				<div class="item">
					<div class="image">
						<img src="yacht-images/fleet/3.png" alt="">
					</div>
					<div class="text">
						<h3>Sports Car</h3>
					</div>
				</div>
				<!-- 4 -->
				<div class="item">
					<div class="image">
						<img src="yacht-images/fleet/4.png" alt="">
					</div>
					<div class="text">
						<h3>Minivans</h3>
					</div>
				</div>
				<!-- 5 -->
				<div class="item">
					<div class="image">
						<img src="yacht-images/fleet/5.png" alt="">
					</div>
					<div class="text">
						<h3>Passenger vans</h3>
					</div>
				</div>
				<!-- 6 -->
				<div class="item">
					<div class="image">
						<img src="yacht-images/fleet/5.png" alt="">
					</div>
					<div class="text">
						<h3>Pickup Trucks</h3>
					</div>
				</div>
				<!-- 7 -->
				<div class="item">
					<div class="image">
						<img src="yacht-images/fleet/1.png" alt="">
					</div>
					<div class="text">
						<h3>SUVs</h3>
					</div>
				</div>
				<!-- 8 -->
				<div class="item">
					<div class="image">
						<img src="yacht-images/fleet/2.png" alt="">
					</div>
					<div class="text">
						<h3>Convertibles</h3>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="offers_block"> 
		<div class="container">
			<div class="heading">
				<h2>Ongoing Offers</h2>
			</div>
			<div class="row">
				<!-- 1 -->
				<div class="col-md-4">
					<div class="item">
						<div class="image">
							<img src="yacht-images/offers/1.png" alt="">
							<span class="offer">20% off</span>
						</div>
						<div class="text">
							<h4>Superfast Pickup and Drop</h4>
							<p>Pickup and drop services in no time.</p>
						</div>
					</div>
				</div>
				<!-- 2 -->
				<div class="col-md-4">
					<div class="item">
						<div class="image">
							<img src="yacht-images/offers/2.png" alt="">
							<span class="offer">10% off</span>
						</div>
						<div class="text">
							<h4>Airport pickup service</h4>
							<p>Enjoy shuttle service at low prices this month.</p>
						</div>
					</div>
				</div>
				<!-- 3 -->	
				<div class="col-md-4">
					<div class="item">
						<div class="image">
							<img src="yacht-images/offers/3.png" alt="">
							<span class="offer">30% off</span>
						</div>
						<div class="text">
							<h4>Your Trip in style</h4>
							<p>Book SUV cars for your next desert Safari !</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="mobileApp">
		<div class="container">
			<div class="mobileApp_text text-center">
				<h2>Our mobile app</h2>
				<h3>coming soon.</h3>
				<p>With best-in-class car rentals and services that provide you the best experience of all time !</p>
				<div class="d-flex justify-content-center">
                     <a href=""><img src="yacht-images/playstore.png"></a>
                     <a href=""><img src="yacht-images/appstore.png"></a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 text-center left">
					<div class="image">
						<img src="yacht-images/phone.png" alt="">
					</div>
				</div>
				<div class="col-md-6 text-center right">
					<div class="image">
						<img src="yacht-images/iphone.png" alt="">
					</div>
				</div>
			</div>
		</div>
	</section>
	<footer class="site_footer">
		<div class="container">
			<div class="footer_row">
				<div class="item">
					<div class="footer_logo">
						<div class="image">
							<img src="yacht-images/footer-logo.png">
						</div>
					</div>
				</div>
				<div class="item">
					<div class="text">
						<h3>Services</h3>
						<ul>
							<li><a href="" title="">Rent a car</a></li>
							<li><a href="" title="">Airport Pickup and Drop</a></li>
						</ul>
					</div>
				</div>
				<div class="item">
					<div class="text">
						<h3>Quick Links</h3>
						<ul>
							<li><a href="" title="">About Us</a></li>
							<li><a href="" title="">FAQs</a></li>
							<li><a href="" title="">Privacy Policy</a></li>
							<li><a href="" title="">Terms & Conditions</a></li>							
						</ul>
					</div>
				</div>
				<div class="item">
					<div class="text">
						<h3>Contact Us</h3>
						<ul>
							<li><a href="" title="">+644 6655 654</a></li>
							<li><a href="" title="">example@gmail.com</a></li>							
						</ul>
					</div>
				</div>
				<div class="item">
					<div class="text social_icon">					
						<ul class="d-flex">
							<li><a href="" title=""><i class="fa fa-facebook"></i></a></li>
							<li><a href="" title=""><i class="fa fa-google"></i></a></li>
							<li><a href="" title=""><i class="fa fa-apple"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="footer_bar">
				<ul class="d-flex justify-content-center">
					<li>© Atlantic</li>
					<li>Privacy Policy</li>
					<li>Site Credits</li>
				</ul>
			</div>
		</div>
	</footer>

	<script>
		$(function () {
		AOS.init({
			duration: 1000,
		});	
			});
		$(".menu_cta").click( function() {
			$("html").toggleClass("active");
		});

		// Show the first tab and hide the rest
$('#tabs-nav li:first-child').addClass('active');
$('.tab-content').hide();
$('.tab-content:first').show();

// Click function
$('#tabs-nav li').click(function(){
  $('#tabs-nav li').removeClass('active');
  $(this).addClass('active');
  $('.tab-content').hide();
  
  var activeTab = $(this).find('a').attr('href');
  $(activeTab).fadeIn();
  return false;
});


  $(".fleet_slider").slick({
   slidesToShow: 6,
   infinite:false,
   slidesToScroll: 1,
   autoplay: true,
   autoplaySpeed: 2000,
   dots:false,
   arrows: true
  });
	</script>
</body>
</html>