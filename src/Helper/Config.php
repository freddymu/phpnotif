<?php


namespace Freddymu\Phpnotif\Helper;

use Exception;
use Freddymu\Phpnotif\Exceptions\ConfigException;

/**
 * Class ConfigHelper
 * @package Freddymu\Phpnotif
 */
class Config
{
    /**
     * @param string $name
     * @return mixed
     * @throws ConfigException
     */
    public static function get(string $name)
    {
        $configs = self::getConfigFileContent();

        $dotSeparator = explode('.', $name);

        $result = array_reduce($dotSeparator, function ($carry, $item) {

            if (is_numeric($item)) {
                return [];
            }

            return $carry[$item] ?? [];

        }, $configs);

        return empty($result) ? '' : $result;
    }

    /**
     * @return array
     * @throws ConfigException
     */
    private static function getConfigFileContent(): array
    {
        if (function_exists('config_path')) {

            $laravelConfig = config_path('phpnotif.php');

            if (file_exists($laravelConfig)) {
                return include $laravelConfig;
            }
        }

        $defaultConfig = dirname(__DIR__) . '/../config/phpnotif.php';

        if (file_exists($defaultConfig)) {
            return include $defaultConfig;
        }

        throw new ConfigException('Cannot found phpnotif.php configuration file.');
    }
}
