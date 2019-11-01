<?php

class SessionTest extends \PHPUnit\Framework\TestCase
{
	public function setUp() : void
	{
        $config = [
            // Session Cookie Name
            'name' => 'K',
            // Session Lifetime.
            'expiration' => 7200,
            // Match user agents on session requests.
            'matchUserAgent' => true,
            // Hashing algorithm used for creating security tokens.
            'hashAlgo' => 'md5',
            // Session regeneration frequency (0 to turn off).
            'updateFrequency' => 10
        ];

        $this->session = new \Core\Session\Session($config);
        $this->session->setHashKey('randomstring');

		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 6.1; WOW64)';
	}

    public function tearDown() : void
    {
        session_write_close();
    }

	public function testGetSet()
	{
        $this->session->start();

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