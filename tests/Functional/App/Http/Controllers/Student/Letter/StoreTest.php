<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 02 April 2019, 6:16 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

class StoreTest extends TestCase
{
    private static $users;

    public static function _getRoute()
    {
        return path_route('student.letter.store.post');
    }

    public static function _getHeaders($authorization = 'empty')
    {
        return array_merge(self::_getAuthHeaders(), [
            'Authorization' => "Bearer $authorization"
        ]);
    }

    public static function _getAuthHeaders()
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
