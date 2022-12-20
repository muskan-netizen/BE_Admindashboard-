@extends('layouts.store', ['title' => 'Bid Requests'])
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
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>{{ __('Place a Bid Request') }}</h2>
                        </div>
                        <div class="card-box">
                            <div class="row">
                                <div class="col-6 align-items-center border-right">
                                    <div class="page-title">
                                        <h5 class="mb-3">{{ __('Upload Prescription To Place a Bid') }}</h5>
                                    </div>
                                    <form class="" action="{{ route('bid.uploadPrescription') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card ">
                                            <div class="dropify-wrapper report-upload-subt w-100">
                                                <div class="dropify-loader"></div>
                                                <div class="dropify-errors-container">
                                                    <ul></ul>
                                                </div>
                                                <input required type="file" accept="image/*,.pdf,.doc" data-plugins="dropify" name="prescriptions" class="dropify" data-default-file="">
                                                <button type="button" class="dropify-clear">Remove</button>
                                                <div class="dropify-preview">
                                                    <span class="dropify-render"></span>
                                                    <div class="dropify-infos">
                                                        <div class="dropify-infos-inner">
                                                            <p class="dropify-filename">
                                                                <span class="file-icon"></span>
                                                                <span class="dropify-filename-inner"></span>
                                                            </p>
                                                            <p class="dropify-infos-message">Drag and drop or click to
                                                                replace</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="w-50 mt-3 btn btn-info waves-effect waves-light mt-2">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <div class="col-md-12">
                                        <div class="page-title">
                                            <h4>{{ __('Prescriptions') }}</h4>
                                        </div>
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
                                                    @foreach ($prescriptions as $prescription)
                                                    <tr>
                                                        <td>{{$loop->iterations}}</td>
                                                        <td>{{$pescription->prescription}}</td>
                                                        <td>{{$pescription->createdAt}}</td>

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
