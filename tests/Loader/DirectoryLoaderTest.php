<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class DirectoryLoaderTest extends TestCase
{

    /**
     * @var DirectoryLoader
     */
    private $loader;

    private $mockLoader;

    public function setUp(): void
    {
        $this->mockLoader = $this->createMock(LoaderInterface::class);
        $this->loader = new DirectoryLoader($this->mockLoader);
    }

    public function testLoadFixturesByPath()
    {
        $files = [];

        $this->mockLoader->expects($this->any())->method('load')
            ->with($this->anything())->will($this->returnCallback(function ($var) use (&$files) {
                $files[] = $var;

                return new FixtureCollection();
            }));

        $path = realpath(__DIR__.'/../TestResources/chainFixtures');

        $this->loader->load($path);

        $this->assertCount(3, $files);
        $this->assertContains($path.DIRECTORY_SEPARATOR.'groups.json', $files);
        $this->assertContains($path.DIRECTORY_SEPARATOR.'roles.php', $files);
        $this->assertContains($path.DIRECTORY_SEPARATOR.'user.yml', $files);
    }
}
