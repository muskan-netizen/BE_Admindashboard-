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
    protected $fillable = ['total_delivery_fee', 'total_waiting_price', 'total_waiting_time', 'recurring_booking_type', 'recurring_week_day', 'recurring_week_type', 'recurring_day_data', 'recurring_booking_time', 'scheduled_date_time', 'marg_max_attempt', 'marg_status', 'rental_hours', 'total_amount', 'attachment_path'];

    protected $appends = ['total_tax_casted'];

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }
    public function ordervendor()
    {
        return $this->hasOne('App\Models\OrderVendor', 'order_id', 'id')->select('*', 'dispatcher_status_option_id as dispatcher_status');
    }

    public function orderVendorProduct()
    {
        return $this->hasOne('App\Models\OrderVendorProduct', 'order_id', 'id')->select('*');
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
        return $this->hasOne('App\Models\UserAddress', 'id', 'address_id')->withTrashed();
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

    public function order_product_status()
    {
        return $this->hasMany('App\Models\VendorOrderProductStatus', 'order_id');
    }
    public function scopeBetween($query, $from, $to)
    {
        $query->whereBetween('created_at', [$from, $to]);
    }
    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'order_id', 'id');
    }

    public function refund()
    {
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
        return $this->vendors()->sum('discount_amount') + $this->vendors()->sum('subscription_discount_admin') + $this->vendors()->sum('subscription_discount_vendor');
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
        return self::where('order_number', $order_number)->with('user', 'products', 'products.addon', 'products.addon.option', 'products.pvariant')->first();
    }
    public function giftCard()
    {
        return $this->hasOne('App\Models\GiftCard', 'id', 'gift_card_id');
    }
    public function userGiftCard()
    {
        return $this->hasOne('App\Models\UserGiftCard', 'gift_card_code', 'gift_card_code');
    }
    public function editingInCart()
    {
        return $this->hasOne('App\Models\Cart', 'order_id', 'id');
    }
    public function OrderFiles()
    {
        return $this->hasMany('App\Models\OrderFiles'); //, 'order_id', 'id'
    }
    public function scopeOnlyEnabledLuxuryOptions($query, $EnabledLuxuryOptions = [])
    {
        return $query->whereIn('luxury_option_id', $EnabledLuxuryOptions);
    }

    public function getOrderScheduleDateAttribute()
    {
        $timezone = \Auth::user()->timezone;
        return dateTimeInUserTimeZone($this->scheduled_date_time, $timezone, true, false, false);
    }

    public function getTotalTaxCastedAttribute()
    {
        $otherTaxes = $this->total_other_taxes;

        if (is_null($otherTaxes)) {
            return null;
        }

        $otherTaxes = explode(',', $otherTaxes);
        $otherTaxes = array_map(static fn ($t) => explode(':', $t), $otherTaxes);

        $taxCollection = [];

        foreach ($otherTaxes as $taxes) {
            [$header, $tax] = array_merge($taxes, array_fill(0, 2, null));
            $taxCollection[$header] = $tax;
        }

        return collect($taxCollection);
    }
}
