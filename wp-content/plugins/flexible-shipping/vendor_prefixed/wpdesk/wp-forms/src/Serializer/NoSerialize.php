<?php

namespace FSVendor\WPDesk\Forms\Serializer;

use FSVendor\WPDesk\Forms\Serializer;
class NoSerialize implements Serializer
{
    public function serialize($value)
    {
        return $value;
    }
    public function unserialize($value)
    {
        return $value;
    }
}
