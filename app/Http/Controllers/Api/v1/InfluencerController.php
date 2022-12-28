<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\InfluencerAttribute;
use App\Models\InfluencerCategory;
use App\Models\InfluencerUser;
use Illuminate\Http\Request;
use Auth;
use Session;

class InfluencerController extends Controller
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
        $data = ['influencer_category' => $influencer_category, 'influencer_user' => $influencer_user];
        return $this->successResponse($data);
    }

    function getInfluencerForm(Request $request, $id) {
        
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
        $data = ['attributes' => $productAttributes];
        return $this->successResponse($data);
    }
}
