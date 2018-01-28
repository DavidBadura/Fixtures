<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\EventListener;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use DavidBadura\Fixtures\Util\ObjectAccess\ObjectAccess;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class SecurityListener
{
    private $factory;

    public function __construct(EncoderFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function getEncoderFactory(): EncoderFactoryInterface
    {
        return $this->factory;
    }

    public function onPostExecute(FixtureCollectionEvent $event): void
    {
        $fixtures = $event->getCollection();

        foreach ($fixtures as $fixture) {
            $properties = $fixture->getProperties();

            if (!isset($properties['security']) || $properties['security'] == false) {
                continue;
            }

            $passwordField = isset($properties['security']['password']) ? $properties['security']['password'] : 'password';
            $saltField = isset($properties['security']['salt']) ? $properties['security']['salt'] : 'salt';

            foreach ($fixture as $data) {
                $object = $data->getObject();

                if (!$object) {
                    continue;
                }

                $encoder = $this->factory->getEncoder($object);
                $access = new ObjectAccess($object);

                $password = $encoder->encodePassword(
                    $access->readProperty($passwordField),
                    $access->readProperty($saltField)
                );
                $access->writeProperty($passwordField, $password);
            }
        }
    }
}
