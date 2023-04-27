<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomePageLabel;
use Carbon\Carbon;
use DB;
use Log;
class HomePageLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $already = HomePageLabel::where('slug', 'vendors')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Vendors',
            'slug' => 'vendors',
            'order_by' => 1,
        ]);


        $already = HomePageLabel::where('slug', 'featured_products')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Featured Products',
            'slug' => 'featured_products',
            'order_by' => 2,
        ]);



        $already = HomePageLabel::where('slug', 'new_products')->count();

        if($already == 0){
        //   // Log::info($already);
            $home_page = HomePageLabel::insertGetId([
                'title' => 'New Products',
                'slug' => 'new_products',
                'order_by' => 3,
            ]);
        }


        $already = HomePageLabel::where('slug', 'on_sale')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'On Sale',
            'slug' => 'on_sale',
            'order_by' => 4,
        ]);



        $already = HomePageLabel::where('slug', 'best_sellers')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Best Sellers',
            'slug' => 'best_sellers',
            'order_by' => 5,
        ]);

        $already = HomePageLabel::where('slug', 'brands')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Brands',
            'slug' => 'brands',
            'order_by' => 6,
        ]);

        $already = HomePageLabel::where('slug', 'pickup_delivery')->count();

        if($already == 0){
             $home_page = HomePageLabel::insertGetId([
                'id' => 7,
                'title' => 'Pickup Delivery',
                'slug' => 'pickup_delivery',
                'order_by' => 7,
                'is_active' => 0
            ]);

        }

        $already = HomePageLabel::where('slug', 'dynamic_page')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Dynamic HTML',
            'slug' => 'dynamic_page',
            'order_by' => 8,
        ]);


        $already = HomePageLabel::where('slug', 'trending_vendors')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Trending Vendors',
            'slug' => 'trending_vendors',
            'order_by' => 9,
        ]);

        $already = HomePageLabel::where('slug', 'recent_orders')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Recent Orders',
            'slug' => 'recent_orders',
            'order_by' => 10,
        ]);

        $already = HomePageLabel::where('slug', 'cities')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Cities',
            'slug' => 'cities',
            'order_by' => 11,
        ]);
        
        $already = HomePageLabel::where('slug', 'long_term_service')->count();
        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Long Term Service',
            'slug' => 'long_term_service',
            'order_by' => 12,
        ]);

        $already = HomePageLabel::where('slug', 'recently_viewed')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title'      => 'Recently Viewed',
            'slug'       => 'recently_viewed',
            'order_by'   => 12,
            'created_at' => Carbon::now(),
        ]);

        $already = HomePageLabel::where('slug', 'spotlight_deals')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title'      => 'Spotlight Deals',
            'slug'       => 'spotlight_deals',
            'order_by'   => 13,
            'created_at' => Carbon::now(),
        ]);

        $already = HomePageLabel::where('slug', 'top_rated')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title'      => 'Top Rated',
            'slug'       => 'top_rated',
            'order_by'   => 14,
            'created_at' => Carbon::now(),
        ]);

        $already = HomePageLabel::where('slug', 'nav_categories')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title'      => 'NavCategories',
            'slug'       => 'nav_categories',
            'order_by'   => 15,
            'created_at' => Carbon::now(),
        ]);

        $already = HomePageLabel::where('slug', 'single_category_products')->count();
        if($already == 0){
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Single Category Products',
                'slug'       => 'single_category_products',
                'order_by'   => 16,
                'created_at' => Carbon::now(),
            ]);
        }

        $already = HomePageLabel::where('slug', 'selected_products')->count();
        if($already == 0){
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Selected Products',
                'slug'       => 'selected_products',
                'order_by'   => 16,
                'created_at' => Carbon::now(),
            ]);
        }

        $already = HomePageLabel::where('slug', 'most_popular_products')->count();
        if($already == 0){
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Most Popular Products',
                'slug'       => 'most_popular_products',
                'order_by'   => 17,
                'created_at' => Carbon::now(),
            ]);
        }
        $already = HomePageLabel::where('slug', 'banner')->count();
        if($already == 0){
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Banner',
                'slug'       => 'banner',
                'order_by'   => 18,
                'created_at' => Carbon::now(),
            ]);
        }

        $already = HomePageLabel::where('slug', 'ordered_products')->count();
        if($already == 0){
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Ordered Products',
                'slug'       => 'ordered_products',
                'order_by'   => 19,
                'created_at' => Carbon::now(),
            ]);
        }
        
    }
}