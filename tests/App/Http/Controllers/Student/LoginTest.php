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
        $this->assertEquals($path_route, $this->getRoute());
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
            $this->getRoute()
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
        $request    = $this->createJsonRequest(
            'POST',
            [
                'credential' => '10001',
                'password' => '12345678',
                'role' => 'student'
            ],
            $this->getRoute()
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
        $this->json('POST', $this->getRoute(), [], $this->getHeaders())
            ->seeJson([
                'code' => HttpStatus::UNPROCESSABLE_ENTITY,
            ]);
    }

    public function test_it_should_respond_ok_given_valid_data()
    {
        /** @var $response */
        $this->json('POST', $this->getRoute(),
            [
                'credential' => '10001',
                'password' => '12345678'
            ],
            $this->getHeaders())
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);
    }

    public function test_it_should_not_access_login_page_again()
    {
        /** @var array $token */
        $token = $this->doAuth();
        $this->json('POST', $this->getRoute(),
            [
                'credential' => '10001',
                'password' => '12345678'
            ],
            array_merge($this->getHeaders(), [
                'Authorization' => "Bearer {$token['data']['token']}"
            ]))
            ->seeJson([
                'code' => HttpStatus::FORBIDDEN,
            ]);
    }


    private function getRoute()
    {
        return path_route('student.auth.login.post');
    }

    private function getHeaders()
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * @param null $creds
     * @return array
     */
    private function doAuth($creds = null)
    {
        /** @var $response */
        $this->json('POST', $this->getRoute(),
            $creds == null ? [
                'credential' => '10001',
                'password' => '12345678'
            ] : $creds,
            $this->getHeaders())
            ->seeJson([
                'code' => HttpStatus::OK,
            ]);

        return json_decode($this->response->content(), true);
    }
}

?>
