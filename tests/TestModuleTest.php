<?php

namespace Tests;

use Neon\Request;
use Neon\WebApplicationFactory;
use PHPUnit\Framework\TestCase;
use Neon\WebApplication;

class TestModuleTest extends TestCase
{
  public function testModule()
  {
    $request = Request::create("/");
    
    /** @var WebApplication $app */
    $app = WebApplicationFactory::create(TestModule::class);
    
    $response = $app->handleRequest($request);

    // TODO
  }
}
