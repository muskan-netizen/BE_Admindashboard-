<div id="user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Add Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_user" action="{{ route('customer.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="imageInput">
                                <label>Profile image</label>
                                <input data-default-file="" type="file" data-plugins="dropify" name="image" accept="image/*" class="dropify"/>
                                <label class="logo-size text-right w-100">Image Size 110x110 </label>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="nameInput">
                                        {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="emailInput">
                                        {!! Form::label('title', 'Email',['class' => 'control-label']) !!}
                                        {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>                                
                                <div class="col-md-6">
                                    <div class="form-group" id="phone_numberInput">
                                        {!! Form::label('title', 'Phone Number',['class' => 'control-label']) !!}
                                        <input type="tel" class="form-control phone" id="phone" placeholder="Phone Number" name="phone_number" value="{{ old('full_number')}}">

                                        <input type="hidden" id="addphoneHidden" name="phoneHidden">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="passwordInput">
                                        {!! Form::label('title', 'Password',['class' => 'control-label']) !!}
                                        <input type="password" class="form-control" id="password" placeholder="Password" required="" name="password" value="{{ old('password')}}">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="form-group" id="countryInput">
                                        {!! Form::label('title', 'Country',['class' => 'control-label']) !!}
                                        <select class="selectize-select form-control" name="country_id">
                                            <option value="">Select</option>
                                            @foreach($countries as $key => $val)
                                                <option value="{{$val->id}}">{{$val->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- <div class="form-group" id="typeInput">
                                        {!! Form::label('title', 'Type',['class' => 'control-label']) !!}
                                        <select class="selectize-select form-control" name="role_id">
                                            <option value="1">Buyer</option>
                                            <option value="2">Seller</option>
                                        </select>
                                    </div> --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Email Verified',['class' => 'control-label']) !!} 
                                        <div>
                                             <input type="checkbox" data-plugin="switchery" name="email_verified" class="form-control email_verify_add">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Phone Verified',['class' => 'control-label']) !!} 
                                        <div>
                                             <input type="checkbox" data-plugin="switchery" name="phone_verified" class="form-control phone_verify_add">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="addCountryData" name="countryData" value="us">
                    <button type="submit" class="btn btn-info w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-customer-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Edit Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="add_user" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6" id="imageInput">
                            <input data-default-file="" type="file" data-plugins="dropify" name="image" accept="image/*" class="dropify"/>
                            <p class="text-muted text-center mt-2 mb-0">Profile image</p>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="nameInputEdit">
                                        {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="emailInputEdit">
                                        {!! Form::label('title', 'Email',['class' => 'control-label']) !!}
                                        {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>                                
                                <div class="col-md-6">
                                    <div class="form-group" id="phone_numberInputEdit">
                                        {!! Form::label('title', 'Phone Number',['class' => 'control-label']) !!}
                                        <input type="tel" class="form-control phone" id="phone" placeholder="Phone Number" name="phone_number" value="{{ old('full_number')}}">

                                        <input type="hidden" id="addphoneHidden" name="phoneHidden">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="passwordInputEdit">
                                        {!! Form::label('title', 'Password',['class' => 'control-label']) !!}
                                        <input type="password" class="form-control" id="password" placeholder="Password" required="" name="password" value="{{ old('password')}}">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="typeInputEdit">
                                        {!! Form::label('title', 'Type',['class' => 'control-label']) !!}
                                        <select class="selectize-select form-control" name="role_id">
                                            <option value="1">Buyer</option>
                                            <option value="2">Seller</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="countryInputEdit">
                                        {!! Form::label('title', 'Country',['class' => 'control-label']) !!}
                                        <select class="selectize-select form-control" name="country_id">
                                            <option value="">Select</option>
                                            @foreach($countries as $key => $val)
                                                <option value="{{$val->id}}">{{$val->nicename}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="editCountryData" name="editCountryData" value="us">
                    <button type="submit" class="btn btn-info w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="customer-wallet-transactions-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Wallet Transactions</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-centered table-striped w-100" id="customer_wallet_transactions_datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-nowrap">Date</th>
                                        <th>Description</th>
                                        <th>Credit / Debit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </form>
        </div>
    </div>
</div>