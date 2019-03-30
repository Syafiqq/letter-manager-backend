<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 28 March 2019, 5:59 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

class ControllerTest extends ControllerTestCase
{
    public function testConstructor()
    {
        $factory    = $this->app->make('tymon.jwt.payload.factory');
        $hash       = $this->app->make('hash');
        $auth       = $this->app->make('tymon.jwt.auth');
        $controller = $this->app->make(\App\Http\Controllers\Student\Auth::class);

        $this->assertNotNull($factory);
        $this->assertNotNull($hash);
        $this->assertNotNull($auth);
        $this->assertNotNull($controller);
    }
}

?>
