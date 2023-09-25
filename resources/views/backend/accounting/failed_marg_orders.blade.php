@extends('layouts.vertical', ['demo' => 'Orders', 'title' => 'Accounting - Orders'])
@section('css')
{{-- <link href="{{asset('assets/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" /> --}}
<style>
.dataTables_filter,.toolbar,.dt-buttons.btn-group.flex-wrap {position: absolute;height:40px;}.dataTables_filter{right:0;top: -50px;}
.dataTables_filter label{margin:0;height:40px;}.dataTables_filter label input{margin:0;height:40px;}.dt-buttons.btn-group.flex-wrap{right: 200px;top: -50px;}
.table-responsive{position: relative;overflow:visible;margin-top:10px;}table.dataTable{margin-top:0 !important;}
div.dataTables_wrapper div.dataTables_filter input {width: 285px;}
.dt-buttons.btn-group.flex-wrap {right: 310px;top: -50px;}
</style>
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ __('Orders') }}</h4>
                </div>
            </div>
        </div>
       
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body position-relative al">
                    <div class="top-input position-absoluteal">
                     
                   </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="failed_margorders_datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Order ID') }}</th>
                                    <th>{{ __('Order Number') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Customer Name') }}</th>
                                    <th>{{ __('Vendor') }}</th>
                                    <th>{{ __('Action') }}</th>
                                  
                                    
                                </tr>
                            </thead>
                            <tbody id="accounting_vendor_tbody_list">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var table;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
  
        getOrderList();
        function getOrderList() {
            $(document).ready(function() {
                initDataTable();
                
                function initDataTable() {
                    $('#failed_margorders_datatable').DataTable({
                        "dom": '<"toolbar">brtip',
                        "scrollX": true,
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        "iDisplayLength": 50,
                        language: {
                            info:'{{__("Showing _START_ to _END_  of _TOTAL_ entries")}}',
                        
                        },
                        drawCallback: function () {
                        },
                        buttons: false,
                        ajax: {
                          url: "{{route('account.order.margFilter')}}",
                        },
                        columns: [
                            {data: 'orderId', name: 'orderId',orderable: false, searchable: false},
                            {data: 'order_number', name: 'order_number',orderable: false, searchable: false},
                            {data: 'created_date', name: 'name',orderable: false, searchable: false},
                            {data: 'user_name', name: 'Customer Name',orderable: false, searchable: false},
                            {data: 'vendor_name', name: 'vendor_name', orderable: false, searchable: false},
                            {data: 'sync_order', name: 'sync_order', orderable: true, searchable: false, "mRender": function ( data, type, full ) {
                                var syncUrl = full.sync_order; // Assuming full.sync_order contains the URL
                                return '<a href="' + syncUrl + '" class="btn btn-primary">Sync Order</a>';
   

                            }},
                            
                            
                           
                      
                        ]
                    });
                }

			
            });
        }
    });
  
</script>
@endsection
@section('script')
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
@endsection
