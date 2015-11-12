<?php
namespace PivotalTrackerV5;


use PivotalTrackerV5\Models\Collection;


class Factory
{
    public static function create($objectName, $data)
    {
        $modelName = ucfirst(strtolower($objectName));
        $className = '\\PivotalTrackerV5\\Models\\' . $modelName;

        if (class_exists($className))
        {
            $model = new $className($data);

            if ($model instanceof Collection)
            {
                return $model->all();
            }

            return $model;
        }

        return false;
    }
}