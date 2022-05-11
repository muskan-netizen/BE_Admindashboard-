@if($client_preference_detail->business_type != 'taxi' && (\Request::route()->getName() != 'customer.login') && (\Request::route()->getName() != 'customer.register') && (\Request::route()->getName() != 'user.verify') && (\Request::route()->getName() != 'user.profile') && (\Request::route()->getName() != 'user.addressBook') && (\Request::route()->getName() != 'user.orders') && (\Request::route()->getName() != 'user.wishlists') && (\Request::route()->getName() != 'user.loyalty') && (\Request::route()->getName() != 'user.wallet') && (\Request::route()->getName() != 'user.subscription.plans') && (\Request::route()->getName() != 'user.changePassword') && (\Request::route()->getName() != 'showCart') && (\Request::route()->getName() != 'order.success') && (\Request::route()->getName() != 'order.return.success'))
<div class="main-menu @if((\Request::route()->getName() != 'userHome')) no-category-image @endif">
			<div class="container-fluid text-center py-3" >
				<div class="row align-items-center justify-content-center position-initial">
					<div class="al_count_tabs_fourdesign d-none d-sm-block"  >
								@if($mod_count > 1)
								<ul class="nav nav-tabs navigation_tab_al nav-material tab-icons vendor_mods" id="top-tab" role="tablist">
									@if($client_preference_detail->delivery_check==1) @php $Delivery=getNomenclatureName('Delivery', true); $Delivery=($Delivery==='Delivery') ? __('Delivery') : $Delivery; @endphp
									<li class="navigation-tab-item" role="presentation">
										<a class="nav-link al_delivery {{($mod_count==1 || (Session::get('vendorType')=='delivery') || (Session::get('vendorType')=='')) ? 'active' : ''}}" id="delivery_tab" data-toggle="tab" href="#delivery_tab" role="tab" aria-controls="profile" aria-selected="false">
											{{$Delivery}}
										</a>
									</li>
									@endif @if($client_preference_detail->dinein_check==1) @php $Dine_In=getNomenclatureName('Dine-In', true); $Dine_In=($Dine_In==='Dine-In') ? __('Dine-In') : $Dine_In; @endphp
									<li class="navigation-tab-item " role="presentation">
										<a class="nav-link al_dinein {{($mod_count==1 || (Session::get('vendorType')=='dine_in')) ? 'active' : ''}}" id="dinein_tab" data-toggle="tab" href="#dinein_tab" role="tab" aria-controls="dinein_tab" aria-selected="false">
											{{$Dine_In}}
										</a>
									</li>
									@endif @if($client_preference_detail->takeaway_check==1)
									<li class="navigation-tab-item " role="presentation">
										@php $Takeaway=getNomenclatureName('Takeaway', true); $Takeaway=($Takeaway==='Takeaway') ? __('Takeaway') : $Takeaway; @endphp
										<a class="nav-link al_takeway {{($mod_count==1 || (Session::get('vendorType')=='takeaway')) ? 'active' : ''}}" id="takeaway_tab" data-toggle="tab" href="#takeaway_tab" role="tab" aria-controls="takeaway_tab" aria-selected="false">
											{{$Takeaway}}
										</a>
									</li>
									@endif
									<div class="navigation-tab-overlay_alnew_design"></div>
								</ul>
								@endif
							</div>

							<div class="al_count_tabs_new_design al_tab_mobile position-fixed d-block d-sm-none">
								@if($mod_count > 1)
								<ul class="nav nav-tabs navigation-tab_al nav-material tab-icons mr-lg-3 vendor_mods d-flex justify-content-around" id="top-tab" role="tablist">
									@if($client_preference_detail->delivery_check==1) @php $Delivery=getNomenclatureName('Delivery', true); $Delivery=($Delivery==='Delivery') ? __('Delivery') : $Delivery; @endphp
									<li class="navigation-tab-item pr-lg-3" role="presentation">
										<a class="nav-link al_delivery {{($mod_count==1 || (Session::get('vendorType')=='delivery') || (Session::get('vendorType')=='')) ? 'active' : ''}}" id="delivery_tab" data-toggle="tab" href="#delivery_tab" role="tab" aria-controls="profile" aria-selected="false">
											<span><img src="{{asset('images/al_custom3.png')}}" alt=""></span>
											{{$Delivery}}
										</a>
									</li>
									@endif @if($client_preference_detail->dinein_check==1) @php $Dine_In=getNomenclatureName('Dine-In', true); $Dine_In=($Dine_In==='Dine-In') ? __('Dine-In') : $Dine_In; @endphp
									<li class="navigation-tab-item pr-lg-3 " role="presentation">
										<a class="nav-link al_dinein {{($mod_count==1 || (Session::get('vendorType')=='dine_in')) ? 'active' : ''}}" id="dinein_tab" data-toggle="tab" href="#dinein_tab" role="tab" aria-controls="dinein_tab" aria-selected="false">
											<span><img src="{{asset('images/al_custom1.png')}}" alt=""></span>
											{{$Dine_In}}
										</a>
									</li>
									@endif @if($client_preference_detail->takeaway_check==1)
									<li class="navigation-tab-item  pr-lg-3" role="presentation">
										@php $Takeaway=getNomenclatureName('Takeaway', true); $Takeaway=($Takeaway==='Takeaway') ? __('Takeaway') : $Takeaway; @endphp
										<a class="nav-link al_takeway {{($mod_count==1 || (Session::get('vendorType')=='takeaway')) ? 'active' : ''}}" id="takeaway_tab" data-toggle="tab" href="#takeaway_tab" role="tab" aria-controls="takeaway_tab" aria-selected="false">
											<span><img src="{{asset('images/al_custom2.png')}}" alt=""></span>
											{{$Takeaway}}
										</a>
									</li>
									@endif
								</ul>
								@endif
							</div>
				</div>
			</div>
		</div>
@endif