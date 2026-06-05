@extends('layouts.vertical', ['demo' => 'creative', 'title' => __('Contact Us')])
@section('css')
<style>
    #contact_us_datatable .contact-us-message-wrap {
        max-width: 28rem;
        white-space: normal;
        word-break: break-word;
        line-height: 1.45;
    }
    #contact_us_datatable td.contact-us-msg-col {
        vertical-align: top;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Contact Us') }}</h4>
            </div>
        </div>
    </div>
    <div class="row m-0">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="contact_us_datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Message') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#contact_us_datatable').DataTable({
                scrollX: false,
                autoWidth: false,
                destroy: true,
                processing: true,
                serverSide: true,
                iDisplayLength: 25,
                pageLength: 25,
                order: [[0, 'desc']],
                dom: '<"toolbar">Bfrtip',
                language: {
                    search: "",
                    searchPlaceholder: "{{ __('Search') }}",
                    paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                },
                buttons: [],
                ajax: {
                    url: "{{ route('contact-us.data') }}",
                    type: 'GET'
                },
                columnDefs: [
                    { targets: 4, className: 'contact-us-msg-col' }
                ],
                columns: [
                    { data: 'id', name: 'id', width: '5%', orderable: true, searchable: false },
                    { data: 'name', name: 'name', width: '12%', orderable: true, searchable: true },
                    { data: 'email', name: 'email', width: '18%', orderable: true, searchable: true },
                    { data: 'phone_number', name: 'phone_number', width: '12%', orderable: false, searchable: true, defaultContent: '' },
                    { data: 'message', name: 'message', width: '38%', orderable: false, searchable: true },
                    { data: 'created_at', name: 'created_at', width: '15%', orderable: true, searchable: false }
                ]
            });
        });
    </script>
@endsection
