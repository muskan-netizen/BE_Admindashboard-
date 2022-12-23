<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\InfluencerUser;
use Illuminate\Http\Request;
use App\Models\{InfluencerCategory, InfluencerAttribute, ReferEarnDetail};
use Auth;
use Session;
class InfluencerReferAndEarnController extends Controller
{
    function index(Request $request) {
        $user =  Auth::user();
        $influencer_user = [];
        $influencer_category = [];
        if (checkTableExists('influencer_users')) {
            $influencer_user = InfluencerUser::with('user')->where('user_id', $user->id)->first();
        }
        if (checkTableExists('influencer_categories')) {
            $influencer_category = InfluencerCategory::get();
        }
        return view('frontend/account/referAndEarn')->with(['influencer_category' => $influencer_category, 'influencer_user' => $influencer_user]);
    }

    function getReferEarnForm(Request $request, $domain, $id) {
        
        $productAttributes = [];
        if( checkTableExists('influ_attributes') ) {
            // , 'varcategory.cate.primary'
            $productAttributes = InfluencerAttribute::with('option')
                ->select('influ_attributes.*')
                ->join('influ_attr_cat', 'influ_attr_cat.attribute_id', 'influ_attributes.id')
                ->where('influ_attr_cat.category_id', $id)
                ->where('influ_attributes.status', '!=', 2)
                ->orderBy('position', 'asc')->get();
        }
        
        return view('frontend/account/referAndEarnForm')->with(['productAttributes' => $productAttributes]);
    }

    function save(Request $request) {
        try {
            if( !empty($request->attribute) ) {
            
                $insert_arr = [];
                $insert_count = 0;
                $user_id = Auth::user()->id;

                $influencer_user_id = InfluencerUser::insertGetId([
                    "user_id" => $user_id
                ]);
    
                foreach($request->attribute as $key => $value) {
                    if( !empty($value) && !empty($value['option'] && is_array($value) )) {
                        
                        if(!empty($value['type']) && $value['type'] == 1 ) { // dropdown
                            $value_arr = @$value['value'];
                            
                            foreach( $value['option'] as $key1 => $val1 ) {
                                if( @in_array($val1['option_id'], $value_arr) ) {
    
                                    $insert_arr[$insert_count]['influencer_user_id'] = $influencer_user_id ;
                                    $insert_arr[$insert_count]['user_id'] = $user_id;
                                    $insert_arr[$insert_count]['attribute_id'] = $value['id'];
                                    $insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
                                    $insert_arr[$insert_count]['attribute_option_id'] = $val1['option_id'];
                                    $insert_arr[$insert_count]['key_value'] = $val1['option_id'];
                                }
                                $insert_count++;
                            }
                        }
                        else {
                            foreach($value['option'] as $option_key => $option) {
                                if(@$option['value']){
                                    $insert_arr[$insert_count]['influencer_user_id'] = $influencer_user_id ;
                                    $insert_arr[$insert_count]['user_id'] = $user_id;
                                    $insert_arr[$insert_count]['attribute_id'] = $value['id'];
                                    $insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
                                    $insert_arr[$insert_count]['attribute_option_id'] = $option['option_id'];
                                    $insert_arr[$insert_count]['key_value'] = $option['value'] ?? $option['option_title'];
                                }
                                $insert_count++;
                            }
                        }
                    }
    
                
                }
                ReferEarnDetail::insert($insert_arr);
                Session::flash('success', 'Thanks for registering with us');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Session::flash('danger', 'Something went wrong');
            return redirect()->back();
        }
        
    }
}
