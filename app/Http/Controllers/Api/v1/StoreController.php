<?php

namespace App\Http\Controllers\Api\v1;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\v1\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\{User, Vendor, Order,UserVendor, PaymentOption, VendorCategory, Product, VendorOrderStatus, OrderStatusOption,ClientCurrency, Category_translation, OrderVendor, LuxuryOption, ClientLanguage, ProductCategory, ProductVariant, ProductTranslation, Variant, Brand, AddonSet, TaxCategory, ClientPreference, Celebrity, ProductImage, ProductAddon, ProductUpSell, ProductCrossSell, ProductRelated, ProductCelebrity, ProductTag, VendorMedia, ProductVariantSet, CartProduct, ProductVariantImage, UserWishlist};

class StoreController extends BaseController{
    use ApiResponser;
	private $folderName = 'prods';

    public function getMyStoreProductList(Request $request){
    	try {
			$category_list = [];
    		$user = Auth::user();
    		$langId = $user->language;
    		$is_selected_vendor_id = 0;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $client_currency_detail = ClientCurrency::where('currency_id', $user->currency)->first();
            $selected_vendor_id = $request->has('selected_vendor_id') ? $request->selected_vendor_id : '';
            $selected_category_id = $request->has('selected_category_id') ? $request->selected_category_id : '';
			$user_vendor_ids = UserVendor::where('user_id', $user->id)->pluck('vendor_id');
			if($user_vendor_ids){
				$is_selected_vendor_id = $selected_vendor_id ? $selected_vendor_id : $user_vendor_ids->first();
			}
			$vendor_list = Vendor::whereIn('id', $user_vendor_ids)->get(['id','name','logo']);
			foreach ($vendor_list as $vendor) {
				$vendor->is_selected = ($is_selected_vendor_id == $vendor->id) ? true : false;
			}
			$vendor_categories = VendorCategory::where('vendor_id', $is_selected_vendor_id)
							->whereHas('category', function($query) {
							   	$query->whereIn('type_id', [1]);
							})->where('status', 1)->get('category_id');
			$vendor_category_id = 0;
			if($vendor_categories->count()){
				$vendor_category_id = $vendor_categories->first()->category_id;
			}
			$is_selected_category_id = $selected_category_id ? $selected_category_id : $vendor_category_id;
			foreach ($vendor_categories as $vendor_category) {
				$Category_translation = Category_translation::where('category_id', $vendor_category->category->id)->where('language_id', $langId)->first();
				if(!$Category_translation){
					$Category_translation = Category_translation::where('category_id', $vendor_category->category->id)->first();
				}
				$category_list []= array(
					'id' => $vendor_category->category->id,
					'name' => $Category_translation ? $Category_translation->name : $vendor_category->category->slug,
					'type_id' => $vendor_category->category->type_id,
					'is_selected' => $is_selected_category_id == $vendor_category->category_id ? true : false
				);
			}
			$products = Product::select('id', 'sku', 'url_slug','is_live','category_id')->has('vendor')
						->with(['media.image', 'translation' => function($q) use($langId){
                        	$q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    	},'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                    	},
                    ])->where('category_id', $is_selected_category_id);
			if($selected_vendor_id > 0){
				$products = $products->where('vendor_id', $selected_vendor_id);
			}
			$products = $products->where('is_live', 1)->paginate($paginate);
			foreach ($products as $product) {
                foreach ($product->variant as $k => $v) {
                    $product->variant[$k]->multiplier = $client_currency_detail->doller_compare;
                }
            }
			$data = ['vendor_list' => $vendor_list,'category_list' => $category_list,'products'=> $products];
            return $this->successResponse($data, '', 200);
    	} catch (Exception $e) {
    		
    	}
    }
    public function getMyStoreDetails(Request $request){
    	try {
    		$user = Auth::user();
    		$is_selected_vendor_id = 0;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $selected_vendor_id = $request->has('selected_vendor_id') ? $request->selected_vendor_id : '';
			$user_vendor_ids = UserVendor::where('user_id', $user->id)->pluck('vendor_id');
			if($user_vendor_ids){
				$is_selected_vendor_id = $selected_vendor_id ? $selected_vendor_id : $user_vendor_ids->first();
			}
			$order_list = Order::with('orderStatusVendor')
						->whereHas('vendors', function($query) use ($is_selected_vendor_id){
						   $query->where('vendor_id', $is_selected_vendor_id);
						})
						->where(function ($q1) {
							$q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
							$q1->orWhere(function ($q2) {
								$q2->where('payment_option_id', 1);
							});
						})
						->orderBy('id', 'DESC')->paginate($paginate);
			foreach ($order_list as $order) {
				$order_status = [];
				$product_details = [];
				$order_item_count = 0;
				$order->user_name = $order->user->name;
				$order->user_image = $order->user->image;
				$order->date_time = dateTimeInUserTimeZone($order->created_at, $user->timezone);
				$order->payment_option_title = __($order->paymentOption->title);
				foreach ($order->vendors as $vendor) {
					$vendor_order_status = VendorOrderStatus::where('order_id', $order->id)->where('vendor_id', $is_selected_vendor_id)->orderBy('id', 'DESC')->first();
					if($vendor_order_status){
						$order_status_option_id = $vendor_order_status->order_status_option_id;
						$current_status = OrderStatusOption::select('id','title')->find($order_status_option_id);
						if($order_status_option_id == 2){
							$upcoming_status = OrderStatusOption::select('id','title')->where('id', '>', 3)->first();
						}elseif ($order_status_option_id == 3) {
							$upcoming_status = null;
						}elseif ($order_status_option_id == 6) {
							$upcoming_status = null;
						}else{
							$upcoming_status = OrderStatusOption::select('id','title')->where('id', '>', $order_status_option_id)->first();
						}
						$order->order_status = [
							'current_status' => $current_status,
							'upcoming_status' => $upcoming_status,
						];
					}
				}
				foreach ($order->products as $product) {
    				$order_item_count += $product->quantity;
    				if($is_selected_vendor_id == $product->vendor_id){
	    				$product_details[]= array(
	    					'image_path' => $product->media->first() ? $product->media->first()->image->path : $product->image,
	    					'price' => $product->price,
	    					'qty' => $product->quantity,
	    				);
    				}
				}
				if(!empty($order->scheduled_date_time)){
					$order->scheduled_date_time = dateTimeInUserTimeZone($order->scheduled_date_time, $user->timezone);
				}
				$luxury_option_name = '';
				if($order->luxury_option_id > 0){
					$luxury_option = LuxuryOption::where('id', $order->luxury_option_id)->first();
					if($luxury_option->title == 'takeaway'){
						$luxury_option_name = $this->getNomenclatureName('Takeaway', $user->language, false);
					}elseif($luxury_option->title == 'dine_in'){
						$luxury_option_name = __('Dine-In');
					}else{
						$luxury_option_name = __('Delivery');
					}
				}
				$order->luxury_option_name = $luxury_option_name;
				$order->product_details = $product_details;
				$order->item_count = $order_item_count;
				unset($order->user);
				unset($order->products);
				unset($order->paymentOption);
				unset($order->payment_option_id);
			}
			$vendor_list = Vendor::whereIn('id', $user_vendor_ids)->get(['id','name','logo']);
			foreach ($vendor_list as $vendor) {
				$vendor->is_selected = ($is_selected_vendor_id == $vendor->id) ? true : false;
			}
			$data = ['order_list' => $order_list, 'vendor_list' => $vendor_list];
            return $this->successResponse($data, '', 200);
    	} catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
    }
    public function getMyStoreRevenueDetails(Request $request){
        $dates = [];
        $sales = [];
        $revenue = [];
        $type = $request->type;
        $vendor_id = $request->vendor_id;
        $monthly_sales_query = OrderVendor::select(\DB::raw('sum(payable_amount) as y'), \DB::raw('count(*) as z'), \DB::raw('date(created_at) as x'));
        switch ($type) {
        	case 'monthly':
        		$created_at = $monthly_sales_query->whereRaw('MONTH(created_at) = ?', [date('m')]);
    		break;
    		case 'weekly':
    			Carbon::setWeekStartsAt(Carbon::SUNDAY);
        		$created_at = $monthly_sales_query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); 
    		break;
    		case 'yearly':
        		$created_at = $monthly_sales_query->whereRaw('YEAR(created_at) = ?', [date('Y')]);
    		break;
        	default:
    			$created_at = $monthly_sales_query->whereRaw('MONTH(created_at) = ?', [date('m')]);
    		break;
        }
 		$monthlysales = $monthly_sales_query->where('vendor_id', $vendor_id)->groupBy('x')->get();
        foreach ($monthlysales as $monthly) {
            $dates[] = $monthly->x;
            $sales[] = $monthly->z;
            $revenue[] = $monthly->y;
        }
        $data = ['dates' => $dates, 'revenue' => $revenue, 'sales' => $sales];
        return $this->successResponse($data, '', 200);
    }

	public function my_pending_orders(Request $request){
		try {
    		$user = Auth::user();
            $paginate = $request->has('limit') ? $request->limit : 12;
			$order_list = Order::with(['orderStatusVendor','vendors.products','vendors.status'])->select('id','order_number','payable_amount','payment_option_id','user_id');
			if($user->is_superadmin == 1){
				$order_list = $order_list->whereHas('vendors', function($query){
					$query->where('order_status_option_id', 1);
				 })->with('vendors', function ($query){
					$query->where('order_status_option_id', 1);
			   });
			} else {
				$user_vendor_ids = UserVendor::where('user_id', $user->id)->pluck('vendor_id');
				$order_list = $order_list->whereHas('vendors', function($query) use ($user_vendor_ids){
					$query->where('order_status_option_id', 1);
					$query->whereIn('vendor_id', $user_vendor_ids);
				 })->with('vendors', function ($query){
					$query->where('order_status_option_id', 1);
			   });
			}
			$order_list = $order_list->orderBy('id', 'DESC')->paginate($paginate);
			foreach ($order_list as $order) {
				$order_status = [];
				$product_details = [];
				$order_item_count = 0;
				$order->user_name = $order->user->name;
				$order->user_image = $order->user->image;
				$order->date_time = dateTimeInUserTimeZone($order->created_at, $user->timezone);
				$order->payment_option_title = $order->paymentOption->title;
				foreach ($order->vendors as $vendor) {
					$vendor_order_status = VendorOrderStatus::where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
					if($vendor_order_status){
						$order_status_option_id = $vendor_order_status->order_status_option_id;
						$current_status = OrderStatusOption::select('id','title')->find($order_status_option_id);
						$upcoming_status = OrderStatusOption::select('id','title')->where('id', '>', $order_status_option_id)->first();
						$order->order_status = [
							'current_status' => $current_status,
							'upcoming_status' => $upcoming_status,
						];
					}
				}
				foreach ($order->products as $product) {
					$order_item_count += $product->quantity;
					$product_details[] = array(
						'image_path' => $product->media->first() ? $product->media->first()->image->path : $product->image,
						'price' => $product->price,
						'qty' => $product->quantity,
					);
				}
				$order->product_details = $product_details;
				$order->item_count = $order_item_count;
				unset($order->user);
				unset($order->products);
				unset($order->paymentOption);
				unset($order->payment_option_id);
			}
			$data = ['order_list' => $order_list];
            return $this->successResponse($data, '', 200);
    	} catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
	}

	public function VendorCategory(Request $request)
	{
		try {			
			$validator = Validator::make($request->all(), [
				'vendor_id'	=>	'required'
			]);

			if ($validator->fails()) {			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
			$user = Auth::user();	
			$vendorid = $request->vendor_id;
			$product_categories = VendorCategory::with(['category', 'category.translation' => function($q) {
				$q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
				;
			}])->where('status', 1)->where('vendor_id', $vendorid)->groupBy('category_id')->get();
			if(count($product_categories)==0)
			{
				return $this->errorResponse('No category found', 422);
			}
			$p_categories = collect();
			$product_categories_hierarchy = '';
			if ($product_categories) {
				foreach($product_categories as $pc){
					$p_categories->push($pc->category);
				}
				$product_categories_build = $this->buildTree($p_categories->toArray());
				$product_categories_hierarchy = $this->printCategoryOptionsHeirarchy($product_categories_build);
				foreach($product_categories_hierarchy as $k => $cat){
					$myArr = array(1,3,7,8,9);
					if (isset($cat['type_id']) && !in_array($cat['type_id'], $myArr)) {
						unset($product_categories_hierarchy[$k]);
					}
				}
			}
			$output = [];
			foreach($product_categories_hierarchy as $singlecat)
			{
				$output[] = $singlecat;
			}			
			$data = $output;
			return $this->successResponse($data, '', 200);
    	} catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
	}

	public function addProduct(Request $request)
	{
		//return $product = Product::with('brand', 'variant.set', 'variant.vimage.pimage.image', 'primary', 'category.cat', 'variantSet', 'vatoptions', 'addOn', 'media.image', 'related', 'upSell', 'crossSell', 'celebrities')->where('id', 232)->firstOrFail();
		try{
			$validator = Validator::make($request->all(), [
				'sku' => 'required|unique:products',
				'url_slug' => 'required|unique:products',
				'category_id' => 'required',
				'product_name' => 'required',
				'vendor_id'	=>	'required'
			]);

			if ($validator->fails()) {
			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
			
			$user = Auth::user();		
			
			$product = new Product();
			$product->sku = $request->sku;
			$product->url_slug = $request->url_slug;
			$product->title = $request->product_name;        
			$product->category_id = $request->category_id;
			$product->type_id = 1;
			$product->vendor_id = $request->vendor_id;
			$client_lang = ClientLanguage::where('is_primary', 1)->first();
			if (!$client_lang) {
				$client_lang = ClientLanguage::where('is_active', 1)->first();
			}
			$product->save();
			if ($product->id > 0) {
				$datatrans[] = [
					'title' => $request->product_name??null,
					'body_html' => '',
					'meta_title' => '',
					'meta_keyword' => '',
					'meta_description' => '',
					'product_id' => $product->id,
					'language_id' => $client_lang->language_id
				];
				$product_category = new ProductCategory();
				$product_category->product_id = $product->id;
				$product_category->category_id = $request->category_id;
				$product_category->save();
				$proVariant = new ProductVariant();
				$proVariant->sku = $request->sku;
				$proVariant->product_id = $product->id;            
				$proVariant->barcode = $this->generateBarcodeNumber();
				$proVariant->save();
				ProductTranslation::insert($datatrans);
				//$product_detail = Product::with('brand', 'variant.set', 'variant.vimage.pimage.image', 'primary', 'category.cat', 'variantSet', 'vatoptions', 'addOn', 'media.image', 'related', 'upSell', 'crossSell', 'celebrities')->where('id', $product->id)->firstOrFail();
				$product_detail = Product::where('id', $product->id)->firstOrFail();
				//$data = $this->preProductDetail($product->id);
				$data = ['product_detail' => $product_detail];
				return $this->successResponse($data, 'Product added successfully!', 200);
			}
		} catch (Exception $e) {
			return $this->errorResponse($e->getMessage(), $e->getCode());
		}
		
	}		

	public function productDetail(Request $request)
	{
		try{
			$validator = Validator::make($request->all(), [
				'product_id' => 'required',				
			]);

			if ($validator->fails()) {			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
			$user = Auth::user();	
			$productid = $request->product_id;

			$data = $this->preProductDetail($productid);			
			return $this->successResponse($data, 'Product detail!', 200);

		} catch (Exception $e) {
			return $this->errorResponse($e->getMessage(), $e->getCode());
		}
	}

	public function makeVariantRows(Request $request)
	{
		try{
			$validator = Validator::make($request->all(), [
				'product_id' 	=> 'required',		
				'optionIds'  	=>	'required',
				'variantIds'	=>	'required'
			]);

			if ($validator->fails()) {			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
			//return $request->all();
			$multiArray = array();
			$variantNames = array();
			$product = Product::where('id', $request->product_id)->firstOrFail();
			$msgRes = 'Please check variants to create variant set.';
			if (!$request->has('optionIds') || !$request->has('variantIds')) {
				return $this->errorResponse($msgRes, 422);            
			}
			foreach ($request->optionIds as $key => $value) {
				$name = explode(';', $request->variantIds[$key]);
				if (!in_array($name[1], $variantNames)) {
					$variantNames[] = $name[1];
				}
				$multiArray[$request->variantIds[$key]][] = $value;
			}

			$combination = $this->array_combinations($multiArray);
			$new_combination = array();
			$edit = 0;

			if ($request->has('existing') && !empty($request->existing)) {
				$existingComb = $request->existing;
				$edit = 1;
				foreach ($combination as $key => $value) {
					$comb = $arrayVal = '';
					foreach ($value as $k => $v) {
						$arrayVal = explode(';', $v);
						$comb .= $arrayVal[0] . '*';
					}

					$comb = rtrim($comb, '*');

					if (!in_array($comb, $existingComb)) {
						$new_combination[$key] = $value;
					}
				}
				$combination = $new_combination;
				$msgRes = 'No new variant set found.';
			}

			if (count($combination) < 1) {
				return $this->errorResponse($msgRes, 422); 
			}

			$data = $this->combinationVariants($combination, $multiArray, $variantNames, $product->id, $request->sku, $edit);
			return $this->successResponse($data, 'Variant detail!', 200);
		} catch (Exception $e) {
			return $this->errorResponse($e->getMessage(), $e->getCode());
		}
	}

	function combinationVariants($combination, $multiArray, $variantNames, $product_id, $sku = '',  $edit = 0)
    {
        $arrVal = array();
        foreach ($multiArray as $key => $value) {
            $varStr = $optStr = array();
            $vv = explode(';', $key);

            foreach ($value as $k => $v) {
                $ov = explode(';', $v);
                $optStr[] = $ov[0];
            }

            $arrVal[$vv[0]] = $optStr;
        }
        $name1 = '';

        $all_variant_sets = array();
		$output_data = [];
        $inc = 0;
        foreach ($combination as $key => $value) {
            $names = array();
            $ids = array();
            foreach ($value as $k => $v) {
                $variant = explode(';', $v);
                $ids[] = $variant[0];
                $names[] = $variant[1];
            }
            $proSku = $sku . '-' . implode('*', $ids);
            $proVariant = ProductVariant::where('sku', $proSku)->first();
            if (!$proVariant) {
                $proVariant = new ProductVariant();
                $proVariant->sku = $proSku;
                $proVariant->title = $sku . '-' . implode('-', $names);
                $proVariant->product_id = $product_id;
                $proVariant->barcode = $this->generateBarcodeNumber();
                $proVariant->save();

                foreach ($ids as $id1) {
                    $all_variant_sets[$inc] = [
                        'product_id' => $product_id,
                        'product_variant_id' => $proVariant->id,
                        'variant_option_id' => $id1,
                    ];

                    foreach ($arrVal as $key => $value) {

                        if (in_array($id1, $value)) {
                            $all_variant_sets[$inc]['variant_type_id'] = $key;
                        }
                    }
                    $inc++;
                }
            }
			$varient = array('id'=>$proVariant->id, 'product_id'=>$product_id, 'title'=>$proVariant->title, 'names'=>implode(", ", $names));
			$output_data[] = $varient;
            
        }
        ProductVariantSet::insert($all_variant_sets);
        
        return $output_data;
    }

	public function updateProduct(Request $request)
	{
		$product = Product::where('id', $request->product_id)->firstOrFail();
		try{
			$validator = Validator::make($request->all(), [
				'product_id' => 'required',		
				'product_name' => 'required|string',
				'sku' => 'required|unique:products,sku,'.$product->id,
				'url_slug' => 'required|unique:products,url_slug,'.$product->id,		
			]);

			if ($validator->fails()) {			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
			$user = Auth::user();	
			$productid = $product->id;

			$product_category = ProductCategory::where('product_id', $productid)->where('category_id', $request->category_id)->first();
			if(!$product_category){
				$product_category = new ProductCategory();
				$product_category->product_id = $productid;
				$product_category->category_id = $request->category_id;
				$product_category->save();
			}

			if ($product->is_live == 0) {
				$product->publish_at = ($request->is_live == 1) ? date('Y-m-d H:i:s') : '';
			}

			foreach ($request->only('country_origin_id', 'weight', 'weight_unit', 'is_live', 'brand_id') as $k => $val) {
				$product->{$k} = $val;
			}

			$product->sku = $request->sku;
			$product->url_slug = $request->url_slug;
			$product->tags        = $request->tags??null;
			$product->category_id = $request->category_id;
			$product->inquiry_only = $request->inquiry_only ?? 0;
			$product->tax_category_id = $request->tax_category;
			$product->is_new                    = $request->is_new ?? 0;
			$product->is_featured               = $request->is_featured ?? 0;
			$product->is_physical               = $request->is_physical ?? 0;
			$product->pharmacy_check            = $request->pharmacy_check ?? 0;
			$product->has_inventory             = $request->has_inventory ?? 0;
			$product->sell_when_out_of_stock    = $request->sell_stock_out ?? 0;
			$product->requires_shipping         = $request->require_ship ?? 0;
			$product->Requires_last_mile        = $request->last_mile ?? 0;
			$product->need_price_from_dispatcher = $request->need_price_from_dispatcher ?? 0;
			$product->mode_of_service        = $request->mode_of_service??null;
			$product->delay_order_hrs        = $request->delay_order_hrs??0;
			$product->delay_order_min        = $request->delay_order_min??0;
			$product->pickup_delay_order_hrs        = $request->pickup_delay_order_hrs??0;
			$product->pickup_delay_order_min        = $request->pickup_delay_order_min??0;
			$product->dropoff_delay_order_hrs        = $request->dropoff_delay_order_hrs??0;
			$product->dropoff_delay_order_min        = $request->dropoff_delay_order_min??0;
			$product->minimum_order_count        = $request->minimum_order_count??0;
			$product->batch_count        = $request->batch_count??1;
			if (empty($product->publish_at)) {
				$product->publish_at = ($request->is_live == 1) ? date('Y-m-d H:i:s') : '';
			}
			$product->has_variant = ($request->has('variant_ids') && count($request->variant_ids) > 0) ? 1 : 0;
			if($product){
				if(isset($product->category) && in_array($product->category->categoryDetail->type_id,[8,9]))
				$product->sell_when_out_of_stock = 1;
			}
			$product->save();
			if ($product->id > 0) {
				$trans = ProductTranslation::where('product_id', $product->id)->where('language_id', $request->language_id)->first();
				if (!$trans) {
					$trans = new ProductTranslation();
					$trans->product_id = $product->id;
					$trans->language_id = $request->language_id;
				}
				$trans->title               = $request->product_name;
				$trans->body_html           = $request->body_html;
				$trans->meta_title          = $request->meta_title;
				$trans->meta_keyword        = $request->meta_keyword;
				$trans->meta_description    = $request->meta_description;
				$trans->save();
				$varOptArray = $prodVarSet = $updateImage = array();
				$i = 0;

				// if ($request->has('file')) {
				// 	//$imageId = [];
				// 	$files = $request->file('file');
				// 	if (is_array($files)) {
				// 		foreach ($files as $file) {
				// 			$img = new VendorMedia();
				// 			$img->media_type = 1;
				// 			$img->vendor_id = $product->vendor_id;
				// 			$img->path = Storage::disk('s3')->put($this->folderName, $file, 'public');
				// 			$img->save();
				// 			$path1 = $img->path['proxy_url'] . '40/40' . $img->path['image_path'];
				// 			if ($img->id > 0) {
				// 				$imageId = $img->id;
				// 				$image = new ProductImage();
				// 				$image->product_id = $product->id;
				// 				$image->is_default = 1;
				// 				$image->media_id = $img->id;
				// 				$image->save();
				// 				// if ($request->has('variantId')) {
				// 				// 	$resp .= '<div class="col-md-3 col-sm-4 col-12 mb-3">
				// 				// 				<div class="product-img-box">
				// 				// 					<div class="form-group checkbox checkbox-success">
				// 				// 						<input type="checkbox" id="image' . $image->id . '" class="imgChecks" imgId="' . $image->id . '" checked variant_id="' . $request->variantId . '">
				// 				// 						<label for="image' . $image->id . '">
				// 				// 						<img src="' . $path1 . '" alt="">
				// 				// 						</label>
				// 				// 					</div>
				// 				// 				</div>
				// 				// 			</div>';
				// 				// }
				// 			}
				// 		}
						
				// 	} else {
				// 		$img = new VendorMedia();
				// 		$img->media_type = 1;
				// 		$img->vendor_id = $product->vendor_id;
				// 		$img->path = Storage::disk('s3')->put($this->folderName, $files, 'public');
				// 		$img->save();
				// 		$imageId = $img->id;
				// 		if ($img->id > 0) {
				// 			$imageId = $img->id;
				// 			$image = new ProductImage();
				// 			$image->product_id = $product->id;
				// 			$image->is_default = 1;
				// 			$image->media_id = $img->id;
				// 			$image->save();
				// 		}
				// 	}					
				// }

				// $productImageSave = array();
				// if ($request->has('fileIds')) {
				// 	foreach ($request->fileIds as $key => $value) {
				// 		$productImageSave[] = [
				// 			'product_id' => $product->id,
				// 			'media_id' => $value,
				// 			'is_default' => 1
				// 		];
				// 	}
				// }
				// ProductImage::insert($productImageSave);
				$cat = $addonsArray = $upArray = $crossArray = $relateArray = $tagSetArray = array();
				$delete = ProductAddon::where('product_id', $product->id)->delete();
				$delete = ProductUpSell::where('product_id', $product->id)->delete();
				$delete = ProductCrossSell::where('product_id', $product->id)->delete();
				$delete = ProductRelated::where('product_id', $product->id)->delete();
				$delete = ProductCelebrity::where('product_id', $product->id)->delete();
				$delete = ProductTag::where('product_id', $product->id)->delete();
				
				if ($request->has('addon_sets') && count($request->addon_sets) > 0) {
					foreach ($request->addon_sets as $key => $value) {
						$addonsArray[] = [
							'product_id' => $product->id,
							'addon_id' => $value
						];
					}
					ProductAddon::insert($addonsArray);
				}
	
				if ($request->has('tag_sets') && count($request->tag_sets) > 0) {
					foreach ($request->tag_sets as $key => $value) {
						$tagSetArray[] = [
							'product_id' => $product->id,
							'tag_id' => $value
						];
					}
					ProductTag::insert($tagSetArray);
				}
	
				if ($request->has('celebrities') && count($request->celebrities) > 0) {
					foreach ($request->celebrities as $key => $value) {
						$celebArray[] = [
							'celebrity_id' => $value,
							'product_id' => $product->id
						];
					}
					ProductCelebrity::insert($celebArray);
				}
	
				if ($request->has('up_cell') && count($request->up_cell) > 0) {
					foreach ($request->up_cell as $key => $value) {
						$upArray[] = [
							'product_id' => $product->id,
							'upsell_product_id' => $value
						];
					}
					ProductUpSell::insert($upArray);
				}
	
				if ($request->has('cross_cell') && count($request->cross_cell) > 0) {
					foreach ($request->cross_cell as $key => $value) {
						$crossArray[] = [
							'product_id' => $product->id,
							'cross_product_id' => $value
						];
					}
					ProductCrossSell::insert($crossArray);
				}
	
				if ($request->has('releted_product') && count($request->releted_product) > 0) {
					foreach ($request->releted_product as $key => $value) {
						$relateArray[] = [
							'product_id' => $product->id,
							'related_product_id' => $value
						];
					}
					ProductRelated::insert($relateArray);
				}
	
				$existv = array();
	
				if ($request->has('variant_ids')) {
					foreach ($request->variant_ids as $key => $value) {
						$variantData = ProductVariant::where('id', $value)->first();
						$existv[] = $value;
	
						if ($variantData) {
							$variantData->title             = $request->variant_titles[$key];
							$variantData->price             = $request->variant_price[$key];
							$variantData->compare_at_price  = $request->variant_compare_price[$key];
							$variantData->cost_price        = $request->variant_cost_price[$key];
							$variantData->quantity          = $request->variant_quantity[$key];
							$variantData->tax_category_id   = $request->tax_category;
							$variantData->save();
						}
					}
					$delOpt = ProductVariant::whereNotIN('id', $existv)->where('product_id', $product->id)->whereNull('title')->delete();
				} else {
					$variantData = ProductVariant::where('product_id', $product->id)->first();
					if (!$variantData) {
						$variantData = new ProductVariant();
						$variantData->product_id    = $product->id;
						$variantData->sku           = $product->sku;
						$variantData->title         = $product->sku;
						$variantData->barcode       = $this->generateBarcodeNumber();
					}
					$variantData->price             = $request->price;
					$variantData->compare_at_price  = $request->compare_at_price;
					$variantData->cost_price        = $request->cost_price;
					$variantData->quantity          = $request->quantity;
					$variantData->tax_category_id   = $request->tax_category;
					$variantData->save();
				}
			}

			$data = Product::with('brand', 'variant.set', 'variant.vimage.pimage.image', 'primary', 'category.cat', 'variantSet', 'vatoptions', 'addOn', 'media.image', 'related', 'upSell', 'crossSell', 'celebrities')->where('id', $product->id)->firstOrFail();	
			return $this->successResponse($data, 'Product Updated successfully!', 200);

		} catch (Exception $e) {
			return $this->errorResponse($e->getMessage(), $e->getCode());
		}
	}

	public function deleteProduct(Request $request)
	{		
		try{
			$validator = Validator::make($request->all(), [
				'product_id' => 'required',					
			]);

			if ($validator->fails()) {			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
			$id = $request->product_id;
			DB::beginTransaction();
            $product = Product::find($id);
			if(!$product)
			{
				return $this->errorResponse('Product not found', 422);
			}
            $dynamic = time();

            Product::where('id', $id)->update(['sku' => $product->sku.$dynamic ,'url_slug' => $product->url_slug.$dynamic]);

            $tot_var  = ProductVariant::where('product_id', $id)->get();
            foreach($tot_var as $varr)
            {
                $dynamic = time().substr(md5(mt_rand()), 0, 7);
                ProductVariant::where('id', $varr->id)->update(['sku' => $product->sku.$dynamic]);
            }

            Product::where('id', $id)->delete();

            CartProduct::where('product_id', $id)->delete();
            UserWishlist::where('product_id', $id)->delete();
			
            DB::commit();
			return $this->successResponse('', 'Product deleted successfully!', 200);
		} catch (Exception $e) {
			DB::rollback();
			return $this->errorResponse($e->getMessage(), $e->getCode());
		}
	}

	public function deleteProductVariant(Request $request)
	{		
		try{
			$validator = Validator::make($request->all(), [
				'product_id' => 'required',	
				'variant_id' => 'required',	
			]);

			if ($validator->fails()) {			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
			$product_id = $request->product_id;
			$variant_id = $request->variant_id;
			
			$product_variant = ProductVariant::where('id', $variant_id)->where('product_id', $product_id)->first();
			if(!$product_variant)
			{
				return $this->errorResponse('Product variant not found', 422);
			}
			$product_variant->status = 0;
			$product_variant->save();
			// if ($request->is_product_delete > 0) {
			// 	Product::where('id', $request->product_id)->delete();
			// }
			return $this->successResponse('', 'Product variant deleted successfully!', 200);
		} catch (Exception $e) {
			DB::rollback();
			return $this->errorResponse($e->getMessage(), $e->getCode());
		}
	}

	public function productImages(Request $request){
        
		try{
			$validator = Validator::make($request->all(), [
				'product_id' => 'required',					
			]);

			if ($validator->fails()) {			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
			$product_id = $request->product_id;
			$variant_id = $request->variant_id;
			$imageid = $request->image_id;
			
			$resp = '';
			$product = Product::findOrFail($product_id);
			if(!$product)
			{
				return $this->errorResponse('Product not found', 422);
			}

			//delete prev variant images
			if($variant_id && $variant_id!="")
			{
				$deleteVarImg = ProductVariantImage::where('product_variant_id', $variant_id)->delete();
			}
			if ($request->has('image_id')) {				
				foreach ($imageid as $key => $value) {
					$saveImage[] = [
						'product_variant_id' => $variant_id,
						'product_image_id' => $value
					];
				}
				ProductVariantImage::insert($saveImage);
			}

			if ($request->has('file')) {
				$imageId = '';
				$files = $request->file('file');
				if(is_array($files)) {
					foreach ($files as $file) {
						$img = new VendorMedia();
						$img->media_type = 1;
						$img->vendor_id = $product->vendor_id;
						$img->path = Storage::disk('s3')->put($this->folderName, $file, 'public');
						$img->save();
						$path1 = $img->path['proxy_url'] . '40/40' . $img->path['image_path'];
						if ($img->id > 0) {
							$imageId = $img->id;
							$image = new ProductImage();
							$image->product_id = $product->id;
							$image->is_default = 1;
							$image->media_id = $imageId;
							$image->save();
							if($image->id > 0 && $variant_id!="")
							{
								$varientimage = new ProductVariantImage();
								$varientimage->product_variant_id = $variant_id;
								$varientimage->product_image_id = $image->id;
								$varientimage->save();
							}							
						}
					}
					//return response()->json(['htmlData' => $resp]);
				} else {
					$img = new VendorMedia();
					$img->media_type = 1;
					$img->vendor_id = $product->vendor_id;
					$img->path = Storage::disk('s3')->put($this->folderName, $files, 'public');
					$img->save();					
					if ($img->id > 0) {
						$imageId = $img->id;
						$image = new ProductImage();
						$image->product_id = $product->id;
						$image->is_default = 1;
						$image->media_id = $img->id;
						$image->save();
						if($image->id > 0 && $variant_id!="")
						{
							$varientimage = new ProductVariantImage();
							$varientimage->product_variant_id = $variant_id;
							$varientimage->product_image_id = $image->id;
							$varientimage->save();
						}						
					}
				}
			}
			$images = ProductImage::with('image')->where('product_images.product_id', $product->id)->get();

			return $this->successResponse($images, 'Product image added successfully!', 200);			
			
		} catch (Exception $e) {
			DB::rollback();
			return $this->errorResponse($e->getMessage(), $e->getCode());
		}
    }
	
	public function deleteProductImage(Request $request){
		try{
			$validator = Validator::make($request->all(), [
				'product_id' => 'required',	
				'media_id' => 'required',	
			]);

			if ($validator->fails()) {			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
			$product_id = $request->product_id;
			$image_id = $request->media_id;
			$product = Product::findOrfail($product_id);
			$img = VendorMedia::findOrfail($image_id);
			$img->delete();
			return $this->successResponse('', 'Product image deleted successfully!', 200);
		} catch (Exception $e) {			
			return $this->errorResponse($e->getMessage(), $e->getCode());
		}			
    }

	public function getProductImages(Request $request){
		try{
			$validator = Validator::make($request->all(), [
				'product_id' => 'required',	
				'variant_id' => 'required',	
			]);

			if ($validator->fails()) {			
				return $this->errorResponse($validator->errors()->first(), 422);
			}
			$product_id = $request->product_id;
			$variant_id = $request->variant_id;

			$product = Product::where('id', $product_id)->first();
			if(!$product)
			{
				return $this->errorResponse('Product not found', 422);
			}
			$variantImages = array();
			if ($variant_id > 0) {
				$varImages = ProductVariantImage::where('product_variant_id', $variant_id)->get();
				if ($varImages) {
					foreach ($varImages as $key => $value) {
						$variantImages[] = $value->product_image_id;
					}
				}
			}			
			//$variId = ($request->has('variant_id') && $request->variant_id > 0) ? $request->variant_id : 0;
			$images = ProductImage::with('image')->where('product_images.product_id', $product->id)->get();
			$k=0;
			foreach($images as $singleimage)
			{
				if(in_array($singleimage->id,$variantImages))
				{
					$images[$k]->is_selected = 1;
				}else{
					$images[$k]->is_selected = 0;
				}
				$k++;
			}
			return $this->successResponse($images, 'Product images details!', 200);
		} catch (Exception $e) {			
			return $this->errorResponse($e->getMessage(), $e->getCode());
		}			
    }

	private function preProductDetail($productid)
	{		
		$user = Auth::user();
		$product = Product::with('brand', 'variant.set', 'variant.vimage.pimage.image', 'primary', 'category.cat', 'variantSet', 'vatoptions', 'addOn', 'media.image', 'related', 'upSell', 'crossSell', 'celebrities')->where('id', $productid)->firstOrFail();
		$productVariants = Variant::with('option', 'varcategory.cate.primary')
		->select('variants.*')
		->join('variant_categories', 'variant_categories.variant_id', 'variants.id')
		->where('variant_categories.category_id', $product->category_id)
		->where('variants.status', '!=', 2)
		->orderBy('position', 'asc')->get();

		$brands = Brand::join('brand_categories as bc', 'bc.brand_id', 'brands.id')
		->select('brands.id', 'brands.title', 'brands.image')
		->where('bc.category_id', $product->category_id)->where('status',1)->get();
		
		$clientLanguages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
		->select('lang.id as langId', 'lang.name as langTitle', 'lang.sort_code', 'client_languages.is_primary')
		->where('client_languages.client_code', Auth::user()->code)
		->where('client_languages.is_active', 1)
		->orderBy('client_languages.is_primary', 'desc')->get();

		$addons = AddonSet::with('option')->select('id', 'title')
		->where('status', '!=', 2)
		->where('vendor_id', $product->vendor_id)
		->orderBy('position', 'asc')->get();

		$taxCate = TaxCategory::all();

		$otherProducts = Product::with('primary')->select('id', 'sku')->where('is_live', 1)->where('id', '!=', $product->id)->where('vendor_id', $product->vendor_id)->get();
		$configData = ClientPreference::select('celebrity_check', 'pharmacy_check', 'need_dispacher_ride', 'need_delivery_service', 'enquire_mode','need_dispacher_home_other_service','delay_order','product_order_form','business_type','minimum_order_batch')->first();
		$celebrities = Celebrity::select('id', 'name')->where('status', '!=', 3)->get();
		$data = ['product_detail' => $product, 'product_variants'=>$productVariants, 'brands'=> $brands, 'client_languages' => $clientLanguages, 'addons' => $addons, 'tax_category'=>$taxCate,'other_products' => $otherProducts, 'config_data' => $configData, 'celebrities' => $celebrities];
		return $data;
	}

	private function generateBarcodeNumber()
    {
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }

	private function array_combinations($arrays)
    {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i++) {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j++)
                array_push($result[$i], current($arrays[$j]));
            for ($j = ($sizeIn - 1); $j >= 0; $j--) {
                if (next($arrays[$j]))
                    break;
                elseif (isset($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }

}
