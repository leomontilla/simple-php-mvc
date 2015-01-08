<?php

namespace MVC\Console\Input;

/**
 * Description of InputInterface
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
interface InputInterface
{
    public function bind(InputDefinition $definition);
    
    public function getArgument($name);
    
    public function getArguments();
    
    /**
     * @return string
     */
    public function getFirstArgument();
    
    public function getOption($name);
    
    public function getOptions();
    
    public function getParameterOption($values, $default = false);
    
    /**
     * @param string $name
     * @return boolean
     */
    public function hasArgument($name);
    
    /**
     * @param string $name
     * @return boolean
     */
    public function hasOption($name);
    
    /**
     * @param string|array $values
     * @return boolean
     */
    public function hasParameterOption($values);
    
    /**
     * @return boolean
     */
    public function isInteractive();
    
    public function setArgument($name, $value);
    
    public function setInteractive($interactive);
    
    public function setOption($name, $value);
    
    public function validate();
    
}
