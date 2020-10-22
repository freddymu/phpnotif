<?php

namespace Freddymu\Phpnotif\Database;

use Freddymu\Phpnotif\Helper\Config;
use MongoDB\Driver\Command;
use MongoDB\Driver\Exception\Exception;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;

/**
 * Class MongoDb
 * @package Freddymu\Phpnotif\Database
 */
class MongoDb implements DatabaseInterface
{
    /**
     * @var Manager|null
     */
    private $connection;

    /**
     * @var mixed|string
     */
    private $username;
    /**
     * @var mixed|string
     */
    private $password;
    /**
     * @var mixed|string
     */
    private $host;
    /**
     * @var mixed|string
     */
    private $port;
    /**
     * @var mixed|string
     */
    private $database;
    /**
     * @var mixed|string
     */
    private $adminDatabase;

    /**
     * @var
     */
    protected $collectionName;

    /**
     * MongoDb constructor.
     */
    public function __construct()
    {
        $this->username = Config::get('connection.mongodb.username');
        $this->password = Config::get('connection.mongodb.password');
        $this->host = Config::get('connection.mongodb.host');
        $this->port = Config::get('connection.mongodb.port');
        $this->database = Config::get('connection.mongodb.database');
        $this->adminDatabase = Config::get('connection.mongodb.options.database');
    }

    /**
     * @return $this
     */
    public function openConnection()
    {
        $credential = (empty($this->username) || empty($this->password)) ? '' : "{$this->username}:{$this->password}@";
        $this->connection = new Manager("mongodb://{$credential}{$this->host}:{$this->port}/{$this->adminDatabase}");
        return $this;
    }

    /**
     * @return Manager|null
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return $this
     */
    public function closeConnection()
    {
        // A new mongodb driver does not have a close connection mechanism
        // Read more here https://github.com/mongodb/mongo-php-driver/issues/393
        if ($this->connection !== null) {
            $this->connection = null;
        }

        return $this;
    }

    /**
     * @param string $collectionName
     * @param array $documents
     * @return array
     * @throws Exception
     * https://docs.mongodb.com/manual/reference/command/insert/
     */
    public function create(string $collectionName, array $documents)
    {
        $command = new Command([
            'insert' => $collectionName,
            'documents' => $documents
        ]);

        return $this
            ->connection
            ->executeCommand($this->database, $command)
            ->toArray();
    }

    /**
     * @param string $collectionName
     * @param array $payload
     * @return array
     * @throws Exception
     * https://docs.mongodb.com/manual/reference/command/find/
     */
    public function read(string $collectionName, array $payload)
    {
        $query = new Query(
            $payload['filter'],
            $payload['options'] ?? []
        );

        return $this->connection
            ->executeQuery("{$this->database}.{$collectionName}", $query)
            ->toArray();
    }

    /**
     * @param string $collectionName
     * @param array $payload
     * @return array
     * @throws Exception
     * https://docs.mongodb.com/manual/reference/command/update/
     */
    public function update(string $collectionName, array $payload)
    {
        $command = new Command([
            'update' => $collectionName,
            'updates' => [$payload]
        ]);

        return $this
            ->connection
            ->executeCommand($this->database, $command)
            ->toArray();
    }

    /**
     * @param string $collectionName
     * @param array $payload
     * @return array
     * @throws Exception
     * https://docs.mongodb.com/manual/reference/command/delete/
     */
    public function delete(string $collectionName, array $payload)
    {
        $command = new Command([
            'delete' => $collectionName,
            'deletes' => [$payload]
        ]);

        return $this
            ->connection
            ->executeCommand($this->database, $command)
            ->toArray();
    }

    /**
     * @param string $collectionName
     * @param array $payload
     * @return array
     * @throws Exception
     */
    public function count(string $collectionName, array $payload)
    {
        $pipeline = [
            [
                '$match' => (object)$payload
            ],
            [
                '$group' => [
                    '_id' => 1,
                    'n' => [
                        '$sum' => 1
                    ]
                ]
            ]
        ];

        $params = [
            'aggregate' => $collectionName,
            'pipeline' => $pipeline,
            'cursor' => new \stdClass()
        ];

        $command = new Command($params);

        return $this
            ->connection
            ->executeReadCommand($this->database, $command)
            ->toArray();
    }
}
