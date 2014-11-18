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

		// Set Content
		$response->setContent($test);

		// Append Content
		$response->addContent($append);

		$this->assertEquals($response->getContent(), $test.$append);
	}
}