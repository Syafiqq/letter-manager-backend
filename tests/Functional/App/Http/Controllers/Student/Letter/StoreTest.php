<?php

use App\Eloquent\Letter;
use App\Eloquent\User;
use App\Model\Util\HttpStatus;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;

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
        $file   = fopen(storage_path('app/public') . '/letters/20190328/example-letter-01.pdf', 'r');
        $upload = new File('example-letter-01.pdf', $file);
        $user   = self::_getUserRepository()->take(1)->first();
        $title  = 'Title New';
        /** @var array $response */
        $token = self::_doAuth($this, $user);
        $this->cPost(self::_getRoute(),
            [
                'title' => $title,
                'code' => 'Code New',
                'index' => 'Index New',
                'number' => 'Number New',
                'subject' => 'Subject New',
                'date' => '2019-03-28 04:04:04',
                'kind' => Letter::letterKind[0],
            ],
            self::_getHeaders($token),
            [], [
                'upload' => $upload
            ])
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);
        $resultPath = storage_path('app/public') . '/letters/20190328/' . $upload->hashName();
        $this->assertNotNull(Letter::where('title', $title)->first());
        $this->assertFileExists($resultPath);
        fclose($file);
        @unlink($resultPath);
        Letter::where('title', $title)->delete();
    }

    /**
     * @throws Exception
     */
    public function test_it_should_fail_store_due_to_invalid_extension()
    {
        $file   = fopen(storage_path('app') . '/.gitignore', 'r');
        $upload = new File('.gitignore', $file);
        $user   = self::_getUserRepository()->take(1)->first();
        $title  = 'Title New';
        /** @var array $response */
        $token = self::_doAuth($this, $user);
        $this->cPost(self::_getRoute(),
            [
                'title' => $title,
                'code' => 'Code New',
                'index' => 'Index New',
                'number' => 'Number New',
                'subject' => 'Subject New',
                'date' => '2019-03-28 04:04:04',
                'kind' => Letter::letterKind[0],
            ],
            self::_getHeaders($token),
            [], [
                'upload' => $upload
            ])
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
    }

    /**
     * @throws Exception
     */
    public function test_it_should_fail_store_due_to_incomplete_data()
    {
        $file   = fopen(storage_path('app') . '/.gitignore', 'r');
        $upload = new File('.gitignore', $file);
        $user   = self::_getUserRepository()->take(1)->first();
        $title  = 'Title New';
        /** @var array $response */
        $token = self::_doAuth($this, $user);
        $this->cPost(self::_getRoute(),
            [
                'title' => $title,
                'code' => 'Code New',
                'index' => 'Index New',
                'number' => 'Number New',
                'subject' => 'Subject New',
                'date' => '2019-03-28 04:04:04',
            ],
            self::_getHeaders($token),
            [], [
                'upload' => $upload
            ])
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
    }

    public static function _getRoute()
    {
        return path_route('student.letter.store.post');
    }

    public static function _getHeaders(string $authorization = 'empty')
    {
        return [
            'Content-Type' => 'multipart/form-data',
            'Accept' => 'application/json',
            'Authorization' => "Bearer $authorization",
        ];
    }

    /**
     * @param TestCase $case
     * @param User $user
     * @return string
     */
    public static function _doAuth(TestCase $case, User $user): string
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
    public static function _generatePdfFile($size = 1): UploadedFile
    {
        return UploadedFile::fake()->create(Uuid::uuid4()->toString() . '.pdf', $size);
    }
}

?>
