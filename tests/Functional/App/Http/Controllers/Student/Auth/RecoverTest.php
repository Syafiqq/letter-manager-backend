<?php

use App\Eloquent\User;
use App\Model\Util\HttpStatus;
use Ramsey\Uuid\Uuid;

/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 31 March 2019, 5:41 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
class RecoverTest extends TestCase
{
    private static $users;

    public function test_it_should_not_access_after_authenticated()
    {
        $actual = self::_getUserRepository()->take(1)->first();
        /** @var array $token */
        $token = self::_doAuth($this, [
            'credential' => $actual->{'credential'},
            'password' => self::_getRightPassword(),
        ]);
        $this->json('PATCH', self::_getRoute(),
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

    public function test_it_should_fail_recover_invalid_required_data()
    {
        $this->json('PATCH', self::_getRoute(),
            [
            ],
            self::_getHeaders())
            ->seeJson([
                'code' => HttpStatus::NOT_FOUND,
            ]);
    }

    /**
     * @throws Exception
     */
    public function test_it_should_fail_recover_missing_required_data()
    {
        $actual        = self::_getUserRepository()->take(1)->first();
        $lost_password = Uuid::uuid4();

        User::where(['id' => $actual->{'id'}])->update(['lost_password' => $lost_password]);
        $this->json('PATCH', self::_getRoute(),
            [
                'token' => $lost_password,
            ],
            self::_getHeaders())
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
        User::where(['id' => $actual->{'id'}])->update(['lost_password' => $actual->{'lost_password'}]);
    }

    /**
     * @throws Exception
     */
    public function test_it_should_success_recover()
    {
        $actual        = self::_getUserRepository()->take(1)->first();
        $lost_password = Uuid::uuid4();

        User::where(['id' => $actual->{'id'}])->update(['lost_password' => $lost_password]);
        $this->json('PATCH', self::_getRoute(),
            [
                'token' => $lost_password,
                'password' => self::_getUpdatedPassword(),
                'password_confirmation' => self::_getUpdatedPassword(),
            ],
            self::_getHeaders())
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);
        $hash = $this->app->make('hash');
        User::where(['id' => $actual->{'id'}])->update([
            'lost_password' => $actual->{'lost_password'},
            'password' => $hash->make(self::_getRightPassword(), [])
        ]);
    }


    public static function _getRoute()
    {
        return path_route('student.auth.recover.patch');
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

    public static function _getUpdatedPassword()
    {
        return 'new password';
    }
}

?>
