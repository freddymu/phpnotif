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

    public function getInboxByUserId(int $userId, int $page = 1)
    {
        $defaultPerPage = (int)Config::get('pagination.per_page');
        $offset = ($page - 1) * $defaultPerPage;

        $totalData = $this->count($this->collectionName, ['user_id' => $userId])[0]->n ?? 0;

        $foundData = $this->read($this->collectionName, [
            'filter' => ['user_id' => $userId],
            'options' => [
                'skip' => $offset,
                'limit' => $defaultPerPage,
                // 'maxTimeMS' => 5000
            ]
        ]);

        return [
            'total_data' => $totalData,
            'page' => $page,
            'total_page' => (int)ceil($totalData / $defaultPerPage),
            'data' => $foundData
        ];
    }

    /**
     * Close connection if applicable
     */
    public function __destruct()
    {
        //$this->closeConnection();
    }
}
