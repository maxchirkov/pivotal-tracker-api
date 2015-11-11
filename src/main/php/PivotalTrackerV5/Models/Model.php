<?php
namespace PivotalTrackerV5\Models;


class Model
{
    public static $required = [];


    function __get($property)
    {
        if (property_exists($this, $property))
        {
            return $this->$property;
        }
    }


    function toArray()
    {
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);

        $data = array();

        foreach ($props as $prop)
        {
            $property   = $prop->getName();
            $value      = $this->$property;

            if ($value === null)
                continue;

            if (is_object($value) && $value instanceof Model)
            {
                $data[$property] = $value->toArray();
            }
            else if (is_array($value))
            {
                $tmp = [];

                foreach ($value as $item)
                {
                    if (is_object($item) && $item instanceof Model)
                    {
                        $tmp[] = $item->toArray();
                    }
                    else
                    {
                        $tmp[] = $item;
                    }
                }

                $data[$property] = $tmp;
            }
            else
            {
                $data[$property] = $value;
            }
        }

        return $data;
    }


    /**
     * Checks if all required properties are provided
     */
    public function isValid()
    {
        if (!empty(static::$required))
        {
            foreach (static::$required as $property)
            {
                if (!isset($this->$property))
                {
                    return false;
                }
            }
        }

        return true;
    }
}