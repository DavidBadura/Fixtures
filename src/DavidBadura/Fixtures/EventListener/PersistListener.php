<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\Persister\PersisterInterface;
use DavidBadura\Fixtures\Event\PostExecuteEvent;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PersistListener
{

    /**
     * @var PersisterInterface
     */
    private $persister;

    /**
     *
     * @param PersisterInterface $persister
     */
    public function __construct(PersisterInterface $persister)
    {
        $this->persister = $persister;
    }

    /**
     *
     * @return PersisterInterface
     */
    public function getPersister()
    {
        return $this->persister;
    }

    /**
     *
     * @param PostExecuteEvent $event
     */
    public function onPostExecute(PostExecuteEvent $event)
    {
        $options = $event->getOptions();

        if (isset($options['test']) && $options['test'] == true) {
            return;
        }

        $fixtures = $event->getFixtures();

        foreach ($fixtures as $fixture) {
            foreach ($fixture as $data) {
                $this->persister->addObject($data->getObject());
            }
        }

        $this->persister->save();
    }

}
