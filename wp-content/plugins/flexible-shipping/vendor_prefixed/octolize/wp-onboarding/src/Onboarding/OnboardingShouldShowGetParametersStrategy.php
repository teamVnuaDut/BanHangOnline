<?php

/**
 * @package Octolize\Onboarding
 */
namespace FSVendor\Octolize\Onboarding;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * When and if show Onboarding.
 */
class OnboardingShouldShowGetParametersStrategy implements OnboardingShouldShowStrategy
{
    /**
     * Whether to show onboarding on the page or not. Array of arrays with condition for _GET.
     * Inner arrays mean AND, outer arrays mean OR conditions.
     *
     * ie. [ [ 'parameters' => [ .. and .. and ..] ] or [ 'parameters' => [ .. and .. and ..] or .. ] ]
     *
     * @var array
     */
    private $conditions;
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }
    /**
     * Should Onboarding be visible?
     *
     * @return bool
     */
    public function should_display(): bool
    {
        return $this->should_display_from_get_parameters();
    }
    private function should_display_from_get_parameters(): bool
    {
        $current_screen = get_current_screen();
        $page = $current_screen ? $current_screen->base : '';
        foreach ($this->conditions as $or_conditions) {
            if ($this->should_display_on_current_page($page === ($or_conditions['page'] ?? ''), $or_conditions['parameters'] ?? [])) {
                return \true;
            }
        }
        return \false;
    }
    private function should_display_on_current_page(bool $display, array $parameters): bool
    {
        foreach ($parameters as $parameter => $value) {
            if (!isset($_GET[$parameter]) && !empty($value) || isset($_GET[$parameter]) && $_GET[$parameter] !== $value) {
                $display = \false;
            }
        }
        return $display;
    }
}
