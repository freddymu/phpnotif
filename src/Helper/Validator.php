<?php

namespace Freddymu\Phpnotif\Helper;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;

/**
 * Class Validator
 * @package Freddymu\Phpnotif\Helper
 */
class Validator
{
    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return \Illuminate\Validation\Validator
     */
    public static function make(array $data, array $rules, array $messages = [])
    {
        // This bunch of object creation is showing you the truth when missing a dependency injection :-)
        $loader = new ArrayLoader();
        $loader->addMessages('en', 'validation', include dirname(__DIR__) . '/../resources/lang/en/validation.php');
        $translator = new Translator($loader, 'en');

        return new \Illuminate\Validation\Validator($translator, $data, $rules, $messages);
    }
}
