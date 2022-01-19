<?php

namespace App\Http\Controllers\Client;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\EstimateProduct;
use App\Http\Controllers\Client\BaseController;
use App\Models\EstimateProductTranslation;
use App\Models\{Client,ClientLanguage};
use Illuminate\Support\Facades\Storage;
use Auth;

class EstimationController extends BaseController{
    use ApiResponser;

    public function __construct()
    {
        $code = Client::orderBy('id','asc')->value('code');
        $this->folderName = '/'.$code.'/estimate_products';
    }

    
    public function index(Request $request){
        $estimate_products = EstimateProduct::with('primary')->get();

        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
        ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
        ->where('client_languages.client_code', Auth::user()->code)
        ->where('client_languages.is_active', 1)
        ->orderBy('client_languages.is_primary', 'desc')->get();
       
        return view('backend/setting/estimate_product')->with(['estimate_products' => $estimate_products,'client_languages' => $client_languages]);
  
    }


    public function store(Request $request){
        try {
            $this->validate($request, [
              'name.0' => 'required|string|max:255',
              'icon' => 'image'
             ],['name.0' => 'The default language name field is required.']);
            DB::beginTransaction();

            $tag = new EstimateProduct();

            if ($request->hasFile('icon')) {    /* upload icon file */
                $file = $request->file('icon');
                $tag->icon = Storage::disk('s3')->put($this->folderName, $file, 'public');
            }

            $tag->save();
            $language_id = $request->language_id;
            foreach ($request->name as $k => $name) {
                if($name){
                    $TagTranslation = new EstimateProductTranslation();
                    $TagTranslation->name = $name;
                    $TagTranslation->slug = Str::slug($name, '-');
                    $TagTranslation->language_id = $language_id[$k];
                    $TagTranslation->estimate_product_id = $tag->id;
                    $TagTranslation->save();
                }
            }
            DB::commit();
            return $this->successResponse($tag, 'Product Added Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request){
        try {
            $tag = EstimateProduct::with(['translations'])->where(['id' => $request->estimate_product_id])->first();
            return $this->successResponse($tag, '');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EstimateProduct $tag){
         try {
            $this->validate($request, [
              'name.0' => 'required|string|max:255',
              'icon' => 'image'
            ],['name.0' => 'The default language name field is required.']);
            DB::beginTransaction();
            $estimate_product_id = $request->estimate_product_id;
            $tag = EstimateProduct::where('id', $estimate_product_id)->first();

            if ($request->hasFile('icon')) {    /* upload icon file */
                $file = $request->file('icon');
                $tag->icon = Storage::disk('s3')->put($this->folderName, $file, 'public');
            }

            $tag->save();
            $language_id = $request->language_id;
            EstimateProductTranslation::where('estimate_product_id', $estimate_product_id)->delete();
            foreach ($request->name as $k => $name) {
                if($name){
                    $TagTranslation = new EstimateProductTranslation();
                    $TagTranslation->name = $name;
                    $TagTranslation->slug = Str::slug($name, '-');
                    $TagTranslation->language_id = $language_id[$k];
                    $TagTranslation->estimate_product_id = $tag->id;
                    $TagTranslation->save();
                }
            }
            DB::commit();
            return $this->successResponse($tag, 'Product Updated Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
        try {
            EstimateProduct::where('id', $request->estimate_product_id)->delete();
            EstimateProductTranslation::where('estimate_product_id', $request->estimate_product_id)->delete();
            return $this->successResponse([], 'Product Deleted Successfully.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }
}
