<?php
use Core\Http\Cookie;

class CookieTest extends \PHPUnit_Framework_TestCase
{
    public function invalidNames()
    {
        return array(
            array(''),
            array(",MyName"),
            array(";MyName"),
            array(" MyName"),
            array("\tMyName"),
            array("\rMyName"),
            array("\nMyName"),
            array("\013MyName"),
            array("\014MyName"),
        );
    }

    public function testArrayAccess()
    {
        $cookie = new Cookie('MyCookie', 'foo');

        $this->assertEquals('foo', $cookie['value']);

        $this->assertTrue(isset($cookie['name']));

        $this->assertTrue(!isset($cookie['unknown']));

        $this->assertEquals(null, $cookie['unknown']);

        unset($cookie['value']);

        $cookie['value'] = 'bar';

        $this->assertEquals('bar', $cookie->getValue());

    }

    public function testGetName()
    {
        $cookie = new Cookie('MyCookie', 'foo');

        $this->assertEquals('MyCookie', $cookie->getName());
    }

    /**
     * @dataProvider invalidNames
     * @expectedException \InvalidArgumentException
     */
    public function testInstantiationThrowsExceptionIfCookieNameContainsInvalidCharacters($name)
    {
        new Cookie($name);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidExpiration()
    {
        $cookie = new Cookie('MyCookie', 'foo', 'bar');
    }

    public function testGetValue()
    {
        $value = 'MyValue';
        $cookie = new Cookie('MyCookie', $value);

        $this->assertSame($value, $cookie->getValue(), '->getValue() returns the proper value');

        $this->assertSame($value, $cookie['value'], '$cookie["value"] returns the proper value');
    }

    public function testGetPath()
    {
        $cookie = new Cookie('foo', 'bar');

        $this->assertSame('/', $cookie->getPath(), '->getPath() returns / as the default path');
    }

    public function testGetExpiresTime()
    {
        $cookie = new Cookie('foo', 'bar', 3600);

        $this->assertEquals(3600, $cookie->getExpiresTime(), '->getExpiresTime() returns the expire date');
    }

    public function testConstructorWithDateTime()
    {
        $expire = new \DateTime();
        $cookie = new Cookie('foo', 'bar', $expire);

        $this->assertEquals($expire->format('U'), $cookie->getExpiresTime(), '->getExpiresTime() returns the expire date');
    }

    public function testGetExpiresTimeWithStringValue()
    {
        $value = "+1 day";
        $cookie = new Cookie('foo', 'bar', $value);
        $expire = strtotime($value);

        $this->assertEquals($expire, $cookie->getExpiresTime(), '->getExpiresTime() returns the expire date');
    }

    public function testGetDomain()
    {
        $cookie = new Cookie('foo', 'bar', 3600, '/', '.myfoodomain.com');

        $this->assertEquals('.myfoodomain.com', $cookie->getDomain(), '->getDomain() returns the domain name on which the cookie is valid');
    }

    public function testIsSecure()
    {
        $cookie = new Cookie('foo', 'bar', 3600, '/', '.myfoodomain.com', true);

        $this->assertTrue($cookie->isSecure(), '->isSecure() returns whether the cookie is transmitted over HTTPS');
    }

    public function testIsHttpOnly()
    {
        $cookie = new Cookie('foo', 'bar', 3600, '/', '.myfoodomain.com', false, true);

        $this->assertTrue($cookie->isHttpOnly(), '->isHttpOnly() returns whether the cookie is only transmitted over HTTP');
    }

    public function testCookieIsNotCleared()
    {
        $cookie = new Cookie('foo', 'bar', time()+3600*24);

        $this->assertFalse($cookie->isCleared(), '->isCleared() returns false if the cookie did not expire yet');
    }

    public function testCookieIsCleared()
    {
        $cookie = new Cookie('foo', 'bar', time()-20);

        $this->assertTrue($cookie->isCleared(), '->isCleared() returns true if the cookie has expired');
    }
}
