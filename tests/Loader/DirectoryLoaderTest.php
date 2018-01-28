<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Loader\DirectoryLoader;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DirectoryLoaderTest extends \PHPUnit\Framework\TestCase
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

        $path = realpath(__DIR__ . '/../TestResources/chainFixtures');

        $this->loader->load($path);

        $this->assertContains($path .'/roles.php', $files);
        $this->assertContains($path .'/user.yml', $files);
        $this->assertContains($path .'/groups.json', $files);
    }
}
