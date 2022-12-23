@extends('layouts.store', ['title' =>  __('Address Book')  ])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
@endsection
@section('content')

<style type="text/css">
    .productVariants .firstChild{
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }
    .product-right .color-variant li, .productVariants .otherChild{
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }
    .productVariants .otherSize{
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }
    .product-right .size-box ul li.active {
        background-color: inherit;
    }
    .login-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }
    .invalid-feedback{
        display: block;
    }
    .outer-box{
        min-height: 280px;
    }
    #address-map-container #pick-address-map {
        width: 100%;
        height: 100%;
    }
    .address-input-group{
        position: relative;
    }
    .address-input-group .pac-container{
        top:35px!important;
        left:0!important;
    }
    .cursor-pointer{
        cursor: pointer;
    }
</style>
<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                    @endif
                    @if ( ($errors) && (count($errors) > 0) )
                        <div class="alert alert-danger">
                            <ul class="m-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row my-md-3">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">{{ __('My Account') }}</a></div>
                <div class="dashboard-left mb-3">
                    <div class="collection-mobile-back">
                        <span class="filter-back d-lg-none d-inline-block">
                            <i class="fa fa-angle-left" aria-hidden="true"></i>{{ __('Back') }}
                        </span>
                    </div>
                    @include('layouts.store/profile-sidebar')
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        @if(empty($influencer_user))
                            <div class="page-title">
                                    <h2>{{ __('Refer and earn') }}</h2>
                            </div>
                            <div class="box-account box-info order-address">
                                @if( !empty($influencer_category) )
                                    <div class="row">
                                        @foreach($influencer_category as $key => $val)
                                            <div class="col-md-4">
                                                <a class="alert alert-dark cursor-pointer d-block" role="alert" href="{{ route('refer-earn.form', $val->id) }}">
                                                    {{$val->name ?? ''}}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @else
                                @if($influencer_user->is_approved == 1) {{-- && $influencer_user->status == 1 --}}
                                    <div class="row welcome-msg justify-content-between refer_code">
                                        <div class="col-md-6 mt-3">
                                            <h4 class="d-inline-block m-0 mb-3">
                                                <span>{{__('Your  Referral Code:')}} <strong>{{$influencer_user->reffered_code}}</strong></span>
                                            </h4>
                                            <sup class="position-relative">
                                                <a class="copy-icon ml-1" id="copy_icon" title="Copy" style="cursor:pointer;"><i class="fa fa-copy"></i></a>
                                                <a class="edit-icon ml-1" id="edit_refferal_icon" title="Edit" style="cursor:pointer;"><i class="fa fa-edit"></i></a>
                                                <p id="copy_message" class="copy-message"></p>
                                            </sup>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="total_amount_details">
                                                <div class="total-amount">
                                                    <label>Earning Amount</label>
                                                    <h3>$243.00</h3>
                                                </div>
                                                <div class="Earning-amount">
                                                    <label>Discount per order</label>
                                                    <h3>10%</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-1 profile-page">
                                        <div class="col-md-12">
                                            <div class="custom-table">
                                                <div class="group-item">
                                                    <input type="text" placeholder="Search by order id">
                                                </div>
                                                <table class="table table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Order ID</th>
                                                            <th scope="col">Product Name</th>
                                                            <th scope="col">Total Amount</th>
                                                            <th scope="col">User Discount</th>
                                                            <th scope="col">Earning Amount</th>
                                                            <th scope="col">Order Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>13569983</td>
                                                            <td>Bruschetta</td>
                                                            <td>$500.00</td>
                                                            <td>10%</td>
                                                            <td>$50.00</td>
                                                            <td>23/12/2022</td>
                                                        </tr>
                                                        <tr>
                                                            <td>04985599</td>
                                                            <td>Baby Oil</td>
                                                            <td>$600.00</td>
                                                            <td>10%</td>
                                                            <td>$60.00</td>
                                                            <td>03/12/2022</td>
                                                        </tr>
                                                        <tr>
                                                            <td>99241181</td>
                                                            <td>Baby Wipes</td>
                                                            <td>$410.00</td>
                                                            <td>10%</td>
                                                            <td>$41.00</td>
                                                            <td>23/12/2022</td>
                                                        </tr>
                                                        <tr>
                                                            <td>81598233</td>
                                                            <td>Brown Bread</td>
                                                            <td>$310.00</td>
                                                            <td>10%</td>
                                                            <td>$31.00</td>
                                                            <td>13/12/2022</td>
                                                        </tr>
                                                        <tr>
                                                            <td>58351469</td>
                                                            <td>Cheese</td>
                                                            <td>$610.00</td>
                                                            <td>10%</td>
                                                            <td>$61.00</td>
                                                            <td>03/12/2022</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center"><h3><strong>Waiting For Admin Approval</strong></h3></div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<div class="modal fade" id="removeAddressConfirmation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_addressLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title" id="remove_addressLabel">{{ __('Delete Address') }} </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="m-0">
                {{ __('Do you really want to delete this address ?') }}
        </h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="button" class="btn btn-solid" id="remove_address_confirm_btn" data-id="">{{ __('Delete') }}</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="updateRefferalCode" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_addressLabel">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-bottom">
          <h5 class="modal-title" id="remove_addressLabel">{{ __('Update Refferal Code') }} </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <h6 class="m-0">
                  {{ __('Do you really want to delete this address ?') }}
          </h6>
        </div>
        <div class="modal-footer">
            {{-- <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{ __('Cancel') }}</button> --}}
            <button type="button" class="btn btn-solid text-left" id="update_refferal_code_btn" data-id="">{{ __('Update') }}</button>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('script')
<script>
$(document).ready(function(){
    $('#edit_refferal_icon').on('click', function(){
        $('#updateRefferalCode').modal();
    });
});
</script>
@endsection
