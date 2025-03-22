<?php

namespace FSVendor\Octolize\Tracker\OptInNotice;

/**
 * Should display $_GET parameter value.
 */
class ShouldDisplayGetParameterValue implements ShouldDisplay
{
    /**
     * @var string
     */
    private $parameter;
    /**
     * @var string
     */
    private $value;
    /**
     * @param string $parameter
     * @param string $value
     */
    public function __construct(string $parameter, string $value)
    {
        $this->parameter = $parameter;
        $this->value = $value;
    }
    /**
     * @inheritDoc
     */
    public function should_display()
    {
        return isset($_GET[$this->parameter]) && $_GET[$this->parameter] === $this->value;
    }
}
