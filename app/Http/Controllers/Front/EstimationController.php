<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Exception;
use Timezonelist;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ClientCurrency;
use App\Models\VendorCategory;
use App\Models\EstimateProduct;
use App\Models\EstimatedProduct;
use App\Models\ProductVariantSet;
use Illuminate\Support\Facades\DB;
use App\Models\EstimateAddonOption;
use App\Http\Controllers\Controller;
use App\Models\EstimatedProductCart;
use App\Models\EstimatedProductAddons;
use App\Models\EstimateProductTranslation;
use App\Http\Controllers\Front\FrontController;



class EstimationController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $preferences = Session::get('preferences');      
        $langId = Session::get('customerLanguage');
        $curId  = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $navCategories = $this->categoryNav($langId);

        $estimateProductsWithAddons = EstimateProduct::with(['estimate_product_addons.estimate_addon_set.option','category.primary' , 'estimate_product_translation' => function($q) use($langId) {
            $q->where('language_id', '=', $langId);
        }])->groupBy('category_id')->orderBy('id', 'ASC')->get();  

        $estimatedProductCart = EstimatedProductCart::where('user_id', Auth::user()->id)->first();
        if($estimatedProductCart){
            $estimatedProducts = EstimatedProduct::where('estimated_cart_id', $estimatedProductCart->id)->get();
        }else{
            $estimatedProducts = [];
        }
        return view('frontend.estimation.index')->with(['navCategories' => $navCategories, 'products' => $estimateProductsWithAddons, 'estimatedProducts' => $estimatedProducts]);
    }


    public function estimateProductAddons(Request $request)
    {
        // dd(getClientPreferenceDetail());
        $langId = Session::get('customerLanguage')??'1';
        $product_id = $request->slug;
        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
        $variant_id = ($request->has('variant')) ? $request->variant : 0;

           $AddonData = EstimateProduct::with(['estimate_product_addons.estimate_addon_set.option','category.primary' , 'estimate_product_translation' => function($q) use($langId) {
                $q->where('language_id', '=', $langId);
            }])->where('id', $product_id)->first(); 
        if(!empty($AddonData)){
            $AddonData->product_image =  $AddonData->icon['image_fit'].'300/300'.$AddonData->icon['image_path'];
            $AddonData->translation_title = $AddonData->estimate_product_translation->name;
            $AddonData->translation_description = $AddonData->estimate_product_translation->name;
            $AddonData->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
            $variant_price = 0;
            $AddonData->variant_price = 22;
        }
     
        return response()->json(array('status' => 'Success', 'data' => $AddonData));
    }


    public function addToEstimateCart(Request $request)
    {
        try {
            DB::beginTransaction();


            // $quantity = $request->get('quantity');
            $quantity = 1;

            // Query To Check if the Cart is Already Exists
            $estimatedProductCart = EstimatedProductCart::where('user_id', Auth::user()->id)->first();

            // If Cart Not Exists Then Generate New Cart Else Just Increment the item_count Column by 1.
            if(!$estimatedProductCart){
                $estimatedProductCart = new EstimatedProductCart();
                $estimatedProductCart->unique_identifier = Str::random(18);
                $estimatedProductCart->user_id           = Auth::user()->id;
                $estimatedProductCart->item_count        = ($quantity != '') ? $quantity : 1;
                $estimatedProductCart->currency_id       = Session::get('customerCurrency');
                $estimatedProductCart->save();
            }else{
                $estimatedProductCart->item_count = $estimatedProductCart->item_count+$quantity;
                $estimatedProductCart->save();
            }
            DB::commit();

            // Query To Check if the Product is Already in Cart.
            $estimatedProduct = EstimatedProduct::where('estimated_cart_id', $estimatedProductCart->id)->where('product_id', $request->get('estimate_product_id'))->first();

            // Check $this in Products Table - If Found Just Increasing the Quantity else Creating Now One.
            if(!$estimatedProduct){
                // Insert Estimated Product
                $estimatedProduct = new EstimatedProduct();
                $estimatedProduct->estimated_cart_id = $estimatedProductCart->id;
                $estimatedProduct->product_id        = $request->get('estimate_product_id');
                $estimatedProduct->quantity          = $quantity;
                $estimatedProduct->save();
            }else{
                $estimatedProduct->quantity          = $estimatedProduct->quantity+$quantity;
                $estimatedProduct->save();
            }

             // Make Array from (estimate_option_id) string
            // $estimate_option_ids = explode(',',$request->get('estimate_option_id'));
            
            //Delete previous added addons
            EstimatedProductAddons::where('estimated_product_id', $estimatedProduct->id)->delete();
            // Loop through the (estimate_option_ids)
            foreach($request->estimate_option_id as $estimate_option_id){
               
                $checkAddonExists = EstimatedProductAddons::where('estimated_product_id', $estimatedProduct->id)->where('estimated_addon_option_id', $estimate_option_id)->first();
                if(!$checkAddonExists){
                    $estimatedProductAddon = new EstimatedProductAddons();
                    $estimatedProductAddon->estimated_product_id      = $estimatedProduct->id;
                    // Get Estimate Addon ID Frome Estimate Option ID
                    $estimateAddonOption = EstimateAddonOption::find($estimate_option_id);
                    $estimatedProductAddon->estimated_addon_id        = $estimateAddonOption->estimate_addon_id;
                    $estimatedProductAddon->estimated_addon_option_id = $estimate_option_id;
                    $estimatedProductAddon->save();
                }
            }            

            $success['message'] = 'Product has been added to cart.';
            $success['estimatedProductCart'] = $estimatedProductCart;
            return response()->json($success, 200);
           
          } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
          }
    }


    public function estimationList(Request $request)
    {
        // Get language ID from Request Header - By Ovi
        $langId  = Session::get('customerLanguage')??'1';
        $user_id = Auth::user()->id;
        $currency = Session::get('customerCurrency');
        // Get Cart from Estimated Product Cart based on user_id - By Ovi
        $userCart = EstimatedProductCart::where('user_id', $user_id)->first();
        if(!$userCart){
            $message = "Your Cart is Empty";
            return redirect()->back()->withErrors(['message', $message]);
        }
        // Get Products from added Estimated Cart using cart id - By Ovi
        $userProducts = EstimatedProduct::where('estimated_cart_id', $userCart->id )->get();
        //dd($userProducts);
        $clientCurrency = ClientCurrency::where('currency_id', $currency)->first();
        $doller_compare = ($clientCurrency) ? $clientCurrency->doller_compare : 1;
        // Search for similar products and addons. - By Ovi
        $searchResult = $this->searchProducts($userProducts, $langId);
        $navCategories = $this->categoryNav($langId);
        //dd($searchResult['addonKey']);
        // foreach($searchResult as $vendor){
        //   foreach($vendor->productsLive as $product){
            
        //     $p_id = $product->id;

        //     $variantData = $product->with(['variantSet' => function ($z) use ($langId, $p_id) {
        //         $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
        //         $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
        //         $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
        //         $z->where('vt.language_id', $langId);
        //         $z->where('product_variant_sets.product_id', $p_id)->where('vr.status', 1)->orderBy('product_variant_sets.variant_type_id', 'asc');
        //     },'variantSet.option2'=> function ($zx) use ($langId, $p_id) {
        //         $zx->where('vt.language_id', $langId)
        //         ->where('product_variant_sets.product_id', $p_id);
        //     }])->where('id', $p_id)->first();

        //     $product->variantSet = $variantData->variantSet;
        //     $product->variant_multiplier = 1;
        //     $product->variant_price = ($product->variant->isNotEmpty()) ? $product->variant->first()->price : 0;
        //     $vendor->variant_multiplier = $doller_compare;
        //     $vendor->variant_price = ($product->variant->isNotEmpty()) ? $product->variant->first()->price : 0;
        //     $product->variant_id = ($product->variant->isNotEmpty()) ? $product->variant->first()->id : 0;
        //     $product->variant_quantity = ($product->variant->isNotEmpty()) ? $product->variant->first()->quantity : 0;

        //     foreach($userProducts as $userProduct){
        //         if($userProduct->count() <= $vendor->products->count()){
        //             $vendor->match = "Complete Match";
        //         }
        //     }

        //   }
        // }
        
        $returnHTML = view('frontend.estimation.list')->with(['vendor_count'=> count($searchResult).' Vendors Found','vendors' => $searchResult,'navCategories' => $navCategories])->render();
        return response()->json(array('status' => 'Success', 'html' => $returnHTML));

        // Return Vendor Count and Result.
        // return view('frontend.estimation.list')->with([
        //     'vendor_count' => $searchResult->count().' Vendors Found',
        //     'vendors' => $searchResult,
        //     'navCategories' => $navCategories
        // ]);
    }

    public function searchProducts($userProducts, $langId)
    {
            // Make empty array for vendors, product keywords and adoon keywords - By Ovi
            $all_vendors = array();
            $keywords = array();
            $addonKeywords = array();

            // Loop through cart products
            foreach($userProducts as $i=> $product)
            {
                // dd($product->estimated_product_addons->count());
                // Get Specific ($this) Product Translation - By Ovi
                $estimateProductTranslation = EstimateProductTranslation::where('estimate_product_id', $product->product_id)->where('language_id', $langId)->first();
                //dd($estimateProductTranslation);
                // Save Product Name in $keywords, so that we can run search later - By Ovi
                $keywords[] =  ($estimateProductTranslation) ? $estimateProductTranslation->name : '';

                // Get All Addons of ($this) Specific Product - By Ovi
                $estimatedProductAddons = EstimatedProductAddons::where('estimated_product_id', $product->id)->get();

                // Loop through these addons and save the ($title) for later search - By Ovi
                foreach($estimatedProductAddons as $k=> $estimatedProductAddon){
                    $addonKeywords[$i][$k] = $estimatedProductAddon->estimated_product_addon_option->title;
                }
            }
           //dd($addonKeywords);

            // ***Start*** BY - OVI 
            // Query to get:
            // 1) All the Vendor list with similar product, string saved in ($keywords)
            // Product Addons of Specific Product with Translations
            // Product Media and Variants
            // Product Translations, comparing ($language_id)
            // By Checking Vendor (status) active, inactive, or pending.
            // ***End*** BY - OVI 
            $teststests = 0;
            $all_vendors = Vendor::OrderBy('id','desc')->with(['productsLive' => function($q) use($langId, $keywords, $addonKeywords){
                    $q->whereHas('translation',function($q) use($langId, $keywords){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId)->whereIn('title', $keywords);
                        }
                )->with(['sets.setoptions' => function($ad) use($langId, $addonKeywords){
                    $ad->whereHas('translation_one',function($ad) use($langId, $addonKeywords){
                        $ad->select('id', 'title')->where('language_id', $langId)->whereIn('title', $addonKeywords);
                        });
                }])->with('media.image','variant');
            }])->whereHas('productsLive.translation',function($q) use($langId, $keywords, $teststests){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId)->whereIn('title', $keywords);
            })->where('status',1)->get();

            $newarr = array();
            if(isset($all_vendors)){
                $all_array = $all_vendors->toArray();
                foreach($all_array as $i=> $data){
                    // $newarr[$k]['all'] = $all_array[$k];
                    // $newarr[$k]['all']['all_count'] = count($addonKeywords);

                    foreach($addonKeywords as $k=> $data1){
                        $newarr[$i]['all'] = $data;
                        $newarr[$i]['all']['all_count'] = count($data1);
                    }

                }

               
            }
            //print_r($newarr);
            //print_r($all_vendors->toArray());
            //die;
            // $addonKeywordsP = [];
            // count($all_vendors[0]->productsLive[0]->sets);

            // foreach($all_vendors[0]->productsLive[0]->sets[0]->setoptions as $titleAdd)
            // {
            //     $addonKeywordsP[] = $titleAdd->title;
            // }
            // dd($addonKeywordsP);

            // Return All Vendors with Products, Addons - By Ovi 
           // $data['addonKey'] = $addonKeywords;
           // $data['all_vendors'] = $newarr;
            return $newarr;
    }

    public function destroy(Request $request)
    {
        $estimated_cart_id = $request->estimated_cart_id;
        $product_id = $request->product_id;

        $estimatedProduct = EstimatedProduct::where('product_id', $product_id )->where('estimated_cart_id', $estimated_cart_id )->first();
        $estimatedProductAddons = EstimatedProductAddons::where('estimated_product_id', $estimatedProduct->id )->delete();
        $estimatedProduct->delete();

        $product_count = EstimatedProduct::where('estimated_cart_id', $estimated_cart_id )->count();
        
        $estimatedProductCart = EstimatedProductCart::find($estimated_cart_id)->first();
        $estimatedProductCart->item_count = $product_count;
        $estimatedProductCart->save();

        $success['message'] = 'Product has been removed from cart.';
        return response()->json($success, 200);
                
    }


    // public function searchEstimatedProducts(Request $request)
    // {
    //     $response = [];
    //     $tagId = $request->input('tag_id');
    //     $keyword = $request->input('keyword');
    //     $langId = Session::get('customerLanguage');
    //     $preferences = Session::get('preferences');

    //     $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();

    //     $estimateProductsWithAddons = EstimateProduct::with(['estimate_product_addons.estimate_addon_set.option','category.primary' , 'estimate_product_translation' => function($q) use($langId, $keyword) {
    //         $q->where('language_id', '=', $langId)->where('name', 'LIKE', '%' . $keyword . '%');
    //     }])->groupBy('category_id')->orderBy('id', 'ASC')->get();  

    //     $returnHTML = view('frontend.get-estimation-search-products')->with(['products'=> $estimateProductsWithAddons])->render();
    //     return response()->json(array('status' => 'Success', 'html' => $returnHTML));
    // }
}
