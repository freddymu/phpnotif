<?php


namespace Freddymu\Phpnotif\Helper;


use Faker\Factory;
use Freddymu\Phpnotif\Entities\PhpNotifEntity;

class Test
{
    /**
     * @return PhpNotifEntity
     */
    public static function createEntity(): PhpNotifEntity
    {
        $faker = Factory::create();

        $entity = new PhpNotifEntity();
        $entity->id = $faker->randomNumber();
        $entity->title = $faker->text(50);
        $entity->content_long = $faker->realText();
        $entity->created_at = MongoDB::getUtcDate();
        $entity->created_at_unixtimestamp = time();
        $entity->user_id = $faker->randomNumber();
        return $entity;
    }
}
