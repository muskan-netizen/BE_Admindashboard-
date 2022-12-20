@extends('layouts.store', ['title' => 'Create Requests'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }

</style>
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
@endsection
@section('content')

<style type="text/css">
    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }

    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }

    .productVariants .otherSize {
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

    .invalid-feedback {
        display: block;
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
        <div class="row my-md-3 mt-5 pt-4">
            <div class="col-lg-1">
                <div class="account-sidebar"><a class="popup-btn">{{ __('My Account') }}</a></div>
                {{-- @include('layouts.store/profile-sidebar') --}}
            </div>
            <div class="col-lg-10">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>{{ __('Bid Request') }}</h2>
                        </div>
                        <div class="card-box">
                            <h5 class="mb-3">{{ __('Prescriptions') }}</h4>
                            <div class="row align-items-center">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap table-striped" id="client_customer_table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('Prescription') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Image</td>
                                                <td>date</td>
                                                <td>View / Bid </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-2">
                        <div class="row welcome-msg justify-content-between">
                            <div class="col-12">
                                <h4 class="d-inline-block m-0">
                                    <span>{{ __('Bid Requests')}}</span>
                                </h4>
                                <sup class="position-relative">

                                </sup>
                            </div>
                        </div>
                        <div class="row mt-3 profile-page">
                            <div class="col-lg-6">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="info-text mb-2">
                                            </div>

                                            <div class="info-text mb-2">
                                            </div>

                                            <div class="info-text mb-2">
                                            </div>

                                            <div class="info-text mb-2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade edit_profile_modal" id="profile-modal" tabindex="-1" aria-labelledby="profile-modalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="profile-modalLabel">{{ __('Edit Profile') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editProfileForm" method="post" action="{{route('user.updateAccount')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="editProfileBox">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-solid w-100">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script type="text/javascript">


    $("#timezone").change(function(){
        $("#user_timezone_form").submit();
    });
    $("#copy_icon").click(function(){
        var temp = $("<input>");
        var url = $(this).data('url');
        $("body").append(temp);
        temp.val(url).select();
        document.execCommand("copy");
        temp.remove();
        $("#copy_message").text("{{ __('URL Copied!') }}").show();
        setTimeout(function(){
            $("#copy_message").text('').hide();
        }, 3000);
    });
</script>
<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
</script>
<script>
    $(document).ready(function() {
        $("#phone").keypress(function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            return true;
        });
    });
    $(document).delegate('.iti__country', 'click', function() {
        var code = $(this).attr('data-country-code');
        $('#countryData').val(code);
        var dial_code = $(this).attr('data-dial-code');
        $('#dialCode').val(dial_code);
    });
</script>
@endsection
