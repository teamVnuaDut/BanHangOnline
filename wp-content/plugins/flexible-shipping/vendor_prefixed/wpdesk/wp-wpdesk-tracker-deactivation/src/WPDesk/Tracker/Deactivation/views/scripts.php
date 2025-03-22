<?php

namespace FSVendor;

/**
 * @var $plugin_title string
 * @var $plugin_file string
 * @var $plugin_slug string
 * @var $thickbox_id string
 * @var $ajax_action string
 * @var $ajax_nonce string
 */
if (!\defined('ABSPATH')) {
    exit;
}
?><script type="text/javascript">

    jQuery(document).ready(function(){

        function resize_deactivation_tb_window() {
            let margin_horizontal = 40;
            let margin_vertical = 110;
            let $body = jQuery('#TB_ajaxContent').find('.wpdesk_tracker_deactivate');
            let $TB_window = jQuery(document).find('#TB_window');
            let $TB_ajaxContent = jQuery(document).find('#TB_ajaxContent');
            let width = $body.width();
            let height = $body.height();
            $TB_window.width( width + margin_horizontal ).height( height + margin_vertical ).css( 'margin-left', - ( width + margin_horizontal ) / 2 );
            $TB_ajaxContent.height( height + margin_vertical );
            let margin_top = window.innerHeight / 2 - $TB_window.height() / 2;
            if ( margin_top > 0) {
                $TB_window.css( 'margin-top', margin_top );
            }
        }

        function disable_buttons() {
            setTimeout(function(){
                tb_remove();
            }, 2100);
            jQuery('.<?php 
echo $thickbox_id;
?> .footer').attr('disabled',true).toggle(500);
            jQuery(document).find('#TB_window').toggle(1500);
        }

        jQuery(document).on('click', "span.deactivate a", function(e){
            if ( jQuery(this).closest('tr').attr('data-plugin') === '<?php 
echo $plugin_file;
?>' ) {
                e.preventDefault();
                let tb_id = '#TB_inline?inlineId=<?php 
echo $thickbox_id;
?>';
                tb_show('<?php 
\_e('Plugin deactivation', 'flexible-shipping');
?>', tb_id);
                resize_deactivation_tb_window();
            }
        });

        jQuery(document).on( 'click', '.<?php 
echo $thickbox_id;
?> .tracker-button-close', function(e) {
            e.preventDefault();
            tb_remove();
        });

        jQuery(document).on( 'click', '.<?php 
echo $thickbox_id;
?> .button-deactivate', function(e) {
            e.preventDefault();
            let url = jQuery("tr[data-plugin='<?php 
echo $plugin_file;
?>']").find('span.deactivate a').attr('href');
            let reason = jQuery('.<?php 
echo $thickbox_id;
?> input[name=selected-reason]:checked').val();
            let additional_info = jQuery('.<?php 
echo $thickbox_id;
?> input[name=selected-reason]:checked').closest('li').find('.additional-info').val();
            if ( typeof reason !== 'undefined' ) {
                jQuery('.button').attr('disabled',true);
                jQuery.ajax( '<?php 
echo \admin_url('admin-ajax.php');
?>',
                    {
                        type: 'POST',
                        data: {
                            'action': '<?php 
echo $ajax_action;
?>',
                            '_ajax_nonce': '<?php 
echo $ajax_nonce;
?>',
                            'reason': reason,
                            'additional_info': additional_info,
                        }
                    }
                ).always(function() {
                    window.location.href = url;
                });
            }
            else {
                window.location.href = url;
            }
            disable_buttons();
        });

        jQuery(document).on( 'click', '.<?php 
echo $thickbox_id;
?> .button-skip-and-deactivate', function(e) {
            e.preventDefault();
            window.location.href = jQuery("tr[data-plugin='<?php 
echo $plugin_file;
?>']").find('span.deactivate a').attr('href');
            disable_buttons();
        });

        jQuery(document).on( 'change', '.<?php 
echo $thickbox_id;
?> input[type=radio]', function(){
            var tracker_deactivate = jQuery(this).closest('.wpdesk_tracker_deactivate');
            tracker_deactivate.find('input[type=radio]').each(function(){
                const reason_input = jQuery(this).closest('li').find('.reason-input');
                const description = jQuery(this).closest('li').find('.description');
                const checked = jQuery(this).is(':checked');
                if ( checked ) {
                    description.show();
                    reason_input.show();
                    reason_input.find('textarea').focus();
                } else {
                    description.hide();
                    reason_input.hide();
                }
            });
            resize_deactivation_tb_window();
            jQuery('.<?php 
echo $thickbox_id;
?> .button-deactivate').attr('disabled',false);
        });

        jQuery(window).on( 'load', function() {
            jQuery(window).resize(function(){
                resize_deactivation_tb_window();
            });
        });
    });

</script>
<style>
    #TB_ajaxContent {
        overflow: hidden;
    }
    .<?php 
echo $thickbox_id;
?> input[type=text] {
        margin-left: 25px;
        width: 90%;
    }
</style>
<?php 
