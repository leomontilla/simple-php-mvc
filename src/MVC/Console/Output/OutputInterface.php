<?php

namespace MVC\Console\Output;

use MVC\Console\Formatter\OutputFormatterInterface;

/**
 * Description of OutputInterface
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
interface OutputInterface 
{
    const VERBOSITY_QUIET = 0;
    const VERBOSITY_NORMAL = 1;
    const VERBOSITY_VERBOSE = 2;
    const VERBOSITY_VERY_VERBOSE = 3;
    const VERBOSITY_DEBUG = 4;

    const OUTPUT_NORMAL = 0;
    const OUTPUT_RAW = 1;
    const OUTPUT_PLAIN = 2;
    
    public function getFormatter();
    
    public function getVerbosity();
    
    public function isDecorated();
    
    public function setDecorated($decorated);
    
    public function setFormatter(OutputFormatterInterface $formatter);
    
    public function setVerbosity($level);
    
    public function write($messages, $newline = false, $type = self::OUTPUT_NORMAL);
    
    public function writeln($messages, $type = self::OUTPUT_NORMAL);
   
}
