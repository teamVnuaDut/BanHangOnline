<?php

namespace FSVendor\WPDesk\ShowDecision;

class AndStrategy implements ShouldShowStrategy
{
    /**
     * @var ShouldShowStrategy[]
     */
    private array $conditions = [];
    public function __construct(ShouldShowStrategy $strategy)
    {
        $this->conditions[] = $strategy;
    }
    public function addCondition(ShouldShowStrategy $condition): self
    {
        $this->conditions[] = $condition;
        return $this;
    }
    public function shouldDisplay(): bool
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->shouldDisplay()) {
                return \false;
            }
        }
        return \true;
    }
}
