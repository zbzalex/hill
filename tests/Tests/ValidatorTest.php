<?php

namespace Tests;

use Hill\Module;
use Hill\Request;
use Hill\RequestMethod;
use Hill\Route;
use Hill\RouteMatcher;
use Hill\RouteScanner;
use Hill\Validator;

/**
 * Module test class
 */
class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    public function testValidation()
    {
        $validator = new \Hill\Validator([
            'hex' => [
                function($field, $value) {
                    if (!preg_match("/^[0-9a-f]{2,}$/i", $value)) {
                        return "Invalid hex format";
                    }
                },
            ],
        ]);

        $fakeData = [
            'hex' => bin2hex(random_bytes(32)),
        ];

        $errors = $validator->validate($fakeData);

        $this->assertEquals(count($errors), 0, "Validation error");
    }

    public function testInvalidValidation() {
        $validator = new \Hill\Validator([
            'email' => [
                function($field, $value) {
                    if (!preg_match("/^[^@]+@[^$]+$/i", $value)) {
                        return "Invalid email format";
                    }
                },
            ],
        ]);

        $fakeData = [
            'email' => 'bad.email___gmail_com',
        ];

        $errors = $validator->validate($fakeData);

        $this->assertEquals(count($errors), 1, "Validation error");
    }
}
