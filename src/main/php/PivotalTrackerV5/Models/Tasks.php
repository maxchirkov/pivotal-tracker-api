<?php
namespace PivotalTrackerV5\Models;


class Tasks extends Collection
{
    function __construct(array $data)
    {
        foreach ($data as $parameters)
        {
            $this->collection[] = new Task($parameters);
        }
    }
}