<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\TestObjects;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class Post
{
    private $name;
    private $date;

    public function __construct(string $name, \DateTime $date)
    {
        $this->name = $name;
        $this->date = $date;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }
}
