<?php

namespace MVC\Command;

/**
 * Functions of Controller
 * 
 * @author Ramón Serrano
 */
interface Controller {
   
    function buildController();
    
    function makeController( $name_file, $path_file);
    
}
