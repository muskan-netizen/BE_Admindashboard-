<?php

namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\{Category, PaymentOption, Product, UserAddress, Vendor, VerificationOption};
use Illuminate\Support\Facades\Auth;

trait YachtTrait
{
    public function productSearch($request, $pickup, $dropOff)
    {
        $pickup_time = $pickup->time;
        $drop_time = $dropOff->time;
        $clientPreference = \App\Models\ClientPreference::where(['id' => 1])->first();
        if ($request->service == 'airport') {
            $mapKey = '1234';
            if ($clientPreference && !empty($clientPreference->map_key)) {
                $mapKey = $clientPreference->map_key;
            }
            $response = \Http::get("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=$pickup->latitude,$pickup->longitude&rankby=distance&type=airport&key=$mapKey")['results'];
            $data['products'] = collect($response)->map(function ($result) {
                return [
                    'title' => $result['name'],
                    'path' => $result['icon'],
                    'location' => $result['vicinity'],
                    'latitude' => $result['geometry']['location']['lat'],
                    'longitude' => $result['geometry']['location']['lng'],
                ];
            });
        }
        
        $category = Category::where('slug', $request->service)->first();
        $data['products'] = [];
        if ($category) {
            $data['products'] = Product::with([
                'variant', 'media.image',
                'ProductAttribute' => function ($q) use ($request) {
                    if ($request->service == 'rental') {
                        $q->whereIn('key_name', ['Transmission', 'Fuel Type', 'Seats']);
                    } else {
                        $q->whereIn('key_name', ['Cabins', 'Berths', 'Baths']);
                    }
                },
                'ProductAttribute.attributeOption:id,title'
            ])
                ->where(function ($q) use ($pickup_time, $drop_time) {
                    if (!empty($pickup_time) && !empty($drop_time)) {
                        // $q->where('pickup_time', '<=', $pickup_time)
                        // ->where('drop_time', '>=', $drop_time);
                    }
                })->whereDoesntHave('productBooked')
                ->where(function ($q) use ($request) {
                    if ($request->has('seats') && !empty($request->seats)) {
                        $q->whereHas('ProductAttribute', function ($q) use ($request) {
                            if ($request->service == 'rental') {
                                $q->where('key_name', 'Seats')->where('key_value', '>=', $request->seats);
                            } else {
                                $q->where('key_name', 'Berths')->where('key_value', '>=', $request->seats);
                            }
                        });
                    }

                })
                ->where(function ($q) use ($request, $pickup, $dropOff) {
                    if (isset($pickup->latitude) && isset($pickup->longitude)) {
                        $q->whereHas('vendor.serviceArea', function ($q) use ($pickup) {
                            $q->select('id', 'vendor_id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $pickup->latitude . " " . $pickup->longitude . ")'))");
                        });
                    }

                    if ($request->has('diff-location') && !empty($dropOff->latitude) && !empty($dropOff->longitude)) {
                        $q->whereHas('vendor.serviceArea', function ($q) use ($dropOff) {
                            $q->select('id', 'vendor_id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $dropOff->latitude . " " . $dropOff->longitude . ")'))");
                        });
                    }
                })
                ->with('vendor', function ($q) use ($pickup, $clientPreference) {
                    if (!empty($pickup->latitude) && !empty($pickup->longitude)) {
                        $q->distanceInMeters($pickup->latitude, $pickup->longitude);
                    } else {
                        $q->distanceInMeters($clientPreference->Default_latitude, $clientPreference->Default_longitude);
                    }
                })
                ->where('category_id', $category->id)->get();
        }


        $data['service'] = $request->service;
        $data['pick_drop_time'] = $request->pick_drop_time;
        $data['pickup_time'] = $pickup_time;
        $data['drop_time'] = $drop_time;
        $data['category'] = $category;
        $data['pickup'] = $pickup;
        $data['dropoff'] = $dropOff;
        $data['diff_location'] = $request->diff_location ?? 0;
        $data['seats'] = $request->seats ?? '';
        return $data;
    }
}
