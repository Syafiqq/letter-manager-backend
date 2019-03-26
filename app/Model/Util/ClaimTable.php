<?php
/**
 * Created By IT Dev of PT. Jasuindo Tiga Perkasa TBK.
 * Copyright (c) 2018. All rights reserved.
 */

/**
 * This nepal-trace-1.com project created by :
 * Name         : IT DEV
 * Date / Time  : 01 Mei 2018, 14:36.
 * Email        : jasuindo.co.id
 */

namespace App\Model\Util;


class ClaimTable
{
    // @formatter:off
    //RESERVED
    const ISSUER                                                    = 'iss';
    const ISSUED_AT                                                 = 'iat';
    const EXPIRATION                                                = 'exp';
    const NOT_BEFORE                                                = 'nbf';
    const JWT_ID                                                    = 'jti';
    const SUBJECT                                                   = 'sub';
    const PROVIDER_MODEL                                            = 'prv';
    const AUDIENCE                                                  = 'aud';
    const PRINCIPAL                                                 = 'prn';
    const TYPE                                                      = 'typ';

    //PRODUCT
    const WRITABLE_PRODUCT                                          = 'wpd';
    const READABLE_PRODUCT                                          = 'rpd';

    //AUTHENTICATION
    const ROLE                                                      = 'rle';
    const INTERNAL                                                  = 'itl';

    const AUTH_STAMP                                                = 'ast';
    const SESSION                                                   = 'sss';
    // @formatter:on
}

?>
