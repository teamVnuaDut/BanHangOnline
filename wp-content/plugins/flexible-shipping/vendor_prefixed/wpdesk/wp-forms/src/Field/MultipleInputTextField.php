<?php

namespace FSVendor\WPDesk\Forms\Field;

class MultipleInputTextField extends InputTextField
{
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'input-text-multiple';
    }
}
