<section class="section-b-space ratio_asos" style="padding:0px;">
    <div class="collection-wrapper">

        <div class="container">
            <button type="button" class="close mt-2" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h2 class="category-head m-0" style="position:initial;">{{__("Select Vendors")}}</h2>
            
            <div class="position-relative">
                <div class="categories-product-list">
                    <div class="row">
                        <div class="col-12">
                            <div class="row vendor-products-wrapper">
                                <div class="col-md-12 col-lg-12">
                                    <div class="loading add_to_real_cart_loader" style="position: absolute;top: 30%;left: 45%; display:none;">
                                        <img style="width:30%;" src="{{asset('assets/images/loading_new.gif')}}" alt="">
                                    </div>
                                    @forelse($vendors as $vendor)
                                        {{-- @php
                                        dd($vendor);
                                        @endphp --}}
                                        <section class="scrolling_section" id="{{ $vendor->slug }}">
                                           
                                            <?php $productDetails = \App\Models\Product::where('vendor_id', $vendor->id)->get(); ?>
                                            @if (!empty($vendor->productsLive))
                                                <div class="col-md-12 mb-3 mt-3 d-sm-flex">
                                                    <div
                                                        class="vender-icon mr-sm-1 text-center text-sm-left mb-2 mb-sm-0">
                                                        <img src="{{ $vendor->logo['image_fit'] . '120/120' . $vendor->logo['image_path'] }}"
                                                        class="rounded-circle avatar-lg" alt="profile-image">
                                                    </div>

                                                    <div class="ml-sm-1">
                                                        <span class="badge @if($vendor->match == "Complete Match") badge-danger @else badge-primary @endif" style="font-size:10px;">
                                                            {{ ($vendor->match != "") ? $vendor->match : 'Partial Match'; }}
                                                        </span>
                                                        <h4 style="color:black; padding-left:10px;"> {{ $vendor->name }} ({{ $vendor->productsLive->count() }})</h4>
                                                        <ul class="vendor-info">
                                                            <li class="d-block vendor-location" style="font-size:12px; color:#6c757d;">
                                                                <i class="icon-location"></i> {{ $vendor->address }}
                                                            </li>
                                                           
                                                        </ul>
                                                     
                                                            
                                                   
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <span class="badge badge-primary" style="font-size:13px; background:#6558cc;">
                                                                    @php
                                                                        $final_price = 0;
                                                                        $prod_ids = array();
                                                                        $variant_id = array();
                                                                        $vendor_id = $vendor->id;
                                                                        $addon_id = array();
                                                                        $option_id = array();
                                                                    @endphp
                                                                    @foreach ($vendor->productsLive as $prod)
                                                                    <?php
                                                                        array_push($prod_ids, $prod->id);
                                                                      
                                                                         $addon_price = 0; 
                                                                        foreach($prod->sets as $set){
                                                                            array_push($addon_id, $set->addon_id);
                                                                            $addon_price = $set->setoptions->sum('price');

                                                                            foreach($set->setoptions as $key => $option){
                                                                                array_push($option_id, $option->id );
                                                                            }
                                                                        } 
                                                                        $addon_id_new = implode(',',array_unique($addon_id));
                                                                        $option_id_new = implode(',',array_unique($option_id));
                                                                        $prod_ids_new = implode(',',array_unique($prod_ids));
                                                                        
                                                                        $final_price += number_format(($prod->variant[0]->price *$vendor->variant_multiplier)+$addon_price , 2, '.', '');
                                                                    ?>
                                                                        
                                                                    @endforeach
                                                                    {{ Session::get('currencySymbol').' '.$final_price}}
                                                                </span>
                                                            </div>
                                                            <div class="col-md-4 text-right">
                                                                <a class="add-cart-btn add_real_cart"
                                                                id="aadd_button_href"
                                                                data-add_to_cart_url="{{ route('addToCart') }}"
                                                                data-vendor_id="{{ $vendor_id }}"
                                                                data-product_id="{{ $prod_ids_new}}"
                                                                data-addonId="{{ $addon_id_new }}"
                                                                data-option_id="{{ $option_id_new }}" href="javascript:void(0)">{{ __('Proceed Button') }}
                                                                </a>
                                                            </a>
                                                            </div>
                                                        </div>
                                                    
                                                    </div>
                                                </div>
                                                <hr>
                                            @else
                                                <h4 class="mt-3 mb-3 text-center">No product found</h4>
                                            @endif
                                        </section>
                                    @empty
                                        <h4 class="mt-3 mb-3 text-center">No product found</h4>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
