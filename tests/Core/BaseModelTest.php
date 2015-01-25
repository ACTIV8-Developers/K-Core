<?php
use \Core\Core\Model;

class BaseModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
	public function testDb() // Will throw exception since it cant connect to db at this momment.
	{
		$mod = new MockModel();

		$this->assertInstanceOf('Core\Database\Database', $mod->getDatabase());

		$this->assertSame(\Core\Core\Core::getInstance()['db.default'], $mod->getDatabase());
	}
}


class MockModel extends Model
{
    public function getDatabase()
    {
        return $this->db;
    }
}