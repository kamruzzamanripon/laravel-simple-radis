<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

if (!function_exists('checkRedisDriverActiveOrNot')) {

    function checkRedisDriverActiveOrNot()
    {
        if( env('CACHE_DRIVER') === 'redis'){
            return true;
        }

        Log::info("No redis driver found in environment file!");
        return false;
    }
}


if (!function_exists('redisKeyExist')) {

    function redisKeyExist($key, $is_collection = 0, $is_assoc_array = false)
    {
        $exist_key = Redis::exists($key);

        if($exist_key){
            if($is_assoc_array){
                return json_decode(Redis::get($key), true);
            }
            else if(!$is_collection){
                return json_decode(Redis::get($key));
            }
            else{
                return collect(json_decode(Redis::get($key)));
            }
        }

        return false;
    }
}

if (!function_exists('redisSetData')) {

    function redisSetData($key, $data, $expire_time = 86400, $is_json = true)
    {
       $is_json ? Redis::set($key, json_encode($data)) : Redis::set($key, $data);
       $pxpire_time = 0 ? Redis::expire($key) : Redis::expire($key, $expire_time);

       return true;
    }
}


if (!function_exists('redisResetData')) {

    function redisResetData($key)
    {
       Redis::del($key);

       return true;
    }
}


if (!function_exists('flushAllRedisKey')) {

    function flushAllRedisKey()
    {
       Redis::command('flushdb');

       return true;
    }
}
