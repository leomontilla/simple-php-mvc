<?php

namespace MVC\Console\Command;

use MVC\Console\Application;

use MVC\Console\Input\InputInterface;
use MVC\Console\Output\OutputInterface;

/**
 * Description of CommandInterface
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
interface CommandInterface
{

    public function getDefinition();
    
    public function getName();
    
    public function getAliases();
    
    public function isEnabled();

    public function run(InputInterface $input, OutputInterface $output);

    public function setApplication(Application $application);
    
}
