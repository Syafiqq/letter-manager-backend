<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 30 March 2019, 11:13 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

class ControllerTestCase extends TestCase
{
    protected function createRequest(
        string $method,
        string $content,
        string $uri = '/test',
        array $server = ['CONTENT_TYPE' => 'application/json'],
        array $parameters = [],
        array $cookies = [],
        array $files = []
    )
    {
        return \Illuminate\Http\Request::createFromBase(\Symfony\Component\HttpFoundation\Request::create($uri, $method, $parameters, $cookies, $files, $server, $content));
    }
}

?>
