<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Loader\TraceLoader;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class TraceLoaderTest extends \PHPUnit\Framework\TestCase
{

    /**
     *
     * @var FixtureLoader
     */
    private $loader;

    private $mockLoader;

    public function setUp()
    {
        $this->mockLoader = $this->createMock('DavidBadura\Fixtures\Loader\LoaderInterface');

        $this->mockLoader->expects($this->any())->method('load')
            ->with($this->anything())->will($this->returnCallback(function () {
                return new FixtureCollection();
            }));

        $this->loader = new TraceLoader($this->mockLoader);
    }

    public function testLoadFixture()
    {
        $this->assertEmpty($this->loader->getTrace());

        $path = realpath(__DIR__ . '/../TestResources/chainFixtures');

        $this->loader->load([
            $path .'/roles.php',
            $path .'/user.yml',
            $path .'/groups.json',
        ]);

        $this->assertContains($path .'/roles.php', $this->loader->getTrace());
        $this->assertContains($path .'/user.yml', $this->loader->getTrace());
        $this->assertContains($path .'/groups.json', $this->loader->getTrace());

        $this->loader->reset();

        $this->assertEmpty($this->loader->getTrace());

        $this->loader->load($path .'/user.yml');
        $this->loader->load($path .'/roles.php');
        $this->loader->load($path .'/groups.json');

        $this->assertContains($path .'/roles.php', $this->loader->getTrace());
        $this->assertContains($path .'/user.yml', $this->loader->getTrace());
        $this->assertContains($path .'/groups.json', $this->loader->getTrace());
    }
}
