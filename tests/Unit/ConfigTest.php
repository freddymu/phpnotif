<?php

namespace Freddymu\Phpnotif\Tests\Unit;

use Freddymu\Phpnotif\Helper\Config;
use Freddymu\Phpnotif\Tests\BaseTestCase;


class ConfigTest extends BaseTestCase
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

    /**
     * @test
     */
    public function read_a_sub_key_using_numeric_value()
    {
        // Given
        $configs = Config::get('connection.mongodb.1');

        // When

        // Then
        self::assertEquals('', $configs);
    }

}
