<?php

namespace DavidBadura\Fixtures\FixtureManager;

use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureManagerPublicMethods extends FixtureManager
{

    public function publicFilterByTags(FixtureCollection $collection, $tags)
    {
        $this->filterByTags($collection, $tags);
    }

    public function publicReplaceServicePlaceholder(FixtureCollection $collection)
    {
        $this->replaceServicePlaceholder($collection);
    }

    public function publicReplaceMultiPlaceholder(FixtureCollection $collection)
    {
        $this->replaceMultiPlaceholder($collection);
    }

}
