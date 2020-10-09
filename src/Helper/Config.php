<?php


namespace Freddymu\Phpnotif\Helper;

use Exception;
use Freddymu\Phpnotif\Exceptions\ConfigHelperException;

/**
 * Class ConfigHelper
 * @package Freddymu\Phpnotif
 */
class Config
{
    /**
     * @param string $name
     * @return mixed
     * @throws ConfigHelperException
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
     * @throws ConfigHelperException
     */
    private static function getConfigFileContent(): array
    {
        $laravelConfigStructure = dirname(__DIR__) . '/config/phpnotif.php';

        if (file_exists($laravelConfigStructure)) {
            return include $laravelConfigStructure;
        }

        $defaultConfigFile = dirname(__DIR__) . '/src/config/phpnotif.php';

        if (file_exists($defaultConfigFile)) {
            return include $defaultConfigFile;
        }

        throw new ConfigHelperException('Cannot found phpnotif.php configuration file.');
    }
}
