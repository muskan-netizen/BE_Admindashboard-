@extends('layouts.vertical', ['demo' => 'creative', 'title' => getNomenclatureName('Pincode', True)])
@section('css')
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/multiselect/multiselect.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-selectroyoorders/bootstrap-select.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/nestable2/nestable2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .error {
            color: red;
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-error">
                        {{ session()->get('error') }}
                    </div>
                @endif
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h3 class="page-title">{{ getNomenclatureName(__('Pincode'), true) }}</h3>
                    </div>
                    <div class="col-md-6">
                        <div class="al_new_export_table royo_customber_btn table_customber_add">
                            <div class="position-absolute mb-2">
                                <button class="btn btn-info waves-effect waves-light text-sm-right importPincodeModal"><i class="mdi mdi-plus-circle mr-1"></i> {{ __('Import CSV') }}</button>
                                <button type="button" class="btn btn-info waves-effect waves-light text-sm-right addPincodeBtn" data-pincode=""><i class="mdi mdi-plus-circle mr-1"></i> {{ __('Add Pincode') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body position-relative">
                        <div class="table-responsive">
                            <table id="pincode_table" class="table table-centered table-nowrap table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Pincode') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        {{-- <th>{{ __('Created Date') }}</th> --}}
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="review_table_tbody_list">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="add-edit-pincode" class="modal fade add_reason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">{{ __('Add Pincode') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="save_reason_form" method="post" enctype="multipart/form-data" action="{{ route('pincode.store') }}">
                    @csrf
                    <input type="hidden" name="pincode_id" id="pincode_id" value="">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title">Pincode</label>
                                    <input type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "6" class="form-control" name="pincode" id="pincode" placeholder="Enter Pincode" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info waves-effect waves-light submitPincode">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="import-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">{{ __('Import Pincode') }} </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" enctype="multipart/form-data" id="save_imported_pincode">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="{{url('/sample_customer.csv')}}">{{ __("Download Sample file here!") }}</a>
                            </div>
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <input type="file" accept=".csv" onchange="submitImportPincodeForm()" data-plugins="dropify" name="pincode_csv" class="dropify" data-default-file="" required/>
                                        <p class="text-muted text-center mt-2 mb-0">{{ __("Upload") }} CSV</p>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-striped" id="">
                                <p id="p-message" style="color:red;"></p>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('File Name') }}</th>
                                            <th colspan="2">{{ __('Status') }}</th>
                                            <th>{{ __('Link') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="post_list">
                                        @foreach($csvCustomers ?? '' as $csv)
                                        <tr data-row-id="{{$csv->id}}">
                                            <td> {{ $loop->iteration }} </td>
                                            <td> {{ $csv->name }} </td>
                                            @if($csv->status == 1)
                                            <td>{{ __('Pending') }}</td>
                                            <td></td>
                                            @elseif($csv->status == 2)
                                            <td>{{ __('Success') }}</td>
                                            <td></td>
                                            @else
                                            <td>{{ __('Errors') }}</td>
                                            <td class="position-relative text-center alTooltipHover">
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
                                            <td> <a href="{{ $csv->path }}">{{ __('Download') }}</a> </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            initDataTable();
            
            function initDataTable() {
                $('#pincode_table').DataTable({
                    "lengthChange": false,
                    "searching": true,
                    "destroy": true,
                    "scrollX": true,
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength": 10,
                    "ajax": "{{ url('client/pincode') }}",
                    drawCallback: function() {
                        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                    },
                    language: {
                        search: "",
                        info:'{{__("Showing _START_ to _END_  of _TOTAL_ entries")}}',
                        paginate: {
                            previous: "<i class='mdi mdi-chevron-left'>",
                            next: "<i class='mdi mdi-chevron-right'>"
                        },
                        searchPlaceholder: '{{__("Search By Pincode")}}'
                    },

                    columns: [
                        {
                            data: 'id',
                            name: 'id',
                            //orderable: false,
                            searchable: false
                        },
                        {
                            data: 'pincode',
                            name: 'pincode',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }
            $(document).on('click', '.addPincodeBtn', function(){
                var pincode = $(this).data('pincode');
                var pincode_id = $(this).data('id');
                if(pincode_id == ''){
                    $('#add-edit-pincode #pincode_id').val('');
                    $('#add-edit-pincode #pincode').val('');
                    $('#add-edit-pincode .modal-title').text("Add Pincode");
                }else{
                    $('#add-edit-pincode #pincode_id').val(pincode_id);
                    $('#add-edit-pincode #pincode').val(pincode);
                    $('#add-edit-pincode .modal-title').text("Edit Pincode");
                }
                $('#add-edit-pincode').modal();
            });

            $('.importPincodeModal').click(function(){
                $('#import-form').modal('show');
                $('.dropify').dropify();
            });
        });
    </script>
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
@endsection
