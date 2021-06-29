<?php

class PaginationTest extends \PHPUnit\Framework\TestCase
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

        $this->assertEquals('', \Core\Pagination\Pagination::getNew([])->create());
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
                                    ->curOffset(5)->create();

        $this->assertIsString($pagination);

        //if current link in last set of links
        $pagination = \Core\Pagination\Pagination::getNew()
            ->totalRows(10)
            ->perPage(1)
            ->numLinks(1)
            ->curOffset(10)->create();

        $this->assertIsString($pagination);

    }

    public function testInvalidConfig()
    {
        $this->expectException(InvalidArgumentException::class);

        $pagination = \Core\Pagination\Pagination::getNew()
            ->uknown(10);
    }
}