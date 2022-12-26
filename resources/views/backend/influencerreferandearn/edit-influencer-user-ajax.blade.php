{{-- @dd($influencer_users->user->name) --}}
<div class="row">
    <div class="col-sm-12 mb-2">
        <label for="title" class="control-label">Commision Type</label>
        <select class="selectize-select form-control" name="commision_type" required="">
            <option value="1">Percentage</option>
            <option value="2">Fixed</option>
        </select>
    </div>
    <div class="col-sm-12 mb-2">
        <label for="title" class="control-label">Commision</label>
        <input class="form-control" min="1" onkeypress="return isNumberKey(event)" placeholder="Commision" required="required" name="commision" type="number" value="0">
    </div>
</div>