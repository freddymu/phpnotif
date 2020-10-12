<?php

namespace Freddymu\Phpnotif;

use Freddymu\Phpnotif\Entities\GenericResponseEntity;
use Freddymu\Phpnotif\Entities\PhpNotifEntity;
use Freddymu\Phpnotif\Helper\Validator;
use Freddymu\Phpnotif\Models\PhpNotifModel;
use MongoDB\Driver\Exception\Exception;

/**
 * Class Phpnotif
 * @package Freddymu\Phpnotif
 */
class Phpnotif
{
    /**
     * @param PhpNotifEntity $entity
     * @return GenericResponseEntity
     * @throws Exception
     */
    public function save(PhpNotifEntity $entity): GenericResponseEntity
    {
        $response = new GenericResponseEntity();

        $data = $entity->toArray();

        $rules = [
            'title' => 'required|max:256',
            'content_short' => 'max:256',
            'content_medium' => 'max:512',
            'content_long' => 'max:1024',
            'category_id' => 'integer',
            'group_id' => 'integer',
            'thumbnail_url' => 'max:256',
            'image_url' => 'max:256',
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

    public function setMessageAsRead(int $userId, $messageId): GenericResponseEntity
    {
        $response = new GenericResponseEntity();

        $model = new PhpNotifModel();
        $result = $model->setMessageAsRead($userId, $messageId);

        $response->success = !empty($result);
        $response->data = $result;

        return $response;
    }

    public function getInboxByUserId(int $userId, int $page = 1): GenericResponseEntity
    {
        $response = new GenericResponseEntity();

        $model = new PhpNotifModel();
        $result = $model->getInboxByUserId($userId, $page);

        $response->success = !empty($result);
        $response->message = 'Found ' . $result['total_data'] . ' data(s).';
        $response->data = $result;

        return $response;
    }
}
