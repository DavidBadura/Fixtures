<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\ServiceProvider;

use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ServiceProviderTest extends TestCase
{

    /**
     * @var ServiceProvider
     */
    protected $provider;

    public function setUp(): void
    {
        $this->provider = new ServiceProvider();
    }

    public function testServiceProvider()
    {
        $service1 = 'test_service1';
        $service2 = 'test_service2';

        $this->assertFalse($this->provider->has('service1'));
        $this->assertFalse($this->provider->has('service2'));

        $this->provider->add('service1', $service1);
        $this->assertTrue($this->provider->has('service1'));
        $this->assertFalse($this->provider->has('service2'));
        $this->assertEquals($service1, $this->provider->get('service1'));

        $this->provider->add('service2', $service2);
        $this->assertTrue($this->provider->has('service1'));
        $this->assertTrue($this->provider->has('service2'));
        $this->assertEquals($service1, $this->provider->get('service1'));
        $this->assertEquals($service2, $this->provider->get('service2'));

        $this->provider->remove('service1');
        $this->assertFalse($this->provider->has('service1'));
        $this->assertTrue($this->provider->has('service2'));

        $this->provider->remove('service2');
        $this->assertFalse($this->provider->has('conv1'));
        $this->assertFalse($this->provider->has('service2'));
    }
}
