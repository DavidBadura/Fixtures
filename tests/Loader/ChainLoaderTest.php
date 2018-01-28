<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Fixture\FixtureCollection;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ChainLoaderTest extends TestCase
{
    /**
     * @var ChainLoader
     */
    private $loader;

    private $mock1;

    private $mock2;

    public function setUp()
    {
        $this->mock1 = $this->createMock(LoaderInterface::class);
        $this->mock2 = $this->createMock(LoaderInterface::class);

        $this->loader = new ChainLoader([
            $this->mock1,
            $this->mock2,
        ]);
    }

    public function testLoadFixtures()
    {
        $path = __DIR__.'/../TestResources/chainFixtures';

        $this->mock1->expects($this->once())->method('load')
            ->with($this->equalTo($path))->will($this->returnCallback(function () {
                return new FixtureCollection();
            }));

        $this->mock2->expects($this->once())->method('load')
            ->with($this->equalTo($path))->will($this->returnCallback(function () {
                return new FixtureCollection();
            }));

        $this->loader->load(__DIR__.'/../TestResources/chainFixtures');
    }
}
