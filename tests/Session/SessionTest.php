<?php

class SessionTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
        $this->session = new \Core\Session\Session();
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 6.1; WOW64)';
	}

	public function testGetSet()
	{
		$_SESSION['foo'] = 'bar';

		$this->assertEquals('bar', $_SESSION['foo']);

		$this->assertEquals('bar', $this->session->get('foo'));

		$this->session->set('bar', 'foo');

		$this->assertEquals('foo', $this->session->get('bar'));

		$this->assertTrue($this->session->has('bar'));

		$this->assertTrue(!$this->session->has('bar2'));

		$this->session->forget('bar');

		$this->assertTrue(!$this->session->has('bar'));

		$_SESSION['foo'] = 'bar';

		$this->assertTrue($this->session->has('foo'));

		$this->session->flush();

		$this->assertTrue(!$this->session->has('foo'));
	}
}