<?php
namespace App\Http\Traits;
use Square\SquareClient;
use Square\Environment;
use Square\Exceptions\ApiException;
use Ramsey\Uuid\Uuid;
use App\Models\{Product, Variant, TaxCategory, Client, ProductVariantSet, ClientPreference, ProductTranslation, ClientLanguage, ProductVariant, ClientCurrency};
use Auth, Log;
trait SquareInventoryManager{

  private $application_id;
  private $access_token;
  private $location_id;
  private $sandbox_enable_status;

  public function init()
  {
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

  public function createNewProductInSquare($product_id)
  {
    try{
      $ClientPreference = ClientPreference::with(['primary'])->first();
      $product         = Product::with(['media.image', 'primary', 'category.cat', 'vendor','brand', 'addOn','variant', 'variant.set', 'variantSets', 'taxCategory.taxRate'])->select('id', 'sku', 'is_live', 'has_variant', 'tax_category_id')
                          ->where('id', $product_id)->where('is_live', 1)->first();
      if(!empty($product))
      {
        //init square client
        $client = $this->init();

        //---setting variant price and currency
        // item variant object creation starts here
        //https://developer.squareup.com/docs/catalog-api/build-with-catalog
        $variations = [];
        $itemname = str_replace(' ', '_', $product->primary->title ?? $product->title);
        foreach($product->variant as $proVariant){

          $price_money = new \Square\Models\Money();
          $price_money->setAmount($proVariant->price ?? 0.00);
          $price_money->setCurrency($ClientPreference->primary->currency->iso_code ?? 'USD');

          if(isset($proVariant->set[0])){
            $setVName = $proVariant->set[0]->title;
          }else{
            $setVName = $product->primary->title ?? $product->title;
          }

          $item_variation_data = new \Square\Models\CatalogItemVariation();
          $item_variation_data->setItemId('#ITEM_'.$product->id);
          $item_variation_data->setName($setVName);
          $item_variation_data->setSku(!empty($proVariant->sku) ? $proVariant->sku : $product->sku);
          $item_variation_data->setPricingType('FIXED_PRICING');//https://developer.squareup.com/reference/square/enums/CatalogPricingType
          $item_variation_data->setPriceMoney($price_money);//https://developer.squareup.com/reference/square/objects/Money
          $item_variation_data->setStockable(true);
          $item_variation_data->setSellable(true);
          $item_variation_data->setTrackInventory(true);

          $catalog_object = new \Square\Models\CatalogObject('ITEM_VARIATION', "#ITEM_VARIATION_".$proVariant->id);
          $catalog_object->setItemVariationData($item_variation_data);

          $variations[] = $catalog_object;

        }
        // item variant object creation ends here

        // item object creation starts here
        $item_data = new \Square\Models\CatalogItem();
        $item_data->setName($product->primary->title ?? $product->title);
        $item_data->setVariations($variations);
        $item_data->setProductType('REGULAR');// https://developer.squareup.com/reference/square/enums/CatalogItemProductType

        $catalog_object = new \Square\Models\CatalogObject('ITEM', '#ITEM_'.$product->id);
        $catalog_object->setItemData($item_data);
        // item object creation ends here

        $catalog_object3 = [];
        if(!empty($product->taxCategory)){
          $taxrate = $taxrateid = 0;
          $square_unique_id = '';
          foreach($product->taxCategory->taxRate as $taxes){
            if($taxrate == 0){
              $taxrate   = $taxes->tax_rate;
              $taxrateid = $taxes->id;
              $square_unique_id = $taxes->square_unique_id;
            }
          }
          if($taxrate > 0){
            $tax_data = new \Square\Models\CatalogTax();//https://developer.squareup.com/reference/square/objects/CatalogTax
            $tax_data->setName($product->taxCategory->title);
            $tax_data->setCalculationPhase('TAX_SUBTOTAL_PHASE');
            $tax_data->setInclusionType('ADDITIVE');
            $tax_data->setPercentage($taxrate);
    
            $catalog_object3 = new \Square\Models\CatalogObject('TAX', '#TAX_'.$product->id);
            $catalog_object3->setTaxData($tax_data);
          }
        }
       
        $objects = [$catalog_object, $catalog_object3];
        $catalog_object_batch = new \Square\Models\CatalogObjectBatch($objects);

        $batches = [$catalog_object_batch];
        $uniqueid = Uuid::uuid4();
        $body = new \Square\Models\BatchUpsertCatalogObjectsRequest($uniqueid, $batches);

        $api_response = $client->getCatalogApi()->batchUpsertCatalogObjects($body);

        if ($api_response->isSuccess()) {
          $result = $api_response->getResult()->getObjects();
          //foreach($result as $resultdata){
            //pr($result->objects);
          //}
          pr($result);
          return response()->json([
              'status'  => 'success',
              'result'  => $result,
              'message' => __('product created in Square.')
          ]);
        } 
        else 
        {
          $errors = $api_response->getErrors();
          Log::info($errors);
          return response()->json([
            'status'  => 'error',
            'result'  => $errors,
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



  public function updateNewProductInSquare()
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
