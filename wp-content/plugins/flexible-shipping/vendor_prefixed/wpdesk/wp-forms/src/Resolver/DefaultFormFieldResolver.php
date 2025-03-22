<?php

namespace FSVendor\WPDesk\Forms\Resolver;

use FSVendor\WPDesk\View\Renderer\Renderer;
use FSVendor\WPDesk\View\Resolver\DirResolver;
use FSVendor\WPDesk\View\Resolver\Resolver;
/**
 * Use with View to resolver form fields to default templates.
 *
 * @package WPDesk\Forms\Resolver
 */
class DefaultFormFieldResolver implements Resolver
{
    /** @var Resolver */
    private $dir_resolver;
    public function __construct()
    {
        $this->dir_resolver = new DirResolver(__DIR__ . '/../../templates');
    }
    public function resolve($name, Renderer $renderer = null)
    {
        return $this->dir_resolver->resolve($name, $renderer);
    }
}
