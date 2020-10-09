<?php

namespace Freddymu\Phpnotif\Tests\Integration;

use Faker\Factory;
use Freddymu\Phpnotif\Database\MongoDb;
use Freddymu\Phpnotif\Entities\PhpNotifEntity;
use Freddymu\Phpnotif\Helper\Config;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;

class MongoDbTest extends TestCase
{
    /**
     * @test
     */
    public function open_connection_and_close_connection()
    {
        // Given
        $mongoDb = new MongoDb();

        // When
        $mongoDb->openConnection();

        $currentConnection = $mongoDb->getConnection();

        // Then
        self::assertInstanceOf(Manager::class, $currentConnection);
        self::assertNotNull($currentConnection);

        $mongoDb->closeConnection();
        self::assertNull($mongoDb->getConnection());
    }

    /**
     * @test
     */
    public function add_document()
    {
        $faker = Factory::create();

        // Given
        $mongoDb = new MongoDb();

        $entity = new PhpNotifEntity();
        $entity->id = $faker->uuid;
        $entity->title = $faker->text(50);
        $entity->content_long = $faker->realText();
        $entity->created_at = (new UTCDateTime(time() * 1000));
        $entity->created_at_unixtimestamp = time();
        $entity->user_id = $faker->randomNumber();

        $data = [$entity->toArray()];

        $collectionName = Config::get('connection.mongodb.default_collection_name');

        // When
        $mongoDb->openConnection();
        $result = $mongoDb->create($collectionName, $data);
        $mongoDb->closeConnection();

        // Then
        self::assertNotNull($result);
        self::assertEquals(1, $result[0]->n);
    }

    /**
     * @test
     */
    public function add_and_edit_document()
    {
        $faker = Factory::create();

        // Given
        $mongoDb = new MongoDb();

        $entity = new PhpNotifEntity();
        $entity->id = $faker->uuid;
        $entity->title = $faker->text(50);
        $entity->content_long = $faker->realText();
        $entity->created_at = (new UTCDateTime(time() * 1000));
        $entity->created_at_unixtimestamp = time();
        $entity->user_id = $faker->randomNumber();

        $data = [$entity->toArray()];

        $collectionName = Config::get('connection.mongodb.default_collection_name');

        // When
        $mongoDb->openConnection();

        $mongoDb->create($collectionName, $data);

        $entity->title .= ' [EDITED]';
        $updatedData = $entity->toArray();
        unset($updatedData->id);

        $result = $mongoDb->update($collectionName, [
            'q' => ['id' => $entity->id],
            'u' => ['$set' => $updatedData]
        ]);

        $mongoDb->closeConnection();

        // Then
        self::assertNotNull($result);
        self::assertEquals(1, $result[0]->nModified);
    }

    /**
     * @test
     */
    public function add_and_delete_document()
    {
        $faker = Factory::create();

        // Given
        $mongoDb = new MongoDb();

        $entity = new PhpNotifEntity();
        $entity->id = $faker->uuid;
        $entity->title = $faker->text(50);
        $entity->content_long = $faker->realText();
        $entity->created_at = (new UTCDateTime(time() * 1000));
        $entity->created_at_unixtimestamp = time();
        $entity->user_id = $faker->randomNumber();

        $collectionName = Config::get('connection.mongodb.default_collection_name');

        // When
        $mongoDb->openConnection();
        $mongoDb->create($collectionName, [$entity->toArray()]);
        $result = $mongoDb->delete($collectionName, [
            'q' => ['id' => $entity->id],
            'limit' => 0
        ]);
        $mongoDb->closeConnection();

        // Then
        self::assertNotNull($result);
        self::assertEquals(1, $result[0]->n);
    }
}
