<?php

/**
 * Functions of Controller
 * 
 * @author Ramón Serrano
 * @package MVC\Command
 */

namespace MVC\Console\Command;

interface ControllerInterface
{

    function buildController();

    /**
     * @param string $nameFile
     * @param string $pathFile
     */
    function makeController($nameFile, $pathFile);
}
