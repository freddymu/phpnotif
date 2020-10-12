<?php

namespace Freddymu\Phpnotif\Tests\Integration;

use Faker\Factory;
use Freddymu\Phpnotif\Entities\PhpNotifEntity;
use Freddymu\Phpnotif\Phpnotif;
use MongoDB\BSON\UTCDateTime;
use PHPUnit\Framework\TestCase;

class PhpNotifTest extends TestCase
{
    /**
     * @test
     */
    public function send_notification_to_inbox()
    {
        $faker = Factory::create();

        // Given
        $phpNotif = new Phpnotif();
        $entity = new PhpNotifEntity();
        $entity->id = $faker->randomNumber();
        $entity->title = $faker->text(50);
        $entity->content_long = $faker->realText();
        $entity->created_at = (new UTCDateTime(time() * 1000));
        $entity->created_at_unixtimestamp = time();
        $entity->user_id = $faker->randomNumber();

        // When
        $result = $phpNotif->save($entity);

        // Then
        self::assertTrue($result->success);
        self::assertNotNull($result->data, $result->message ?? '');
        self::assertNotEmpty($result->data, $result->message ?? '');
    }

    /**
     * @test
     */
    public function get_inbox_by_user_id()
    {
        // Given
        $faker = Factory::create();

        // Given
        $phpNotif = new Phpnotif();
        $entity = new PhpNotifEntity();
        $entity->id = $faker->randomNumber();
        $entity->title = $faker->text(50);
        $entity->content_long = $faker->realText();
        $entity->created_at = (new UTCDateTime(time() * 1000));
        $entity->created_at_unixtimestamp = time();
        $entity->user_id = $faker->randomNumber();

        // When
        $phpNotif->save($entity);
        $result = $phpNotif->getInboxByUserId($entity->user_id);

        // Then
        self::assertTrue($result->success);
        self::assertGreaterThanOrEqual(1, $result->data['total_data']);
    }

    /**
     * @test
     */
    public function set_message_as_read()
    {
        // Given

        // When

        // Then
    }

}
