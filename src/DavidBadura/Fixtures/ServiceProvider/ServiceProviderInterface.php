<?php

namespace DavidBadura\Fixtures\ServiceProvider;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface ServiceProviderInterface
{

    /**
     * @param string
     * @return object
     */
    public function get($name);

    /**
     *
     * @param string $name
     * @param object $object
     */
    public function add($name, $object);

    /**
     *
     * @param  type    $name
     * @return boolean
     */
    public function has($name);

    /**
     *
     * @param type $name
     */
    public function remove($name);

}
