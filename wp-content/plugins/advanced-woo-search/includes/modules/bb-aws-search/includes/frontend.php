<?php

$args = $settings->placeholder ? array( 'placeholder' => $settings->placeholder ) : array();
$search_form = aws_get_search_form( false, $args );

echo $search_form;
