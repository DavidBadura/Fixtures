<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\EventListener;

use DavidBadura\Fixtures\AbstractFixtureTest;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ValidationListenerTest extends AbstractFixtureTest
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     *
     * @var ValidationListener
     */
    private $listener;

    public function setUp()
    {
        parent::setUp();
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validator->expects($this->never())->method('validate')->will($this->returnValue([]));

        $this->listener = new ValidationListener($this->validator);
    }

    public function testValidationListener()
    {
        $fixtures = new FixtureCollection([
            $this->createFixture('test1', ['key1' => 'data1']),
            $this->createFixture('test2', ['key2' => 'data2']),
        ]);

        $event = new FixtureCollectionEvent($this->createFixtureManagerMock(), $fixtures);
        $this->listener->onPostExecute($event);
    }
}
