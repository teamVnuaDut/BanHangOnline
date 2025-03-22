<?php

namespace FSVendor;

/**
 * @var string $text_align
 * @var string $plugin_author
 * @var string $plugin_title
 * @var string $rating_url
 */
?><div class="wpdesk-rating-petition" style="text-align: <?php 
echo \esc_attr($text_align);
?>;">
	<?php 
echo \wp_kses_post(\sprintf(\__('Created with %1$s by %2$s - If you like %3$s you can %4$srate us %5$s in plugins repository &rarr;%6$s', 'flexible-shipping'), '<span class="heart">&hearts;</span>', $plugin_author, '<span class="plugin-title">' . $plugin_title . '</span>', '<a href="' . $rating_url . '" target="_blank">', '<span class="star">&#9733;&#9733;&#9733;&#9733;&#9733;</span><span class="plugins-repository">', '</span></a>'));
?>
</div><?php 
