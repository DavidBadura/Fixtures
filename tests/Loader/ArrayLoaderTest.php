<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ArrayLoaderTest extends TestCase
{
    /**
     * @var ArrayLoader
     */
    private $loader;

    private $mockLoader;

    public function setUp(): void
    {
        $this->mockLoader = $this->createMock(LoaderInterface::class);
        $this->loader = new ArrayLoader($this->mockLoader);
    }

    public function testLoadFixture()
    {
        $files = [];

        $this->mockLoader->expects($this->exactly(3))->method('load')
            ->with($this->anything())->will($this->returnCallback(function ($var) use (&$files) {
                $files[] = $var;

                return new FixtureCollection();
            }));

        $path = realpath(__DIR__.'/../TestResources/chainFixtures');

        $this->loader->load([
            $path.'/roles.php',
            $path.'/user.yml',
            $path.'/groups.json',
        ]);

        $this->assertContains($path.'/roles.php', $files);
        $this->assertContains($path.'/user.yml', $files);
        $this->assertContains($path.'/groups.json', $files);
    }
}
