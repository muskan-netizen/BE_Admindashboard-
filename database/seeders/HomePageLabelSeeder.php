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

        $already = HomePageLabel::get()->pluck('slug');

        if (!$already->contains('vendors')) {
            $home_page = HomePageLabel::insertGetId([
                'title' => 'Vendors',
                'slug' => 'vendors',
                'order_by' => 1,
            ]);
        }
        if (!$already->contains('featured_products')) 
            $home_page = HomePageLabel::insertGetId([
                'title' => 'Featured Products',
                'slug' => 'featured_products',
                'order_by' => 2,
            ]);




        if (!$already->contains('new_products')) {
            //   // Log::info($already);
            $home_page = HomePageLabel::insertGetId([
                'title' => 'New Products',
                'slug' => 'new_products',
                'order_by' => 3,
            ]);
        }



        if (!$already->contains('on_sale')) 
            $home_page = HomePageLabel::insertGetId([
                'title' => 'On Sale',
                'slug' => 'on_sale',
                'order_by' => 4,
            ]);



            if (!$already->contains('best_sellers')) 
            $home_page = HomePageLabel::insertGetId([
                'title' => 'Best Sellers',
                'slug' => 'best_sellers',
                'order_by' => 5,
            ]);


            if (!$already->contains('brands')) 
            $home_page = HomePageLabel::insertGetId([
                'title' => 'Brands',
                'slug' => 'brands',
                'order_by' => 6,
            ]);


            if (!$already->contains('pickup_delivery')) {
            $home_page = HomePageLabel::insertGetId([
                'id' => 7,
                'title' => 'Pickup Delivery',
                'slug' => 'pickup_delivery',
                'order_by' => 7,
                'is_active' => 0
            ]);
        }


        if (!$already->contains('dynamic_page')) 
            $home_page = HomePageLabel::insertGetId([
                'title' => 'Dynamic HTML',
                'slug' => 'dynamic_page',
                'order_by' => 8,
            ]);



            if (!$already->contains('trending_vendors')) 
            $home_page = HomePageLabel::insertGetId([
                'title' => 'Trending Vendors',
                'slug' => 'trending_vendors',
                'order_by' => 9,
            ]);


            if (!$already->contains('recent_orders')) 
            $home_page = HomePageLabel::insertGetId([
                'title' => 'Recent Orders',
                'slug' => 'recent_orders',
                'order_by' => 10,
            ]);


            if (!$already->contains('cities')) 
            $home_page = HomePageLabel::insertGetId([
                'title' => 'Cities',
                'slug' => 'cities',
                'order_by' => 11,
            ]);

            if (!$already->contains('long_term_service')) 
            $home_page = HomePageLabel::insertGetId([
                'title' => 'Long Term Service',
                'slug' => 'long_term_service',
                'order_by' => 12,
            ]);


            if (!$already->contains('recently_viewed')) 
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Recently Viewed',
                'slug'       => 'recently_viewed',
                'order_by'   => 12,
                'created_at' => Carbon::now(),
            ]);


            if (!$already->contains('spotlight_deals')) 
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Spotlight Deals',
                'slug'       => 'spotlight_deals',
                'order_by'   => 13,
                'created_at' => Carbon::now(),
            ]);


            if (!$already->contains('top_rated')) 
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Top Rated',
                'slug'       => 'top_rated',
                'order_by'   => 14,
                'created_at' => Carbon::now(),
            ]);

            if (!$already->contains('nav_categories')) 
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'NavCategories',
                'slug'       => 'nav_categories',
                'order_by'   => 15,
                'created_at' => Carbon::now(),
            ]);

            if (!$already->contains('single_category_products')) {
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Single Category Products',
                'slug'       => 'single_category_products',
                'order_by'   => 16,
                'created_at' => Carbon::now(),
            ]);
        }

        if (!$already->contains('selected_products')) {
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Selected Products',
                'slug'       => 'selected_products',
                'order_by'   => 16,
                'created_at' => Carbon::now(),
            ]);
        }

        if (!$already->contains('most_popular_products')) {
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Most Popular Products',
                'slug'       => 'most_popular_products',
                'order_by'   => 17,
                'created_at' => Carbon::now(),
            ]);
        }
        if (!$already->contains('banner')) {
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Banner',
                'slug'       => 'banner',
                'order_by'   => 18,
                'created_at' => Carbon::now(),
            ]);
        }

        if (!$already->contains('ordered_products')) {
            $home_page = HomePageLabel::insertGetId([
                'title'      => 'Ordered Products',
                'slug'       => 'ordered_products',
                'order_by'   => 19,
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
