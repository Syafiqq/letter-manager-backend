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

    public function test_create_file()
    {
        $this->assertTrue(true);
        $file = new UploadedFile(storage_path('app/public') . '/letters/20190328/example-letter-01.pdf', 'example-letter-01.pdf', 'application/pdf', null, null, true);
        $this->assertNotNull($file);
    }

    public function test_it_should_failed_store_due_to_empty_data()
    {
        $user = self::_getUserRepository()->take(1)->first();
        /** @var array $response */
        $token = self::_doAuth($this, $user);
        $this->json('POST', self::_getRoute(),
            [],
            self::_getHeaders($token))
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
    }

    /**
     * @throws Exception
     */
    public function test_it_should_success_store_letter()
    {
        $file = self::_generatePdfFile(10);
        //$file = new UploadedFile(storage_path('app/public') . '/letters/20190328/example-letter-01.pdf', 'example-letter-01.pdf', 'application/pdf', null, null, false);
        $user = self::_getUserRepository()->take(1)->first();
        /** @var array $response */
        $token = self::_doAuth($this, $user);
        echo $token . "\n";
        $this->post(self::_getRoute(),
            [
                'title' => 'Title New',
                'code' => 'Code New',
                'index' => 'Index New',
                'number' => 'Number New',
                'subject' => 'Subject New',
                'date' => '2019-03-28 04:04:04',
                'kind' => \App\Eloquents\Letter::letterKind[0],
                'upload' => $file
            ],
            self::_getHeaders($token))
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
        echo vj($this->response->content());
    }

    public static function _getRoute()
    {
        return path_route('student.letter.store.post');
    }

    public static function _getHeaders(string $authorization = 'empty')
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'Authorization' => "Bearer $authorization",
        ];
    }

    /**
     * @param TestCase $case
     * @param \App\Eloquents\User $user
     * @return string
     */
    public static function _doAuth(TestCase $case, \App\Eloquents\User $user): string
    {
        return LoginTest::_doAuth($case, [
            'credential' => $user->{'credential'},
            'password' => LoginTest::_getRightPassword(),
        ])['data']['token'];
    }

    public static function _getUserRepository()
    {
        if (self::$users == null)
        {
            self::$users = LoginTest::_getUserRepository();
        }

        return self::$users;
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
