<?php

namespace App\Http\Controllers\Client;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use Illuminate\Support\Str;
use App\Models\{ClientPreference, VendorSection, Vendor, VendorSectionTranslation};

class VendorSectionController extends BaseController {

    public function store(Request $request){

        try {

            $this->validate($request, [
              'name.0' => 'required|string|max:60',
              'file_type' => 'required',
            ],['name.0' => 'The default language name field is required.']);
            if($request->file_type=="Selecter"){
                $this->validate($request, [
                    'option_name.0.0' => 'required|string|max:60',
                  ],['option_name.0.0' => 'The default Option name field is required.']);
            }
            DB::beginTransaction();
            $vendor_registration_document = new VendorSection();
            $vendor_registration_document->slug = Str::slug($request->name[0], '-');
            $vendor_registration_document->vendor_id = $request->vendor_id;
            $vendor_registration_document->save();
            $language_id = $request->language_id;
            foreach ($request->name as $k => $name) {
                if($name){
                    $VendorRegistrationDocumentTranslation = new VendorRegistrationDocumentTranslation();
                    $VendorRegistrationDocumentTranslation->name = $name;
                    $VendorRegistrationDocumentTranslation->slug = Str::slug($name, '-');
                    $VendorRegistrationDocumentTranslation->language_id = $language_id[$k];
                    $VendorRegistrationDocumentTranslation->vendor_registration_document_id = $vendor_registration_document->id;
                    $VendorRegistrationDocumentTranslation->save();
                }
            }
            if($request->has('option_name')){
                foreach($request->option_name as $key =>$value){

                    if(isset($value[0]) && !empty($value[0])){
                        $option  = new VendorRegistrationSelectOption();
                        $option->vendor_registration_documents_id = $vendor_registration_document->id;
                        $option->save();

                        foreach($request->language_id as $lang_key =>$lang_value){
                            if(isset($value[$lang_key]) && !empty($value[$lang_key])){
                                $optionTrabslation  = new VendorRegistrationSelectOptionTranslations();
                                $optionTrabslation->vendor_registration_select_option_id =$option->id ;
                                $optionTrabslation->language_id = $lang_value;
                                $optionTrabslation->name =$value[$lang_key] ;
                                $optionTrabslation->save();
                            }
                        }
                    }


                }
            }

            DB::commit();
            return $this->successResponse($vendor_registration_document, 'Vendor Registration Document Added Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }

    public function index(Request $request)
    {
    }
}