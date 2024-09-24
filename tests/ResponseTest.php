<?php

namespace Tests;

use Neon\Cookie;
use Neon\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
  public function testResponse() {
    $response = new Response(null);

    $this->assertTrue(count($response->getHeaders()) === 0, 'Bad');

    // $response->addHeader('authorization', 'header');
    // $response->addCookie(new Cookie('jwt', '123', strtotime('+6 months')));
    // $response->addCookie(new Cookie('hello', 'world', strtotime('+9 months')));
  }
}
