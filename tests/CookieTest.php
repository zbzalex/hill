<?php

namespace Tests;

use Neon\Cookie;
use PHPUnit\Framework\TestCase;

class CookieTest extends TestCase {
  public function testCookie() {
    $cookie = new Cookie('jwt', '123', strtotime('+6 months'));

    $this->assertTrue($cookie->path === '/', 'Bad');
  }
}