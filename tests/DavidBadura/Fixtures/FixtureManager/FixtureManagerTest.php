<?php

namespace DavidBadura\Fixtures\FixtureManager;

use DavidBadura\Fixtures\FixtureManager\FixtureManager;
use DavidBadura\Fixtures\Loader\LoaderInterface;
use DavidBadura\Fixtures\Persister\PersisterInterface;
use DavidBadura\Fixtures\Executor\ExecutorInterface;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\AbstractFixtureTest;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureManagerTest extends AbstractFixtureTest
{

    /**
     *
     * @var FixtureManager
     */
    private $fixtureManager;

    /**
     *
     * @var LoaderInterface
     */
    private $loader;

    /**
     *
     * @var ExecutorInterface
     */
    private $executor;

    /**
     * @var PersisterInterface
     */
    private $persister;

    /**
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    public function setUp()
    {
        parent::setUp();

        $this->loader = $this->getMock('DavidBadura\Fixtures\Loader\LoaderInterface');
        $this->loader->expects($this->once())->method('load')->will($this->returnValue(new FixtureCollection()));

        $this->executor = $this->getMock('DavidBadura\Fixtures\Executor\ExecutorInterface');
        $this->executor->expects($this->once())->method('execute');

        $this->persister = $this->getMock('DavidBadura\Fixtures\Persister\PersisterInterface');
        $this->persister->expects($this->any())->method('addObject');
        $this->persister->expects($this->once())->method('save');

        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->eventDispatcher->expects($this->exactly(5))->method('dispatch');

        $this->fixtureManager = new FixtureManager($this->loader, $this->executor, $this->persister, $this->eventDispatcher);
    }

    public function testFixtureManager()
    {
        $this->fixtureManager->load(null);
    }

    public function testFilterByTags()
    {
        $this->markTestIncomplete();
        $fixture1 = $this->createFixture('test1', array(), array(
            'tags' => array('test', 'install')
        ));

        $fixture2 = $this->createFixture('test2', array(), array(
            'tags' => array('test')
        ));

        $fixture3 = $this->createFixture('test3', array(), array(
            'tags' => array('install')
        ));

        $fixture4 = $this->createFixture('test4');

        $event = new PreExecuteEvent(new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4)), array('tags' => array()));
        $this->listener->onPreExecute($event);
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4)), $event->getCollection());

        $event = new PreExecuteEvent(new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4)), array('tags' => array('install')));
        $this->listener->onPreExecute($event);
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture3)), $event->getCollection());

        $event = new PreExecuteEvent(new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4)), array('tags' => array('install', 'test')));
        $this->listener->onPreExecute($event);
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture2, $fixture3)), $event->getCollection());
    }

    public function testPersistListener()
    {
        $this->markTestIncomplete();
        $collection = FixtureCollection::create(array(
            'test1'=> array('data' => array('key1' => 'data1')),
            'test2'=> array('data' => array('key2' => 'data2'))
        ));

        $event = new PostExecuteEvent($collection, array());
        $this->listener->onPostExecute($event);
    }

}
