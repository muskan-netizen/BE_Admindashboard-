<?php

namespace App\Http\Controllers\Client;

use Dotenv\Loader\Loader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Campaign, CampaignRoster, Celebrity, Brand, Category, Country, User, UserVendor, Client, Timezone, UserDevice, Vendor};
use Carbon\Carbon;

class CampaignController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){ 

        // $sendto = "testu00091@gmail.com";
        // $subject = "Test subject for email notification";
        // $body = "test body message";

        // $email_data = [
        //     // 'code' => '1324',
        //     // 'link' => "link",
        //     'email' => $sendto,
        //     'mail_from' => 'testu00091@gmail.com',
        //     // 'client_name' => 'XYZ',
        //     //'logo' => $client->logo['original'],
        //     'subject' => $subject,
        //     //'customer_name' => ucwords($user->name),
        //     'email_template_content' => $body,
        //     // 'cartData' => $cartDetails,
        //     // 'user_address' => $address,
        // ];
        // $email_data['send_to_cc'] = 0;
        // dispatch(new \App\Jobs\SendOrderSuccessEmailJob($email_data))->onQueue('verify_email');

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

        // $timezonedetail = Client::with('getTimezone')->first('timezone');
        // $tz = new Timezone();
        // $usertimezone = $tz->timezone_name($timezonedetail->timezone);        
        // echo $notification_time = Carbon::parse($request->schedule_datetime . $usertimezone ?? 'UTC')->tz('UTC');
        //  return $newnotification_time = $notification_time->addMinute($request->request_time_gap);

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
            // $campaign->email_title = $request->email_title;
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
            $notification_type = $request->type;
            if($usertype==1)    // for all users
            {
                $users = User::where(['status'=>1])->get();
                $getusercount = count($users);                
                $totalbatches = round($getusercount/$request->request_user_count);
                for ($i=1;$i<=$totalbatches;$i++)
                {
                    $usercount = $request->request_user_count;
                    if($i==1)
                    {
                        $notification_time = $notification_time;
                    }else{                        
                        $notification_time = $notification_time->addMinute($request->request_time_gap);
                    }
                    $roasterdata = [];
                    $conditionalvalue = (($i*$usercount)<=$getusercount)?$i*$usercount:$getusercount;
                    for($j = (($i-1)*$usercount); $j<$conditionalvalue;$j++)
                    {                        
                        $getdevicedetail = UserDevice::where('user_id',$users[$j]->id)->latest()->first();
                        if($getdevicedetail)
                        {
                            $roasterdata[] = array(
                                'campaign_id'   =>  $campaign->id,
                                'user_id'   =>  $users[$j]->id,
                                'notification_time'   =>  $notification_time,
                                'notofication_type'   =>  $notification_type,
                                'device_type'   =>  $getdevicedetail->device_type,
                                'device_token'   =>  $getdevicedetail->device_token,
                                'status'    =>  0
                            );
                        }                        
                    }
                    $insertroaster = CampaignRoster::insert($roasterdata);
                }
                $total_requests = CampaignRoster::where('campaign_id',$campaign->id)->count();
                Campaign::where('id',$campaign->id)->update(['total_request_count'=>$total_requests]);
                
            }else{  //for vendors only
                $vendors = UserVendor::select('user_id')->with('user')->groupBy('user_id')->get();
                $getusercount = count($vendors);                
                $usercount = $request->request_user_count;
                $totalbatches = round($getusercount/$usercount);
                for ($i=1;$i<=$totalbatches;$i++)
                {                    
                    if($i==1)
                    {
                        $notification_time = $notification_time;
                    }else{                        
                        $notification_time = $notification_time->addMinute($request->request_time_gap);
                    }                    
                    $roasterdata = [];
                    $conditionalvalue = (($i*$usercount)<=$getusercount)?$i*$usercount:$getusercount;
                    for($j = (($i-1)*$usercount); $j<$conditionalvalue;$j++)
                    {
                        $getdevicedetail = UserDevice::where('user_id',$vendors[$j]->user_id)->latest()->first();
                        if($getdevicedetail)
                        {
                            $roasterdata[] = array(
                                'campaign_id'   =>  $campaign->id,
                                'user_id'   =>  $vendors[$j]->user_id,
                                'notification_time'   =>  $notification_time,
                                'notofication_type'   =>  $notification_type,
                                'device_type'   =>  $getdevicedetail->device_type,
                                'device_token'   =>  $getdevicedetail->device_token,
                                'status'    =>  0
                            );
                        }  
                    }
                    $insertroaster = CampaignRoster::insert($roasterdata);
                }
                $total_requests = CampaignRoster::where('campaign_id',$campaign->id)->count();
                Campaign::where('id',$campaign->id)->update(['total_request_count'=>$total_requests]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Campaign created Successfully!',
                'data' => $campaign
            ]);
        }
    }

    public function GetPushOptions(Request $request)
    {
        $pushoption =  $request->pushvalue;
        $html='<select class="form-control" name="push_url_option_value" id="push_url_option_value"> ';
        if($pushoption==2)  //categories        
        {
            $getcategories = Category::where('status',1)->with('translation_one')->get(['id','slug']);            
            foreach($getcategories as $singlecategory)
            {
                $html .= '<option value="'.$singlecategory->id.'">'.$singlecategory->translation_one->name.'</option>';
            }
        }elseif($pushoption==3) //vendors
        {
            $getvendors = Vendor::where('status',1)->get(['id','name','slug']);
            foreach($getvendors as $singlevendor)
            {
                $html .= '<option value="'.$singlevendor->id.'">'.$singlevendor->name.'</option>';
            }
        }else{

        }
        $html .= '</select>';
        $result = array('html'=>$html);
        echo json_encode($result);
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
        CampaignRoster::where('campaign_id',$id)->delete();
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
