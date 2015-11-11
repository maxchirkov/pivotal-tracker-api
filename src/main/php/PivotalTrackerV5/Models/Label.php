<?php
namespace PivotalTrackerV5\Models;


class Label extends Model
{
    protected $id;              // read only
    protected $project_id;      // read only
    protected $name;
    protected $created_at;      // read only
    protected $updated_at;      // read only
    protected $counts;          // read only
    protected $kind;            // read only


    public function __construct($name = null)
    {
        if ($name)
        {
            $this->setName($name);
        }
    }


    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}