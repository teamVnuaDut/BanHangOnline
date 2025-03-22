<?php

namespace FSVendor\WPDesk\Forms\Field;

class ProductSelect extends SelectField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_multiple();
    }
    public function get_template_name()
    {
        return 'product-select';
    }
}
