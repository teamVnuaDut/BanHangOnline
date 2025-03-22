<?php

namespace FSVendor\WPDesk\ShowDecision;

/**
 * Show when some conditions with $_GET are meet.
 */
class GetStrategy implements ShouldShowStrategy
{
    /**
     * @var array
     */
    private $conditions;
    /**
     * @param array $conditions Whether to show on the page or not. Array of arrays with condition for _GET.
     *
     * Inner arrays mean AND, outer arrays mean OR conditions.
     *
     * ie. [ [ .. and .. and ..] or [ .. and .. and ..] or .. ]
     *
     */
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }
    /**
     * @return bool
     */
    public function shouldDisplay()
    {
        foreach ($this->conditions as $or_conditions) {
            $display = \true;
            foreach ($or_conditions as $parameter => $value) {
                if (!isset($_GET[$parameter]) || $_GET[$parameter] !== $value) {
                    $display = \false;
                }
            }
            if ($display) {
                return $display;
            }
        }
        return \false;
    }
}
