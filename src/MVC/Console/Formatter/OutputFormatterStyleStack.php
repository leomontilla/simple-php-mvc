<?php

namespace MVC\Console\Formatter;

/**
 * Description of OutputFormatterStyleStack
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class OutputFormatterStyleStack
{
    
    /**
     * @var OutputFormatterStyleInterface[]
     */
    private $styles;

    /**
     * @var OutputFormatterStyleInterface
     */
    private $emptyStyle;

    /**
     * Constructor.
     *
     * @param OutputFormatterStyleInterface|null $emptyStyle
     */
    public function __construct(OutputFormatterStyleInterface $emptyStyle = null)
    {
        $this->emptyStyle = $emptyStyle ?: new OutputFormatterStyle();
        $this->reset();
    }

    /**
     * Resets stack (ie. empty internal arrays).
     */
    public function reset()
    {
        $this->styles = array();
    }

    /**
     * Pushes a style in the stack.
     *
     * @param OutputFormatterStyleInterface $style
     */
    public function push(OutputFormatterStyleInterface $style)
    {
        $this->styles[] = $style;
    }

    /**
     * Pops a style from the stack.
     *
     * @param OutputFormatterStyleInterface|null $style
     *
     * @return OutputFormatterStyleInterface
     *
     * @throws \InvalidArgumentException When style tags incorrectly nested
     */
    public function pop(OutputFormatterStyleInterface $style = null)
    {
        if (empty($this->styles)) {
            return $this->emptyStyle;
        }

        if (null === $style) {
            return array_pop($this->styles);
        }

        foreach (array_reverse($this->styles, true) as $index => $stackedStyle) {
            if ($style->apply('') === $stackedStyle->apply('')) {
                $this->styles = array_slice($this->styles, 0, $index);

                return $stackedStyle;
            }
        }

        throw new \InvalidArgumentException('Incorrectly nested style tag found.');
    }

    /**
     * Computes current style with stacks top codes.
     *
     * @return OutputFormatterStyle
     */
    public function getCurrent()
    {
        if (empty($this->styles)) {
            return $this->emptyStyle;
        }

        return $this->styles[count($this->styles)-1];
    }

    /**
     * @param OutputFormatterStyleInterface $emptyStyle
     *
     * @return OutputFormatterStyleStack
     */
    public function setEmptyStyle(OutputFormatterStyleInterface $emptyStyle)
    {
        $this->emptyStyle = $emptyStyle;

        return $this;
    }

    /**
     * @return OutputFormatterStyleInterface
     */
    public function getEmptyStyle()
    {
        return $this->emptyStyle;
    }
}
