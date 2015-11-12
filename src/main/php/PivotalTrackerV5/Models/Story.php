<?php
namespace PivotalTrackerV5\Models;


/**
 * Class Story
 *
 * @url https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_post
 *
 */
class Story extends Model
{
    public static $required = [
        'name'
    ];

    protected $project_id;

    protected $name;

    protected $description;

    /**
     * @var feature, bug, chore, release
     */
    protected $story_type = 'feature';

    /**
     * @var accepted, delivered, finished, started, rejected, planned, unstarted, unscheduled
     */
    protected $current_state = 'unscheduled';

    protected $estimate;

    protected $accepted_at;

    protected $deadline;

    protected $requested_by_id;

    protected $owner_ids;

    protected $labels;

    protected $label_ids;

    protected $tasks;

    protected $follower_ids;

    protected $comments;

    protected $created_at;

    protected $before_id;

    protected $after_id;

    protected $integration_id;

    protected $external_id;


    /**
     * @param mixed $projectId
     */
    public function setProjectId($projectId)
    {
        $this->project_id = $projectId;
    }


    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @param mixed $story_type
     */
    public function setStoryType($story_type)
    {
        $this->story_type = $story_type;
    }


    /**
     * @param mixed $current_state
     */
    public function setCurrentState($current_state)
    {
        $this->current_state = $current_state;
    }


    /**
     * @param mixed $estimate
     */
    public function setEstimate($estimate)
    {
        $this->estimate = $estimate;
    }


    /**
     * @param mixed $deadline
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }


    /**
     * @param mixed $requested_by_id
     */
    public function setRequestedById($requested_by_id)
    {
        $this->requested_by_id = $requested_by_id;
    }


    /**
     * @param mixed $owner_ids
     */
    public function setOwnerIds($owner_ids)
    {
        // TODO: extract into a conversion method and apply to all properties that have strict
        // type requirements.

        if (is_string($owner_ids))
        {
            $ids = explode(',', $owner_ids);
            $owner_ids = [];

            foreach ($ids as $id)
            {
                if (is_numeric($id))
                {
                    $owner_ids[] = intval($id);
                }
            }
        }

        $this->owner_ids = $owner_ids;
    }


    /**
     * @param mixed $labels
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;
    }


    /**
     * @param mixed $label_ids
     */
    public function setLabelIds($label_ids)
    {
        $this->label_ids = $label_ids;
    }


    /**
     * @param mixed $tasks
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }


    /**
     * @param mixed $follower_ids
     */
    public function setFollowerIds($follower_ids)
    {
        $this->follower_ids = $follower_ids;
    }


    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }


    /**
     * @param mixed $accepted_at
     */
    public function setAcceptedAt($accepted_at)
    {
        $this->accepted_at = $accepted_at;
    }


    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }


    /**
     * @param mixed $before_id
     */
    public function setBeforeId($before_id)
    {
        $this->before_id = $before_id;
    }


    /**
     * @param mixed $after_id
     */
    public function setAfterId($after_id)
    {
        $this->after_id = $after_id;
    }


    /**
     * @param mixed $integration_id
     */
    public function setIntegrationId($integration_id)
    {
        $this->integration_id = $integration_id;
    }


    /**
     * @param mixed $external_id
     */
    public function setExternalId($external_id)
    {
        $this->external_id = $external_id;
    }


    public function addComment($comment)
    {
        if (is_string($comment))
        {
            $this->comments[] = new Comment($comment);
        }
        else if ($comment instanceof Comment)
        {
            $this->comments[] = $comment;
        }
    }


    public function addTask($task)
    {
        if (is_string($task))
        {
            $this->tasks[] = new Task($task);
        }
        else if ($task instanceof Task)
        {
            $this->tasks[] = $task;
        }
    }
}