<?php

use Core\Http\Response;

class ResponseTest extends PHPUnit_Framework_TestCase
{
	public function testDisplay()
	{
		// Create instance of response class
		$response = new Response();

		// Test HTML
		$test = '<h1>Test</h2>';
		$append = '<div>Message</div>';

		// Set Body
		$response->setBody($test);

		// Append Body
		$response->addBody($append);

		$this->assertEquals($response->getBody(), $test.$append);
	}

    public function testStatusCode()
    {
        $response = new Response();

        $response->setStatusCode(200);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testProtocolVersion()
    {
        $response = new Response();

        $response->setProtocolVersion('HTTP/1.1');

        $this->assertEquals('HTTP/1.1', $response->getProtocolVersion());
    }

    public function testHeaders()
    {
        $response = new Response();

        $response->setHeader('Content-Length', 256);

        $this->assertEquals(256, $response->getHeader('Content-Length'));

        $this->assertEquals(['Content-Length'=>256], $response->getHeaders());
    }

    public function testCookies()
    {
        $response = new Response();

        $response->setCookie('mycookie', 'foo');
    }

    public function testSend()
    {
        $response = new Response();

        $response->setCookie('foo', 'bar');

        $response->setHeader('Content-Length', 0);

        $response->send();
    }
}