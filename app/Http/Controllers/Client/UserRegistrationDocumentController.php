<?php

namespace App\Http\Controllers\Client;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\UserRegistrationDocuments;
use App\Http\Controllers\Client\BaseController;
use App\Models\UserRegistrationDocumentTranslation;


class UserRegistrationDocumentController extends BaseController{
    use ApiResponser;
    public function store(Request $request){

        try {

            $this->validate($request, [
              'name.0' => 'required|string|max:60',
              'file_type' => 'required',
            ],['name.0' => 'The default language name field is required.']);
            
            DB::beginTransaction();
            $user_registration_document = new UserRegistrationDocuments();
            $user_registration_document->file_type = $request->file_type;
            $user_registration_document->is_required = $request->is_required;
            $user_registration_document->save();
            $language_id = $request->language_id;
            foreach ($request->name as $k => $name) {
                if($name){
                    $data= [
                            'name' => $name,
                            'slug' => Str::slug($name, '-'),
                            'language_id' =>$language_id[$k],
                            'user_registration_document_id' =>$user_registration_document->id
                        ];
                        $Loction = UserRegistrationDocumentTranslation::updateOrCreate(
                        ['slug'=>Str::slug($name, '-'),'language_id' =>$language_id[$k],'user_registration_document_id' =>$user_registration_document->id ],
                            $data
                        );
                    
                    // $UserRegistrationDocumentTranslation = new UserRegistrationDocumentTranslation();
                    // $UserRegistrationDocumentTranslation->name = $name;
                    // $UserRegistrationDocumentTranslation->slug = Str::slug($name, '-');
                    // $UserRegistrationDocumentTranslation->language_id = $language_id[$k];
                    // $UserRegistrationDocumentTranslation->user_registration_document_id = $user_registration_document->id;
                    // $UserRegistrationDocumentTranslation->save();
                }
            }
           
            DB::commit();
            return $this->successResponse($user_registration_document, 'User Registration Document Added Successfully.');
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
            $language_id = $request->language_id;
            $user_registration_document = UserRegistrationDocuments::with(['translations'])->where(['id' => $request->user_registration_document_id])->firstOrFail();
            return $this->successResponse($user_registration_document, '');
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
    public function update(Request $request, UserRegistrationDocuments $userRegistrationDocument){
         try {

            $this->validate($request, [
              'name.0' => 'required|string|max:60',
              'file_type' => 'required',
            ],['name.0' => 'The default language name field is required.']);
            DB::beginTransaction();
            $user_registration_document_id = $request->user_registration_document_id;
            $user_registration_document = UserRegistrationDocuments::where('id', $user_registration_document_id)->first();
            $user_registration_document->file_type = $request->file_type;
            $user_registration_document->is_required = $request->is_required;
            $user_registration_document->save();
            $language_id = $request->language_id;
            UserRegistrationDocumentTranslation::where('user_registration_document_id', $user_registration_document_id)->delete();
            foreach ($request->name as $k => $name) {
                if($name){
                    $data= [
                        'name' => $name,
                        'slug' => Str::slug($name, '-'),
                        'language_id' =>$language_id[$k],
                        'user_registration_document_id' =>$user_registration_document->id
                    ];
                    $Loction = UserRegistrationDocumentTranslation::updateOrCreate(
                    ['slug'=>Str::slug($name, '-'),'language_id' =>$language_id[$k],'user_registration_document_id' =>$user_registration_document->id ],
                        $data
                    );
                
                    // $UserRegistrationDocumentTranslation = new UserRegistrationDocumentTranslation();
                    // $UserRegistrationDocumentTranslation->name = $name;
                    // $UserRegistrationDocumentTranslation->slug = Str::slug($name, '-');
                    // $UserRegistrationDocumentTranslation->language_id = $language_id[$k];
                    // $UserRegistrationDocumentTranslation->user_registration_document_id = $user_registration_document->id;
                    // $UserRegistrationDocumentTranslation->save();
                }
            }
            DB::commit();
            return $this->successResponse($user_registration_document, 'User Registration Document Updated Successfully.');
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
            UserRegistrationDocuments::where('id', $request->user_registration_document_id)->delete();
            UserRegistrationDocumentTranslation::where('user_registration_document_id', $request->user_registration_document_id)->delete();
            return $this->successResponse([], 'User Registration Document Deleted Successfully.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }
}
