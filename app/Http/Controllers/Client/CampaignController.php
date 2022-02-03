<?php

namespace App\Http\Controllers\Client;

use Dotenv\Loader\Loader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Campaign, CampaignRoster, Celebrity, Brand, Country, User, UserVendor, Client, Timezone };
use Carbon\Carbon;

class CampaignController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){     
        //return $vendors = UserVendor::select('user_id')->with('user')->groupBy('user_id')->get();
        $campaigns = Campaign::all();
        return view('backend.campaign.index')->with(['campaigns' => $campaigns]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){      
        //dd($request->all());
        $rules = array(
            // 'slug' => 'required|string|max:30|unique:celebrities',
            'title' => 'required|string|max:190',
            'schedule_datetime' => 'required'
        );
        /* upload logo file */
        if ($request->hasFile('push_image')) {
            $rules['push_image'] =  'image|mimes:jpeg,png,jpg,gif';
        }
        $validation  = Validator::make($request->all(), $rules)->validate();
        $campaign = new Campaign();
        $campaign->title = $request->title;
        $campaign->type = $request->type;
        if($request->type==1)
        {
            $campaign->sms_text = $request->sms_text;
        }elseif($request->type==2)
        {
            $campaign->email_title = $request->email_title;
            $campaign->email_subject = $request->email_subject;
            $campaign->email_body = $request->email_body;
        }else{
            $campaign->push_title = $request->push_title;
            $campaign->push_message_body = $request->push_message_body;
            $campaign->push_url_option = $request->push_url_option;
            $campaign->push_url_option_value = $request->push_url_option_value;
        }        
        $campaign->send_to = $request->send_to;
        $campaign->schedule_datetime = $request->schedule_datetime;
        $campaign->request_user_count = $request->request_user_count;
        $campaign->request_time_difference = $request->request_time_gap;
        $campaign->status = $request->status;
        if($request->type==3)
        {
            if ($request->hasFile('push_image')) {
                $file = $request->file('push_image');
                $images = Storage::disk('s3')->put('/notification', $file, 'public');
                $campaign->push_image = $images;
            }
        }
        $campaign->save();
        if ($campaign->id > 0) {
            $usertype = $request->send_to;
            $timezonedetail = Client::with('getTimezone')->first('timezone');
            $tz = new Timezone();
            $usertimezone = $tz->timezone_name($timezonedetail->timezone);        
            $notification_time = Carbon::parse($request->schedule_datetime . $usertimezone ?? 'UTC')->tz('UTC');
            if($usertype==1)    // for all users
            {
                $users = User::where(['status'=>1])->get();

            }else{  //for vendors only
                $vendors = UserVendor::select('user_id')->with('user')->groupBy('user_id')->get();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Campaign created Successfully!',
                'data' => $campaign
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $campaign = Campaign::where('id', $id)->first();
        
        $returnHTML = view('backend.campaign.form')->with(['campaign' => $campaign])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($domain = '', Request $request, $id)
    {
        $rules = array(
            'slug' => 'required|string|max:30|unique:categories,slug,'.$id,
            'name' => 'required|string|max:150',
        );
        if ($request->hasFile('image')) {
            $rules['image'] =  'image|mimes:jpeg,png,jpg,gif';
        }
        $validation  = Validator::make($request->all(), $rules)->validate();
        $celebrity = Celebrity::where('id', $id)->firstOrFail();
        $celebrity->name = $request->input('name');
        $celebrity->slug = $request->input('slug');
        $celebrity->country_id = $request->input('countries');
        $celebrity->description = $request->description;
        $celebrity->status = '1';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $images = Storage::disk('s3')->put('/celebrity', $file, 'public');
            $celebrity->avatar = $images;
        }

        $celebrity->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Celebrity created Successfully!',
            'data' => $celebrity
        ]);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id){
        Campaign::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Campaign deleted successfully!');
    }

    /**
     * Change the status of Loyalty card.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $domain = ''){
        $loyaltyCard = Celebrity::find($request->id);
        $loyaltyCard->status = $request->status;
        $loyaltyCard->save();
        return response()->json(array('success' => true, 'data' => $loyaltyCard));
    }

    /**
     * Get the default value of Redeem Point
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getBrandList($domain = ''){
        $brands = Brand::all();
        return response()->json(['brands' => $brands]);
    }
}
