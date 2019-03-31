<?php
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

    public static function _getRightPassword()
    {
        return LoginTest::_getRightPassword();
    }
}

?>
