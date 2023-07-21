@extends('layouts.vertical', ['title' => 'Companies'])

@section('css')
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Companies</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-8">
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
                            <div class="col-sm-4 text-right">
                                <button class="btn btn-info openServiceModal"> {{ __('Add New Company') }}</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                                <thead>
                                    <tr>
                                        <th>Logo</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($company as $geo)
                                        <tr>
                                            <td class="table-user">
                                                <a href="javascript:void(0);" class="text-body">{{ $geo->logo }}</a>
                                            </td>
                                            <td class="table-user">
                                                <a href="javascript:void(0);" class="text-body">{{ $geo->name }}</a>
                                            </td>
                                            <td class="table-user">
                                                <a href="javascript:void(0);" class="text-body">{{ $geo->email }}</a>
                                            </td>
                                            <td class="table-user">
                                                <a href="javascript:void(0);" class="text-body">{{ $geo->phone_number }}</a>
                                            </td>
                                            <td class="table-user">
                                                <a href="javascript:void(0);" class="text-body">{{ $geo->address }}</a>
                                            </td>

                                            <td>
                                                {{-- @if ($client_preference_detail->slots_with_service_area == 1 && $vendor->show_slot == 0)
                                                    <input type="checkbox" data-plugin="switchery"
                                                        name="is_active_for_vendor_slot"
                                                        class="form-control is_active_for_vendor_slot" data-color="#43bee1"
                                                        data-aid="{{ $geo->id }}"
                                                        @if ($geo->is_active_for_vendor_slot == 1) checked @endif
                                                        {{ $vendor->cron_for_service_area == 1 ? 'disabled' : '' }}>
                                                @endif

                                                <button type="button"
                                                    class="btn btn-primary-outline action-icon editAreaBtn"
                                                    area_id="{{ $geo->id }}"><i
                                                        class="mdi mdi-square-edit-outline"></i></button>

                                                <form action="{{ route('admin.serviceArea.delete') }}" method="POST"
                                                    class="action-icon">
                                                    @csrf
                                                    <input type="hidden" value="{{ $geo->id }}" name="area_id">
                                                    <button type="submit"
                                                        onclick="return confirm('Are you sure? You want to delete the service area.')"
                                                        class="btn btn-primary-outline action-icon"><i
                                                            class="mdi mdi-delete"></i></button>

                                                </form> --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">No areas found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination pagination-rounded justify-content-end mb-0">
                            {{-- $banners->links() --}}
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div>
    <div id="service-area-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">{{ __('Add New Company') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form enctype="multipart/form-data" action="{{ route('company.add') }}" method="POST">
                    @csrf
                    <div class="modal-body mt-0" id="editCardBox">
                        <div class="row">

                            <div class="col-lg-12 mb-2">
                                {!! Form::label('title', __('Logo'), ['class' => 'control-label']) !!}
                                <input type="file" name="logo">
                            </div>
                        
                            <div class="col-lg-12 mb-2">
                                {!! Form::label('title', __('Name'), ['class' => 'control-label']) !!}
                                {!! Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name', 'required' => 'required']) !!}
                            </div>
                            <div class="col-lg-12 mb-2">
                                {!! Form::label('title', __('Phone Number'), ['class' => 'control-label']) !!}
                                {!! Form::number('phone_number', '', ['class' => 'form-control', 'placeholder' => 'Number', 'required' => 'required']) !!}
                            </div>
                            <div class="col-lg-12 mb-2">
                                {!! Form::label('title', __('Email'), ['class' => 'control-label']) !!}
                                {!! Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'Email', 'required' => 'required']) !!}
                            </div>
                        
                            <div class="col-lg-12 mb-2">
                                {!! Form::label('title', __('Address'), ['class' => 'control-label']) !!}
                                {!! Form::textarea('address', '', [
                                    'class' => 'form-control',
                                    'rows' => '3',
                                    'placeholder' => 'Address',
                                ]) !!}
                            </div>
                           
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit"
                                    class="btn btn-block btn-blue waves-effect waves-light w-100">{{ __('Save') }}</button>
                            </div>
                            <div class="col-md-6 p-0">
                                <input id="remove-line" class="btn btn-block btn-blue waves-effect waves-light w-100"
                                    type="button" value="Remove" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="edit-area-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">{{ __('Add Service Area') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="edit-area-form" action="" method="POST">
                    @csrf
                    <div class="modal-body" id="editAreaBox">
                        
                    </div>
                    <div class="modal-footer">
                        <div class="row mt-1">
                            <div class="col-12">
                                <button type="submit"
                                    class="btn btn-block btn-blue waves-effect waves-light">{{ __('Save') }}</button>
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
       
        function overlayClickListener(overlay) {
            google.maps.event.addListener(overlay, "mouseup", function(event) {
                $('#latlongs').val(overlay.getPath().getArray());
            });
        }
        $("#geo_form").on("submit", function(e) {
            var lat = $('#latlongs').val();
            var trainindIdArray = lat.replace("[", "").replace("]", "").split(',');
            var length = trainindIdArray.length;

            if (length < 6) {
                Swal.fire(
                    'Select Location?',
                    'Please Draw a Location On Map first',
                    'question'
                )
                e.preventDefault();
            }
        });
        /*                  EDIT       AREA        MODAL           */
        var CSRF_TOKEN = $("input[name=_token]").val();
        $(document).on('click', '.editAreaBtn', function() {
            var aid = $(this).attr('area_id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                dataType: "json",
                url: "{{ route('admin.serviceArea.edit') }}",
                data: {
                    _token: CSRF_TOKEN,
                    data: aid
                },
                success: function(data) {
                    document.getElementById("edit-area-form").action =
                        "{{ url('client/admin/updateArea') }}" + '/' + aid;
                    $('#edit-area-form #editAreaBox').html(data.html);
                    initialize_edit(data.zoomLevel, data.coordinate);
                    $('#edit-area-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            });
        });
        var Editmap; // Global declaration of the map
        function initialize_edit(zoomLevel = 0, coordinates = '') {
            var zoomLevel = zoomLevel;
            var coordinate = coordinates;
            if (coordinate != '') {
                coordinate = coordinate.split('(');
                coordinate = coordinate.join('[');
                coordinate = coordinate.split(')');
                coordinate = coordinate.join(']');
                coordinate = "[" + coordinate;
                coordinate = coordinate + "]";
                coordinate = JSON.parse(coordinate);
                var triangleCoords = [];
                const lat1 = coordinate[0][0];
                const long1 = coordinate[0][1];
                var max_x = lat1;
                var min_x = lat1;
                var max_y = long1;
                var min_y = long1;
                $.each(coordinate, function(key, value) {
                    if (value[0] > max_x) {
                        max_x = value[0];
                    }
                    if (value[0] < min_x) {
                        min_x = value[0];
                    }
                    if (value[1] > max_y) {
                        max_y = value[1];
                    }
                    if (value[1] < min_y) {
                        min_y = value[1];
                    }
                    triangleCoords.push(new google.maps.LatLng(value[0], value[1]));
                });
                var myLatlng = new google.maps.LatLng((min_x + ((max_x - min_x) / 2)), (min_y + ((max_y - min_y) / 2)));
                var myOptions = {
                    zoom: parseInt(zoomLevel),
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                Editmap = new google.maps.Map(document.getElementById("edit_map-canvas"), myOptions);
                myPolygon = new google.maps.Polygon({
                    paths: triangleCoords,
                    draggable: true, // turn off if it gets annoying
                    editable: true,
                    strokeColor: '#424fsd',
                    //strokeOpacity: 0.8,
                    //strokeWeight: 2,
                    fillColor: '#bb3733',
                    //fillOpacity: 0.35
                });
                myPolygon.setMap(Editmap);
                google.maps.event.addListener(myPolygon, "mouseup", function(event) {
                    $('#zoom_level_edit').val(Editmap.getZoom());
                    document.getElementById("latlongs_edit").value = myPolygon.getPath().getArray();
                });
            }
        }
        
        google.maps.event.addDomListener(window, 'load', initialize);
        google.maps.event.addDomListener(window, 'load', initialize_show);
        google.maps.event.addDomListener(window, 'load', initialize_edit);
        google.maps.event.addDomListener(document.getElementById('refresh'), 'click', deleteSelectedShape);
        
    </script>
    <script type="text/javascript">
        $('.openServiceModal').click(function() {
            $('#service-area-form').modal({
                keyboard: false
            });
        });
    </script>
@endsection
