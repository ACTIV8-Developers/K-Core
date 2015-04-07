<?php
use Core\Core\Core;
use Core\Core\Model;

class BaseModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
	public function testDb() // Will throw exception since it cant connect to db at this momment.
	{
        $app = Core::getInstance(__DIR__.'/../MockApp')
                            ->boot();
        
		$mod = new MockModel();

		$this->assertInstanceOf('Core\Database\Database', $mod->getDatabase());

		$this->assertSame($app['db.default'], $mod->getDatabase());
	}
}


class MockModel extends Model
{
    public function getDatabase()
    {
        return $this->db;
    }
}