<?php

namespace MVC\Provider;

/**
 * Description of Provider
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
abstract class Provider implements ProviderInterface
{
    /**
     * The provider name
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Returns the provider name (the class short name).
     *
     * @return string The Provider name
     */
    final public function getName()
    {
        if (null !== $this->name) {
            return $this->name;
        }

        $name = get_class($this);
        $pos = strrpos($name, '\\');

        return $this->name = false === $pos ? $name : substr($name, $pos + 1);
    }
}
