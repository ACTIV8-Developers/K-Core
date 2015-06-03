<?php

class PaginationTest extends PHPUnit_Framework_TestCase
{
    public function testEmptyPagination()
    {
        $this->assertEquals('', \Core\Pagination\Pagination::getNew([])->create());
    }

    public function testPagination()
    {
        $config = [
            'totalRows'   => 100,
            'perPage'     => 2,
            'curOffset'   => 0,
            'extraParams' => '/id',
            'baseUrl'     => ''
        ];

        $pagination = \Core\Pagination\Pagination::getNew($config)->create();
    }

    public function testLastLinkPagination()
    {
        $config = [
            'totalRows'   => 100,
            'perPage'     => 2,
            'curOffset'   => 0,
            'extraParams' => '/id',
            'baseUrl'     => ''
        ];

        $pagination = \Core\Pagination\Pagination::getNew($config)
                                    ->totalRows(10)
                                    ->perPage(2)
                                    ->numLinks(1)
                                    ->curOffset(5);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidConfig()
    {
        $pagination = \Core\Pagination\Pagination::getNew()
            ->uknown(10);
    }
}