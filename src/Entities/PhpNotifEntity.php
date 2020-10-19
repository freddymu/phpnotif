<?php

namespace Freddymu\Phpnotif\Entities;

/**
 * Class PhpNotifEntity
 * @package Freddymu\Phpnotif\Entities
 */
class PhpNotifEntity
{
    /**
     * @var string
     */
    public $_id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var null
     */
    public $content_short = null;

    /**
     * @var null
     */
    public $content_medium = null;

    /**
     * @var
     */
    public $content_long;

    /**
     * @var int
     */
    public $category_id = 0;

    /**
     * @var int
     */
    public $group_id = 0;

    /**
     * @var null
     */
    public $thumbnail_url = null;

    /**
     * @var null
     */
    public $image_url = null;

    /**
     * @var
     */
    public $created_at;

    /**
     * @var
     */
    public $created_at_unixtimestamp;

    /**
     * @var int
     */
    public $is_read = 0;

    /**
     * @var null
     */
    public $read_at = null;

    /**
     * @var null
     */
    public $read_at_unixtimestamp = null;

    /**
     * @var null
     */
    public $reference_name = null;

    /**
     * @var null
     */
    public $reference_id = null;

    /**
     * @var null
     */
    public $reference_page = null;

    /**
     * @var
     */
    public $user_id;

    /**
     * @return array
     */
    final public function toArray(): array
    {
        $props = get_object_vars($this);
        $buffers = [];

        foreach ($props as $key => $value) {
            if ($key === '_id') {
                continue;
            }
            $buffers[$key] = $value;
        }

        return $buffers;
    }

}
