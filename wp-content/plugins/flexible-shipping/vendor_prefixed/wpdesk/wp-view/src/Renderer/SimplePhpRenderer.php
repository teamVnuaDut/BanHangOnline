<?php

namespace FSVendor\WPDesk\View\Renderer;

use FSVendor\WPDesk\View\Resolver\Resolver;
/**
 * Can render templates
 */
class SimplePhpRenderer implements Renderer
{
    /** @var Resolver */
    private $resolver;
    public function __construct(Resolver $resolver)
    {
        $this->set_resolver($resolver);
    }
    /**
     * @param Resolver $resolver
     *
     * @return void|Resolver
     */
    public function set_resolver(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }
    /**
     * @param string $template
     * @param array|null $params
     *
     * @return string
     */
    public function render($template, array $params = null)
    {
        ob_start();
        $this->output_render($template, $params);
        return ob_get_clean();
    }
    /**
     * @param string $template
     * @param array|null $params
     */
    public function output_render($template, array $params = null)
    {
        if ($params !== null) {
            extract($params, \EXTR_SKIP);
        }
        include $this->resolver->resolve($template . '.php');
    }
}
