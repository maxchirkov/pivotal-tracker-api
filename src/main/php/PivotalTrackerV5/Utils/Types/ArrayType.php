<?php
namespace PivotalTrackerV5\Utils\Types;


class ArrayType
{
    public static function toString($array, $delimiter = ',')
    {
        return implode($delimiter, $array);
    }


    public static function toObject($array)
    {
        $object = new \stdClass();

        foreach ($array as $k => $v)
        {
            if (is_array($v))
            {
                $object->$k = self::toObject($v);
            }
            else
            {
                $object->$k = $v;
            }
        }

        return $object;
    }
}