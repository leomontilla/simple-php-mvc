<?php

namespace MVC\Console\Formatter;

/**
 * Description of OutputFormatterStyleInterface
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
interface OutputFormatterStyleInterface
{
    /**
     * Sets style foreground color.
     *
     * @param string $color The color name
     */
    public function setForegroundColor($color = null);

    /**
     * Sets style background color.
     *
     * @param string $color The color name
     */
    public function setBackgroundColor($color = null);

    /**
     * Sets some specific style option.
     *
     * @param string $option The option name
     */
    public function setOption($option);

    /**
     * Unsets some specific style option.
     *
     * @param string $option The option name
     */
    public function unsetOption($option);

    /**
     * Sets multiple style options at once.
     *
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * Applies the style to a given text.
     *
     * @param string $text The text to style
     *
     * @return string
     */
    public function apply($text);
}
