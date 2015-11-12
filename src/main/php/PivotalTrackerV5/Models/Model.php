<?php
namespace PivotalTrackerV5\Models;


use PivotalTrackerV5\Utils\Types\JsonSchemaToType;


class Model
{
    public static $required = [];

    protected $invalidProperties;

    protected $convertTypes;
    protected $converter;


    public function __construct($parameters = null, $convertTypes = true)
    {
        $this->convertTypes = $convertTypes;

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
            $value = $this->maybeConvertTypes($property, $value);

            if ($setterMethod = $this->getSetter($property))
            {
                call_user_func(
                    array($this, $setterMethod),
                    $value
                );
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
        return $this->exportTo('array', $this);

//        $reflect = new \ReflectionClass($this);
//        $props   = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);
//
//        $data = array();
//
//        foreach ($props as $prop)
//        {
//            $property   = $prop->getName();
//            $value      = $this->$property;
//
//            if (in_array($property, ['invalidProperties', 'convertTypes', 'converter']))
//                continue;
//
//            if ($value === null)
//                continue;
//
//            if (is_object($value) && $value instanceof Model)
//            {
//                $data[$property] = $value->toArray();
//            }
//            else if (is_array($value))
//            {
//                $tmp = [];
//
//                foreach ($value as $item)
//                {
//                    if (is_object($item) && $item instanceof Model)
//                    {
//                        $tmp[] = $item->toArray();
//                    }
//                    else
//                    {
//                        $tmp[] = $item;
//                    }
//                }
//
//                $data[$property] = $tmp;
//            }
//            else
//            {
//                $data[$property] = $value;
//            }
//        }
//
//        return $data;
    }


    function toDto()
    {
        return $this->exportTo('object', $this);

//        $reflect = new \ReflectionClass($this);
//        $props   = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);
//
//        $dto = new \stdClass();
//
//        foreach ($props as $prop)
//        {
//            $property = $prop->getName();
//            $value    = $this->$property;
//
//            if (in_array($property, ['invalidProperties', 'convertTypes', 'converter']))
//            {
//                continue;
//            }
//
//            if ($value === null)
//            {
//                continue;
//            }
//
//            if (is_array($value))
//            {
//                foreach ($value as $item)
//                {
//                    if (is_object($item) && $item instanceof Model)
//                    {
//                        $dto->$property[] = $item->toDto();
//                    }
//                    else
//                    {
//                        $dto->$property[] = $item;
//                    }
//                }
//            }
//            else
//            {
//                $dto->$property = $value;
//            }
//        }
//
//        return $dto;
    }


    protected function exportTo($type = 'array', $modelObject)
    {
        $destination = ($type == 'object') ? new \stdClass() : [];

        $reflect = new \ReflectionClass($modelObject);
        $props   = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);

        foreach ($props as $prop)
        {
            $property   = $prop->getName();
            $value      = $modelObject->$property;

            if (in_array($property, ['invalidProperties', 'convertTypes', 'converter']))
                continue;

            if ($value === null)
                continue;

            if (is_object($value) && $value instanceof Model)
            {
                $destination = $this->addTo($destination, $property, $this->exportTo($type, $value));
            }
            else if (is_array($value))
            {
                $tmp = [];

                foreach ($value as $item)
                {
                    if ($item instanceof Model)
                    {
                        $tmp[] = $this->exportTo($type, $item);
                    }
                    else
                    {
                        $tmp[]= $item;
                    }
                }

                $destination = $this->addTo($destination, $property, $tmp);
            }
            else
            {
                $destination = $this->addTo($destination, $property, $value);
            }
        }

        return $destination;
    }


    protected function addTo($destination, $property, $value)
    {
        if (is_object($destination))
        {
            $destination->$property = $value;
        }
        else if (is_array($destination))
        {
            $destination[$property] = $value;
        }

        return $destination;
    }

    /**
     * Checks if all required properties are provided
     */
    public function isValid()
    {
        $this->invalidProperties = [];

        if (!empty(static::$required))
        {
            foreach (static::$required as $property)
            {
                if (!isset($this->$property))
                {
                    $this->invalidProperties[] = $property;
                }
            }
        }

        return (!empty($this->invalidProperties)) ? false : true;
    }


    public function getValidationMessage()
    {
        if (!empty($this->invalidProperties))
        {
            return 'Story object is invalid. One or more properties are invalid: ' . implode(', ', $this->invalidProperties) . '.';
        }

        return;
    }


    public function maybeConvertTypes($property, $value)
    {
        if ($this->convertTypes && $this->getConverter())
        {
            return $this->getConverter()->convert($property, $value);
        }

        return $value;
    }


    protected function getConverter()
    {
        if ($this->converter)
            return $this->converter;

        $reflection = new \ReflectionClass($this);
        $modelName = $reflection->getShortName();
        $className = '\\PivotalTrackerV5\\Models\\' . $modelName;

        if (class_exists($className))
        {

            $schemaFile = __DIR__ . '/../Schemas/'. strtolower($modelName) . '-schema.json';

            if (file_exists($schemaFile))
            {
                $this->converter = new JsonSchemaToType($this->retrieveSchema($schemaFile));
            }
        }

        return $this->converter;
    }


    protected function retrieveSchema($fileName)
    {
        // Get the schema and data as objects
        $retriever = new \JsonSchema\Uri\UriRetriever;
        return $retriever->retrieve('file://' . realpath($fileName));
    }
}