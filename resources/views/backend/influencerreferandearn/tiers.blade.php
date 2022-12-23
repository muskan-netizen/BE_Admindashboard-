<div class="col-6">
            <div class="">
                <div class="influencer-form-list">
                    <div class="">
                        <div class="card-box h-100">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="page-title">{{ ("Influencer Tiers") }}</h4>
                                        <button class="btn btn-info waves-effect waves-light text-sm-right addAttributbtn" dataid="0"><i class="mdi mdi-plus-circle mr-1"></i> {{ __('Add') }}
                                        </button>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="row variant-row">
                                <div class="col-md-12">
                                   
                                    <div class="table-responsive outer-box">
                                        <table class="table table-centered table-nowrap table-striped" id="varient-datatable">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Category') }}</th>
                                                    <th>{{ __('Options') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                @if(!empty($attributes))
                                                @foreach($attributes as $key => $variant)

                                                    @if(!empty($variant->translation_one))
                                                        <tr class="variantList" data-row-id="{{$variant->id}}">
                                                            <td>
                                                                <a class="editAttributeBtn" dataid="{{$variant->id}}" href="javascript:void(0);">{{$variant->title}}</a>
                                                            </td>
                                                            <td>
                                                            <a href="{{ route('influencer-refer-earn.edit', ['id' => $variant->influencerCategory[0]->id]) }}">{{$variant->influencerCategory[0]->name ?? ''}}</a>
                                                            </td>
                                                            <td>
                                                                @foreach($variant->option as $key => $value)
                                                                <label style="margin-bottom: 3px;">
                                                                    @if(isset($variant) && !empty($variant->type) && $variant->type == 2)
                                                                    <span style="padding:8px; float: left; border: 1px dotted #ccc; background:{{$value->hexacode}};"> </span>
                                                                    @endif
                                                                    &nbsp;&nbsp; {{$value->title}}</label> <br />
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                <a class="action-icon editAttributeBtn" dataid="{{$variant->id}}" href="javascript:void(0);">
                                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                                </a>
                                                                @if( auth()->user()->is_superadmin )
                                                                <a class="action-icon deleteAttribute" dataid="{{$variant->id}}" href="javascript:void(0);">
                                                                    <i class="mdi mdi-delete"></i>
                                                                </a>
                                                                <form action="{{route('attribute-influencer-refer-earn.delete', $variant->id)}}" method="POST" style="display: none;" id="attrDeleteForm{{$variant->id}}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="action-icon btn btn-primary-outline" dataid="{{$variant->id}}" onclick="return confirm('Are you sure? You want to delete the attribute.')"> <i class="mdi mdi-delete"></i></button>
                                                                </form>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                @endif
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