<?php

namespace FSVendor\WPDesk\RepositoryRating\DisplayStrategy;

/**
 * DisplayDecision based on GET parameters.
 */
class GetParametersDisplayDecision implements DisplayDecision
{
    /**
     * Whether to show beacon on the page or not. Array of arrays with condition for _GET.
     * Inner arrays mean AND, outer arrays mean OR conditions.
     *
     * ie. [ [ ... and ... and ... ] or [ ... and ... and ... ] or ... ]
     *
     * @var array
     */
    private $conditions;
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }
    /**
     * Should display?
     *
     * @return bool
     */
    public function should_display(): bool
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
