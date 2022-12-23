<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;
use App\Models\{InfluencerCategory, Attribute, InfluencerAttribute, ReferEarnDetail, InfluencerTier, InfluencerUser};
use App\Http\Requests\InfluencerCategoryRequest;
use Auth;

class InfluencerReferAndEarnController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = [];
        if( checkTableExists('influ_attributes') ) {
            // , 'varcategory.cate.primary'
            $attributes = InfluencerAttribute::with('option','translation_one', 'influencerCategory')->where('status', '!=', 2)->orderBy('position', 'asc');
            if(Auth::user()->is_superadmin) {
                $attributes = $attributes->get();
            }
            else {
                $attributes = $attributes->where('user_id', Auth::id())->get();
            }
            
        }
        
        $influencer_list = InfluencerCategory::paginate(10);

        $influencer_tier_list = InfluencerTier::paginate(10);
        // dd($influencer_tier_list);
        return view('backend.influencerreferandearn.index')->with(['influencer_list' => $influencer_list, 'attributes' => $attributes, 'influencer_tier_list'=> $influencer_tier_list]);
    }

    function edit($domain ,$id) {
        
        $influence_edit = InfluencerCategory::where('id', $id)->first();
        
        if( !empty($influence_edit) ) {
            return view('backend.influencerreferandearn.create-edit')->with(['influence_edit' => $influence_edit]);
        }
        return redirect()->route('influencer-refer-earn.index');
    }

    function create(Request $request) {
        return view('backend.influencerreferandearn.create-edit')->with(['influence_edit' => '']);
    }

    function store(InfluencerCategoryRequest $request) {
        try {
            InfluencerCategory::create([
                'name' => $request->name,
                'is_active' => 1
            ]);

            return redirect()->route('influencer-refer-earn.index');
        }
        catch(\Exception $e) {
            return redirect()->route('influencer-refer-earn.index');
        }
    }

    function update(InfluencerCategoryRequest $request) {
        
        try {
            InfluencerCategory::where('id', $request->id)->update([
                'name' => $request->name,
                'is_active' => 1
            ]);
            return redirect()->route('influencer-refer-earn.index');
        }
        catch(\Exception $e) {
            return redirect()->route('influencer-refer-earn.index');
        }
    }

    function userList(Request $request) {
        $influencer_users = InfluencerUser::with(['user', 'tier'])->paginate(10);
        
        return view('backend.influencerreferandearn.user-list')->with(['influencer_users' => $influencer_users]);
    }
}
