<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MVC\Console\Output;

use MVC\Console\Formatter\OutputFormatter;
use MVC\Console\Formatter\OutputFormatterInterface;

/**
 * Description of Output
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
abstract class Output implements OutputInterface
{
    /**
     * Verbosity level
     * 
     * @var int 
     */
    private $verbosity;
    
    /**
     * Instance of OutputFormatter
     * 
     * @var OutputFormatterInterface
     */
    private $formatter;
    
    public function __construct($verbosity = self::VERBOSITY_NORMAL, $decorated = false, OutputFormatterInterface $formatter = null)
    {
        $this->verbosity = null === $verbosity ? self::VERBOSITY_NORMAL : $verbosity;
        $this->formatter = $formatter ?: new OutputFormatter();
        $this->formatter->setDecorated($decorated);
    }
    
    abstract protected function doWrite($message, $newline);
    
    public function getFormatter()
    {
        return $this->formatter;
    }

    public function getVerbosity()
    {
        return $this->verbosity;
    }

    public function isDebug()
    {
        return self::VERBOSITY_DEBUG <= $this->verbosity;
    }
    
    public function isDecorated()
    {
        return $this->formatter->isDecorated();
    }
    
    public function isQuiet()
    {
        return self::VERBOSITY_QUIET === $this->verbosity;
    }

    public function isVerbose()
    {
        return self::VERBOSITY_VERBOSE <= $this->verbosity;
    }

    public function isVeryVerbose()
    {
        return self::VERBOSITY_VERY_VERBOSE <= $this->verbosity;
    }

    public function setDecorated($decorated)
    {
        $this->formatter->setDecorated($decorated);
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function setVerbosity($level)
    {
        $this->verbosity = (int) $level;
    }

    public function write($messages, $newline = false, $type = self::OUTPUT_NORMAL)
    {
        if (self::VERBOSITY_QUIET === $this->verbosity) {
            return;
        }

        $messages = (array) $messages;

        foreach ($messages as $message) {
            switch ($type) {
                case self::OUTPUT_NORMAL:
                    $message = $this->formatter->format($message);
                    break;
                case self::OUTPUT_RAW:
                    break;
                case self::OUTPUT_PLAIN:
                    $message = strip_tags($this->formatter->format($message));
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('Unknown output type given (%s)', $type));
            }

            $this->doWrite($message, $newline);
        }
    }

    public function writeln($messages, $type = self::OUTPUT_NORMAL)
    {
        $this->write($messages, true, $type);
    }

}
