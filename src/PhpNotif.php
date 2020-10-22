<?php

namespace Freddymu\Phpnotif;

use Freddymu\Phpnotif\Entities\GenericResponseEntity;
use Freddymu\Phpnotif\Entities\PhpNotifEntity;
use Freddymu\Phpnotif\Helper\Validator;
use Freddymu\Phpnotif\Models\PhpNotifModel;
use Illuminate\Support\Carbon;
use MongoDB\Driver\Exception\Exception;

/**
 * Class PhpNotif
 * @package Freddymu\Phpnotif
 */
class PhpNotif
{
    /**
     * @param PhpNotifEntity $entity
     * @return GenericResponseEntity
     * @throws Exception
     */
    final public function save(PhpNotifEntity $entity): GenericResponseEntity
    {
        $response = new GenericResponseEntity();

        $data = $entity->toArray();

        $rules = [
            'title' => 'required|max:256',
            'content_short' => 'nullable|max:256',
            'content_medium' => 'nullable|max:512',
            'content_long' => 'required|max:1024',
            'category_id' => 'integer',
            'group_id' => 'integer',
            'thumbnail_url' => 'nullable|max:256',
            'image_url' => 'nullable|max:256',
            'user_id' => 'required|integer',
            'reference_id' => 'nullable|integer'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $response->message = 'All mandatory fields must be provided.';
            $response->data = $validator->errors();
            return $response;
        }

        $model = new PhpNotifModel();
        $result = $model->save($data);

        $response->success = $result !== false;
        $response->data = $entity->toArray();

        return $response;
    }

    /**
     * @param int $userId
     * @param $messageId
     * @return GenericResponseEntity
     * @throws Exception
     */
    final public function setMessageAsRead(int $userId, string $messageId): GenericResponseEntity
    {
        $response = new GenericResponseEntity();

        $model = new PhpNotifModel();
        $result = $model->setMessageAsRead($userId, $messageId);

        $response->success = (bool)($result->nModified ?? 0);
        $response->data = $result;

        return $response;
    }

    /**
     * @param int $userId
     * @param int $page
     * @return GenericResponseEntity
     * @throws Exception
     * @throws Exceptions\ConfigException
     */
    final public function getInboxByUserId(int $userId, int $page = 1, int $groupId = null): GenericResponseEntity
    {
        $response = new GenericResponseEntity();

        $model = new PhpNotifModel();
        $result = $model->getInboxByUserId($userId, $page, $groupId);

        // transform data
        $transformedData = collect($result['data'])
            ->map(function ($item) {
                $readAt = $item->read_at !== null
                    ? Carbon::createFromTimestampMs((string)$item->read_at, '7')->format('Y-m-d H:i:s')
                    : null;
                $createdAt = $item->created_at !== null
                    ? Carbon::createFromTimestampMs((string)$item->created_at, '7')->format('Y-m-d H:i:s')
                    : null;
                $newElements = [
                    'id' => (string)$item->_id,
                    'created_at_formatted' => $createdAt,
                    'read_at_formatted' => $readAt
                ];
                return array_merge((array)$item, $newElements);
            });

        $result['data'] = $transformedData;

        $response->success = !empty($result);
        $response->message = 'Found ' . $result['total_data'] . ' data(s).';
        $response->data = $result;

        return $response;
    }

    /**
     * @param int $userId
     * @return GenericResponseEntity
     * @throws Exception
     */
    final public function getInboxSummary(int $userId) : GenericResponseEntity
    {
        $response = new GenericResponseEntity();

        $model = new PhpNotifModel();
        $response->success = true;
        $response->message = 'Inbox Stats';
        $response->data = $model->getInboxSummary($userId);

        return $response;
    }
}
