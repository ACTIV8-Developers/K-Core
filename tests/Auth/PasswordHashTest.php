<?php

use Core\Auth\PasswordHash;

class PasswordHashTest extends \PHPUnit\Framework\TestCase
{
    public function testHash()
    {
        $hash = new PasswordHash(2, true);

        $hashedPassword = $hash->HashPassword("12345");

        $this->assertTrue($hash->CheckPassword("12345", $hashedPassword));
    }
}