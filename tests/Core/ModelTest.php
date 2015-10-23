<?php

use Core\Core\Model;
use Core\Core\Core;

class ModelTest extends PHPUnit_Framework_TestCase
{
    public function testGetSet()
    {
        $app = Core::getInstance(__DIR__ . '/../MockApp')
            ->setViewsPath(__DIR__ . '/../MockApp/MockViews')
            ->boot();

        $model = new AnotherTestModel();

        $model->setApp($app);

        $this->assertSame($model->getApp(), $app);
        $this->assertSame($app['request'], $model->getRequest());
        $this->assertSame($app['response'], $model->getResponse());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $model = new AnotherTestModel;

        $container = new \Core\Container\Container();
        $model->setApp($container);

        $model->getUknown();
    }
}

class AnotherTestModel extends Model
{
    public function getRequest()
    {
        return $this->app['request'];
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