<?php

namespace Freddymu\Phpnotif\Tests\Integration;

use Faker\Factory;
use Freddymu\Phpnotif\Entities\PhpNotifEntity;
use Freddymu\Phpnotif\Helper\Test;
use Freddymu\Phpnotif\Phpnotif;
use MongoDB\Driver\Exception\Exception;
use PHPUnit\Framework\TestCase;

class PhpNotifTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function send_notification_to_inbox()
    {
        // Given
        $phpNotif = new Phpnotif();
        $entity = Test::createEntity();

        // When
        $result = $phpNotif->save($entity);

        // Then
        self::assertTrue($result->success);
        self::assertNotNull($result->data, $result->message ?? '');
        self::assertNotEmpty($result->data, $result->message ?? '');
    }

    /**
     * @test
     * @throws Exception
     */
    public function get_inbox_by_user_id()
    {
        // Given
        $phpNotif = new Phpnotif();
        $entity = Test::createEntity();

        // When
        $phpNotif->save($entity);
        $result = $phpNotif->getInboxByUserId($entity->user_id);

        // Then
        self::assertTrue($result->success);
        self::assertGreaterThanOrEqual(1, $result->data['total_data']);
    }

    /**
     * @test
     * @throws Exception
     */
    public function set_message_as_read()
    {
        // Given
        $phpNotif = new Phpnotif();
        $entity = Test::createEntity();

        // When
        $phpNotif->save($entity);
        $result = $phpNotif->setMessageAsRead($entity->user_id, $entity->id);

        // Then
        self::assertTrue($result->success);
        self::assertEquals(1, $result->data->nModified);
    }

}
