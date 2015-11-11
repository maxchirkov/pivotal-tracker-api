<?php
namespace PivotalTrackerV5\Models;


class Model
{
    public static $required = [];


    public function __construct($parameters = null)
    {
        if (is_array($parameters))
        {
            foreach ($parameters as $property => $value)
            {
                $this->set($property, $value);
            }
        }
    }


    function __get($property)
    {
        if (property_exists($this, $property))
        {
            return $this->$property;
        }
    }


    public function set($property, $value)
    {
        if (property_exists($this, $property))
        {
            if ($setterMethod = $this->getSetter($property))
            {
                call_user_func(array($this, $setterMethod), $value);
            }
            else
            {
                $this->$property = $value;
            }
        }

    }


    protected function getSetter($property)
    {
        $parts = explode('_', $property);
        $methodName = 'set' . implode('', array_map('ucfirst', $parts));

        if (method_exists($this, $methodName))
        {
            return $methodName;
        }

        return false;
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