<?php

namespace App\Http\Traits\HomePage;

use App\Models\{Category, HomePageLabel, HomeProduct, Product, Vendor, VendorCategory};
use Carbon\Carbon;
use Session, DB;
use Illuminate\Support\Str;


trait WebStylingTrait
{

    public function getCategoryListing()
    {
        return $p_categories = Category::with(['parent', 'translation_one'])
            ->whereIn('type_id', ['1', '3', '7', '8', '9'])
            ->where('id', '>', '1')
            ->where('deleted_at', NULL)
            ->where('status', 1)
            ->orderBy('parent_id', 'asc')
            ->orderBy('position', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function homePageLabelExists($slug)
    {
        return HomePageLabel::where('slug', '=', $slug)->exists();
    }

    public function getCategories($slug)
    {
        $single_category_products = [];

        if ($this->homePageLabelExists($slug)) {
            $single_category_products['categories'] = $this->getCategoryListing();
            $single_category_products['slug'] = $slug;
        }
        return $single_category_products;
    }

    public function updateSingleCategoryProductsToDb($request)
    {


        if (checkTableExists('home_products')) {
            $insert = ['slug' => 'single_category_products', 'product_category' => $request->product_category];
            HomeProduct::updateOrCreate(
                ['slug' => $insert['slug']],
                ['category_id' => $insert['product_category']]
            );
        }
        return true;
    }
    public function updateSelectedProductstoDb($id, $request)
    {
      
        if (checkTableExists('home_products')) {
            $delete = HomeProduct::where('layout_id', $id)->delete();
            foreach($request->selected_products as $products){
            $relatedArray[] = [
                'slug' => 'selected_products',
                'products' => $products,
                'layout_id'=> $id
            ];
        }
            HomeProduct::insert($relatedArray);

        }
        return true;
    }


    public function getSingleCategoryProducts($slug)
    {
        if (checkTableExists('home_products')) {
            return HomeProduct::where('slug', $slug)->first();
        }
        return [];
    }

    public function getSelectedProducts()
    {
        $product_ids = [];
        if (checkColumnExists('home_products', 'slug')) {
            $single_category_products = HomeProduct::whereSlug('selected_products')->first();
            if (!empty($single_category_products->products)) {

                $product_ids = json_decode($single_category_products->products);
            } else {
                $product_ids = [];
            }
        }
        return $product_ids;
    }
}
