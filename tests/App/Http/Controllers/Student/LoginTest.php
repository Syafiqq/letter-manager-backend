<?php

use App\Model\Util\HttpStatus;

/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 30 March 2019, 10:52 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
class LoginTest extends ControllerTestCase
{
    private static $repos;

    /**
     * LoginTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function test_get_login_path()
    {
        $this->assertTrue(true);
        $domain     = env('APP_URL');
        $route      = route('student.auth.login.post', []);
        $path_route = str_replace($domain, '', $route);
        $this->assertEquals($path_route, path_route('student.auth.login.post'));
        $this->assertEquals($path_route, $this->_getRoute());
    }

    /**
     * @throws Exception
     */
    public function test_login_with_empty_data()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request = $this->createJsonRequest(
            'POST',
            null,
            $this->_getRoute()
        );
        /** @var \App\Http\Controllers\Student\Auth $controller */
        $controller = $this->app->make(\App\Http\Controllers\Student\Auth::class);

        $controller->postLogin($request);

        $this->assertNotNull($controller);
    }

    /**
     * @throws Exception
     */
    public function test_login_with_right_data()
    {
        $actual     = $this->_getUserRepository()->take(1)->first();
        $request    = $this->createJsonRequest(
            'POST',
            [
                'credential' => $actual->{'credential'},
                'password' => $this->_getARightPassword(),
                'role' => 'student'
            ],
            $this->_getRoute()
        );
        $controller = $this->app->make(\App\Http\Controllers\Student\Auth::class);

        $response = $controller->postLogin($request);
        $this->assertEquals(HttpStatus::OK, $response->status());
        $data = $response->getData(true);
        $this->assertArrayHasKey('token', $data['data']);
        $this->assertArrayHasKey('type', $data['data']);
        $this->assertArrayHasKey('expires', $data['data']);
        $this->assertNotNull($controller);
    }

    public function test_it_should_respond_unprocessable_entity_given_no_data()
    {
        /** @var $response */
        $this->json('POST', $this->_getRoute(), [], $this->_getHeaders())
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
    }

    public function test_it_should_respond_ok_given_valid_data()
    {
        $actual = $this->_getUserRepository()->take(1)->first();
        /** @var $response */
        $this->json('POST', $this->_getRoute(),
            [
                'credential' => $actual->{'credential'},
                'password' => $this->_getARightPassword(),
            ],
            $this->_getHeaders())
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);
    }

    public function test_it_should_respond_not_found_given_invalid_data()
    {
        $actual = $this->_getUserRepository()->take(1)->first();
        /** @var $response */
        $this->json('POST', $this->_getRoute(),
            [
                'credential' => $actual->{'credential'},
                'password' => $this->_getAWrongPassword(),
            ],
            $this->_getHeaders())
            ->seeJson([
                'code' => HttpStatus::NOT_FOUND,
            ]);
    }

    public function test_it_should_not_access_login_page_again()
    {
        $actual = $this->_getUserRepository()->take(1)->first();
        /** @var array $token */
        $token = $this->_doAuth([
            'credential' => $actual->{'credential'},
            'password' => $this->_getARightPassword(),
        ]);
        $this->json('POST', $this->_getRoute(),
            [
                'credential' => $actual->{'credential'},
                'password' => $this->_getARightPassword(),
            ],
            array_merge($this->_getHeaders(), [
                'Authorization' => "Bearer {$token['data']['token']}"
            ]))
            ->seeJson([
                'code' => HttpStatus::FORBIDDEN,
            ]);
    }


    private function _getRoute()
    {
        return path_route('student.auth.login.post');
    }

    private function _getHeaders()
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * @param array $creds
     * @return array
     */
    private function _doAuth(array $creds)
    {
        /** @var $response */
        $this->json('POST', $this->_getRoute(),
            $creds,
            $this->_getHeaders())
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);

        return json_decode($this->response->content(), true);
    }

    private function _getUserRepository()
    {
        if (self::$repos == null)
        {
            self::$repos = \App\Eloquents\User::all();
        }

        return self::$repos;
    }

    private function _getARightPassword()
    {
        return '12345678';
    }

    private function _getAWrongPassword()
    {
        return '123456789';
    }
}

?>
