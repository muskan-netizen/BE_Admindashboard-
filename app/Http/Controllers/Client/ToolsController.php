<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Vendor,Product,Client,AddonSet,Category,ProductVariant,CartProduct,UserWishlist,TaxCategory,VendorCategory,VendorSlot,VendorSlotDate,VendorDineinCategory,VendorDineinTable};  
use Auth,Carbon,DB,Storage,Session;

class ToolsController extends BaseController
{
    private $folderName = 'prods';
    private $vendorObj, $productObj, $clientObj, $addOnSetObj, $categoryObj,$vendorCategoryObj,$vendorSlotObj, $vendorSlotDateObj, $vendorDineinCategoryObj,$vendorDineinTableObj;
    public function __construct(Vendor $vendor, Product $product, Client $client,AddonSet $addonSet, Category $category, VendorCategory $vendorCategory, VendorSlot $vendorSlot, VendorSlotDate $vendorSlotDate, VendorDineinCategory $vendorDineinCategory, VendorDineinTable $vendorDineinTable)
    {
        $this->vendorObj = $vendor;
        $this->productObj = $product;
        $this->clientObj = $client;
        $this->addOnSetObj = $addonSet;
        $this->categoryObj = $category;
        $this->vendorCategoryObj = $vendorCategory;
        $this->vendorSlotObj = $vendorSlot;
        $this->vendorSlotDateObj = $vendorSlotDate;
        $this->vendorDineinCategoryObj = $vendorDineinCategory;
        $this->vendorDineinTableObj = $vendorDineinTable;
    }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::where('status',1)->select('id','name','slug');
        if (Auth::user()->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            }); 
        }
        $vendors = $vendors->get(); 
        $taxCategory = TaxCategory::all();

        $p_categories = Category::with(['parent','translation_one'])
        ->whereIn('type_id',['1','3','7','8','9'])
        ->where('id', '>', '1')
        ->where('deleted_at', NULL)
        ->where('status', 1)
        ->orderBy('parent_id', 'asc')
        ->orderBy('position', 'asc')
        ->orderBy('id', 'asc')
        ->get();

        return view('backend.tools.index')->with(['vendors'=>$vendors,'taxCategory'=>$taxCategory,'categories'=>$p_categories]);
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
        try{
            $from_vendor = $this->vendorObj->getById($request->copy_from);
            $from_products = $this->productObj->getByVendorId($request->copy_from);
            $client = $this->clientObj->getClient();

            if(count($request->copy_to) > 0)
            {
                foreach($request->copy_to as $copy_to)
                {
                    $update_vendor = $this->updateVendorData($from_vendor,$copy_to);
                    if(isset($client->custom_domain) && !empty($client->custom_domain) && $client->custom_domain != $client->sub_domain)
                        $sku_url =  ($client->custom_domain);
                    else
                        $sku_url =  ($client->sub_domain.env('SUBMAINDOMAIN'));

                    $sku_url = array_reverse(explode('.',$sku_url));
                    $sku_url = implode(".",$sku_url);

                    $to_vendor = $this->vendorObj->getById($copy_to);
                    $vendor_name = $to_vendor->name;
                    $vendor_name = preg_replace('/\s+/', '', $vendor_name);
                    if(isset($vendor_name) && !empty($vendor_name))
                    $sku_url = $sku_url.".".$vendor_name;

                    foreach($from_products as $from_product)
                    {
                        $product_slug = createSlug(!is_null($from_product->title) ? $from_product->title : $from_product->url_slug);
                        $product_sku = $sku_url.'.'.$product_slug;
                        $check_product = $this->productObj->getProductBySku($product_sku);
                        if($check_product){
                            // dd($check_product);
                            $delete_product = $this->deleteProduct($check_product->id);
                        }
                        $add_product = $this->addProduct($from_product,$copy_to,$request->copy_from,$product_sku);
                    }
                }
                return redirect()->back()->with('success', 'Catalogs copied successfully!');
            }
            return redirect()->back()->with('error', 'Please select atleast one store');
        }catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function addProduct($from_product,$copy_to,$copy_from,$product_sku)
    {
        $product = $from_product;
        $product = $product->replicate();
        $product->vendor_id = $copy_to; 
        $product->sku = $product_sku;
        $product->save();
        foreach($from_product->addOn as $addOn)
        {
            $addOn_id = $addOn->addon_id;
            $check_addon = $this->addOnSetObj->checkAddon($addOn->addOnName,$copy_to);
            if(is_null($check_addon)){
                $add_addOn = $this->addCompleteAddOn($addOn,$copy_to);
                $addOn_id = $add_addOn->id;
            }
            $new_addOn = $addOn;
            $new_addOn = $new_addOn->replicate();
            $new_addOn->product_id = $product->id;
            $new_addOn->addon_id = $addOn_id;
            $new_addOn->save();
        }
        if(isset($from_product->category) && !is_null($from_product->category)) 
        {
            $category_id = $from_product->category->category_id;
            if(!is_null($from_product->category->categoryDetail->vendor_id)){
                $check_category = $this->categoryObj->checkCategory($from_product->category->categoryDetail,$copy_to);
                if(is_null($check_category))
                {
                    $add_category = $this->addCompleteCategory($from_product->category->categoryDetail,$copy_to);
                    $category_id = $add_category->id;
                }
            }
            $new_category = $from_product->category;
            $new_category = $new_category->replicate();
            $new_category->product_id = $product->id;
            $new_category->category_id = $category_id;
            $new_category->save();
        }
        foreach($from_product->celebrities as $celebrity)
        {
            $new_celebrity = $celebrity;
            $new_celebrity = $new_celebrity->replicate();
            $new_celebrity->product_id = $product->id;
            $new_celebrity->save();
        }
        foreach($from_product->media as $media)
        {
            $new_media = $media;
            $new_media = $new_media->replicate();
            $new_media->product_id = $product->id;
            $new_media->save();
        }
        
        foreach($from_product->all_tags as $tag)
        {
            $new_tag = $tag;
            $new_tag = $new_tag->replicate();
            $new_tag->product_id = $product->id;
            $new_tag->save();
        }
        foreach($from_product->translation as $translation)
        {
            $new_translation = $translation;
            $new_translation = $new_translation->replicate();
            $new_translation->product_id = $product->id;
            $new_translation->save();
        }
        foreach($from_product->variant as $key=>$variant)
        {
            $new_variant = $variant;
            $new_variant = $new_variant->replicate();
            $new_variant->product_id = $product->id;
            $new_variant->sku = $product_sku.'.'.$key;
            $new_variant->barcode = $product->id.'_'.$key;
            $new_variant->save();
            foreach($variant->media as $v_media)
            {
                $new_v_media = $v_media;
                $new_v_media = $new_v_media->replicate();
                $new_v_media->product_variant_id = $new_variant->id;
                $new_v_media->save();
            }
            foreach($variant->vset as $vset) 
            {
                $new_vset = $vset;
                $new_vset = $new_vset->replicate();
                $new_vset->product_id = $product->id;
                $new_vset->product_variant_id = $new_variant->id;
                $new_vset->save();
            }
        }
    }
    public function deleteProduct($id)
    {
        try{
            DB::beginTransaction();
            $product = Product::find($id);
            $dynamic = time();
            $up = Product::where('id', $id)->update(['sku' => $product->sku.$dynamic ,'url_slug' => $product->url_slug.$dynamic]);
            $tot_var  = ProductVariant::where('product_id', $id)->get();
            foreach($tot_var as $varr)
            {
                $dynamic = time().substr(md5(mt_rand()), 0, 7);
                ProductVariant::where('id', $varr->id)->update(['sku' => $product->sku.$dynamic]);
            }
            ProductVariant::where('product_id', $id)->delete();
            CartProduct::where('product_id', $id)->delete();
            UserWishlist::where('product_id', $id)->delete();
            Product::where('id', $id)->delete();
            DB::commit();
        }catch(\Exception $ex){
            DB::rollback();
        }
    }
    public function addCompleteAddOn($addOn,$copy_to)
    {
        $addOnSet = $addOn->addOnName;
        $new_addOnSet = $addOnSet;
        $new_addOnSet = $new_addOnSet->replicate();
        $new_addOnSet->vendor_id = $copy_to;
        $new_addOnSet->save();
        foreach($addOnSet->translation_many as $translation)
        {
            $new_translation = $translation;
            $new_translation = $new_translation->replicate();
            $new_translation->addon_id = $new_addOnSet->id;
            $new_translation->save();
        }
        foreach($addOnSet->option as $option)
        {
            $new_option = $option;
            $new_option = $new_option->replicate();
            $new_option->addon_id = $new_addOnSet->id;
            $new_option->save();
            foreach($option->translation_many as $o_translation)
            {
                $new_o_translation = $o_translation;
                $new_o_translation = $new_o_translation->replicate();
                $new_o_translation->addon_opt_id = $new_option->id;
                $new_o_translation->save();
            }
        }
        return $new_addOnSet;
    }
    public function addCompleteCategory($category, $copy_to)
    {
        $new_category = $category;
        $new_category = $new_category->replicate();
        $new_category->vendor_id = $copy_to;
        $new_category->slug = $category->slug.'_'.$copy_to;
        $new_category->save();
        foreach($category->translationSet as $translation)
        {
            $new_translation = $translation;
            $new_translation = $new_translation->replicate();
            $new_translation->category_id = $new_category->id;
            $new_translation->save();
        }
        foreach($category->tags as $tag)
        {
            $new_tag = $tag;
            $new_tag = $new_tag->replicate();
            $new_tag->category_id = $new_category->id;
            $new_tag->save();
        }
        foreach($category->brands as $brand)
        {
            $new_brand = $brand;
            $new_brand = $new_brand->replicate();
            $new_brand->category_id = $new_category->id;
            $new_brand->save();
        }
        return $new_category;
    }
    public function updateVendorData($from_vendor, $to_vendor_id)
    {
        $update = Vendor::where('id',$to_vendor_id)->update([
            'order_min_amount' => $from_vendor->order_min_amount,
            'order_pre_time' => $from_vendor->order_pre_time,
            'auto_reject_time' => $from_vendor->auto_reject_time,
            'commission_percent' => $from_vendor->commission_percent,
            'commission_fixed_per_order' => $from_vendor->commission_fixed_per_order,
            'commission_monthly' => $from_vendor->commission_monthly,
            'dine_in' => $from_vendor->dine_in,
            'takeaway' => $from_vendor->takeaway,
            'delivery' => $from_vendor->delivery,
            'add_category' => $from_vendor->add_category,
            'setting' => $from_vendor->setting,
            'is_show_vendor_details' => $from_vendor->is_show_vendor_details,
            'show_slot' => $from_vendor->show_slot,
            'vendor_templete_id' => $from_vendor->vendor_templete_id,
            'auto_accept_order' => $from_vendor->auto_accept_order,
            'service_fee_percent' => $from_vendor->service_fee_percent,
            'order_amount_for_delivery_fee' => $from_vendor->order_amount_for_delivery_fee,
            'delivery_fee_minimum' => $from_vendor->delivery_fee_minimum,
            'delivery_fee_maximum' => $from_vendor->delivery_fee_maximum,
            'slot_minutes' => $from_vendor->slot_minutes,
            'closed_store_order_scheduled' => $from_vendor->closed_store_order_scheduled,
        ]);
        foreach($from_vendor->getAllCategory as $v_category)
        {
            $add_up_cat = $this->vendorCategoryObj->addVendorCategory($to_vendor_id,$v_category->category_id);  
        }
        ///Replicate Vendor Slot/SlotDate
        $delete_slot = $this->vendorSlotObj->deleteVendorSlots($to_vendor_id);
        $delete_slot_date = $this->vendorSlotDateObj->deleteVendorSlotDates($to_vendor_id);
        foreach($from_vendor->slots as $slot)
        {
            $new_slot = $slot;
            $new_slot = $new_slot->replicate();
            $new_slot->vendor_id = $to_vendor_id;
            $new_slot->save();
        }
        foreach($from_vendor->slotDates as $slotDate)
        {
            $new_slotDate = $slotDate;
            $new_slotDate = $new_slotDate->replicate();
            $new_slotDate->vendor_id = $to_vendor_id;
            $new_slotDate->save();
        }
        //Replication Vendor Dine In
        $add_dinein = $this->updateVendorDinein($from_vendor,$to_vendor_id);

    }
    public function updateVendorDinein($from_vendor, $to_vendor_id)
    {
        $delete_dinein_table = $this->vendorDineinTableObj->deleteByVendor($to_vendor_id);
        $delete_dinein_cate = $this->vendorDineinCategoryObj->deleteByVendor($to_vendor_id);
        foreach($from_vendor->dineinCategories as $category)
        {
            $new_category = $category;
            $new_category = $new_category->replicate();
            $new_category->vendor_id = $to_vendor_id;
            $new_category->save();
            foreach($category->translations as $c_trans)
            {
                $new_c_trans = $c_trans;
                $new_c_trans = $new_c_trans->replicate();
                $new_c_trans->category_id = $new_category->id;
                $new_c_trans->save();
            }
            foreach($category->dineinTable as $table)
            {
                $new_table = $table;
                $new_table = $new_table->replicate();
                $new_table->vendor_id = $to_vendor_id;
                $new_table->vendor_dinein_category_id = $new_category->id;
                $new_table->save();
                foreach($table->translations as $t_trans)
                {
                    $new_t_trans = $t_trans;
                    $new_t_trans = $new_t_trans->replicate();
                    $new_t_trans->vendor_dinein_table_id = $new_table->id;
                    $new_t_trans->save();
                }
            }
        }
    }
    public function taxCopy(Request $request)
    {
        try{
            foreach($request->product_category as $category_id)
            {
                $products = $this->productObj->getProductByCategory($category_id);
                foreach($products as $product)
                {
                    $product->tax_category_id = $request->tax_category;
                    $product->save();
                    foreach($product->variant as $variant)
                    {
                        $variant->tax_category_id = $request->tax_category;
                        $variant->save();
                    }
                }
            }
            return redirect()->back()->with('success', 'Tax copied successfully!');

        }catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
    public function uploadImage(Request $request)
    {
        $data = [];
        if ($request->has('file')) {
            $imageId = '';
            $file = $request->file('file');
            $data['image_path'] = Storage::disk('s3')->put($this->folderName, $file, 'public');
            $data['image_url'] = \Config::get('app.IMG_URL1').'30/30'.\Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($data['image_path']).'@webp';
            $data['image_id'] = uniqid();
            return response()->json(['data' => $data]);
        } else {
            return response()->json(['data' => $data,'error' => 'No file']);
        }
    }
}
