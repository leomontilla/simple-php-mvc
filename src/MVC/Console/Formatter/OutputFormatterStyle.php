<?php

namespace MVC\Console\Formatter;

/**
 * Description of OutputFormatterStyle
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class OutputFormatterStyle implements OutputFormatterStyleInterface
{
    /**
     * Available Foreground Colors
     * 
     * @var array
     */
    private static $availableForegroundColors = array(
        'black' => array('set' => 30, 'unset' => 39),
        'red' => array('set' => 31, 'unset' => 39),
        'green' => array('set' => 32, 'unset' => 39),
        'yellow' => array('set' => 33, 'unset' => 39),
        'blue' => array('set' => 34, 'unset' => 39),
        'magenta' => array('set' => 35, 'unset' => 39),
        'cyan' => array('set' => 36, 'unset' => 39),
        'white' => array('set' => 37, 'unset' => 39),
    );
    
    /**
     * Available Backgroud Colors
     * 
     * @var array
     */
    private static $availableBackgroundColors = array(
        'black' => array('set' => 40, 'unset' => 49),
        'red' => array('set' => 41, 'unset' => 49),
        'green' => array('set' => 42, 'unset' => 49),
        'yellow' => array('set' => 43, 'unset' => 49),
        'blue' => array('set' => 44, 'unset' => 49),
        'magenta' => array('set' => 45, 'unset' => 49),
        'cyan' => array('set' => 46, 'unset' => 49),
        'white' => array('set' => 47, 'unset' => 49),
    );
    
    /**
     * Available Options
     * 
     * @var array 
     */
    private static $availableOptions = array(
        'bold' => array('set' => 1, 'unset' => 22),
        'underscore' => array('set' => 4, 'unset' => 24),
        'blink' => array('set' => 5, 'unset' => 25),
        'reverse' => array('set' => 7, 'unset' => 27),
        'conceal' => array('set' => 8, 'unset' => 28),
    );

    /**
     * Background color
     * 
     * @var string
     */
    private $backgroundColor;
    
    /**
     * Foreground color
     * 
     * @var string
     */
    private $foregroundColor;
    
    /**
     * User options
     * 
     * @var array
     */
    private $options = array();
    
    /**
     * OutputFormatter Construct
     * 
     * @param string $foregroundColor
     * @param string $backgroundColor
     * @param array $options
     */
    public function __construct($foregroundColor = null, $backgroundColor = null, array $options = array())
    {
        if (null !== $foregroundColor) {
            $this->setForegroundColor($foregroundColor);
        }
        if (null !== $backgroundColor) {
            $this->setBackgroundColor($backgroundColor);
        }
        if (count($options)) {
            $this->setOptions($options);
        }
    }
    
    /**
     * Aply output format style to the text
     * 
     * @param string $text
     * @return string
     */
    public function apply($text)
    {
        $setCodes = array();
        $unsetCodes = array();

        if (null !== $this->foregroundColor) {
            $setCodes[] = $this->foregroundColor['set'];
            $unsetCodes[] = $this->foregroundColor['unset'];
        }
        if (null !== $this->backgroundColor) {
            $setCodes[] = $this->backgroundColor['set'];
            $unsetCodes[] = $this->backgroundColor['unset'];
        }
        if (count($this->options)) {
            foreach ($this->options as $option) {
                $setCodes[] = $option['set'];
                $unsetCodes[] = $option['unset'];
            }
        }

        if (0 === count($setCodes)) {
            return $text;
        }

        return sprintf("\033[%sm%s\033[%sm", implode(';', $setCodes), $text, implode(';', $unsetCodes));
    }

    public function setBackgroundColor($color = null)
    {
        if (null === $color) {
            $this->backgroundColor = null;

            return;
        }

        if (!isset(static::$availableBackgroundColors[$color])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid foreground color specified: "%s". Expected one of (%s)',
                $color,
                implode(', ', array_keys(static::$availableBackgroundColors))
            ));
        }

        $this->backgroundColor = static::$availableBackgroundColors[$color];
    }

    public function setForegroundColor($color = null)
    {
        if (null === $color) {
            $this->foregroundColor = null;

            return;
        }

        if (!isset(static::$availableForegroundColors[$color])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid foreground color specified: "%s". Expected one of (%s)',
                $color,
                implode(', ', array_keys(static::$availableForegroundColors))
            ));
        }

        $this->foregroundColor = static::$availableForegroundColors[$color];
    }

    public function setOption($option)
    {
        if (!isset(static::$availableOptions[$option])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid option specified: "%s". Expected one of (%s)',
                $option,
                implode(', ', array_keys(static::$availableOptions))
            ));
        }

        if (false === array_search(static::$availableOptions[$option], $this->options)) {
            $this->options[] = static::$availableOptions[$option];
        }
    }

    /**
     * Set options
     * 
     * @param array $options
     * @return OutputFormatterStyle
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        
        return $this;
    }

    public function unsetOption($option)
    {
        if (!isset(static::$availableOptions[$option])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid option specified: "%s". Expected one of (%s)',
                $option,
                implode(', ', array_keys(static::$availableOptions))
            ));
        }

        $pos = array_search(static::$availableOptions[$option], $this->options);
        if (false !== $pos) {
            unset($this->options[$pos]);
        }
    }

}
