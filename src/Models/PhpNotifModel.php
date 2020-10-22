<?php


namespace Freddymu\Phpnotif\Models;

use Freddymu\Phpnotif\Database\MongoDb;
use Freddymu\Phpnotif\Exceptions\ConfigException;
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
     * @param int $userId
     * @param int $page
     * @return array
     * @throws Exception
     * @throws ConfigException
     */
    public function getInboxByUserId(int $userId, int $page = 1, int $groupId = null)
    {
        $defaultPerPage = (int)Config::get('pagination.per_page');
        $offset = ($page - 1) * $defaultPerPage;

        $totalData = $this->count($this->collectionName, ['user_id' => $userId])[0]->n ?? 0;

        $filter = ['user_id' => $userId];

        if ($groupId !== null && $groupId !== 0) {
            $filter['group_id'] = $groupId;
        }

        $foundData = $this->read($this->collectionName, [
            'filter' => $filter,
            'options' => [
                'skip' => $offset,
                'limit' => $defaultPerPage,
                'sort' => ['_id' => -1] // sort by newest
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
     * @param int $userId
     * @param string $messageId
     * @return mixed
     * @throws Exception
     */
    public function setMessageAsRead(int $userId, string $messageId)
    {
        $set = [
            'is_read' => 1,
            'read_at' => \Freddymu\Phpnotif\Helper\MongoDB::getUtcDate(),
            'read_at_unixtimestamp' => time()
        ];

        $objectId = new \MongoDB\BSON\ObjectId($messageId);

        $result = $this->update($this->collectionName, [
            'q' => ['_id' => $objectId, 'user_id' => $userId],
            'u' => ['$set' => $set]
        ]);

        return $result[0];
    }

    /**
     * @param int $userId
     * @return int[]
     * @throws Exception
     */
    public function getInboxSummary(int $userId) : array
    {
        $totalUnread = $this->count($this->collectionName, ['user_id' => $userId, 'is_read' => 0])[0]->n ?? 0;
        $totalRead = $this->count($this->collectionName, ['user_id' => $userId, 'is_read' => 1])[0]->n ?? 0;

        return [
            'unread' => $totalUnread,
            'read' => $totalRead,
            'total' => $totalUnread + $totalRead
        ];
    }

    /**
     * Close connection if applicable
     */
    public function __destruct()
    {
        $this->closeConnection();
    }


}
