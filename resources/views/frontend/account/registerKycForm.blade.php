<input type="hidden" name="kyc" value="1" />
<div class="col-sm-12">
    <div class="page-title">
        <h3 style="margin-top: 0px !important;margin-bottom: 10px !important;">{{ __('Kyc Details') }}</h3>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label for="">Adhar (Front)</label>
        <input type="file" name="adhar_front" id="adhar_front" class="form-control" value="" required />
        @error('adhar_front')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label for="">Adhar (Back)</label>
        <input type="file" name="adhar_back" id="adhar_back" class="form-control" value="" required />
        @error('adhar_back')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label for="">Adhar Number</label>
        <input type="text" name="adhar_number" id="adhar_number" class="form-control" value="" required />
        @error('adhar_number')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label for="">UPI ID</label>
        <input type="text" name="upi_id" id="upi_id" class="form-control" value="" required />
        @error('upi_id')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label for="">Bank Name</label>
        <input type="text" name="bank_name" id="bank_name" class="form-control" value="" required />
        @error('bank_name')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label for="">Beneficiary name</label>
        <input type="text" name="account_name" id="account_name" class="form-control" value="" required />
        @error('account_name')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label for="">Account Number</label>
        <input type="text" name="account_number" id="account_number" class="form-control" value="" required />
        @error('account_number')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label for="">IFSC Code</label>
        <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" value="" required />
        @error('ifsc_code')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
</div>
