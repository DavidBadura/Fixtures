<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\AbstractFixtureTest;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use DavidBadura\Fixtures\Exception\RuntimeException;
use DavidBadura\Fixtures\Executor\ExecutorInterface;
use DavidBadura\Fixtures\ExpressionLanguage;
use DavidBadura\Fixtures\Fixture\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ExpressionLanguageListenerTest extends AbstractFixtureTest
{
    /**
     * @var ExecutorInterface
     */
    private $executor;

    /**
     * @var ExpressionLanguageListener
     */
    private $listener;

    public function setUp()
    {
        parent::setUp();
        $this->executor = $this->createMock(ExecutorInterface::class);
        $this->listener = new ExpressionLanguageListener(new ExpressionLanguage($this->executor));
    }

    public function testExpressionLanguageListener()
    {
        $fixtures = new FixtureCollection([
            $this->createFixture('test1', [
                'key1' => [
                    'foo' => '@expr(1 + 4)',
                ],
            ]),
            $this->createFixture('test2', [
                'key2' => [
                    'test' => '@expr("foo" ~ "bar")',
                ],
            ]),
        ]);

        $event = new FixtureCollectionEvent($this->createFixtureManagerMock(), $fixtures);
        $this->listener->onPreExecute($event);

        $data1 = $fixtures->get('test1')->get('key1')->getData();
        $data2 = $fixtures->get('test2')->get('key2')->getData();

        $this->assertEquals(5, $data1['foo']);
        $this->assertEquals('foobar', $data2['test']);
    }

    public function testExpressionLanguageException()
    {
        $this->expectException(RuntimeException::class);

        $fixtures = new FixtureCollection([
            $this->createFixture('test1', [
                'key1' => [
                    'foo' => '@expr(1 + adsd +/ 2)',
                ],
            ]),
        ]);

        $event = new FixtureCollectionEvent($this->createFixtureManagerMock(), $fixtures);
        $this->listener->onPreExecute($event);
    }

    public function testExpressionLanguageListenerCreateObject()
    {
        $fixtures = new FixtureCollection([
            $this->createFixture('test1', [
                'key1' => [
                    'foo' => '@expr(object("test2", "key2").test)',
                ],
            ]),
            $this->createFixture('test2', [
                'key2' => [
                    'test' => '@expr("foo" ~ "bar")',
                ],
            ]),
        ]);

        $this->executor
            ->expects($this->once())
            ->method('createObject')
            ->will($this->returnValue((object)['test' => 'foobar']));

        $event = new FixtureCollectionEvent($this->createFixtureManagerMock(), $fixtures);
        $this->listener->onPreExecute($event);

        $data1 = $fixtures->get('test1')->get('key1')->getData();
        $data2 = $fixtures->get('test2')->get('key2')->getData();

        $this->assertEquals('foobar', $data1['foo']);
        $this->assertEquals('foobar', $data2['test']);
    }
}
