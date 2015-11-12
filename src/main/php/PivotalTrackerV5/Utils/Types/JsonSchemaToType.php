<?php
namespace PivotalTrackerV5\Utils\Types;


use PivotalTrackerV5\Factory;


class JsonSchemaToType
{
    protected $schema;


    function __construct($schema)
    {
        $this->schema = $schema;
    }


    function convert($propertyName, $value)
    {
        if (isset($this->schema->properties->$propertyName))
        {
            $property = $this->schema->properties->$propertyName;

            switch ($property->type)
            {
                case 'array':
                    if (is_array($value))
                    {
                        $array = $value;
                    }
                    else
                    {
                        $array = StringType::toArray($value);
                    }

                    if (isset($property->items))
                    {
                        $result = [];

                        $type = $property->items[0]->type;

                        if ($type == 'object')
                        {
                            $result = Factory::create($propertyName, $array);
                        }
                        else
                        {
                            foreach ($array as $item)
                            {
                                $method = 'to' . ucfirst($type);

                                if (is_callable(array('\PivotalTrackerV5\Utils\Type\StringType', $method)))
                                {
                                    $result[] = StringType::$method($item);
                                }
                            }
                        }

                        return $result;
                    }

                    return $array;

                case 'number':
                    return StringType::toInteger($value);

                case 'object':
                    Factory::create($propertyName, $value);

                case 'string':
                    return strval($value);
            }
        }
        else
        {
            throw new \Exception('Property ' . $propertyName . ' is not supported.');
        }
    }

}