<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\{Brand, Tag, TagTranslation, ProductTag, Category, AddonSet, ProductAddon, ClientLanguage, CsvProductImport, Product, ProductCategory, ProductTranslation, ProductVariant, ProductVariantSet, TaxCategory, Variant, VariantOption, VendorCategory, VendorMedia, ProductImage, Client, Vendor};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use voku\helper\ASCII;

/**
 * CSV columns are 0-based and match {@see public/sample_product.csv}:
 * 0 SKU, 1 Title, 2 Description (HTML), 3 Published, 4 Category,
 * 5–6 Option1 name/value, 7–8 Option2, 9–10 Option3, 11 Variant SKU, 12 Variant Price, 13 Variant Quantity,
 * 14 Variant Compare At Price, 15 Variant Taxable, 16 Image Src, 17 Variant Cost Price, 18 Brand, 19 Tax Category,
 * 20 Product Quantity, 21 Product Price, 22 Product Compare At Price, 23 Addon, 24 Tags.
 *
 * When the import passes the header row, Category and Tax Category are read by column title as well as by index
 * (see {@see csvCategoryName} and {@see csvTaxCategoryTitle}).
 */
class ProductImportCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $folderName = 'prods';
    public $vendor_id, $csv_product_import_id,$data;

    /** @var array<int, string>|null First CSV row (column titles); used to resolve Description (HTML) etc. */
    private $headerRow;

    public function __construct($vendor_id, $csv_product_import_id,$rows,$data)
    {
        $this->vendor_id = $vendor_id;
        $this->csv_product_import_id = $csv_product_import_id;
        $code = Client::orderBy('id', 'asc')->value('code');
        $this->folderName = '/' . $code . '/prods';
        $this->data = $rows;
        $this->headerRow = is_array($data) && $data !== [] ? array_values($data) : null;
    }

    public function handle(){
        try {
            $i = 0;
            $error = array();
            $variant_exist = 0;
            try {
                DB::beginTransaction();
                $client_lang = ClientLanguage::where('is_primary', 1)->first();
                if (! $client_lang) {
                    $client_lang = ClientLanguage::where('is_active', 1)->first();
                }
                Log::info('[ProductImportCsv] job started', [
                    'vendor_id' => $this->vendor_id,
                    'csv_product_import_id' => $this->csv_product_import_id,
                    'row_count' => is_countable($this->data) ? count($this->data) : null,
                    'client_language_id' => $client_lang->language_id ?? null,
                ]);
                foreach ( $this->data as $row) {
                    $rowNormalized = $this->rowForLog($row);
                    Log::info('[ProductImportCsv] incoming row', [
                        'loop_index' => $i,
                        'column_count' => count($rowNormalized),
                        'columns' => $rowNormalized,
                    ]);
                    $checker = 0;
                    if (isset($row[0]) && $row[0] != "SKU") { // data of excel check

                        if (isset($row[0]) && $row[0] == "") { // if sku or handle is empty
                            $error[] = "Row " . $i . " : SKU  is empty";
                            $checker = 1;
                        }
                        // if (Product::where('sku', $row[0])->exists()) { // if sku or handle is empty
                        //     $pro = Product::where('sku', $row[0])->first();
                        //     \Log::info($pro);
                        //     $error[] = "Row " . $i . " : Product with this sku already exist";
                        //     $checker = 1;
                        // }
                        // if ($row[3] == "") { //check if published is empty
                        // $error[] = "Row " . $i . " : Please mark published either true or false";
                        // $checker = 1;
                        // }
                        $rowArr = $this->rowToArray($row);
                        $categoryName = $this->csvCategoryName($rowArr);
                        if ($categoryName === '') { // check if category is empty
                            $error[] = "Row " . $i . " : Category cannot be empty";
                            $checker = 1;
                        }
                        if ($categoryName !== '') {
                            $vendorCategoryExists = $this->resolveVendorCategoryForImport($categoryName);
                            if (! $vendorCategoryExists) {
                                $error[] = "Row " . $i . " : Category doesn't exist";
                                $checker = 1;
                            } elseif ((int) $vendorCategoryExists->status !== 1) {
                                $error[] = "Row " . $i . " : This category is not activated for this vendor";
                                $checker = 1;
                            }
                        }
                        /*
                         * if ($row[5] != "" && $row[6] != "") {
                         * $variant_check = Variant::whereHas('category.translation_one' , function($query)use ($row){
                         * $query->where('name' , $row[4]);
                         * })->where(['title' => $row[5], 'variants.status' => 1])->first();
                         * // $variant_check = Variant::where('title', $row[5])->first();
                         * if (!$variant_check) {
                         * $error[] = "Row " . $i . " : Option1 Name doesn't exist";
                         * $checker = 1;
                         * }
                         *
                         * $variant_option = VariantOption::where('title', $row[6])->first();
                         * if (!$variant_option) {
                         * $error[] = "Row " . $i . " : Option1 value doesn't exist";
                         * $checker = 1;
                         * }
                         *
                         * if ($variant_check && $variant_option) {
                         * $checkVariantMatch = VariantOption::where(['title' => $row[6], 'variant_id' => $variant_check->id])->first();
                         * if (!$checkVariantMatch) {
                         * $error[] = "Row " . $i . " : Option1 value is not available for this Name";
                         * $checker = 1;
                         * } else {
                         * $variant_exist = 1;
                         * }
                         * }
                         * }
                         * if ($row[7] != "" && $row[8] != "") {
                         * $variant_check = Variant::whereHas('category.translation_one' , function($query)use ($row){
                         * $query->where('name' , $row[4]);
                         * })->where(['title' => $row[7], 'variants.status' => 1])->first();
                         * // $variant_check = Variant::where('title', $row[7])->first();
                         * if (!$variant_check) {
                         * $error[] = "Row " . $i . " : Option2 Name doesn't exist";
                         * $checker = 1;
                         * }
                         *
                         * $variant_option = VariantOption::where('title', $row[8])->first();
                         * if (!$variant_option) {
                         * $error[] = "Row " . $i . " : Option2 value doesn't exist";
                         * $checker = 1;
                         * }
                         *
                         * if ($variant_check && $variant_option) {
                         * $checkVariantMatch = VariantOption::where(['title' => $row[8], 'variant_id' => $variant_check->id])->first();
                         * if (!$checkVariantMatch) {
                         * $error[] = "Row " . $i . " : Option2 value is not available for this Name";
                         * $checker = 1;
                         * } else {
                         * $variant_exist = 1;
                         * }
                         * }
                         * }
                         *
                         * if ($row[9] != "" && $row[10] != "") {
                         * $variant_check = Variant::whereHas('category.translation_one' , function($query)use ($row){
                         * $query->where('name' , $row[4]);
                         * })->where(['title' => $row[9], 'variants.status' => 1])->first();
                         * // $variant_check = Variant::where('title', $row[9])->first();
                         * if (!$variant_check) {
                         * $error[] = "Row " . $i . " : Option3 Name doesn't exist";
                         * $checker = 1;
                         * }
                         *
                         * $variant_option = VariantOption::where('title', $row[10])->first();
                         * if (!$variant_option) {
                         * $error[] = "Row " . $i . " : Option3 value doesn't exist";
                         * $checker = 1;
                         * }
                         *
                         * if ($variant_check && $variant_option) {
                         * $checkVariantMatch = VariantOption::where(['title' => $row[10], 'variant_id' => $variant_check->id])->first();
                         * if (!$checkVariantMatch) {
                         * $error[] = "Row " . $i . " : Option3 value is not available for this Name";
                         * $checker = 1;
                         * } else {
                         * $variant_exist = 1;
                         * }
                         * }
                         * }
                         */

                        if ($variant_exist == 1) {
                            if (isset($row[11]) && $row[11] == "") {
                                $error[] = "Row " . $i . " : Variant Sku is empty";
                                $checker = 1;
                            }
                            // else {
                            //     $proVariant = ProductVariant::where('sku', $row[11])->first();
                            //     if ($proVariant) {
                            //         $error[] = "Row " . $i . " : Variant Sku already exist";
                            //         $checker = 1;
                            //     }
                            // }
                        }
                       /* if (isset($row[11]) && $row[11] != "") {
                            $proVariant = ProductVariant::where('sku', $row[11])->first();
                            if ($proVariant) {
                                $error[] = "Row " . $i . " : Variant Sku already exist";
                                $checker = 1;
                            }
                        }*/

                        $taxTitle = $this->csvTaxCategoryTitle($rowArr);
                        if ($taxTitle !== '') {
                            $tax_category = $this->findTaxCategoryByTitle($taxTitle);
                            if (! $tax_category) {
                                $error[] = "Row " . $i . " : Tax Category doesn't exist";
                                $checker = 1;
                            }
                        }
                        if (isset($row[23]) && $row[23] != "") {

                            foreach (explode(',', $row[23]) as $titleKey => $Addontitle) {
                                $Addontitle = trim((string) $Addontitle);
                                if ($Addontitle === '') {
                                    continue;
                                }
                                $vendorAddonSetExists = AddonSet::where('title', "LIKE", $Addontitle)->where('vendor_id', $this->vendor_id)->first();
                                if (! $vendorAddonSetExists) {
                                    $error[] = "Row " . $i . " : Addon doesn't exist";
                                    $checker = 1;
                                    break;
                                }
                            }
                        }
                        if ($checker == 0) {
                            $da = $this->rowToArray($row);
                            if(count($da) > 4){
                                $bodyHtml = $this->csvCellByHeader($da, 2, [
                                    'Description (HTML)',
                                    'description (html)',
                                    'Description HTML',
                                    'Body HTML',
                                    'body_html',
                                ]);
                                if ($bodyHtml !== '') {
                                    $bodyHtml = str_replace("", "’", $bodyHtml);
                                }
                                $da[2] = $bodyHtml;
                                if (! Product::where('sku', $da[0])->exists()) {
                                    $brand_id = null;
                                    $tax_category_id = null;

                                    if (isset($da[18]) && $da[18] != "") {
                                        $brand = Brand::where('title', "LIKE", $da[18])->first();
                                        if ($brand) {
                                            $brand_id = $brand->id;
                                        }
                                    }

                                    $taxTitleDa = $this->csvTaxCategoryTitle($da);
                                    if ($taxTitleDa !== '') {
                                        $tax_category = $this->findTaxCategoryByTitle($taxTitleDa);
                                        if ($tax_category) {
                                            $tax_category_id = $tax_category->id;
                                        }
                                    }

                                    $category = $this->resolveVendorCategoryForImport($this->csvCategoryName($da));

                                    $tagsColumn = isset($da[24]) && trim((string) $da[24]) !== '' ? trim((string) $da[24]) : null;

                                    $product = Product::insertGetId([
                                        'type_id' => 1,
                                        'sku' => $da[0],
                                        'is_featured' => 0,
                                        'is_physical' => 0,
                                        'has_inventory' => $this->inferHasInventoryFromRow($da),
                                        'url_slug' => $da[0],
                                        'brand_id' => $brand_id,
                                        'requires_shipping' => 0,
                                        'Requires_last_mile' => 0,
                                        'sell_when_out_of_stock' => 0,
                                        'vendor_id' => $this->vendor_id,
                                        'category_id' => $category->category_id,
                                        'tax_category_id' => $tax_category_id,
                                        'title' => ($da[1] == "") ? "" : $da[1],
                                        'is_live' => $this->csvPublishedToIsLive($da[3] ?? ''),
                                        'body_html' => ($da[2] == "") ? "" : $da[2],
                                        'tags' => $tagsColumn,
                                    ]);
                                    if (isset($da[23]) && $da[23] != "") {
                                        foreach (explode(',', $da[23]) as $titleKey => $Addontitle) {
                                            $Addontitle = trim((string) $Addontitle);
                                            if ($Addontitle === '') {
                                                continue;
                                            }
                                            $vendorAddonSetExists = AddonSet::where('title', "LIKE", $Addontitle)->where('vendor_id', $this->vendor_id)->first();
                                            if ($vendorAddonSetExists) {
                                                $addonsArray = [
                                                    'product_id' => $product,
                                                    'addon_id' => $vendorAddonSetExists->id
                                                ];
                                                ProductAddon::insert($addonsArray);
                                            }
                                        }
                                    }

                                    // insertion into product category
                                    $cat = [
                                        'product_id' => $product,
                                        'category_id' => $category->category_id,
                                    ];

                                    ProductCategory::insert($cat);

                                    if (isset($da[24]) && $da[24] != "") {
                                        // $delete = ProductTag::where('product_id', $product)->delete();
                                        foreach (explode(',', $da[24]) as $titleKey => $tagtitle) {
                                            $tagtitle = trim((string) $tagtitle);
                                            if ($tagtitle === '') {
                                                continue;
                                            }
                                            $tagModel = $this->createTagDirectForImport($tagtitle, $client_lang);
                                            ProductTag::insert([
                                                'product_id' => $product,
                                                'tag_id' => $tagModel->id,
                                            ]);
                                        }
                                    }

                                    // Insert into Product Translation
                                    ProductTranslation::updateOrcreate([
                                        'product_id' => $product,
                                        'language_id' =>  $client_lang->language_id
                                    ],[
                                        'title' => ($da[1] == "") ? "" : $da[1],
                                        'body_html' => ($da[2] == "") ? "" : $da[2],
                                        'meta_title' => '',
                                        'meta_keyword' => '',
                                        'meta_description' => '',
                                        'product_id' => $product,
                                        'language_id' => $client_lang->language_id
                                    ]);

                                    if ($da[5] != "" || $da[7] != "" || $da[9] != "") {
                                        $product_hasvariant = Product::where('id', $product)->first();
                                        $product_hasvariant->has_variant = 1;
                                        $product_hasvariant->save();
                                        // inserting product variant
                                        if ($da[11] != "") {
                                            $vAttrs = [
                                                'title' => $da[11],
                                                'quantity' => (! empty($da[13])) ? $da[13] : 0,
                                                'price' => $da[12],
                                                'compare_at_price' => $da[14],
                                                'tax_category_id' => $this->variantTaxCategoryIdFromCsv($da, $tax_category_id),
                                            ];
                                            if (isset($da[17]) && $da[17] !== '') {
                                                $vAttrs['cost_price'] = $da[17];
                                            }
                                            $proVariant = ProductVariant::updateOrcreate([
                                                'sku' => $da[11],
                                                'product_id' => $product,
                                            ], $vAttrs);
                                            if (empty($proVariant->barcode)) {
                                                $proVariant->barcode = $this->generateBarcodeNumber();
                                                $proVariant->save();
                                            }
                                        }
                                        if ($da[5] != "") {
                                            $variant = Variant::whereHas('category.translation_one', function ($query) use ($da) {
                                                $query->where('name', $this->csvCategoryName($da));
                                            })->where([
                                                'title' => $da[5],
                                                'variants.status' => 1
                                            ])->first();
                                            // $variant = Variant::where('title', $da[5])->first();
                                            if ($variant) {
                                                $variant_optionn = VariantOption::where([
                                                    'title' => $da[6],
                                                    'variant_id' => $variant->id
                                                ])->first();
                                                // inserting product variant sets
                                                $proVariantSet = new ProductVariantSet();
                                                $proVariantSet->product_id = $product;
                                                $proVariantSet->product_variant_id = $proVariant->id;
                                                $proVariantSet->variant_type_id = $variant->id;
                                                $proVariantSet->variant_option_id = $variant_optionn->id;
                                                $proVariantSet->save();
                                            }
                                        }

                                        if ($da[7] != "") {
                                            // $variant = Variant::where('title', $da[7])->first();
                                            $variant = Variant::whereHas('category.translation_one', function ($query) use ($da) {
                                                $query->where('name', $this->csvCategoryName($da));
                                            })->where([
                                                'title' => $da[7],
                                                'variants.status' => 1
                                            ])->first();
                                            if ($variant) {
                                                $variant_optionn = VariantOption::where([
                                                    'title' => $da[8],
                                                    'variant_id' => $variant->id
                                                ])->first();
                                                // inserting product variant sets
                                                $proVariantSet = new ProductVariantSet();
                                                $proVariantSet->product_id = $product;
                                                $proVariantSet->product_variant_id = $proVariant->id;
                                                $proVariantSet->variant_type_id = $variant->id;
                                                $proVariantSet->variant_option_id = $variant_optionn->id;
                                                $proVariantSet->save();
                                            }
                                        }

                                        if ($da[9] != "") {
                                            // $variant = Variant::where('title', $da[9])->first();
                                            $variant = Variant::whereHas('category.translation_one', function ($query) use ($da) {
                                                $query->where('name', $this->csvCategoryName($da));
                                            })->where([
                                                'title' => $da[9],
                                                'variants.status' => 1
                                            ])->first();
                                            if ($variant) {
                                                $variant_optionn = VariantOption::where([
                                                    'title' => $da[10],
                                                    'variant_id' => $variant->id
                                                ])->first();
                                                // inserting product variant sets
                                                $proVariantSet = new ProductVariantSet();
                                                $proVariantSet->product_id = $product;
                                                $proVariantSet->product_variant_id = $proVariant->id;
                                                $proVariantSet->variant_type_id = $variant->id;
                                                $proVariantSet->variant_option_id = $variant_optionn->id;
                                                $proVariantSet->save();
                                            }
                                        }
                                    } else {
                                        $proVariant = new ProductVariant();
                                        $proVariant->sku = $da[0];
                                        $proVariant->title = (($da[1] ?? '') !== '') ? $da[1] : $da[0];
                                        $proVariant->product_id = $product;
                                        $proVariant->barcode = $this->generateBarcodeNumber();
                                        $proVariant->quantity = isset($da[20]) && $da[20] !== '' ? $da[20] : 0;
                                        $proVariant->price = $da[21] ?? "";
                                        $proVariant->compare_at_price = $da[22] ?? "";
                                        if (isset($da[17]) && $da[17] !== '') {
                                            $proVariant->cost_price = $da[17];
                                        }
                                        $proVariant->tax_category_id = $this->variantTaxCategoryIdFromCsv($da, $tax_category_id);
                                        $proVariant->save();
                                    }

                                    // images
                                    if (! empty($da[16])) {
                                        $imgOrdinal = 0;
                                        foreach (explode(',', $da[16]) as $file_key => $file) {
                                            $file = trim((string) $file);
                                            if ($file === '') {
                                                continue;
                                            }
                                            $img = new VendorMedia();
                                            $img->media_type = 1;
                                            $img->vendor_id = $this->vendor_id;
                                            $img->path = $file;
                                            $img->save();
                                            $image = new ProductImage();
                                            $image->product_id = $product;
                                            $image->is_default = $imgOrdinal === 0 ? 1 : 0;
                                            $image->media_id = $img->id;
                                            $image->save();
                                            $imgOrdinal ++;
                                        }
                                    }
                                } else {
                                    $product_id = Product::where('sku', $da[0])->first();

                                    // update product
                                    $brand_id = null;
                                    $tax_category_id = null;

                                    if ($da[18] != "") {
                                        $brand = Brand::where('title', "LIKE", $da[18])->first();
                                        if ($brand) {
                                            $brand_id = $brand->id;
                                        }
                                    }

                                    $taxTitleDa = $this->csvTaxCategoryTitle($da);
                                    if ($taxTitleDa !== '') {
                                        $tax_category = $this->findTaxCategoryByTitle($taxTitleDa);
                                        if ($tax_category) {
                                            $tax_category_id = $tax_category->id;
                                        }
                                    }

                                    $category = $this->resolveVendorCategoryForImport($this->csvCategoryName($da));

                                    $tagsColumn = isset($da[24]) && trim((string) $da[24]) !== '' ? trim((string) $da[24]) : null;

                                    Product::where('id', $product_id->id)->update([
                                        'type_id' => 1,
                                        'sku' => $da[0],
                                        'is_featured' => 0,
                                        'is_physical' => 0,
                                        'has_inventory' => $this->inferHasInventoryFromRow($da),
                                        'url_slug' => $da[0],
                                        'brand_id' => $brand_id,
                                        'requires_shipping' => 0,
                                        'Requires_last_mile' => 0,
                                        'sell_when_out_of_stock' => 0,
                                        'vendor_id' => $this->vendor_id,
                                        'category_id' => $category->category_id,
                                        'tax_category_id' => $tax_category_id,
                                        'title' => ($da[1] == "") ? "" : $da[1],
                                        'is_live' => $this->csvPublishedToIsLive($da[3] ?? ''),
                                        'body_html' => ($da[2] == "") ? "" : $da[2],
                                        'tags' => $tagsColumn,
                                    ]);
                                    ProductCategory::updateOrCreate(
                                        ['product_id' => $product_id->id],
                                        ['category_id' => $category->category_id]
                                    );
                                    ProductTranslation::updateOrcreate([
                                        'product_id' => $product_id->id,
                                        'language_id' =>  $client_lang->language_id
                                    ],[
                                        'title' => ($da[1] == "") ? "" : $da[1],
                                        'body_html' => ($da[2] == "") ? "" : $da[2],
                                        'meta_title' => '',
                                        'meta_keyword' => '',
                                        'meta_description' => '',
                                        'product_id' => $product_id->id,
                                        'language_id' => $client_lang->language_id
                                    ]);
                                    $delete = ProductAddon::where('product_id', $product_id->id)->delete();
                                    $delete = ProductTag::where('product_id', $product_id->id)->delete();
                                    if (isset($da[23]) && $da[23] != "") {
                                        foreach (explode(',', $da[23]) as $titleKey => $Addontitle) {
                                            $Addontitle = trim((string) $Addontitle);
                                            if ($Addontitle === '') {
                                                continue;
                                            }
                                            $vendorAddonSetExists = AddonSet::where('title', "LIKE", $Addontitle)->where('vendor_id', $this->vendor_id)->first();
                                            if ($vendorAddonSetExists) {
                                                $addonsArray = [
                                                    'product_id' => $product_id->id,
                                                    'addon_id' => $vendorAddonSetExists->id
                                                ];
                                                ProductAddon::insert($addonsArray);
                                            }
                                        }
                                    }
                                    if (isset($da[24]) && $da[24] != "") {
                                        foreach (explode(',', $da[24]) as $titleKey => $tagtitle) {
                                            $tagtitle = trim((string) $tagtitle);
                                            if ($tagtitle === '') {
                                                continue;
                                            }
                                            $tagModel = $this->createTagDirectForImport($tagtitle, $client_lang);
                                            ProductTag::insert([
                                                'product_id' => $product_id->id,
                                                'tag_id' => $tagModel->id,
                                            ]);
                                        }
                                    }
                                    if (! empty(trim((string) ($da[16] ?? '')))) {
                                        $this->replaceProductImagesFromCsv((int) $product_id->id, (string) $da[16]);
                                    }
                                    if ($da[5] != "" || $da[7] != "" || $da[9] != "") {
                                        $product_hasvariant = Product::where('id', $product_id->id)->first();
                                        $product_hasvariant->has_variant = 1;
                                        $product_hasvariant->save();
                                        // inserting product variant
                                        if ($da[11] != "") {
                                            $vAttrs = [
                                                'title' => $da[11],
                                                'quantity' => (! empty($da[13])) ? $da[13] : 0,
                                                'price' => $da[12],
                                                'compare_at_price' => $da[14],
                                                'tax_category_id' => $this->variantTaxCategoryIdFromCsv($da, $tax_category_id),
                                            ];
                                            if (isset($da[17]) && $da[17] !== '') {
                                                $vAttrs['cost_price'] = $da[17];
                                            }
                                            $proVariant = ProductVariant::updateOrcreate([
                                                'sku' => $da[11],
                                                'product_id' => $product_id->id,
                                            ], $vAttrs);
                                            if (empty($proVariant->barcode)) {
                                                $proVariant->barcode = $this->generateBarcodeNumber();
                                                $proVariant->save();
                                            }
                                        }
                                        if ($da[5] != "") {
                                            $variant = Variant::whereHas('category.translation_one', function ($query) use ($da) {
                                                $query->where('name', $this->csvCategoryName($da));
                                            })->where([
                                                'title' => $da[5],
                                                'variants.status' => 1
                                            ])->first();
                                            if ($variant) {
                                                $variant_optionn = VariantOption::where([
                                                    'title' => $da[6],
                                                    'variant_id' => $variant->id
                                                ])->first();
                                                // inserting product variant sets
                                                $proVariantSet = new ProductVariantSet();
                                                $proVariantSet->product_id = $product_id->id;
                                                $proVariantSet->product_variant_id = $proVariant->id;
                                                $proVariantSet->variant_type_id = $variant->id;
                                                $proVariantSet->variant_option_id = $variant_optionn->id;
                                                $proVariantSet->save();
                                            }
                                        }

                                        if ($da[7] != "") {
                                            $variant = Variant::whereHas('category.translation_one', function ($query) use ($da) {
                                                $query->where('name', $this->csvCategoryName($da));
                                            })->where([
                                                'title' => $da[7],
                                                'variants.status' => 1
                                            ])->first();
                                            if ($variant) {
                                                $variant_optionn = VariantOption::where([
                                                    'title' => $da[8],
                                                    'variant_id' => $variant->id
                                                ])->first();
                                                // inserting product variant sets
                                                $proVariantSet = new ProductVariantSet();
                                                $proVariantSet->product_id = $product_id->id;
                                                $proVariantSet->product_variant_id = $proVariant->id;
                                                $proVariantSet->variant_type_id = $variant->id;
                                                $proVariantSet->variant_option_id = $variant_optionn->id;
                                                $proVariantSet->save();
                                            }
                                        }

                                        if ($da[9] != "") {
                                            $variant = Variant::whereHas('category.translation_one', function ($query) use ($da) {
                                                $query->where('name', $this->csvCategoryName($da));
                                            })->where([
                                                'title' => $da[9],
                                                'variants.status' => 1
                                            ])->first();
                                            if ($variant) {
                                                $variant_optionn = VariantOption::where([
                                                    'title' => $da[10],
                                                    'variant_id' => $variant->id
                                                ])->first();
                                                // inserting product variant sets
                                                $proVariantSet = new ProductVariantSet();
                                                $proVariantSet->product_id = $product_id->id;
                                                $proVariantSet->product_variant_id = $proVariant->id;
                                                $proVariantSet->variant_type_id = $variant->id;
                                                $proVariantSet->variant_option_id = $variant_optionn->id;
                                                $proVariantSet->save();
                                            }
                                        }
                                    } else {
                                        $variantAttrs = [
                                            'title' => (($da[1] ?? '') !== '') ? $da[1] : $da[0],
                                            'quantity' => isset($da[20]) && $da[20] !== '' ? $da[20] : 0,
                                            'price' => $da[21] ?? '',
                                            'compare_at_price' => $da[22] ?? '',
                                            'tax_category_id' => $this->variantTaxCategoryIdFromCsv($da, $tax_category_id),
                                        ];
                                        if (isset($da[17]) && $da[17] !== '') {
                                            $variantAttrs['cost_price'] = $da[17];
                                        }
                                        $proVariant = ProductVariant::updateOrCreate(
                                            [
                                                'sku' => $da[0],
                                                'product_id' => $product_id->id,
                                            ],
                                            $variantAttrs
                                        );
                                        if (empty($proVariant->barcode)) {
                                            $proVariant->barcode = $this->generateBarcodeNumber();
                                            $proVariant->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $i ++;
                }
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                $error[] = "Other: " . $ex->getMessage();
                Log::error('[ProductImportCsv] transaction failed', [
                    'vendor_id' => $this->vendor_id,
                    'csv_product_import_id' => $this->csv_product_import_id,
                    'message' => $ex->getMessage(),
                    'line' => $ex->getLine(),
                    'file' => $ex->getFile(),
                ]);
            }
            $vendor_csv = CsvProductImport::where('vendor_id', $this->vendor_id)->where('id', $this->csv_product_import_id)->first();
            if (! empty($error)) {
                $vendor_csv->status = 3;
                $vendor_csv->error = json_encode($error);
            } else {
                $vendor_csv->status = 2;
            }
            $vendor_csv->save();
            Log::info('[ProductImportCsv] job finished', [
                'vendor_id' => $this->vendor_id,
                'csv_product_import_id' => $this->csv_product_import_id,
                'status' => $vendor_csv->status,
                'error_count' => count($error),
                'errors_sample' => array_slice($error, 0, 20),
            ]);
            return 1;
        } catch (\Exception $ex) {
            DB::rollback();
            if (! isset($error)) {
                $error = [];
            }
            $error[] = "Other: " . $ex->getMessage();
            Log::error('[ProductImportCsv] job failed (outer)', [
                'vendor_id' => $this->vendor_id,
                'csv_product_import_id' => $this->csv_product_import_id,
                'message' => $ex->getMessage(),
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ]);
        }
    }

    private function resolveVendorCategoryForImport(string $categoryName): ?VendorCategory
    {
        if ($categoryName === '') {
            return null;
        }
        $name = trim($categoryName);
        $like = '%' . addcslashes($name, '%_\\') . '%';

        foreach ([true, false] as $requireActiveClientLang) {
            $found = VendorCategory::query()
                ->where('vendor_id', $this->vendor_id)
                ->whereIn('category_id', function ($q) use ($name, $like, $requireActiveClientLang) {
                    $q->select('ct.category_id')
                        ->from('category_translations as ct')
                        ->join('categories as c', 'c.id', '=', 'ct.category_id')
                        ->whereNull('c.deleted_at')
                        ->when($requireActiveClientLang, function ($sub) {
                            $sub->join('client_languages as cl', 'cl.language_id', '=', 'ct.language_id')
                                ->where('cl.is_active', 1);
                        })
                        ->where(function ($sub) use ($name, $like) {
                            $sub->where('ct.name', $name)
                                ->orWhere('ct.name', 'LIKE', $like);
                        });
                })
                ->first();
            if ($found) {
                return $found;
            }
        }

        return null;
    }

    /** Category column: prefer header name "Category" when a header row was passed to the job. */
    private function csvCategoryName(array $da): string
    {
        return trim((string) $this->csvCellByHeader($da, 4, [
            'Category',
        ]));
    }

    /** Tax column: prefer header name "Tax Category" when a header row was passed to the job. */
    private function csvTaxCategoryTitle(array $da): string
    {
        return trim((string) $this->csvCellByHeader($da, 19, [
            'Tax Category',
        ]));
    }

    private function findTaxCategoryByTitle(string $title): ?TaxCategory
    {
        $title = trim($title);
        if ($title === '') {
            return null;
        }
        $found = TaxCategory::where('title', $title)->first();
        if ($found) {
            return $found;
        }

        return TaxCategory::where('title', 'LIKE', $title)->first();
    }

    /**
     * @param  mixed  $row
     */
    private function rowToArray($row): array
    {
        if ($row instanceof Collection) {
            return $row->all();
        }

        return is_array($row) ? $row : [];
    }

    private function csvPublishedToIsLive($value): int
    {
        $v = is_string($value) ? strtoupper(trim($value)) : $value;
        if ($v === true || $v === 1 || $v === '1') {
            return 1;
        }
        if (is_string($v)) {
            return in_array($v, ['TRUE', 'YES', 'Y', 'ON'], true) ? 1 : 0;
        }

        return 0;
    }

    private function inferHasInventoryFromRow(array $da): int
    {
        if (($da[5] ?? '') !== '' || ($da[7] ?? '') !== '' || ($da[9] ?? '') !== '') {
            $qty = $da[13] ?? '';

            return ($qty !== '' && (float) $qty > 0) ? 1 : 0;
        }
        $qty = $da[20] ?? '';

        return ($qty !== '' && (float) $qty > 0) ? 1 : 0;
    }

    private function csvTruthy($value): bool
    {
        $v = is_string($value) ? strtoupper(trim($value)) : $value;
        if ($v === true || $v === 1 || $v === '1') {
            return true;
        }
        if (is_string($v)) {
            return in_array($v, ['TRUE', 'YES', 'Y', 'ON'], true);
        }

        return false;
    }

    /**
     * Column 15 "Variant Taxable": when set, FALSE clears variant tax; TRUE uses product tax category.
     * When empty, inherit product tax category on the variant.
     */
    private function variantTaxCategoryIdFromCsv(array $da, ?int $productTaxCategoryId): ?int
    {
        if (isset($da[15]) && trim((string) $da[15]) !== '') {
            return $this->csvTruthy($da[15]) ? $productTaxCategoryId : null;
        }

        return $productTaxCategoryId;
    }

    /**
     * Create a new tag + translation for each CSV value (no lookup / deduplication).
     */
    private function createTagDirectForImport(string $name, ClientLanguage $client_lang): Tag
    {
        $name = trim($name);
        $tag = new Tag();
        $tag->save();
        $slug = static::slugNew($name);
        if ($slug === '') {
            $slug = 'tag-' . $tag->id;
        }
        TagTranslation::create([
            'tag_id' => $tag->id,
            'language_id' => $client_lang->language_id,
            'name' => $name,
            'slug' => $slug,
        ]);

        return $tag->fresh();
    }

    /**
     * @param  array<int, string>  $headerNeedles  Normalized by lowercase trim match against CSV header row.
     */
    private function csvCellByHeader(array $da, int $fallbackIndex, array $headerNeedles): string
    {
        $idx = $this->resolveColumnIndexByHeaderNames($headerNeedles);
        if ($idx !== null && array_key_exists($idx, $da)) {
            return (string) $da[$idx];
        }

        return (string) ($da[$fallbackIndex] ?? '');
    }

    /**
     * @param  array<int, string>  $headerNeedles
     */
    private function resolveColumnIndexByHeaderNames(array $headerNeedles): ?int
    {
        if ($this->headerRow === null) {
            return null;
        }
        $needles = array_map(function ($n) {
            return strtolower(trim($n));
        }, $headerNeedles);
        foreach ($this->headerRow as $i => $label) {
            $l = strtolower(trim((string) $label));
            if (in_array($l, $needles, true)) {
                return (int) $i;
            }
        }

        return null;
    }

    private function replaceProductImagesFromCsv(int $productId, string $urlsCsv): void
    {
        $existing = ProductImage::where('product_id', $productId)->get();
        foreach ($existing as $pi) {
            if ($pi->media_id) {
                VendorMedia::where('id', $pi->media_id)->delete();
            }
            $pi->delete();
        }
        $ordinal = 0;
        foreach (explode(',', $urlsCsv) as $file) {
            $file = trim((string) $file);
            if ($file === '') {
                continue;
            }
            $img = new VendorMedia();
            $img->media_type = 1;
            $img->vendor_id = $this->vendor_id;
            $img->path = $file;
            $img->save();
            $image = new ProductImage();
            $image->product_id = $productId;
            $image->is_default = $ordinal === 0 ? 1 : 0;
            $image->media_id = $img->id;
            $image->save();
            $ordinal ++;
        }
    }

    /**
     * Normalize a CSV/Excel row for logging (preserve column indexes).
     */
    private function rowForLog($row): array
    {
        if ($row instanceof Collection) {
            return $row->all();
        }
        if (is_array($row)) {
            return $row;
        }

        return [(string) $row];
    }

    private function generateBarcodeNumber()
    {
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }

    public static function slugNew($title, $separator = '-', $language = 'en')
    {
        $title = $language ? static::asciis($title, $language) : $title;

        // Convert all dashes/underscores into separator
        $flip = $separator === '-' ? '_' : '-';

        $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);

        // Replace @ with the word 'at'
        $title = str_replace('@', $separator . 'at' . $separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', $title);

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);

        return trim($title, $separator);
    }

    public static function asciis($value, $language = 'en')
    {
        return ASCII::to_ascii((string) $value, $language);
    }
}
