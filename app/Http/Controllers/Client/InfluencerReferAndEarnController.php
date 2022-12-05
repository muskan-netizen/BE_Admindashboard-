<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;
use App\Models\{InfluencerCategory, Attribute};

class InfluencerReferAndEarnController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = Attribute::with('option', 'varcategory.cate.primary','translation_one')->where('status', '!=', 2)->orderBy('position', 'asc');
        if(Auth::user()->is_superadmin) {
            $attributes = $attributes->get();
        }
        else {
            $attributes = $attributes->where('user_id', Auth::id())->get();
        }
        $influencer_list = InfluencerCategory::paginate(10);
        return view('backend.influencerreferandearn.index')->with(['influencer_list' => $influencer_list]);
    }

    function edit() {

    }
}
