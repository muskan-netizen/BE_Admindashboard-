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

        $already = CabBookingLayout::where('slug', 'vendors')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title' => 'Vendors',
            'slug' => 'vendors',
            'order_by' => 1,
        ]);

        $already = CabBookingLayout::where('slug', 'featured_products')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title' => 'Featured Products',
            'slug' => 'featured_products',
            'order_by' => 2,
        ]);



        $already = CabBookingLayout::where('slug', 'new_products')->count();

        if($already == 0){
        //   // Log::info($already);
            $home_page = CabBookingLayout::insertGetId([
                'title' => 'New Products',
                'slug' => 'new_products',
                'order_by' => 3,
            ]);
        }


        $already = CabBookingLayout::where('slug', 'on_sale')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title' => 'On Sale',
            'slug' => 'on_sale',
            'order_by' => 4,
        ]);



        $already = CabBookingLayout::where('slug', 'best_sellers')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title' => 'Best Sellers',
            'slug' => 'best_sellers',
            'order_by' => 5,
        ]);

        $already = CabBookingLayout::where('slug', 'brands')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title' => 'Brands',
            'slug' => 'brands',
            'order_by' => 6,
        ]);
        $already = CabBookingLayout::where('slug', 'long_term_service')->count();
        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title'    => 'Long Term Service',
            'slug'     => 'long_term_service',
            'order_by' => 7,
        ]);
        
        $already = CabBookingLayout::where('slug', 'recently_viewed')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title'      => 'Recently Viewed',
            'slug'       => 'recently_viewed',
            'order_by'   => 7,
            'created_at' => Carbon::now(),
        ]);

        $already = CabBookingLayout::where('slug', 'spotlight_deals')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title'      => 'Spotlight Deals',
            'slug'       => 'spotlight_deals',
            'order_by'   => 8,
            'created_at' => Carbon::now(),
        ]);

        $already = CabBookingLayout::where('slug', 'top_rated')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title'      => 'Top Rated',
            'slug'       => 'top_rated',
            'order_by'   => 9,
            'created_at' => Carbon::now(),
        ]);

        $already = CabBookingLayout::where('slug', 'nav_categories')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title'      => 'NavCategories',
            'slug'       => 'nav_categories',
            'order_by'   => 10,
            'created_at' => Carbon::now(),
        ]);

        $already = CabBookingLayout::where('slug', 'single_category_products')->count();
        if($already == 0){
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Single Category Products',
                'slug'       => 'single_category_products',
                'order_by'   => 11,
                'created_at' => Carbon::now(),
            ]);
        }

        $already = CabBookingLayout::where('slug', 'selected_products')->count();
        if($already == 0){
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Selected Products',
                'slug'       => 'selected_products',
                'order_by'   => 11,
                'created_at' => Carbon::now(),
            ]);
        }

        $already = CabBookingLayout::where('slug', 'most_popular_products')->count();
        if($already == 0){
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Most Popular Products',
                'slug'       => 'most_popular_products',
                'order_by'   => 12,
                'created_at' => Carbon::now(),
            ]);
        }

        $already = CabBookingLayout::where('slug', 'ordered_products')->count();
        if($already == 0){
            $home_page = CabBookingLayout::insertGetId([
                'title'      => 'Ordered Products',
                'slug'       => 'ordered_products',
                'order_by'   => 13,
                'created_at' => Carbon::now(),
            ]);
        }
        
    }
}
