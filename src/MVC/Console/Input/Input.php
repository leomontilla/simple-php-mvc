<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MVC\Console\Input;

/**
 * Description of Input
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
abstract class Input implements InputInterface
{
    /**
     * @var InputDefinition
     */
    protected $definition;
    protected $options = array();
    protected $arguments = array();
    protected $interactive = true;
    
    public function __construct(InputDefinition $definition = null)
    {
        if (null === $definition) {
            $this->definition = new InputDefinition();
        } else {
            $this->bind($definition);
            $this->validate();
        }
    }
    
    /**
     * Processes command line arguments.
     */
    abstract protected function parse();
    
    /**
     * Binds the current Input
     * 
     * @param InputDefinition $definition
     */
    public function bind(InputDefinition $definition)
    {
        $this->arguments = array();
        $this->options = array();
        $this->definition = $definition;

        $this->parse();
    }
    
    public function escapeToken($token)
    {
        return preg_match('{^[\w-]+$}', $token) ? $token : escapeshellarg($token);
    }

    public function getArgument($name)
    {
        if (!$this->definition->hasArgument($name)) {
            throw new \InvalidArgumentException(sprintf('The "%s" argument does not exist.', $name));
        }

        return isset($this->arguments[$name]) ? $this->arguments[$name] : $this->definition->getArgument($name)->getDefault();
    }

    public function getArguments()
    {
        return array_merge($this->definition->getArgumentDefaults(), $this->arguments);
    }

    public function getOption($name)
    {
        if (!$this->definition->hasOption($name)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $name));
        }

        return isset($this->options[$name]) ? $this->options[$name] : $this->definition->getOption($name)->getDefault();
    }

    public function getOptions()
    {
        return array_merge($this->definition->getOptionDefaults(), $this->options);
    }

    public function hasArgument($name)
    {
        return $this->definition->hasArgument($name);
    }

    public function hasOption($name)
    {
        return $this->definition->hasOption($name);
    }

    public function isInteractive()
    {
        return $this->interactive;
    }

    public function setArgument($name, $value) 
    {
        if (!$this->definition->hasArgument($name)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $name));
        }

        $this->arguments[$name] = $value;
    }

    public function setInteractive($interactive)
    {
        $this->interactive = (bool) $interactive;
    }

    public function setOption($name, $value)
    {
        if (!$this->definition->hasOption($name)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $name));
        }

        $this->options[$name] = $value;
    }

    public function validate()
    {
        if (count($this->arguments) < $this->definition->getArgumentRequiredCount()) {
            throw new \RuntimeException('Not enough arguments.');
        }
    }

}
