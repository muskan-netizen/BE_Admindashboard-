<?php
namespace App\Http\Traits;
use App\Models\{ProductRecentlyViewed,WebStylingOption,Product,Category};
use Illuminate\Support\Str;
use Auth;
use Session;
use Carbon\Carbon;

trait ProductActionTrait{

      
     /**
     * getRecentProductIds
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getRecentProductIds()
    {
        if(checkColumnExists('product_recently_viewed','product_id')){
            $query =  ProductRecentlyViewed::query();
            if(Auth::check()){
                $query =  $query->where('user_id', Auth::user()->id);
            } else{
                $query = $query->where('token_id', session()->get('_token'));
            }
            $return = $query->orderBy('updated_at','DESC')->pluck('product_id');
            return $return;
        } else{
            return [];
        }
       
    }
    /**
     * RecentView
     *
     * @param  mixed $p_id
     * @return void
     */
    public function RecentView($p_id)
    {
        $token_id = session()->get('_token');
        $user_id = 0;
        $update_by['product_id'] = $p_id;
         if(Auth::check()){
            $user_id = Auth::user()->id;
            $update_by['user_id'] = $user_id;
        } else{
            $update_by['token_id'] = $token_id;
        }
        $RecentlyViewed = [
            'product_id' => $p_id,
            'token_id' => $token_id,
            'user_id' => $user_id,
            'updated_at' => Carbon::now()
        ];
        if(checkColumnExists('product_recently_viewed','product_id')){
            ProductRecentlyViewed::updateOrCreate(
                $update_by
            ,$RecentlyViewed);
        }
      
    }
    
    /**
     * LoginActionRecentView
     *
     * @param  mixed $user_id
     * @return void
     */
    public function LoginActionRecentView($user_id)
    {
        if(checkColumnExists('product_recently_viewed','product_id')){
            ProductRecentlyViewed::where('token_id', session()->get('_token'))->update(['user_id' => $user_id, 'token_id' => '']);
        }
    }


    /**
     * LoginActionRecentView
     *
     * @param  mixed $user_id
     * @return void
     */
    public function checkTemplateForAction($t_id)
    {
        $set_template = WebStylingOption::where('is_selected', 1)->first();
        $val = 0;
        if(isset($set_template)  && $set_template->template_id == $t_id){
            $val = 1;
        }
        return $val;
    }
    public function productvendorProducts($venderIds, $langId, $currency = 'USD', $where = '', $type,$p_dim)
    {
        $recent_ids = $this->getRecentProductIds();
        $rc_ids = [];
        if(sizeof($recent_ids) > 0){
          $rc_ids = $recent_ids->toArray();
        } else {
            return [];
        }
        $products = Product::byProductCategoryServiceType($type)->with([
            'category.categoryDetail.translation' => function ($q) use ($langId) {
                $q->where('category_translations.language_id', $langId);
            },
            'vendor',
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
          $products = $products->whereIn('id', $rc_ids);

        $pndCategories = Category::where('type_id', 7)->pluck('id');
        // if (is_array($venderIds)) {
        //     $products = $products->whereIn('vendor_id', $venderIds);
        // }
        if ($pndCategories) {
            $products = $products->whereNotIn('category_id', $pndCategories);
        }
        $products = $products->whereHas('vendor', function($q) use ($type,$venderIds){
                    $q->where('status',1);
                    $q->whereIn('id',$venderIds);
                    $q->where($type, 1);
                })->where('is_live', 1)->take(10)->inRandomOrder()->get();
        $productArray = [];
        if (!empty($products)) {

            foreach ($products as $key => $value) {
                $multiply = Session::get('currencyMultiplier') ?? 1;
                $title = $value->translation->first() ? $value->translation->first()->title : $value->sku;
                $image_url = $value->media->first() ? $value->media->first()->image->path['proxy_url'] . $p_dim . $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                $productArray[] = array(
                    'tag_title' => $products_tag_title??0,
                    'image_url' => $image_url,
                    'sku' => $value->sku,
                    'title' => Str::limit($title, 18, '..'),
                    'url_slug' => $value->url_slug,
                    'averageRating' => number_format($value->averageRating, 1, '.', ''),
                    'inquiry_only' => $value->inquiry_only,
                    'vendor_name' => $value->vendor ? $value->vendor->name : '',
                    'vendor' => $value->vendor,
                    'price' => Session::get('currencySymbol') . ' ' . (decimal_format(@$value->variant->first()->price??0 * $multiply,',')),
                    'category' => (@$value->category->categoryDetail->translation) ? @$value->category->categoryDetail->translation->first()->name : @$value->category->categoryDetail->slug
                );
                
            }
        }
      
       return $productArray;
       
    }

     public function longTermServiceProducts($venderIds, $langId, $currency = '', $where = '', $type,$p_dim ='260/100',$requestFrom='web' )
    {
       
        $products = Product::byLongTermProductCategoryServiceType($type)->byProductLongTerm()->with([
            'vendor','LongTermProducts.product',
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only','is_long_term_service')
        ->whereHas('LongTermProducts.product', function($q){$q->where('is_live',1); });
       
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
     
       //$venderIds = ['8'];
        $products = $products->whereHas('vendor', function($q) use ($type,$venderIds){
                    $q->where('status',1);
                    $q->whereIn('id',$venderIds);
                    $q->where($type, 1);
                })->take(10)->inRandomOrder()->get();
     
       
        $return = [];
        if (!empty($products)) {
            // return response from to app
            if($requestFrom == 'app'){
                foreach ($products as $key => $value) {
                    foreach ($value->variant as $k => $v) {
                        $value->variant[$k]->multiplier = $currency ? $currency->doller_compare : 1;
                    }
                }
                return $products;
            }
            foreach ($products as $key => $value) {
                $multiply = Session::get('currencyMultiplier') ?? 1;
                $title = $value->translation->first() ? $value->translation->first()->title : $value->sku;
                $image_url = $value->media->first() ? $value->media->first()->image->path['proxy_url'] . $p_dim . $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                $return[] = array(
                    'tag_title' => $title??'0',
                    'image_url' => $image_url,
                    'sku' => $value->sku,
                    'title' => Str::limit($title, 18, '..'),
                    'url_slug' => $value->url_slug,
                    'averageRating' => number_format($value->averageRating, 1, '.', ''),
                    'inquiry_only' => $value->inquiry_only,
                    'vendor_name' => $value->vendor ? $value->vendor->name : '',
                    'vendor' => $value->vendor,
                    'price' => Session::get('currencySymbol') . ' ' . (decimal_format(@$value->variant->first()->price * $multiply,',')),
                    'category' => ''
                );
            }
        }
       return $return;
        
    }
    
   
}
