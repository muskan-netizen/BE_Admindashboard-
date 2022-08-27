<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\VendorRegistrationDocument;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantSet;

class VendorProductExport implements FromCollection, WithHeadings, WithMapping{

    protected $id;

    function __construct($id) {
            $this->id = $id;
    }
    public function collection(){

        
        $products = Product::with(['media.image', 'primary', 'category.cat', 'brand', 'tags.tag', 'variant.tax', 'addOn.addOnName', 'vatoptions', 'variantSets'])->select('id', 'sku', 'title', 'body_html','vendor_id', 'is_live', 'is_new', 'is_featured', 'has_inventory', 'has_variant', 'sell_when_out_of_stock', 'Requires_last_mile', 'averageRating', 'brand_id','minimum_order_count','batch_count')
                    ->where('vendor_id', $this->id)->get();
        $dataArra = array();
        foreach($products as $product):
            if(!empty($product->variant[0])):
                $addonarray = array();
                foreach($product->addOn as $addon):
                    $addonarray[] = $addon->addOnName->title;
                endforeach;
                $tagarray = array();
                foreach($product->tags as $tags):
                    $tagarray[] = $tags->tag->title;
                endforeach;
                foreach($product->variant as $variant):
                    
                    $varientsetdata = ProductVariantSet::with(['variantDetail', 'optionData'])->where('product_variant_id', $variant->id)->where('product_id', $product->id)->first();
                    $array = array();
                    $array[] = $product->sku;
                    $array[] = $product->title;
                    $array[] = $product->body_html;
                    $array[] = ($product->is_live == 1) ? TRUE : FALSE;
                    $array[] = $product->category->cat->name;
                    $array[] = ($varientsetdata)?$varientsetdata->variantDetail->title:'';
                    $array[] = ($varientsetdata)?$varientsetdata->optionData->title:'';
                    $array[] = (!empty($varientsetdata))?$variant->sku:'';
                    $array[] = (!empty($varientsetdata))?$variant->price:'';
                    $array[] = (!empty($varientsetdata))?$variant->quantity:'';
                    $array[] = ($varientsetdata)?$variant->compare_at_price:'';
                    $array[] = (!empty($variant->cost_price))?$variant->cost_price:0;
                    $array[] = ($variant->brand)?$variant->brand->title:'';
                    $array[] = ($variant->tax)?$variant->tax->title:'';
                    $array[] = (isset($product->media[0]))?$product->media[0]->image->path['original_image']:'';
                    $array[] = (empty($varientsetdata))?$variant->quantity:'';
                    $array[] = (empty($varientsetdata))?$variant->price:'';
                    $array[] = (empty($varientsetdata))?$variant->compare_at_price:'';
                    $array[] = implode(', ', $addonarray);
                    $array[] = implode(', ', $tagarray);
                    $dataArra[] = $array;
                endforeach;
            endif;
        endforeach;
        $collection = collect();
        return $collection->push($dataArra);
    }

    public function headings(): array{
        $heding = [
            __('SKU'),
            __('Title'),
            __('Description (HTML)'),
             __('Published'),
             __('Category'),
             __('Option Name'),
             __('Option Value'),
             __('Variant SKU'),
             __('Variant Price'),
             __('Variant Quantity'),
             __('Variant Compare At Price'),
             __('Variant Cost Price'),
             __('Brand'),
             __('Tax Category'),
             __('Image Src'),
             __('Product Quantity'),
             __('Product Price'),
             __('Product Compare At Price'),
             __('Addon'),
             __('Tags'), 
        ];
       
        return $heding;
    }

    public function map($dataArra): array
    {
        return $dataArra;
    }

}
