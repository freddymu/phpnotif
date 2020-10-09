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

    public function setMessageAsRead(): GenericResponseEntity
    {
        $response = new GenericResponseEntity();

        return $response;
    }

    public function getInboxByUserId(): GenericResponseEntity
    {
        $response = new GenericResponseEntity();

        return $response;
    }
}
