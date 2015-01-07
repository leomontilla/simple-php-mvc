<?php

namespace EjemploModule\Controller;

/**
 * Description of DefaultController
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class DefaultController
{
    public function ejemploJsonAction()
    {
        return 'Ejemplo Json Route response';
    }
    
    public function ejemploPhpAction()
    {
        return 'Ejemplo Php Route response';
    }
    
    public function indexAction()
    {
        return 'Ejemplo App Response';
    }
}
