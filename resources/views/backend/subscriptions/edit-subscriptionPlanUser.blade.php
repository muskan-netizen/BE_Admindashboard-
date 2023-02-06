<div class="modal-header border-bottom">
    <h4 class="modal-title">{{ __('Edit Plan') }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<form id="user_subscription_form" method="post" enctype="multipart/form-data" action="{{ route('subscription.plan.save.user', $plan->slug) }}">
    @csrf
    <div class="modal-body" >
        <div class="row">
            <div class="col-md-12">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>{{ __('Upload Image') }}</label>
                        <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{ $plan->image['proxy_url'].'100/100'.$plan->image['image_path'] }}" />
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('title', __('Enable'),['class' => 'control-label']) !!}
                            <div class="mt-md-1">
                                <input type="checkbox" data-plugin="switchery" name="status" class="form-control status" data-color="#43bee1" {{($plan->status == 1) ? 'checked' : ''}}>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group" id="nameInput">
                            {!! Form::label('title', __('Title'),['class' => 'control-label']) !!}
                            {!! Form::text('title', $plan->title, ['class'=>'form-control', 'required'=>'required']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    @php
                    $feature_percent_value = '';
                    @endphp
                    <div class="col-md-6 features_wrapper">
                        <div class="form-group">
                            <label for="">{{ __("Features") }}</label>
                            <select class="form-control select2-multiple subscription_features" name="features[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ..." required="required">
                                @foreach($features as $feature)
                                    @php
                                    if(in_array(2, $subPlanFeaturesIds)){
                                        $off_on_order_feature = $planFeatures->where('feature_id', 2)->first();
                                        $feature_percent_value = $off_on_order_feature ? $off_on_order_feature->percent_value : '';
                                    }
                                    @endphp
                                    <option value="{{$feature->id}}" {{ (in_array($feature->id, $subPlanFeaturesIds)) ? "selected" : "" }}> {{$feature->title}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 percentage_value_wrapper" style="{{ (in_array(2, $subPlanFeaturesIds)) ? 'display:block' : '' }}">
                        <div class="form-group">
                            <label for="percent_value">{{ __('Percent Value') }}</label>
                            <input class="form-control" type="number" id="percent_value" name="percent_value" min="0" value="{{$feature_percent_value}}" placeholder="Percent Value" onKeyPress="if(this.value.length==6) return false;" {{ (in_array(2, $subPlanFeaturesIds)) ? 'required=true' : '' }}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">{{ __('Price') }}</label>
                            <input class="form-control" type="number" name="price" min="0" value="{{ decimal_format($plan->price) }}" required="required">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">{{ __("Frequency") }}</label>
                            <select class="form-control" name="frequency" value="{{ $plan->frequency }}" required="required">
                                <option value="weekly" {{ $plan->frequency == 'weekly' ? 'selected' : '' }}>{{ __("Weekly") }}</option>
                                <option value="monthly" {{ $plan->frequency == 'monthly' ? 'selected' : '' }}>{{ __("Monthly") }}</option>
                                <option value="yearly" {{ $plan->frequency == 'yearly' ? 'selected' : '' }}>{{ __("Yearly") }}</option>
                            </select>
                        </div>
                    </div>
                    <?php /* ?><div class="col-md-6">
                        <div class="form-group">
                            <label for="">Sort Order</label>
                            <input class="form-control" type="number" name="sort_order" min="1" value="{{ $plan->sort_order }}" required="required">
                        </div>
                    </div><?php */ ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('title', __('Description'),['class' => 'control-label']) !!}
                            {!! Form::textarea('description', $plan->description, ['class' => 'form-control', 'rows' => '3']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info waves-effect waves-light submitAddSubscriptionForm">{{ __('Submit') }}</button>
    </div>
</form>