@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Role'])

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Roles and Permission</h4>
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


                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif

                        </div>
                        <div class="col-sm-4 text-right">
                            <a class="btn btn-info waves-effect waves-light text-sm-right add-role"
                                href="javascript:;"><i class="mdi mdi-plus-circle mr-1"></i> Create Role</a>

                                {{-- <a class="btn btn-info waves-effect waves-light text-sm-right " href="{{route('permissions')}}"><i class="mdi mdi-plus-circle mr-1"></i> Add Permissions
                            </a> --}}
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="vendor_payouts_datatable" class="display" style="width:100%">
                            <thead>
                            <tr>
                               <th>No</th>
                               <th>Name</th>
                               <th width="280px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                              @forelse ($roles as $key => $role)
                              <tr>
                                  <td>{{ ++$key }}</td>
                                  <td>{{ @$role->name }}</td>
                                  <td>
                                    <a class="btn btn-primary btn-sm edit-role" data-name="{{@$role->name}}" data-id="{{$role->id}}" href="javascript:;">Edit</a>
                                      {{-- <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>
                                      @can('role-edit')
                                          <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                                      @endcan
                                      @can('role-delete')
                                          {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                              {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                          {!! Form::close() !!}
                                      @endcan --}}
                                  </td>
                              </tr>
                              @empty

                              <tr>
                                <td colspan="10" class="text-center">No Record found.</td>
                              </tr>
                              @endforelse
                            </tbody>
                          </table>
                        
                    </div>
                  
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>


<div id="add-role-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __('Add Role') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_role" method="post" action="{{ route('save.roles') }}">
                @csrf
                <div class="modal-body" >
                    <div class="row">
                        <input name="id" class="role-id" type="hidden" />

                                    <div class="col-md-12">
                                        <div class="form-group" id="nameInput">
                                                {!! Form::label('title', __('Role'),['class' => 'control-label']) !!}
                                                {!! Form::text('role_name', null, ['class'=>'form-control role-name', 'required'=>'required']) !!}
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 select2">
                                        <div class="form-group" id="nameInput">
                                        {!! Form::label('title', __('Permission'),['class' => 'control-label']) !!}
                                        <select class="permissoin-multiple selectTo" name="permission[]" multiple="multiple">
                                            @foreach($permissions as $perm)
                                            <option value="{{$perm->id}}" >{{$perm->name}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                </div>
                              
                          
                 
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light submitAddSubscriptionForm">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')


<script>
$('.permissoin-multiple').select2();
var table = $('#vendor_payouts_datatable').DataTable({
            // rowReorder: true,
            sort:false
        });

    // table.on( 'row-reorder', function ( e, diff, edit ) {
    //     var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
    //     for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
    //         var rowData = table.row( diff[i].node ).data();
    //         result += rowData[1]+' updated to be in position '+
    //             diff[i].newData+' (was '+diff[i].oldData+')<br>';
    //     }
    //     $('#result').html( 'Event result:<br>'+result );
    // });

    $(document).delegate(".add-role", "click", function(){
        $('.role-name').val('');
        $('.role-id').val('');
        $("#add-role-modal").modal("show");
    });


    $(document).on('click', '.edit-role', function(e) {
            var id = $(this).attr('data-id');
            callAjax(id)
        });

        $(document).on('click', '.close', function(e) {
            $(".select2").hide();
        });


        function callAjax(id)
        {
            var id = id;
            $.ajax({
                method: "post",
                headers: {
                    Accept: "application/json",
                },
                url: "{{route('get.role') }}",
                data: 'id='+id,
                success: function (response) {
                    if (response) {
                        $("#add-role-modal").modal("show");
                        $('.role-name').val(response.role.name);
                        $('.role-id').val(response.role.id);
                        $('.select2').show();
                        $('.selectTo').html(response.select);
                        $('.permissoin-multiple').select2();
                    }else{
                        alert('Try Again!');
                    }
                }
            });
            
        }

</script>

@endsection