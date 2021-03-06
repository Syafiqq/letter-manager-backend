<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 31 March 2019, 12:54 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

class Helpers
{
    /**
     * @param string $uri The URI
     * @param string $method The HTTP method
     * @param array $parameters The query (GET) or request (POST) parameters
     * @param array $cookies The request cookies ($_COOKIE)
     * @param array $files The request files ($_FILES)
     * @param array $server The server parameters ($_SERVER)
     * @param string|resource|array|null $content The raw body data
     *
     * @return \Illuminate\Http\Request
     */
    public static function createJsonRequest(
        string $method,
        $content,
        string $uri,
        array $server = [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json',
        ],
        array $parameters = [],
        array $cookies = [],
        array $files = []
    )
    {
        if (is_null($content))
        {
            $content = [];
        }
        if (!is_array($content))
        {
            $content = [$content];
        }

        return self::createRequest($method, json_encode($content), $uri, $server, $parameters, $cookies, $files);
    }

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
    public static function createRequest(
        string $method,
        $content,
        string $uri,
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
