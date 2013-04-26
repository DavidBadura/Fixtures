<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\AbstractFixtureTest;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FilterLoaderTest extends AbstractFixtureTest
{

    /**
     *
     * @var FixtureLoader
     */
    private $loader;

    public function testFilterLoader()
    {
        $mockLoader = $this->getMock('DavidBadura\Fixtures\Loader\LoaderInterface');
        $loader = new FilterLoader($mockLoader);

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


        // empty tags
        $collection = new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4));
        $mockLoader->expects($this->any())->method('load')->will($this->returnValue($collection));
        $collection = $loader->load('');
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4)), $collection);

        // install
        $collection = new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4));
        $mockLoader->expects($this->any())->method('load')->will($this->returnValue($collection));
        $collection = $loader->load('', array('tags' => array('install')));
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture3)), $collection);

        // install, test
        $collection = new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4));
        $mockLoader->expects($this->any())->method('load')->will($this->returnValue($collection));
        $collection = $loader->load('', array('tags' => array('install', 'test')));
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture3)), $collection);
    }

}
