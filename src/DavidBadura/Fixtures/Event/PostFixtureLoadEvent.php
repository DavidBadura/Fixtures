<?php

namespace DavidBadura\Fixtures\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PostFixtureLoadEvent extends Event
{

    /**
     *
     * @var array
     */
    private $data;

    /**
     *
     * @var array
     */
    private $options;

    /**
     *
     * @param array $data
     * @param array $options
     */
    public function __construct(array $data, array $options = array())
    {
        $this->data = $data;
        $this->options = $options;
    }

    /**
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     * @param  array                                              $data
     * @return \DavidBadura\Fixtures\Event\PostExecuteEvent
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     *
     * @param  array                                              $options
     * @return \DavidBadura\Fixtures\Event\PostExecuteEvent
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

}
