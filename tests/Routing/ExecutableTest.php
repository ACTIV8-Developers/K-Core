<?php

class ExecutableTest extends \PHPUnit\Framework\TestCase
{
    public function testGettersSetters()
    {
        $e = new \Core\Routing\Executable('TestClass', 'getFoo', ['test']);

        $params = ['foo'];

        $e->addParams($params);

        $this->assertEquals($e(), 'test'.'foo');
    }
}

class TestClass extends \Core\Core\Controller
{
    public function getFoo($param1, $param2)
    {
        return $param1 . $param2;
    }
}