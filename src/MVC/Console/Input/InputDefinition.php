<?php

namespace MVC\Console\Input;

/**
 * Description of InputDefinition
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class InputDefinition
{
    
    /**
     * Command Arguments
     * 
     * @var array
     */
    private $arguments;
    
    private $requiredCount;
    private $hasAnArrayArgument = false;
    private $hasOptional;
    
    /**
     * Command Options
     * 
     * @var array
     */
    private $options;
    
    private $shortcuts;
    
    public function __construct(array $definition = array())
    {
        $this->setDefinition($definition);
    }
    
    public function addArgument(InputArgument $argument)
    {
        if (isset($this->arguments[$argument->getName()])) {
            throw new \LogicException(sprintf('An argument with name "%s" already exists.', $argument->getName()));
        }

        if ($this->hasAnArrayArgument) {
            throw new \LogicException('Cannot add an argument after an array argument.');
        }

        if ($argument->isRequired() && $this->hasOptional) {
            throw new \LogicException('Cannot add a required argument after an optional one.');
        }

        if ($argument->isArray()) {
            $this->hasAnArrayArgument = true;
        }

        if ($argument->isRequired()) {
            ++$this->requiredCount;
        } else {
            $this->hasOptional = true;
        }

        $this->arguments[$argument->getName()] = $argument;
    }
    
    public function addArguments($arguments = array())
    {
        if (null !== $arguments) {
            foreach ($arguments as $argument) {
                $this->addArgument($argument);
            }
        }
    }
    
    public function addOption(InputOption $option)
    {
        if (isset($this->options[$option->getName()]) && !$option->equals($this->options[$option->getName()])) {
            throw new \LogicException(sprintf('An option named "%s" already exists.', $option->getName()));
        }

        if ($option->getShortcut()) {
            foreach (explode('|', $option->getShortcut()) as $shortcut) {
                if (isset($this->shortcuts[$shortcut]) && !$option->equals($this->options[$this->shortcuts[$shortcut]])) {
                    throw new \LogicException(sprintf('An option with shortcut "%s" already exists.', $shortcut));
                }
            }
        }

        $this->options[$option->getName()] = $option;
        if ($option->getShortcut()) {
            foreach (explode('|', $option->getShortcut()) as $shortcut) {
                $this->shortcuts[$shortcut] = $option->getName();
            }
        }
    }
    
    public function addOptions($options = array())
    {
        foreach ($options as $option) {
            $this->addOption($option);
        }
    }
    
    /**
     * Get argument name
     * 
     * @param string|int $name
     * @return InputArgument
     * @throws \InvalidArgumentException
     */
    public function getArgument($name)
    {
        if (!$this->hasArgument($name)) {
            throw new \InvalidArgumentException(sprintf('The "%s" argument does not exist.', $name));
        }

        $arguments = is_int($name) ? array_values($this->arguments) : $this->arguments;

        return $arguments[$name];
    }
    
    public function getArgumentDefaults()
    {
        $values = array();
        foreach ($this->arguments as $argument) {
            $values[$argument->getName()] = $argument->getDefault();
        }

        return $values;
    }

    public function getArguments()
    {
        return $this->arguments;
    }
    
    public function getArgumentRequiredCount()
    {
        return $this->requiredCount;
    }
    
    /**
     * Get option name 
     * 
     * @param string $name
     * @return InputOption
     * @throws \InvalidArgumentException
     */
    public function getOption($name)
    {
        if (!$this->hasOption($name)) {
            throw new \InvalidArgumentException(sprintf('The "--%s" option does not exist.', $name));
        }

        return $this->options[$name];
    }
    
    /**
     * Get the option name from shortcut
     *
     * @param string $shortcut
     * @return InputOption
     * @throws \InvalidArgumentException
     */
    public function getOptionForShortcut($shortcut)
    {
        if (!isset($this->shortcuts[$shortcut])) {
            throw new \InvalidArgumentException(sprintf('The "-%s" option does not exist.', $shortcut));
        }

        return $this->getOption($this->shortcuts[$shortcut]);
    }
    
    /**
     * Get options
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    public function getOptionDefaults()
    {
        $values = array();
        foreach ($this->options as $option) {
            $values[$option->getName()] = $option->getDefault();
        }

        return $values;
    }
    
    public function getSynopsis()
    {
        $elements = array();
        foreach ($this->getOptions() as $option) {
            $shortcut = $option->getShortcut() ? sprintf('-%s|', $option->getShortcut()) : '';
            $elements[] = sprintf('['.($option->isValueRequired() ? '%s--%s="..."' : ($option->isValueOptional() ? '%s--%s[="..."]' : '%s--%s')).']', $shortcut, $option->getName());
        }

        foreach ($this->getArguments() as $argument) {
            $elements[] = sprintf($argument->isRequired() ? '%s' : '[%s]', $argument->getName().($argument->isArray() ? '1' : ''));

            if ($argument->isArray()) {
                $elements[] = sprintf('... [%sN]', $argument->getName());
            }
        }

        return implode(' ', $elements);
    }
    
    
    
    public function hasArgument($name)
    {
        $arguments = is_int($name) ? array_values($this->arguments) : $this->arguments;

        return isset($arguments[$name]);
    }
    
    public function setArguments($arguments = array())
    {
        $this->arguments = array();
        $this->requiredCount = 0;
        $this->hasOptional = false;
        $this->hasAnArrayArgument = false;
        $this->addArguments($arguments);
    }
    
    public function setDefinition(array $definition)
    {
        $arguments = array();
        $options = array();
        foreach ($definition as $item) {
            if ($item instanceof InputOption) {
                $options[] = $item;
            } else {
                $arguments[] = $item;
            }
        }

        $this->setArguments($arguments);
        $this->setOptions($options);
    }
    
    public function setOptions($options = array())
    {
        $this->options = array();
        $this->shortcuts = array();
        $this->addOptions($options);
    }
    
}
