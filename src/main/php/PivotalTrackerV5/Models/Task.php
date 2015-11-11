<?php
namespace PivotalTrackerV5\Models;


class Task extends Model
{
    public static $required = [
        'description'
    ];

    protected $id;              // read only
    protected $story_id;        // read only
    protected $description;
    protected $complete;
    protected $position;
    protected $created_at;      // read only
    protected $updated_at;      // read only
    protected $kind;            // read only


    public function __construct($parameters = null)
    {
        if (is_string($parameters))
        {
            $this->setDescription($parameters);
        }
        else if (is_array($parameters))
        {
            foreach ($parameters as $parameter => $value)
            {
                if (property_exists($this, $parameter))
                {
                    $this->$parameter = $value;
                }
            }
        }
    }


    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @param mixed $complete
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;
    }


    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}