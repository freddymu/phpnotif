<?php

namespace Freddymu\Phpnotif\Tests\Integration;

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
        // Given
        $phpNotif = new Phpnotif();
        $entity = new PhpNotifEntity();
        $entity->id = getmyuid();
        $entity->title = 'The title';
        $entity->content_long = 'This is the content long';
        $entity->created_at = (new UTCDateTime(time() * 1000));
        $entity->created_at_unixtimestamp = time();
        $entity->user_id = null;

        // When
        $result = $phpNotif->save($entity);

        // Then
        self::assertTrue($result->success);
        self::assertNotNull($result->data, $result->message);
        self::assertNotEmpty($result->data, $result->message);
    }

    /**
     * @test
     */
    public function get_inbox_by_user_id()
    {
        // Given
        $userId = 0;

        // When
        // Get inbox

        // Then
        // Check the inbox
    }
}
