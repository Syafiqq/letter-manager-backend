<?php

use App\Model\Util\ClaimTable;

/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 31 March 2019, 12:21 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
class JwtTest extends TestCase
{
    public function test_encode()
    {
        $this->assertTrue(true);
        /** @var \Tymon\JWTAuth\Factory $factory */
        $factory = $this->app->make('tymon.jwt.payload.factory');
        /** @var \Tymon\JWTAuth\JWTAuth $auth */
        $auth   = $this->app->make('tymon.jwt.auth');
        $claims = [
            ClaimTable::AUDIENCE => 'audience', //Audience of the token
            ClaimTable::ISSUER => 'issuer', // Issuer of the token
            ClaimTable::SUBJECT => 'subject', // Subject of the token
            ClaimTable::ISSUED_AT => time(), // Time when JWT was issued.
            ClaimTable::EXPIRATION => time() + 60 * 60, // Expiration time
            ClaimTable::AUTH_STAMP => 'stamp',
            ClaimTable::SESSION => 'session',
            ClaimTable::ROLE => 'role',
        ];

        $payload = $factory->customClaims($claims)->make();

        $encode = $auth->manager()->encode($payload);
        echo $encode->get();
    }

    public static function _generateToken(TestCase $case, array $claims): string
    {
        /** @var \Tymon\JWTAuth\Factory $factory */
        $factory = $case->app->make('tymon.jwt.payload.factory');
        /** @var \Tymon\JWTAuth\JWTAuth $auth */
        $auth    = $case->app->make('tymon.jwt.auth');
        $payload = $factory->customClaims($claims)->make();
        $encode  = $auth->manager()->encode($payload);

        return $encode->get();
    }

    public static function _dummyClaims(): array
    {
        return [
            ClaimTable::AUDIENCE => 'audience', //Audience of the token
            ClaimTable::ISSUER => 'issuer', // Issuer of the token
            ClaimTable::SUBJECT => 'subject', // Subject of the token
            ClaimTable::ISSUED_AT => time(), // Time when JWT was issued.
            ClaimTable::EXPIRATION => time() + 60 * 60, // Expiration time
            ClaimTable::AUTH_STAMP => 'stamp',
            ClaimTable::SESSION => 'session',
            ClaimTable::ROLE => 'role',
        ];
    }
}

?>
