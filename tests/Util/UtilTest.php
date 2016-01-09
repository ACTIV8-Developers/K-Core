<?php

use Core\Util\Date;
use Core\Util\Util;

class UtilTest extends PHPUnit_Framework_TestCase
{
	public function testBaseUrl()
	{
    	// Test assets get (remember PUBLIC folder is set to 'public')
    	$this->assertEquals(Util::base('foo'), 'http://localhost/www/foo');
    	$this->assertEquals(Util::css('foo.css'), 'http://localhost/www/public/css/foo.css');
    	$this->assertEquals(Util::js('foo.js'), 'http://localhost/www/public/js/foo.js');
    	$this->assertEquals(Util::img('foo.png'), 'http://localhost/www/public/images/foo.png');
        // Test cached
        $this->assertEquals(Util::base('foo'), 'http://localhost/www/foo');
	}

    public function testRedirect()
    {

    }
}