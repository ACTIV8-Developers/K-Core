<?php

use Core\Core\Core;
use Core\Core\Controller;

class ControllerTest extends PHPUnit_Framework_TestCase
{

    public function __construct()
    {
        $core = Core::getInstance(__DIR__ . '/../MockApp');

        $this->container = $core->getContainer();
        $config = $this->container['config'];
        $config['viewsPath'] = __DIR__ . '/../MockApp/MockViews';
        $this->container['config'] = $config;
    }

	public function testGetContainerObjects()
	{
		$con = new AnotherTestController();
        $con->setContainer($this->container);

		$this->assertSame($this->container['request'], $con->getRequest());
		$this->assertSame($this->container['response'], $con->getResponse());
	}

	public function testRender()
	{
		$con = new AnotherTestController();
        $con->setContainer($this->container);


		// Try rendering view with no passed data
		$view = 'MockView';
		$result = $con->bufferIt($view);

		// Output string should be same as content of MockView.php file
		$this->expectOutputString(file_get_contents($this->container['config']['viewsPath'].'/'.$view.'.php'));
		echo $result;
	}

	public function testRenderDynamicPage()
	{
		$con = new AnotherTestController();

        $con->setContainer($this->container);

		// Used view files
		$view = 'MockDynamicView';
		$compareView = 'MockDynamicViewCompare';

		// Buffer view to nest in main MockView
		$data['content'] = '<div>Test</div>';

		// Output main and nested view
		$res = $con->bufferIt($view, $data);

		// Output string should be same as content of MockNestedViewTest.php file
		$this->expectOutputString(file_get_contents($this->container['config']['viewsPath'].'/'.$compareView.'.php'));
		echo $res;
	}

    public function testBuffer()
    {
        $con = new AnotherTestController();

        $con->setContainer($this->container);

        $view = 'MockView';

        $con->renderIt($view, []);
    }

    public function testJson()
    {
        $con = new AnotherTestController();

        $con->setContainer($this->container);

        $data = $con->jsonIt(['foo' => 'bar']);

        $this->assertEquals($data, json_encode(['foo' => 'bar']));
    }

    /**
     * @expectedException Core\Core\Exceptions\NotFoundException
     */
    public function testNotFound()
    {
        $con = new AnotherTestController();

        $con->setContainer($this->container);

        $con->notFoundIt();
    }

    /**
     * @expectedException Core\Core\Exceptions\StopException
     */
    public function testStop()
    {
        $con = new AnotherTestController();

        $con->setContainer($this->container);

        $con->stopIt();
    }

    public function testInput()
    {
        $con = new AnotherTestController();

        $con->setContainer($this->container);

        $con->input();
    }

    public function testContainer()
    {
        $c = new AnotherTestController;

        $container = new \Core\Container\Container();
        $c->setContainer($container);

        $this->assertEquals($container, $c->getApp());
;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $c = new AnotherTestController;

        $container = new \Core\Container\Container();
        $c->setContainer($container);

        $c->getUknown();
    }
}

class AnotherTestController extends Controller
{
	public function getRequest()
	{
		return $this->container['request'];
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

    public function getFoo()
    {
        return $this->foo;
    }

    public function getFoo2()
    {
        return $this->container['foo'];
    }

    public function getUknown()
    {
        return $this->uknown;
    }

    public function getApp()
    {
        return $this->container;
    }
}