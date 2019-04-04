<?php

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $content = json_decode($this->response->getContent(), true);
        $this->assertIsArray($content);
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('version', $content['data']);
        $this->assertEquals($this->app->version(), $content['data']['version']);
    }
}
