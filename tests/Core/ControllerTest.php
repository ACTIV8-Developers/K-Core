<?php

use Core\Core\Controller;
use Core\Core\Core;

class ControllerTest extends PHPUnit_Framework_TestCase
{

    public function __construct()
    {
        $this->app = Core::getInstance(__DIR__ . '/../MockApp')
            ->setViewsPath(__DIR__ . '/../MockApp/MockViews')
            ->boot();
    }

	public function testGetContainerObjects()
	{
		$con = new AnotherTestController();
        $con->setApp($this->app);

		$this->assertSame($this->app['request'], $con->getRequest());
		$this->assertSame($this->app['response'], $con->getResponse());
	}

	public function testRender()
	{
		$con = new AnotherTestController();
        $con->setApp($this->app);

		// Try rendering view with no passed data
		$view = 'MockView';
		$result = $con->bufferIt($view);

		// Output string should be same as content of MockView.php file
		$this->expectOutputString(file_get_contents(\Core\Core\Core::getInstance()->getViewsPath().'/'.$view.'.php'));
		echo $result;
	}

	public function testRenderDynamicPage()
	{
		$con = new AnotherTestController();

        $con->setApp($this->app);

		// Used view files
		$view = 'MockDynamicView';
		$compareView = 'MockDynamicViewCompare';

		// Buffer view to nest in main MockView
		$data['content'] = '<div>Test</div>';

		// Output main and nested view
		$res = $con->bufferIt($view, $data);

		// Output string should be same as content of MockNestedViewTest.php file
		$this->expectOutputString(file_get_contents(\Core\Core\Core::getInstance()->getViewsPath().'/'.$compareView.'.php'));
		echo $res;
	}

    public function testBuffer()
    {
        $con = new AnotherTestController();

        $con->setApp($this->app);

        $view = 'MockView';

        $con->renderIt($view, []);
    }

    public function testJson()
    {
        $con = new AnotherTestController();

        $con->setApp($this->app);

        $data = $con->jsonIt(['foo' => 'bar']);

        $this->assertEquals($data, json_encode(['foo' => 'bar']));
    }

    /**
     * @expectedException Core\Core\Exceptions\NotFoundException
     */
    public function testNotFound()
    {
        $con = new AnotherTestController();

        $con->setApp($this->app);

        $con->notFoundIt();
    }

    /**
     * @expectedException  Core\Core\Exceptions\StopException
     */
    public function testStop()
    {
        $con = new AnotherTestController();

        $con->setApp($this->app);

        $con->stopIt();
    }

    public function testInput()
    {
        $con = new AnotherTestController();

        $con->setApp($this->app);

        $con->input();
    }
}

class AnotherTestController extends Controller
{
	public function getRequest()
	{
		return $this->app['request'];
	}

	public function getResponse()
	{
		return $this->response;
	}

    public function bufferIt($view, $data = [])
    {
    	return $this->buffer($view, $data);
    }

    public function renderIt($view, $data = [])
    {
        $this->render($view, $data);
    }

    public function jsonIt($data = [])
    {
        $this->json($data);

        return $this->response->getBody();
    }

    public function notFoundIt()
    {
        $this->notFound();
    }

    public function stopIt()
    {
        $this->stop();
    }

    public function input()
    {
        $this->get('uknown');

        $this->post('uknown');

        $this->cookies('uknown');

        $this->files('uknown');

        $this->get();

        $this->post();

        $this->cookies();

        $this->files();
    }
}