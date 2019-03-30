<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 30 March 2019, 10:52 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

class AuthTest extends ControllerTestCase
{
    public function testLoginWithEmptyData()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request    = $this->createJsonRequest(
            'POST',
            null,
            '/student/auth/login'
        );
        $controller = $this->app->make(\App\Http\Controllers\Student\Auth::class);

        $controller->postLogin($request);

        $this->assertNotNull($controller);
    }

    public function testLoginWithRightData()
    {
        $request    = $this->createJsonRequest(
            'POST',
            [
                'credential' => '10001',
                'password' => '12345678',
                'role' => 'student'
            ],
            '/student/auth/login'
        );
        $controller = $this->app->make(\App\Http\Controllers\Student\Auth::class);

        $response = $controller->postLogin($request);
        $this->assertEquals(200, $response->status());

        $this->assertNotNull($controller);
    }
}

?>
