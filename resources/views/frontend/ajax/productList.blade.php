@php
    $additionalPreference = getAdditionalPreference(['is_token_currency_enable']);
@endphp

@if(@$data['filter_type'] && $data['filter_type'] == 1)
<div class="col-12 custom_filtter">
    <ul>
        <input type="hidden" name="order_type" id='order_type' class="sortingFilter" />
        <li><span>{{__('Sort By:')}}</span></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "newly_added" ? 'active' : ''}}" data-value="newly_added">{{__('Newest Arrivals')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "featured" ? 'active' : ''}}" data-value="featured">{{__('Featured')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "a_to_z" ? 'active' : ''}}" data-value="a_to_z">{{__('A to Z')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "z_to_a" ? 'active' : ''}}" data-value="z_to_a">{{__('Z to A')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "low_to_high" ? 'active' : ''}}" data-value="low_to_high">{{__('Cost : Low to High')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "high_to_low" ? 'active' : ''}}" data-value="high_to_low">{{__('Cost : High to Low')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "rating" ? 'active' : ''}}" data-value="rating">{{__('Avg. Customer Review')}}</a></li>
        
    </ul>
</div>
@else
<div class="col-12 text-right mt-2">
    <select name="order_type" id='order_type' class="sortingFilter p-1">
     <option value="">{{__('Sort By')}}</option>
     <option value="newly_added" {{isset($data['order_type']) && $data['order_type'] == "newly_added" ? 'selected' : ''}}>{{__('Newest Arrivals')}}</option>
        <option value="featured" {{isset($data['order_type']) && $data['order_type'] == "featured" ? 'selected' : ''}}>{{__('Featured')}}</option>
        <option value="a_to_z" {{isset($data['order_type']) && $data['order_type'] == "a_to_z" ? 'selected' : ''}}>{{__('A to Z')}}</option>
        <option value="z_to_a" {{isset($data['order_type']) && $data['order_type'] == "z_to_a" ? 'selected' : ''}}>{{__('Z to A')}}</option>
        <option value="low_to_high" {{isset($data['order_type']) && $data['order_type'] == "low_to_high" ? 'selected' : ''}}>{{__('Cost : Low to High')}}</option>
        <option value="high_to_low" {{isset($data['order_type']) && $data['order_type'] == "high_to_low" ? 'selected' : ''}}>{{__('Cost : High to Low')}}</option>
        <option value="rating" {{isset($data['order_type']) && $data['order_type'] == "rating" ? 'selected' : ''}}>{{__('Avg. Customer Review')}}</option>
       
    </select>
</div>
@endif

@include('frontend.ajax.product-card')

@if(count($listData))
<div class="pagination pagination-rounded justify-content-end mb-0">
    {{ $listData->links() }}
</div>
@endif

@section('script')
<script>
    $(document).ready(function(){
        let currentPage = '{{$_GET["page"]??"1"}}';
        if(currentPage){
            $('.page-link').each(function(){
                if($(this).text()==currentPage){
                    $(this).prev().addClass('active');
                    break;
                }
            })
        }
    })
</script>
@endsection
