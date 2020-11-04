<?php

namespace Freddymu\Phpnotif\Tests\Integration;

use Freddymu\Phpnotif\Exceptions\ConfigException;
use Freddymu\Phpnotif\Helper\Test;
use Freddymu\Phpnotif\PhpNotif;
use Freddymu\Phpnotif\Tests\BaseTestCase;
use MongoDB\Driver\Exception\Exception;

class PhpNotifTest extends BaseTestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function send_notification_to_inbox()
    {
        // Given
        $phpNotif = new PhpNotif();
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
        $phpNotif = new PhpNotif();
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
     * @throws ConfigException
     * @throws Exception
     */
    public function get_inbox_by_user_id_and_group_id()
    {
        // Given
        $groupId = 1;
        $page = 1;
        $phpNotif = new PhpNotif();
        $entity = Test::createEntity();
        $entity->group_id = $groupId;

        // When
        $phpNotif->save($entity);
        $result = $phpNotif->getInboxByUserId($entity->user_id, $page, $groupId);

        // Then
        self::assertTrue($result->success);
        self::assertGreaterThanOrEqual(1, $result->data['total_data']);
        self::assertEquals($groupId, $result->data['data'][0]['group_id']);
    }

    /**
     * @test
     * @throws Exception|ConfigException
     */
    public function set_message_as_read()
    {
        // Given
        $phpNotif = new PhpNotif();
        $entity = Test::createEntity();

        // When
        $phpNotif->save($entity);
        $message = $phpNotif->getInboxByUserId($entity->user_id)->data['data'][0];
        $result = $phpNotif->setMessageAsRead($entity->user_id, $message['id']);

        // Then
        self::assertTrue($result->success);
        self::assertEquals(1, $result->data->nModified);
    }

    /**
     * @test
     */
    public function get_inbox_summary()
    {
        // Given
        $phpNotif = new PhpNotif();
        $entity = Test::createEntity();

        // When
        $phpNotif->save($entity);
        $result = $phpNotif->getInboxSummary($entity->user_id);

        // Then
        self::assertTrue($result->success);
        self::assertGreaterThanOrEqual(1, $result->data['unread']);
        self::assertGreaterThanOrEqual(1, $result->data['total']);
    }
}
