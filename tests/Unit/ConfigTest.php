<?php

namespace Freddymu\Phpnotif\Tests\Unit;

use Freddymu\Phpnotif\Helper\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @test
     */
    public function read_config_file()
    {
        // Given
        $configContent = Config::get('connection');

        // When

        // Then
        self::assertIsArray($configContent);
    }

    /**
     * @test
     */
    public function read_a_sub_key_from_config_file()
    {
        // Given
        $configContent = Config::get('connection.mongodb.username');

        // When

        // Then
        self::assertIsString($configContent);
        self::assertNotEmpty($configContent);
    }
}
