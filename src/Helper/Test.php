<?php


namespace Freddymu\Phpnotif\Helper;


use Faker\Factory;
use Freddymu\Phpnotif\Entities\PhpNotifEntity;

/**
 * Class Test
 * @package Freddymu\Phpnotif\Helper
 */
class Test
{
    /**
     * @return PhpNotifEntity
     */
    public static function createEntity(): PhpNotifEntity
    {
        $faker = Factory::create();

        $entity = new PhpNotifEntity();
        $entity->title = $faker->text(50);
        $entity->content_long = $faker->realText();
        $entity->created_at = MongoDB::getUtcDate();
        $entity->created_at_unixtimestamp = time();
        $entity->user_id = $faker->randomNumber();
        return $entity;
    }

    /**
     * @param $mixed
     */
    public static function dd($mixed)
    {
        var_dump($mixed);
        die();
    }

    /**
     * @param $mixed
     */
    public static function dump($mixed)
    {
        var_dump($mixed);
    }
}
