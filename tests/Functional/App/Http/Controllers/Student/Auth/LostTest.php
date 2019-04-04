<?php

use App\Eloquent\User;
use App\Model\Util\HttpStatus;

/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 31 March 2019, 5:00 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
class LostTest extends TestCase
{
    private static $users;

    public function test_it_should_fail_lost_missing_required_data()
    {
        /** @var $response */
        $this->json('POST', self::_getRoute(),
            [],
            self::_getHeaders())
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
    }

    public function test_it_should_not_access_after_authenticated()
    {
        $actual = self::_getUserRepository()->take(1)->first();
        /** @var array $token */
        $token = self::_doAuth($this, [
            'credential' => $actual->{'credential'},
            'password' => self::_getRightPassword(),
        ]);
        $this->json('POST', self::_getRoute(),
            [
                'credential' => $actual->{'credential'},
                'password' => self::_getRightPassword(),
            ],
            array_merge(self::_getHeaders(), [
                'Authorization' => "Bearer {$token['data']['token']}"
            ]))
            ->seeJson([
                'code' => HttpStatus::FORBIDDEN,
            ]);
    }

    public function test_it_should_success_operate_lost()
    {
        $actual = self::_getUserRepository()->take(1)->first();
        $this->json('POST', self::_getRoute(),
            [
                'credential' => $actual->{'credential'},
            ],
            self::_getHeaders())
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);
        $data = json_decode($this->response->content(), true)['data'];
        $this->assertArrayHasKey('recovery_token', $data);
        User::where('credential', $actual->{'credential'})->update(['lost_password' => $actual->{'lost_password'}]);
    }

    public static function _getRoute()
    {
        return path_route('student.auth.lost.post');
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
}

?>
