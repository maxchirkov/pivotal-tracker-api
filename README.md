pivotal-tracker-api
===================
**Develop Branch**  
[![Build Status](https://travis-ci.org/maxchirkov/pivotal-tracker-api.svg?branch=develop)](https://travis-ci.org/maxchirkov/pivotal-tracker-api)

Library that provides a PHP interface to interact with the PivotalTracker API V5


Example:

```php
$pivotalTracker =  new \PivotalTrackerV5\Client(  $apiToken , $projectId ) ;

$storyList = $pivotalTracker->getStories( 'label:test')  ;
```

To Add a Story:

```php

$story =  array(
		'name' => 'A Brand New Story',
		'story_type' => 'feature',
		'description' => 'A small description',
		'labels' => array(  
		    array( 
		    	'name' => 'test'  
		    ) 
		 )
); 
$result = $pivotalTracker->addStory($story);

```

This API also supports a few models that can be correctly generated according to API JSON schema. Values are automatically parsed and their values changed according to schema. This allows use of raw posted data where most values are strings:

```php
$parameters = array(
    'project_id'    => '1',
    'name'          => 'Some story name',
    'description'   => 'Story description',
    'estimate'      => '4',
    'labels'        => 'label 1,label2,label3,
    'tasks          => 'Task 1,Task2,Task3
); 

$story = new Story($parameters);

$result = $pivotalTracker->addStory($story->toArray());

```

To get a data transfer object:

```php

$story->toDto();

```

To get an array:

```php

$story->toArray();

```

This is a JSON representation of the returned value.

```json
{
  "project_id": 1,
  "name": "Some story name",
  "description": "Story description",
  "story_type": "feature",
  "current_state": "unscheduled",
  "estimate": 4,
  "labels": [
    {
      "name": "label 1"
    },
    {
      "name": "label 2"
    },
    {
      "name": "label 3"
    }
  ],
  "tasks": [
    {
      "description": "Task 1"
    },
    {
      "description": "Task 2"
    },
    {
      "description": "Task 3"
    }
  ]
}

```