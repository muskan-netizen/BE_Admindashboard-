<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{ClientPreference, HomePageLabel,ClientLanguage, HomePageLabelTranslation};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WebStylingController extends BaseController{
    //
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $client_preferences = ClientPreference::first();
        $home_page_labels = HomePageLabel::with('translations')->orderBy('order_by')->get();
        // pr($home_page_labels->toArray());die;
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->where('client_languages.is_active', 1)
                    ->orderBy('client_languages.is_primary', 'desc')->get();

        return view('backend/web_styling/index')->with(['client_preferences' => $client_preferences,'home_page_labels' => $home_page_labels, 'langs' => $langs]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWebStyles(Request $request){
        // dd($request->all());
        foreach ($request->home_labels as $key => $value) {
            $home_translation = HomePageLabelTranslation::where('language_id', $request->languages[$key])->where('home_page_label_id', $request->home_labels[$key])->first();
            if (!$home_translation) {
                $home_translation = new HomePageLabelTranslation();
            }
            $home_translation->title = $request->names[$key];
            $home_translation->home_page_label_id = $request->home_labels[$key];
            $home_translation->language_id = $request->languages[$key];
            $home_translation->save();
        }
        $featured_products = HomePageLabel::where('slug', 'featured_products')->first();
        if($featured_products){
            $featured_products->is_active = $request->has('featured_products') && $request->featured_products == "on" ? 1 : 0;
            $featured_products->save(); 
        }
        $vendors = HomePageLabel::where('slug', 'vendors')->first();
        if($vendors){
            $vendors->is_active = $request->has('vendors') && $request->vendors == "on" ? 1 : 0;
            $vendors->save(); 
        }
        $new_products = HomePageLabel::where('slug', 'new_products')->first();
        if($new_products){
            $new_products->is_active = $request->has('new_products') && $request->new_products == "on" ? 1 : 0;
            $new_products->save(); 
        }
        $on_sale = HomePageLabel::where('slug', 'on_sale')->first();
        if($on_sale){
            $on_sale->is_active = $request->has('on_sale') && $request->on_sale == "on" ? 1 : 0;
            $on_sale->save(); 
        }
        $brands = HomePageLabel::where('slug', 'brands')->first();
        if($brands){
            $brands->is_active = $request->has('brands') && $request->brands == "on" ? 1 : 0;
            $brands->save(); 
        }
        $best_sellers = HomePageLabel::where('slug', 'best_sellers')->first();
        if($best_sellers){
            $best_sellers->is_active = $request->has('best_sellers') && $request->best_sellers == "on" ? 1 : 0;
            $best_sellers->save(); 
        }
        $client_preferences = ClientPreference::first();
        if($client_preferences){
            if($request->has('favicon')){
                $client_preferences->favicon = Storage::disk('s3')->put('favicon', $request->favicon, 'public');
            }
            $client_preferences->web_color = $request->primary_color;
            $client_preferences->cart_enable = $request->cart_enable == 'on' ? 1 : 0;
            $client_preferences->age_restriction = $request->age_restriction == 'on' ? 1 : 0;
            $client_preferences->rating_check = $request->rating_enable == 'on' ? 1 : 0;
            $client_preferences->show_contact_us = $request->show_contact_us == 'on' ? 1 : 0;
            $client_preferences->show_icons = $request->show_icons == 'on' ? 1 : 0;
            $client_preferences->show_wishlist = $request->show_wishlist == 'on' ? 1 : 0;
            $client_preferences->age_restriction_title = $request->age_restriction_title;
            $client_preferences->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Web Styling Updated Successfully!'
        ]);
    }

    /**
     * save the order of banner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function saveOrder(Request $request)
    {
        foreach ($request->order as $key => $value) {
            $home_page = HomePageLabel::where('id', $value)->first();
            $home_page->order_by = $key + 1;
            $home_page->save();
        }
        return response()->json([
            'status'=>'success',
            'message' => 'Home Page Labels order updated Successfully!',
        ]);
    }
}
