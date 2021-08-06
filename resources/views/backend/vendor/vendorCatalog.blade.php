@extends('layouts.vertical', ['demo' => 'creative', 'title' => getNomenclatureName('vendors', true)])

@section('css')
<link href="{{asset('assets/libs/fullcalendar-list/fullcalendar-list.min.css')}}" rel="stylesheet" type="text/css" />
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ucfirst($vendor->name)}} profile</h4>
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
                        <a href="{{ route('vendor.catalogs', $vendor->id) }}" aria-expanded="false" class="nav-link {{($tab == 'catalog') ? 'active' : '' }} {{$vendor->status == 1 ? '' : 'disabled'}}">
                            Catalog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('vendor.show', $vendor->id) }}" aria-expanded="false" class="nav-link {{($tab == 'configuration') ? 'active' : '' }} {{$vendor->status == 1 ? '' : 'disabled'}}">
                            Configuration
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('vendor.categories', $vendor->id) }}" aria-expanded="true" class="nav-link {{($tab == 'category') ? 'active' : '' }} {{$vendor->status == 1 ? '' : 'disabled'}}">
                            Categories & Add Ons
                        </a>
                    </li>
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
                                                <span data-plugin="counterup" id="total_earnings_by_vendors">{{$product_count}}</span>
                                            </h3>
                                            <p class="text-muted font-15 mb-0">Total Products</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                        <div class="text-center">
                                            <h3>
                                                <i class="mdi mdi-package-variant text-primary mdi-24px"></i>
                                                <span data-plugin="counterup" id="total_order_count">{{$published_products}}</span>
                                            </h3>
                                            <p class="text-muted font-15 mb-0">Published Products</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                        <div class="text-center">
                                            <h3>
                                                <i class="mdi mdi-store-24-hour text-primary mdi-24px"></i>
                                                <span data-plugin="counterup" id="total_cash_to_collected">{{$last_mile_delivery}}</span>
                                            </h3>
                                            <p class="text-muted font-15 mb-0">Last Mile Deliverables</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                        <div class="text-center">
                                            <h3>
                                                <i class="mdi mdi-new-box text-primary mdi-24px"></i>
                                                <span data-plugin="counterup" id="total_delivery_fees">{{$new_products}}</span>
                                            </h3>
                                            <p class="text-muted font-15 mb-0">New Products</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                        <div class="text-center">
                                            <h3>
                                                <i class="mdi mdi-diamond text-primary mdi-24px"></i>
                                                <span data-plugin="counterup" id="total_delivery_fees">{{$featured_products}}</span>
                                            </h3>
                                            <p class="text-muted font-15 mb-0">Featured Products</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane {{($tab == 'configuration') ? 'active show' : '' }} card-body" id="configuration"></div>
                    <div class="tab-pane {{($tab == 'category') ? 'active show' : '' }}" id="category"></div>
                    <div class="tab-pane {{($tab == 'catalog') ? 'active show' : '' }}" id="catalog">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-12 d-flex align-items-center justify-content-between mb-3">
                                    <h4 class="mb-0"> Catalog</h4>
                                    <div class="">
                                        <a class="btn btn-info waves-effect waves-light text-sm-right importProductBtn {{$vendor->status == 1 ? '' : 'disabled'}}" dataid="0" href="javascript:void(0);" {{$vendor->status == 1 ? '' : 'disabled'}}><i class="mdi mdi-plus-circle mr-1"></i> Import
                                        </a>
                                        <a class="btn btn-info waves-effect waves-light text-sm-right addProductBtn {{$vendor->status == 1 ? '' : 'disabled'}}" dataid="0" href="javascript:void(0);"><i class="mdi mdi-plus-circle mr-1"></i> Add Product</a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-nowrap table-striped" id="">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Category</th>
                                                    <th>Brand</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Status</th>
                                                    <th>New</th>
                                                    <th>Featured</th>
                                                    <th>Requires Last<br>Mile Delivery</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="post_list">
                                                @foreach($products as $product)
                                                <tr data-row-id="{{$product->id}}">
                                                    <td>
                                                        @if(isset($product->media[0]))
                                                        <img alt="{{$product->id}}" class="rounded-circle" src="{{$product->media[0]->image->path['proxy_url'].'30/30'.$product->media[0]->image->path['image_path']}}">
                                                        @else
                                                        {{ $product->sku }}
                                                        @endif
                                                    </td>
                                                    <td> <a href="{{ route('product.edit', $product->id) }}" target="_blank">{{ Str::limit((isset($product->primary->title) && !empty($product->primary->title)) ? $product->primary->title : '' , 30)}}</a> </td>
                                                    <td> {{ $product->category ? $product->category->cat->name: 'N/A' }}</td>
                                                    <td> {{ !empty($product->brand) ? $product->brand->title : 'N/A'  }}</td>
                                                    <td> {{ $product->variant->first() ? $product->variant->first()->quantity : 0 }}</td>
                                                    <td> {{ $product->variant->first() ? $product->variant->first()->price : 0 }}</td>
                                                    <td> {{ ($product->is_live == 1) ? 'Published' : 'Draft'}}</td>
                                                    <td> {{ ($product->is_new == 0) ? 'No' : 'Yes' }}</td>
                                                    <td> {{ ($product->is_featured == 0) ? 'No' : 'Yes' }}</td>
                                                    <td> {{ ($product->Requires_last_mile == 0) ? 'No' : 'Yes' }}</td>
                                                    <td>
                                                        <div class="form-ul" style="width: 60px;">
                                                            <div class="inner-div" style="float: left;">
                                                                <a class="action-icon" href="{{ route('product.edit', $product->id) }}" userId="{{$product->id}}"><i class="mdi mdi-square-edit-outline"></i></a>
                                                            </div>
                                                            <div class="inner-div">
                                                                <form method="POST" action="{{ route('product.destroy', $product->id) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <div class="form-group">
                                                                        <button type="submit" onclick="return confirm('Are you sure? You want to delete the product.')" class="btn btn-primary-outline action-icon"><i class="mdi mdi-delete"></i></button>
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
<div id="add-product" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Add Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_product_form" method="post" enctype="multipart/form-data" action="{{route('product.store')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-group" id="skuInput">
                                        {!! Form::label('title', 'SKU (Allowed Keys -> a-z,A-Z,0-9,-,_)',['class' => 'control-label']) !!}
                                        <span class="text-danger">*</span>
                                        {!! Form::text('sku', null, ['class'=>'form-control','id' => 'sku', 'onkeypress' => 'return alplaNumeric(event)','onkeyup' => 'return alplaNumeric(event)', 'placeholder' => 'Apple-iMac']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                        {!! Form::hidden('type_id', 1) !!}
                                        {!! Form::hidden('vendor_id', $vendor->id) !!}
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group" id="url_slugInput">
                                        {!! Form::label('title', 'URL Slug',['class' => 'control-label']) !!}
                                        {!! Form::text('url_slug', null, ['class'=>'form-control', 'id' => 'url_slug', 'placeholder' => 'Apple iMac', 'onkeypress' => 'return slugify(event)']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group" id="categoryInput">
                                        {!! Form::label('title', 'Category',['class' => 'control-label']) !!}
                                        <select class="form-control selectizeInput" id="category_list" name="category">
                                            <option value="">Select Category...</option>
                                            @foreach($product_categories as $product_category)
                                                @if($product_category->category)
                                                    @if( ($product_category->category->type_id == 1) || ($product_category->category->type_id == 3) || ($product_category->category->type_id == 7))
                                                        <option value="{{$product_category->category_id}}">{{(isset($product_category->category->primary->name)) ? $product_category->category->primary->name : $product_category->category->slug}}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitProduct">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="import-product" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Add Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form method="post" enctype="multipart/form-data" id="save_imported_products">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="{{url('file-download'.'/sample_product.csv')}}">Download Sample file here!</a>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" value="{{$vendor->id}}" name="vendor_id" />
                            <input type="file" accept=".csv" onchange="submitProductImportForm()" data-plugins="dropify" name="product_excel" class="dropify" />
                            <p class="text-muted text-center mt-2 mb-0">Upload CSV File</p>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-centered table-nowrap table-striped" id="">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>File Name</th>
                                        <th colspan="2">Status</th>
                                        <th>Link</th>
                                    </tr>
                                </thead>
                                <tbody id="post_list">
                                    @foreach($csvProducts as $csv)
                                    <tr data-row-id="{{$csv->id}}">
                                        <td> {{ $loop->iteration }}</td>
                                        <td> {{ $csv->name }}</td>
                                        @if($csv->status == 1)
                                        <td>Pending</td>
                                        <td></td>
                                        @elseif($csv->status == 2)
                                        <td>Success</td>
                                        <td></td>
                                        @else
                                        <td>Errors</td>
                                        <td class="position-relative text-center">
                                            <i class="mdi mdi-exclamation-thick"></i>
                                            <ul class="tooltip_error">
                                                <?php $error_csv = json_decode($csv->error); ?>
                                                @foreach($error_csv as $err)
                                                <li>
                                                    {{$err}}
                                                </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        @endif
                                        <td> <a href="{{ $csv->path }}">Download</a> </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.addProductBtn').click(function() {
        $('#add-product').modal({
            keyboard: false
        });
    });
    $('.importProductBtn').click(function() {
        $('#import-product').modal({
            keyboard: false
        });
    });
    var regexp = /^[a-zA-Z0-9-_]+$/;
    function alplaNumeric(evt) {
        var charCode = String.fromCharCode(event.which || event.keyCode);
        if (!regexp.test(charCode)) {
            return false;
        }
        var n1 = $('#sku').val();
        $('#url_slug').val(n1+charCode)
        slugify(evt);
        return true;
    }
    function slugify(evt) {
      var charCode = String.fromCharCode(event.which || event.keyCode);
      if (!regexp.test(charCode)) {
        return false;
      }
      var string = $('#url_slug').val();
      var slug = string.toString().trim().toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "").replace(/\-\-+/g, "-").replace(/^-+/, "").replace(/-+$/, "");
      $('#url_slug').val(slug);
    }
    $(document).on('click', '.submitProduct', function(e) {
        var form = document.getElementById('save_product_form');
        var formData = new FormData(form);
        $.ajax({
            type: "post",
            url: "{{route('product.validate')}}",
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
                            $("#categoryInput span.invalid-feedback").children("strong").text('The category field is required.');
                            $("#categoryInput span.invalid-feedback").show();
                        } else {
                            $("#" + key + "Input input").addClass("is-invalid");
                            $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                            $("#" + key + "Input span.invalid-feedback").show();
                        }
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
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
@endsection