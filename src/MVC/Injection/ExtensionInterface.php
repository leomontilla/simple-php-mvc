<?php

namespace MVC\Injection;

/**
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
interface ExtensionInterface
{
    
    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     */
    public function getNamespace();

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     */
    public function getAlias();
}
