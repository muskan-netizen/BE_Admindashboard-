@extends('layouts.vertical', ['demo' => 'creative', 'title' => getNomenclatureName('vendors', true)])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .pac-container, .pac-container .pac-item { z-index: 99999 !important; }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="page-title">{{getNomenclatureName('vendors', true)}}</h4>
            </div>
        </div>
        <div class="col-sm-6 text-sm-right">
            <button class="btn btn-info waves-effect waves-light text-sm-right openImportModal"
                    userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Import
            </button>
            <button class="btn btn-info waves-effect waves-light text-sm-right openAddModal"
                userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card widget-inline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-storefront text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_earnings_by_vendors">{{$total_vendor_count}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Total {{getNomenclatureName('vendors', true)}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-store-24-hour text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_order_count">{{$available_vendors_count}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Open {{getNomenclatureName('vendors', true)}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-currency-usd text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_cash_to_collected">{{$vendors_product_count}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Total Products</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-currency-usd text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_delivery_fees">{{$vendors_active_order_count}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Total Active Orders</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-12 tab-product pt-0">
            <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="active-vendor" data-toggle="tab" href="#active_vendor" role="tab" aria-selected="false" data-rel="vendor_active_datatable" data-status="1">
                        <i class="icofont icofont-man-in-glasses"></i>Active<sup class="total-items" id="active_vendor_count">({{$active_vendor_count}})</sup>
                    </a>
                    <div class="material-border"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="awaiting-vendor" data-toggle="tab" href="#awaiting_vendor" role="tab" aria-selected="true" data-rel="awaiting__Approval_vendor_datatble" data-status="0">
                        <i class="icofont icofont-ui-home"></i>Awaiting Approval<sup class="total-items">({{$awaiting__Approval_vendor_count}})</sup>
                    </a>
                    <div class="material-border"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="block-vendor" data-toggle="tab" href="#block_vendor" role="tab" aria-selected="false" data-rel="blocked_vendor_datatble" data-rel="blocked_vendor_datatble" data-status="2">
                        <i class="icofont icofont-man-in-glasses"></i>Blocked<sup class="total-items">({{$blocked_vendor_count}})</sup>
                    </a>
                    <div class="material-border"></div>
                </li>
            </ul>
            <div class="tab-content nav-material pt-0   " id="top-tabContent">
                <div class="tab-pane fade past-order show active" id="active_vendor" role="tabpanel"
                    aria-labelledby="active-vendor">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <form name="saveOrder" id="saveOrder"> @csrf</form>
                                        <table class="table table-centered table-nowrap table-striped" id="vendor_active_datatable" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Icon</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Address</th>
                                                    <th>Offers</th>
                                                    <th class="text-center">Can Add <br> Category</th>
                                                    <th class="text-center">Commission <br> Percentage</th>
                                                    <th class="text-center">Products</th>
                                                    <th class="text-center">Orders</th>
                                                    <th class="text-center">Active <br> Orders</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="post_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row address" id="def" style="display: none;">
                            <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="awaiting_vendor" role="tabpanel"
                    aria-labelledby="awaiting-vendor">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <form name="saveOrder" id="saveOrder"> @csrf</form>
                                        <table class="table table-centered table-nowrap table-striped" id="awaiting__Approval_vendor_datatble" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Icon</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Address</th>
                                                    <th>Offers</th>
                                                    <th class="text-center">Can Add <br> Category</th>
                                                    <th class="text-center">Commission <br> Percentage</th>
                                                    <th class="text-center">Products</th>
                                                    <th class="text-center">Orders</th>
                                                    <th class="text-center">Active <br> Orders</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="post_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row address" id="def" style="display: none;">
                            <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade past-order" id="block_vendor" role="tabpanel" aria-labelledby="block-vendor">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-nowrap table-striped" id="blocked_vendor_datatble" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Icon</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Address</th>
                                                    <th>Offers</th>
                                                    <th class="text-center">Can Add <br> Category</th>
                                                    <th class="text-center">Commission <br> Percentage</th>
                                                    <th class="text-center">Products</th>
                                                    <th class="text-center">Orders</th>
                                                    <th class="text-center">Active <br> Orders</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="post_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row address" id="def" style="display: none;">
                            <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="edit_vendor_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">Edit {{getNomenclatureName('vendors', false)}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="update_vendor_form" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" id="editVendorBox">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info waves-effect waves-light" id="update_vendor_modal">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('backend.vendor.modals')
@endsection
@section('script')
@include('backend.vendor.pagescript')
    <script src="{{asset('js/admin_vendor.js')}}"></script>
    <script type="text/javascript">
        var search_text = "{{getNomenclatureName('vendors', false)}}";
    </script>
@endsection