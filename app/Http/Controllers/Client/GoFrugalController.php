<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Http\Traits\GoFrugal;
use App\Models\Category;
use App\Models\Category_translation;
use App\Models\CategoryHistory;
use App\Models\Country;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoFrugalController extends BaseController
{
    use GoFrugal;

    public function index(Request $request)
    {
        $response = $this->getProducts();
        if (!$response['status']) {
            return redirect()->back()->withErrors(['error' => $response['message']]);
        }
        $products = $response['data'];
        foreach($products as $product){
            pr($product);
            $item = [

            ];
        }

    }

    public function fetchCustomers(){
        $customers = $this->getCustomers();
        if (!$customers['status']) {
            return redirect()->back()->withErrors(['error' => $customers['message']]);
        }
        foreach($customers['data']->eCustomers as $customer){
            $country = Country::where('nicename', 'like', '%' . $customer->country. '%')->first();
            $user = [
                'name' => $customer->name,
                'email' => $customer->email,
                'country_id' => $country->id ?? 1,
                'phone_number' => $customer->mobile,
                'is_superadmin' => 0,
                'created_at' => date('Y-m-d', $customer->syncTS),
                'updated_at' => date('Y-m-d', $customer->syncTS)
            ];
            $user = User::updateOrCreate([
                'email' => $customer->email
            ],$user); 
            $address = [
                'user_id' => $user->id,
                'address' => $customer->address1,
                'city' => $customer->city,
                'state' => $customer->state,
                'country' => $customer->country,
                'pincode' => $customer->pincode,
                'latitude' => $customer->latitude,
                'longitude' => $customer->longitude,
                'created_at' => date('Y-m-d', $customer->syncTS),
                'updated_at' => date('Y-m-d', $customer->syncTS)
            ];
            UserAddress::updateOrCreate([
                'user_id' => $user->id,
                'latitude' => $customer->latitude,
                'longitude' => $customer->longitude
            ],
            $address
            );
        }

        return response()->json(['message' => 'Customer Added Successfully'], 200);
    }

    //fetching categories

    public function fetchCategories()
    {
        $categories = $this->getCategory();
        if (!$categories['status']) {
            return redirect()->back()->withErrors(['error' => $categories['message']]);
        }
        $user = Auth::user();
        $code = $user->code ?? '245bae';
        foreach ($categories['data']->categories as $category) {
            $newCategory = [
                'slug' => $category->displayName,
                'created_at' => date('Y-m-d', $category->timeStamp),
                'updated_at' => date('Y-m-d', $category->timeStamp),
                'status' => $category->Status == 'Y' ? 1 : 0,
                'client_code' => $code,
                'position' => 1,
                'parent_id' => 1,
                'is_visible' => 1,
                'type_id' => 6 //for subcategory
            ];

            $newCategory = Category::firstorCreate(['slug' => $category->displayName]);
            Category_translation::updateOrCreate(
                [
                    'category_id' => $newCategory->id,
                    'language_id' => 1
                ],
                [
                    'category_id' => $newCategory->id,
                    'language_id' => 1,
                    'name' => $newCategory->slug,
                    'meta_title' => '',
                    'meta_description' => '',
                    'meta_keywords' => ''
                ]
            );
            $this->addCategoryHistory($newCategory, $user);
            $this->addCategoryTranslation($newCategory);
            if (!empty($category->categoryValues)) {
                foreach ($category->categoryValues as $subCategory) {
                    $newSubCategory = [
                        'slug' => $subCategory->categoryValueName,
                        'status' => $subCategory->catStatus == 'Y' ? 1 : 0,
                        'created_at' => date('Y-m-d', $subCategory->syncTs),
                        'updated_at' => date('Y-m-d', $subCategory->syncTs),
                        'parent_id' => $newCategory->id,
                        'client_code' => $code,
                        'is_visible' => 1,
                        'type_id' => 1 //for product
                    ];
                    $newSubCategory = Category::updateOrCreate(['slug' => $subCategory->categoryValueName], $newSubCategory);
                    $this->addCategoryHistory($newSubCategory, $user);
                    $this->addCategoryTranslation($newSubCategory);
                }
            }
        }
        return response()->json(['message' => 'Categories Added Successfully'], 200);
    }

    public function addCategoryHistory($newCategory, $user)
    {
        $hs = new CategoryHistory();
        $hs->category_id = $newCategory->id;
        $hs->action = 'Add';
        $hs->updater_role = 'Admin';
        $hs->update_id = $user->id ?? 1;
        $hs->client_code = $user->code ?? '245bae';
        $hs->save();
    }

    public function addCategoryTranslation($category)
    {
        Category_translation::updateOrCreate(
            [
                'category_id' => $category->id,
                'language_id' => 1
            ],
            [
                'category_id' => $category->id,
                'language_id' => 1,
                'name' => $category->slug,
                'meta_title' => '',
                'meta_description' => '',
                'meta_keywords' => ''
            ]
        );
    }
    
    //fetching categories end

    //fetching all vendors
    public function fetchAllVendors()
    {
        $response =  $this->getVendors();
        if (!$response['status']) {
            return redirect()->back()->withErrors(['error' => $response['message']]);
        }
        $vendors = $response['data'];
        foreach ($vendors->supplierMaster as $key => $vendor) {
            $newVendor = new Vendor();
            $newVendor->name = $vendor['name'];
            $newVendor->slug = strtolower(str_replace(' ', '-', $newVendor->name));
            $newVendor->address = $vendor['address1'];
            $newVendor->city = $vendor['address2'];
            $newVendor->state = $vendor['address3'];
            $newVendor->email = $vendor['emailId'];
            $newVendor->phone_no = $vendor['mobileNumber'];
            $newVendor->pincode = $vendor['pincode'];
            $newVendor->save();
        }
        return response()->json(['message' => 'Vendor Added Successfully'], 200);
    }

    //fetching vendors end
}
