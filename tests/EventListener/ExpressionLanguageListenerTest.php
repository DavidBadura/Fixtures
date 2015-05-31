<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\EventListener\ExpressionLanguageListener;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use DavidBadura\Fixtures\ExpressionLanguage;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\AbstractFixtureTest;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ExpressionLanguageListenerTest extends AbstractFixtureTest
{

    /**
     *
     * @var Executor
     */
    private $executor;

    /**
     *
     * @var PersistListener
     */
    private $listener;

    public function setUp()
    {
        parent::setUp();
        $this->executor = $this->getMock('DavidBadura\Fixtures\Executor\ExecutorInterface');
        $this->listener = new ExpressionLanguageListener(new ExpressionLanguage($this->executor));
    }

    public function testExpressionLanguageListener()
    {
        $fixtures = new FixtureCollection(array(
            $this->createFixture('test1', array('key1' => array(
                'foo' => '@expr(1 + 4)',
            ))),
            $this->createFixture('test2', array('key2' => array(
                'test' => '@expr("foo" ~ "bar")'
            )))
        ));

        $event = new FixtureCollectionEvent($this->createFixtureManagerMock(), $fixtures);
        $this->listener->onPreExecute($event);

        $data1 = $fixtures->get('test1')->get('key1')->getData();
        $data2 = $fixtures->get('test2')->get('key2')->getData();

        $this->assertEquals(5, $data1['foo']);
        $this->assertEquals('foobar', $data2['test']);
    }

    /**
     * @expectedException DavidBadura\Fixtures\Exception\RuntimeException
     */ 
    public function testExpressionLanguageException()
    {
        $fixtures = new FixtureCollection(array(
            $this->createFixture('test1', array('key1' => array(
                'foo' => '@expr(1 + adsd +/ 2)',
            ))),
            ));

        $event = new FixtureCollectionEvent($this->createFixtureManagerMock(), $fixtures);
        $this->listener->onPreExecute($event);
    }

    public function testExpressionLanguageListenerCreateObject()
    {
        $fixtures = new FixtureCollection(array(
            $this->createFixture('test1', array('key1' => array(
                'foo' => '@expr(object("test2", "key2").test)',
            ))),
            $this->createFixture('test2', array('key2' => array(
                'test' => '@expr("foo" ~ "bar")'
            )))
        ));

        $this->executor
            ->expects($this->once())
            ->method('createObject')
            ->will($this->returnValue((object) array('test' => 'foobar')))
        ;

        $event = new FixtureCollectionEvent($this->createFixtureManagerMock(), $fixtures);
        $this->listener->onPreExecute($event);

        $data1 = $fixtures->get('test1')->get('key1')->getData();
        $data2 = $fixtures->get('test2')->get('key2')->getData();

        $this->assertEquals('foobar', $data1['foo']);
        $this->assertEquals('foobar', $data2['test']);
    }



}
