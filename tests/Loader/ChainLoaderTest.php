<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Loader\ChainLoader;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ChainLoaderTest extends \PHPUnit\Framework\TestCase
{

    /**
     *
     * @var FixtureLoader
     */
    private $loader;

    private $mock1;

    private $mock2;

    public function setUp()
    {
        $this->mock1 = $this->createMock('DavidBadura\Fixtures\Loader\LoaderInterface');
        $this->mock2 = $this->createMock('DavidBadura\Fixtures\Loader\LoaderInterface');

        $this->loader = new ChainLoader([
            $this->mock1,
            $this->mock2,
        ]);
    }

    public function testLoadFixtures()
    {
        $path = __DIR__ . '/../TestResources/chainFixtures';

        $this->mock1->expects($this->once())->method('load')
            ->with($this->equalTo($path))->will($this->returnCallback(function ($var) {
                return new FixtureCollection();
            }));

        $this->mock2->expects($this->once())->method('load')
            ->with($this->equalTo($path))->will($this->returnCallback(function ($var) {
                return new FixtureCollection();
            }));

        $this->loader->load(__DIR__ . '/../TestResources/chainFixtures');
    }
}
