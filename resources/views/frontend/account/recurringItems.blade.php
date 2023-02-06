@if (isset($product->longTermSchedule) && count($product->longTermSchedule->schedule) > 0)
<div class="outer_div col-9 mb-2">
    <h6 class="mt-0"> <b>{{ __('Product Detail') }} </b></h6>
    <hr class="my-2">
    <div class="service_product">
        @php
            $Service_product_url = isset($product->longTermSchedule->product) ? route('product.edit', @$product->longTermSchedule->product->id) : '#';
        @endphp
        <div class="d-flex justify-content-start">
            <h6 class="m-0 pr-2 text-left">{{ __('Product Name') }}:</h6>
            <a href="{{ $Service_product_url }}" target="_blank"> {{ $product->longTermSchedule->product->primary->title }}</a>
        </div>
        <div class="d-flex justify-content-start">
            <h6 class="m-0 pr-2 text-left">{{ __('No. of Bookings') }}:</h6>
            <span>{{ $product->longTermSchedule->service_quentity }}</span>
        </div>
        <div class="d-flex justify-content-start">
            <h6 class="m-0 pr-2 text-left">{{ __('Service Time') }}:</h6>
            <span>{{ __(config('constants.Period.' . $product->longTermSchedule->service_period)) }}</span>
        </div>

        @if ($product->longTermSchedule->addon && count($product->longTermSchedule->addon))
            <hr class="my-2">
            <h6 class="m-0 pl-0"><b>{{ __('Add Ons') }}</b></h6>
            @foreach ($product->longTermSchedule->addon as $addon)
                <div class="longTermAddon d-flex justify-content-start">
                    <h6 class="p-0 m-0">
                        {{ $addon->set->title }} :</h6>
                    <span class="p-0 m-0">{{ $addon->option->translation_title }}</span>
                </div>
            @endforeach
        @endif
    </div>
</div>
<div class="outer_divLongTermBox w-100">
    <h6>{{ __('Long Term Service Schedule') }}</h6>
    <div class="col-12">

        <table class="wp-table w-100">
            <tr>
                <th>#</th>
                <th>{{ __('Scheduled date time') }}
                </th>
                <th>{{ __('Status') }}</th>
            </tr>
            @foreach ($product->longTermSchedule->schedule as $key => $schedule)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td><a
                            href="javascript:void(0)">{{ date('d M Y h:i A', strtotime(dateTimeInUserTimeZone($schedule->schedule_date, $timezone))) }}</a>
                    </td>
                    <td> <span
                            class="badge {{ $schedule->status == 0 ? 'badge-info' : 'badge-success' }}  mr-2">{{ $schedule->status == 0 ? __('Pending') : __('Complete') }}</span>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endif