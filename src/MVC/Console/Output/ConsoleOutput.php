<?php

namespace MVC\Console\Output;

use MVC\Console\Formatter\OutputFormatterInterface;

/**
 * Description of ConsoleOutput
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class ConsoleOutput extends StreamOutput
{

    /**
     *
     * @var StreamOutput
     */
    private $stderr;
    
    public function __construct($verbosity = self::VERBOSITY_NORMAL, $decorated = null, OutputFormatterInterface $formatter = null)
    {
        $outputStream = 'php://stdout';
        if (!$this->hasStdoutSupport()) {
            $outputStream = 'php://output';
        }

        parent::__construct(fopen($outputStream, 'w'), $verbosity, $decorated, $formatter);

        $this->stderr = new StreamOutput(fopen('php://stderr', 'w'), $verbosity, $decorated, $this->getFormatter());
    }
    
    public function getErrorOutput()
    {
        return $this->stderr;
    }
    
    /**
     * Get if this environment suports STDOUT
     * 
     * @return boolean
     */
    protected function hasStdoutSupport()
    {
        return ('OS400' != php_uname('s'));
    }
    
    public function setDecorated($decorated)
    {
        parent::setDecorated($decorated);
        $this->stderr->setDecorated($decorated);
    }
    
    public function setErrorOutput(OutputInterface $error)
    {
        $this->stderr = $error;
    }
    
    public function setFormatter(OutputFormatterInterface $formatter)
    {
        parent::setFormatter($formatter);
        $this->stderr->setFormatter($formatter);
    }
    
    public function setVerbosity($level)
    {
        parent::setVerbosity($level);
        $this->stderr->setVerbosity($level);
    }

}
