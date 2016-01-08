<?php

use Core\Core\Model;
use Core\Core\Core;

class ModelTest extends PHPUnit_Framework_TestCase
{
    public function testGetSet()
    {
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $model = new AnotherTestModel();

        $model->setContainer($app->getContainer());

        $this->assertSame($model->getContainer(), $app->getContainer());
        $this->assertSame($app->getContainer()['request'], $model->getRequest());
        $this->assertSame($app->getContainer()['response'], $model->getResponse());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $model = new AnotherTestModel;

        $container = new \Core\Container\Container();
        $model->setContainer($container);

        $model->getUknown();
    }
}

class AnotherTestModel extends Model
{
    public function getRequest()
    {
        return $this->container['request'];
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getUknown()
    {
        return $this->uknown;
    }
}