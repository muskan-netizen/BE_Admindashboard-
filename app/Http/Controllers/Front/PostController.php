<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\ClientLanguage;
use App\Models\ClientPreference;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTranslation;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ToasterResponser;
use App\Models\Client;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\UserVendor;
use App\Models\Vendor;
use App\Models\VendorMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Session;

class PostController extends FrontController
{

    use ToasterResponser;

    private $folderName = 'prods';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        $celebrity_check = ClientPreference::first()->value('celebrity_check');
        $categories = Category::with('translation_one','type')->where('id', '>', '1')
        ->whereHas('type', function($q){
            $q->where('service_type', 'p2p');
        })
        ->where('is_core', 1)->orderBy('parent_id', 'asc')->orderBy('position', 'asc')->where('deleted_at', NULL)->where('status', 1);

        if ($celebrity_check == 0)
            $categories = $categories->where('type_id', '!=', 5);   # if celebrity mod off .

        $categories = $categories->get();
        // dd($categories);
        return view('frontend.template_nine.posts.index')->with(['categories' => $categories, 'navCategories' => $navCategories]);
    }



    public function getCategoryAttributes(Request $request)
    {
        $category_id = $request->category_id;
        $productAttributes = [];
        if( checkTableExists('attributes') ) {
            $productAttributes = Attribute::with('option', 'varcategory.cate.primary')
                ->select('attributes.*')
                ->join('attribute_categories', 'attribute_categories.attribute_id', 'attributes.id')
                ->where('attribute_categories.category_id', $category_id)
                ->where('attributes.status', '!=', 2)
                ->orderBy('position', 'asc')->get();
            
          
        }

        $returnHTML = view('frontend.template_nine.posts.product-attribute')->with(['productAttributes' => $productAttributes,  'attribute_value' => [], 'attribute_key_value' =>[]])->render();

        // dd($returnHTML);

        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            

            //ProductVariant::where('product_id',$id)->update(['status'=>0]);
            // $product = Product::where('id', $id)->firstOrFail();
            $rule = array(
                'product_name' => 'required|string',
                'category_id' => 'required',
                'price' => 'required',
                'images.*' => 'required',
                'product_description' =>  'required',
                // 'minimum_order_count' => 'required|numeric|min:1',
                // 'batch_count' => 'required|numeric|min:1'
            );
            // dd($request->all());
            $validation  = Validator::make($request->all(), $rule);
            if ($validation->fails()) {
                return redirect()->back()->withInput()->withErrors($validation);
            }
           

            $product = $this->saveProduct($request);
            $fileIds = $this->uploadProductImages($product, $request);

            // $productImageSave = array();
            // if (@$fileIds) {
            //     foreach ($fileIds as $key => $value) {
            //         $productImageSave[] = [
            //             'product_id' => $product->id,
            //             'media_id' => $value,
            //             'is_default' => 1
            //         ];
            //     }
            // }
            // ProductImage::insert($productImageSave);

            if( clientPrefrenceModuleStatus('p2p_check') ) {
                if( !empty($request->attribute) ) {
                    if( checkTableExists('product_attributes') ) {
                        $insert_arr = [];
                        $insert_count = 0;
                        foreach($request->attribute as $key => $value) {
                            if( !empty($value) && !empty($value['option'] && is_array($value) )) {
                                
                                if(!empty($value['type']) && $value['type'] == 1 ) { // dropdown
                                    $value_arr = @$value['value'];
                                    
                                    foreach( $value['option'] as $key1 => $val1 ) {
                                        if( @in_array($val1['option_id'], $value_arr) ) {

                                            $insert_arr[$insert_count]['product_id'] = $product->id;
                                            $insert_arr[$insert_count]['attribute_id'] = $value['id'];
                                            $insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
                                            $insert_arr[$insert_count]['attribute_option_id'] = $val1['option_id'];
                                            $insert_arr[$insert_count]['key_value'] = $val1['option_id'];
                                            $insert_arr[$insert_count]['latitude'] = null;
                                            $insert_arr[$insert_count]['longitude'] = null;
                                            $insert_arr[$insert_count]['is_active'] = 1;
                                        }
                                        $insert_count++;
                                    }
                                }
                                else {
                                    foreach($value['option'] as $option_key => $option) {
                                        if(@$option['value']){
                                            $insert_arr[$insert_count]['product_id'] = $product->id;
                                            $insert_arr[$insert_count]['attribute_id'] = $value['id'];
                                            $insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
                                            $insert_arr[$insert_count]['attribute_option_id'] = $option['option_id'];
                                            $insert_arr[$insert_count]['key_value'] = $option['value'] ?? $option['option_title'];
                                            $insert_arr[$insert_count]['latitude'] = $option['latitude'] ?? null;
                                            $insert_arr[$insert_count]['longitude'] = $option['longitude'] ?? null;
                                            $insert_arr[$insert_count]['is_active'] = 1;

                                        }
                                        $insert_count++;
                                    }
                                }
                            }

                        
                        }
                        if( !empty($insert_arr) ) {
                            ProductAttribute::where('product_id',$product->id)->delete();
                            ProductAttribute::insert($insert_arr);
                        }
                    }
                }
                
            }

            $vendor = Vendor::where('id', $product->vendor_id)->first();

            if(@$vendor->slug && @ $product->url_slug){
                return redirect()->route('productDetail', [
                    'vendor' => $vendor->slug,
                    'id' => $product->url_slug
                ]);
            }
            
            
           
            $toaster = $this->successToaster(__('Success'),__('Product updated successfully') );
            return redirect()->back()->with('toaster', $toaster);
        } catch (\Exception $e) {
        //    dd($e->getMessage());

            $toaster = $this->errorToaster(__('ERROR'),$e->getMessage() );
            return redirect()->back()->with('toaster', $toaster);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function saveProduct($request)
    {

        $client = Client::orderBy('id','asc')->first();
        if(isset($client->custom_domain) && !empty($client->custom_domain) && $client->custom_domain != $client->sub_domain) {
            $sku_url =  ($client->custom_domain);
        } else {
            $sku_url =  ($client->sub_domain.env('SUBMAINDOMAIN'));
        }

        $slug = $this->generateSlug($request->product_name);
        $slug = str_replace(' ', '-',$slug);
        $generated_slug = $sku_url.'.'.$slug;
        $user = Auth::user();	
        $user_vendor = UserVendor::where('user_id', $user->id)->first();
        if(@$user_vendor->vendor_id){
            $product = new Product();
            $product->sku = $slug;
            $product->url_slug = $generated_slug;
            $product->title = empty($request->product_name) ? $request->sku : $request->product_name;
            $product->type_id = $request->type_id ?? 1;
            $product->category_id = $request->category_id;
            $product->vendor_id = $user_vendor->vendor_id ?? $user->id;
            $product->is_live = 1;
            $product->publish_at = date('Y-m-d H:i:s');
            $client_lang = ClientLanguage::where('is_primary', 1)->first();
            if (!$client_lang) {
                $client_lang = ClientLanguage::where('is_active', 1)->first();
            }
            $product->save();
            
            if ($product->id > 0) {
                $datatrans[] = [
                    'title' => $request->product_name??null,
                    'body_html' => !empty($request->product_description)?$request->product_description:'',
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
                $proVariant->price = $request->price;
                $proVariant->sku =$slug;
                $proVariant->title =$slug . '-' .  empty($request->product_name) ?$slug : $request->product_name;
                $proVariant->product_id = $product->id;
                $proVariant->barcode = $this->generateBarcodeNumber();
                $proVariant->quantity = 1;            
                $proVariant->status = 1;
                $proVariant->save();
                ProductTranslation::insert($datatrans);
                

                return $product;
            }
            else{
                throw new \ErrorException('Sorry, You are not a vendor.', 400);
            }
        }else{
            throw new \ErrorException('Sorry, You are not a vendor.', 400);
        }
    }

    private function generateBarcodeNumber()
    {
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }


    public function uploadProductImages($product, $request)
    {
        
        if ($request->has('file')) {
            $imageId = [];
            $files = $request->file('file');
            if (is_array($files)) {
                foreach ($files as $file) {
                    $img = new VendorMedia();
                    $img->media_type = 1;
                    $img->vendor_id = $product->vendor_id;
                    $img->path = Storage::disk('s3')->put($this->folderName, $file, 'public');
                    $img->save();
                    $path1 = $img->path['proxy_url'] . '40/40' . $img->path['image_path'];
                    if ($img->id > 0) {
                        $imageId[] = $img->id;
                        $image = new ProductImage();
                        $image->product_id = $product->id;
                        $image->is_default = 1;
                        $image->media_id = $img->id;
                        $image->save();
                       
                    }
                }
                
            } 
            // dd($imageId);
           
            return $imageId;
        }

    }

    public function generateSlug($name)
    {
        if (Product::whereSku($slug = $name)->exists()) {
            $max = Product::whereSku($name)->latest('id')->value('sku');
            if (isset($max[-1]) && is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }
            return $slug.'-'.rand();
        }
        return $slug;
    }
}
