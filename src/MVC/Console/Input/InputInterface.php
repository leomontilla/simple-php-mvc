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
    
    public function getFirstArgument();
    
    public function getOption($name);
    
    public function getOptions();
    
    public function getParameterOption($values, $default = false);
    
    public function hasArgument($name);
    
    public function hasOption($name);
    
    public function hasParameterOption($values);
    
    public function isInteractive();
    
    public function setArgument($name, $value);
    
    public function setInteractive($interactive);
    
    public function setOption($name, $value);
    
    public function validate();
    
}
