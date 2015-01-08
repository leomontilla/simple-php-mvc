<?php

namespace MVC\Console\Command;

/**
 * Functions of Model
 * 
 * @author Ramón Serrano
 * @package MVC\Command
 */
interface ModelInterface
{
   
    function buildModel();
    
    /**
     * @param string $nameFile
     * @param string $pathFile
     */
    function makeModel( $nameFile, $pathFile);
    
}
