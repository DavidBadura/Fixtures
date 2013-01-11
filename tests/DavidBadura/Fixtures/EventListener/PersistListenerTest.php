<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\EventListener\PersistListener;
use DavidBadura\Fixtures\Event\PostExecuteEvent;
use DavidBadura\Fixtures\Persister\PersisterInterface;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
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
        $collection = FixtureCollection::create(array(
            'test1'=> array('data' => array('key1' => 'data1')),
            'test2'=> array('data' => array('key2' => 'data2'))
        ));

        $event = new PostExecuteEvent($collection, array());
        $this->listener->onPostExecute($event);
    }

}
