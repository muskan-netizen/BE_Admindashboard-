
    <div class="col-md-12">
        <div class="table-responsive">
            <form name="inventry-import" method="post" action="{{route('post.inventory.store.products')}}">
                @csrf()
                <input name="vendor_id" value="{{$vendor_id}}" type="hidden">
                <table class="table table-centered table-nowrap table-striped" id="">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="all-product_check"
                                    name="all_product_id" id="all-product_check"></th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Category') }}</th>
                        

                        </tr>
                    </thead>
                    <tbody id="post_list">
                        @foreach ($store_product as $key => $product)
                            <tr data-row-id="{{ $product['id'] }}">  
                                <td><input type="checkbox" class="single_product_check"
                                        name="productids[]" 
                                        value="{{ $product['id'] }}"></td>
                                <td> {{ Str::limit(isset($product['primary']['title']) && !empty($product['primary']['title']) ? $product['primary']['title'] : '', 30) }}</td>
                                <td> {{ $product['category'] ? $product['category']['cat']['name'] : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-12">
                    <button class="btn btn-info waves-effect waves-light w-100">{{ __("Import") }}</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(".all-product_check").click(function() {
            if ($(this).is(':checked')) {
                $("#action_product_button").css("display", "block");
                $('.single_product_check').prop('checked', true);
            } else {
                $("#action_product_button").css("display", "none");
                $('.single_product_check').prop('checked', false);
            }
        });
        </script>