<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;


trait RedisCacheTrait{
    public $cache_minutes = '3600';
    public $radius = '2';

    public function storeLocations($locations,$data='',$loc_key = 'geo_fence:locations', $requestHash = '') {
        $redis = Redis::connection();
        $redis->pipeline(function ($pipe) use ($locations,$data,$loc_key,$requestHash) {
            //foreach ($locations as $location) {
                $cacheKey = $requestHash ? $locations[0]['key'] . ':' . $requestHash : $locations[0]['key'];
                $pipe->geoadd($loc_key, $locations[0]['longitude'], $locations[0]['latitude'], $cacheKey);
                Redis::set($cacheKey, json_encode($data));
                Redis::expire($cacheKey, $this->cache_minutes);
            //}
        });
    }

    public function isPointInRadius($latitude, $longitude, $radius= '5', $key='geo_fence:locations', $requestHash = '') {

        $redis = Redis::connection();
        //echo $key;
        $ret = [];
        $result = $redis->georadius($key, $longitude, $latitude, $radius, 'km', 'WITHDIST');
        $ret = $result;
        if(@$result[0][0]) {
            // Try to find cached result with request hash first
            $cacheKeyWithHash = $requestHash ? $result[0][0] . ':' . $requestHash : $result[0][0];
            $cachedResult = Redis::get($cacheKeyWithHash);
            
            // If not found with hash, try without hash for backward compatibility
            if (!$cachedResult && $requestHash) {
                $cachedResult = Redis::get($result[0][0]);
            }
            
            if ($cachedResult) {
                $ret['data'] = json_decode($cachedResult, true);
            } 
        } 
        return $ret;

    }

    public function deleteKeysContainingWord(Request $request)
    {

        try {
            $redis = Redis::connection();
            $redis->select(0);
            $keys = $redis->keys('*:'.$request->code.'*');
            foreach ($keys as $key) {
                shell_exec("redis-cli DEL ".$key);
            }
            return response()->json([
                "success" => true,
                'message' => "Keys containing the word \"redis\" have been deleted.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'message' => "Something went wrong!",
            ]);
        }
        
    }       
}
