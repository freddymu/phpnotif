<?php

namespace Freddymu\Phpnotif\Tests\Integration;

use Freddymu\Phpnotif\Database\MongoDb;
use Freddymu\Phpnotif\Exceptions\ConfigHelperException;
use Freddymu\Phpnotif\Helper\Config;
use Freddymu\Phpnotif\Helper\Test;
use MongoDB\Driver\Exception\Exception;
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
     * @throws ConfigHelperException
     * @throws Exception
     */
    public function add_document()
    {
        // Given
        $mongoDb = new MongoDb();
        $entity = Test::createEntity();
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
     * @throws ConfigHelperException
     * @throws Exception
     */
    public function add_and_read_document()
    {
        // Given
        $mongoDb = new MongoDb();
        $entity = Test::createEntity();
        $data = [$entity->toArray()];

        $collectionName = Config::get('connection.mongodb.default_collection_name');

        // When
        $mongoDb->openConnection();

        $mongoDb->create($collectionName, $data);
        $result = $mongoDb->read($collectionName, ['filter' => ['user_id' => $entity->user_id]]);

        $mongoDb->closeConnection();

        // Then
        self::assertNotNull($result);
        self::assertEquals($entity->user_id, $result[0]->user_id);
    }

    /**
     * @test
     * @throws Exception|ConfigHelperException
     */
    public function add_and_edit_document()
    {
        // Given
        $mongoDb = new MongoDb();
        $entity = Test::createEntity();
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
     * @throws Exception|ConfigHelperException
     */
    public function add_and_delete_document()
    {
        // Given
        $mongoDb = new MongoDb();
        $entity = Test::createEntity();
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
