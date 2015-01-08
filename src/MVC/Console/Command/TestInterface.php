<?php

namespace MVC\Console\Command;

/**
 * Functions of Test
 *
 * @author Ramón Serrano
 * @package MVC\Command
 */
interface TestInterface
{

    function buildUnitTest();
    
    /**
     * @param string $nameFile
     * @param string $pathFile
     */
    function makeUnitTest( $nameFile, $pathFile);

}
