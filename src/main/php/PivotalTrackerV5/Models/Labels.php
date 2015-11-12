<?php
namespace PivotalTrackerV5\Models;


class Labels extends Collection
{
    function __construct(array $data)
    {
        foreach ($data as $parameters)
        {
            $this->collection[] = (new Label($parameters))->toDto();
        }
    }
}