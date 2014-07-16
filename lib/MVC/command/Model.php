<?php

namespace MVC\command;

/**
 * Functions of Model
 * 
 * @author Ramón Serrano
 */
interface Model {
   
    function buildModel();
    
    function makeModel( $name_file, $path_file);
    
}
