<?php

namespace Freddymu\Phpnotif\Tests;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase {

    protected function setUp()
    {
        parent::setUp();
//        $dotenv = \Dotenv\Dotenv::createMutable(__DIR__ . '/..');
//        $dotenv->load();
        $dotenv = \Dotenv\Dotenv::create(__DIR__ . '/..');
        $dotenv->load();
    }

}
