<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * Visit the given URI with a JSON request.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     * @param array $files
     * @param array $parameters
     * @param array $cookies
     * @return $this
     */
    public function cJson($method, $uri, array $data = [], array $headers = [], array $files = [], array $parameters = [], array $cookies = [])
    {
        $content = json_encode($data);

        $headers = array_merge([
            'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json',
        ], $headers);

        $this->call(
            $method, $uri, $parameters, $cookies, $files, $this->transformHeadersToServerVars($headers), $content
        );

        return $this;
    }


    /**
     * Visit the given URI with a POST request.
     *
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     * @param array $cookies
     * @param array $files
     * @return $this
     */
    public function cPost($uri, array $data = [], array $headers = [], array $cookies = [], array $files = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('POST', $uri, $data, $cookies, $files, $server);

        return $this;
    }
}
