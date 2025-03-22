<?php

namespace FSVendor\WPDesk\Forms\Field;

class TextAreaField extends BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
    }
    public function get_template_name()
    {
        return 'textarea';
    }
}
