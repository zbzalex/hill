<?php

namespace AppModule\Guard;

use AppModule\Service\AppService;
use Hill\Request;

//
//
//
class AuthGuard implements \Hill\IGuard
{
    public function __construct(
        AppService $appService
    ) {
        //var_dump($appService->sayHello());
    }
    
    public function __invoke(Request $request)
    {
        $headers = $request->headers;

        $token = isset($headers['access-token'])
            ? $headers['access-token']
            : null;
        // if ($token === null) {
        //     return false;
        // }

        return true;
    }
}
