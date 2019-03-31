<?php

use App\Model\Util\HttpStatus;

/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 31 March 2019, 11:50 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
class RefreshTest extends TestCase
{
    private static $users;

    /**
     * @param TestCase $case
     * @param array $creds
     * @return array
     */
    public static function _doAuth(TestCase $case, array $creds)
    {
        return LoginTest::_doAuth($case, $creds);
    }

    public function test_it_should_not_access_login_page_again()
    {
        $actual = self::_getUserRepository()->take(1)->first();
        /** @var array $token */
        $tokenString = JwtTest::_generateToken($this, JwtTest::_dummyClaims($actual->{'id'}));
        $this->json('POST', self::_getLoginRoute(),
            [
                'credential' => $actual->{'credential'},
                'password' => self::_getRightPassword(),
            ],
            array_merge(self::_getHeaders(), [
                'Authorization' => "Bearer {$tokenString}"
            ]))
            ->seeJson([
                'code' => HttpStatus::FORBIDDEN,
            ]);
    }

    public function test_it_should_successfully_refresh_token()
    {
        $actual = self::_getUserRepository()->take(1)->first();
        /** @var array $token */
        $tokenString = JwtTest::_generateToken($this, JwtTest::_dummyClaims($actual->{'id'}));
        $this->json('POST', self::_getRoute(),
            [],
            array_merge(self::_getHeaders(), [
                'Authorization' => "Bearer {$tokenString}"
            ]))
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);
        $this->assertTrue(!is_null($this->response->headers->get('authorization')));
        $this->assertTrue(strlen($this->response->headers->get('authorization')) != 0);
    }

    public function test_it_should_successfully_refresh_expired_token()
    {
        $actual = self::_getUserRepository()->take(1)->first();
        /** @var array $token */
        $tokenString = JwtTest::_generateToken($this, JwtTest::_dummyClaims($actual->{'id'}, 1));
        sleep(2);
        self::_accessAuthorizedArea($this, $tokenString, HttpStatus::UNAUTHORIZED);
        $this->json('POST', self::_getRoute(),
            [],
            array_merge(self::_getHeaders(), [
                'Authorization' => "Bearer {$tokenString}"
            ]))
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);
        $tokenString = $this->response->headers->get('authorization');
        $this->assertTrue(!is_null($tokenString));
        $this->assertTrue(strlen($tokenString) != 0);
        $tokenString = substr($tokenString, 7);
        self::_accessAuthorizedArea($this, $tokenString, HttpStatus::OK);
    }

    public static function _getUserRepository()
    {
        if (self::$users == null)
        {
            self::$users = LoginTest::_getUserRepository();
        }

        return self::$users;
    }

    public static function _getLoginRoute()
    {
        return LoginTest::_getRoute();
    }

    public static function _getRightPassword()
    {
        return LoginTest::_getRightPassword();
    }

    public static function _getHeaders()
    {
        return LoginTest::_getHeaders();
    }

    public static function _getRoute()
    {
        return path_route('student.auth.refresh.post');
    }

    public static function _accessAuthorizedArea(TestCase $case, string $token, int $expectedStatue)
    {
        $case->json('POST', '/',
            [],
            array_merge(self::_getHeaders(), [
                'Authorization' => "Bearer {$token}"
            ]))
            ->seeJson([
                'code' => $expectedStatue,
            ]);
    }
}

?>
