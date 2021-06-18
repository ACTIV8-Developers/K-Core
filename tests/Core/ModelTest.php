<?php

use Core\Core\Model;

class ModelTest extends \PHPUnit\Framework\TestCase
{
    public function testGetSet()
    {
        $app = new \Core\Core\Core(new \Core\Container\Container(__DIR__ . '/../MockApp'));

        $model = new AnotherTestModel();

        $model->setContainer($app->getContainer());

        $this->assertSame($model->getContainer(), $app->getContainer());
        $this->assertSame($app->getContainer()['request'], $model->getRequest());
    }

    public function testInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);

        $model = new AnotherTestModel;

        $container = new \Core\Container\Container(__DIR__ . '/../MockApp');
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