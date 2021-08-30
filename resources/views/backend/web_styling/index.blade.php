@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Styling - Web Styling'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css">
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Web Styling</h4>
        </div>
    </div>
</div>

<form id="favicon-form" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card card-box">
                <div class="row">
                    <div class="col-5">
                        <h4 class="header-title">Favicon</h4>
                        <div class="mb-0">
                            <label>Upload Favicon</label>
                            <input type="file" accept="image/*" data-default-file="{{$client_preferences->favicon ? $client_preferences->favicon['proxy_url'].'600/400'.$client_preferences->favicon['image_path'] : ''}}" data-plugins="dropify" name="favicon" class="dropify" id="image" />
                            <label class="logo-size d-block text-right mt-1">Icon Size 32x32</label>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-7">
                        <h4 class="header-title">Color</h4>
                        <div class="form-group">
                            <label for="primary_color">Primary Color</label>
                            <input type="text" id="primary_color_option" name="primary_color" class="form-control" value="{{ old('primary_color', $client_preferences->web_color ?? 'cccccc')}}">
                        </div>
                        <div class="form-group mb-0">
                            <label>Top Header Color</label>
                            <input type="text" id="site_top_header_color" name="site_top_header_color" class="form-control" value="{{ old('site_top_header_color', $client_preferences->site_top_header_color ?? '#4c4c4c')}}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-box">
                <ul class="pl-0 mb-0">
                    <li class="d-flex flex-column justify-content-start mt-2">
                        <h4 class="header-title mb-2">Show Dark Mode</h4>
                        <div class="form-group">
                            <ul class="list-inline">
                                <li class="d-inline-block ml-3 mr-2">
                                    <input type="radio" class="custom-control-input check" onchange="submitDarkMmode('0')" id="option1" name="show_dark_mode" {{$client_preferences->show_dark_mode == 0 ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="option1">Day</label>
                                </li>
                                <li class="d-inline-block ml-3 mr-2 mb-2 mb-lg-0">
                                    <input type="radio" class="custom-control-input check" onchange="submitDarkMmode('1')" id="option2" name="show_dark_mode" {{$client_preferences->show_dark_mode == 1 ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="option2">Night</label>
                                </li>
                                <li class="d-inline-block ml-3">
                                    <input type="radio" class="custom-control-input check" onchange="submitDarkMmode('2')" id="option3" name="show_dark_mode" {{$client_preferences->show_dark_mode == 2 ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="option3">Day with Toggle</label>
                                </li>
                            </ul>
                        </div>
                        <!-- <div class="mb-0">
                            <input type="checkbox" id="show_dark_mode" data-plugin="switchery" name="show_dark_mode" class="chk_box2" data-color="#43bee1" {{$client_preferences->show_dark_mode == 1 ? 'checked' : ''}}>
                        </div> -->
                    </li>
                </ul>
            </div>
        </div>        

        <div class="col-md-6 col-xl-3">
            <div class="card card-box">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="header-title mb-0">Age Restriction Popup</h4>
                    <div class="mb-0">
                        <input type="checkbox" id="age_restriction" data-plugin="switchery" name="age_restriction" class="chk_box1" data-color="#43bee1" {{$client_preferences->age_restriction == 1 ? 'checked' : ''}}>
                    </div>
                </div>
                <label for="">Title</label>
                <input type="text" class="form-control" id="age_restriction_title" name="age_restriction_title" value="{{ old('age_restriction_title', $client_preferences->age_restriction_title ?? '')}}">
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card card-box">
                <ul class="pl-0 mb-0">
                    <li class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title mb-2">Show Wishlist Icon</h4>
                        <div class="mb-0">
                            <input type="checkbox" id="show_wishlist" data-plugin="switchery" name="show_wishlist" class="chk_box2" data-color="#43bee1" {{$client_preferences->show_wishlist == 1 ? 'checked' : ''}}>
                        </div>
                    </li>
                    <li class="d-flex align-items-center justify-content-between mt-2">
                        <h4 class="header-title mb-2">Show Ratings</h4>
                        <div class="mb-0">
                            <input type="checkbox" id="rating_enable" data-plugin="switchery" name="rating_enable" class="chk_box2" data-color="#43bee1" {{$client_preferences->rating_check == 1 ? 'checked' : ''}}>
                        </div>
                    </li>
                    <li class="d-flex align-items-center justify-content-between mt-2">
                        <h4 class="header-title mb-2">Show Cart Icon</h4>
                        <div class="mb-0">
                            <input type="checkbox" id="cart_enable" data-plugin="switchery" name="cart_enable" class="chk_box1" data-color="#43bee1" {{$client_preferences->cart_enable == 1 ? 'checked' : ''}}>
                        </div>
                    </li>
                    <li class="d-flex align-items-center justify-content-between mt-2">
                        <h4 class="header-title mb-2">Show Contact Us</h4>
                        <div class="mb-0">
                            <input type="checkbox" id="show_contact_us" data-plugin="switchery" name="show_contact_us" class="chk_box2" data-color="#43bee1" {{$client_preferences->show_contact_us == 1 ? 'checked' : ''}}>
                        </div>
                    </li>
                    <li class="d-flex align-items-center justify-content-between mt-2">
                        <h4 class="header-title mb-2">Show Icons in navigation</h4>
                        <div class="mb-0">
                            <input type="checkbox" id="show_icons" data-plugin="switchery" name="show_icons" class="chk_box2" data-color="#43bee1" {{$client_preferences->show_icons == 1 ? 'checked' : ''}}>
                        </div>
                    </li>
                    <li class="d-flex align-items-center justify-content-between mt-2">
                        <h4 class="header-title mb-2">Show Payment Icons</h4>
                        <div class="mb-0">
                            <input type="checkbox" id="show_payment_icons" data-plugin="switchery" name="show_payment_icons" class="chk_box2" data-color="#43bee1" {{$client_preferences->show_payment_icons == 1 ? 'checked' : ''}}>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>   

    <div class="row">
        <div class="col-xl-8">
            <div class="card-box home-options-list">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title mt-0">Home Page</h4>
                        <p class="sub-header">
                            Drag & drop to edit different sections.
                        </p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right" id="save_home_page">Save</button>
                    </div>
                </div>

                <div class="custom-dd-empty dd" id="homepage_datatable">
                    <ol class="dd-list p-0" id="homepage_ol">
                        @foreach($home_page_labels as $home_page_label)
                        <li class="dd-item dd3-item d-flex align-items-center" data-id="1" data-row-id="{{$home_page_label->id}}">
                            <a herf="#" class="dd-handle dd3-handle d-block mr-auto">
                                @if($home_page_label->slug == "vendors")
                                {{getNomenclatureName('Vendors', true)}}
                                @else
                                {{$home_page_label->title}}
                                @endif
                            </a>
                            <div class="language-inputs style-4">
                                <div class="row no-gutters flex-nowrap align-items-center my-2">
                                    @foreach($langs as $lang)
                                    @php
                                    $exist = 0;
                                    $value = '';
                                    @endphp
                                    <div class="col-3 pl-1">
                                        <input class="form-control" type="hidden" value="{{$home_page_label->id}}" name="home_labels[]">
                                        <input class="form-control" type="hidden" value="{{$lang->langId}}" name="languages[]">
                                        @foreach($home_page_label->translations as $translation)
                                        @if($translation->language_id == $lang->langId)
                                        @php
                                        $exist = 1;
                                        $value = $translation->title;
                                        @endphp
                                        @endif
                                        @endforeach
                                        <input class="form-control" value="{{$exist == 1 ? $value : '' }}" type="text" name="names[]" placeholder="{{ $lang->langName }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-0 ml-3">
                                <input type="checkbox" {{$home_page_label->is_active == 1 ? 'checked' : ''}} id="{{$home_page_label->slug}}" data-plugin="switchery" name="{{$home_page_label->slug}}" class="chk_box2" data-color="#43bee1">
                            </div>
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('script')
<script src="{{asset('assets/js/jscolor.js')}}"></script>
<script type="text/javascript">
    var options = {
        zIndex: 9999
    }
    $(document).ready(function() {
        var color1 = new jscolor('#primary_color_option', options);
    });
    
    $(document).ready(function() {
        var color1 = new jscolor('#site_top_header_color', options);
    });

    $("#primary_color_option, #site_top_header_color").change(function() {
        submitData();
    });

    $("#show_contact_us").change(function() {
        submitData();
    });
    $("#show_icons").change(function() {
        submitData();
    });
    $("#show_wishlist").change(function() {
        submitData();
    });
    $("#show_payment_icons").change(function() {
        submitData();
    });
    $("#save_home_page").click(function(event) {
        event.preventDefault();
        submitData();
    });
    $("#cart_enable").change(function() {
        submitData();
    });
    $("#rating_enable").change(function() {
        submitData();
    });
    $("#age_restriction").change(function() {
        submitData();
    });
    $("#age_restriction_title").keyup(function() {
        submitData();
    });
    $("#image").change(function() {
        submitData();
    });

    function submitDarkMmode(id) {
        var data_uri = "{{route('styling.updateDarkMode')}}";
        console.log(id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                show_dark_mode: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    }

    function submitData() {
        var form = document.getElementById('favicon-form');
        var formData = new FormData(form);
        var data_uri = "{{route('styling.updateWebStyles')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            url: data_uri,
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                Accept: "application/json"
            },
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                    var r = document.querySelector(':root');
                    r.style.setProperty('--theme-deafult', 'lightblue');
                }
            }
        });
    }
    $("#homepage_datatable ol").sortable({
        placeholder: "ui-state-highlight",
        update: function(event, ui) {
            var post_order_ids = new Array();
            $('#homepage_ol li').each(function() {
                post_order_ids.push($(this).data("row-id"));
            });
            console.log(post_order_ids);
            saveOrder(post_order_ids);
        }
    });

    function saveOrder(orderVal) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ url('client/homepagelabel/saveOrder') }}",
            data: {
                order: orderVal
            },
            success: function(response) {
                if (response.status == 'success') {
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            },
        });
    }
</script>
@endsection