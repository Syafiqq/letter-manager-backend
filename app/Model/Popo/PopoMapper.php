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
     * @return array
     */
    static function notifResponse($code = 200, $status = 'Empty Status', $data = [], $notify = [], $alert = [])
    {
        return self::jsonResponse($code, $status, $data, array_merge($notify, [$status]), $alert);
    }

    /**
     * @param int $code
     * @param string $status
     * @param array $data
     * @param array $notify
     * @param array $alert
     * @return array
     */
    static function jsonResponse($code = 200, $status = 'Empty Status', $data = [], $notify = [], $alert = [])
    {
        return compact('code', 'status', 'data', 'notify', 'alert');
    }

    /**
     * @param int $code
     * @param string $status
     * @param array $data
     * @param array $notify
     * @param array $alert
     * @return array
     */
    static function alertResponse($code = 200, $status = 'Empty Status', $data = [], $notify = [], $alert = [])
    {
        return self::jsonResponse($code, $status, $data, $notify, array_merge($alert, [$status]));
    }
}


?>
