<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Converter;

use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ConverterRepositoryTest extends TestCase
{
    /**
     * @var ConverterRepository
     */
    protected $repository;

    public function setUp()
    {
        $this->repository = new ConverterRepository();
    }

    public function testConverterRepository()
    {
        $converter1 = $this->createMock(ConverterInterface::class);
        $converter1->expects($this->any())->method('getName')->will($this->returnValue('conv1'));

        $converter2 = $this->createMock(ConverterInterface::class);
        $converter2->expects($this->any())->method('getName')->will($this->returnValue('conv2'));

        $this->assertFalse($this->repository->hasConverter('conv1'));
        $this->assertFalse($this->repository->hasConverter('conv2'));

        $this->repository->addConverter($converter1);
        $this->assertTrue($this->repository->hasConverter('conv1'));
        $this->assertFalse($this->repository->hasConverter('conv2'));
        $this->assertEquals($converter1, $this->repository->getConverter('conv1'));

        $this->repository->addConverter($converter2);
        $this->assertTrue($this->repository->hasConverter('conv1'));
        $this->assertTrue($this->repository->hasConverter('conv2'));
        $this->assertEquals($converter1, $this->repository->getConverter('conv1'));
        $this->assertEquals($converter2, $this->repository->getConverter('conv2'));

        $this->repository->removeConverter('conv1');
        $this->assertFalse($this->repository->hasConverter('conv1'));
        $this->assertTrue($this->repository->hasConverter('conv2'));

        $this->repository->removeConverter('conv2');
        $this->assertFalse($this->repository->hasConverter('conv1'));
        $this->assertFalse($this->repository->hasConverter('conv2'));
    }
}
