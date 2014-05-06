<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Loader\PuliLoader;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Webmozart\Puli\Repository\ResourceRepository;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PuliLoaderTest extends \PHPUnit_Framework_TestCase
{

    private $repo;
    private $mockLoader;

    /**
     *
     * @var FixtureLoader
     */
    private $loader;

    public function setUp()
    {
        $this->repo = new ResourceRepository();
        $this->mockLoader = $this->getMock('DavidBadura\Fixtures\Loader\LoaderInterface');
        $this->loader = new PuliLoader($this->repo, $this->mockLoader);
    }

    public function testLoadFixture()
    {
        $this->repo->add('/test/fixtures', __DIR__ . '/../TestResources/fixtures/');

        $this->mockLoader->expects($this->any())->method('load')
            ->with($this->equalTo(realpath(__DIR__ . '/../TestResources/fixtures/user.yml')))->will($this->returnValue(true));

        $data = $this->loader->load('/test/fixtures/user.yml');

        $this->assertEquals(true, $data);
    }

    public function testLoadFixtureByTags()
    {
        $this->repo->add('/test/fixtures', __DIR__ . '/../TestResources/fixtures/');
        $this->repo->tag('/test/fixtures/*', 'test/fixtures');

        $path  = realpath(__DIR__ . '/../TestResources/fixtures') . '/*';
        $paths = glob($path);

        $this->mockLoader->expects($this->any())->method('load')
            ->with($this->equalTo($paths))->will($this->returnValue(true));

        $data = $this->loader->load('test/fixtures', array('puli_tag' => true));

        $this->assertEquals(true, $data);
    }
}
