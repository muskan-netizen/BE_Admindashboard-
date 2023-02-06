<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Order extends Model implements Auditable
{

    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
    protected $casts = ['total_amount' => 'float'];

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }
    public function ordervendor()
    {
        return $this->hasOne('App\Models\OrderVendor', 'order_id', 'id')->select('*', 'dispatcher_status_option_id as dispatcher_status');
    }
    public function vendors()
    {
        return $this->hasMany('App\Models\OrderVendor', 'order_id', 'id')->select('*', 'dispatcher_status_option_id as dispatcher_status');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->withTrashed();
    }
    public function address()
    {
        return $this->hasOne('App\Models\UserAddress', 'id', 'address_id');
    }
    public function orderLocation()
    {
        return $this->hasOne('App\Models\OrderLocations', 'order_id', 'id');
    }
    public function paymentOption()
    {
        return $this->hasOne('App\Models\PaymentOption', 'id', 'payment_option_id');
    }

    public function reqCancelOrder()
    {
        return $this->hasOne('App\Models\OrderCancelRequest'); //, 'order_id', 'id'
    }
    public function orderStatusVendor()
    {
        return $this->hasMany('App\Models\VendorOrderStatus', 'order_id', 'id');
    }
    public function scopeBetween($query, $from, $to)
    {
        $query->whereBetween('created_at', [$from, $to]);
    }
    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'order_id', 'id');
    }
    
    public function refund(){
        return $this->hasMany('App\Models\OrderRefund', 'order_id', 'id');
    }
    public function loyaltyCard()
    {
        return $this->hasOne('App\Models\LoyaltyCard', 'id', 'loyalty_membership_id');
    }
    public function taxes()
    {
        return $this->hasMany('App\Models\OrderTax', 'order_id', 'id')->latest();
    }
    public function prescription()
    {
        return $this->hasMany('App\Models\OrderProductPrescription', 'order_id', 'id');
    }
    public function user_vendor()
    {
        return $this->hasManyThrough(
            'App\Models\UserVendor',
            'App\Models\OrderVendor',
            'order_id', // Foreign key on the environments table...
            'vendor_id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'vendor_id' // Local key on the environments table...
        );
    }

    public function getTotalDiscountCalculateAttribute()
    {
        if(checkColumnExists('order_vendors', 'subscription_discount_admin')){
            return $this->vendors()->sum('discount_amount') + $this->vendors()->sum('subscription_discount_admin') + $this->vendors()->sum('subscription_discount_vendor');
        }else{
            return $this->vendors()->sum('discount_amount');
        }
    }

    public function luxury_option()
    {
        return $this->belongsTo('App\Models\LuxuryOption', 'luxury_option_id', 'id');
    }

    public static function DecreaseStock($variant_id, $quantity)
    {
        $ProductVariant = ProductVariant::find($variant_id);
        if ($ProductVariant) {
            $ProductVariant->quantity  = $ProductVariant->quantity - $quantity;
            $ProductVariant->save();
            return 1;
        }
        return 0;
    }

    public function driver_rating()
    {
        return $this->hasOne('App\Models\OrderDriverRating', 'order_id', 'id');
    }

    public function reports()
    {
        return $this->hasOne('App\Models\OrderVendorReport', 'order_id', 'id');
    }

    public function order_exchange_request()
    {
        return $this->hasOne('App\Models\OrderReturnRequest', 'order_id', 'id');
    }
    
    public function getByNumber($order_number)
    {
        return self::where('order_number',$order_number)->with('user','products','products.addon','products.addon.option','products.pvariant')->first();
    }
    public function giftCard(){
        return $this->hasOne('App\Models\GiftCard','id','gift_card_id');
    }
    public function userGiftCard(){
        return $this->hasOne('App\Models\UserGiftCard','gift_card_code','gift_card_code');
    }
    public function editingInCart()
    {
        return $this->hasOne('App\Models\Cart', 'order_id', 'id');
    }
}
