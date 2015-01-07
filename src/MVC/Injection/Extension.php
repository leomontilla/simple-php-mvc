<?php

namespace MVC\Injection;

/**
 * Description of Extension
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class Extension implements ExtensionInterface
{
    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * This convention is to remove the "Extension" postfix from the class
     * name and then lowercase and underscore the result. So:
     *
     *     AcmeHelloExtension
     *
     * becomes
     *
     *     acme_hello
     *
     * This can be overridden in a sub-class to specify the alias manually.
     *
     * @return string The alias
     *
     * @throws \BadMethodCallException When the extension name does not follow conventions
     */
    public function getAlias()
    {
        $className = get_class($this);
        if (substr($className, -9) != 'Extension') {
            throw new \BadMethodCallException('This extension does not follow the naming convention; you must overwrite the getAlias() method.');
        }
        $classBaseName = substr(strrchr($className, '\\'), 1, -9);

        return Container::underscore($classBaseName);
    }

    public function getNamespace()
    {
        return 'http://example.org/schema/dic/' . $this->getAlias();
    }

}
