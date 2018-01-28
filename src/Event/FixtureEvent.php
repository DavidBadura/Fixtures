<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Event;

use Symfony\Component\EventDispatcher\Event;
use DavidBadura\Fixtures\FixtureManager\FixtureManagerInterface;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureEvent extends Event
{
    private $fixtureManager;
    private $options;

    public function __construct(FixtureManagerInterface $fixtureManager, array $options = [])
    {
        $this->fixtureManager = $fixtureManager;
        $this->options = $options;
    }

    public function getFixtureManager(): FixtureManagerInterface
    {
        return $this->fixtureManager;
    }

    public function setFixtureManager(FixtureManagerInterface $fixtureManager): void
    {
        $this->fixtureManager = $fixtureManager;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
