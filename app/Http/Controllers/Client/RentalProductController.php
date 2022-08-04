<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\{CsvProductImport, Product, Category, ProductTranslation, Vendor, AddonSet, ProductRelated, ProductCrossSell, ProductAddon, ProductCategory, ClientLanguage, ProductVariant, ProductImage, TaxCategory, ProductVariantSet, Country, Variant, VendorMedia, ProductVariantImage, Brand, Celebrity, ClientPreference, ProductCelebrity, Type, ProductUpSell, CartProduct, CartAddon, UserWishlist,Client, CsvQrcodeImport, Tag,ProductTag,ProductFaq,TaxRate};
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\ToasterResponser;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Imports\QrcodesImport;
use GuzzleHttp\Client as GCLIENT;
class RentalProductController extends BaseController
{
    use ApiResponser;
    use ToasterResponser;

    public function getRow(Request $request)
    {
        $sku  = $request->sku;
        $ids  = $request->variant_ids ?? [];
        $proSku = $sku . '-' . implode('*', $ids);
        $product_id = $request->pid;
        $proVariantCount = ProductVariant::where('product_id', $product_id)->count();
        $proVariant = ProductVariant::where('sku', $proSku)->first();
        if (!$proVariant) {
            $proVariant = new ProductVariant();
            $proVariant->sku = $proSku;
            $proVariant->title = $sku . '-' .$request->vid;
            $proVariant->product_id = $product_id;
            $proVariant->barcode = $this->generateBarcodeNumber();
            $proVariant->save();
        }
        $returnHTML = view('backend.product.part.addRows')->with(['varnt' => $proVariant,'show'=>true,'product_id'=>$product_id])->render();
        return response()->json(array('success' => true, 'htmlData' => $returnHTML));
     
     }
    private function generateBarcodeNumber()
    {
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }

    public function getScheduleTableData(Request $request)
    {
        $data  = [[
                   "name" => "Tiger Nixon", 
                   "hr" => [
                      "position" => "System Architect", 
                      "salary" => "$320,800", 
                      "start_date" => "2011/04/25" 
                   ], 
                   "contact" => [
                         "Edinburgh", 
                         "5421" 
                    ] 
                ], 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ], 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
                , 
                [
                    "name" => "Donna Snider", 
                    "hr" => [
                        "position" => "Customer Support", 
                        "salary" => "$112,000", 
                        "start_date" => "2011/01/25" 
                    ], 
                    "contact" => [
                            "New York", 
                            "4226" 
                    ] 
                ]
            
            
            ]; 
        
        return response()->json(array('success' => true, 'data' => $data));
     
     }


}
