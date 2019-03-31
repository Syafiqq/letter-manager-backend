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
        /** @var \Tymon\JWTAuth\Factory $factory */
        $factory = $this->app->make('tymon.jwt.payload.factory');
        /** @var \Tymon\JWTAuth\JWTAuth $auth */
        $auth   = $this->app->make('tymon.jwt.auth');
        $claims = self::_dummyClaims();

        $payload = $factory->customClaims($claims)->make();

        $encode = $auth->manager()->encode($payload);
        $this->assertTrue(!is_null($encode->get()));
        $this->assertTrue(strlen($encode->get()) != 0);
    }

    /**
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function test_parse()
    {
        $user        = \App\Eloquents\User::take(1)->first();
        $stringToken = self::_generateToken($this, self::_dummyClaims($user->{'id'}));
        $request     = Helpers::createJsonRequest(
            'POST',
            [],
            path_route('student.auth.login.post'),
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer $stringToken",
            ]
        );

        /** @var \Tymon\JWTAuth\JWTAuth $auth */
        $auth = $this->app->make('tymon.jwt.auth');
        $auth->setRequest($request);

        $auth->parseToken();
        if (!$user = $auth->authenticate())
        {
            echo 'Not Found';
        }
        else
        {
            /** @var \Tymon\JWTAuth\Payload $payload */
            $payload = $auth->getPayload();
            $this->assertEquals($user->{'id'}, $payload->get(ClaimTable::SUBJECT));
        }
    }

    /**
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function test_parse_expired()
    {
        $this->expectException(\Tymon\JWTAuth\Exceptions\TokenExpiredException::class);

        $user        = \App\Eloquents\User::take(1)->first();
        $stringToken = self::_generateToken($this, self::_dummyClaims($user->{'id'}, 1));
        $request     = Helpers::createJsonRequest(
            'POST',
            [],
            path_route('student.auth.login.post'),
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer $stringToken",
            ]
        );

        sleep(2);
        /** @var \Tymon\JWTAuth\JWTAuth $auth */
        $auth = $this->app->make('tymon.jwt.auth');
        $auth->setRequest($request);
        $auth->parseToken();
        $auth->getPayload();
    }

    /**
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function test_parse_invalidate()
    {
        $this->expectException(\Tymon\JWTAuth\Exceptions\TokenInvalidException::class);

        $user        = \App\Eloquents\User::take(1)->first();
        $stringToken = self::_generateToken($this, self::_dummyClaims($user->{'id'}));
        $request     = Helpers::createJsonRequest(
            'POST',
            [],
            path_route('student.auth.login.post'),
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer $stringToken",
            ]
        );

        /** @var \Tymon\JWTAuth\JWTAuth $auth */
        $auth = $this->app->make('tymon.jwt.auth');
        $auth->setRequest($request);
        $auth->parseToken();
        $auth->invalidate(true);
        $auth->getPayload();
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

    public static function _dummyClaims(string $subject = 'subject', int $expiration = 60 * 60): array
    {
        return [
            ClaimTable::AUDIENCE => 'audience', //Audience of the token
            ClaimTable::ISSUER => 'issuer', // Issuer of the token
            ClaimTable::SUBJECT => $subject, // Subject of the token
            ClaimTable::ISSUED_AT => time(), // Time when JWT was issued.
            ClaimTable::EXPIRATION => time() + $expiration, // Expiration time
            ClaimTable::AUTH_STAMP => 'stamp',
            ClaimTable::SESSION => 'session',
            ClaimTable::ROLE => 'role',
        ];
    }
}

?>
