<?php
namespace App\Http\Traits;

use App\Models\{Product};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Log;
trait ProductTrait{

    public function getProduct($product_id,$vendor_slug,$url_slug,$user='',$langId)
    {
       $with_array = [
            'variant' => function ($sel) {
                $sel->groupBy('product_id');
            },
            'translation_one'=> function ($t) use ($langId) {
                $t->where('language_id', $langId);
            },
            'variant.set' => function ($sel) {
                $sel->select('product_variant_id', 'variant_option_id');
            },
            'variant.media.pimage.image', 'related', 'upSell', 'crossSell', 'vendor', 'media.image',
             'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
            },
            'addOn' => function ($q1) use ($langId) {
                $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                $q1->where('set.status', 1)->where('ast.language_id', $langId);
            },
            'variantSet' => function ($z) use ($langId, $product_id) {
                $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                $z->where('vt.language_id', $langId);
                $z->where('product_variant_sets.product_id', $product_id);
                $z->where('vr.status', 1);
            },
            'variantSet.option2' => function ($zx) use ($langId, $product_id) {
                $zx->where('vt.language_id', $langId)
                    ->where('product_variant_sets.product_id', $product_id);
            },
            'addOn.setoptions' => function ($q2) use ($langId) {
                $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                $q2->where('apt.language_id', $langId);
            },
            'category.categoryDetail.allParentsAccount',
            'ServicePeriod',
            
        ];
        
        if( checkTableExists('product_attributes') ) {
            $with_array[] = 'ProductAttribute';
            $with_array[] = 'ProductAttribute.attribute';
            $with_array[] = 'ProductAttribute.attributeOption';
            $with_array[] = 'ProductAttribute.attribute';
        }
        $product = Product::with($with_array);
           
            if($user){
                $product = $product->with('inwishlist', function ($query) use($user) {
                    $query->where('user_wishlists.user_id', $user->id);
                });
            }
            $product = $product->with('related');
            $product = $product->select('id', 'sku', 'inquiry_only', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory', 'averageRating','sell_when_out_of_stock','minimum_order_count','batch_count','additional_increments_min','minimum_duration_min','buffer_time_duration_min','minimum_duration','additional_increments','buffer_time_duration','tags','is_long_term_service','service_duration', 'returnable' , 'replaceable' , 'return_days' );
            
            $product = $product->whereHas('vendor',function($q) use($vendor_slug){
                    $q->where('slug',$vendor_slug);
                })->where('url_slug', $url_slug)
                ->where('is_live', 1)
                ->firstOrFail();
        return $product;
    }
}