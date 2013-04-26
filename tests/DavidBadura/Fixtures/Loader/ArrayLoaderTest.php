<?php

namespace DavidBadura\Fixtures\Loader;

use DavidBadura\Fixtures\Loader\ArrayLoader;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ArrayLoaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var FixtureLoader
     */
    private $loader;

    private $mockLoader;

    public function setUp()
    {
        $this->mockLoader = $this->getMock('DavidBadura\Fixtures\Loader\LoaderInterface');
        $this->loader = new ArrayLoader($this->mockLoader);
    }

    public function testLoadFixture()
    {
        $files = array();

        $this->mockLoader->expects($this->exactly(3))->method('load')
            ->with($this->anything())->will($this->returnCallback(function($var) use (&$files) {
                $files[] = $var;
                return new FixtureCollection();
        }));

        $path = realpath(__DIR__ . '/../TestResources/chainFixtures');

        $this->loader->load(array(
            $path .'/roles.php',
            $path .'/user.yml',
            $path .'/groups.json'
        ));

        $this->assertContains($path .'/roles.php', $files);
        $this->assertContains($path .'/user.yml', $files);
        $this->assertContains($path .'/groups.json', $files);
    }

}
