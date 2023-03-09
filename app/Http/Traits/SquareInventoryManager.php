<?php
namespace App\Http\Traits;
use Square\SquareClient;
use Square\Environment;
use Square\Exceptions\ApiException;
use Ramsey\Uuid\Uuid;
use App\Models\{Product, Variant, TaxCategory, Client, ProductVariantSet, ClientPreference, ProductTranslation, ClientLanguage, ProductVariant, ClientCurrency, TaxRate, AddonSet, AddonOption};
use Auth, Log;
trait SquareInventoryManager{

  private $application_id;
  private $access_token;
  private $location_id;
  private $sandbox_enable_status;
  private $ClientPreference;

  public function init()
  {
    $this->ClientPreference      = ClientPreference::with(['primary'])->first();
    $getAdditionalPreference     = getAdditionalPreference(['square_enable_status', 'square_credentials']);
    $square_credentials          = json_decode($getAdditionalPreference['square_credentials'], true);
    $this->sandbox_enable_status = (int) isset($square_credentials['sandbox_enable_status']) ? $square_credentials['sandbox_enable_status'] : 0;
    $this->application_id        = isset($square_credentials['application_id']) ? $square_credentials['application_id'] : '';
    $this->access_token          = isset($square_credentials['access_token']) ? $square_credentials['access_token'] : '';
    $this->location_id           = isset($square_credentials['location_id']) ? $square_credentials['location_id'] : '';
    $config = [
        'accessToken' => $this->access_token,
        'environment' => ($this->sandbox_enable_status == 1) ? 'sandbox' : 'production',
    ];
    return new SquareClient($config);
  }

  public function createOrUpdateProductInSquarePos($product_id)
  {
    try{
      $product         = Product::with(['media.image', 'primary', 'category.cat', 'vendor','brand','variant', 'variant.set', 'variantSets', 'taxCategory.taxRate', 
                          'addOn.setoptions'])->select('id', 'sku', 'is_live', 'has_variant', 'tax_category_id', 'square_item_id', 'square_item_version')
                          ->where('id', $product_id)->where('is_live', 1)->first();
      if(!empty($product))
      {
        //------https://developer.squareup.com/docs/catalog-api/build-with-catalog

        //------init square client
        $client = $this->init();
        
        $square_item_id = !empty($product->square_item_id) ? $product->square_item_id : '#ITEM_'.$product->id;
        $object_ids[] = $square_item_id;

        $taxrate = $taxrateid = 0;
        $square_tax_id = '';
        if(!empty($product->taxCategory)){
          foreach($product->taxCategory->taxRate as $taxes){
            if($taxrate == 0){
              $taxrate   = $taxes->tax_rate;
              $taxrateid = $taxes->id;
              $square_tax_id = !empty($taxes->square_tax_id) ? $taxes->square_tax_id : '#TAX_'.$taxes->id;
              $object_ids[] = $square_tax_id;
            }
          }
        }
        
        //------get versions if items already exists
        $object_versions = $this->getItemVersionFromSquarePos($object_ids);

        $variations = [];
        //------item variant object creation starts here
        foreach($product->variant as $proVariant){
          
          $price_money = new \Square\Models\Money();
          $price_money->setAmount(($proVariant->price ?? 0.00) * 100);
          $price_money->setCurrency($this->ClientPreference->primary->currency->iso_code ?? 'USD');

          if(isset($proVariant->set[0])){
            $setVName = $proVariant->set[0]->title;
          }else{
            $setVName = $product->primary->title ?? $product->title;
          }

          $square_variant_id = !empty($proVariant->square_variant_id) ? $proVariant->square_variant_id : '#ITEM_VARIATION_'.$proVariant->id;
          
          $item_variation_data = new \Square\Models\CatalogItemVariation();
          $item_variation_data->setItemId($square_item_id);
          $item_variation_data->setName($setVName);
          $item_variation_data->setSku(!empty($proVariant->sku) ? $proVariant->sku : $product->sku);
          $item_variation_data->setPricingType('FIXED_PRICING');//------https://developer.squareup.com/reference/square/enums/CatalogPricingType
          $item_variation_data->setPriceMoney($price_money);//------https://developer.squareup.com/reference/square/objects/Money
          $item_variation_data->setStockable(true);
          $item_variation_data->setSellable(true);
          $item_variation_data->setTrackInventory(true);

          $catalog_object = new \Square\Models\CatalogObject('ITEM_VARIATION', $square_variant_id);
          if(isset($object_versions[$square_variant_id]) && $object_versions[$square_variant_id] !='')
          {
            $catalog_object->setVersion($object_versions[$square_variant_id]);
          }
          $catalog_object->setItemVariationData($item_variation_data);
          $variations[] = $catalog_object;
        }
        //------item variant object creation ends here

        
        //------item object creation starts here
        $item_data = new \Square\Models\CatalogItem();
        $item_data->setName($product->primary->title ?? $product->title);
        $item_data->setVariations($variations);
        $item_data->setProductType('REGULAR');//------https://developer.squareup.com/reference/square/enums/CatalogItemProductType

        $catalog_object = new \Square\Models\CatalogObject('ITEM', $square_item_id);
        if(isset($object_versions[$square_item_id]) && $object_versions[$square_item_id]!=''){
          $catalog_object->setVersion($object_versions[$square_item_id]);
        }
        $catalog_object->setItemData($item_data);
        $objects[] = $catalog_object;
        //------item object creation ends here

        //------tax object creation starts here
        if($taxrate > 0){
            $tax_data = new \Square\Models\CatalogTax();//------https://developer.squareup.com/reference/square/objects/CatalogTax
            $tax_data->setName($product->taxCategory->title);
            $tax_data->setCalculationPhase('TAX_SUBTOTAL_PHASE');
            $tax_data->setInclusionType('ADDITIVE');
            $tax_data->setPercentage($taxrate);
    
            $catalog_object3 = new \Square\Models\CatalogObject('TAX', $square_tax_id);
            if(isset($object_versions[$square_tax_id]) && $object_versions[$square_tax_id]!=''){
              $catalog_object3->setVersion($object_versions[$square_tax_id]);
            }
            $catalog_object3->setTaxData($tax_data);
            $objects[] = $catalog_object3;
        }
        //------tax object creation ends here
      
        $catalog_object_batch = new \Square\Models\CatalogObjectBatch($objects);
        $batches = [$catalog_object_batch];
          
        $uniqueid = Uuid::uuid4();
        $body = new \Square\Models\BatchUpsertCatalogObjectsRequest($uniqueid, $batches);
        $api_response = $client->getCatalogApi()->batchUpsertCatalogObjects($body);

        if($api_response->isSuccess()) {
          $resultObject = $api_response->getResult()->getObjects();
          
          foreach($resultObject as $resultobjectdata){
          
            //------update squarepos item/version/tax id and version in respective table
            if($resultobjectdata->getType() == "ITEM"){

              Product::where('id', $product->id)->update(['square_item_id' => $resultobjectdata->getId(), 'square_item_version' => $resultobjectdata->getVersion()]);
              
              foreach($resultobjectdata->getItemData()->getVariations() as $variantData){
                if($variantData->getType() == "ITEM_VARIATION" && $variantData->getItemVariationData()->getSku()!=''){
                  ProductVariant::where('product_id', $product->id)->where('sku', '=', $variantData->getItemVariationData()->getSku())->update(['square_variant_id' => $variantData->getId(), 'square_variant_version' => $variantData->getVersion()]);
                }
              }

            }

            if($resultobjectdata->getType() == "TAX" && $taxrateid > 0){
              TaxRate::where('id', $taxrateid)->update(['square_tax_id' => $resultobjectdata->getId(), 'square_tax_version' => $resultobjectdata->getVersion()]);
            }

          }
          
          return response()->json([
              'status'  => 'success',
              'result'  => '',
              'message' => __('product created/updated in Square.')
          ]);
        } 
        else 
        {
          $errors = $api_response->getErrors();
          Log::info($errors);
          return response()->json([
            'status'  => 'error',
            'result'  => '',
            'message' => __('There is some error while creating Item, tax and variant in Square.')
          ]);
        }
        
      }
      else
      {
        return response()->json([
          'status'  => 'error',
          'result'  => [],
          'message' => __('product and its variants not found.')
        ]);
      }
    } 
    catch (ApiException $e) 
    {
      return response()->json([
        'status'  => 'error',
        'result'  => [],
        'message' => $e->getMessage()
      ]);
    } 
  }

public function createOrUpdateModifiersSquare($addOnid)
{
  try{
    $addOn = AddonSet::with(['primary', 'option.translation_one'])->where('id', $addOnid)->first();
    //pr($addOn->toArray());
    if(!empty($addOn)){
      //------init square client
      $client = $this->init();
      //------item addon object creation starts here
      $square_modifier_id = !empty($addOn->square_modifier_id) ? $addOn->square_modifier_id : '#modifier_list';
      $object_versions = $this->getItemVersionFromSquarePos([$square_modifier_id]);
      $modifiers = [];
      
      foreach($addOn->option as $addonOptionData)
      {
        $price_money = new \Square\Models\Money();
        $price_money->setAmount(($addonOptionData->price ?? 0.00) * 100);
        $price_money->setCurrency($this->ClientPreference->primary->currency->iso_code ?? 'USD');

        $setVName = !empty($addonOptionData->translation_one) ? $addonOptionData->translation_one->title : $addonOptionData->title;
        
        $modifier_data = new \Square\Models\CatalogModifier();
        $modifier_data->setName($setVName);
        $modifier_data->setPriceMoney($price_money);
        $modifier_data->setModifierListId($square_modifier_id);

        $square_modifier_option_id = !empty($addonOptionData->square_modifier_option_id) ? $addonOptionData->square_modifier_option_id : '#MODIFIER_'.$addonOptionData->id;
        $catalog_object = new \Square\Models\CatalogObject('MODIFIER', $square_modifier_option_id);

        if(isset($object_versions[$square_modifier_option_id]) && $object_versions[$square_modifier_option_id] !='')
        {
          $catalog_object->setVersion($object_versions[$square_modifier_option_id]);
        }
        $catalog_object->setModifierData($modifier_data);
        $modifiers[] = $catalog_object;
      }
  
      $modifier_list_data = new \Square\Models\CatalogModifierList();
      $modifier_list_data->setName($addOn->primary->title);
      $modifier_list_data->setModifiers($modifiers);

      $object       = new \Square\Models\CatalogObject('MODIFIER_LIST', $square_modifier_id);
      if(isset($object_versions[$square_modifier_id]) && $object_versions[$square_modifier_id] !='')
      {
        $object->setVersion($object_versions[$square_modifier_id]);
      }
      $object->setModifierListData($modifier_list_data);

      $uniqueid     = Uuid::uuid4();
      $body         = new \Square\Models\UpsertCatalogObjectRequest($uniqueid, $object);

      $api_response = $client->getCatalogApi()->upsertCatalogObject($body);

      if ($api_response->isSuccess()) {
        $result = $api_response->getResult();
        //pr($result);
        $resultObject = $api_response->getResult()->getCatalogObject();
        
        foreach($resultObject as $resultobjectdata){
        
          //------update squarepos item/version/tax id and version in respective table
          if($resultobjectdata->getType() == "MODIFIER_LIST"){

            AddonSet::where('id', $addOn->id)->update(['square_item_id' => $resultobjectdata->getId()]);
            
            foreach($resultobjectdata->getItemData()->getModifiers() as $modifierData){
              if($modifierData->getType() == "MODIFIER"){
                AddonOption::where('product_id', $addOn->id)->where('title', '=', $modifierData->getModifierData()->getName())->update(['square_modifier_option_id' => $modifierData->getId()]);
              }
            }

          }
        }
      } else {
          $errors = $api_response->getErrors();
          pr($errors);
      }
    }else{
      return response()->json([
        'status'  => 'error',
        'result'  => [],
        'message' => __("Addon does not exists")
      ]);
    }
    
  } 
  catch (ApiException $e) 
  {
    return response()->json([
      'status'  => 'error',
      'result'  => [],
      'message' => $e->getMessage()
    ]);
  } 
}
  
  //------get square pos verionas objects ids (item, tax..... etc) starts here
  public function getItemVersionFromSquarePos($object_ids){
    $client = $this->init();

    $body = new \Square\Models\BatchRetrieveCatalogObjectsRequest($object_ids);
    $body->setIncludeRelatedObjects(true);

    $api_response = $client->getCatalogApi()->batchRetrieveCatalogObjects($body);
    
    $object_versions = array();
    if($api_response->isSuccess()) {
      $result = $api_response->getResult()->getObjects();
      $resultObject = $api_response->getResult()->getObjects();
      if($api_response->getResult()->getObjects()){
        $resultObject = $api_response->getResult()->getObjects();
        foreach($resultObject as $resultobjectdata){
      
          if($resultobjectdata->getType() == "ITEM"){
            $object_versions[$resultobjectdata->getId()] = $resultobjectdata->getVersion();
            foreach($resultobjectdata->getItemData()->getVariations() as $variantData){
              if($variantData->getType() == "ITEM_VARIATION" && $variantData->getItemVariationData()->getSku()!=''){
                $object_versions[$variantData->getId()] = $variantData->getVersion();
              }
            }
          }
  
          if($resultobjectdata->getType() == "TAX"){
            $object_versions[$resultobjectdata->getId()] = $resultobjectdata->getVersion();
          }
        }
      }
    } else {
        $errors = $api_response->getErrors();
        Log::info($errors);
    }
    return $object_versions;
  }//------get square pos verionas objects ids (item, tax..... etc) ends here


  public function deleteBatchInSquarePos($batch_square_ids)
  {
    try{
      $client = $this->init();
      $object_ids = $batch_square_ids;
      $body = new \Square\Models\BatchDeleteCatalogObjectsRequest();
      $body->setObjectIds($object_ids);

      $api_response = $client->getCatalogApi()->batchDeleteCatalogObjects($body);

      if ($api_response->isSuccess()) {
          $result = $api_response->getResult();
          return response()->json([
            'status'  => 'success',
            'result'  => '',
            'message' => __('Batch deleted in Square.')
          ]);
      } else {
          $errors = $api_response->getErrors();
          return response()->json([
            'status'  => 'error',
            'result'  => [],
            'message' => __('Something went wrong, Please try again later.')
          ]);
      }
    }
    catch (ApiException $e) 
    {
      return response()->json([
        'status'  => 'error',
        'result'  => [],
        'message' => $e->getMessage()
      ]);
    } 
  }


  //-----------------------********-----Testing Functions-----**********------------------------------//
  public function createNewProductInSquareTest()
  {
    try{
      //init square client
      $client = $this->init();

      //---setting variant price and currency
      $price_money = new \Square\Models\Money();
      $price_money->setAmount(300);
      $price_money->setCurrency('USD');

      $item_variation_data = new \Square\Models\CatalogItemVariation();
      $item_variation_data->setItemId('#coffee');
      $item_variation_data->setName('Small');
      $item_variation_data->setSku('small_coffee');
      $item_variation_data->setPricingType('FIXED_PRICING');
      $item_variation_data->setPriceMoney($price_money);

      $catalog_object1 = new \Square\Models\CatalogObject('ITEM_VARIATION', '#small_coffee');
      $catalog_object1->setItemVariationData($item_variation_data);

      $price_money1 = new \Square\Models\Money();
      $price_money1->setAmount(350);
      $price_money1->setCurrency('USD');

      $item_variation_data1 = new \Square\Models\CatalogItemVariation();
      $item_variation_data1->setItemId('#coffee');
      $item_variation_data1->setName('Large');
      $item_variation_data1->setSku('large_coffee');
      $item_variation_data1->setPricingType('FIXED_PRICING');
      $item_variation_data1->setPriceMoney($price_money1);

      $catalog_object2 = new \Square\Models\CatalogObject('ITEM_VARIATION', '#large_coffee');
      $catalog_object2->setItemVariationData($item_variation_data1);

      $variations = [$catalog_object1, $catalog_object2];
      $item_data = new \Square\Models\CatalogItem();
      $item_data->setName('Coffee');
      //$item_data->setCategory('coffeeeee');
      $item_data->setVariations($variations);
      $item_data->setProductType('REGULAR');

      $catalog_object = new \Square\Models\CatalogObject('ITEM', '#coffee');
      $catalog_object->setItemData($item_data);

      $tax_data = new \Square\Models\CatalogTax();
      $tax_data->setName('Drink Tax');
      $tax_data->setCalculationPhase('TAX_SUBTOTAL_PHASE');
      $tax_data->setInclusionType('ADDITIVE');
      $tax_data->setPercentage('7.5');

      $catalog_object3 = new \Square\Models\CatalogObject('TAX', '#sales_tax');
      $catalog_object3->setTaxData($tax_data);

      $objects = [$catalog_object, $catalog_object3];
      $catalog_object_batch = new \Square\Models\CatalogObjectBatch($objects);

      $batches = [$catalog_object_batch];
      $uniqueid = Uuid::uuid4();
      Log::info($uniqueid);
      $body = new \Square\Models\BatchUpsertCatalogObjectsRequest($uniqueid, $batches);

      $api_response = $client->getCatalogApi()->batchUpsertCatalogObjects($body);

      if ($api_response->isSuccess()) {
          $result = $api_response->getResult();
          echo '<pre/>';print_r($result);die;
          return $result;
      } else {
          $errors = $api_response->getErrors();
          //pr($errors);
          return $errors;
      }

    } catch (ApiException $e) {
      dd("Recieved error while calling Square: " . $e->getMessage());
    } 
  }



  public function updateNewProductInSquareTest()
  {
    try{
      //init square client
      $client = $this->init();

      //---setting variant price and currency
      $price_money = new \Square\Models\Money();
      $price_money->setAmount(300);
      $price_money->setCurrency('USD');

      $item_variation_data = new \Square\Models\CatalogItemVariation();
      $item_variation_data->setItemId('#coffee');
      $item_variation_data->setName('Small');
      $item_variation_data->setSku('small_coffee');
      $item_variation_data->setPricingType('FIXED_PRICING');
      $item_variation_data->setPriceMoney($price_money);

      $catalog_object1 = new \Square\Models\CatalogObject('ITEM_VARIATION', '#small_coffee');
      $catalog_object1->setItemVariationData($item_variation_data);

      $price_money1 = new \Square\Models\Money();
      $price_money1->setAmount(350);
      $price_money1->setCurrency('USD');

      $item_variation_data1 = new \Square\Models\CatalogItemVariation();
      $item_variation_data1->setItemId('#coffee');
      $item_variation_data1->setName('Large');
      $item_variation_data1->setSku('large_coffee');
      $item_variation_data1->setPricingType('FIXED_PRICING');
      $item_variation_data1->setPriceMoney($price_money1);

      $catalog_object2 = new \Square\Models\CatalogObject('ITEM_VARIATION', '#large_coffee');
      $catalog_object2->setItemVariationData($item_variation_data1);

      $variations = [$catalog_object1, $catalog_object2];
      $item_data = new \Square\Models\CatalogItem();
      $item_data->setName('Coffee');
      $item_data->setVariations($variations);
      $item_data->setProductType('REGULAR');

      $catalog_object = new \Square\Models\CatalogObject('ITEM', '#coffee');
      $catalog_object->setItemData($item_data);

      $tax_data = new \Square\Models\CatalogTax();
      $tax_data->setName('Drink Tax');
      $tax_data->setCalculationPhase('TAX_SUBTOTAL_PHASE');
      $tax_data->setInclusionType('ADDITIVE');
      $tax_data->setPercentage('7.5');

      $catalog_object3 = new \Square\Models\CatalogObject('TAX', '#sales_tax');
      $catalog_object3->setTaxData($tax_data);

      $objects = [$catalog_object, $catalog_object3];
      $catalog_object_batch = new \Square\Models\CatalogObjectBatch($objects);

      $batches = [$catalog_object_batch];
      $uniqueid = Uuid::uuid4();
      Log::info($uniqueid);
      $body = new \Square\Models\BatchUpsertCatalogObjectsRequest($uniqueid, $batches);

      $api_response = $client->getCatalogApi()->batchUpsertCatalogObjects($body);

      if ($api_response->isSuccess()) {
          $result = $api_response->getResult();
          echo '<pre/>';print_r($result);die;
          return $result;
      } else {
          $errors = $api_response->getErrors();
          //pr($errors);
          return $errors;
      }

    } catch (ApiException $e) {
      dd("Recieved error while calling Square: " . $e->getMessage());
    } 
  }
  
}
