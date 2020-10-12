<?php


namespace Freddymu\Phpnotif\Helper;


use MongoDB\BSON\UTCDateTime;

/**
 * Class MongoDB
 * @package Freddymu\Phpnotif\Helper
 */
class MongoDB
{
    /**
     * @return UTCDateTime
     */
    public static function getUtcDate()
    {
        return new UTCDateTime(time() * 1000);
    }
}
