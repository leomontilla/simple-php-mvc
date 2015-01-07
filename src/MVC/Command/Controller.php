<?php

/**
 * Functions of Controller
 * 
 * @author Ramón Serrano
 * @package MVC\Command
 */

namespace MVC\Command;

interface Controller
{

    function buildController();

    /**
     * @param string $nameFile
     * @param string $pathFile
     */
    function makeController($nameFile, $pathFile);
}
