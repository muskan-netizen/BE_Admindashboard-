<?php

namespace App\Http\Controllers\Client\GiftCard;
use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session,Log,DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ApiResponser;
use App\Models\{GiftCard};

use Exception;
class GiftcardController extends BaseController
{
    use ApiResponser;
   
    

    /**
     * Add Block slot for rental
     *
     * @param Request $request
     * @param mixed $name
     * @return void
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
        	$data = GiftCard::where('is_deleted',0);//->get();
           // pr($data->toArray());
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('image_url', function($users) {
                        return $users->image['proxy_url'] . '40/40' . $users->image['image_path'];
                      // return "<img src='.$url.' class='rounded-circle'>";
                    })
                    // ->editColumn('is_user_verified',function($row){
                    //     $modify_url = route('change_agent_status',[$row->id]);
                    //     if($row->is_user_verified)
                    //     $btn = '<input type="checkbox" name="change_status" checked data-bootstrap-switch data-off-color="danger" data-on-color="success"  data-on-text="ACTIVE" data-off-text="INACTIVE" data-href ="'.$modify_url.'">';
                    //     else
                    //     $btn = '<input type="checkbox" name="change_status" data-bootstrap-switch data-off-color="danger" data-on-color="success"  data-on-text="ACTIVE" data-off-text="INACTIVE" data-href ="'.$modify_url.'">';
                    //     return $btn;
                    // })
                    ->editColumn('action', function ($data) use ($request) {
                        $approve_action = '';
                    
                     
                        $action = '<div class="inner-div"> 
                                    <a href="#" class="action-icon editGiftCard" data-gify-card="' . $data->id . '"> <i class="mdi mdi-square-edit-outline"></i></a>
                                    <a href="#" class="action-icon deleteGiftCard"  data-gify-card="' . $data->id . '"> <i class="mdi mdi mdi-delete"></i></a>
                                    </div>
                                    ';
                        return $action;
                    })
                    ->rawColumns(['action','image_url'])
                    ->make(true);
        }
        return view('backend/giftcard/index')->with(['listdata'=>'']);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'short_desc' => 'required',
            'expiry_date' => 'required',
            'amount' => 'required|numeric',
            'name' => 'required|string|max:150||unique:gift_cards',
        );
        if ($request->hasFile('image')) {    /* upload logo file */
            $rules['image'] =  'image|mimes:jpeg,png,jpg,gif';
        }
        $validation  = Validator::make($request->all(), $rules)->validate();
        $giftcard = new GiftCard();
        $giftcardReturn = $this->save($request, $giftcard, 'false');
   
        if($giftcardReturn){
            return response()->json([
                'status'=>'success',
                'message' => __('Gift Card created Successfully!'),
                'data' => []
            ]);
        }else{
            return response()->json([
                'status'=>'error',
                'message' => __('Something went wrong. please try again!'),
                'data' => []
            ]);
        }
    }

     /**
     * save and update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GiftCard  $banner
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, GiftCard $GiftCard, $update = 'false'){
        try{
            foreach ($request->only('name', 'amount', 'expiry_date','short_desc', 'title') as $key => $value) {
                $GiftCard->{$key} = $value;
            }
            if ($request->hasFile('image')) {    /* upload logo file */
                $file            = $request->file('image');
                $GiftCard->image = Storage::disk('s3')->put('/giftcard', $file,'public');
            }
    
            $GiftCard->added_by = Auth::id()??null;
            $GiftCard->save();
        
            return $GiftCard;
        } catch (Exception $e) {
            Log::info('add gift Card: '. $e->getCode());
            return [];
        }
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GiftCard  $promocode
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id){
      
        $GiftCard = GiftCard::where('id', $id)->first();
      
        $returnHTML = view('backend.giftcard.editForm')->with(['GiftCard' => $GiftCard])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GiftCard  $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id = 0){
        $rules = array(
            'name' => 'required|string|max:150||unique:gift_cards,name,'.$id,
            'amount' => 'required|numeric',
            'title' => 'required',
            'short_desc' => 'required',
            'expiry_date' => 'required',
        );
        if ($request->hasFile('image')) {    /* upload logo file */
            $rules['image'] =  'image|mimes:jpeg,png,jpg,gif';
        }
        $validation  = Validator::make($request->all(), $rules)->validate();
        $GiftCard = GiftCard::findOrFail($id);
        $giftcardReturn = $this->save($request, $GiftCard, 'false');
        if($giftcardReturn){
            return response()->json([
                'status'=>'success',
                'message' => __('Gift Card Updated Successfully!'),
                'data' => $giftcardReturn
            ]);
        }else{
            return response()->json([
                'status'=>'error',
                'message' => __('Something went wrong. please try again!'),
                'data' => []
            ]);
        }
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GiftCard  $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id){
        try{
            GiftCard::where('id', $id)->delete();
            return response()->json([
                'status'=>'success',
                'message' => __('GiftCard deleted successfully!'),
                'data' => []
            ]);
        } catch (Exception $e) {
            Log::info('destroy gift Card: '. $e->getCode());
            return response()->json([
                'status'=>'error',
                'message' => $e->getCode(),
                'data' => []
            ]);
        }
        
    }


}
