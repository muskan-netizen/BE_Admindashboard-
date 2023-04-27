<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CabBookingLayout;
use Carbon\Carbon;
use DB;
use Log;

class HomePageLabelSeederDefault extends Seeder
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

        $already = CabBookingLayout::get()->pluck('slug');

        if (!$already->contains('vendors'))
            $home_page = CabBookingLayout::insertGetId([
                'title' => 'Vendors',
                'slug' => 'vendors',
                'order_by' => 1,
            ]);


        if (!$already->contains('featured_products'))
            $home_page = CabBookingLayout::insertGetId([
                'title' => 'Featured Products',
                'slug' => 'featured_products',
                'order_by' => 2,
            ]);




        if (!$already->contains('new_products')) {
            //   // Log::info($already);
            $home_page = CabBookingLayout::insertGetId([
                'title' => 'New Products',
                'slug' => 'new_products',
                'order_by' => 3,
            ]);
        }



        if (!$already->contains('on_sale'))
            $home_page = CabBookingLayout::insertGetId([
                'title' => 'On Sale',
                'slug' => 'on_sale',
                'order_by' => 4,
            ]);




        if (!$already->contains('best_sellers'))
            $home_page = CabBookingLayout::insertGetId([
                'title' => 'Best Sellers',
                'slug' => 'best_sellers',
                'order_by' => 5,
            ]);


        if (!$already->contains('brands'))
            $home_page = CabBookingLayout::insertGetId([
                'title' => 'Brands',
                'slug' => 'brands',
                'order_by' => 6,
            ]);

        if (!$already->contains('long_term_service'))
            $home_page = CabBookingLayout::insertGetId([
                'title'    => 'Long Term Service',
                'slug'     => 'long_term_service',
                'order_by' => 7,
            ]);


        if (!$already->contains('recently_viewed'))
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Recently Viewed',
                'slug'       => 'recently_viewed',
                'order_by'   => 7,
                'created_at' => Carbon::now(),
            ]);


        if (!$already->contains('spotlight_deals'))
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Spotlight Deals',
                'slug'       => 'spotlight_deals',
                'order_by'   => 8,
                'created_at' => Carbon::now(),
            ]);


        if (!$already->contains('top_rated'))
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Top Rated',
                'slug'       => 'top_rated',
                'order_by'   => 9,
                'created_at' => Carbon::now(),
            ]);


        if (!$already->contains('nav_categories'))
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'NavCategories',
                'slug'       => 'nav_categories',
                'order_by'   => 10,
                'created_at' => Carbon::now(),
            ]);

        if (!$already->contains('single_category_products')) {
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Single Category Products',
                'slug'       => 'single_category_products',
                'order_by'   => 11,
                'created_at' => Carbon::now(),
            ]);
        }

        if (!$already->contains('selected_products')) {
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Selected Products',
                'slug'       => 'selected_products',
                'order_by'   => 11,
                'created_at' => Carbon::now(),
            ]);
        }

        if (!$already->contains('most_popular_products')) {
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Most Popular Products',
                'slug'       => 'most_popular_products',
                'order_by'   => 12,
                'created_at' => Carbon::now(),
            ]);
        }

        if (!$already->contains('ordered_products')) {
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Ordered Products',
                'slug'       => 'ordered_products',
                'order_by'   => 13,
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
