@php
    $additionalPreference = getAdditionalPreference(['is_token_currency_enable','is_service_product_price_from_dispatch']);
    $is_service_product_price_from_dispatch_forOnDemand = 0;

    if(($additionalPreference['is_service_product_price_from_dispatch'] == 1) && ( Session::get('vendorType') == 'on_demand')){
        $is_service_product_price_from_dispatch_forOnDemand =1;
    }
@endphp

	@include('frontend.ajax.product-card')
@if(count($listData))
<div class="pagination pagination-rounded justify-content-end mb-0 page-m-20">
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
