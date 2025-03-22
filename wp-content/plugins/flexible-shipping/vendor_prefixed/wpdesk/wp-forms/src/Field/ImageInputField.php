<?php

namespace FSVendor\WPDesk\Forms\Field;

class ImageInputField extends BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
        $this->set_attribute('type', 'text');
    }
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'input-image';
    }
}
