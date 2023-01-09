<?php

namespace App\Http\Traits;

use DB;
use Auth;

trait InventoryTrait
{

    public function saveBrand($i_product, $request)
    {
        $order_brand_id = null;
        $brand_details = $i_product['brand'];
        if (@$brand_details) {
            \DB::table('brands')->updateOrInsert([
                'title' => $brand_details['title']
            ], [
                'image' => $brand_details['image'],
                'image_banner' => $brand_details['image_banner'],
                'status' => $brand_details['status']
            ]);
            $order_brand =  \DB::table('brands')->where('title', $brand_details['title'])->first();
            $order_brand_id = $order_brand->id;

            \DB::table('brand_categories')->updateOrInsert([
                'brand_id' => $order_brand->id,
                'category_id' => $request->order_cat
            ], [
                'brand_id' => $order_brand->id,
                'category_id' => $request->order_cat
            ]);
            \DB::table('brand_translations')->updateOrInsert([
                'title' => $brand_details->title,
                'brand_id' => $order_brand->id
            ], [
                'title' => $brand_details->title,
                'brand_id' => $order_brand->id
            ]);
        }
        return $order_brand_id;
    }
    public function saveTax($i_product, $request)
    {
        $order_product_tax_categories_id = null;
        $inventory_product_tax_category = $i_product['tax']['tax_categories'];
        if (@$inventory_product_tax_category) {
            \DB::table('tax_categories')->updateOrInsert([
                'title' => $inventory_product_tax_category['title'],
                'code' => $inventory_product_tax_category['code']
            ],[
                'title' => $inventory_product_tax_category['title'],
                'code' => $inventory_product_tax_category['code'],
                'description' => $inventory_product_tax_category['description'],
                'is_core' => $inventory_product_tax_category['is_core']
                // 'description' => $inventory_product_tax_category->code,
            ]);

            $order_product_tax_categories =  \DB::table('tax_categories')->where([
                'title' => $inventory_product_tax_category['title'],
                'code' => $inventory_product_tax_category['code']
            ])->first();
            $order_product_tax_categories_id = $order_product_tax_categories->id;

            $inventory_tax_cat = $i_product['tax']['tax_rate_categories'];
            $tax_rates = $i_product['tax']['tax_rates'];

            if( !empty($tax_rates) ) {
                foreach($tax_rates as $tax_key => $inventory_tax_rate) {
                   
                    
                    \DB::table('tax_rates')->updateOrInsert([
                        'identifier' => $inventory_tax_rate['identifier'],
                    ], [
                        'identifier'    => $inventory_tax_rate['identifier'],
                        'is_zip'        => $inventory_tax_rate['is_zip'],
                        'zip_code'      => $inventory_tax_rate['zip_code'],
                        'zip_from'      => $inventory_tax_rate['zip_from'],
                        'zip_to'        => $inventory_tax_rate['zip_to'],
                        'state'         => $inventory_tax_rate['state'],
                        'country'       => $inventory_tax_rate['country'],
                        'tax_rate'      => $inventory_tax_rate['tax_rate'],
                        'tax_amount'    => $inventory_tax_rate['tax_amount'],
                    ]);
                    
                    $order_tax_rate = \DB::table('tax_rates')->where('identifier', $inventory_tax_rate['identifier'])->first();
                    \DB::table('tax_rate_categories')->updateOrInsert([
                        'tax_cate_id' => $order_product_tax_categories_id,
                        'tax_rate_id' => $order_tax_rate->id,
                    ],[
                        'tax_cate_id' => $order_product_tax_categories_id,
                        'tax_rate_id' => $order_tax_rate->id,
                    ]);
                }
            }
        }
        return $order_product_tax_categories_id;

    }


    public function saveProduct($i_product)
    {
        $order_product_id = '';
        $product_update_create = $i_product;

        unset($product_update_create['brand']);
        unset($product_update_create['tax']);
        unset($product_update_create['product_varaint_set']);
        unset($product_update_create['media']);
        unset($product_update_create['variantData']);
        unset($product_update_create['addon_sets']);
        unset($product_update_create['product_translations']);
        unset($product_update_create['variants']);


        if($i_product['sku'] != null){
            $product_exists = \DB::table('products')->where('sku', $i_product['sku'] )->first();
            if( empty($product_exists) ) {
                $order_product_id = \DB::table('products')->insertGetId($product_update_create);
                
            }else {
                \DB::table('products')->where('sku', $i_product['sku'] )->update($product_update_create);
                $order_product_id = $product_exists->id;
            }
        }else{
            $order_product_id = \DB::table('products')->insertGetId($product_update_create);
        }

        return $order_product_id;
    }


    public function saveProductTranslation($product_translations, $order_product_id)
    {
        foreach($product_translations as  $product_translation) {     # import product translation
            $product_trans = [
                'title'         => $product_translation['title'],
                'body_html'     => $product_translation['body_html'],
                'meta_title'    => $product_translation['meta_title'],
                'meta_keyword'  => $product_translation['meta_keyword'],
                'meta_description' => $product_translation['meta_description'],
                'product_id'    => $order_product_id,
                'language_id'   => $product_translation['language_id'],
            ];
            $product_translation_import = \DB::table('product_translations')->updateOrInsert(['product_id' => $order_product_id], $product_trans);
        }

        return true;
    }


    public function saveProductVariantSet($variants, $order_product_id)
    {

        \DB::table('product_variant_sets')->where('product_id', $order_product_id)->delete();
        
    }
}
