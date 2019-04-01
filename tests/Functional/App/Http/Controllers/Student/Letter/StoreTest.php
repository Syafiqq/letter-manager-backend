<?php

use App\Model\Util\HttpStatus;
use Illuminate\Http\UploadedFile;

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

    public function test_it_should_failed_store_due_to_empty_data()
    {
        $user = self::_getUserRepository()->take(1)->first();
        /** @var array $response */
        $response = self::_doAuth($this, [
            'credential' => $user->{'credential'},
            'password' => self::_getRightPassword(),
        ]);
        $this->json('POST', self::_getRoute(),
            [],
            self::_getHeaders($response['data']['token']))
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
    }

    public static function _getRoute()
    {
        return path_route('student.letter.store.post');
    }

    public static function _getHeaders($authorization = 'empty')
    {
        return array_merge(LoginTest::_getHeaders(), [
            'Authorization' => "Bearer $authorization"
        ]);
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

    /**
     * @param int $size
     * @return UploadedFile
     * @throws Exception
     */
    public static function _generatePdfFile($size = 1): \Illuminate\Http\UploadedFile
    {
        return UploadedFile::fake()->create(\Ramsey\Uuid\Uuid::uuid4()->toString() . '.pdf', $size);
    }
}

?>
