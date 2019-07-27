<?php
/**
 * Created By IT Dev of PT. Jasuindo Tiga Perkasa TBK.
 * Copyright (c) 2018. All rights reserved.
 */

use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\JWTGuard;

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
     * @param string|null $guard
     * @return Factory|Guard|StatefulGuard|JWTGuard
     */
    function j_auth($guard = 'api')
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        return auth($guard);
    }
}

if (!function_exists('j_debug'))
{
    function j_debug()
    {
        try
        {
            /** @var JWTAuth $jwt */
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            $jwt = JWTAuth::parseToken();
        }
        catch (JWTException $e)
        {
        }
        Log::debug(var_export([
            $jwt->getToken(),
            $jwt->getCustomClaims(),
            $jwt->getPayload()
        ], true));
    }
}
?>
