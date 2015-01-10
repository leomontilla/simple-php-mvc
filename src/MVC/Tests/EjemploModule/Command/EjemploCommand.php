<?php

namespace MVC\Tests\EjemploModule\Command;

use MVC\Console\Command\Command;

/**
 * Description of EjemploCommand
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class EjemploCommand extends Command
{

    protected function configure()
    {
        $this->setName('ejemplo')
            ->setDescription('Ejemplo de comando')
            ->setHelp(<<<EOF
Este es un ejemplo de comando
EOF
        );
    }

}
