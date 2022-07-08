<?php
namespace App\Http\Traits;

use App\Models\Country;
use Auth, Log;

trait cartManager{
  
  public function __construct()
  {
    $this->user = auth()->user();
    $countries = Country::get();
    $langId = session()->get('customerLanguage');
  }


  public function getCartDetails()
  {

  }

  public function cartVendorProducts()
  {

  }

  public function productAddons()
  {

  }

  public function vendorDeliveryFees()
  {

  }

}
