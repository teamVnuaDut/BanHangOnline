<?php

namespace FSVendor\WPDesk\RepositoryRating;

/**
 * Petition text generator.
 */
interface PetitionText
{
    /**
     * Returns petition text.
     *
     * @return string
     */
    public function get_petition_text(): string;
}
