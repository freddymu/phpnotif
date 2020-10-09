<?php


namespace Freddymu\Phpnotif\Models;

use Freddymu\Phpnotif\Database\MongoDb;
use Freddymu\Phpnotif\Helper\Config;
use MongoDB\Driver\Exception\Exception;

/**
 * Class PhpNotifModel
 * @package Freddymu\Phpnotif\Models
 */
class PhpNotifModel extends MongoDb
{
    /**
     * PhpNotifModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->collectionName = Config::get('connection.mongodb.default_collection_name');
        $this->openConnection();
    }

    /**
     * @param array $payload
     * @return array
     * @throws Exception
     */
    public function save(array $payload)
    {
        if (is_array($payload[0] ?? null) === false) {
            $payload = [$payload];
        }

        return $this->create($this->collectionName, $payload);
    }

    /**
     * Close connection if applicable
     */
    public function __destruct()
    {
        $this->closeConnection();
    }
}
