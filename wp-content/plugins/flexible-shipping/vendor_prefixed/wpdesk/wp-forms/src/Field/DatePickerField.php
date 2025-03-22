<?php

namespace FSVendor\WPDesk\Forms\Field;

use FSVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class DatePickerField extends BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
        $this->add_class('date-picker');
        $this->set_placeholder('YYYY-MM-DD');
        $this->set_attribute('type', 'text');
    }
    public function get_sanitizer()
    {
        return new TextFieldSanitizer();
    }
    public function get_template_name()
    {
        return 'input-date-picker';
    }
}
