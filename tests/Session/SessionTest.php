<?php

class SessionTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
        $config = [
            // Session Cookie Name
            'name' => 'K',
            // Connection name (needed only if handler is database).
            'connName' => 'default',
            // Session table name (needed only if handler is database).
            'tableName' => 'sessions',
            // Session Lifetime.
            'expiration' => 7200,
            // Match user agents on session requests.
            'matchUserAgent' => true,
            // Hashing algorithm used for creating security tokens.
            'hashAlgo' => 'md5',
            // Session regeneration frequency (0 to turn off).
            'updateFrequency' => 10
        ];

        $handler = new \Core\Session\Handlers\EncryptedFileSessionHandler();

        $this->session = new \Core\Session\Session($config, $handler);
        $this->session->setHashKey('randomstring');

		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 6.1; WOW64)';
	}

    public function testStart()
    {
        $this->session->start();
    }

	public function testGetSet()
	{
		$_SESSION['foo'] = 'bar';

        $this->assertEquals($_SESSION, $this->session->all());

		$this->assertEquals('bar', $_SESSION['foo']);

		$this->assertEquals('bar', $this->session->get('foo'));

		$this->session->set('bar', 'foo');

		$this->assertEquals('foo', $this->session->get('bar'));

		$this->assertTrue($this->session->has('bar'));

		$this->assertTrue(!$this->session->has('bar2'));

		$this->session->remove('bar');

		$this->assertTrue(!$this->session->has('bar'));

		$_SESSION['foo'] = 'bar';

		$this->assertTrue($this->session->has('foo'));

		$this->session->clear();

		$this->assertTrue(!$this->session->has('foo'));
	}
}