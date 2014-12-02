<?php
use \Core\Http\Response;
use \Core\Core\Controller;

// Define location of mock views to this dir
define('APPVIEW', __DIR__.'/MockViews/');

class BaseControllerTest extends PHPUnit_Framework_TestCase
{
	public function testGet()
	{
		// Minimal request needed information.
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI'] = '';

		$con = new MockController();

		$this->assertSame(\Core\Core\Core::getInstance()['request'], $con->getRequest());
		$this->assertSame(\Core\Core\Core::getInstance()['response'], $con->getResponse());
		$this->assertSame(\Core\Core\Core::getInstance()['session'], $con->getSession());
	}

	public function testRender()
	{
		$con = new MockController();

		// Try rendering view with no passed data
		$view = 'MockView';
		$result = $con->bufferIt($view);

		// Output string should be same as content of MockView.php file
		echo $result;
		$this->expectOutputString(file_get_contents(APPVIEW.$view.'.php'));
	}

	public function testRenderDynamicPage()
	{
		$con = new MockController();

		// Used view files
		$view = 'MockDynamicView';
		$compareView = 'MockDynamicViewCompare';

		// Buffer view to nest in main MockView
		$data['content'] = '<div>Test</div>';

		// Output main and nested view
		$res = $con->bufferIt($view, $data);

		echo $res;

		// Output string shoudl be same as content of MockNestedViewTest.php file
		$this->expectOutputString(file_get_contents(APPVIEW.$compareView.'.php'));
	}
}

class MockController extends Controller
{
	public function getSession()
	{
		return $this->getValue('session');
	}

	public function getRequest()
	{
		return $this->app['request'];
	}

	public function getResponse()
	{
		return $this->getValue('response');
	}

    public function bufferIt($view, $data = [])
    {
    	return $this->buffer($view, $data);
    }
}