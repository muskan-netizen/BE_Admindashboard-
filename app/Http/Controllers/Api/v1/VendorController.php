<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{Client, Type, User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, ClientPreference, ClientLanguage, Vendor, Brand, VendorCategory, Permissions, UserPermissions, UserVendor, VendorDocs, VendorRegistrationDocument, EmailTemplate, Country};

class VendorController extends BaseController{
    use ApiResponser;
    private $field_status = 2;
    private $folderName = '/vendor/extra_docs';

    public function postVendorCategoryList(Request $request){
        try {
            $vendor_ids = [];
            $category_details = [];
            $vendor_id = $request->vendor_id;
            $type = Type::where('title' ,'Vendor')->first();
            $vendor = Vendor::select('name', 'latitude', 'longitude')->where('id', $vendor_id)->first();
            $vendor_products = Product::with(['category.categoryDetail','category.categoryDetail.type'  => function ($q) {
                $q->select('id', 'title as redirect_to');
            }])->where('vendor_id', $vendor_id)->where('is_live', 1)->get(['id']);
            foreach ($vendor_products as $vendor_product) {
                if(!in_array($vendor_product->category->categoryDetail->id, $vendor_ids)){
                    if($vendor_product->category->categoryDetail->id != $type->id){
                        $vendor_ids[] = $vendor_product->category->categoryDetail->id;
                        $category_details[] = array(
                            'id' => $vendor_product->category->categoryDetail->id,
                            'slug' => $vendor_product->category->categoryDetail->slug,
                            'name' => $vendor_product->category->categoryDetail->translation_one ? $vendor_product->category->categoryDetail->translation_one->name :$vendor_product->category->categoryDetail->slug,
                            'icon' => $vendor_product->category->categoryDetail->icon,
                            'image' => $vendor_product->category->categoryDetail->image,
                            'type' => $vendor_product->category->categoryDetail->type??null
                        );
                    }
                }
            }
            return $this->successResponse($category_details, '', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function productsByVendor(Request $request, $vid = 0){
        try {
            if($vid == 0){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $user = Auth::user();
            $userid = $user->id;
            $latitude = $user->latitude;
            $longitude = $user->longitude;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $clientCurrency = ClientCurrency::where('currency_id', $user->currency)->first();
            $preferences = ClientPreference::select('distance_to_time_multiplier','distance_unit_for_time', 'is_hyperlocal', 'Default_location_name', 'Default_latitude', 'Default_longitude')->first();
            $langId = $user->language;
            $vendor = Vendor::select('id', 'name', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'slug', 'show_slot',
                        'order_min_amount', 'vendor_templete_id', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery','closed_store_order_scheduled')
                        ->withAvg('product', 'averageRating');
            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                $latitude = ($latitude) ? $latitude : $preferences->Default_latitude;
                $longitude = ($longitude) ? $longitude : $preferences->Default_longitude;
                $distance_unit = (!empty($preferences->distance_unit_for_time)) ? $preferences->distance_unit_for_time : 'kilometer';
                //3961 for miles and 6371 for kilometers
                $calc_value = ($distance_unit == 'mile') ? 3961 : 6371;
                $vendor = $vendor->select('*', DB::raw(' ( ' .$calc_value. ' * acos( cos( radians(' . $latitude . ') ) *
                        cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) +
                        sin( radians(' . $latitude . ') ) *
                        sin( radians( latitude ) ) ) )  AS vendorToUserDistance'))->orderBy('vendorToUserDistance', 'ASC');
            }
            $vendor = $vendor->where('id', $vid)->first();
            if(!$vendor){
                return response()->json(['error' => 'No record found.'], 200);
            }

            $vendor->is_vendor_closed = 0;
            if($vendor->show_slot == 0){
                if( ($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty()) ){
                    $vendor->is_vendor_closed = 1;
                }else{
                    $vendor->is_vendor_closed = 0;
                    if($vendor->slotDate->isNotEmpty()){
                        $vendor->opening_time = Carbon::parse($vendor->slotDate->first()->start_time)->format('g:i A');
                        $vendor->closing_time = Carbon::parse($vendor->slotDate->first()->end_time)->format('g:i A');
                    }elseif($vendor->slot->isNotEmpty()){
                        $vendor->opening_time = Carbon::parse($vendor->slot->first()->start_time)->format('g:i A');
                        $vendor->closing_time = Carbon::parse($vendor->slot->first()->end_time)->format('g:i A');
                    }
                }
            }
            $slotsDate = 0;
            $slotsDate = findSlot('',$vendor->id,'');
            $vendor->delaySlot = $slotsDate;
            $vendor->closed_store_order_scheduled = (($slotsDate)?$vendor->closed_store_order_scheduled:0);

        if($vendor->closed_store_order_scheduled == 1 && $vendor->is_vendor_closed == 1)
        {
            $vendor->scheduled_time = findSlot('',$vid);
          }

            $vendor->is_show_category = ($vendor->vendor_templete_id == 2 || $vendor->vendor_templete_id == 4 ) ? 1 : 0;
            $vendor->is_show_products_with_category = ($vendor->vendor_templete_id == 5) ? 1 : 0;
            $categoriesList = '';

            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                $vendor = $this->getLineOfSightDistanceAndTime($vendor, $preferences);
            }

            $code = $request->header('code');
            $client = Client::where('code',$code)->first();
            $vendor->share_link = "https://".$client->sub_domain.env('SUBMAINDOMAIN')."/vendor/".$vendor->slug;
            $variantSets =  ProductVariantSet::with(['options' => function($zx) use($langId){
                                $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                                $zx->select('variant_options.*', 'vt.title');
                                $zx->where('vt.language_id', $langId);
                            }])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                            ->join('variant_translations as vt','vt.variant_id','vr.id')
                            ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                            ->where('vt.language_id', $langId)
                            ->whereIn('product_id', function($qry) use($vid){
                            $qry->select('id')->from('products')
                                ->where('vendor_id', $vid);
                            })
                        ->groupBy('product_variant_sets.variant_type_id')->get();
                    // ->join('product_categories as pc', 'pc.product_id', 'products.id')
                    // ->whereNotIn('pc.category_id', function($qr) use($vid){
                    //             $qr->select('category_id')->from('vendor_categories')
                    //                 ->where('vendor_id', $vid)->where('status', 0);
                    // })

            if($vendor->vendor_templete_id == 5){
                $vendor_categories = VendorCategory::with(['category.translation' => function($q) use($langId){
                    $q->where('category_translations.language_id', $langId);
                }])->where('vendor_id', $vid);
                // if(isset($categorySlug) && ($categorySlug != '')){
                //     $vendor_categories = $vendor_categories->whereHas('category', function($query) use($categorySlug) {
                //         $query->where('slug', $categorySlug);
                //     });
                //     $vendor_categories = $vendor_categories->where('status', 1)->get();
                //     foreach($vendor_categories as $category){
                //         $childs = $this->getChildCategoriesForVendor($category->category_id, $langId, $vid);
                //         foreach($childs as $child){
                //             $vendor_categories->push($child);
                //         }
                //     }
                // }else{
                    // $vendor_categories = $vendor_categories->whereHas('category', function($query) {
                    //     $query->whereIn('type_id', [1]);
                    // });
                    $vendor_categories = $vendor_categories->where('status', 1)->get();
                // }

                foreach($vendor_categories as $ckey => $category) {
                    if ($category->category) {

                        $cckey = 0;
                        $categoriesList = $categoriesList . $category->category->translation_one->name ?? '';
                         if($cckey != $vendor_categories->count() - 1){
                            $categoriesList = $categoriesList . ', ';
                        }

                    }


                    $products = Product::with(['category.categoryDetail', 'category.categoryDetail.translation' => function($q) use($langId){
                                $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                                ->where('category_translations.language_id', $langId);
                            }, 'inwishlist' => function($qry) use($userid){
                                $qry->where('user_id', $userid);
                            },
                            'media.image',
                            'addOn' => function ($q1) use ($langId) {
                                $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                                $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                                $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                                $q1->where('set.status', 1)->where('ast.language_id', $langId);
                            },
                            'addOn.setoptions' => function ($q2) use ($langId) {
                                $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                                $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                                $q2->where('apt.language_id', $langId);
                            },
                            'translation' => function($q) use($langId){
                                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                            },
                            'variant' => function($q) use($langId){
                                $q->select('id','sku', 'product_id', 'quantity', 'price', 'barcode', 'compare_at_price')->orderBy('quantity', 'desc');
                                // $q->groupBy('product_id');
                            },'variant.checkIfInCartApp', 'checkIfInCartApp',
                             'tags.tag.translations' => function ($q) use ($langId) {
                                $q->where('language_id', $langId);
                            }
                        ])->select('id', 'sku', 'description', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only','minimum_order_count','batch_count');
                    $products = $products->where('is_live', 1)->where('category_id', $category->category_id)->where('vendor_id', $vid)->get();

                    if(!empty($products)){
                        foreach ($products as $key => $value) {
                            foreach ($value->addOn as $key => $val) {
                                foreach ($val->setoptions as $k => $v) {
                                    if($v->price == 0){
                                        $v->is_free = true;
                                    }else{
                                        $v->is_free = false;
                                    }
                                    $v->multiplier = $clientCurrency->doller_compare;
                                }
                            }

                            $p_id = $value->id;
                            $variantData = $value->with(['variantSet' => function ($z) use ($langId, $p_id) {
                                $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                                $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                                $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                                $z->where('vt.language_id', $langId);
                                $z->where('product_variant_sets.product_id', $p_id)->orderBy('product_variant_sets.variant_type_id', 'asc');
                            },'variantSet.option2'=> function ($zx) use ($langId, $p_id) {
                                $zx->where('vt.language_id', $langId)
                                ->where('product_variant_sets.product_id', $p_id);
                            }])->where('id', $p_id)->first();
                            $value->variantSet = $variantData->variantSet;
                            $value->product_image = ($value->media->isNotEmpty()) ? $value->media->first()->image->path['image_fit'] . '300/300' . $value->media->first()->image->path['image_path'] : '';
                            $value->translation_title = ($value->translation->isNotEmpty()) ? $value->translation->first()->title : $value->sku;
                            $value->translation_description = ($value->translation->isNotEmpty()) ? html_entity_decode(strip_tags($value->translation->first()->body_html),ENT_QUOTES) : '';
                            $value->translation_description = !empty($value->translation_description) ? mb_substr($value->translation_description, 0, 70) . '...' : '';
                            $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                            $value->variant_price = ($value->variant->isNotEmpty()) ? $value->variant->first()->price : 0;
                            $value->variant_id = ($value->variant->isNotEmpty()) ? $value->variant->first()->id : 0;
                            $value->variant_quantity = ($value->variant->isNotEmpty()) ? $value->variant->first()->quantity : 0;
                            $value->minimum_order_count = ($value->minimum_order_count) ? $value->minimum_order_count : 0;
                            foreach ($value->variant as $k => $v) {
                                $value->variant[$k]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                            }
                        }
                    }
                    if($products->count() > 0){
                        $category->products = $products;
                        $category->products_count = $products->count();
                    }else{
                        // if(isset($categorySlug) && ($categorySlug != '')){
                        //     $category->products_count = $products->count();
                        // }
                        // else{
                            $vendor_categories->forget($ckey);
                        // }
                    }
                }
                $listData =  array_values($vendor_categories->toArray());
            }
            else{
                $vendorCategories = VendorCategory::with(['category.translation' => function($q) use($langId){
                    $q->where('category_translations.language_id', $langId);
                }])->where('vendor_id', $vendor->id)->where('status', 1)->get();

                foreach($vendorCategories as $key => $category){
                    if($category->category){
                        $categoriesList = $categoriesList . ($category->category->translation->first()->name ?? '');
                        if( $key !=  $vendorCategories->count()-1 ){
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $products = Product::with(['category.categoryDetail', 'category.categoryDetail.translation' => function($q) use($langId){
                            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                            ->where('category_translations.language_id', $langId);
                        }, 'inwishlist' => function($qry) use($userid){
                            $qry->where('user_id', $userid);
                        },
                        'media.image',
                        'addOn' => function($q1) use($langId){
                            $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                            $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                            $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                            $q1->where('set.status', 1)->where('ast.language_id', $langId);
                        },
                        'addOn.setoptions' => function($q2) use($langId){
                            $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                            $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                            $q2->where('apt.language_id', $langId);
                        },
                        'translation' => function($q) use($langId){
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('id','sku', 'product_id', 'title', 'quantity', 'price', 'barcode');
                            // $q->groupBy('product_id');
                        }, 'variant.checkIfInCartApp', 'checkIfInCartApp',
                        'tags.tag.translations' => function ($q) use ($langId) {
                            $q->where('language_id', $langId);
                        }
                    ])->select('products.id', 'products.sku', 'products.requires_shipping', 'products.sell_when_out_of_stock', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.Requires_last_mile', 'products.averageRating', 'products.category_id', 'products.minimum_order_count', 'products.batch_count')
                    ->where('products.vendor_id', $vid)
                    ->where('products.is_live', 1)->paginate($paginate);
                if(!empty($products)){
                    foreach ($products as $key => $product) {
                        foreach ($product->addOn as $key => $value) {
                            foreach ($value->setoptions as $k => $v) {
                                if($v->price == 0){
                                    $v->is_free = true;
                                }else{
                                    $v->is_free = false;
                                }
                                $v->multiplier = $clientCurrency->doller_compare;
                            }
                        }

                        $p_id = $product->id;
                        $variantData = $product->with(['variantSet' => function ($z) use ($langId, $p_id) {
                            $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                            $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                            $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                            $z->where('vt.language_id', $langId);
                            $z->where('product_variant_sets.product_id', $p_id)->orderBy('product_variant_sets.variant_type_id', 'asc');
                        },'variantSet.options'=> function($zx) use($langId, $p_id){
                            $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id')
                            ->select('variant_options.*', 'vt.title', 'pvs.product_variant_id', 'pvs.variant_type_id')
                            ->where('pvs.product_id', $p_id)
                            ->where('vt.language_id', $langId);
                        }])->where('id', $p_id)->first();
                        $product->variantSet = $variantData->variantSet;
                        // $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                        $product->product_image = ($product->media->isNotEmpty()) ? $product->media->first()->image->path['image_fit'] . '300/300' . $product->media->first()->image->path['image_path'] : '';
                        $product->translation_title = ($product->translation->isNotEmpty()) ? $product->translation->first()->title : $product->sku;
                        $product->translation_description = ($product->translation->isNotEmpty()) ? html_entity_decode(strip_tags($product->translation->first()->body_html),ENT_QUOTES) : '';
                        $product->translation_description = !empty($product->translation_description) ? mb_substr($product->translation_description, 0, 70) . '...' : '';
                        $product->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                        $product->variant_price = ($product->variant->isNotEmpty()) ? $product->variant->first()->price : 0;
                        $product->variant_id = ($product->variant->isNotEmpty()) ? $product->variant->first()->id : 0;
                        $product->variant_quantity = ($product->variant->isNotEmpty()) ? $product->variant->first()->quantity : 0;
                        foreach ($product->variant as $k => $v) {
                            $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                        }
                    }
                }
            }
            $vendor->categoriesList = $categoriesList;
            $response['vendor'] = $vendor;
            $response['products'] = ($vendor->vendor_templete_id != 5) ? $products : [];
            $response['categories'] = ($vendor->vendor_templete_id == 5) ? $listData : [];
            $response['filterData'] = $variantSets;
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage().''.$e->getLineNo(), $e->getCode());
        }
    }

    /**
     * Display product By Vendor Category
     * vendor -> category -> product
     * @return \Illuminate\Http\Response
     */
    public function vendorCategoryProducts(Request $request, $slug1 = 0, $slug2 = 0){
        try{
            $paginate = $request->has('limit') ? $request->limit : 12;
            // $preferences = Session::get('preferences');
            $vendor = Vendor::select('id', 'name', 'slug', 'desc', 'logo', 'show_slot', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery', 'vendor_templete_id','closed_store_order_scheduled')->where('slug', $slug1)->where('status', 1)->first();
            if (!empty($vendor)) {
                if (!empty($vendor)) {
                    $vendor->is_vendor_closed = 0;
                    if ($vendor->show_slot == 0) {
                        if (($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty())) {
                            $vendor->is_vendor_closed = 1;
                        } else {
                            $vendor->is_vendor_closed = 0;
                            if ($vendor->slotDate->isNotEmpty()) {
                                $vendor->opening_time = Carbon::parse($vendor->slotDate->first()->start_time)->format('g:i A');
                                $vendor->closing_time = Carbon::parse($vendor->slotDate->first()->end_time)->format('g:i A');
                            } elseif ($vendor->slot->isNotEmpty()) {
                                $vendor->opening_time = Carbon::parse($vendor->slot->first()->start_time)->format('g:i A');
                                $vendor->closing_time = Carbon::parse($vendor->slot->first()->end_time)->format('g:i A');
                            }
                        }
                    }
                    $slotsDate = findSlot('',$vendor->vendor_id,'');
                    $vendor->delaySlot = $slotsDate;
                    $vendor->closed_store_order_scheduled = (($slotsDate)?$vendor->closed_store_order_scheduled:0);
                    $code = $request->header('code');
                    $client = Client::where('code', $code)->first();
                    $vendor->share_link = "https://".$client->sub_domain.env('SUBMAINDOMAIN')."/vendor/".$vendor->slug;
                }
                $user = Auth::user();
                $userid = $user->id;
                $langId = $user->language;
                $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
                $categoriesList = '';

                if ($vendor->vendor_templete_id == 5) {
                    $vid = $vendor->id;
                    $vendor_categories = VendorCategory::with(['category.translation' => function ($q) use ($langId) {
                        $q->where('category_translations.language_id', $langId);
                    }])->where('vendor_id', $vid);
                    if (isset($slug2) && ($slug2 != '')) {
                        $vendor_categories = $vendor_categories->whereHas('category', function ($query) use ($slug2) {
                            $query->where('slug', $slug2);
                        });
                        $vendor_categories = $vendor_categories->where('status', 1)->get();
                        foreach ($vendor_categories as $category) {
                            $childs = $this->getChildCategoriesForVendor($category->category_id, $langId, $vid);
                            foreach ($childs as $child) {
                                $vendor_categories->push($child);
                            }
                        }
                    } else {
                        // $vendor_categories = $vendor_categories->whereHas('category', function($query) {
                        //     $query->whereIn('type_id', [1]);
                        // });
                        $vendor_categories = $vendor_categories->where('status', 1)->get();
                    }

                    foreach ($vendor_categories as $ckey => $category) {
                        if ($category->category) {
                            $cckey = 0;
                            $categoriesList = $categoriesList . $category->category->translation_one->name ?? '';
                            if ($cckey != $vendor_categories->count() - 1) {
                                $categoriesList = $categoriesList . ', ';
                            }
                        }

                        $products = Product::with(['category.categoryDetail', 'category.categoryDetail.translation' => function ($q) use ($langId) {
                            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                                ->where('category_translations.language_id', $langId);
                        }, 'inwishlist' => function ($qry) use ($userid) {
                            $qry->where('user_id', $userid);
                        },
                            'media.image',
                            'addOn' => function ($q1) use ($langId) {
                                $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                                $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                                $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                                $q1->where('set.status', 1)->where('ast.language_id', $langId);
                            },
                            'addOn.setoptions' => function ($q2) use ($langId) {
                                $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                                $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                                $q2->where('apt.language_id', $langId);
                            },
                            'translation' => function ($q) use ($langId) {
                                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                            },
                            'variant' => function ($q) use ($langId) {
                                $q->select('id', 'sku', 'product_id', 'quantity', 'price', 'barcode', 'compare_at_price')->orderBy('quantity', 'desc');
                            // $q->groupBy('product_id');
                            },'variant.checkIfInCartApp', 'checkIfInCartApp',
                        ])->select('id', 'sku', 'description', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only');
                        $products = $products->where('is_live', 1)->where('category_id', $category->category_id)->where('vendor_id', $vid)->get();

                        if (!empty($products)) {
                            foreach ($products as $key => $value) {
                                foreach ($value->addOn as $key => $val) {
                                    foreach ($val->setoptions as $k => $v) {
                                        if ($v->price == 0) {
                                            $v->is_free = true;
                                        } else {
                                            $v->is_free = false;
                                        }
                                        $v->multiplier = $clientCurrency->doller_compare;
                                    }
                                }

                                $p_id = $value->id;
                                $variantData = $value->with(['variantSet' => function ($z) use ($langId, $p_id) {
                                    $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                                    $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                                    $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                                    $z->where('vt.language_id', $langId);
                                    $z->where('product_variant_sets.product_id', $p_id)->orderBy('product_variant_sets.variant_type_id', 'asc');
                                },'variantSet.option2'=> function ($zx) use ($langId, $p_id) {
                                    $zx->where('vt.language_id', $langId)
                                ->where('product_variant_sets.product_id', $p_id);
                                }])->where('id', $p_id)->first();
                                $value->variantSet = $variantData->variantSet;
                                $value->product_image = ($value->media->isNotEmpty()) ? $value->media->first()->image->path['image_fit'] . '300/300' . $value->media->first()->image->path['image_path'] : '';
                                $value->translation_title = ($value->translation->isNotEmpty()) ? $value->translation->first()->title : $value->sku;
                                $value->translation_description = ($value->translation->isNotEmpty()) ? html_entity_decode(strip_tags($value->translation->first()->body_html),ENT_QUOTES) : '';
                                $value->translation_description = !empty($value->translation_description) ? mb_substr($value->translation_description, 0, 70) . '...' : '';
                                $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                                $value->variant_price = ($value->variant->isNotEmpty()) ? $value->variant->first()->price : 0;
                                $value->variant_id = ($value->variant->isNotEmpty()) ? $value->variant->first()->id : 0;
                                $value->variant_quantity = ($value->variant->isNotEmpty()) ? $value->variant->first()->quantity : 0;

                                foreach ($value->variant as $k => $v) {
                                    $value->variant[$k]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                                }
                            }
                        }
                        if ($products->count() > 0) {
                            $category->products = $products;
                            $category->products_count = $products->count();
                        } else {
                            // if(isset($slug2) && ($slug2 != '')){
                            //     $category->products_count = $products->count();
                            // }
                            // else{
                            $vendor_categories->forget($ckey);
                            // }
                        }
                    }
                    $listData =  array_values($vendor_categories->toArray());
                } else {
                    $vendorCategories = VendorCategory::with(['category.translation' => function ($q) use ($langId) {
                        $q->where('category_translations.language_id', $langId);
                    }])->where('vendor_id', $vendor->id)->where('status', 1)->get();

                    foreach ($vendorCategories as $key => $category) {
                        if ($category->category) {
                            $categoriesList = $categoriesList . ($category->category->translation->first()->name ?? '');
                            if ($key !=  $vendorCategories->count()-1) {
                                $categoriesList = $categoriesList . ', ';
                            }
                        }
                    }

                    $products = Product::with(['media.image',
                            'category.categoryDetail', 'category.categoryDetail.translation' => function ($q) use ($langId) {
                                $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                                ->where('category_translations.language_id', $langId);
                            }, 'inwishlist' => function ($qry) use ($userid) {
                                $qry->where('user_id', $userid);
                            },
                            'addOn' => function ($q1) use ($langId) {
                                $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                                $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                                $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                                $q1->where('set.status', 1)->where('ast.language_id', $langId);
                            },
                            'addOn.setoptions' => function ($q2) use ($langId) {
                                $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                                $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                                $q2->where('apt.language_id', $langId);
                            },
                            'translation' => function ($q) use ($langId) {
                                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                            },
                            'variant' => function ($q) use ($langId) {
                                $q->select('id', 'sku', 'product_id', 'title', 'quantity', 'price', 'barcode');
                            // $q->groupBy('product_id');
                            }, 'variant.checkIfInCartApp', 'checkIfInCartApp',
                        ])->select('id', 'sku', 'description', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only');
                    if (!empty($slug2)) {
                        $category = Category::select('id')->where('slug', $slug2)->first();
                        $products = $products->where('category_id', $category->id??0);
                    }
                    $products = $products->where('is_live', 1)->where('vendor_id', $vendor->id)->paginate($paginate);
                    if (!empty($products)) {
                        foreach ($products as $key => $product) {
                            foreach ($product->addOn as $key => $value) {
                                foreach ($value->setoptions as $k => $v) {
                                    if ($v->price == 0) {
                                        $v->is_free = true;
                                    } else {
                                        $v->is_free = false;
                                    }
                                    $v->multiplier = $clientCurrency->doller_compare;
                                }
                            }

                            $p_id = $product->id;
                            $variantData = $product->with(['variantSet' => function ($z) use ($langId, $p_id) {
                                $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                                $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                                $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                                $z->where('vt.language_id', $langId);
                                $z->where('product_variant_sets.product_id', $p_id)->orderBy('product_variant_sets.variant_type_id', 'asc');
                            },'variantSet.options'=> function ($zx) use ($langId, $p_id) {
                                $zx->join('variant_option_translations as vt', 'vt.variant_option_id', 'variant_options.id')
                            ->select('variant_options.*', 'vt.title', 'pvs.product_variant_id', 'pvs.variant_type_id')
                            ->where('pvs.product_id', $p_id)
                            ->where('vt.language_id', $langId);
                            }])->where('id', $p_id)->first();

                            $product->variantSet = $variantData->variantSet;
                            $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                            $product->product_image = ($product->media->isNotEmpty()) ? $product->media->first()->image->path['image_fit'] . '300/300' . $product->media->first()->image->path['image_path'] : '';
                            $product->translation_title = ($product->translation->isNotEmpty()) ? $product->translation->first()->title : $product->sku;
                            $product->translation_description = ($product->translation->isNotEmpty()) ? html_entity_decode(strip_tags($product->translation->first()->body_html),ENT_QUOTES) : '';
                            $product->translation_description = !empty($product->translation_description) ? mb_substr($product->translation_description, 0, 70) . '...' : '';
                            $product->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                            $product->variant_price = ($product->variant->isNotEmpty()) ? $product->variant->first()->price : 0;
                            $product->variant_id = ($product->variant->isNotEmpty()) ? $product->variant->first()->id : 0;
                            $product->variant_quantity = ($product->variant->isNotEmpty()) ? $product->variant->first()->quantity : 0;
                            foreach ($product->variant as $k => $v) {
                                $product->variant[$k]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                            }
                        }
                    }
                    $vid = $vendor->id;
                    $categoryId = $category->id;
                    $variantSets =  ProductVariantSet::with(['options' => function ($zx) use ($langId) {
                        $zx->join('variant_option_translations as vt', 'vt.variant_option_id', 'variant_options.id');
                        $zx->select('variant_options.*', 'vt.title');
                        $zx->where('vt.language_id', $langId);
                    }])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                ->join('variant_translations as vt', 'vt.variant_id', 'vr.id')
                ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                ->where('vt.language_id', $langId)
                ->whereIn('product_id', function ($qry) use ($vid, $categoryId) {
                    $qry->select('id')->from('products')->where('vendor_id', $vid)->where('category_id', $categoryId);
                })
                ->groupBy('product_variant_sets.variant_type_id')->get();
                }

                $vendor->categoriesList = $categoriesList;
                $response['vendor'] = $vendor;
                $response['products'] = ($vendor->vendor_templete_id != 5) ? $products : [];
                $response['categories'] = ($vendor->vendor_templete_id == 5) ? $listData : [];
                $response['filterData'] = $variantSets??[];
            }else{
                $response['vendor'] = [];
                $response['products'] = [];
                $response['categories'] =  [];
                $response['filterData'] = [];
            }
            return response()->json(['data' => $response]);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage().''.$e->getLineNo(), $e->getCode());
        }
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function vendorFilters(Request $request, $vid = 0){
        if($vid == 0 || $vid < 0){
            return response()->json(['error' => 'No record found.'], 404);
        }
        $langId = Auth::user()->language;
        $curId = Auth::user()->currency;
        $setArray = $optionArray = array();
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        if($request->has('variants') && !empty($request->variants)){
            $setArray = array_unique($request->variants);
        }
        $startRange = 0; $endRange = 20000;
        if($request->has('range') && !empty($request->range)){
            $range = explode(';', $request->range);
            $clientCurrency->doller_compare;
            $startRange = $range[0] * $clientCurrency->doller_compare;
            $endRange = $range[1] * $clientCurrency->doller_compare;
        }
        $multiArray = array();
        if($request->has('options') && !empty($request->options)){
            foreach ($request->options as $key => $value) {
                $multiArray[$request->variants[$key]][] = $value;
            }
        }
        $variantIds = $productIds = array();
        if(!empty($multiArray)){
            foreach ($multiArray as $key => $value) {
                $new_pIds = $new_vIds = array();
                $vResult = ProductVariantSet::select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
                    ->where('product_variant_sets.variant_type_id', $key)
                    ->whereIn('product_variant_sets.variant_option_id', $value);
                if(!empty($variantIds)){
                    $vResult  = $vResult->whereIn('product_variant_sets.product_variant_id', $variantIds);
                }
                $vResult  = $vResult->groupBy('product_variant_sets.product_variant_id')->get();
                if($vResult){
                    foreach ($vResult as $key => $value) {
                        $new_vIds[] = $value->product_variant_id;
                        $new_pIds[] = $value->product_id;
                    }
                }
                $variantIds = $new_vIds;
                $productIds = $new_pIds;
            }
        }
        $order_type = $request->has('order_type') ? $request->order_type : '';
        $products = Product::with(['category.categoryDetail', 'media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },'variant' => function($q) use($langId, $variantIds){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            if(!empty($variantIds)){
                                $q->whereIn('id', $variantIds);
                            }
                            if(!empty($order_type) && $order_type == 'low_to_high'){
                                $q->orderBy('price', 'asc');
                            }
                            if(!empty($order_type) && $order_type == 'high_to_low'){
                                $q->orderBy('price', 'desc');
                            }
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
                    ->where('vendor_id', $vid)
                    ->where('is_live', 1)
                    ->whereIn('id', function($qr) use($startRange, $endRange){
                        $qr->select('product_id')->from('product_variants')->where('price',  '>=', $startRange)->where('price',  '<=', $endRange);
                    });
        if(!empty($productIds)){
            $products = $products->whereIn('id', $productIds);
        }
        if($request->has('brands') && !empty($request->brands)){
            $products = $products->whereIn('brand_id', $request->brands);
        }
        if(!empty($order_type) && $request->order_type == 'rating'){
            $products = $products->orderBy('averageRating', 'desc');
        }
        $paginate = $request->has('limit') ? $request->limit : 12;
        $products = $products->paginate($paginate);
        if(!empty($products)){
            foreach ($products as $key => $product) {
                $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                foreach ($product->variant as $k => $v) {
                    $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                }
            }
        }
        return response()->json([
            'data' => $products,
        ]);
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    // public function vendorCategoryProductsFilters(Request $request, $vid = 0, $cid = 0){
    //     if( ($vid == 0 || $vid < 0) && ($cid == 0 || $cid < 0) ){
    //         return response()->json(['error' => 'No record found.'], 404);
    //     }
    //     $langId = Auth::user()->language;
    //     $curId = Auth::user()->currency;
    //     $setArray = $optionArray = array();
    //     $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
    //     if($request->has('variants') && !empty($request->variants)){
    //         $setArray = array_unique($request->variants);
    //     }
    //     $startRange = 0; $endRange = 20000;
    //     if($request->has('range') && !empty($request->range)){
    //         $range = explode(';', $request->range);
    //         $clientCurrency->doller_compare;
    //         $startRange = $range[0] * $clientCurrency->doller_compare;
    //         $endRange = $range[1] * $clientCurrency->doller_compare;
    //     }
    //     $multiArray = array();
    //     if($request->has('options') && !empty($request->options)){
    //         foreach ($request->options as $key => $value) {
    //             $multiArray[$request->variants[$key]][] = $value;
    //         }
    //     }
    //     $variantIds = $productIds = array();
    //     if(!empty($multiArray)){
    //         foreach ($multiArray as $key => $value) {
    //             $new_pIds = $new_vIds = array();
    //             $vResult = ProductVariantSet::select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
    //                 ->where('product_variant_sets.variant_type_id', $key)
    //                 ->whereIn('product_variant_sets.variant_option_id', $value);
    //             if(!empty($variantIds)){
    //                 $vResult  = $vResult->whereIn('product_variant_sets.product_variant_id', $variantIds);
    //             }
    //             $vResult  = $vResult->groupBy('product_variant_sets.product_variant_id')->get();
    //             if($vResult){
    //                 foreach ($vResult as $key => $value) {
    //                     $new_vIds[] = $value->product_variant_id;
    //                     $new_pIds[] = $value->product_id;
    //                 }
    //             }
    //             $variantIds = $new_vIds;
    //             $productIds = $new_pIds;
    //         }
    //     }
    //     $order_type = $request->has('order_type') ? $request->order_type : '';
    //     $products = Product::with(['category.categoryDetail', 'media.image', 'translation' => function($q) use($langId){
    //                     $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
    //                     },'variant' => function($q) use($langId, $variantIds){
    //                         $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
    //                         if(!empty($variantIds)){
    //                             $q->whereIn('id', $variantIds);
    //                         }
    //                         if(!empty($order_type) && $order_type == 'low_to_high'){
    //                             $q->orderBy('price', 'asc');
    //                         }
    //                         if(!empty($order_type) && $order_type == 'high_to_low'){
    //                             $q->orderBy('price', 'desc');
    //                         }
    //                         $q->groupBy('product_id');
    //                     },
    //                 ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
    //                 ->where('vendor_id', $vid)
    //                 ->where('category_id', $cid)
    //                 ->where('is_live', 1)
    //                 ->whereIn('id', function($qr) use($startRange, $endRange){
    //                     $qr->select('product_id')->from('product_variants')->where('price',  '>=', $startRange)->where('price',  '<=', $endRange);
    //                 });
    //     if(!empty($productIds)){
    //         $products = $products->whereIn('id', $productIds);
    //     }
    //     if($request->has('brands') && !empty($request->brands)){
    //         $products = $products->whereIn('brand_id', $request->brands);
    //     }
    //     if(!empty($order_type) && $request->order_type == 'rating'){
    //         $products = $products->orderBy('averageRating', 'desc');
    //     }
    //     $paginate = $request->has('limit') ? $request->limit : 12;
    //     $products = $products->paginate($paginate);
    //     if(!empty($products)){
    //         foreach ($products as $key => $product) {
    //             $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
    //             foreach ($product->variant as $k => $v) {
    //                 $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
    //             }
    //         }
    //     }
    //     return response()->json([
    //         'data' => $products,
    //     ]);
    // }

    public function postVendorRegister(Request $request){
        try {
            DB::beginTransaction();
            $vendor_registration_documents = VendorRegistrationDocument::with('primary')->get();
            if (empty($request->input('user_id'))) {
                if ($vendor_registration_documents->count() > 0) {
                    $rules_array = [
                        'full_name' => 'required',
                        'email' => 'required|email|unique:users',
                        'phone_number' => 'required|string|min:6|max:15|unique:users',
                        'dialCode' => 'required',
                        'password' => 'required|string|min:6|max:50',
                        'confirm_password' => 'required|same:password',
                        'name' => 'required|string|max:150|unique:vendors',
                        'address' => 'required',
                        // 'vendor_registration_document.*.did_visit' => 'required',
                        'check_conditions' => 'required',
                    ];
                    foreach ($vendor_registration_documents as $vendor_registration_document) {
                        if($vendor_registration_document->is_required == 1){
                            $rules_array[$vendor_registration_document->primary->slug] = 'required';
                        }
                    }

                    $validator = Validator::make($request->all(),
                        $rules_array,
                        ['check_conditions.required' => __('Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy')]
                    );
                } else {
                    $validator = Validator::make($request->all(),
                        [
                            'full_name' => 'required',
                            'email' => 'required|email|unique:users',
                            'phone_number' => 'required|string|min:6|max:15|unique:users',
                            'dialCode' => 'required',
                            'password' => 'required|string|min:6|max:50',
                            'confirm_password' => 'required|same:password',
                            'name' => 'required|string|max:150|unique:vendors',
                            'address' => 'required',
                            'check_conditions' => 'required',
                        ],
                        ['check_conditions.required' => __('Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy')]
                    );
                }
            } else {
                $rules_array = [
                    'name' => 'required|string|max:150|unique:vendors',
                    'address' => 'required',
                    'check_conditions' => 'required',
                ];
                foreach ($vendor_registration_documents as $vendor_registration_document) {
                    if($vendor_registration_document->is_required == 1){
                        $rules_array[$vendor_registration_document->primary->slug] = 'required';
                    }
                }
                $validator = Validator::make($request->all(),
                    $rules_array,
                    ['check_conditions.required' => __('Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy')]
                );
            }
            if($validator->fails()){
                foreach($validator->errors()->toArray() as $error_key => $error_value){
                    $error = __($error_value[0]);
                    return $this->errorResponse($error, 422);
                }
            }
            $client_detail = Client::first();
            $client_preference = ClientPreference::first();
            if(!$request->user_id){
                $user = new User();
                $county = Country::where('code', strtoupper($request->countryData))->first();
                $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
                $user->type = 1;
                $user->status = 1;
                $user->role_id = 1;
                $user->is_admin = 1;
                $user->is_email_verified = 0;
                $user->is_phone_verified = 0;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->title = $request->title;
                $user->country_id = $county->id;
                $user->dial_code = $request->dialCode;
                $user->phone_token_valid_till = $sendTime;
                $user->email_token_valid_till = $sendTime;
                $user->email_token = mt_rand(100000, 999999);
                $user->phone_token = mt_rand(100000, 999999);
                $user->phone_number = $request->phone_number;
                $user->password = Hash::make($request->password);
                $user->save();
                $wallet = $user->wallet;
            }else{
                $user = User::where('id', $request->user_id)->first();
                $user->title = $request->title;
                $user->save();
            }
            $vendor = new Vendor();
            $count = 0;
            if($client_preference){
                if($client_preference->dinein_check == 1){$count++;}
                if($client_preference->takeaway_check == 1){$count++;}
                if($client_preference->delivery_check == 1){$count++;}
            }
            if($count > 1){
                $vendor->dine_in = ($request->has('dine_in') && $request->dine_in == 'on') ? 1 : 0;
                $vendor->takeaway = ($request->has('takeaway') && $request->takeaway == 'on') ? 1 : 0;
                $vendor->delivery = ($request->has('delivery') && $request->delivery == 'on') ? 1 : 0;
            }
            else{
                $vendor->dine_in = $client_preference->dinein_check == 1 ? 1 : 0;
                $vendor->takeaway = $client_preference->takeaway_check == 1 ? 1 : 0;
                $vendor->delivery = $client_preference->delivery_check == 1 ? 1 : 0;
            }
            $vendor->logo = 'default/default_logo.png';
            $vendor->banner = 'default/default_image.png';
            if ($request->hasFile('upload_logo')) {
                $file = $request->file('upload_logo');
                $vendor->logo = Storage::disk('s3')->put('/vendor', $file, 'public');
            }
            if ($request->hasFile('upload_banner')) {
                $file = $request->file('upload_banner');
                $vendor->banner = Storage::disk('s3')->put('/vendor', $file, 'public');
            }
            $vendor->status = 0;
            $vendor->name = $request->name;
            $vendor->email = $request->email;
            $vendor->phone_no = $user->phone_no;
            $vendor->address = $request->address;
            $vendor->website = $request->website;
            $vendor->latitude = $request->latitude;
            $vendor->longitude = $request->longitude;
            $vendor->desc = $request->vendor_description;
            $vendor->slug = Str::slug($request->name, "-");
            $vendor->save();
            $permission_details = Permissions::whereIn('id', [1,2,3,12,17,18,19,20,21])->get();
            if ($vendor_registration_documents->count() > 0) {
                foreach ($vendor_registration_documents as $vendor_registration_document) {
                    $doc_name = str_replace(" ", "_", $vendor_registration_document->primary->slug);
                    if ($vendor_registration_document->file_type != "Text" && $vendor_registration_document->file_type != "selector") {
                        if ($request->hasFile($doc_name)) {
                            $vendor_docs =  new VendorDocs();
                            $vendor_docs->vendor_id = $vendor->id;
                            $vendor_docs->vendor_registration_document_id = $vendor_registration_document->id;
                            $filePath = $this->folderName . '/' . Str::random(40);
                            $file = $request->file($doc_name);
                            $vendor_docs->file_name = Storage::disk('s3')->put($filePath, $file, 'public');
                            $vendor_docs->save();
                        }
                    } else {
                        if (!empty($request->$doc_name)) {
                            $vendor_docs =  new VendorDocs();
                            $vendor_docs->vendor_id = $vendor->id;
                            $vendor_docs->vendor_registration_document_id = $vendor_registration_document->id;
                            $vendor_docs->file_name = $request->$doc_name;
                            $vendor_docs->save();
                        }
                    }
                }
            }
            UserVendor::create(['user_id' => $user->id, 'vendor_id' => $vendor->id]);
            foreach ($permission_details as $permission_detail) {
                UserPermissions::create(['user_id' => $user->id, 'permission_id' => $permission_detail->id]);
            }
            $content = '';
            $email_template = EmailTemplate::where('id', 1)->first();
            if($email_template){
                $content = $email_template->content;
                $content = str_ireplace("{title}", $user->title, $content);
                $content = str_ireplace("{email}", $user->email, $content);
                $content = str_ireplace("{address}", $vendor->address, $content);
                $content = str_ireplace("{website}", $vendor->website, $content);
                $content = str_ireplace("{description}", $vendor->desc, $content);
                $content = str_ireplace("{vendor_name}", $vendor->name, $content);
                $content = str_ireplace("{phone_no}", $user->phone_number, $content);
            }
            $email_data = [
                'title' => $user->title,
                'email' => $user->email,
                'powered_by' => url('/'),
                'banner' => $vendor->banner,
                'website' => $vendor->website,
                'address' => $vendor->address,
                'vendor_logo' => $vendor->logo,
                'vendor_name' => $vendor->name,
                'description' => $vendor->desc,
                'phone_no' => $user->phone_number,
                'email_template_content' => $content,
                'subject' => $email_template->subject,
                'client_name' => $client_detail->name,
                'customer_name' => ucwords($user->name),
                'logo' => $client_detail->logo['original'],
                'mail_from' => $client_preference->mail_from,
            ];
            $admin_email_data = [
                'title' => $user->title,
                'email' => $user->email,
                'powered_by' => url('/'),
                'banner' => $vendor->banner,
                'website' => $vendor->website,
                'address' => $vendor->address,
                'vendor_logo' => $vendor->logo,
                'vendor_name' => $vendor->name,
                'description' => $vendor->desc,
                'phone_no' => $user->phone_number,
                'email_template_content' => $content,
                'client_name' => $client_detail->name,
                'subject' => 'New Vendor Registration',
                'customer_name' => ucwords($user->name),
                'logo' => $client_detail->logo['original'],
                'mail_from' => $client_preference->mail_from,
            ];
            try{
                dispatch(new \App\Jobs\sendVendorRegistrationEmail($email_data))->onQueue('verify_email');
                dispatch(new \App\Jobs\sendVendorRegistrationEmail($admin_email_data))->onQueue('verify_email');
            }catch(Exception $e) {

            }
            DB::commit();
            return $this->successResponse('', 'Vendor Registration Created Successfully!', 200);
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Vendor Registration Created Successfully!',
            // ]);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
            // return response()->json([
            //     'status' => 'error',
            //     'message' => $e->getMessage(),
            // ]);
        }
    }

    public function getVendorDetails(Request $request)
    {
        try {
			$validator = Validator::make($request->all(), [
				'vendor_id' => 'required',	
			]);

			if ($validator->fails()) {			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
            $vendordetail = Vendor::where('id',$request->vendor_id)->first();
            if($vendordetail)
            {
                return $this->successResponse($vendordetail);
            }else{
                return $this->errorResponse('Vendor not found', 422);
            }
        } catch (Exception $e) {            
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

}
