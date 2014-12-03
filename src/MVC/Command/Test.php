<?php

namespace MVC\Command;

/**
 * Functions of Test
 *
 * @author Ramón Serrano
 * @package MVC\Command
 */
interface Test
{

    function buildUnitTest();
    
    /**
     * @param string $nameFile
     * @param string $pathFile
     */
    function makeUnitTest( $nameFile, $pathFile);

}
