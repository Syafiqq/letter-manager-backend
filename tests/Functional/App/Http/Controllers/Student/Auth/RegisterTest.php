<?php

use App\Eloquent\Coupon;
use App\Eloquent\User;
use App\Model\Util\HttpStatus;

/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 31 March 2019, 10:03 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
class RegisterTest extends TestCase
{
    private static $users;
    private static $coupons;

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

    public function test_it_should_fail_register_missing_required_data()
    {
        /** @var User $user */
        $user = self::_getUserRepository()->take(1)->first();
        /** @var $response */
        $this->json('POST', self::_getRoute(),
            [
                'credential' => self::_getNewUserCredential(),
                'email' => $user->{'email'},
                'name' => $user->{'name'},
                'gender' => $user->{'gender'},
                'role' => $user->{'role'},
                'password' => self::_getRightPassword(),
                'password_confirmation' => self::_getRightPassword(),
            ],
            self::_getHeaders())
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
    }

    public function test_it_should_success_register()
    {
        /** @var User $user */
        $user = self::_getUserRepository()->take(1)->first();
        /** @var Coupon $coupon */
        $coupon = self::_getCouponRepository()->firstWhere('usage', $user->{'role'});
        /** @var $response */
        $this->json('POST', self::_getRoute(),
            [
                'credential' => self::_getNewUserCredential(),
                'email' => $user->{'email'},
                'name' => $user->{'name'},
                'gender' => $user->{'gender'},
                'role' => $user->{'role'},
                'password' => self::_getRightPassword(),
                'password_confirmation' => self::_getRightPassword(),
                'token' => $coupon->{'coupon'}
            ],
            self::_getHeaders())
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);
        self::_removeNewUser();
        Coupon::insert($coupon->toArray());
    }

    public function test_it_should_fail_register_due_to_already_exists()
    {
        /** @var User $user */
        $user = self::_getUserRepository()->take(1)->first();
        /** @var Coupon $coupon */
        $coupon = self::_getCouponRepository()->firstWhere('usage', $user->{'role'});
        /** @var $response */
        $this->json('POST', self::_getRoute(),
            [
                'credential' => $user->{'credential'},
                'email' => $user->{'email'},
                'name' => $user->{'name'},
                'gender' => $user->{'gender'},
                'role' => $user->{'role'},
                'password' => self::_getRightPassword(),
                'password_confirmation' => self::_getRightPassword(),
                'token' => $coupon->{'coupon'}
            ],
            self::_getHeaders())
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
    }

    public static function _getRoute()
    {
        return path_route('student.auth.register.post');
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

    public static function _getCouponRepository()
    {
        if (self::$coupons == null)
        {
            self::$coupons = Coupon::all();
        }

        return self::$coupons;
    }

    public static function _getRightPassword()
    {
        return LoginTest::_getRightPassword();
    }

    public static function _getNewUserCredential()
    {
        return '10011';
    }

    public static function _removeNewUser()
    {
        User::where('credential', self::_getNewUserCredential())->delete();
    }
}

?>
