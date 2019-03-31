<?php

use App\Model\Util\HttpStatus;

/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 31 March 2019, 6:41 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
class LogoutTest extends TestCase
{
    private static $users;

    public function test_it_should_not_access_before_authenticated()
    {
        $this->json('POST', self::_getRoute(),
            [
            ],
            self::_getHeaders())
            ->seeJson([
                'code' => HttpStatus::UNAUTHORIZED,
            ]);
    }

    public function test_it_should_successfully_logout()
    {
        $actual = self::_getUserRepository()->take(1)->first();
        /** @var array $token */
        $token = self::_doAuth($this, [
            'credential' => $actual->{'credential'},
            'password' => self::_getRightPassword(),
        ]);
        $this->json('POST', self::_getRoute(),
            [
            ],
            array_merge(self::_getHeaders(), [
                'Authorization' => "Bearer {$token['data']['token']}"
            ]))
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);
    }

    public function test_it_should_cannot_access_authorized_area_after_logout()
    {
        $actual = self::_getUserRepository()->take(1)->first();
        /** @var array $token */
        $token = self::_doAuth($this, [
            'credential' => $actual->{'credential'},
            'password' => self::_getRightPassword(),
        ]);
        $this->json('POST', self::_getRoute(),
            [
            ],
            array_merge(self::_getHeaders(), [
                'Authorization' => "Bearer {$token['data']['token']}"
            ]))
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);
        self::_accessAuthorizedArea($this, $token['data']['token'], HttpStatus::UNAUTHORIZED);
    }

    public static function _getRoute()
    {
        return path_route('student.auth.logout.post');
    }

    public static function _getHeaders()
    {
        return LoginTest::_getHeaders();
    }

    /**
     * @param TestCase $case
     * @param array $creds
     * @return array
     */
    public static function _doAuth(TestCase $case, array $creds)
    {
        return LoginTest::_doAuth($case, $creds);
    }

    public static function _getUserRepository()
    {
        if (self::$users == null)
        {
            self::$users = LoginTest::_getUserRepository();
        }

        return self::$users;
    }

    public static function _getRightPassword()
    {
        return LoginTest::_getRightPassword();
    }

    public static function _accessAuthorizedArea(TestCase $case, string $token, int $expectedStatue)
    {
        return RefreshTest::_accessAuthorizedArea($case, $token, $expectedStatue);
    }
}

?>
