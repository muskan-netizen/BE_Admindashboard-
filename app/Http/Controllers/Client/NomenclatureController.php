<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Nomenclature;
use App\Models\NomenclatureTranslation;
use App\Http\Controllers\Client\BaseController;

class NomenclatureController extends BaseController{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $names = $request->names;
        $loyalty_cards_language_ids = $request->loyalty_cards_language_ids;
        $loyalty_cards_names = $request->loyalty_cards_names;
        $takeaway_names = $request->takeaway_names;
        if(count($names) > 0){
            $names_value_exists = [];
            foreach ($names as $name) {
                if($name){
                    $names_value_exists[]=$name;
                }
            }
            if(count($names_value_exists) > 0){
                $this->validate($request, [
                  'names.0' => 'required|string',
                ]);
                NomenclatureTranslation::truncate();
                $language_ids = $request->language_ids;
                foreach ($names as $key => $name) {
                    if($name){
                        $nomenclature = NomenClature::where('label', 'vendors')->first();
                        if($nomenclature){
                            $nomenclature->update(['label' => "vendors"]);
                        }else{
                            $nomenclature = NomenClature::create(['label' => "vendors"]);
                        }
                        $nomenclature_translation =  new NomenclatureTranslation();
                        $nomenclature_translation->name = $name;
                        $nomenclature_translation->language_id = $language_ids[$key];
                        $nomenclature_translation->nomenclature_id = $nomenclature->id;
                        $nomenclature_translation->save();
                    }
                }
            }else{
                NomenclatureTranslation::where('nomenclature_id', 1)->delete();
            }
        }
        if(count($loyalty_cards_names) > 0){
            $value_exists = [];
            foreach ($loyalty_cards_names as $loyalty_cards_name) {
                if($loyalty_cards_name){
                    $value_exists[]=$loyalty_cards_name;
                }
            }
            if(count($value_exists) > 0){
                $this->validate($request, [
                  'loyalty_cards_names.0' => 'required|string',
                ]);
                foreach ($loyalty_cards_names as $ke => $loyalty_cards_name) {
                    if($loyalty_cards_name){
                        $nomenclature = NomenClature::where('label', 'Loyalty Cards')->first();
                        if($nomenclature){
                            $nomenclature->update(['label' => "Loyalty Cards"]);
                        }else{
                            $nomenclature = NomenClature::create(['label' => "Loyalty Cards"]);
                        }
                        $nomenclature_translation =  new NomenclatureTranslation();
                        $nomenclature_translation->name = $loyalty_cards_name;
                        $nomenclature_translation->language_id = $loyalty_cards_language_ids[$ke];
                        $nomenclature_translation->nomenclature_id = $nomenclature->id;
                        $nomenclature_translation->save();
                    }
                }
            }else{
                NomenclatureTranslation::where('nomenclature_id', 2)->delete();
            }
        }
        if(count($takeaway_names) > 0){
            $names_value_exists = [];
            foreach ($takeaway_names as $name) {
                if($name){
                    $names_value_exists[]=$name;
                }
            }
            if(count($names_value_exists) > 0){
                $this->validate($request, [
                  'takeaway_names.0' => 'required|string',
                ]);
                $takeaway_language_ids = $request->takeaway_language_ids;
                foreach ($takeaway_names as $takeaway_key => $takeaway_name) {
                    if($takeaway_name){
                        $nomenclature = NomenClature::where('label', 'Takeaway')->first();
                        if(empty($nomenclature)){
                            $nomenclature = NomenClature::create(['label' => "Takeaway"]);
                        }
                        $nomenclature_translation =  new NomenclatureTranslation();
                        $nomenclature_translation->name = $takeaway_name;
                        $nomenclature_translation->language_id = $takeaway_language_ids[$takeaway_key];
                        $nomenclature_translation->nomenclature_id = $nomenclature->id;
                        $nomenclature_translation->save();
                    }
                }
            }else{
                NomenclatureTranslation::where('nomenclature_id', 3)->delete();
            }
        }
        return redirect()->route('configure.customize')->with('success', 'Nomenclature Saved Successfully!');
    }
}
