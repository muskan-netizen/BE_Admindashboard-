<hr class="my-1">
<div class="row mb-1 d-flex align-items-center LongTermSechudel" id ='LongTermSechudel_{{ $vendor_product->id}}' data-cart_product_id="{{$vendor_product->id}}">
    {{-- @php
    pr($vendor_product);
    @endphp --}}
    <div class="col-3 vendor_service_timing" >
        <div class="hsProductTiming">
            <h6 class="product-title mt-0">{{ __('Service Time') }}:<br>
            </h6>
            <select class="form-control selectize-select"
                id="service_period" name="service_period">
                @foreach (config('constants.Period') as $key => $value)
                    @if (in_array($key, $vendor_product->product->ServicePeriods))
                        <option value="{{ $key }}" {{ $vendor_product->service_period == $key ? 'selected' : '' }} >
                            {{ __($value) }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-3 service_date_div {{  $vendor_product->service_period == 'months' ? '' : 'd-none' }}" >
        <label for="">{{ __('Date') }}</label>
        <select class="form-control selectize-select" id="service_date"
            name="date" disabled>
            @for ($i = 1; $i <= 28; $i++)
                <option value="{{ $i }}" {{ $vendor_product->service_date == $i ? 'selected' : '' }} >{{ $i }}
                </option>
                @if ($i == 28)
                    <option value="0" {{ $vendor_product->service_date == 0 ? 'selected' : '' }}> {{ __('Last day of month') }}
                    </option>
                @endif
            @endfor
        </select>
    </div>
    <div class="service_day_div col-3 {{  $vendor_product->service_period == 'week' ? '' : 'd-none' }}">
        <label for="">{{ __('Day:') }}</label>
        <select class="form-control selectize-select" id="service_day"
            name="day" disabled>
            @foreach (config('constants.weekDay') as $dayKey => $day)
                <option value="{{ $dayKey }}" {{ $vendor_product->service_day == $dayKey ? 'selected' : '' }} >{{ __($day) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="service_time_div col-3">
        <label for="">{{ __('Time:') }}</label>
        <input type="time" id="service_start_time" value="{{ $vendor_product->service_start_time  }}"  disabled class="form-control">
    </div>
</div>