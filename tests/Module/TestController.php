<?php

namespace Module;

use Hill\Request;
use Hill\RequestMapping;

class TestController extends \Hill\Controller implements \Hill\IController
{
  private $fooService;
  public function __construct(
    \Module\FooService $fooService
  ) {
    $this->fooService = $fooService;
  }

  public static function getConfig(): array
  {
    return [
      'path' => '/',
      'mapping' => [
        new RequestMapping("GET", "/hello", "_index")
      ],
    ];
  }

  public function _index(Request $req)
  {
    return "hello";
  }
}
