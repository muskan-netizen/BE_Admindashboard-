@extends('layouts.vertical', ['demo' => 'creative', 'title' => getNomenclatureName('vendors', true)])

@section('css')
    <link href="{{ asset('assets/libs/fullcalendar-list/fullcalendar-list.min.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .pac-container,
        .pac-container .pac-item {
            z-index: 99999 !important;
        }

        .fc-v-event {
            border-color: #43bee1;
            background-color: #43bee1;
        }

        .dd-list .dd3-content {
            position: relative;
        }

        span.inner-div {
            top: 50%;
            -webkit-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        .button {
            position: relative;
            padding: 8px 16px;
            background: #009579;
            border: none;
            outline: none;
            border-radius: 50px;
            cursor: pointer;
        }

        .button:active {
            background: #007a63;
        }

        .button__text {
            font: bold 20px "Quicksand", san-serif;
            color: #ffffff;
            transition: all 0.2s;
        }

        .button--loading .button__text {
            visibility: hidden;
            opacity: 0;
        }

        .button--loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            border: 4px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: button-loading-spinner 1s ease infinite;
        }

        @keyframes button-loading-spinner {
            from {
                transform: rotate(0turn);
            }

            to {
                transform: rotate(1turn);
            }
        }

    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 d-flex align-items-center">
                <div class="page-title-box">
                    <h4 class="page-title">{{ ucfirst($vendor->name) }} {{ __('profile') }}</h4>
                </div>
                <div class="form-group mb-0 ml-3">
                    <div class="site_link position-relative">
                        <a href="{{ route('vendorDetail', $vendor->slug) }}" target="_blank"><span id="pwd_spn"
                                class="password-span">{{ route('vendorDetail', $vendor->slug) }}</span></a>
                        <label class="copy_link float-right" id="cp_btn" title="copy">
                            <img src="{{ asset('assets/icons/domain_copy_icon.svg') }}" alt="">
                            <span class="copied_txt" id="show_copy_msg_on_click_copy"
                                style="display:none;">{{ __('Copied') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                    @endif
                    @if (\Session::has('error_delete'))
                        <div class="alert alert-danger">
                            <span>{!! \Session::get('error_delete') !!}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-xl-3">
                @include('backend.vendor.show-md-3')
            </div>
            <div class="col-lg-9 col-xl-9">
                <div>
                    <ul class="nav nav-pills navtab-bg nav-justified">
                        <li class="nav-item">
                            <a href="{{ route('vendor.catalogs', $vendor->id) }}" aria-expanded="false"
                                class="nav-link {{ $tab == 'catalog' ? 'active' : '' }} {{ $vendor->status == 1 ? '' : 'disabled' }}">
                                {{ __('Catalog') }}
                            </a>
                        </li>
                        @if ($client_preference_detail->business_type != 'taxi')
                            <li class="nav-item">
                                <a href="{{ route('vendor.show', $vendor->id) }}" aria-expanded="false"
                                    class="nav-link {{ $tab == 'configuration' ? 'active' : '' }} {{ $vendor->status == 1 ? '' : 'disabled' }}">
                                    {{ __('Configuration') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('vendor.categories', $vendor->id) }}" aria-expanded="true"
                                    class="nav-link {{ $tab == 'category' ? 'active' : '' }} {{ $vendor->status == 1 ? '' : 'disabled' }}">
                                    {{ __('Categories & Add Ons') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card widget-inline">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                            <div class="text-center">
                                                <h3>
                                                    <i class="mdi mdi-package-variant-closed text-primary mdi-24px"></i>
                                                    <span data-plugin="counterup"
                                                        id="total_earnings_by_vendors">{{ $product_count }}</span>
                                                </h3>
                                                <p class="text-muted font-15 mb-0">{{ __('Total Products') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                            <div class="text-center">
                                                <h3>
                                                    <i class="mdi mdi-package-variant text-primary mdi-24px"></i>
                                                    <span data-plugin="counterup"
                                                        id="total_order_count">{{ $published_products }}</span>
                                                </h3>
                                                <p class="text-muted font-15 mb-0">{{ __('Published Products') }}</p>
                                            </div>
                                        </div>
                                        @if ($client_preference_detail->business_type != 'taxi')
                                            <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                                <div class="text-center">
                                                    <h3>
                                                        <i class="mdi mdi-store-24-hour text-primary mdi-24px"></i>
                                                        <span data-plugin="counterup"
                                                            id="total_cash_to_collected">{{ $last_mile_delivery }}</span>
                                                    </h3>
                                                    <p class="text-muted font-15 mb-0">{{ __('Last Mile Deliverables') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                                <div class="text-center">
                                                    <h3>
                                                        <i class="mdi mdi-new-box text-primary mdi-24px"></i>
                                                        <span data-plugin="counterup"
                                                            id="total_delivery_fees">{{ $new_products }}</span>
                                                    </h3>
                                                    <p class="text-muted font-15 mb-0">{{ __('New Products') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                                <div class="text-center">
                                                    <h3>
                                                        <i class="mdi mdi-diamond text-primary mdi-24px"></i>
                                                        <span data-plugin="counterup"
                                                            id="total_delivery_fees">{{ $featured_products }}</span>
                                                    </h3>
                                                    <p class="text-muted font-15 mb-0">{{ __('Featured Products') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane {{ $tab == 'configuration' ? 'active show' : '' }} card-body"
                            id="configuration"></div>
                        <div class="tab-pane {{ $tab == 'category' ? 'active show' : '' }}" id="category"></div>
                        <div class="tab-pane {{ $tab == 'catalog' ? 'active show' : '' }}" id="catalog">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="mb-0"> {{ __('Catalog') }}</h4>
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-end mb-3">

                                            <a class="btn btn-info  waves-effect waves-light text-sm-right action_product_button" dataid="0"
                                                id="action_product_button" href="javascript:void(0);"
                                                style="display: none;"><i class="mdi mdi-plus-circle mr-1"></i>
                                                {{ __('Action') }}
                                            </a>

                                            <a class="btn btn-info waves-effect waves-light text-sm-right importProductBtn mx-2 {{ $vendor->status == 1 ? '' : 'disabled' }}"
                                                dataid="0" href="javascript:void(0);"
                                                {{ $vendor->status == 1 ? '' : 'disabled' }}><i
                                                    class="mdi mdi-plus-circle mr-1"></i> {{ __('Import') }}
                                            </a>
                                            <a class="btn btn-info waves-effect waves-light text-sm-right addProductBtn {{ $vendor->status == 1 ? '' : 'disabled' }}"
                                                dataid="0" href="javascript:void(0);"><i
                                                    class="mdi mdi-plus-circle mr-1"></i> {{ __('Add Product') }}
                                            </a>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-striped" id="">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" class="all-product_check"
                                                                name="all_product_id" id="all-product_check"></th>
                                                        <th>#</th>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Category') }}</th>
                                                        @if ($client_preference_detail->business_type != 'taxi')
                                                            <th>{{ __('Brand') }}</th>
                                                            <th>{{ __('Quantity') }}</th>
                                                            <th>{{ __('Price') }}</th>
                                                        @endif
                                                        <th>{{ __('Status') }}</th>
                                                        @if ($client_preference_detail->business_type != 'taxi')
                                                            <th>{{ __('New') }}</th>
                                                            <th>{{ __('Featured') }}</th>
                                                            <th>{{ __('Requires Last') }}<br>{{ __('Mile Delivery') }}
                                                            </th>
                                                        @endif

                                                        <th>{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="post_list">
                                                    @foreach ($products as $product)
                                                        <tr data-row-id="{{ $product->id }}">

                                                            <td><input type="checkbox" class="single_product_check"
                                                                    name="product_id[]" id="single_product"
                                                                    value="{{ $product->id }}"></td>
                                                            <td>
                                                                @if (isset($product->media[0]))
                                                                    <img alt="{{ $product->id }}" class="rounded-circle"
                                                                        src="{{ $product->media[0]->image->path['proxy_url'] . '30/30' . $product->media[0]->image->path['image_path'] }}">
                                                                @else
                                                                    {{ $product->sku }}
                                                                @endif
                                                            </td>
                                                            <td> <a href="{{ route('product.edit', $product->id) }}"
                                                                    target="_blank">{{ Str::limit(isset($product->primary->title) && !empty($product->primary->title) ? $product->primary->title : '', 30) }}</a>
                                                            </td>
                                                            <td> {{ $product->category ? $product->category->cat->name : 'N/A' }}
                                                            </td>
                                                            @if ($client_preference_detail->business_type != 'taxi')
                                                                <td> {{ !empty($product->brand) ? $product->brand->title : 'N/A' }}
                                                                </td>
                                                                <td> {{ $product->variant->first() ? $product->variant->first()->quantity : 0 }}
                                                                </td>
                                                                <td> {{ $product->variant->first() ? $product->variant->first()->price : 0 }}
                                                                </td>
                                                            @endif
                                                            <td> {{ $product->is_live == 1 ? 'Published' : 'Draft' }}
                                                            </td>
                                                            @if ($client_preference_detail->business_type != 'taxi')
                                                                <td> {{ $product->is_new == 0 ? 'No' : 'Yes' }}</td>
                                                                <td> {{ $product->is_featured == 0 ? 'No' : 'Yes' }}
                                                                </td>
                                                                <td> {{ $product->Requires_last_mile == 0 ? 'No' : 'Yes' }}
                                                                </td>
                                                            @endif
                                                            <td>
                                                                <div class="form-ul" style="width: 60px;">
                                                                    <div class="inner-div" style="float: left;">
                                                                        <a class="action-icon"
                                                                            href="{{ route('product.edit', $product->id) }}"
                                                                            userId="{{ $product->id }}"><i
                                                                                class="mdi mdi-square-edit-outline"></i></a>
                                                                    </div>
                                                                    <div class="inner-div">
                                                                        <form method="POST"
                                                                            action="{{ route('product.destroy', $product->id) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <div class="form-group">
                                                                                <button type="submit"
                                                                                    onclick="return confirm('Are you sure? You want to delete the product.')"
                                                                                    class="btn btn-primary-outline action-icon"><i
                                                                                        class="mdi mdi-delete"></i></button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
    <div class="row address" id="def" style="display: none;">
        <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
    </div>
    <div id="add-product" class="modal fade add_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">{{ __('Add Product') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="save_product_form" method="post" enctype="multipart/form-data"
                    action="{{ route('product.store') }}">
                    @csrf
                    <div class="modal-body pb-0">
                      
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <div class="form-group" id="product_nameInput">
                                            {!! Form::label('title', __('Product Name'), ['class' => 'control-label']) !!}
                                            <span class="text-danger">*</span>
                                            {!! Form::text('product_name', null, ['class' => 'form-control', 'id' => 'product_name', 'onkeyup' => 'return setSkuFromName(event)', 'placeholder' => 'Apple iMac', 'autocomplete' => 'off']) !!}

                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group" id="skuInput">
                                            {!! Form::label('title', __('SKU'), ['class' => 'control-label']) !!}
                                            <span class="text-danger">*</span>
                                            {!! Form::text('sku', null, ['class' => 'form-control', 'id' => 'sku', 'onkeyup' => 'return alplaNumeric(event)', 'placeholder' => 'Apple-iMac']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                            {!! Form::hidden('type_id', 1) !!}
                                            {!! Form::hidden('vendor_id', $vendor->id) !!}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group" id="url_slugInput">
                                            {!! Form::label('title', __('URL Slug'), ['class' => 'control-label']) !!}
                                            {!! Form::text('url_slug', null, ['class' => 'form-control', 'id' => 'url_slug', 'placeholder' => 'Apple iMac', 'onkeypress' => 'return slugify(event)']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>    

                                    <div class="col-12">
                                        <div class="form-group" id="categoryInput">
                                            {!! Form::label('title', __('Category'),['class' => 'control-label']) !!}
                                        <select class="form-control selectizeInput" id="category_list" name="category">
                                            <option value="">{{ __("Select Category") }}...</option>
                                            @foreach($product_categories as $product_category)
                                                <option value="{{$product_category['id']}}">{{$product_category['hierarchy']}}</option>
                                            @endforeach
                                                
                                            {{--@foreach($product_categories as $product_category)
                                                @if($product_category->category)
                                                    @if( ($product_category->category->type_id == 1) || ($product_category->category->type_id == 3) || ($product_category->category->type_id == 7))
                                                        <option value="{{$product_category->category_id}}">{{(isset($product_category->category->primary->name)) ? $product_category->category->primary->name : $product_category->category->slug}}</option>
                                                    @endif
                                                @endif
                                            @endforeach --}}
                                            </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-info waves-effect waves-light submitProduct">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="import-product" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">{{ __('Add Product') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    <div class="row">


                        <div class="col-md-12 text-center">

                            <div id="import_csv" class="row align-items-center mb-3">
                                <div class="col-12 text-right mb-2">
                                    <button class="btn btn-info button" id="csv_button"
                                        type="button">{{ __('Import form Woocommerce') }}</button>
                                </div>
                                <div class="col-md-12">
                                    <form method="post" enctype="multipart/form-data" id="save_imported_products">
                                        @csrf
                                        <a
                                            href="{{ url('file-download' . '/sample_product.csv') }}">{{ __('Download Sample file here!') }}</a>
                                        <input type="hidden" value="{{ $vendor->id }}" name="vendor_id" />
                                        <input type="file" accept=".csv" onchange="submitProductImportForm()"
                                            data-plugins="dropify" name="product_excel" class="dropify" />
                                    </form>
                                </div>
                            </div>

                            <div id="import_woocommerce" class="row align-items-center mb-3">
                                <div class="col-12 text-right mb-2">
                                    <button class="btn btn-info button" id="woocommerce_button"
                                        type="button">{{ __('Import CSV') }}</button>
                                </div>
                                <div class="col-md-12">
                                    <form id="woocommerces_form">
                                        <div class="form-group">
                                            <input class="form-control" type="url" name="domain_name"
                                                placeholder="Domain Name"
                                                value="{{ $woocommerce_detail ? $woocommerce_detail->url : '' }}">
                                            <span class="text-danger" id="domain_name_error"></span>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="consumer_key"
                                                placeholder="Consumer Key"
                                                value="{{ $woocommerce_detail ? $woocommerce_detail->consumer_key : '' }}">
                                            <span class="text-danger" id="consumer_key_error"></span>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="consumer_secret"
                                                placeholder="Consumer Secret"
                                                value="{{ $woocommerce_detail ? $woocommerce_detail->consumer_secret : '' }}">
                                            <span class="text-danger" id="consumer_secret_error"></span>
                                        </div>
                                        <button class="btn btn-info button" id="save_woocommerce_btn" type="button"
                                            onclick="this.classList.toggle('button--loading')">Save</button>
                                        <button class="btn btn-info button" id="import_product_from_woocomerce"
                                            data-vendor="{{ $vendor->id }}"
                                            onclick="this.classList.toggle('button--loading')">{{ __('Import Products From Woocommerce') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-striped" id="">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('File Name') }}</th>
                                            <th colspan="2">{{ __('Status') }}</th>
                                            <th>{{ __('Link') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="post_list">
                                        @foreach ($csvProducts as $csv)
                                            <tr data-row-id="{{ $csv->id }}">
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $csv->name }}</td>
                                                @if ($csv->status == 1)
                                                    <td>{{ __('Pending') }}</td>
                                                    <td></td>
                                                @elseif($csv->status == 2)
                                                    <td>{{ __('Success') }}</td>
                                                    <td></td>
                                                @else
                                                    <td>{{ __('Errors') }}</td>
                                                    <td class="position-relative text-center">
                                                        <i class="mdi mdi-exclamation-thick"></i>
                                                        <ul class="tooltip_error">
                                                            <?php $error_csv = json_decode($csv->error); ?>
                                                            @foreach ($error_csv as $err)
                                                                <li>
                                                                    {{ $err }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                @endif
                                                <td> <a href="{{ $csv->path }}">{{ __('Download') }}</a> </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- start product action popup -->
    <div id="action-product-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">{{ __('Product Action') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                 
                        <div class="card-box">
                            <form id="save_product_action_modal" method="post" enctype="multipart/form-data"
                            action="{{ route('product.update.action') }}">
                            @csrf

                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    {!! Form::label('title', __('Action For '), ['class' => 'control-label']) !!}
                                    <select class="form-control" id="action_for" name="action_for" required>
                                        <option value="0">{{__('Select')}}</option>
                                        @if ($client_preferences->business_type != 'taxi')
                                         <option value="for_new">{{__('For  New')}}</option>
                                         <option value="for_featured">{{__('For Featured')}}</option>
                                         @endif
                                         @if ($client_preferences->need_delivery_service == 1)
                                         <option value="for_last_mile">{{__('For Requires Last Mile Delivery')}}</option>
                                         @endif
                                         <option value="for_live">{{__('Draft/Published')}}</option>
                                         <option value="for_tax">{{__('Tax Category')}}</option>
                                    </select>
                                </div>
                                
                               

                            </div>

                            <div class="row mb-2">
                                @if ($client_preferences->business_type != 'taxi')
                                    <div class="col-md-6 justify-content-between mb-2" id="for_new" style="display:none;">
                                        {!! Form::label('title', __('New'), ['class' => 'control-label']) !!}
                                        <input type="checkbox" id="is_new" data-plugin="switchery" name="is_new"
                                            class="chk_box" data-color="#43bee1">
                                    </div>
                                      <div class="col-md-6 justify-content-between mb-2"   id="for_featured" style="display:none;">
                                        {!! Form::label('title', __('Featured'), ['class' => 'control-label']) !!}
                                        <input type="checkbox" id="is_featured" data-plugin="switchery" name="is_featured"
                                            class="chk_box" data-color="#43bee1">
                                    </div>
                                @endif
                                @if ($client_preferences->need_delivery_service == 1)
                                     <div class="col-md-6  justify-content-between mb-2"    id="for_last_mile"  style="display:none;">
                                        {!! Form::label('title', __('Requires Last Mile Delivery'), ['class' => 'control-label']) !!}
                                        <input type="checkbox" id="last_mile" data-plugin="switchery" name="last_mile"
                                            class="chk_box" data-color="#43bee1">
                                    </div>
                                @endif

                            </div>
                            <div class="row">
                                  <div class="col-md-6 mb-2"  id="for_live"  style="display: none;">
                                    {!! Form::label('title', __('Live'), ['class' => 'control-label']) !!}
                                    <select class="selectizeInput form-control" id="is_live" name="is_live">
                                        <option value="0">Draft</option>
                                        <option value="1">Published</option>
                                    </select>
                                  </div>

                            
                                <div class="col-md-6 mb-2"  id="for_tax"  style="display: none;">
                                    {!! Form::label('title', __('Tax Category'), ['class' => 'control-label']) !!}
                                    <select class="form-control " id="typeSelectBox" name="tax_category">
                                        <option value="">Select</option>
                                        @foreach ($taxCate as $cate)
                                            <option value="{{ $cate->id }}">{{ $cate->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button"
                                    class="btn btn-info waves-effect waves-light submitProductAction">{{ __('Submit') }}</button>
                            </div>

                            </form>


                        </div>

                </div>

            </div>
        </div>
    </div>
    <!-- end product popup -->
    <script type="text/javascript">
        $(".all-product_check").click(function() {
            if ($(this).is(':checked')) {
                $("#action_product_button").css("display", "block");
                $('.single_product_check').prop('checked', true);
            } else {
                $("#action_product_button").css("display", "none");
                $('.single_product_check').prop('checked', false);
            }
        });

        $(document).on('change', '#action_for', function() {
            var actionfor = $('#action_for').val();
            $("#for_new").css("display", "none");
            $("#for_featured").css("display", "none");
            $("#for_last_mile").css("display", "none");
            $("#for_live").css("display", "none");
            $("#for_tax").css("display", "none");
           
            $("#"+ actionfor).css("display", "block");
        });

        $(document).on('change', '.single_product_check', function() {
            if ($('input:checkbox.single_product_check:checked').length > 0) {
                $("#action_product_button").css("display", "block");
            } else {
                $('.all-product_check').prop('checked', false);
                $("#action_product_button").css("display", "none");
            }
        });

        ////////   *******************  Save product action data ******************* ////////////////////////
        // $('#save_product_action_modal').on('submit', function(e) {
        //     e.preventDefault(); 
        //     var is_new = $('#is_new').val();
        //     var is_featured = $('#is_featured').val();
        //     var is_live = $('#is_live').val();
        //     var tax_category = $('#tax_category').val();
        //     var product_id = [];
        //      $('.single_product_check:checked').each(function(i){
        //         product_id[i] = $(this).val();
        //     });
        //     if (product_id.length == 0) {
               
        //         $("#action-product-modal .close").click();
        //         return;
        //     }
        //     console.log(product_id);
        //     return false;
        //     $.ajax({
        //         type: "POST",
        //         url: '{{route("product.update.action")}}',
        //         data: {_token: CSRF_TOKEN, is_new: is_new, is_featured: is_featured, is_live: is_live, tax_category: tax_category, product_id: product_id},
        //         success: function( msg ) {
        //             location.reload();
        //         }
        //     });
        // });

        $(document).on('click', '.submitProductAction', function(e) {
            var CSRF_TOKEN = $("input[name=_token]").val();
            var is_new = $('#is_new').prop('checked');
            var is_featured = $('#is_featured').prop('checked');
            var is_live = $('#is_live').val();
            var tax_category = $('#tax_category').val();
            var action_for = $('#action_for').val();
            var last_mile = $('#last_mile').prop('checked');
            var product_id = [];
             $('.single_product_check:checked').each(function(i){
                product_id[i] = $(this).val();
            });
            if (product_id.length == 0) {
               
                $("#action-product-modal .close").click();
                return;
            }
            if(action_for == 0){
                return false;
            }
            
            console.log(is_new);
            $.ajax({
                type: "post",
                url: '{{route("product.update.action")}}',
                data: {_token: CSRF_TOKEN,action_for:action_for,last_mile:last_mile, is_new: is_new, is_featured: is_featured, is_live: is_live, tax_category: tax_category, product_id: product_id},
                 success: function(resp) {
                    if (resp.status == 'success') {
                        $.NotificationApp.send("Success", resp.message, "top-right", "#5ba035",
                            "success");
                        location.reload();
                    }
                },
                beforeSend: function() {
                    $(".loader_box").show();
                },
                complete: function() {
                    $(".loader_box").hide();
                },
                error: function(response) {
                    
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text(
                            'Something went wrong, Please try Again.');
                    
                    return response;
                }
            });
        });
        ////////  ****************** End save product action data *****************//////////////////////////

        $('#action_product_button').click(function() {
            $('#action-product-modal').modal({
                keyboard: false
            });
        });

        $('.addProductBtn').click(function() {
            $.ajax({
                type: "get",
                url: "{{route('vendor.specific_categories',$vendor->id)}}",
                success: function(response) {
                    if(response.status == 1){
                        $("#category_list").find('option').remove();
                        $("#category_list").append(response.options);
                        $('#category_list').selectize()[0].selectize.destroy();
                    }
                },
                error:function(error){

                }
            });
            $('#add-product').modal({
                keyboard: false
            });
        });
        $('.importProductBtn').click(function() {
            $('#import-product').modal({
                keyboard: false
            });
        });

        $("#csv_button").click(function() {
            $("#import_woocommerce").show();
            $("#import_csv").hide();
        });

        $("#import_woocommerce").hide();
        $("#woocommerce_button").click(function() {
            $("#import_csv").show();
            $("#import_woocommerce").hide();
        });

        var regexp = /^[a-zA-Z0-9-_]+$/;

        function setSkuFromName() {
            var n1 = $('#product_name').val();
            var sku_start = "{{ $sku_url }}" + ".";
            var total_sku = sku_start + n1;
            $('#sku').val(sku_start + n1);

            if (regexp.test(n1)) {
                var n1 = $('#product_name').val();
                $('#url_slug').val(n1);
                slugify();
            } else {
                $('#sku').val(total_sku.split(' ').join(''));
            }

            alplaNumeric();

        }

        function alplaNumeric() {
            var n1 = $('#sku').val();
            if (regexp.test(n1)) {
                var n1 = $('#sku').val();
                $('#url_slug').val(n1);
                slugify();
            } else {
                $('#sku').val(n1.split(' ').join(''));
            }
            // var charCode = String.fromCharCode(event.which || event.keyCode);
            // if (!regexp.test(charCode)) {
            //     console.log(">>>ne");
            //     return false;
            // }
            // console.log(">>>ne2");
            // var n1 = $('#sku').val();
            // $('#url_slug').val(n1+charCode)

            // return true;
        }

        function slugify() {
            //   var charCode = String.fromCharCode(event.which || event.keyCode);
            //   if (!regexp.test(charCode)) {
            //     return false;
            //   }
            var string = $('#url_slug').val();
            var slug = string.toString().trim().toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "").replace(
                /\-\-+/g, "-").replace(/^-+/, "").replace(/-+$/, "");
            $('#url_slug').val(slug);
        }
        $(document).on('click', '#save_woocommerce_btn', function(e) {
            var that = $(this);
            $('.text-danger').html('');
            that.attr('disabled', true);
            $('#import_product_from_woocomerce').attr('disabled', true);
            var form = document.getElementById('woocommerces_form');
            var formData = new FormData(form);
            $.ajax({
                type: "post",
                url: "{{ route('woocommerce.save') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    that.attr('disabled', false);
                    that.removeClass('button--loading');
                    $('#import_product_from_woocomerce').attr('disabled', false);
                    if (response.status == 'success') {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035",
                            "success");
                    } else {
                        $.NotificationApp.send("Error", response.message, "top-right", "#FF0000",
                            "error");
                    }
                },
                error: function(error) {
                    that.attr('disabled', false);
                    that.removeClass('button--loading');
                    $('#import_product_from_woocomerce').attr('disabled', false);
                    var response = $.parseJSON(error.responseText);
                    let error_messages = response.errors;
                    $.each(error_messages, function(key, error_message) {
                        $('#' + key + '_error').html(error_message[0]).show();
                    });
                }
            });
        });
        $(document).on('click', '#import_product_from_woocomerce', function(e) {
            var that = $(this);
            $('#save_woocommerce_btn').attr('disabled', true);
            that.attr('disabled', true);
            var vendor_id = $(this).data('vendor');
            $.ajax({
                type: "POST",
                data: {
                    vendor_id: vendor_id
                },
                url: "{{ route('product.import.woocommerce') }}",
                dataType: 'json',
                success: function(response) {
                    that.attr('disabled', false);
                    that.removeClass('button--loading');
                    $('#save_woocommerce_btn').attr('disabled', false);
                    if (response.status == 'success') {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035",
                            "success");
                    } else {
                        $.NotificationApp.send("Error", response.message, "top-right", "#FF0000",
                            "error");
                    }
                }
            });
        });
        $(document).on('click', '.submitProduct', function(e) {
            var form = document.getElementById('save_product_form');
            var formData = new FormData(form);
            $.ajax({
                type: "post",
                url: "{{ route('product.validate') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(resp) {
                    if (resp.status == 'success') {
                        $('#save_product_form').submit();
                    }
                },
                beforeSend: function() {
                    $(".loader_box").show();
                },
                complete: function() {
                    $(".loader_box").hide();
                },
                error: function(response) {
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            if (key == 'category.0') {
                                $("#categoryInput input").addClass("is-invalid");
                                $("#categoryInput span.invalid-feedback").children("strong")
                                    .text('The category field is required.');
                                $("#categoryInput span.invalid-feedback").show();
                            } else {
                                $("#" + key + "Input input").addClass("is-invalid");
                                $("#" + key + "Input span.invalid-feedback").children("strong")
                                    .text(errors[key][0]);
                                $("#" + key + "Input span.invalid-feedback").show();
                            }
                        });
                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text(
                            'Something went wrong, Please try Again.');
                    }
                    return response;
                }
            });
        });
    </script>
    @include('backend.vendor.modals')
@endsection
@section('script')
    @include('backend.vendor.pagescript')
    <script>
        $(document).on('click', '.copy_link', function() {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($('#pwd_spn').text()).select();
            document.execCommand("copy");
            $temp.remove();
            $("#show_copy_msg_on_click_copy").show();
            setTimeout(function() {
                $("#show_copy_msg_on_click_copy").hide();
            }, 1000);
        })
    </script>
@endsection
