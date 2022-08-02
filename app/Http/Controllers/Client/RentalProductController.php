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
    //     $html .= '<table class="table table-centered table-nowrap table-striped">
    //         <thead>
    //             <th>Image</th>
    //             <th>Name</th>
    //             <th>Variants</th>
    //             <th>Price</th>
    //             <th>Compare at price</th>
    //             <th>Cost Price</th>
    //             <th>Quantity</th>
    //             <th> </th>
    //             </thead>';
        $returnHTML = view('backend.product.part.addRows')->with(['varnt' => $proVariant,'show'=>true,'product_id'=>$product_id])->render();
        return response()->json(array('success' => true, 'htmlData' => $returnHTML));
        // $html .= '<tr id="tr_'. $proVariant->id .'">';
        // $html .= '<td><div class="image-upload">
        //             <label class="file-input" for="file-input_' . $proVariant->id . '"><img src="' . asset("assets/images/default_image.png") . '" width="30" height="30" class="uploadImages" for="' . $proVariant->id . '"/> </label>
        //         </div>
        //         <div class="imageCountDiv' . $proVariant->id . '"></div>
        //         </td>';
        // $html .= '<td> <input type="hidden" name="variant_ids[]" value="' . $proVariant->id . '">';

        // $html .= '<input type="text" name="variant_titles[]" value="' . $proVariant->title . '"></td>';
        // $html .= '<td> <input type="text" style="width: 70px;" name="variant_price[]" value="0" onkeypress="return isNumberKey(event)"> </td>';
        // $html .= '<td> <input type="text" style="width: 100px;" name="variant_minimum_duration[]" value="0" onkeypress="return isNumberKey(event)"> </td>';
        // $html .= '<td> <input type="text" style="width: 70px;" name="variant_incremental_price[]" value="0" onkeypress="return isNumberKey(event)"> </td>';
        // $html .= '<td>
        //             <a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a></td>
        //             <a href="javascript:void(0);" data-varient_id="'.$proVariant->id.'" class="action-icon viewC"><i class="mdi mdi-eye"></i></a>
        //             <a href="javascript:void(0);" data-varient_id="'.$proVariant->id.'"  data-product_id="'.$product_id.'" class="action-icon product_varient_ids addExistRow"><i class="mdi mdi-plus"></i>
        //         </a></td>';

        // $html .= '</tr>';
        //return $html;
     }
    private function generateBarcodeNumber()
    {
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }


}
