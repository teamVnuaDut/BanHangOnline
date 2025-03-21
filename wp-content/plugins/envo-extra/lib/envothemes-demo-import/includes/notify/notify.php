<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * @review_dismiss()
 * @review_pending()
 * @envothemes_review_notice_message()
 * Make all the above functions working.
 */
function envothemes_review_notice() {

    envothemes_review_dismiss();
    envothemes_review_pending();

    $activation_time = get_site_option('envothemes_active_time');
    $review_dismissal = get_site_option('envothemes_review_dismiss');
    $maybe_later = get_site_option('envothemes_maybe_later');
	$onboard = get_site_option('elementor_onboarded');
	if (!$onboard) {
        add_site_option('elementor_onboarded', true);
    }

    if ('yes' == $review_dismissal) {
        return;
    }

    if (!$activation_time) {
        add_site_option('envothemes_active_time', time());
    }

    $daysinseconds = 2419200; // 1209600 14 Days in seconds.
    if ('yes' == $maybe_later) {
        $daysinseconds = 4819200; // 28 Days in seconds.
    }

    if (time() - $activation_time > $daysinseconds) {
        add_action('admin_notices', 'envothemes_review_notice_message');
    }
	if( 
	(defined('ENTR_PRO_CURRENT_VERSION') && version_compare(ENTR_PRO_CURRENT_VERSION, '1.2.0', '<') ) ||
	(defined('ENVO_STOREFRONT_PRO_CURRENT_VERSION') && version_compare(ENVO_STOREFRONT_PRO_CURRENT_VERSION, '1.8.1', '<') ) ||
	(defined('ENVO_SHOP_PRO_CURRENT_VERSION') && version_compare(ENVO_SHOP_PRO_CURRENT_VERSION, '1.9.5', '<') ) ||
	(defined('ENVO_ONLINE_STORE_PRO_CURRENT_VERSION') && version_compare(ENVO_ONLINE_STORE_PRO_CURRENT_VERSION, '1.8.0', '<') ) ||
	(defined('ENVO_SHOPPER_PRO_CURRENT_VERSION') && version_compare(ENVO_SHOPPER_PRO_CURRENT_VERSION, '1.9.0', '<') ) ||
	(defined('ENVO_MARKETPLACE_PRO_CURRENT_VERSION') && version_compare(ENVO_MARKETPLACE_PRO_CURRENT_VERSION, '1.9.0', '<') ) ||
	(defined('ENWOO_PRO_CURRENT_VERSION') && version_compare(ENWOO_PRO_CURRENT_VERSION, '1.5.2', '<') ) ||
	(defined('ENVO_ECOMMERCE_PRO_CURRENT_VERSION') && version_compare(ENVO_ECOMMERCE_PRO_CURRENT_VERSION, '2.2.2', '<')  ) ||
	(defined('ENVO_MAGAZINE_PRO_CURRENT_VERSION') && version_compare(ENVO_MAGAZINE_PRO_CURRENT_VERSION, '2.1.1', '<')  )
	)	{add_action('admin_notices',  'envo_extra_admin_notice_update_pro' );}
}

add_action('admin_init', 'envothemes_review_notice');

/**
 * For the notice preview.
 */
function envothemes_review_notice_message() {
	if (isset($_SERVER['REQUEST_URI'])) {
		$server = sanitize_text_field( wp_unslash($_SERVER['REQUEST_URI']));
	}
    $scheme = (parse_url($server, PHP_URL_QUERY)) ? '&' : '?';
    $url = $server . $scheme . 'envothemes_review_dismiss=yes';
    $dismiss_url = wp_nonce_url($url, 'envo-review-nonce');

    $_later_link = $server . $scheme . 'envothemes_review_later=yes';
    $later_url = wp_nonce_url($_later_link, 'envo-review-nonce');
    $theme = wp_get_theme();
    $themetemplate = get_stylesheet();
    $themename = $theme->name;
    ?>

    <div class="envo-review-notice">
        <div class="envo-review-thumbnail">
            <img src="<?php echo esc_url(ENVO_URL) . 'img/et-logo.png'; ?>" alt="">
        </div>
        <div class="envo-review-text">
            <h3><?php esc_html_e('Leave A Review?', 'envothemes-demo-import') ?></h3>
            <p><?php echo sprintf(esc_html__('We hope you\'ve enjoyed using %1$s theme! Would you consider leaving us a review on WordPress.org?', 'envothemes-demo-import'), esc_html($themename)) ?></p>
            <ul class="envo-review-ul">
                <li>
                    <a href="https://wordpress.org/support/theme/<?php echo esc_html($themetemplate); ?>/reviews/?rate=5#new-post" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php esc_html_e('Sure! I\'d love to!', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url($dismiss_url) ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php esc_html_e('I\'ve already left a review', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url($later_url) ?>">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <?php esc_html_e('Maybe Later', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li>
                    <a href="https://envothemes.com/contact/" target="_blank">
                        <span class="dashicons dashicons-sos"></span>
                        <?php esc_html_e('I need help!', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url($dismiss_url) ?>">
                        <span class="dashicons dashicons-dismiss"></span>
                        <?php esc_html_e('Never show again', 'envothemes-demo-import') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php
}

/**
 * For Dismiss! 
 */
function envothemes_review_dismiss() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'envo-review-nonce') ||
            !isset($_GET['envothemes_review_dismiss'])) {

        return;
    }

    add_site_option('envothemes_review_dismiss', 'yes');
}

/**
 * For Maybe Later Update.
 */
function envothemes_review_pending() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'envo-review-nonce') ||
            !isset($_GET['envothemes_review_later'])) {

        return;
    }
    // Reset Time to current time.
    update_site_option('envothemes_active_time', time());
    update_site_option('envothemes_maybe_later', 'yes');
}

function envothemes_pro_notice() {

    envothemes_pro_dismiss();
	
	$theme = wp_get_theme();
    $themetemplate = $theme->template;
	$activetheme = str_replace('-', '_', strtoupper($themetemplate));

    $activation_time = get_site_option('envothemes_active_pro_time');

    if (!$activation_time) {
        add_site_option('envothemes_active_pro_time', time());
    }

    $daysinseconds = 86400; // 1 Day in seconds.

    if (time() - $activation_time > $daysinseconds) {
        if (defined($activetheme . '_PRO_CURRENT_VERSION')) {
            return;
        }
		if (preg_match('[envo|enwoo|entr]', $themetemplate) ) {
			add_action('admin_notices', 'envothemes_pro_notice_message');
		}
    }
}

add_action('admin_init', 'envothemes_pro_notice');

/**
 * For PRO notice 
 */
function envothemes_pro_notice_message() {
	if (isset($_SERVER['REQUEST_URI'])) {
		$server = sanitize_text_field( wp_unslash($_SERVER['REQUEST_URI']));
	}
    $scheme = (parse_url($server, PHP_URL_QUERY)) ? '&' : '?';
    $url = $server . $scheme . 'envothemes_pro_dismiss=yes';
    $dismiss_url = wp_nonce_url($url, 'envo-pro-nonce');
	$theme = wp_get_theme();
    $themetemplate = $theme->template;
	if ($themetemplate == 'enwoo') {
		$templateurl = 'https://enwoo-wp.com/enwoo-pro/';
	} elseif ($themetemplate == 'entr' || $themetemplate == 'envo-one') {
		$templateurl = 'https://envothemes.com/envo-pro/';
	} else {
		$templateurl = 'https://envothemes.com/product/' . $themetemplate . '-pro/';
	}
	
    ?>

    <div class="envo-review-notice">
        <div class="envo-review-thumbnail">
            <img src="<?php echo esc_url(ENVO_URL) . 'img/et-logo.png'; ?>" alt="">
        </div>
        <div class="envo-review-text">
            <h3><?php esc_html_e('Go PRO for More Features', 'envothemes-demo-import') ?></h3>
            <p>
                <?php echo sprintf(esc_html__('Get the %1$s for more stunning elements, demos and customization options.', 'envothemes-demo-import'), '<a href="'.esc_url($templateurl).'" target="_blank">PRO addon</a>') ?>
            </p>
            <ul class="envo-review-ul">
                <li class="show-mor-message">
                    <a href="<?php echo esc_url($templateurl) ?>" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php esc_html_e('Show me more', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li class="hide-message">
                    <a href="<?php echo esc_url($dismiss_url) ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php esc_html_e('Hide this message', 'envothemes-demo-import') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php
}

/**
 * For PRO Dismiss! 
 */
function envothemes_pro_dismiss() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'envo-pro-nonce') ||
            !isset($_GET['envothemes_pro_dismiss'])) {

        return;
    }
    $daysinseconds = 1209600; // 14 Days in seconds (1209600).
    $newtime = time() + $daysinseconds;
    update_site_option('envothemes_active_pro_time', $newtime);
}

/**
 * Envo One notice
 */
function envo_extra_envo_one_notice() {

    envo_extra_envo_one_dismiss();
    
    $theme = wp_get_theme();
    $activation_time = get_site_option('active_envo_one_time');

    if (!$activation_time) {
        add_site_option('active_envo_one_time', time());
    }

    $daysinseconds = 600; // 1 Day in seconds.

    if (time() - $activation_time > $daysinseconds) {
        if (defined('ENVO_ROYAL_PRO_CURRENT_VERSION') || defined('ENTR_PRO_CURRENT_VERSION') || defined('ENWOO_PRO_CURRENT_VERSION') || defined('ENVO_ECOMMERCE_PRO_CURRENT_VERSION') || defined('ENVO_STOREFRONT_PRO_CURRENT_VERSION') || defined('ENVO_SHOP_PRO_CURRENT_VERSION') || defined('ENVO_ONLINE_STORE_PRO_CURRENT_VERSION') || defined('ENVO_MARKETPLACE_PRO_CURRENT_VERSION') || defined('ENVO_SHOPPER_PRO_CURRENT_VERSION')) {
            return;
        }
        if ( 'Envo One' != $theme->name || 'envo-one' != $theme->template ) {
            add_action('admin_notices', 'envo_extra_envo_one_notice_message');
        }
    }
}

add_action('admin_init', 'envo_extra_envo_one_notice');

/**
 * For shop notice 
 */
function envo_extra_envo_one_notice_message() {
	if (isset($_SERVER['REQUEST_URI'])) {
		$server = sanitize_text_field( wp_unslash($_SERVER['REQUEST_URI']));
	}
    $scheme = (parse_url($server, PHP_URL_QUERY)) ? '&' : '?';
    $url = $server . $scheme . 'envo_extra_envo_one_dismiss=yes';
    $dismiss_url = wp_nonce_url($url, 'envo-one-nonce');
    ?>

    <div class="envo-review-notice envo-shop-notice">
        <div class="envo-review-thumbnail">
            <img src="<?php echo esc_url(ENVO_EXTRA_PLUGIN_URL) . 'images/envo-one.png'; ?>" alt="">
        </div>
        <div class="envo-review-text">
            <h3><?php esc_html_e('Try our new FREE WooCommerce WordPress Theme - Envo One', 'envothemes-demo-import') ?></h3>
            <p>
                <?php
                echo sprintf(
                        esc_html__('%1$s - new free WooCommerce theme. Check out theme %2$s, that can be imported for FREE with simple click.', 'envothemes-demo-import'),
                        '<a href="https://envothemes.com/envo-one-woocommerce/" target="_blank">Envo One</a>',
                        '<a href="https://envothemes.com/envo-one-woocommerce/#demos" target="_blank">Demos</a>')
                ?>
            </p>
            <ul class="envo-review-ul">
                <li class="show-mor-message">
                    <a href="https://envothemes.com/envo-one-woocommerce/" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php esc_html_e('Show me more', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li class="hide-message">
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php esc_html_e('Hide this message', 'envothemes-demo-import') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php
}

/**
 * For shop Dismiss! 
 */
function envo_extra_envo_one_dismiss() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'envo-one-nonce') ||
            !isset($_GET['envo_extra_envo_one_dismiss'])) {

        return;
    }
    $daysinseconds = 1209600; // 14 Days in seconds (1209600).
    $newtime = time() + $daysinseconds;
    update_site_option('active_envo_one_time', $newtime);
}

function envo_extra_admin_notice_update_pro(){
	
	$theme = wp_get_theme();
    $themetemplate = $theme->template;
	if ($themetemplate == 'entr' || $themetemplate == 'envo-one' ) {
		$themetemplate = 'envo';
	}
	if ($themetemplate == 'enwoo') {
		$changelogurl = 'https://enwoo-wp.com/' . $themetemplate . '-pro-changelog/';
		$updateurl = 'https://enwoo-wp.com/how-to-update-plugin/';
	}  else {
		$changelogurl = 'https://envothemes.com/' . $themetemplate . '-pro-changelog/';
		$updateurl = 'https://envothemes.com/how-to-update-plugin/';
	}

	$message = sprintf( __( '%1$s requires an %2$supdate%3$s. Please update the plugin to ensure full compatibility with the %4$s theme and WordPress.', 'envothemes-demo-import' ), '<strong>' . ucwords(str_replace('-', ' ',  $themetemplate)) . ' PRO</strong>','<strong>', '</strong>', '<strong>' . ucwords(str_replace('-', ' ',  $themetemplate)) . '</strong>' );
	$button_text = __( 'Update', 'envo-extra' );

	$button = '<p><a href="' . esc_url(admin_url( 'update-core.php?force-check=1')) . '" class="button-secondary">' . esc_html($button_text) . '</a><a href="' . esc_url($changelogurl) . '" target="_blank" class="envo-link-changelog" style="margin-left:10px;margin-top: 4px;display: inline-block;">' . esc_html('Changelog') . '</a><a href="' . esc_url($updateurl) . '" target="_blank" class="envo-link" style="margin-left:10px;margin-top: 4px;display: inline-block;">' . esc_html('How to update?') . '</a></p>';
	printf( '<div class="error"><p>%1$s</p>%2$s</div>', $message, $button );
}
