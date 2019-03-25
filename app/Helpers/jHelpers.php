<?php
/**
 * Created By IT Dev of PT. Jasuindo Tiga Perkasa TBK.
 * Copyright (c) 2018. All rights reserved.
 */

use Illuminate\Support\Facades\Log;

/**
 * This nepal-trace-1.com project created by :
 * Name         : IT DEV
 * Date / Time  : 24 April 2018, 11:40.
 * Email        : jasuindo.co.id
 */

if (!function_exists('j_auth'))
{
    /**
     * Get the available auth instance.
     *
     * @param  string|null $guard
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard|\Tymon\JWTAuth\JWTGuard
     */
    function j_auth($guard = 'api')
    {
        return auth($guard);
    }
}

if (!function_exists('j_debug'))
{
    function j_debug()
    {
        /** @var \Tymon\JWTAuth\JWTAuth $jwt */
        $jwt = JWTAuth::parseToken();
        Log::debug(var_export([
            $jwt->getToken(),
            $jwt->getCustomClaims(),
            $jwt->getPayload()
        ], true));
    }
}
?>
