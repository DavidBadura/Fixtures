<?php

namespace DavidBadura\Fixtures\EventListener;

use Symfony\Component\Validator\ValidatorInterface;
use DavidBadura\Fixtures\Event\PostExecuteEvent;
use DavidBadura\Fixtures\Exception\ValidationException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ValidationListener
{

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     *
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     *
     * @param PostExecuteEvent $event
     */
    public function onPostExecute(PostExecuteEvent $event)
    {
        $collection = $event->getCollection();

        foreach ($collection as $fixture) {

            $properties = $fixture->getProperties();

            if (!isset($properties['validation_enabled']) || !$properties['validation_enabled']) {
                continue;
            }

            foreach ($fixture as $data) {
                $object = $data->getObject();

                if (!$object) {
                    continue;
                }

                $violationList = $this->validator->validate($object, $fixture->getValidationGroups());

                if (count($violationList) != 0) {
                    throw new ValidationException($fixture->getName(), $data->getKey(), $violationList);
                }
            }
        }
    }

}
