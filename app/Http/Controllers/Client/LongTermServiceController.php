<?php

namespace App\Http\Controllers\Client;

use DB,Log;
use Session;
use DataTables;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{LongTermService, LongTermServiceProducts, ClientPreference, LongTermServiceTranslation};
class LongTermServiceController extends BaseController
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$domain = '',$vendor_id)
    {
        //echo $vendor_id;
        $LongTermService = LongTermService::with('primary','product.product.primary')->where('vendor_id',$vendor_id)->get();
        
        return Datatables::of($LongTermService)
        ->addIndexColumn()
        ->addColumn('service_title', function ($LongTermService) {
           
            return $LongTermService->primary ? $LongTermService->primary->name : $LongTermService->slug;
        })
        ->addColumn('service_product_title', function ($LongTermService) {
           
            return $LongTermService->product ? ($LongTermService->product->product ?  ( $LongTermService->product->product->primary ? $LongTermService->product->product->primary->title : $LongTermService->product->produc->sku  ) : 'NA'  ) : $LongTermService->slug;
        })
        ->addColumn('service_image', function ($LongTermService)  {
            $image = '';
            if($LongTermService->image){
                $image_path = $LongTermService->image['proxy_url'] . '30/30' . $LongTermService->image['image_path'];
                $image = '<img  class="rounded-circle" src="'. $image_path.'">';
            }
            return $image;
        })
        ->addColumn('service_product_quantity', function ($LongTermService)  {
           
            return $LongTermService->product ? $LongTermService->product->quantity : 0;
        })
        ->addColumn('time_period', function ($LongTermService)  {
            $title= '';
            if($LongTermService->service_period=='days'){
                $title= __('Daily');
            }elseif($LongTermService->service_period == 'monthly'){
                $title= __('Monthly');
            }else{
                $title= __('Weekly');
            }
            return $title;
        })
        ->editColumn('price', function ($LongTermService)  {
            return $LongTermService->product ?  decimal_format($LongTermService->product->price) : 0;
        })
        ->addColumn('action', function ($LongTermService) use ($request) {
            $edit_url = route('long_term_service.edit', $LongTermService->id);
            $delete_url = route('long_term_service.destroy', $LongTermService->id);
            $action = '<div class="form-ul" style="width: 60px;">
            <div class="inner-div" style="float: left;">
                <a class="action-icon edit_service" data-service_id="'.$LongTermService->id.'"
                    href="'.$edit_url.'"
                    userId="'.$LongTermService->id.'"><i
                        class="mdi mdi-square-edit-outline"></i></a>
            </div>
            <div class="inner-div">
                <a class="action-icon delete_service" data-service_id="'.$LongTermService->id.'"  href="'.$edit_url.'" ><i class="mdi mdi-delete"></i></a>
                
            </div>
        </div>';
            
            
            return $action;
        })
        ->rawColumns(['service_title','service_image','service_title','service_product_title','service_product_quantity','time_period','action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->validate($request, [
                'name.0' => 'required|string|max:60',
                'serviceSku' => 'required',
                'service_product_id' => 'required',
                'product_quantity' => 'required',
                'serice_price' => 'required',
               
                'service_product_variant_id' => 'required',
            ],['name.0' =>__('The default language name field is required.')]);
         

            if($request->has('long_term__service_id') && ($request->long_term__service_id != '') ){
                $LongTermService = LongTermService::where('id', $request->long_term__service_id)->first();
               // $LongTermService->sku = $request->serviceSku;
            }else{
                $LongTermService = new LongTermService();
                $LongTermService->sku = $request->serviceSku;
            }
            // upload file
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $LongTermService->image = Storage::disk('s3')->put('service', $file, 'public');
            }
            $LongTermService->price              = $request->serice_price;
            $LongTermService->compare_at_price   = $request->serice_price + 10;
            $LongTermService->vendor_id          = $request->vendor_id;
            $LongTermService->service_period     = $request->service_period;
            $LongTermService->save();

            $language_id = $request->language_id;
            LongTermServiceTranslation::where('long_term_service_id', $LongTermService->id)->delete();
            foreach ($request->name as $k => $name) {
                if($name){
                    $LongTermServiceT                       = new LongTermServiceTranslation();
                    $LongTermServiceT->name                 = $name;
                    $LongTermServiceT->language_id          = $language_id[$k];
                    $LongTermServiceT->long_term_service_id = $LongTermService->id;
                    $LongTermServiceT->save();
                }
            }
            $longTermProduct =   LongTermServiceProducts::where('long_term_service_id',$LongTermService->id)->first() ?? new LongTermServiceProducts();
            $longTermProduct->long_term_service_id = $LongTermService->id ;
            $longTermProduct->product_id           = $request->service_product_id ;
            $longTermProduct->product_variant      = $request->service_product_variant_id ;
            $longTermProduct->quantity             = $request->product_quantity ;
            $longTermProduct->save();
            DB::commit();
            return $this->successResponse($LongTermService, __('Long Term Service Added Successfully.'));
          
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }

    }
    public function save(Request $request)
    {
        # code...
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$domain = '', $id)
    {
      
        try {
        
            $LongTermService = LongTermService::with('translations','product')->where(['id' => $id])->firstOrFail();
            return $this->successResponse($LongTermService, '');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$domain = '', $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$domain = '', $id)
    {
        try {
            LongTermService::where('id',$id)->delete();
            return response()->json(array('success' => true,'message'=>__('Deleted successfully.')));
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }
}
