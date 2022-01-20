@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Tools'])

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert mt-2 mb-0 alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @endif
                @if ( ($errors) && (count($errors) > 0) )
                <div class="alert mt-2 mb-0 alert-danger">
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
    <div class="row align-items-center">
        <div class="col-sm-12">
            <div class="text-sm-left">
                <div class="page-title-box">
                    <h4 class="page-title">{{ __("Tools") }}</h4>
                </div> 
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <form method="POST" id="catalog_copy_tools" action="{{route('tools.store')}}">
            @csrf
            @method('POST')
                <div class="card-box h-100 mb-0">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{ __('Catalog Copy Tool')}}</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="copy_from" class="mr-3">{{ __("Copy From") }}</label>
                                <select class="form-control" id='copy_from' name="copy_from" required>
                                    <option value="">{{ __("Select vendor for copy") }}</option> 
                                    @foreach($vendors as $vendor)
                                    <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="copy_to" class="mr-3">{{ __("Copy To") }}</label>
                                <select class="form-control select2-multiple" id="copy_to" name="copy_to[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ..." required>
                                    @foreach($vendors as $vendor)
                                    <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mt-3">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-info btn-block" id="catalog_copy_button" type="submit"> {{ __("Copy") }} </button>
                            </div>
                        </div>
                    </div>
                </div>             
            </form>
        </div>
        <div class="col-md-3">
            <form method="POST" id="tax_copy_tools" action="{{route('tools.taxCopy')}}">
            @csrf
            @method('POST')
                <div class="card-box h-100 mb-0">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{ __('Tax Copy Tool')}}</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="tax_category" class="mr-3">{{ __("Tax Category") }}</label> 
                                <select class="form-control" id='tax_category' name="tax_category" required>
                                    <option value="">{{ __("Select Tax Category") }}</option>
                                    @foreach($taxCategory as $tax)
                                    <option value="{{$tax->id}}">{{$tax->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="product_category" class="mr-3">{{ __("Product Category") }}</label> 
                                <select class="form-control select2-multiple" id='product_category' name="product_category[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ..." required>
                                    <option value="">{{ __("Select Product Category") }}</option>
                                    @foreach($parentCategory as $parent)
                                    @if(count( $parent->childs) > 0)
                                    <optgroup label="{{$parent->slug}}">
                                        @foreach($parent->childs as $child)
                                        <option value="{{$child->id}}">{{$child->slug}}</option>
                                        @endforeach
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-2 mt-3">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-info btn-block" id="tax_copy_button" type="submit"> {{ __("Update") }} </button>
                            </div>
                        </div>
                    </div>
                </div>             
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')

<script type="text/javascript">
$(document).ready(function() {
    $('#catalog_copy_tools').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "{{__('Are you sure?')}}",
            text:"{{__('This will delete and overwrite all menu items.')}}",
                // icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Copy',
        }).then((result) => {
            if(result.value)
            {
                $("#catalog_copy_tools").off("submit").submit();
            }else{
                return false;
            }
        });
    });
    $('#tax_copy_tools').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "{{__('Are you sure?')}}",
            // text:"{{__('This will update tax for all product.')}}",
                // icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
        }).then((result) => {
            if(result.value)
            {
                $("#tax_copy_tools").off("submit").submit();
            }else{
                return false;
            }
        });
    });
});
</script>
@endsection