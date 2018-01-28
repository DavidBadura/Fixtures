<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\TestObjects;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class Post
{

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var \DateTime
     */
    private $date;

    /**
     *
     * @param string $name
     * @param \DateTime $date
     */
    public function __construct($name, \DateTime $date)
    {
        $this->name = $name;
        $this->date = $date;
    }

    public function getName()
    {
        return $this->name;
    }


    public function getDate()
    {
        return $this->date;
    }
}
