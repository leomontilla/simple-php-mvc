<?php

namespace MVC\Console;

use MVC\Console\Command\Command;
use MVC\Console\Command\ListCommand;
use MVC\Console\Input\ArgvInput;
use MVC\Console\Input\ArrayInput;
use MVC\Console\Input\InputArgument;
use MVC\Console\Input\InputDefinition;
use MVC\Console\Input\InputInterface;
use MVC\Console\Input\InputOption;
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
     * Auto exit console application
     * 
     * @var boolean
     */
    protected $autoExit = true;

    /**
     * @var boolean
     */
    protected $catchExceptions = true;
    
    /**
     * Commands array
     * 
     * @var array
     */
    protected $commands = array();

    /**
     * Modules Command Registered
     *
     * @var bool
     */
    protected $commandsRegistered = false;
    
    /**
     * Default Console Command Application
     * 
     * @var string
     */
    protected $defaultComand;

    /**
     * @var InputDefinition
     */
    protected $definition;
    
    /**
     * MVC Application
     * 
     * @var MVC
     */
    protected $mvc;
    
    /**
     * Console Application name
     * 
     * @var string
     */
    protected $name;
    
    /**
     * @var Command
     */
    protected $runningCommand;
    
    /**
     * Console Application version
     * 
     * @var string|integer
     */
    protected $version;

    /**
     * Console Application Construct
     *
     * @param MVCInterface $mvc
     * @param string $name
     * @param string $version
     */
    public function __construct(MVCInterface $mvc, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $this->mvc = $mvc;
        $this->name = $name;
        $this->version = $version;
        $this->defaultComand = 'list';
        $this->definition = $this->getDefaultInputDefinition();
        
        foreach ($this->getDefaultCommands() as $command) {
            $this->add($command);
        }
    }

    /**
     * Add Command to the application
     *
     * @param Command $command
     * @return Command
     * @throws \LogicException
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
     * Do run command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function doRun(InputInterface $input, OutputInterface $output)
    {
        if (!$this->commandsRegistered) {
            $this->registerCommands();

            $this->commandsRegistered = true;
        }

        if (true === $input->hasParameterOption(array('--version', '-V'))) {
            $output->writeln($this->getLongVersion());

            return 0;
        }
        
        $name = $input->getFirstArgument();
        
        if (!$name) {
            $name = $this->defaultComand;
            $input = new ArrayInput(array('command' => $this->defaultComand));
        }
        
        $command = $this->find($name);
        $this->runningCommand = $command;
        $exitCode = $this->doRunCommand($command, $input, $output);
        $this->runningCommand = null;

        return $exitCode;
    }
    
    /**
     * Run command
     * 
     * @param Command $command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function doRunCommand(Command $command, InputInterface $input, OutputInterface $output)
    {
        return $command->run($input, $output);
    }

    /**
     * Extract namespace a Command
     *
     * @param string $name
     * @param int $limit
     * @return string
     */
    public function extractNamespace($name, $limit = null)
    {
        $parts = explode(':', $name);
        array_pop($parts);

        return implode(':', null === $limit ? $parts : array_slice($parts, 0, $limit));
    }

    /**
     * Find and get a Command name
     *
     * @param $name
     * @return Command
     * @throws \InvalidArgumentException
     */
    public function find($name)
    {
        $allCommands = array_keys($this->commands);
        $expr = preg_replace_callback('{([^:]+|)}', function ($matches) { return preg_quote($matches[1]).'[^:]*'; }, $name);
        $commands = preg_grep('{^'.$expr.'}', $allCommands);

        if (empty($commands) || count(preg_grep('{^'.$expr.'$}', $commands)) < 1) {
            if (false !== $pos = strrpos($name, ':')) {
                // check if a namespace exists and contains commands
                $this->findNamespace(substr($name, 0, $pos));
            }

            $message = sprintf('Command "%s" is not defined.', $name);

            if ($alternatives = $this->findAlternatives($name, $allCommands, array())) {
                if (1 == count($alternatives)) {
                    $message .= "\n\nDid you mean this?\n    ";
                } else {
                    $message .= "\n\nDid you mean one of these?\n    ";
                }
                $message .= implode("\n    ", $alternatives);
            }

            throw new \InvalidArgumentException($message);
        }

        // filter out aliases for commands which are already on the list
        if (count($commands) > 1) {
            $commandList = $this->commands;
            $commands = array_filter($commands, function ($nameOrAlias) use ($commandList, $commands) {
                $commandName = $commandList[$nameOrAlias]->getName();

                return $commandName === $nameOrAlias || !in_array($commandName, $commands);
            });
        }

        $exact = in_array($name, $commands, true);
        if (count($commands) > 1 && !$exact) {
            $suggestions = $this->getAbbreviationSuggestions(array_values($commands));

            throw new \InvalidArgumentException(sprintf('Command "%s" is ambiguous (%s).', $name, $suggestions));
        }

        return $this->get($exact ? $name : reset($commands));
    }

    /**
     * Find Commands Alternatives
     *
     * @param $name
     * @param $collection
     * @return array
     */
    private function findAlternatives($name, $collection)
    {
        $threshold = 1e3;
        $alternatives = array();

        $collectionParts = array();
        foreach ($collection as $item) {
            $collectionParts[$item] = explode(':', $item);
        }

        foreach (explode(':', $name) as $i => $subname) {
            foreach ($collectionParts as $collectionName => $parts) {
                $exists = isset($alternatives[$collectionName]);
                if (!isset($parts[$i]) && $exists) {
                    $alternatives[$collectionName] += $threshold;
                    continue;
                } elseif (!isset($parts[$i])) {
                    continue;
                }

                $lev = levenshtein($subname, $parts[$i]);
                if ($lev <= strlen($subname) / 3 || '' !== $subname && false !== strpos($parts[$i], $subname)) {
                    $alternatives[$collectionName] = $exists ? $alternatives[$collectionName] + $lev : $lev;
                } elseif ($exists) {
                    $alternatives[$collectionName] += $threshold;
                }
            }
        }

        foreach ($collection as $item) {
            $lev = levenshtein($name, $item);
            if ($lev <= strlen($name) / 3 || false !== strpos($item, $name)) {
                $alternatives[$item] = isset($alternatives[$item]) ? $alternatives[$item] - $lev : $lev;
            }
        }

        $alternatives = array_filter($alternatives, function ($lev) use ($threshold) { return $lev < 2*$threshold; });
        asort($alternatives);

        return array_keys($alternatives);
    }

    /**
     * Find a command namespace
     *
     * @param string $namespace
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function findNamespace($namespace)
    {
        $allNamespaces = $this->getNamespaces();
        $expr = preg_replace_callback('{([^:]+|)}', function ($matches) { return preg_quote($matches[1]).'[^:]*'; }, $namespace);
        $namespaces = preg_grep('{^'.$expr.'}', $allNamespaces);

        if (empty($namespaces)) {
            $message = sprintf('There are no commands defined in the "%s" namespace.', $namespace);

            if ($alternatives = $this->findAlternatives($namespace, $allNamespaces, array())) {
                if (1 == count($alternatives)) {
                    $message .= "\n\nDid you mean this?\n    ";
                } else {
                    $message .= "\n\nDid you mean one of these?\n    ";
                }

                $message .= implode("\n    ", $alternatives);
            }

            throw new \InvalidArgumentException($message);
        }

        $exact = in_array($namespace, $namespaces, true);
        if (count($namespaces) > 1 && !$exact) {
            throw new \InvalidArgumentException(sprintf('The namespace "%s" is ambiguous (%s).', $namespace, $this->getAbbreviationSuggestions(array_values($namespaces))));
        }

        return $exact ? $namespace : reset($namespaces);
    }
    
    /**
     * Get Command object
     * 
     * @param string $name
     * @return Command
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        if (!isset($this->commands[$name])) {
            throw new \InvalidArgumentException(sprintf('The command "%s" does not exist.', $name));
        }

        return $this->commands[$name];
    }

    /**
     * @return boolean
     */
    public function getCatchExceptions()
    {
        return $this->catchExceptions;
    }

    /**
     * Get Default Commands
     * 
     * @return array Default Comands
     */
    protected function getDefaultCommands()
    {
        return array(new ListCommand());
    }

    /**
     * Get default InputDefinition
     *
     * @return InputDefinition
     */
    protected function getDefaultInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption('--help',           '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--verbose',        '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
            new InputOption('--version',        '-V', InputOption::VALUE_NONE, 'Display this application version'),
        ));
    }

    /**
     * Get the InputDefinition
     *
     * @return InputDefinition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Get The Console Application Name with the version formatted
     *
     * @return string
     */
    protected function getLongVersion()
    {
        if ('UNKNOWN' !== $this->getName() && 'UNKNOWN' !== $this->getVersion()) {
            return sprintf('<info>%s</info> version <comment>%s</comment>', $this->getName(), $this->getVersion());
        }

        return '<info>Console Tool</info>';
    }

    /**
     * Get Console Application Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Array Namespaces
     *
     * @return array
     */
    public function getNamespaces()
    {
        $namespaces = array();
        foreach ($this->commands as $command) {
            $namespaces[] = $this->extractNamespace($command->getName());

            foreach ($command->getAliases() as $alias) {
                $namespaces[] = $this->extractNamespace($alias);
            }
        }

        return array_values(array_unique(array_filter($namespaces)));
    }

    /**
     * Terminal width
     *
     * @return int
     */
    public function getTerminalWidth()
    {
        return 100;
    }

    /**
     * Get application version
     *
     * @return int|string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Register modules commands
     */
    protected function registerCommands()
    {
        foreach ($this->mvc->getModules() as $module) {
            if ($module instanceof Module) {
                $module->registerCommands($this);
            }
        }
    }

    /**
     * Print the exception code
     *
     * @param \Exception $e
     * @param OutputInterface $output
     */
    public function renderException(\Exception $e, OutputInterface $output)
    {
        do {
            $title = sprintf('  [%s]  ', get_class($e));

            $len = strlen($title);

            $width = $this->getTerminalWidth() ? $this->getTerminalWidth() - 1 : PHP_INT_MAX;
            // HHVM only accepts 32 bits integer in str_split, even when PHP_INT_MAX is a 64 bit integer: https://github.com/facebook/hhvm/issues/1327
            if (defined('HHVM_VERSION') && $width > 1 << 31) {
                $width = 1 << 31;
            }
            $formatter = $output->getFormatter();
            $lines = array();
            foreach (preg_split('/\r?\n/', $e->getMessage()) as $line) {
                foreach (str_split($line, $width - 4) as $line) {
                    // pre-format lines to get the right string length
                    $lineLength = strlen(preg_replace('/\[[^m]*m/', '', $formatter->format($line))) + 4;
                    $lines[] = array($line, $lineLength);

                    $len = max($lineLength, $len);
                }
            }

            $messages = array('', '');
            $messages[] = $emptyLine = $formatter->format(sprintf('<error>%s</error>', str_repeat(' ', $len)));
            $messages[] = $formatter->format(sprintf('<error>%s%s</error>', $title, str_repeat(' ', max(0, $len - strlen($title)))));
            foreach ($lines as $line) {
                $messages[] = $formatter->format(sprintf('<error>  %s  %s</error>', $line[0], str_repeat(' ', $len - $line[1])));
            }
            $messages[] = $emptyLine;
            $messages[] = '';
            $messages[] = '';

            $output->writeln($messages, OutputInterface::OUTPUT_RAW);

            if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                $output->writeln('<comment>Exception trace:</comment>');

                // exception related properties
                $trace = $e->getTrace();
                array_unshift($trace, array(
                    'function' => '',
                    'file' => $e->getFile() != null ? $e->getFile() : 'n/a',
                    'line' => $e->getLine() != null ? $e->getLine() : 'n/a',
                    'args' => array(),
                ));

                for ($i = 0, $count = count($trace); $i < $count; $i++) {
                    $class = isset($trace[$i]['class']) ? $trace[$i]['class'] : '';
                    $type = isset($trace[$i]['type']) ? $trace[$i]['type'] : '';
                    $function = $trace[$i]['function'];
                    $file = isset($trace[$i]['file']) ? $trace[$i]['file'] : 'n/a';
                    $line = isset($trace[$i]['line']) ? $trace[$i]['line'] : 'n/a';

                    $output->writeln(sprintf(' %s%s%s() at <info>%s:%s</info>', $class, $type, $function, $file, $line));
                }

                $output->writeln("");
                $output->writeln("");
            }
        } while ($e = $e->getPrevious());

        if (null !== $this->runningCommand) {
            $output->writeln(sprintf('<info>%s</info>', sprintf($this->runningCommand->getSynopsis(), $this->getName())));
            $output->writeln("");
            $output->writeln("");
        }
    }

    /**
     * Run Console Application
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|mixed
     * @throws \Exception
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null === $input) {
            $input = new ArgvInput();
        }
        
        if (null === $output) {
            $output = new ConsoleOutput();
        }
        
        try {
            $exitCode = $this->doRun($input, $output);
        } catch (\Exception $ex) {
            if (!$this->catchExceptions) {
                throw $ex;
            }
            $this->renderException($ex, $output);

            $exitCode = $ex->getCode();

            if (is_numeric($exitCode)) {
                $exitCode = (int) $exitCode;
                if (0 === $exitCode) {
                    $exitCode = 1;
                }
            } else {
                $exitCode = 1;
            }
        }
        
        if ($this->autoExit) {
            if ($exitCode > 255) {
                $exitCode = 255;
            }

            exit($exitCode);
        }

        return $exitCode;
    }

    /**
     * Set Auto Exit
     *
     * @param boolean $autoExit
     * @return Application
     */
    public function setAutoExit($autoExit)
    {
        $this->autoExit = (bool) $autoExit;

        return $this;
    }

    /**
     * Set Catch Exceptions
     *
     * @param $catchExceptions
     * @return Application
     */
    public function setCatchExceptions($catchExceptions)
    {
        $this->catchExceptions = (bool) $catchExceptions;

        return $this;
    }

    /**
     * Set InputDefinition
     *
     * @param InputDefinition $definition
     * @return Application
     */
    public function setDefinition(InputDefinition $definition)
    {
        $this->definition = $definition;

        return $this;
    }

}
