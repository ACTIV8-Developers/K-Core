<?php

use \Core\Http\Response;

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
}