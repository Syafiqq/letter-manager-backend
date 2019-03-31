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
        $claims = self::_dummyClaims();

        $payload = $factory->customClaims($claims)->make();

        $encode = $auth->manager()->encode($payload);
        echo $encode->get();
    }

    public function test_parse()
    {
        $this->assertTrue(true);
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

        try
        {
            if (!$user = $auth->parseToken()->authenticate())
            {
                echo 'Not Found';
            }
        }
        catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e)
        {
            echo $e->getMessage();
        }
        catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e)
        {
            echo $e->getMessage();
        }
        catch (Tymon\JWTAuth\Exceptions\JWTException $e)
        {
            echo $e->getMessage();
        }

        $payload = $auth->getPayload();
        echo $payload;
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
