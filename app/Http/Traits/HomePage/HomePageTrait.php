<?php

namespace App\Http\Traits\HomePage;

use App\Models\{Product, Vendor, VendorCategory};
use Carbon\Carbon;
use Session, DB;


trait HomePageTrait
{


    public function getMostSellingVendors($preferences, $vendor_ids)
    {
        $latitude = Session::get('latitude');
        $longitude = Session::get('longitude');
        $mostSellingVendors = Vendor::with('slot.day', 'slotDate')->select('vendors.*', DB::raw('count(vendor_id) as max_sales'))->join('order_vendors', 'vendors.id', '=', 'order_vendors.vendor_id')->whereIn('vendors.id', $vendor_ids)->where('vendors.status', 1)->groupBy('order_vendors.vendor_id')->orderBy(DB::raw('count(vendor_id)'), 'desc');

        // add hyperlocal check to get vendors
        if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {

            if (!empty($latitude) && !empty($longitude)) {
                $mostSellingVendors = $mostSellingVendors->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                    $query->select('vendor_id')
                        ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                });
            }
        }
        $mostSellingVendors = $mostSellingVendors->get();

        if ((!empty($mostSellingVendors) && count($mostSellingVendors) > 0)) {
            foreach ($mostSellingVendors as $key => $value) {
                $value->vendorRating = $this->vendorRating($value->products);
                // $value->name = Str::limit($value->name, 15, '..');
                if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                    $value = $this->getVendorDistanceWithTime($latitude, $longitude, $value, $preferences);
                }
                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . @$category->category->translation_one->name;
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $value->categoriesList = $categoriesList;

                $value->is_vendor_closed = 0;
                if ($value->show_slot == 0) {
                    if (($value->slotDate->isEmpty()) && ($value->slot->isEmpty())) {
                        $value->is_vendor_closed = 1;
                    } else {
                        $value->is_vendor_closed = 0;
                        if ($value->slotDate->isNotEmpty()) {
                            $value->opening_time = Carbon::parse($value->slotDate->first()->start_time)->format('g:i A');
                            $value->closing_time = Carbon::parse($value->slotDate->first()->end_time)->format('g:i A');
                        } elseif ($value->slot->isNotEmpty()) {
                            $value->opening_time = Carbon::parse($value->slot->first()->start_time)->format('g:i A');
                            $value->closing_time = Carbon::parse($value->slot->first()->end_time)->format('g:i A');
                        }
                    }
                }
            }
        }
        if (($preferences) && ($preferences->is_hyperlocal == 1)) {
            $mostSellingVendors = $mostSellingVendors->sortBy('lineOfSightDistance')->values()->all();
        }
        return $mostSellingVendors;
    }


    public function getSpotLight($preferences, $vendor_ids, $language_id, $currency_id)
    {
        $products = Product::with([
            'category.categoryDetail.translation' => function ($q) use ($language_id) {
                $q->where('category_translations.language_id', $language_id);
            },
            'vendor',
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($language_id) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $language_id);
            },
            
        ])
        ->selectRaw('product_variants.sku, product_id, quantity,compare_at_price,  price, barcode, (compare_at_price - price) as discount_amount, ((compare_at_price - price)/compare_at_price)*100 as discount_percentage,   products.id, products.sku, url_slug, weight_unit, weight, vendor_id, has_variant, has_inventory, sell_when_out_of_stock, requires_shipping, Requires_last_mile, averageRating, inquiry_only
        ')
        
       ->join('product_variants', 'products.id', 'product_variants.product_id');
        
        $products = $products->whereHas('vendor', function ($q) use ( $vendor_ids) {
            $q->where('status', 1);
            $q->whereIn('vendors.id', $vendor_ids);
        })->where('is_live', 1)
        ->orderBy(DB::raw("((product_variants.compare_at_price - product_variants.price)/product_variants.compare_at_price)*100"), 'desc')
        ->take(10)->get();

        // $products = $products->sortBy(function($product) { 
        //     return $product->discount_percentage[0]->discount_percentage ?? 0;
        //   });
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = Session::get('currencyMultiplier');
                }
            }
        }
        return $products;
    }
}
