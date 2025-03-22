<?php

namespace FSVendor\WPDesk\RepositoryRating;

class RepositoryRatingPetitionText implements PetitionText
{
    /**
     * @var string
     */
    private $plugin_author;
    /**
     * @var string
     */
    private $plugin_title;
    /**
     * @var string
     */
    private $rating_url;
    /**
     * @var string
     */
    private $text_align;
    /**
     * @param string $plugin_author
     * @param string $plugin_title
     * @param string $rating_url
     * @param string $text_align
     */
    public function __construct(string $plugin_author, string $plugin_title, string $rating_url, string $text_align)
    {
        $this->plugin_author = $plugin_author;
        $this->plugin_title = $plugin_title;
        $this->rating_url = $rating_url;
        $this->text_align = $text_align;
    }
    /**
     * @inheritDoc
     */
    public function get_petition_text(): string
    {
        ob_start();
        $plugin_author = $this->plugin_author;
        $plugin_title = $this->plugin_title;
        $rating_url = $this->rating_url;
        $text_align = $this->text_align;
        include __DIR__ . '/views/html-text-petition.php';
        return ob_get_clean();
    }
}
