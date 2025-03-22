<?php

/**
 * Class ShippingContentsMeta
 */
namespace WPDesk\FS\TableRate\Rule\ShippingContents;

/**
 * Shipping Contents Meta.
 */
class ShippingContentsMeta
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var mixed
     */
    private $value;
    /**
     * @param string $key   .
     * @param mixed  $value .
     */
    public function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
    /**
     * @return string
     */
    public function get_key(): string
    {
        return $this->key;
    }
    /**
     * @return mixed
     */
    public function get_value()
    {
        return $this->value;
    }
}
