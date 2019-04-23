<?php
/**
 * Created By IT Dev of PT. Jasuindo Tiga Perkasa TBK.
 * Copyright (c) 2018. All rights reserved.
 */

/**
 * This api.nepal-trace.com project created by :
 * Name         : IT DEV
 * Date / Time  : 19 April 2018, 10:07.
 * Email        : jasuindo.co.id
 */

namespace App\Model\Popo;


class PopoMapper
{
    /**
     * @param int $code
     * @param string $status
     * @param array $data
     * @param array $notify
     * @param array $alert
     * @return ResponsePopo
     */
    static function notifResponse($code = 200, $status = 'Empty Status', $data = [], $notify = [], $alert = [])
    {
        $notify = array_merge($notify, [$status]);

        $notify = self::sanitizeNotify($notify);
        $alert  = self::sanitizeAlert($alert);

        return self::_jsonResponse($code, $status, $data, $notify, $alert);
    }

    /**
     * @param int $code
     * @param string $status
     * @param array $data
     * @param array $notify
     * @param array $alert
     * @return ResponsePopo
     */
    static function jsonResponse($code = 200, $status = 'Empty Status', $data = [], $notify = [], $alert = [])
    {
        $notify = self::sanitizeNotify($notify);
        $alert  = self::sanitizeAlert($alert);

        return self::_jsonResponse($code, $status, $data, $notify, $alert);
    }

    /**
     * @param int $code
     * @param string $status
     * @param array $data
     * @param array $notify
     * @param array $alert
     * @return ResponsePopo
     */
    static function alertResponse($code = 200, $status = 'Empty Status', $data = [], $notify = [], $alert = [])
    {
        $alert = array_merge($alert, [$status]);

        $notify = self::sanitizeNotify($notify);
        $alert  = self::sanitizeAlert($alert);

        return self::_jsonResponse($code, $status, $data, $notify, $alert);
    }

    private static function sanitizeNotify(array $notify)
    {
        if (count($notify) <= 0)
        {
            return $notify;
        }

        $_notify = [];
        foreach ($notify as &$v)
        {
            if (is_array($v))
            {
                array_push($_notify, $v);
            }
            else
            {
                array_push($_notify, [
                    'message' => $v,
                    'level' => 'info'
                ]);
            }
        }

        return $_notify;
    }

    private static function sanitizeAlert(array $alert)
    {
        if (count($alert) <= 0)
        {
            return $alert;
        }

        $_alert = [];
        foreach ($alert as &$v)
        {
            if (is_array($v))
            {
                array_push($_alert, $v);
            }
            else
            {
                array_push($_alert, [
                    'message' => $v,
                    'level' => 'info',
                    'duration' => -1
                ]);
            }
        }

        return $_alert;
    }

    /**
     * @param int $code
     * @param string $status
     * @param array $data
     * @param array $notify
     * @param array $alert
     * @return ResponsePopo
     */
    private static function _jsonResponse(int $code, string $status, array $data, array $notify, array $alert)
    {
        return new ResponsePopo($code, $status, $data, $notify, $alert);
    }
}


?>
