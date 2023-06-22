<?php
namespace App\Http\Traits;

use DB;
use Auth;
use HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\{Client as CP, Order, ProductAddon, ProductAttribute, ProductCelebrity, ProductCrossSell, ProductRelated, ProductTag, ProductUpSell, SubscriptionInvoicesVendor};
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{User, UserVendor, VendorAdditionalInfo, VendorMultiBanner,WebStylingOption, Product, ClientLanguage, ProductCategory, ProductVariant, ProductTranslation, MargProduct};


trait MargTrait{

    /**
     * getMultiBanner
     *
     * @param  mixed $vendor_id
     * @return void
     */

    public function getData($crulUrl, $payload){
        $ch = curl_init( $crulUrl );
        $payload = json_encode( $payload);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $encrypted = $response;
        return $encrypted;
        // dd($encrypted);
    }


    public function addProduct($request)
	{
        try{
            DB::beginTransaction();	
            $is_exist = Product::where('sku', $request->ProductCode)->first();

			if(isset($request->ProductCode) && isset($request->name) && is_null($is_exist)){
                $url_slug = $this->validateSlug($request->name);
                $request->catcode = 5;

                $product = new Product();
                $product->sku = $request->ProductCode;      // $request->sku;
                $product->url_slug = $url_slug;             // $request->url_slug;
                $product->title = $request->name;           // $request->product_name;        
                $product->category_id = $request->catcode;  // $request->category_id;
                $product->type_id = 1;
                $product->vendor_id = 8;                    //$request->vendor_id;
                $client_lang = ClientLanguage::where('is_primary', 1)->first();
                if (!$client_lang) {
                    $client_lang = ClientLanguage::where('is_active', 1)->first();
                }
                $product->save();
                
                if ($product->id > 0) {
                    $marg_product  =  new MargProduct();
                        $marg_product->product_id   =       $product->id;
                        $marg_product->rid          =       $request->rid;
                        $marg_product->catcode      =       $request->catcode;               
                        $marg_product->code         =       $request->code;               
                        $marg_product->name         =       $request->name;               
                        $marg_product->stock        =       $request->stock;
                        $marg_product->remark       =       $request->remark;
                        $marg_product->company      =       $request->company;
                        $marg_product->shopcode     =       $request->shopcode;
                        $marg_product->MRP          =       $request->MRP;
                        $marg_product->Rate         =       $request->Rate;
                        $marg_product->Deal         =       $request->Deal;
                        $marg_product->Free         =       $request->Free;
                        $marg_product->PRate        =       $request->PRate;
                        $marg_product->Is_Deleted   =       $request->Is_Deleted;
                        $marg_product->curbatch     =       $request->curbatch;
                        $marg_product->exp          =       $request->exp;
                        $marg_product->gcode        =       $request->gcode;
                        $marg_product->MargCode     =       $request->MargCode;
                        $marg_product->Conversion   =       $request->Conversion;
                        $marg_product->Salt         =       $request->Salt;
                        $marg_product->ENCODE       =       $request->ENCODE;
                        $marg_product->remarks      =       $request->remarks;
                        $marg_product->Gcode6       =       $request->Gcode6;
                        $marg_product->ProductCode  =       $request->ProductCode;
                    $marg_product->save();

                    $datatrans[] = [
                        'title' => $request->name??null, // $request->product_name??null,
                        'body_html' => '',
                        'meta_title' => '',
                        'meta_keyword' => '',
                        'meta_description' => '',
                        'product_id' => $product->id,
                        'language_id' => $client_lang->language_id
                    ];
    
                    $product_category = new ProductCategory();
                    $product_category->product_id = $product->id;
                    $product_category->category_id = $request->catcode; // $request->category_id;
                    $product_category->save();

                    $proVariant = new ProductVariant();
                    $proVariant->sku = $request->ProductCode; // $request->sku;
                    $proVariant->product_id = $product->id;            
                    $proVariant->price = $request->MRP;            
                    $proVariant->quantity = $request->stock;            
                    $proVariant->barcode = $this->generateBarcodeNumber();
                    $proVariant->save();

                    $pt = ProductTranslation::insert($datatrans);
                    DB::commit();
                }
            }else{
                \Log::info($request->name." Already exist! (code)".$request->ProductCode);
                return false;
            }

		} catch (Exception $e) {
            DB::rollback();
			\Log::info("error".$e->getMessage());
			return $e->getMessage();
		}
		
	}		

	// public function productDetail(Request $request)
	// {
	// 	try{
	// 		$validator = Validator::make($request->all(), [
	// 			'product_id' => 'required',				
	// 		]);

	// 		if ($validator->fails()) {			
	// 			return $this->errorResponse($validator->errors()->first(), 422);
	// 		}
	// 		$user = Auth::user();	
	// 		$productid = $request->product_id;

	// 		$data = $this->preProductDetail($productid);		
			
			
	// 		// product attributes
	// 		if( clientPrefrenceModuleStatus('p2p_check') ) {
				
	// 			$product = Product::findOrFail($request->product_id);

	// 			// All attribute list
	// 			$productAttributes = Attribute::with('option', 'varcategory.cate.primary', 'productAttribute')
	// 			->select('attributes.*')
	// 			->join('attribute_categories', 'attribute_categories.attribute_id', 'attributes.id')
	// 			->where('attribute_categories.category_id', $product->category_id)
	// 			->where('attributes.status', '!=', 2)
	// 			->orderBy('position', 'asc')->get();
				
	// 			$data['attributes'] = $productAttributes;
	// 			$data['p2p_active'] = true;
				
	// 		}
	// 		else {
	// 			$data['attributes'] = [];
	// 			$data['p2p_active'] = false;
				
	// 		}

	// 		return $this->successResponse($data, 'Product detail!', 200);

	// 	} catch (Exception $e) {
	// 		return $this->errorResponse($e->getMessage(), $e->getCode());
	// 	}
	// }

    // public function updateProduct(Request $request)
	// {
	// 	$product = Product::where('id', $request->product_id)->firstOrFail();
	// 	try{
	// 		$validator = Validator::make($request->all(), [
	// 			'product_id' => 'required',		
	// 			'product_name' => 'required|string',
	// 			'sku' => 'required|unique:products,sku,'.$product->id,
	// 			'url_slug' => 'required|unique:products,url_slug,'.$product->id,		
	// 		]);

	// 		if ($validator->fails()) {			
	// 			return $this->errorResponse($validator->errors()->first(), 422);
	// 		}

	// 		// Save Product Attribute
	// 		if( checkTableExists('product_attributes') ) {
	// 			if( !empty($request->attribute) ) {
	// 				$attribute = json_decode($request->attribute, true);
					
	// 				if( !empty($attribute) ) {
	// 					$insert_arr = [];
    //                     $insert_count = 0;

    //                     foreach($attribute as $key => $value) {
    //                         if( !empty($value) && !empty($value['option'] && is_array($value) )) {
                                
    //                             if(!empty($value['type']) && $value['type'] == 1 ) { // dropdown
    //                                 $value_arr = @$value['value'];
                                    
    //                                 foreach( $value['option'] as $key1 => $val1 ) {
    //                                     if( @in_array($val1['option_id'], $value_arr) ) {

    //                                         $insert_arr[$insert_count]['product_id'] = $request->product_id;
    //                                         $insert_arr[$insert_count]['attribute_id'] = $value['id'];
    //                                         $insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
    //                                         $insert_arr[$insert_count]['attribute_option_id'] = $val1['option_id'];
    //                                         $insert_arr[$insert_count]['key_value'] = $val1['option_id'];
    //                                         $insert_arr[$insert_count]['is_active'] = 1;
    //                                     }
    //                                     $insert_count++;
    //                                 }
    //                             }
    //                             else {
	// 								$value_arr = @$value['value'];
									
	// 								// //\Log::info($option['option_id']);
    //                                 foreach($value['option'] as $option_key => $option) {
    //                                     if(!empty($value['type']) && $value['type'] == 4 ) { // textbox
	// 										$insert_arr[$insert_count]['product_id'] = $request->product_id;
	// 										$insert_arr[$insert_count]['attribute_id'] = $value['id'];
	// 										$insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
	// 										$insert_arr[$insert_count]['attribute_option_id'] = $option['option_id'];
	// 										$insert_arr[$insert_count]['key_value'] = (!empty($value['value']) && !empty($value['value'][0]) ? $value['value'][0] : '');
	// 										$insert_arr[$insert_count]['is_active'] = 1;
	// 									}
	// 									elseif( @in_array($option['option_id'], $value_arr) ) {
											
	// 										$insert_arr[$insert_count]['product_id'] = $request->product_id;
	// 										$insert_arr[$insert_count]['attribute_id'] = $value['id'];
	// 										$insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
	// 										$insert_arr[$insert_count]['attribute_option_id'] = $option['option_id'];
	// 										$insert_arr[$insert_count]['key_value'] = $option['option_id'];
	// 										$insert_arr[$insert_count]['is_active'] = 1;
	// 									}
										
    //                                     $insert_count++;
    //                                 }
    //                             }
    //                         }

                        
    //                     }
    //                     if( !empty($insert_arr) ) {
    //                         ProductAttribute::where('product_id',$request->product_id)->delete();
    //                         ProductAttribute::insert($insert_arr);
    //                     }
	// 				}
	// 			}
	// 		}

	// 		$user = Auth::user();	
	// 		$productid = $product->id;

	// 		$product_category = ProductCategory::where('product_id', $productid)->where('category_id', $request->category_id)->first();
	// 		if(!$product_category){
	// 			$product_category = new ProductCategory();
	// 			$product_category->product_id = $productid;
	// 			$product_category->category_id = $request->category_id;
	// 			$product_category->save();
	// 		}

	// 		if ($product->is_live == 0) {
	// 			$product->publish_at = ($request->is_live == 1) ? date('Y-m-d H:i:s') : '';
	// 		}

	// 		foreach ($request->only('country_origin_id', 'weight', 'weight_unit', 'is_live', 'brand_id') as $k => $val) {
	// 			$product->{$k} = $val;
	// 		}

	// 		$product->sku = $request->sku;
	// 		$product->url_slug = $request->url_slug;
	// 		$product->tags        = $request->tags??null;
	// 		$product->category_id = $request->category_id;
	// 		$product->inquiry_only = $request->inquiry_only ?? 0;
	// 		$product->tax_category_id = $request->tax_category;
	// 		$product->is_new                    = $request->is_new ?? 0;
	// 		$product->is_featured               = $request->is_featured ?? 0;
	// 		$product->is_physical               = $request->is_physical ?? 0;
	// 		$product->pharmacy_check            = $request->pharmacy_check ?? 0;
	// 		$product->has_inventory             = $request->has_inventory ?? 0;
	// 		$product->sell_when_out_of_stock    = $request->sell_stock_out ?? 0;
	// 		$product->requires_shipping         = $request->require_ship ?? 0;
	// 		$product->Requires_last_mile        = $request->last_mile ?? 0;
	// 		$product->need_price_from_dispatcher = $request->need_price_from_dispatcher ?? 0;
	// 		$product->mode_of_service        = $request->mode_of_service??null;
	// 		$product->delay_order_hrs        = $request->delay_order_hrs??0;
	// 		$product->delay_order_min        = $request->delay_order_min??0;
	// 		$product->pickup_delay_order_hrs        = $request->pickup_delay_order_hrs??0;
	// 		$product->pickup_delay_order_min        = $request->pickup_delay_order_min??0;
	// 		$product->dropoff_delay_order_hrs        = $request->dropoff_delay_order_hrs??0;
	// 		$product->dropoff_delay_order_min        = $request->dropoff_delay_order_min??0;
	// 		$product->minimum_order_count        = $request->minimum_order_count??0;
	// 		$product->batch_count        = $request->batch_count??1;
	// 		if (empty($product->publish_at)) {
	// 			$product->publish_at = ($request->is_live == 1) ? date('Y-m-d H:i:s') : '';
	// 		}
	// 		$product->has_variant = ($request->has('variant_ids') && count($request->variant_ids) > 0) ? 1 : 0;
	// 		if($product){
	// 			if(isset($product->category) && in_array($product->category->categoryDetail->type_id,[8,9]))
	// 			$product->sell_when_out_of_stock = 1;
	// 		}
	// 		$product->save();
	// 		if ($product->id > 0) {
	// 			$trans = ProductTranslation::where('product_id', $product->id)->where('language_id', $request->language_id)->first();
	// 			if (!$trans) {
	// 				$trans = new ProductTranslation();
	// 				$trans->product_id = $product->id;
	// 				$trans->language_id = $request->language_id;
	// 			}
	// 			$trans->title               = $request->product_name;
	// 			$trans->body_html           = $request->body_html;
	// 			$trans->meta_title          = $request->meta_title;
	// 			$trans->meta_keyword        = $request->meta_keyword;
	// 			$trans->meta_description    = $request->meta_description;
	// 			$trans->save();
	// 			$varOptArray = $prodVarSet = $updateImage = array();
	// 			$i = 0;

	// 			// if ($request->has('file')) {
	// 			// 	//$imageId = [];
	// 			// 	$files = $request->file('file');
	// 			// 	if (is_array($files)) {
	// 			// 		foreach ($files as $file) {
	// 			// 			$img = new VendorMedia();
	// 			// 			$img->media_type = 1;
	// 			// 			$img->vendor_id = $product->vendor_id;
	// 			// 			$img->path = Storage::disk('s3')->put($this->folderName, $file, 'public');
	// 			// 			$img->save();
	// 			// 			$path1 = $img->path['proxy_url'] . '40/40' . $img->path['image_path'];
	// 			// 			if ($img->id > 0) {
	// 			// 				$imageId = $img->id;
	// 			// 				$image = new ProductImage();
	// 			// 				$image->product_id = $product->id;
	// 			// 				$image->is_default = 1;
	// 			// 				$image->media_id = $img->id;
	// 			// 				$image->save();
	// 			// 				// if ($request->has('variantId')) {
	// 			// 				// 	$resp .= '<div class="col-md-3 col-sm-4 col-12 mb-3">
	// 			// 				// 				<div class="product-img-box">
	// 			// 				// 					<div class="form-group checkbox checkbox-success">
	// 			// 				// 						<input type="checkbox" id="image' . $image->id . '" class="imgChecks" imgId="' . $image->id . '" checked variant_id="' . $request->variantId . '">
	// 			// 				// 						<label for="image' . $image->id . '">
	// 			// 				// 						<img src="' . $path1 . '" alt="">
	// 			// 				// 						</label>
	// 			// 				// 					</div>
	// 			// 				// 				</div>
	// 			// 				// 			</div>';
	// 			// 				// }
	// 			// 			}
	// 			// 		}
						
	// 			// 	} else {
	// 			// 		$img = new VendorMedia();
	// 			// 		$img->media_type = 1;
	// 			// 		$img->vendor_id = $product->vendor_id;
	// 			// 		$img->path = Storage::disk('s3')->put($this->folderName, $files, 'public');
	// 			// 		$img->save();
	// 			// 		$imageId = $img->id;
	// 			// 		if ($img->id > 0) {
	// 			// 			$imageId = $img->id;
	// 			// 			$image = new ProductImage();
	// 			// 			$image->product_id = $product->id;
	// 			// 			$image->is_default = 1;
	// 			// 			$image->media_id = $img->id;
	// 			// 			$image->save();
	// 			// 		}
	// 			// 	}					
	// 			// }

	// 			// $productImageSave = array();
	// 			// if ($request->has('fileIds')) {
	// 			// 	foreach ($request->fileIds as $key => $value) {
	// 			// 		$productImageSave[] = [
	// 			// 			'product_id' => $product->id,
	// 			// 			'media_id' => $value,
	// 			// 			'is_default' => 1
	// 			// 		];
	// 			// 	}
	// 			// }
	// 			// ProductImage::insert($productImageSave);
	// 			$cat = $addonsArray = $upArray = $crossArray = $relateArray = $tagSetArray = array();
	// 			$delete = ProductAddon::where('product_id', $product->id)->delete();
	// 			$delete = ProductUpSell::where('product_id', $product->id)->delete();
	// 			$delete = ProductCrossSell::where('product_id', $product->id)->delete();
	// 			$delete = ProductRelated::where('product_id', $product->id)->delete();
	// 			$delete = ProductCelebrity::where('product_id', $product->id)->delete();
	// 			$delete = ProductTag::where('product_id', $product->id)->delete();
				
	// 			if ($request->has('addon_sets') && count($request->addon_sets) > 0) {
	// 				foreach ($request->addon_sets as $key => $value) {
	// 					$addonsArray[] = [
	// 						'product_id' => $product->id,
	// 						'addon_id' => $value
	// 					];
	// 				}
	// 				ProductAddon::insert($addonsArray);
	// 			}
	
	// 			if ($request->has('tag_sets') && count($request->tag_sets) > 0) {
	// 				foreach ($request->tag_sets as $key => $value) {
	// 					$tagSetArray[] = [
	// 						'product_id' => $product->id,
	// 						'tag_id' => $value
	// 					];
	// 				}
	// 				ProductTag::insert($tagSetArray);
	// 			}
	
	// 			if ($request->has('celebrities') && count($request->celebrities) > 0) {
	// 				foreach ($request->celebrities as $key => $value) {
	// 					$celebArray[] = [
	// 						'celebrity_id' => $value,
	// 						'product_id' => $product->id
	// 					];
	// 				}
	// 				ProductCelebrity::insert($celebArray);
	// 			}
	
	// 			if ($request->has('up_cell') && count($request->up_cell) > 0) {
	// 				foreach ($request->up_cell as $key => $value) {
	// 					$upArray[] = [
	// 						'product_id' => $product->id,
	// 						'upsell_product_id' => $value
	// 					];
	// 				}
	// 				ProductUpSell::insert($upArray);
	// 			}
	
	// 			if ($request->has('cross_cell') && count($request->cross_cell) > 0) {
	// 				foreach ($request->cross_cell as $key => $value) {
	// 					$crossArray[] = [
	// 						'product_id' => $product->id,
	// 						'cross_product_id' => $value
	// 					];
	// 				}
	// 				ProductCrossSell::insert($crossArray);
	// 			}
	
	// 			if ($request->has('releted_product') && count($request->releted_product) > 0) {
	// 				foreach ($request->releted_product as $key => $value) {
	// 					$relateArray[] = [
	// 						'product_id' => $product->id,
	// 						'related_product_id' => $value
	// 					];
	// 				}
	// 				ProductRelated::insert($relateArray);
	// 			}
	
	// 			$existv = array();
	
	// 			if ($request->has('variant_ids')) {
	// 				foreach ($request->variant_ids as $key => $value) {
	// 					$variantData = ProductVariant::where('id', $value)->first();
	// 					$existv[] = $value;
	
	// 					if ($variantData) {
	// 						$variantData->title             = $request->variant_titles[$key] ?? "";
	// 						$variantData->price             = $request->variant_price[$key] ?? "0";
	// 						$variantData->compare_at_price  = $request->variant_compare_price[$key] ?? "0";
	// 						$variantData->cost_price        = $request->variant_cost_price[$key] ?? "0";
	// 						$variantData->quantity          = $request->variant_quantity[$key] ?? "0";
	// 						$variantData->tax_category_id   = $request->tax_category;
	// 						$variantData->save();
	// 					}
	// 				}
	// 				$delOpt = ProductVariant::whereNotIN('id', $existv)->where('product_id', $product->id)->whereNull('title')->delete();
	// 			} else {
	// 				$variantData = ProductVariant::where('product_id', $product->id)->first();
	// 				if (!$variantData) {
	// 					$variantData = new ProductVariant();
	// 					$variantData->product_id    = $product->id;
	// 					$variantData->sku           = $product->sku;
	// 					$variantData->title         = $product->sku;
	// 					$variantData->barcode       = $this->generateBarcodeNumber();
	// 				}
	// 				$variantData->price             = $request->price;
	// 				$variantData->compare_at_price  = $request->compare_at_price;
	// 				$variantData->cost_price        = $request->cost_price;
	// 				$variantData->quantity          = $request->quantity;
	// 				$variantData->tax_category_id   = $request->tax_category;
	// 				$variantData->save();
	// 			}
	// 		}

	// 		$data = Product::with('brand', 'variant.set', 'variant.vimage.pimage.image', 'primary', 'category.cat', 'variantSet', 'vatoptions', 'addOn', 'media.image', 'related', 'upSell', 'crossSell', 'celebrities')->where('id', $product->id)->firstOrFail();	
	// 		return $this->successResponse($data, 'Product Updated successfully!', 200);

	// 	} catch (Exception $e) {
	// 		return $this->errorResponse($e->getMessage(), $e->getCode());
	// 	}
	// }

    function validateSlug($slug) {
        // Convert the slug to lowercase and remove non-alphanumeric characters
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($slug));
    
        // Remove leading and trailing hyphens
        $slug = trim($slug, '-');
    
        // Validate the slug format
        if (preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            return $slug;
        }
    
        return false; // Slug validation failed
    }

    public function generateBarcodeNumber()
    {
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }


	 // Insert order api url-  https://corporate.margerp.com/api/eOnlineData/InsertOrderDetail
    // Parameters-   { "OrderID":"", "OrderNo": "78789", "CustomerID": "5929958", "MargID": "339157", "Type": "S", "Sid": "194130", "ProductCode": "1000004", "Quantity": "2", "Free": "0,0", "Lat": "", "Lng": "", "Address": "", "GpsID": "0", "UserType": "1", "Points": "0.00", "Discounts": "0", "Transport": "", "Delivery": "", "Bankname": "", "BankAdd1": "", "BankAdd2": "", "shipname": "", "shipAdd1": "", "shipAdd2": "", "shipAdd3": "", "paymentmode": "1", "paymentmodeAmount": "0", "payment_remarks": "", "order_remarks": "","CustName":"ramU" ,"CustMobile": "9289757820", "CompanyCode": "RakeshApi2", "OrderFrom": "RakeshApi2" }

	public function makeInsertOrderMargApi($order)
	{
		$productCode = [];
		$productQuantity = [];
		if(empty($order))
		{
			return false;
		}else{

			foreach($order->products as $product)
			{
				$productCode[] = $product->product->sku;
				$productQuantity[] = $product->quantity;
			}

		} 

        $hub_key = @getAdditionalPreference(['marg_access_token','is_marg_enable','marg_decrypt_key', 'marg_company_code']);

        if($hub_key['is_marg_enable'] == 1){
            $decryptionKey  = $hub_key['marg_decrypt_key'];
            $MargID  = $hub_key['marg_access_token'];
            $CompanyCode  = $hub_key['marg_company_code'];
            $detail         = [];
            $MargMST2017 = "https://corporate.margerp.com/api/eOnlineData/InsertOrderDetail";
            $detail = ["OrderID"=>"", "OrderNo"=> $order->order_number, "CustomerID"=> "2", "MargID"=> $MargID, "Type"=> "S", "Sid"=> "194130", "ProductCode"=> implode(',',$productCode), "Quantity"=>  implode(',',$productQuantity), "Free"=> "0,0", "Lat"=> "", "Lng"=> "", "Address"=> "", "GpsID"=> "0", "UserType"=> "1", "Points"=> "0.00", "Discounts"=> "0", "Transport"=> "", "Delivery"=> "", "Bankname"=> "", "BankAdd1"=> "", "BankAdd2"=> "", "shipname"=> "", "shipAdd1"=> "", "shipAdd2"=> "", "shipAdd3"=> "", "paymentmode"=> "1", "paymentmodeAmount"=> "0", "payment_remarks"=> "", "order_remarks"=> "","CustName"=>"ramU" ,"CustMobile"=> "9289757820", "CompanyCode"=> $CompanyCode, "OrderFrom"=> $CompanyCode];

             // Get the encrypted data from the request
            $encryptedData = $this->getData($MargMST2017, $detail);

            $encryptedData = json_decode($encryptedData);
            if(isset($encryptedData) && !isset($encryptedData->Message))
            {
				$updateOrder = Order::findOrFail($order->id);
				$updateOrder->marg_status = $encryptedData??1;
				$updateOrder->save();

                return true;
                
            }else{

                return false;

            }
            return true;

        }else{
            return false;
        }
		
	}



}
