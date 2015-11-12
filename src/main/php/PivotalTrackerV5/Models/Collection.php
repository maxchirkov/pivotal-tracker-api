<?php
namespace PivotalTrackerV5\Models;


class Collection
{
    protected $collection = [];

    public function all()
    {
        return (array)$this->collection;
    }
}