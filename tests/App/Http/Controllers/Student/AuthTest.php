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
    const serverParam = [
        'CONTENT_TYPE' => 'application/json',
        'ACCEPT' => 'application/json'
    ];

    public function testLoginWithEmptyData()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request    = $this->createRequest(
            'POST',
            null,
            '/student/auth/login',
            self::serverParam
        );
        $controller = $this->app->make(\App\Http\Controllers\Student\Auth::class);

        $response = $controller->postLogin($request);

        $this->assertNotNull($controller);
    }
}

?>
