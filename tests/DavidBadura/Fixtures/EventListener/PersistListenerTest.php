<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\EventListener\PersistListener;
use DavidBadura\Fixtures\Event\PostExecuteEvent;
use DavidBadura\Fixtures\Persister\PersisterInterface;
use DavidBadura\Fixtures\FixtureCollection;
use DavidBadura\Fixtures\AbstractFixtureTest;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PersistListenerTest extends AbstractFixtureTest
{

    /**
     * @var PersisterInterface
     */
    private $persister;

    /**
     *
     * @var PersistListener
     */
    private $listener;

    public function setUp()
    {
        parent::setUp();
        $this->persister = $this->getMock('DavidBadura\Fixtures\Persister\PersisterInterface');
        $this->persister->expects($this->exactly(2))->method('addObject');
        $this->persister->expects($this->once())->method('save');

        $this->listener = new PersistListener($this->persister);
    }

    public function testPersistListener()
    {
        $fixtures = new FixtureCollection(array(
            $this->createFixture('test1', array('key1' => 'data1')),
            $this->createFixture('test2', array('key2' => 'data2'))
        ));

        $event = new PostExecuteEvent($fixtures, array());
        $this->listener->onPostExecute($event);
    }

}
