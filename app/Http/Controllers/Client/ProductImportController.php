<?php

namespace App\Http\Controllers\Client;
use DB;
use Image;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ClientLanguage;
use App\Models\ProductVariant;
use App\Models\CategoryHistory;
use App\Models\VendorMedia;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductTranslation;
use Illuminate\Support\Facades\Auth;
use App\Models\Category_translation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductImportController extends Controller{
    private $folderName = 'prods';
    public function getProductImport(Request $request){
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $response = Http::get('https://yogo.gd/wc-api/v3/products?filter%5Blimit%5D=5&consumer_key=ck_8abd4b1f9ba171e4b21db9a70bef6c711d6ba3f0&consumer_secret=cs_b17a5e26234bae2899e0926c8762247d1f03c684');
            $response_data = $response->object();
            $client_language = ClientLanguage::where('is_primary', 1)->first();
            if (!$client_language) {
                $client_language = ClientLanguage::where('is_active', 1)->first();
            }
            foreach ($response_data->products as  $product) {
                pr('foreach start ');
                $sku = Str::slug($product->title, '');
                $url_slug = Str::slug($product->title, '_');
                $category_id = 0;
                $category_slug = $product->categories[0];
                $category_detail = Category::where('slug', $category_slug)->first();
                if($category_detail){
                    $category_id = $category_detail->id;
                }else{
                    $new_category = Category::create([
                        'status' => 1,
                        'type_id' =>1,
                        'is_core' => 1,
                        'position' => 1,
                        'parent_id' => 1,
                        'is_visible' => 1,
                        'can_add_products' => 1,
                        'slug' => $category_slug,
                        'client_code' => $user->code
                    ]);
                    Category_translation::create(['language_id' => 1, 'name' => $category_slug, 'category_id' => $new_category->id]);
                    CategoryHistory::create(['action' => 'Add', 'update_id' => $user->id, 'updater_role' => 'Admin', 'client_code' => $user->code, 'category_id'=>$new_category->id]);
                    $category_id = $new_category->id;
                }
                $product_detail = Product::where('sku', $sku)->first();
                if(!$product_detail){
                    $image = Image::make($product->featured_src);
                    $filePath = $this->folderName.'/' . Str::random(40);
                    $path = Storage::disk('s3')->put($filePath, file_get_contents($product->featured_src), 'public');
                    $new_product = new Product();
                    $new_product->sku = $sku;
                    $new_product->type_id = 1;
                    $new_product->vendor_id = 10;
                    $new_product->url_slug = $url_slug;
                    $new_product->category_id = $category_id;
                    $new_product->save();
                    $vendor_media = new VendorMedia();
                    $vendor_media->media_type = 1;
                    $vendor_media->vendor_id = 10;
                    $vendor_media->path = $filePath;
                    $vendor_media->save();
                    $product_image = new ProductImage();
                    $product_image->is_default = 1;
                    $product_image->media_id = $vendor_media->id;
                    $product_image->product_id = $new_product->id;
                    $product_image->save();
                    ProductCategory::insert(['product_id' => $new_product->id, 'category_id' => $category_id]);
                    ProductVariant::insert(['sku' => $new_product->sku, 'product_id' => $new_product->id, 'barcode' => $this->generateBarcodeNumber(), 'price' => $product['price']]);
                    ProductTranslation::insert(['product_id' => $new_product->id,'language_id' => 1 , 'title' => $product->title, 'meta_description' => $product->description]);
                }
                pr('foreach end ');
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }
    private function generateBarcodeNumber(){
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }
}
