<?php

namespace MVC\Console\Command;

use MVC\Console\Application;
use MVC\Console\Input\InputDefinition;
use MVC\Console\Input\InputInterface;
use MVC\Console\Output\OutputInterface;

/**
 * Command Application for create files of the application
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @package MVC
 */
class Command implements CommandInterface
{

    /**
     * Command aliases
     * 
     * @var array
     */
    protected $aliases = array();
    
    /**
     * Console Application
     * 
     * @var Application
     */
    protected $application;
    
    /**
     * Text Command Help
     * 
     * @var string
     */
    protected $help;
    
    /**
     *
     * @var string Command name
     */
    protected $name;
    
    /**
     *
     * @var InputDefinition
     */
    protected $definition;
    
    protected $synopsis;

    /**
     * Construct of commands
     * 
     * @param string $name Command name
     * @throws \LogicException
     */
    public function __construct($name = null)
    {
        $this->definition = new InputDefinition();
        
        if (null !== $name) {
            $this->setName($name);
        }
        
        $this->configure();
        
        if (!$this->name) {
            throw new \LogicException(sprintf('The command defined in "%s" cannot have an empty name.', get_class($this)));
        }
    }
    
    /**
     * Add new Input Argument
     * 
     * @param string $name
     * @param int $mode
     * @param string $description
     * @param string $default
     * @return Command
     */
    public function addArgument($name, $mode = null, $description = '', $default = null)
    {
        $this->definition->addArgument(new InputArgument($name, $mode, $description, $default));

        return $this;
    }
    
    /**
     * Add new Input Option
     * 
     * @param string $name
     * @param string $shortcut
     * @param int $mode
     * @param string $description
     * @param string $default
     * @return Command
     */
    public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
    {
        $this->definition->addOption(new InputOption($name, $shortcut, $mode, $description, $default));

        return $this;
    }
    
    /**
     * Configures the current command.
     */
    protected function configure()
    {
    }

    /**
     * Get Command aliases
     * 
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }
    
    /**
     * Get Console Application
     * 
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Get Input Definition
     * 
     * @return InputDefinition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Get Command name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 
     * @return boolean
     */
    public function isEnabled()
    {
        return true;
    }
    
    /**
     * Initializes the command after the input is validated
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
    }
    
    /**
     * Interacts with the user
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
    }
    
    public function mergeApplicationDefinition($mergeArgs = true)
    {
        if (null === $this->application || (true === $this->applicationDefinitionMerged && ($this->applicationDefinitionMergedWithArgs || !$mergeArgs))) {
            return;
        }

        if ($mergeArgs) {
            $currentArguments = $this->definition->getArguments();
            $this->definition->setArguments($this->application->getDefinition()->getArguments());
            $this->definition->addArguments($currentArguments);
        }

        $this->definition->addOptions($this->application->getDefinition()->getOptions());

        $this->applicationDefinitionMerged = true;
        if ($mergeArgs) {
            $this->applicationDefinitionMergedWithArgs = true;
        }
    }

    /**
     * Runs command application
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        // force the creation of the synopsis before the merge with the app definition
        $this->synopsis = trim(sprintf('%s %s', $this->name, $this->definition->getSynopsis()));

        // add the application arguments and options
        $this->mergeApplicationDefinition();

        // bind the input against the command specific arguments/options
        try {
            $input->bind($this->definition);
        } catch (\Exception $e) {
            if (!$this->ignoreValidationErrors) {
                throw $e;
            }
        }

        $this->initialize($input, $output);

        if (null !== $this->processTitle) {
            if (function_exists('cli_set_process_title')) {
                cli_set_process_title($this->processTitle);
            } elseif (function_exists('setproctitle')) {
                setproctitle($this->processTitle);
            } elseif (OutputInterface::VERBOSITY_VERY_VERBOSE === $output->getVerbosity()) {
                $output->writeln('<comment>Install the proctitle PECL to be able to change the process title.</comment>');
            }
        }

        if ($input->isInteractive()) {
            $this->interact($input, $output);
        }

        $input->validate();

        if ($this->code) {
            $statusCode = call_user_func($this->code, $input, $output);
        } else {
            $statusCode = $this->execute($input, $output);
        }

        return is_numeric($statusCode) ? (int) $statusCode : 0;
    }

    /**
     * Set console application
     * 
     * @param Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }
    
    /**
     * Set Command name
     * 
     * @param string $name Command name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Set text command help
     * 
     * @param string $help
     * @return Command
     */
    public function setHelp($help)
    {
        $this->help = $help;

        return $this;
    }

}
