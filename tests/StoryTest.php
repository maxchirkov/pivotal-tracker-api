<?php


/**
 * Created by PhpStorm.
 * User: maxchirkov
 * Date: 11/11/15
 * Time: 12:31 PM
 */
class StoryTest extends PHPUnit_Framework_TestCase
{
    public $schema;
    public $validator;


    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        // Get the schema and data as objects
        $retriever = new JsonSchema\Uri\UriRetriever;
        $path = __DIR__ . '/../src/main/php/PivotalTrackerV5/Schemas/story-schema.json';
        $this->schema = $retriever->retrieve('file://' . realpath($path));

        $this->validator = new JsonSchema\Validator();
    }


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
        $story->setProjectId(123);
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


    public function testStoryFromData()
    {
        $params = [
            'project_id' => 1,
            'name'       => 'Some story name',
            'description'=> 'Story description'
        ];

        $story = new \PivotalTrackerV5\Models\Story($params);

        $this->assertEquals(1, $story->project_id);
        $this->assertEquals('Some story name', $story->name);
        $this->assertEquals('Story description', $story->description);

        $story->set('nonexistent_property', true);
        $this->assertEquals(null, $story->nonexistent_property);
    }


    /**
     * @dataProvider schemaValidationProvider
     */
    public function testStorySchemaValidates($params, $expected)
    {
        $story = new \PivotalTrackerV5\Models\Story($params);

        $array = $story->toArray();
        $obj = new \stdClass();

        foreach ($array as $k => $v)
        {
            $obj->$k = $v;
        }

        $this->validator->check($obj, $this->schema);

        $this->assertTrue($expected === $this->validator->isValid());
    }


    public function testStoryOwnerIdsViaSetter()
    {
        $story = new \PivotalTrackerV5\Models\Story();
        $story->setProjectId(1);
        $story->setName('Test Story');
        $story->setOwnerIds([1,2,3]);

        $data = $story->toArray();
        $this->assertEquals([1,2,3], $data['owner_ids']);
    }


    /**
     * @group failing
     */
    public function testStoryOwnerIdsViaConstructor()
    {
        $data = [
            'name'      => 'Test Story',
            'owner_ids' => [92,93,94]
        ];
        $story = new \PivotalTrackerV5\Models\Story($data);

        $data = $story->toArray();
        $this->assertEquals([92,93,94], $data['owner_ids']);

        $data = [
            'name'      => 'Test Story',
            'owner_ids' => 95
        ];
        $story = new \PivotalTrackerV5\Models\Story($data);

        $data = $story->toArray();
        $this->assertEquals([95], $data['owner_ids']);
    }


    public function schemaValidationProvider()
    {
        return [
            [
                [
                    'project_id' => 1,
                    'name'       => 'Some story name',
                    'description'=> 'Story description',
                    'estimate'   => 4,
                ],
                true
            ],

            [
                [
                    'project_id' => 1,
                    'name'       => 'Some story name',
                    'description'=> 'Story description',
                    'estimate'  => '4',
                ],
                true
            ]
        ];
    }


    /**
     * @dataProvider conversionProvider
     *
     * @param $params
     * @param $expected
     */
    public function testConvertRequestToSchemaTypes($params, $expected)
    {
        $story = new \PivotalTrackerV5\Models\Story($params);

        $dto = $story->toDto();

        $this->validator->check($dto, $this->schema);

        foreach ($params as $k => $v)
        {
            $this->assertTrue((isset($dto->$k)));
        }

        $this->assertTrue($expected === $this->validator->isValid());
    }


    public function conversionProvider()
    {
        return [
            [
                [
                    'project_id' => 1,
                    'name'       => 'Some story name',
                    'description'=> 'Story description',
                    'estimate'  => 4,
                ],
                true
            ],

            [
                [
                    'project_id' => 1,
                    'name'       => 'Some story name',
                    'description'=> 'Story description',
                    'estimate'  => '4',
                ],
                true
            ],

            [
                [
                    'project_id' => 1,
                    'name'       => 'Some story name',
                    'description'=> 'Story description',
                    'estimate'  => '4',
                    'labels'    => 'test1,test2,test3'
                ],
                true
            ],

            [
                [
                    'project_id' => 1,
                    'name'       => 'Some story name',
                    'description'=> 'Story description',
                    'estimate'  => '4',
                    'labels'    => ['test1', 'test2', 'test3'],
                    'tasks'     => ['Task 1', 'Task 2', 'Task 3'],
                ],
                true
            ],

        ];
    }
}
