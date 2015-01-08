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

    private $applicationDefinitionMerged = false;
    private $applicationDefinitionMergedWithArgs = false;

    /**
     * Command code
     *
     * @var int
     */
    protected $code;
    
    /**
     * Command InputDefinition
     * 
     * @var InputDefinition
     */
    protected $definition;
    
    /**
     * Command description
     * 
     * @var string
     */
    protected $description;
    
    /**
     * Text Command Help
     * 
     * @var string
     */
    protected $help;

    /**
     * Ignore Validation Errors
     *
     * @var boolean
     */
    protected $ignoreValidationErrors = false;
    
    /**
     * Command name
     * 
     * @var string Command name
     */
    protected $name;

    /**
     * Process title
     *
     * @var boolean
     */
    protected $processTitle = true;
    
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
     * Execute current command that should be override
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        throw new \LogicException('You must override the execute() method in the concrete command class.');
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
     * Get command description
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * Get native InputDefinition
     * 
     * @return InputDefinition
     */
    public function getNativeDefinition()
    {
        return $this->getDefinition();
    }

    public function getSynopsis()
    {
        if (null === $this->synopsis) {
            $this->synopsis = trim(sprintf('%s %s', $this->name, $this->definition->getSynopsis()));
        }

        return $this->synopsis;
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
     * @throws \Exception
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        // force the creation of the synopsis before the merge with the app definition
        $this->synopsis = $this->getSynopsis();

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
     * Set commadn aliases
     * 
     * @param array $aliases
     * @return Command
     */
    public function setAliases(array $aliases)
    {
        $this->aliases = $aliases;
        
        return $this;
    }

    /**
     * Set console application
     * 
     * @param Application $application
     * @return Command
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
        
        return $this;
    }
    
    /**
     * Set Command InputDefinition
     * 
     * @param InputDefinition $definition
     * @return Command
     */
    public function setDefinition(InputDefinition $definition)
    {
        $this->definition = $definition;
        
        return $this;
    }
    
    /**
     * Set Command description
     * 
     * @param string $description
     * @return Command
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }
    
    /**
     * Set Command name
     * 
     * @param string $name Command name
     * @return Command
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
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
