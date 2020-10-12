<?php


namespace Freddymu\Phpnotif\Helper;


class MongoDB
{
    public static function getUtcDate()
    {
        return new \MongoDB\BSON\UTCDateTime(time() * 1000);
    }
}
