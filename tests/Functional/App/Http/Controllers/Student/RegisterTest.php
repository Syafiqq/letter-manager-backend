<?php

use App\Model\Util\HttpStatus;

/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 31 March 2019, 10:03 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
class RegisterTest extends ControllerTestCase
{
    private static $users;
    private static $coupons;

    public function test_it_should_fail_register_missing_required_data()
    {
        /** @var \App\Eloquents\User $user */
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
        /** @var \App\Eloquents\User $user */
        $user = self::_getUserRepository()->take(1)->first();
        /** @var \App\Eloquents\Coupon $coupon */
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
        \App\Eloquents\Coupon::insert($coupon->toArray());
    }

    public function test_it_should_fail_register_due_to_already_exists()
    {
        /** @var \App\Eloquents\User $user */
        $user = self::_getUserRepository()->take(1)->first();
        /** @var \App\Eloquents\Coupon $coupon */
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
            self::$coupons = \App\Eloquents\Coupon::all();
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
        \App\Eloquents\User::where('credential', self::_getNewUserCredential())->delete();
    }
}

?>
