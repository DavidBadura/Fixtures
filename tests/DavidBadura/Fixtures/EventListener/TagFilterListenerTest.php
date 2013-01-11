<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\EventListener\TagFilterListener;
use DavidBadura\Fixtures\Event\PreExecuteEvent;
use DavidBadura\Fixtures\AbstractFixtureTest;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class TagFilterListenerTest extends AbstractFixtureTest
{

    /**
     *
     * @var type
     */
    protected $listener;

    public function setUp()
    {
        parent::setUp();
        $this->listener = new TagFilterListener();
    }

    public function testTagFilterListener()
    {
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

}
