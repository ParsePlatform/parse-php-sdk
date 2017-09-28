<?php

namespace Parse\Test;

use Parse\ParseACL;
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;

class ParseQueryAggregateTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        Helper::setUp();
    }

    public function setUp()
    {
        Helper::clearClass('TestObject');
    }

    public function tearDown()
    {
        Helper::tearDown();
    }

    /**
     * This function used as a helper function in test functions
     */
    public function loadObjects()
    {
        $obj1 = new ParseObject('TestObject');
        $obj2 = new ParseObject('TestObject');
        $obj3 = new ParseObject('TestObject');
        $obj4 = new ParseObject('TestObject');

        $obj1->set('score', 10);
        $obj2->set('score', 10);
        $obj3->set('score', 10);
        $obj4->set('score', 20);

        $obj1->set('name', 'foo');
        $obj2->set('name', 'foo');
        $obj3->set('name', 'bar');
        $obj4->set('name', 'dpl');

        $objects = [$obj1, $obj2, $obj3, $obj4];
        ParseObject::saveAll($objects);
    }

    public function testDistinctQuery()
    {
        $this->loadObjects();
        $query = new ParseQuery('TestObject');
        $results = $query->distinct('score');

        $this->assertEquals(2, count($results));
        $this->assertEquals($results[0], 10);
        $this->assertEquals($results[1], 20);
    }

    public function testDistinctWhereQuery()
    {
        $this->loadObjects();
        $query = new ParseQuery('TestObject');
        $query->equalTo('name', 'foo');
        $results = $query->distinct('score');

        $this->assertEquals(1, count($results));
        $this->assertEquals($results[0], 10);
    }

    public function testDistinctClassNotExistQuery()
    {
        $this->loadObjects();
        $query = new ParseQuery('UnknownClass');
        $results = $query->distinct('score');

        $this->assertEquals(0, count($results));
    }

    public function testDistinctFieldNotExistQuery()
    {
        $this->loadObjects();
        $query = new ParseQuery('TestObject');
        $results = $query->distinct('unknown');

        $this->assertEquals(0, count($results));
    }

    public function testAggregateGroupQuery()
    {
        $pipeline = [
            'group' => [
                'objectId' => '$name'
            ]
        ];
        $this->loadObjects();
        $query = new ParseQuery('TestObject');
        $results = $query->aggregate($pipeline);

        $this->assertEquals(3, count($results));
    }

    public function testAggregateGroupClassNotExistQuery()
    {
        $pipeline = [
            'group' => [
                'objectId' => '$name'
            ]
        ];
        $this->loadObjects();
        $query = new ParseQuery('UnknownClass');
        $results = $query->aggregate($pipeline);

        $this->assertEquals(0, count($results));
    }

    public function testAggregateGroupFieldNotExistQuery()
    {
        $pipeline = [
            'group' => [
                'objectId' => '$unknown'
            ]
        ];
        $this->loadObjects();
        $query = new ParseQuery('UnknownClass');
        $results = $query->aggregate($pipeline);

        $this->assertEquals(0, count($results));
    }

    public function testAggregateMatchQuery()
    {
        $pipeline = [
            'match' => [
                'score' => [ '$gt' => 15 ]
            ]
        ];
        $this->loadObjects();
        $query = new ParseQuery('TestObject');
        $results = $query->aggregate($pipeline);

        $this->assertEquals(1, count($results));
        $this->assertEquals(20, $results[0]['score']);
    }

    public function testAggregateProjectQuery()
    {
        $pipeline = [
            'project' => [
                'name' => 1
            ]
        ];
        $this->loadObjects();
        $query = new ParseQuery('TestObject');
        $results = $query->aggregate($pipeline);

        foreach ($results as $result) {
            $this->assertEquals(array_key_exists('name', $result), true);
            $this->assertEquals(array_key_exists('objectId', $result), true);
            $this->assertEquals(array_key_exists('score', $result), false);
        }
    }

    public function testAggregatePipelineInvalid()
    {
        $pipeline = [
            'unknown' => []
        ];
        $this->loadObjects();
        $query = new ParseQuery('TestObject');
        $this->setExpectedException(
            'Parse\ParseException',
            'Invalid parameter for query: unknown',
            102
        );
        $results = $query->aggregate($pipeline);
    }

    public function testAggregateGroupInvalid()
    {
        $pipeline = [
            'group' => [
                '_id' => '$name'
            ]
        ];
        $this->loadObjects();
        $query = new ParseQuery('TestObject');
        $this->setExpectedException(
            'Parse\ParseException',
            'Invalid parameter for query: group. Please use objectId instead of _id',
            102
        );
        $results = $query->aggregate($pipeline);
    }

     public function testAggregateGroupObjectIdRequired()
    {
        $pipeline = [
            'group' => []
        ];
        $this->loadObjects();
        $query = new ParseQuery('TestObject');
        $this->setExpectedException(
            'Parse\ParseException',
            'Invalid parameter for query: group. objectId is required',
            102
        );
        $results = $query->aggregate($pipeline);
    }
}
