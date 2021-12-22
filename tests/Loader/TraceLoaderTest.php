<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class TraceLoaderTest extends TestCase
{
    /**
     * @var TraceLoader
     */
    private $loader;

    private $mockLoader;

    public function setUp(): void
    {
        $this->mockLoader = $this->createMock(LoaderInterface::class);

        $this->mockLoader->expects($this->any())->method('load')
            ->with($this->anything())->will($this->returnCallback(function () {
                return new FixtureCollection();
            }));

        $this->loader = new TraceLoader($this->mockLoader);
    }

    public function testLoadFixture()
    {
        $this->assertEmpty($this->loader->getTrace());

        $path = realpath(__DIR__.'/../TestResources/chainFixtures');

        $this->loader->load([
            $path.'/roles.php',
            $path.'/user.yml',
            $path.'/groups.json',
        ]);

        $this->assertContains($path.'/roles.php', $this->loader->getTrace());
        $this->assertContains($path.'/user.yml', $this->loader->getTrace());
        $this->assertContains($path.'/groups.json', $this->loader->getTrace());

        $this->loader->reset();

        $this->assertEmpty($this->loader->getTrace());

        $this->loader->load($path.'/user.yml');
        $this->loader->load($path.'/roles.php');
        $this->loader->load($path.'/groups.json');

        $this->assertContains($path.'/roles.php', $this->loader->getTrace());
        $this->assertContains($path.'/user.yml', $this->loader->getTrace());
        $this->assertContains($path.'/groups.json', $this->loader->getTrace());
    }
}
