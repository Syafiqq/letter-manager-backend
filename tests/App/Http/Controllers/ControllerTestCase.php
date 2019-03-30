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
    /**
     * @param string $uri The URI
     * @param string $method The HTTP method
     * @param array $parameters The query (GET) or request (POST) parameters
     * @param array $cookies The request cookies ($_COOKIE)
     * @param array $files The request files ($_FILES)
     * @param array $server The server parameters ($_SERVER)
     * @param string|resource|null $content The raw body data
     *
     * @return \Illuminate\Http\Request
     */
    protected function createRequest(
        string $method,
        $content,
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
