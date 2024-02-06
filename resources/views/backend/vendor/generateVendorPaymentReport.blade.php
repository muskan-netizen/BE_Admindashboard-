@extends('layouts.vertical', ['demo' => 'creative', 'title' => getNomenclatureName('vendors', true)])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" />

<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">


@endsection
@section('content')
<div class="container-fluid vendor-page">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="page-title">Generate Vendor Payment Report</h4>
            </div>
        </div>
    </div>
</div>
<!-- start product action popup -->
<div>
    <a href="{{route('vendorReportExport')}}">
    <button type="button" class="btn btn-primary" > Generate Report  </button>
    </a>
</div>

@endsection
@section('script')

