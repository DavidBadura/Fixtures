<?php declare(strict_types=1);

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
        $mockLoader = $this->createMock('DavidBadura\Fixtures\Loader\LoaderInterface');
        $loader = new FilterLoader($mockLoader);

        $fixture1 = $this->createFixture('test1', [], [
            'tags' => ['test', 'install'],
        ]);

        $fixture2 = $this->createFixture('test2', [], [
            'tags' => ['test'],
        ]);

        $fixture3 = $this->createFixture('test3', [], [
            'tags' => ['install'],
        ]);

        $fixture4 = $this->createFixture('test4');


        // empty tags
        $collection = new FixtureCollection([$fixture1, $fixture2, $fixture3, $fixture4]);
        $mockLoader->expects($this->any())->method('load')->will($this->returnValue($collection));
        $collection = $loader->load('');
        $this->assertEquals(new FixtureCollection([$fixture1, $fixture2, $fixture3, $fixture4]), $collection);

        // install
        $collection = new FixtureCollection([$fixture1, $fixture2, $fixture3, $fixture4]);
        $mockLoader->expects($this->any())->method('load')->will($this->returnValue($collection));
        $collection = $loader->load('', ['tags' => ['install']]);
        $this->assertEquals(new FixtureCollection([$fixture1, $fixture3]), $collection);

        // install, test
        $collection = new FixtureCollection([$fixture1, $fixture2, $fixture3, $fixture4]);
        $mockLoader->expects($this->any())->method('load')->will($this->returnValue($collection));
        $collection = $loader->load('', ['tags' => ['install', 'test']]);
        $this->assertEquals(new FixtureCollection([$fixture1, $fixture3]), $collection);
    }
}
