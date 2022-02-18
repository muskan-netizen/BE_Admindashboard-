@if($payout_option == 'pagarme')
<div class="modal-header py-3 px-3 border-bottom-0 d-block">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title" id="modal-title">{{ __('Create Pagarme Account') }}</h5>
</div>
<div class="modal-body px-3 pb-3 pt-0">
    <form class="" name="" id="" action="" method="post">
        @csrf
        <div class="row mb-2">
            {{-- <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">{{ __("Select Bank)") }}</label>
                    <select class="form-control" name="pagarme_bank" id="pagarme_bank" required >
                        @foreach($banks_list as $bank)
                            <option value="{{$bank->bank_code}}">{{$bank->legal_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">{{ __("Bank Code") }}</label>
                    <input class="form-control" placeholder="{{ __("Bank Code") }}" type="text" name="bank_code" id="bank_code" required />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">{{ __("Agency") }}</label>
                    <input class="form-control" placeholder="{{ __("Agency") }}" type="text" name="agencia" id="agencia" required />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">{{ __("Agency Check Digit") }}</label>
                    <input class="form-control" placeholder="{{ __("Agency Check Digit") }}" type="text" name="agencia_dv" id="agencia_dv" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">{{ __("Account Number") }}</label>
                    <input class="form-control" placeholder="{{ __("Account Number") }}" type="text" name="conta" id="conta" required />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">{{ __("Account Verification Digit") }}</label>
                    <input class="form-control" placeholder="{{ __("Account Verification Digit") }}" type="text" name="conta_dv" id="conta_dv" required />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">{{ __("Account CPF or CNPJ") }}</label>
                    <input class="form-control" placeholder="{{ __("Account CPF or CNPJ") }}" type="text" name="document_number" id="document_number" required />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">{{ __("Full Name or Business Name") }}</label>
                    <input class="form-control" maxlength="30" placeholder="{{ __("Full Name or Business Name") }}" type="text" name="legal_name" id="legal_name" required />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">{{ __("Account Type") }}</label>
                    <select class="form-control" name="type" id="type" required >
                        <option value="conta_corrente" selected>{{ __('Current Account') }}</option>
                        <option value="conta_poupanca">{{ __('Savings Account') }}</option>
                        <option value="conta_corrente_conjunta">{{ __('Joint Current Account') }}</option>
                        <option value="conta_poupanca_conjunta">{{ __('Joint Savings Account') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 d-sm-flex justify-content-between">
                <button type="submit" class="btn btn-info">{{ __('Create') }}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </form>
</div>
@endif
