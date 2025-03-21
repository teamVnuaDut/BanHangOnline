<?php

if (!class_exists('Kirki')) {
    return;
}


Kirki::add_section('colors_preset_section', array(
    'title' => esc_attr__('Colors preset', 'envo-extra'),
    'panel' => 'envo_theme_panel',
    'priority' => 1,
));

