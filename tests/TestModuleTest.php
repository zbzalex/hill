<?php

namespace Tests;

use Neon\WebApplicationFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
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
