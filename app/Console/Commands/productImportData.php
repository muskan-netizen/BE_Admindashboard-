<?php

namespace App\Console\Commands;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ClientLanguage;
use App\Models\ProductVariant;
use App\Models\ProductCategory;
use Illuminate\Console\Command;
use App\Models\ProductTranslation;
use Illuminate\Support\Facades\Http;

class productImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:productImportData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        try {
            $response = Http::get('https://yogo.gd/wc-api/v3/products?filter%5Blimit%5D=1&consumer_key=ck_8abd4b1f9ba171e4b21db9a70bef6c711d6ba3f0&consumer_secret=cs_b17a5e26234bae2899e0926c8762247d1f03c684');
            $response_data = $response->json();
            $client_language = ClientLanguage::where('is_primary', 1)->first();
            if (!$client_language) {
                $client_language = ClientLanguage::where('is_active', 1)->first();
            }
            pr($client_language);die;
            foreach ($response_data['products'] as  $product) {
                $category_id = 0;
                $category_detail = Category::where('slug', $product['categories'][0])->first();
                if($category_detail){
                    $category_id = $category_detail->id;
                }
                $product = new Product();
                $product->type_id = 1;
                $product->vendor_id = 10;
                $product->sku = Str::slug($product['title'], '');
                $product->url_slug = Str::slug($product['title'], '-');
                $product->category_id = $category_id;
                $product->save();
                ProductTranslation::insert(['product_id' => $product->id,'language_id' => 1]);
                ProductCategory::insert(['product_id' => $product->id, 'category_id' => $category_id]);
                ProductVariant::insert(['sku' => $product->sku, 'product_id' => $product->id, 'barcode' => $this->generateBarcodeNumber()]);
            }
        } catch (Exception $e) {
            
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
