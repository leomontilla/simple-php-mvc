<?php

namespace MVC\Command;

/**
 * Functions of Model
 * 
 * @author Ramón Serrano
 * @package MVC\Command
 */
interface Model
{
   
    function buildModel();
    
    /**
     * @param string $nameFile
     * @param string $pathFile
     */
    function makeModel( $nameFile, $pathFile);
    
}
