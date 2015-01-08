<?php

namespace MVC\Console;

use MVC\Console\Command\Command;
use MVC\Console\Input\ArgvInput;
use MVC\Console\Input\InputInterface;
use MVC\Console\Output\ConsoleOutput;
use MVC\Console\Output\OutputInterface;
use MVC\Module;
use MVC\MVC;
use MVC\MVCInterface;

/**
 * Description of Application
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class Application 
{
    /**
     * Commands array
     * 
     * @var array
     */
    private $commands = array();
    
    /**
     * MVC Application
     * 
     * @var MVC
     */
    private $mvc;
    
    /**
     * Console Application name
     * 
     * @var string
     */
    private $name;
    
    /**
     * Console Application version
     * 
     * @var string|integer
     */
    private $version;
    
    /**
     * Default Console Command Application
     * 
     * @var string
     */
    private $defaultComand;
    
    public function __construct(MVCInterface $mvc, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $this->mvc = $mvc;
        $this->name = $name;
        $this->version = $version;
        $this->defaultComand = 'list';
        
        foreach ($this->getDefaultCommands() as $command) {
            $this->add($command);
        }
    }
    
    /**
     * Adds a command object.
     *
     * @param Command $command
     *
     * @return Command
     */
    public function add(Command $command)
    {
        $command->setApplication($this);

        if (!$command->isEnabled()) {
            $command->setApplication(null);

            return;
        }

        if (null === $command->getDefinition()) {
            throw new \LogicException(sprintf('Command class "%s" is not correctly initialized. You probably forgot to call the parent constructor.', get_class($command)));
        }

        $this->commands[$command->getName()] = $command;

        foreach ($command->getAliases() as $alias) {
            $this->commands[$alias] = $command;
        }

        return $command;
    }
    
    /**
     * Get Default Commands
     * 
     * @return array Default Comands
     */
    protected function getDefaultCommands()
    {
        return array();
    }
    
    protected function registerCommands()
    {
        foreach ($this->mvc->getModules() as $module) {
            if ($module instanceof Module) {
                $module->registerCommands($this);
            }
        }
    }
    
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null === $input) {
            $input = new ArgvInput();
        }
        
        if (null === $output) {
            $output = new ConsoleOutput();
        }
    }
    
}
