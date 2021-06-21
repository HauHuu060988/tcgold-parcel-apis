<?php

class ExampleTest extends TestCase
{
    /* NOTE
     * Get api result to debug.
     * $res->response->getContent()
     */

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }
}
