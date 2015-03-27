<?php
use Core\Http\Response;
use Core\Core\Controller;

class BaseControllerTest extends PHPUnit_Framework_TestCase
{
	public function testGet()
	{
		$app = new \Core\Core\Core();

		$con = new MockController();

		$this->assertSame($app->getContainer()['request'], $con->getRequest());
		$this->assertSame($app->getContainer()['response'], $con->getResponse());
		$this->assertSame($app->getContainer()['session'], $con->getSession());
	}

	public function testRender()
	{
		$con = new MockController();

		// Try rendering view with no passed data
		$view = 'MockView';
		$result = $con->bufferIt($view);

		// Output string should be same as content of MockView.php file
		$this->expectOutputString(file_get_contents(APPVIEW.$view.'.php'));
		echo $result;
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

		// Output string shoudl be same as content of MockNestedViewTest.php file
		$this->expectOutputString(file_get_contents(APPVIEW.$compareView.'.php'));
		echo $res;
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
		return $this->container['request'];
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