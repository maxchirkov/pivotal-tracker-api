<?php
namespace PivotalTrackerV5\Models;


class Comment extends Model
{
    protected $id;                      // read only
    protected $story_id;                // read only
    protected $epic_id;                 // read only
    protected $text;
    protected $person_id;
    protected $created_at;              // read only
    protected $updated_at;              // read only
    protected $file_attachment_ids;
    protected $google_attachment_ids;
    protected $commit_identifier;
    protected $commit_type;
    protected $kind;                    // read only


    public function __construct($parameters = null)
    {
        if (is_string($parameters))
        {
            $this->setText($parameters);
        }
        else if (is_array($parameters))
        {
            foreach ($parameters as $parameter => $value)
            {
                $this->set($parameter, $value);
            }
        }
    }


    /**
     * @param mixed $commit_identifier
     */
    public function setCommitIdentifier($commit_identifier)
    {
        $this->commit_identifier = $commit_identifier;
    }


    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }


    /**
     * @param mixed $person_id
     */
    public function setPersonId($person_id)
    {
        $this->person_id = $person_id;
    }


    /**
     * @param mixed $google_attachment_ids
     */
    public function setGoogleAttachmentIds($google_attachment_ids)
    {
        $this->google_attachment_ids = $google_attachment_ids;
    }


    /**
     * @param mixed $file_attachment_ids
     */
    public function setFileAttachmentIds($file_attachment_ids)
    {
        $this->file_attachment_ids = $file_attachment_ids;
    }


    /**
     * @param mixed $commit_type
     */
    public function setCommitType($commit_type)
    {
        $this->commit_type = $commit_type;
    }

}