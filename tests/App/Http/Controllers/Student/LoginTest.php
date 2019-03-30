<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 30 March 2019, 10:52 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

class LoginTest extends ControllerTestCase
{

    private $route;

    /**
     * LoginTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->route = path_route('student.auth.login.post');
    }

    public function test_get_login_path()
    {
        $domain     = env('APP_URL');
        $route      = route('student.auth.login.post', []);
        $path_route = str_replace($domain, '', $route);
        $this->assertEquals($path_route, path_route('student.auth.login.post'));
    }

    public function test_login_with_empty_data()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request    = $this->createJsonRequest(
            'POST',
            null,
            $this->route
        );
        $controller = $this->app->make(\App\Http\Controllers\Student\Auth::class);

        $controller->postLogin($request);

        $this->assertNotNull($controller);
    }

    public function test_login_with_right_data()
    {
        $request    = $this->createJsonRequest(
            'POST',
            [
                'credential' => '10001',
                'password' => '12345678',
                'role' => 'student'
            ],
            $this->route
        );
        $controller = $this->app->make(\App\Http\Controllers\Student\Auth::class);

        $response = $controller->postLogin($request);
        $this->assertEquals(200, $response->status());
        $data = $response->getData(true);
        $this->assertArrayHasKey('token', $data['data']);
        $this->assertArrayHasKey('type', $data['data']);
        $this->assertArrayHasKey('expires', $data['data']);
        $this->assertNotNull($controller);
    }
}

?>
