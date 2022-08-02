<?php

namespace App\Http\Controllers\Client;

use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{ClientPreference, VendorSection, VendorSectionHeadingTranslation, Vendor, VendorSectionTranslation};

class VendorSectionController extends BaseController {
    use ApiResponser;
    public function store(Request $request){
       
         try {

            $this->validate($request, [
              'heading' => 'required|string|max:60',
            ],['heading' => 'The heading field is required.']);
            
            DB::beginTransaction();
            $vendor_section = new VendorSection();
            $vendor_section->slug = Str::slug($request->heading[0], '-');
            $vendor_section->vendor_id = $request->vendor_id;
            $vendor_section->save();

            $vendor_section_heading_translation             = new VendorSectionHeadingTranslation();
            $vendor_section_heading_translation->heading    = $request->heading;
            $vendor_section_heading_translation->language_id      = $request->language_id;
            $vendor_section_heading_translation->vendor_section_id = $vendor_section->id;
            $vendor_section_heading_translation->save();

            
           
            foreach($request->title as $key=>$value){
                $vendor_section_translation                    = new VendorSectionTranslation();
                $vendor_section_translation->title             = $value;
                $vendor_section_translation->description       = $request->description[$key] ?? '';
                $vendor_section_translation->language_id       = $request->language_id;
                $vendor_section_translation->vendor_section_id = $vendor_section->id;
                $vendor_section_translation->save();
            }
            $Vendor_section = VendorSection::with('headingTranslation','SectionTranslation')->where('id',$vendor_section->id)->first();
            //pr( $Vendor_section);
            
            DB::commit();
            return $this->successResponse($Vendor_section, 'Vendor Section Added Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }
    public function show(Request $request, $domain = '', $id){
        $language_id = $request->language_id;
        $VendorSection =  VendorSection::with(array('headingTranslation' => function($query) use($language_id) {
            $query->where('language_id', $language_id);
        },'SectionTranslation' => function($query) use($language_id) {
            $query->where('language_id', $language_id);
        }
        ))->where('id', $id)->first();
        //pr($page->toArray());
        return $this->successResponse($VendorSection);
    }

    public function destroy(Request $request, $domain = "" ,$section_id)
    {
        VendorSection::where('id',$section_id)->delete();
        VendorSectionHeadingTranslation::where('vendor_section_id',$section_id)->delete();
        VendorSectionTranslation::where('vendor_section_id',$section_id)->delete();
        return redirect()->back()->with('success', __('Section Deleted successfully!'));
    }
}