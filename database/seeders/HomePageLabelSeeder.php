<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomePageLabel;
use DB;

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
        DB::table('home_page_labels')->truncate();    
        DB::table('home_page_label_transaltions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');   
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Featured Products',
            'slug' => 'featured_products',
        ]);
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Vendors',
            'slug' => 'vendors',
        ]);
        $home_page = HomePageLabel::insertGetId([
            'title' => 'New Products',
            'slug' => 'new_products',
        ]);
        $home_page = HomePageLabel::insertGetId([
            'title' => 'On Sale',
            'slug' => 'on_sale',
        ]);
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Brands',
            'slug' => 'brands',
        ]);
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Best Sellers',
            'slug' => 'best_sellers',
        ]);
    }
}
