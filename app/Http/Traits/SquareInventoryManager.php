<?php
namespace App\Http\Traits;
use Square\SquareClient;
use Square\Http\ApiResponse;
use Ramsey\Uuid\Uuid;
use App\Models\{Product, Variant, TaxCategory, Client, ProductVariantSet, ClientPreference, ProductTranslation, ClientLanguage, ProductVariant};
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
      //init square client
      $products = Product::with(['media.image', 'primary', 'category.cat', 'vendor','brand', 'addOn','variant'])->select('id', 'sku', 'is_live', 'has_variant')
        ->where('id', $product_id)->where('is_live', 1)->get();
      pr($products->toArray());
      if(!empty($products))
      {
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
          return response()->json([
              'status'  => 'success',
              'result'  => $result,
              'message' => __('Products created in Square.')
          ]);
        } 
        else 
        {
          $errors = $api_response->getErrors();
          return response()->json([
            'status'  => 'error',
            'result'  => $errors,
            'message' => __('There is some error while creating Item and variant in Square.')
          ]);
        }
      }
      else
      {
        return response()->json([
          'status'  => 'error',
          'result'  => [],
          'message' => __('Products and its variants not found.')
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
