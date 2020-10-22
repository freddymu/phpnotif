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
     * @var string|null
     */
    public $content_short = null;

    /**
     * @var string|null
     */
    public $content_medium = null;

    /**
     * @var string
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
     * @var string|null
     */
    public $thumbnail_url = null;

    /**
     * @var string|null
     */
    public $image_url = null;

    /**
     * @var
     */
    public $created_at;

    /**
     * @var int
     */
    public $created_at_unixtimestamp;

    /**
     * @var int
     */
    public $is_read = 0;

    /**
     * @var
     */
    public $read_at = null;

    /**
     * @var int|null
     */
    public $read_at_unixtimestamp = null;

    /**
     * @var string|null
     */
    public $reference_name = null;

    /**
     * @var int|null
     */
    public $reference_id = null;

    /**
     * @var string|null
     */
    public $reference_page = null;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var array
     */
    public $options = [];

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
