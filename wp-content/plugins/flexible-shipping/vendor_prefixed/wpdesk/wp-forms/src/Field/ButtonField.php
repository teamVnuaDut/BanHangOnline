<?php

namespace FSVendor\WPDesk\Forms\Field;

class ButtonField extends NoValueField
{
    public function get_template_name()
    {
        return 'button';
    }
    public function get_type()
    {
        return 'button';
    }
}
