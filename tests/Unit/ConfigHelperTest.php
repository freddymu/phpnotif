<?php

namespace Freddymu\Phpnotif\Tests\Unit;

use Freddymu\Phpnotif\ConfigHelper;
use PHPUnit\Framework\TestCase;

class ConfigHelperTest extends TestCase
{
    /**
     * @test
     */
    public function read_config_file()
    {
        // Given
        $configContent = ConfigHelper::get('connection');

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
        $configContent = ConfigHelper::get('connection.mongodb.username');

        // When

        // Then
        self::assertIsString($configContent);
        self::assertNotEmpty($configContent);
    }
}
