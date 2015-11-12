<?php
namespace PivotalTrackerV5\Utils\Types;


class StringType
{
    public static function toArray($string, $delimiter = ',')
    {
        return explode($delimiter, $string);
    }


    public static function toObject($string)
    {
        return ArrayType::toObject(self::toArray($string));
    }


    public static function toNumber($string)
    {
        return intval($string);
    }


    public static function toInteger($string)
    {
        return intval($string);
    }


    public static function toFloat($string)
    {
        return floatval($string);
    }


    public static function toBool($string, $true = null, $false = null)
    {
        if (!$true && !$false)
        {
            $true  = ['true', 'yes', '1'];
            $false = ['false', 'no', '0'];
        }

        $value = strtolower($string);

        if (in_array($value, $true))
        {
            return true;
        }
        else if (in_array($value, $false))
        {
            return false;
        }

        return boolval($string);
    }
}