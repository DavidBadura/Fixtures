<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Util\ObjectAccess;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class AccessObject
{
    public $publicTestProperty;

    protected $protectedTestProperty;

    public $arrayCollection;

    public $setPublicTestMethodVar;

    public $addPublicTestMethodArrayVar = [];

    public $setDateTimeMethod;

    public $addDateTimeMethod = [];

    public function __construct()
    {
        $this->arrayCollection = new ArrayCollection();
    }

    public function setPublicTestMethod($var)
    {
        $this->setPublicTestMethodVar = $var;
    }

    protected function setProtectedTestMethod()
    {
    }

    public function addPublicTestMethodArray($var)
    {
        $this->addPublicTestMethodArrayVar[] = $var;
    }

    protected function addProtectedTestMethodArray()
    {
    }

    public function getPublicArrayCollection()
    {
        return $this->arrayCollection;
    }

    protected function getProtectedArrayCollection()
    {
    }

    public function getPublicNonArrayCollection()
    {
        return null;
    }

    public function setDateTimeMethod(\DateTime $dateTime)
    {
        $this->setDateTimeMethod = $dateTime;
    }

    public function addMultiDateTimeMethod(\DateTime $dateTime)
    {
        $this->addDateTimeMethod[] = $dateTime;
    }
}
