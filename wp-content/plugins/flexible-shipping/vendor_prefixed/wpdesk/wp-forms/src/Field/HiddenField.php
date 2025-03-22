<?php

namespace FSVendor\WPDesk\Forms\Field;

use FSVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class HiddenField extends BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
        $this->set_attribute('type', 'hidden');
    }
    public function get_sanitizer()
    {
        return new TextFieldSanitizer();
    }
    public function get_template_name()
    {
        return 'input-hidden';
    }
}
