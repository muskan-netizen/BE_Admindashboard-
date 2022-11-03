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
        if (checkColumnExists('home_products', 'slug')) {
            $insert = ['slug' => 'single_category_products', 'product_category' => $request->product_category];
            HomeProduct::updateOrCreate(
                ['slug' => $insert['slug']],
                ['category_id' => $insert['product_category']]
            );
        }
        return true;
    }

    public function getSingleCategoryProducts($slug)
    {
        if (checkColumnExists('home_products', 'slug')) {
            return HomeProduct::where('slug', $slug)->first();
        }
        return [];
    }

    public function getSelectedProducts()
    {
        $product_ids = [];
        if (checkColumnExists('home_products', 'slug')) {
            $single_category_products = HomeProduct::whereSlug('selected_products')->first();
            $product_ids = json_decode($single_category_products->products);
        }
        return $product_ids;
    }
}
