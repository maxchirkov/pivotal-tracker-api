<?php


/**
 * Created by PhpStorm.
 * User: maxchirkov
 * Date: 11/11/15
 * Time: 12:31 PM
 */
class StoryTest extends PHPUnit_Framework_TestCase
{
    public function testModelIsValid()
    {
        $story = new \PivotalTrackerV5\Models\Story();
        $story->setProjectId(123);
        $story->setName('Test Story Name');

        $this->assertTrue($story->isValid());
    }


    public function testModelIsInvalid()
    {
        $story = new \PivotalTrackerV5\Models\Story();
        $story->setName('Test Story Name');
        $this->assertFalse($story->isValid());
    }


    public function testModelToArray()
    {
        $story = new \PivotalTrackerV5\Models\Story();
        $story->setProjectId(123);
        $story->setName('Test Story Name');

        $task1 = new \PivotalTrackerV5\Models\Task();
        $task1->setDescription('Test Task Description');
        $task2 = new \PivotalTrackerV5\Models\Task();
        $task2->setDescription('Test Task Description 2');


        $array1 = [
            'project_id' => 123,
            'name'  => 'Test Story Name',
            'story_type' => 'feature',
            'current_state' => 'unscheduled',
            'tasks'  => [
                [
                    'description' => 'Test Task Description'
                ],
                [
                    'description' => 'Test Task Description 2'
                ]
            ]
        ];

        $array2 = [
            'project_id' => 123,
            'name'  => 'Test Story Name',
            'story_type' => 'feature',
            'current_state' => 'unscheduled',
            'tasks'  => [
                [
                    'description' => 'Test Task Description'
                ]
            ]
        ];

        $story->setTasks([$task1, $task2]);
        $this->assertEquals($array1, $story->toArray());

        $story->setTasks([$task1]);
        $this->assertEquals($array2, $story->toArray());
    }


    public function testStoryAddComment()
    {
        $story = new \PivotalTrackerV5\Models\Story();
        $story->setProjectId(1);
        $story->setName('Test Story');
        $story->addComment('Test comment string');

        $this->assertEquals('Test comment string', $story->comments[0]->text);

        $comment = new \PivotalTrackerV5\Models\Comment('Another comment');
        $story->addComment($comment);
        $this->assertEquals('Another comment', $story->comments[1]->text);

    }


    public function testStoryAddTasks()
    {
        $story = new \PivotalTrackerV5\Models\Story();
        $story->setProjectId(1);
        $story->setName('Test Story');
        $story->addTask('Test task string');

        $this->assertEquals('Test task string', $story->tasks[0]->description);

        $task = new \PivotalTrackerV5\Models\Task('Another task');
        $story->addTask($task);
        $this->assertEquals('Another task', $story->tasks[1]->description);

        $parameters = [
            'description' => 'One more task',
            'position'    => 1
        ];
        $task2 = new \PivotalTrackerV5\Models\Task($parameters);
        $story->addTask($task2);
        $this->assertEquals('One more task', $story->tasks[2]->description);
        $this->assertEquals(1, $story->tasks[2]->position);
    }
}
