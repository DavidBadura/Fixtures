<?php

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\Event\PreExecuteEvent;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class TagFilterListener
{

    /**
     *
     * @param PreExecuteEvent $event
     */
    public function onPreExecute(PreExecuteEvent $event)
    {
        $fixtures = $event->getFixtures();
        $options = $event->getOptions();

        if (empty($options['tags'])) {
            return;
        }

        if (!is_array($options['tags'])) {
            $options['tags'] = array($options['tags']);
        }

        foreach ($fixtures as $fixture) {
            foreach ($fixture->getTags() as $tag) {
                if (in_array($tag, $options['tags'])) {
                    continue 2;
                }
            }
            $fixtures->remove($fixture->getName());
        }
    }

}
