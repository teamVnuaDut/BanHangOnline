<?php
global $post;
?>
<h4><?php _e( 'Shortcode', 'blossomthemes-email-newsletter' ); ?></h4>
<p><?php _e( 'Copy and paste this shortcode directly into any WordPress post or page.', 'blossomthemes-email-newsletter' ); ?></p>
<input type="text" class="large-text code" readonly="readonly" value='<?php echo '[BTEN id="'.$post->ID.'"]'; ?>' onClick="this.setSelectionRange(0, this.value.length)"/>

<h4><?php _e( 'Template Include', 'blossomthemes-email-newsletter' ); ?></h4>
<p><?php _e( 'Copy and paste this code into a template file to include the slider within your theme.', 'blossomthemes-email-newsletter' ); ?></p>
<input type="text" class="large-text code" readonly="readonly" value="&lt;?php echo do_shortcode(&quot;[BTEN id='<?php echo $post->ID; ?>']&quot;); ?&gt;" onClick="this.setSelectionRange(0, this.value.length)"/>
