@extends('layouts.store', ['title' => 'Add Post'])

@section('css')
    <link href="{{ asset('assets/libs/multiselect/multiselect.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/libs/jquery-toast-plugin/jquery-toast-plugin.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .alRentalSinglePageView .single_product-input input {
            width: 48%;
            display: inline-block;
            border: none;
            height: auto;
            padding: 30px 0px 10px 4px;
            font-size: 13px;
        }

        .alRentalSinglePageView .single_product-input {
            border: 1px solid#cfc9c9;
            width: 56%;
            border-radius: 5px;
            position: relative;
        }

        .alRentalSinglePageView .single_cart-temp_label {
            width: 56%;
            position: absolute;
            z-index: 1;
        }

        .alRentalSinglePageView .single_cart-temp_label label {
            display: inline-block;
            width: 46%;
            font-size: 12px;
            padding: 6px 0px 0px 6px;
            color: #000;
        }

        .alRentalSinglePageView .single_product-input input:nth-child(1) {
            border-right: 1px solid#cfc9c9;
            border: 1px solid#cfc9c9;
            border-top: none;
            border-bottom: none;
            border-left: none;
        }

        .disclaimer {
            font-style: italic;
        }

        .cate-item img {
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .cate-item h3 {
            font-style: normal;
            font-weight: 600;
            font-size: 16px;
            line-height: 22px;
            color: #222;
            margin-top: 10px;
            margin-bottom: 0;
        }

        a.backArroww.position-absolute {
            left: 10px;
            color: #000;
        }

        .alPostBoxOuter ul {
            /*border: 1px solid rgba(14,4,5,.2);*/
            position: relative;
        }

        .alPostBoxOuter ul li {
            list-style: none;
        }

        /* .alPostBoxOuter ul li a {
            list-style: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 400;
            display: block;
            align-items: center;
            line-height: 2;
            color: rgba(0, 47, 52, .64);
            border: 1px solid rgba(14, 4, 5, .2);
            padding: 10px;
            height: 135px;
        } */

        .alPostBoxOuter ul li a:hover {
            text-decoration: none;
            background: linear-gradient(180deg, #e1dfdf 0%, #efe7e7 100%);
        }

        .alPostBoxOuter a:hover {
            text-decoration: none;
        }

        .alPostBoxOuter a {
            color: #777
        }

        .alPostItemsData {
            width: 100%;
            min-height: 1px;
            box-sizing: border-box;
        }

        .alPostItemsData label {
            color: #002f34;
            display: block;
            font-size: 14px;
            line-height: 16px;
            margin: 8px;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .alPostItemsData .alInput {
            appearance: none;
            color: #002f34;
            display: block;
            font-size: 16px;
            height: 48px;
            box-sizing: border-box;
            outline: none;
            padding-left: 12px;
            padding-right: 12px;
            width: 100%;
            background: #fff;
            box-shadow: inset 0 0 0 1px rgb(0 47 52 / 64%);
        }

        .dark .alPostItemsData label,
        .dark .cate-item h3 {
            color: #fff;
        }

        .dark .form-control,
        .dark .btn,
        .dark .custom-select {
            background-color: #2b2b2b;
            color: #fff;
            border-color: #444242 !important
        }

        body.dark {
            background-color: #232323;
            color: #eee
        }

        .dark .alPostBoxOuter ul,
        .dark .alPostBoxOuter ul li a,
        .dark .border {
            border-color: #444242 !important;
        }

        .dark a.backArroww.position-absolute,
        .dark .alPostBoxOuter a {
            color: #fff;
        }

        .dark .alPostBoxOuter ul li a:hover {
            background: #2b2b2b !important;
        }

        .dark .bg-light {
            background-color: #2b2b2b !important;
        }

        .dark select {
            background-color: #2b2b2b !important
        }

        .alCategoryItemsHead a {
            color: #f00;
            font-size: 12px;
        }

        body.al_body_template_four.p2p-module form input.form-control {
            width: 100%;
            margin: 0;
        }

        body.al_body_template_four.p2p-module .input-group.mb-2 input {
            width: 90%;
        }

        .checkbox.checkbox-success {
            align-items: center;
            justify-content: flex-start;
            width: 30%;
        }

        .checkbox.checkbox-success input {
            width: auto !important;
            height: 20px;
        }

        .alPostBoxOuter.offset-md-2.col-md-8.mt-4.border.border-rounded.px-0 {
            margin-bottom: 0px;
        }

        .select2-results__option {
            display: block;
        }

        body.al_body_template_nine .alPostBoxOuter ul li a.active {
            background: #efe7e7;
            border: 1px solid #ccc;
            position: relative;
        }


        body.al_body_template_nine .alPostBoxOuter ul li a.active h3 {
            color: #000;
        }

        .form_top {
            border-bottom: 0;
        }

        .alPostHead h3 {
            font-size: 35px;
            font-weight: 600;
            color: #000;
            position: relative;
            display: inline-block;
            padding-bottom: 20px;
        }

        .alPostHead h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            width: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #000;
            margin: 0 auto;
        }

        .Register_form {
            margin-top: 40px;
        }

        .start_form_register {
            /* box-shadow: 0 0 10px #ccc;
        background: #fff; */
        }

        .start_form_register .item {
            padding: 10px;
            border: 1px solid #ccc;
            width: 95%;
            margin: 30px AUTO;
            background: #fff;
            box-shadow: 0 0 10px #ccc;
            border-radius: 14px;
        }

        .start_form_register .alPostHead {
            background: #fff !important;
        }

        .start_form_register .alPostBoxOuter h6 {
            font-size: 18px;
            font-weight: 600;
            color: #000;
        }

        .start_form_register textarea.form-control {
            resize: none;
            height: 150px;
            background: #f5f5f570;
        }

        body.al_body_template_nine.p2p-module form input.form-control {

            background: #f5f5f570;
        }

        .start_form_register .alPostItemsData h5 {
            font-weight: 600;
            text-transform: capitalize !important;
            letter-spacing: 0;
            font-size: 18px;
            margin-bottom: 0;
            margin-top: 0;
        }

        .start_form_register .alPostItemsData .input-group {
            flex-wrap: nowrap !important;
        }

        .start_form_register .form-group.choose_file {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
            background: #f5f5f570;
        }

        .dark .start_form_register .item,
        .dark .start_form_register .alPostHead {
            background: #fff0 !important;
        }

        .dark .start_form_register .item {
            background: #fff0 !important;
            box-shadow: 0 0 10px #000;
        }

        .dark .alPostHead h3,
        .dark .start_form_register .alPostBoxOuter h6 {
            color: #fff;
        }

        .dark .alPostHead h3:after {
            background: #fff;
        }

        body.al_body_template_nine.p2p-module.dark form input.form-control,
        .dark .start_form_register textarea.form-control,
        .dark .start_form_register .form-group.choose_file {
            background: transparent;
        }
        .cate-item img {
    width: 120px;
    height: 120px;
    padding: 3px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin: 0 auto 0px;
}
.alPostBoxOuter ul li {
    list-style: none;
    margin-bottom: 31px;
    width: 16%;
}
.alPostBoxOuter ul {
    /* border: 1px solid rgba(14,4,5,.2); */
    position: relative;
    display: flex;
    flex-wrap: wrap;
}
span.category-type-badge.d-block {
    display: none!important;
}
    </style>
@endsection

@section('content')
    <div class="wrapper Register_form">
        <div class="container">
            <div class="start_form_register ">
                <div class="item">
                    <div class="alPostHead text-center bg-light position-relative py-3">
                        <!-- <a href="#" class="backArroww position-absolute"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/></svg></a> -->
                        <h3>Post Your Ad</h3>
                    </div>


                    <div class=" row">
                        <div class="alPostBoxOuter form_top  col-md-11 mx-auto mt-2   ">
                            <div class="px-3">
                                <div class="d-flex mb-2 align-items-center justify-content-between alCategoryItemsHead">
                                    <h6 class="m-0">CHOOSE A CATEGORY </h6>
                                </div>
                                <ul class=" p-0 m-0 no-gutters">
                                    @if (@$categories)
                                        @foreach ($categories as $key => $category)
                                            @php  $icon = $category['icon']['proxy_url'] . '200/200' . $category['icon']['image_path'];  @endphp
                                            <li class=" px-1 category-list @if ($key > 3) view-all_cats @endif"
                                                id="category_{{ $category->id }}"
                                                @if ($key > 3) style="display:none;" @endif>
                                                <a class="cate-item text-center w-100 py-3 mb-0 rounded select-category"
                                                    data-name="{{ $category['translation_one']['name'] }}"
                                                    data-id="{{ $category['id'] }}" data-type-id="{{$category->type_id}}" href="#">
                                                    <div class="alCategoryItems">
                                                        <img class="" src="{{ $icon }}">
                                                        <span class="category-type-badge d-block">
                                                            {{ $category->type_id == '10' ? 'Rental' : 'Sell' }}
                                                        </span>                                                                                                                 
                                                        <h3>{{ $category['translation_one']['name'] }}</h3>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                        <li class="col-3 px-1 choose-category" style="display:none;">
                                            <a class="cate-item text-center w-100 py-3 mb-4 rounded select-category"
                                                href="#">
                                                <div class="alCategoryItems">
                                                    <h3>Choose Another Category</h3>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                                <label class="cat-error text-danger mt-2 pl-1 d-none">Please select category.</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="alPostBoxOuter col-md-11 mx-auto mb-4 p2p-category-form d-none">
                            <div class="p-3">
                                <form action="{{ route('posts.addProductWithAttribute') }}" enctype="multipart/form-data"
                                    method="post" id="product_form">
                                    @csrf
                                    <h6 class="pb-0">SELECTED CATEGORY</h6>
                                    <input type="hidden" name="category_id" id="category_id" required />
                                    <nav aria-label="breadcrumb"
                                        class="d-flex justify-content-between align-items-center mb-2">
                                        <ol class="breadcrumb bg-transparent p-0 m-0">
                                            <li class="breadcrumb-item selected-category"></li>
                                            {{-- <li class="breadcrumb-item active" aria-current="page">Motors</li>                             --}}
                                        </ol>
                                        {{-- <ol class="breadcrumb bg-transparent p-0 m-0 alCategoryItemsHead">
                                        <li class="breadcrumb-item"><a href="">Back</a></li>
                                    </ol> --}}
                                    </nav>
                                    <div class="col-12 border py-2 px-3">
                                        <div class="row alPostItemsDataOuter  ">
                                            <div class="alPostItemsData">
                                                <h5 class="text-uppercase my-2">Include some details</h5>
                                                <div class="form-group">
                                                    <label>Title *</label>
                                                    <input type="text" class="form-control" name="product_name" required
                                                        id="" aria-describedby="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Description *</label>
                                                    <textarea class="form-control" id="" name="product_description" required rows="3"></textarea>
                                                </div>
                                                {{-- <div class="form-group">
                                                    <label>Emirate *</label>
                                                    <input type="text" class="form-control" name="emirate" required
                                                        id="" aria-describedby="">
                                                </div> --}}
                                                <div class="form-group">
                                                    <label for="inputAddress">Location Avialability *</label>
                                                    <input type="hidden" name="lat" id="latitude" value="">
                                                    <input type="hidden" name="long" id="longitude" value="">
                                                    <input type="text" name="address" class="form-control" id="address"
                                                        placeholder="{{ __('Address') }}"aria-label="Recipient's Address"
                                                        aria-describedby="button-addon2" value=""
                                                        autocomplete="off" required="required">
                                                </div>
                                                <div class="rental-cat-fields d-none">
                                                    <div class="form-group">
                                                        <label>Pricing Detail For *</label>
                                                        <div class="input-group mb-2">
                                                            <div class="row">
                                                                <div class="col input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        {{ getPrimaryCurrencySymbol() }}
                                                                    </div>
                                                                    <input type="text" class="form-control" required
                                                                        name="price" id="day_price" placeholder="Day">
                                                                </div>
                                                                <div class="col input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        {{ getPrimaryCurrencySymbol() }}
                                                                    </div>
                                                                    <input type="text" class="form-control" required
                                                                        name="week_price" id="week_price" placeholder="Week"
                                                                        readonly>
                                                                </div>
                                                                <div class="col input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        {{ getPrimaryCurrencySymbol() }}
                                                                    </div>
                                                                    <input type="text" class="form-control" required
                                                                        name="month_price" id="month_price"
                                                                        placeholder="Month" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Original Price of Item *</label>
                                                        <div class="input-group mb-2">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">{{ getPrimaryCurrencySymbol() }}
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" required
                                                                name="compare_at_price" id="" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Date Range *</label>
                                                        <input type="text" class="form-control" name="date_availability"
                                                            value="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="alPostItemsData" id="productAttributes"></div>
                                        </div>
                                        {{-- <div class="row alPostItemsDataOuter border-top">
                                        <div class="alPostItemsData">
                                            <h5 class="text-uppercase py-3">SET A PRICE</h5>
                                            <div class="form-group">
                                                <label>Price *</label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">{{getPrimaryCurrencySymbol()}}</div>
                                                    </div>
                                                    <input type="text" class="form-control" required name="price" id="" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                        <div class="row alPostItemsDataOuter ">
                                            <div class="alPostItemsData">
                                                <h5 class="text-uppercase py-3">Upload up to 20 photos</h5>
                                                <div class="form-group choose_file">
                                                    {{-- <input type="file" accept="image/*"   data-plugins="dropify" name="images[]" class="dropify ss_form_submit" id="image" multiple /> --}}
                                                    <input type="file" class="form-control-file" required
                                                        name="file[]" accept="image/png, image/gif, image/jpeg"
                                                        id=" " multiple>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="row alPostItemsDataOuter border-top">
                                        <div class="alPostItemsData">
                                            <h5 class="text-uppercase py-3">Confirm your location</h5>
                                            <div class="form-group">
                                                <label for="inputAddress">Address</label>
                                                <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="inputCity">City</label>
                                                    <input type="text" class="form-control" id="inputCity">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="inputState">State</label>
                                                    <select id="inputState" class="form-control">
                                                        <option selected>Choose...</option>
                                                        <option>...</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="inputZip">Zip</label>
                                                    <input type="text" class="form-control" id="inputZip">
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                        <div class="row alPostItemsDataOuter">
                                            <div class="alPostItemsData mt-4">
                                                <button type="submit" class="btn btn-outline-secondary btn-lg" id="save-post">Post Now</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-toast-plugin/jquery-toast-plugin.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/toastr.init.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).on('click', '#view-all_cats', function() {
            $('#view-all_cats').hide();
            $('.view-all_cats').show();

        });
        $(document).on('click', '.category-list', function() {
            $('.category-list').hide();
            $('.category-list').find('.select-category').removeClass('active');
            $(this).find('.select-category').addClass('active');
            $(this).show();
            $('.choose-category').show();
        });
        $(document).on('click', '.choose-category', function() {
            $('.category-list').show();
            $('.choose-category').hide();
        });

        $('.dropify').dropify();
        $(document).on('click', '.select-category', function() {
            var category_id = $(this).data('id');
            $("#category_id").val(category_id);
            $(".selected-category").text($(this).data('name'));
            var type_id = $(this).data('type-id');
            $(".p2p-category-form").removeClass('d-none');
            if(type_id == '10'){
                $(".rental-cat-fields").removeClass('d-none');
            }else{
                $(".rental-cat-fields").addClass('d-none');
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            $.ajax({
                url: "{{ route('category.attributes') }}",
                type: "GET",
                data: {
                    category_id: category_id
                },
                success: function(response) {
                    if (response.success) {
                        $("#productAttributes").html(response.html);
                    }
                },
            });
        });

        function checkAddressString(obj, name) {
            if ($(obj).val() == "") {
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
            }
        }

        //calender and day_price

        $(document).on('keyup', '#day_price', function() {
            var dayPrice = $('#day_price').val();
            var weekPrice = (dayPrice * 4) / 7;
            var monthPrice = (dayPrice * 4 * 3) / 30;
            $('#week_price').val(weekPrice.toFixed(2));
            $('#month_price').val(monthPrice.toFixed(2));
        });

        $(function() {
            var date = new Date();
            var currentMonth = date.getMonth();
            var currentDate = date.getDate();
            var currentYear = date.getFullYear();
            $('input[name="date_availability"]').daterangepicker({
                minDate: new Date(currentYear, currentMonth, currentDate),
                dateFormat: 'yy-mm-dd',
                //startDate: moment(date).add(1,'days'),
                // endDate: moment(date).add(2,'days'),
                locale: {
                    format: 'DD.MM.YYYY'
                }
            });
        });

        var form = document.getElementById("product_form");
        document.getElementById("save-post").addEventListener("click", function (e) {
            e.preventDefault();
            const elements = document.querySelectorAll('.select-category.active');
            const hasElements = elements.length > 0;
            if (hasElements) {
                $('.cat-error').addClass('d-none');
                form.submit();
            } else {
                $('.cat-error').removeClass('d-none');
            }
        });
    </script>

    <?php
    // dd(Session::get('toaster'));
    if (Session::has('toaster')) {
        $toast = Session::get('toaster');
        echo '<script>
                $(document).ready(function(){
                    $.NotificationApp.send("' .
            $toast['title'] .
            '", "' .
            $toast['body'] .
            '", "top-right", "' .
            $toast['color'] .
            '", "' .
            $toast['type'] .
            '");
                });
            </script>';
    }
    ?>
@endsection