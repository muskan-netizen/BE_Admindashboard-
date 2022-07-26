<?php

namespace App\Http\Controllers\Client;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Facilty,FaciltyTranslation};
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Support\Str;
use DB;

use App\Http\Traits\ApiResponser;
class FaciltyController extends BaseController
{  
    use ApiResponser;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request){
        try {

            $this->validate($request, [
              'name.0' => 'required|string|max:60',
            ],['name.0' => 'The default language name field is required.']);
           
            DB::beginTransaction();
            $facilty = new Facilty();
            if ($request->hasFile('facilty_image')) {   
                 /* upload logo file */
                $file = $request->file('facilty_image');
                $facilty->image = Storage::disk('s3')->put('/facilty/icon', $file, 'public');
            }else{
                $facilty->image = 'default/default_image.png';
            }
            $facilty->save();
            $language_id = $request->language_id;
            foreach ($request->name as $k => $name) {
                if($name){
                    $FaciltyTranslation = new FaciltyTranslation();
                    $FaciltyTranslation->name = $name;
                    $FaciltyTranslation->slug = Str::slug($name, '-');
                    $FaciltyTranslation->language_id = $language_id[$k];
                    $FaciltyTranslation->facilties_id = $facilty->id;
                    $FaciltyTranslation->save();
                }
            }

            DB::commit();
            return $this->successResponse($facilty, 'facilty Added Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $domain = '', $id)
    {
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $rules = array(
            'slug' => 'required|string|max:30|unique:categories,slug,' . $id,
            'name.0' => 'required|string|max:60',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $category = Category::where('id', $id)->first();
        $save = $this->save($request, $category, 'true');
        if ($save > 0) {
            if ($request->has('language_id')) {
                foreach ($request->language_id as $key => $value) {
                    $trans = Category_translation::where('category_id', $save)->where('language_id', $value)->first();
                    if (!$trans) {
                        $trans = new Category_translation();
                        $trans->category_id = $save;
                        $trans->language_id = $value;
                    }
                    $trans->name = $request->name[$key];
                    $trans->meta_title = $request->meta_title[$key];
                    $trans->meta_description = $request->meta_description[$key];
                    $trans->meta_keywords = $request->meta_keywords[$key];
                    $trans->save();
                }
            }
            $hs = new CategoryHistory();
            $hs->action = 'Update';
            $hs->category_id = $save;
            $hs->updater_role = 'Admin';
            $hs->update_id = Auth::user()->id;
            $hs->client_code = Auth::user()->code;
            $hs->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Category created Successfully!',
                'data' => $save
            ]);
        }
        if ($save == 'bad parent') {
            return response()->json([
                'status' => 'error1',

            ]);
        }
        if ($save == 'bad type') {
            return response()->json([
                'status' => 'error2',

            ]);
        }
        // return response()->json('error', 'Cannot create a sub-category of product type of category!!!');
    }

    /**
     * save and update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, Category $cate, $update = 'false')
    {
        try {
            $cate->slug = $request->slug;
            if ($request->type_id == 1 && $cate->childs->first() != null) {
                return 'bad type';
            }
            $cate->type_id = $request->type_id;
            $cate->display_mode = $request->display_mode;
            $cate->warning_page_id = $request->warning_page_id;
            $cate->template_type_id = $request->template_type_id;
            $cate->warning_page_design = $request->has('warning_page_design') ? $request->warning_page_design : 0;
            $cate->is_visible = ($request->has('is_visible') && $request->is_visible == 'on') ? 1 : 0;
            $cate->show_wishlist = ($request->has('show_wishlist') && $request->show_wishlist == 'on') ? 1 : 0;
            $cate->can_add_products = ($request->has('can_add_products') && $request->can_add_products == 'on' && ($request->type_id == 1 || $request->type_id == 3)) ? 1 : 0;
            if ($request->has('parent_cate') && $request->parent_cate > 0) {
                $cat = Category::find($request->parent_cate);
                if ($request->parent_cate != 1) {
                    if (($update == 'false' || $update == 'true') && $cat->type->title == 'Product') {
                        return 'bad parent';
                    }
                }
                $cate->parent_id = $request->parent_cate;
            } else {
                $cate->parent_id = 1;
            }
            if ($update == 'false') {
                if ($request->has('vendor_id')) {
                    $cate->is_core = 0;
                    $cate->vendor_id = $request->vendor_id;
                } else {
                    $cate->is_core = 1;
                }
                $cate->status = 1;
                $cate->position = 1;
                $cate->client_code = (!empty(Auth::user()->code)) ? Auth::user()->code : '';
            }
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $cate->icon = Storage::disk('s3')->put($this->folderName, $file, 'public');
            }
            if ($request->hasFile('icon_two')) {
                $file = $request->file('icon_two');
                $cate->icon_two = Storage::disk('s3')->put($this->folderName, $file, 'public');
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $cate->image = Storage::disk('s3')->put('/category/image', $file, 'public');
            }
            $cate->save();
            $tagDelete = CategoryTag::where('category_id', $cate->id)->delete();
            if ($request->has('tags') && !empty($request->tags)) {
                $tagArray = array();
                $tags = explode(',', $request->tags);
                foreach ($tags as $k => $v) {
                    $tagArray[] = [
                        'category_id' => $cate->id,
                        'tag' => $v
                    ];
                }
                CategoryTag::insert($tagArray);
            }
            return $cate->id;
        } catch (Exception $e) {
            pr($e->getMessage());
            die;
        }
    }

    /**
     * Update the order of categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request)
    {
        $data = json_decode($request->orderDta);
        $arr = $this->buildArray($data);
        if ($arr > 0) {
            return redirect('client/category')->with('success', 'Category order updated successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        $user = Auth::user();
        $parent = Category::where('id', $id)->first();
        $array_of_ids = $this->getChildren($parent);
        array_push($array_of_ids, $id);
       

        $dynamic = time().substr(md5(mt_rand()), 0, 7);
     
        $tot_var  = Product::whereIn('category_id', $array_of_ids)->select('id','sku')->get();
        foreach($tot_var as $varr)
        {   
            $dynamic = time().substr(md5(mt_rand()), 0, 7);
            Product::where('id', $varr->id)->update(['sku' => $varr->sku.$dynamic]);
            ProductVariant::where('product_id', $varr->product_id)->update(['sku' => $varr->sku.$dynamic]);
        }

        foreach($array_of_ids as $varr)
        {   
            $dynamic = time().substr(md5(mt_rand()), 0, 7);
            Category::where('id', $varr)->update(['slug' => $dynamic]);
            
        }



        Product::whereIn('category_id', $array_of_ids)->delete();
        Category::destroy($array_of_ids);
        
        // category kyc document delete 
        CategoryKycDocumentMapping::where('category_id',$id)->delete();
        // CategoryKycDocuments::whereIn(['id', $category_kyc_document_ids])->delete();
        // CategoryKycDocumentTranslation::whereIn(['category_kyc_document_id',  $category_kyc_document_ids])->delete();
        // CategoryKycDocumentMapping::whereIn('category_kyc_document_id',$category_kyc_document_ids)->delete();
        //end kyc document

        CategoryHistory::insert([
            'category_id' => $id,
            'action' => 'deleted',
            'update_id' => $user->id,
            'updater_role' => 'Admin',
            'client_code' => $user->code,
        ]);

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    public function show(Request $request){
       
       try {
            $language_id = $request->language_id;
            $facilty = Facilty::with(['translations'])->where(['id' => $request->facilty_id])->firstOrFail();
           
            return $this->successResponse($facilty, '');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }

}
