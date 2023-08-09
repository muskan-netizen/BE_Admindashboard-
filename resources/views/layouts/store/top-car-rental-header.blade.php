<header id="" class="site_header {{ Request::is('/') ? '' : 'inner_header' }}">
	<div class="container">
		<div class="row  align-items-center justify-content-between">
			<div class="left">
				<div class="logo">
					<div class="image">
					<a href="{{route('userHome')}}">
						<img src="/yacht-images/logo.png" alt="Logo">
					</a>
					</div>
				</div>
			</div>
			<div class="right">
				<nav class="menu">
					<ul class="d-flex  align-items-center">
						<li><a href="{{route('userHome')}}" title="">Home</a></li>
						<li><a href="{{ route('productSearch',['service' => 'rental']) }}" title="">Car Rental</a></li>
						<li><a href="{{ route('productSearch',['service' => 'airport']) }}" title="">Airport Pickup and Drop</a></li>
						<li><a href="{{ route('productSearch',['service' => 'yacht']) }}" title="">Yacht</a></li>
						@if (Auth::guest())
							<li><a href="user/login" title="">Sign in / Login</a></li>
						@else	
							<li><a href="{{route('user.profile')}}" title="">My Account</a></li>
						@endif
					</ul>
				</nav>
			</div>
		</div>
	</div>
</header>