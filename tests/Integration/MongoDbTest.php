<?php

namespace Freddymu\Phpnotif\Tests\Integration;

use Freddymu\Phpnotif\Database\MongoDb;
use Freddymu\Phpnotif\Exceptions\ConfigException;
use Freddymu\Phpnotif\Helper\Config;
use Freddymu\Phpnotif\Helper\Test;
use Freddymu\Phpnotif\Tests\BaseTestCase;
use MongoDB\Driver\Exception\Exception;
use MongoDB\Driver\Manager;

class MongoDbTest extends BaseTestCase
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
     * @throws ConfigException
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
     * @throws ConfigException
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
     * @throws Exception|ConfigException
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
        $insertedId = (string)$mongoDb->read($collectionName, ['filter' => []])[0]->_id;
        $objectId = new \MongoDB\BSON\ObjectId($insertedId);

        $entity->title .= ' [EDITED]';
        $updatedData = $entity->toArray();

        $result = $mongoDb->update($collectionName, [
            'q' => ['_id' => $objectId],
            'u' => ['$set' => $updatedData]
        ]);

        $mongoDb->closeConnection();

        // Then
        self::assertNotNull($result);
        self::assertEquals(1, $result[0]->nModified);
    }

    /**
     * @test
     * @throws Exception|ConfigException
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
        $insertedId = (string)$mongoDb->read($collectionName, ['filter' => []])[0]->_id;
        $objectId = new \MongoDB\BSON\ObjectId($insertedId);

        $result = $mongoDb->delete($collectionName, [
            'q' => ['_id' => $objectId],
            'limit' => 0
        ]);

        $mongoDb->closeConnection();

        // Then
        self::assertNotNull($result);
        self::assertEquals(1, $result[0]->n);
    }
}
