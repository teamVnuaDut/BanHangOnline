<?php
/**
 * Plugin Installer
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Start Class
class EnvoThemes_Demo_Import_Plugin_Installer {

    public function start() {
        add_action('wp_ajax_oe_plugin_installer', array($this, 'oe_plugin_installer'));
        add_action('wp_ajax_oe_plugin_activation', array($this, 'oe_plugin_activation'));
        add_action('wp_ajax_oe_premium_plugin_activation', array($this, 'oe_premium_plugin_activation'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Initialize the display of the free plugins
     *
     * @since 1.0.0
     */
    public static function init($plugins) {
        ?>

        <?php
        require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

        foreach ($plugins as $plugin) :

            $button_classes = 'install button';
            $button_text = __('Install Now', 'envothemes-demo-import');

            $api = plugins_api('plugin_information',
                    array(
                        'slug' => sanitize_file_name($plugin['slug']),
                        'fields' => array(
                            'short_description' => true,
                            'sections' => false,
                            'requires' => false,
                            'downloaded' => true,
                            'last_updated' => false,
                            'added' => false,
                            'tags' => false,
                            'compatibility' => false,
                            'homepage' => false,
                            'donate_link' => false,
                            'icons' => true,
                            'banners' => true,
                        ),
                    )
            );

            if (!is_wp_error($api)) { // confirm error free
                $main_plugin_file = EnvoThemes_Demo_Import_Plugin_Installer::get_plugin_file($plugin['slug']); // Get main plugin file

                if (self::check_file_extension($main_plugin_file)) { // check file extension
                    if (is_plugin_active($main_plugin_file)) {
                        // plugin activation, confirmed!
                        $button_classes = 'button disabled';
                        $button_text = __('Activated', 'envothemes-demo-import');
                    } else {
                        // It's installed, let's activate it
                        $button_classes = 'activate button button-primary';
                        $button_text = __('Activate', 'envothemes-demo-import');
                    }
                }

                // Send plugin data to template
                self::render_template($plugin, $api, $button_text, $button_classes);
            }

        endforeach;
        ?>

        <?php
    }

    /**
     * Render display template for each free plugin
     *
     * @since 1.0.0
     */
    public static function render_template($plugin, $api, $button_text, $button_classes) {
        ?>

        <div class="plugin">
            <div class="plugin-wrap">
                <img src="<?php echo esc_html($api->icons['1x']); ?>" alt="">
                <h2><?php echo esc_html($api->name); ?></h2>
                <p><?php echo esc_html($api->short_description); ?></p>

                <p class="plugin-author"><?php esc_html_e('By', 'envothemes-demo-import'); ?> <?php echo esc_html($api->author); ?></p>
            </div>

            <ul class="activation-row">
                <li>
                    <a class="<?php echo esc_attr($button_classes); ?>" data-slug="<?php echo esc_attr($api->slug); ?>" data-name="<?php echo esc_attr($api->name); ?>" href="<?php echo esc_url(get_admin_url()); ?>update.php?action=install-plugin&amp;plugin=<?php echo esc_attr($api->slug); ?>&amp;_wpnonce=<?php echo esc_attr(wp_create_nonce('install-plugin_' . esc_attr($api->slug))) ?>"><?php echo esc_html($button_text); ?></a>
                </li>
                <li>
                    <a href="https://wordpress.org/plugins/<?php echo esc_attr($api->slug); ?>/" target="_blank"><?php esc_html_e('More Details', 'envothemes-demo-import'); ?></a>
                </li>
            </ul>
        </div>

        <?php
    }

    /**
     * Initialize the display of the premium plugins
     *
     * @since 1.0.0
     */
    public static function init_premium($plugins) {
        ?>

        <?php
        foreach ($plugins as $plugin) :

            $button_classes = '';
            $button_text = '';

            $api = array(
                'slug' => isset($plugin['slug']) ? $plugin['slug'] : '',
                'url' => isset($plugin['url']) ? $plugin['url'] : '',
                'full_url' => isset($plugin['full_url']) ? $plugin['full_url'] : '',
                'name' => isset($plugin['name']) ? $plugin['name'] : '',
                'description' => isset($plugin['description']) ? $plugin['description'] : '',
                'icons' => isset($plugin['icons']) ? $plugin['icons'] : '',
                'author' => isset($plugin['author']) ? $plugin['author'] : '',
                'author_url' => isset($plugin['author_url']) ? $plugin['author_url'] : '',
            );

            if (!is_wp_error($api)) { // confirm error free
                $main_plugin_file = EnvoThemes_Demo_Import_Plugin_Installer::get_plugin_file($plugin['slug']); // Get main plugin file

                if (self::check_file_extension($main_plugin_file)) { // check file extension
                    if (is_plugin_active($main_plugin_file)) {
                        // plugin activation, confirmed!
                        $button_classes = 'button disabled';
                        $button_text = __('Activated', 'envothemes-demo-import');
                    } else {
                        // It's installed, let's activate it
                        $button_classes = 'activate button button-primary premium-activation';
                        $button_text = __('Activate', 'envothemes-demo-import');
                    }
                }

                // Send plugin data to template
                self::render_premium_template($plugin, $api, $button_text, $button_classes);
            }

        endforeach;
        ?>

        <?php
    }

    /**
     * Render display template for each premium plugin
     *
     * @since 1.0.0
     */
    public static function render_premium_template($plugin, $api, $button_text, $button_classes) {

        // Var
        $slug = $api['slug'];
        $url = $api['url'];
        $full_url = $api['full_url'];
        $name = $api['name'];
        $description = $api['description'];
        $icons = $api['icons'];
        $author = $api['author'];
        $author_url = $api['author_url'];
        ?>

        <div class="plugin">
            <div class="plugin-wrap">

                <?php if ($icons) { ?>
                    <img src="<?php echo esc_url($icons); ?>" alt="<?php echo esc_attr($name); ?>" />
                    <?php
                }

                if ($name) {
                    ?>
                    <h2><?php echo esc_html($name); ?></h2>
                    <?php
                }

                if ($description) {
                    ?>
                    <p><?php echo esc_html($description); ?></p>
                    <?php
                }

                if ($author) {
                    ?>
                    <p class="plugin-author"><?php esc_html_e('By', 'envothemes-demo-import'); ?> <a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html($author); ?></a></p>
                <?php }
                ?>
            </div>

            <ul class="activation-row">
                <li>
                    <?php
                    // Get main plugin file
                    $main_plugin_file = EnvoThemes_Demo_Import_Plugin_Installer::get_plugin_file($plugin['slug']);

                    // If the plugin is installed
                    if (self::check_file_extension($main_plugin_file)) {
                        ?>

                        <a class="<?php echo esc_attr($button_classes); ?>" data-slug="<?php echo esc_attr($slug); ?>" data-name="<?php echo esc_attr($name); ?>" href="<?php echo esc_url(get_admin_url()); ?>update.php?action=install-plugin&amp;plugin=<?php echo esc_attr($slug); ?>&amp;_wpnonce=<?php echo esc_attr(wp_create_nonce('install-plugin_' . $slug)) ?>"><?php echo esc_html($button_text); ?></a>

                        <?php
                        // If the plugin is not installed
                    } else {

                        // If full url, used for the rec. plugins tab
                        if ($full_url) {
                            ?>
                            <a class="button premium-link" href="<?php echo esc_url($full_url); ?>" target="_blank"><?php esc_html_e('Get This Plugin', 'envothemes-demo-import'); ?></a>
                            <?php
                        }
                    }
                    ?>
                </li>
                <li>
                    <?php
                    // If full url, used for the rec. plugins tab
                    if ($full_url) {
                        ?>
                        <a href="<?php echo esc_url($full_url); ?>" target="_blank"><?php esc_html_e('More Details', 'envothemes-demo-import'); ?></a>
                        <?php
                    }
                    ?>
                </li>
                <li class="ribbon">
                    <?php esc_html_e('Premium', 'envothemes-demo-import'); ?>
                </li>
            </ul>
        </div>

        <?php
    }

    /**
     * An Ajax method for installing plugin
     *
     * @since 1.0.0
     */
    public function oe_plugin_installer() {

        if (!current_user_can('install_plugins')) {
            wp_die(esc_html__('Sorry, you are not allowed to install plugins on this site.', 'envothemes-demo-import'));
        }
        if (isset($_POST['nonce'])) {
            $nonce = sanitize_text_field(wp_unslash($_POST["nonce"]));
        }
        if (isset($_POST['plugin'])) {
            $plugin = sanitize_text_field(wp_unslash($_POST["plugin"]));
        }
        // Check our nonce, if they don't match then bounce!
        if (!wp_verify_nonce($nonce, 'oe_installer_nonce')) {
            wp_die(esc_html__('Error - unable to verify nonce, please try again.', 'envothemes-demo-import'));
        }

        // Include required libs for installation
        require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );

        // Get Plugin Info
        $api = plugins_api('plugin_information',
                array(
                    'slug' => $plugin,
                    'fields' => array(
                        'short_description' => false,
                        'sections' => false,
                        'requires' => false,
                        'rating' => false,
                        'ratings' => false,
                        'downloaded' => false,
                        'last_updated' => false,
                        'added' => false,
                        'tags' => false,
                        'compatibility' => false,
                        'homepage' => false,
                        'donate_link' => false,
                    ),
                )
        );

        $skin = new WP_Ajax_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader($skin);
        $upgrader->install($api->download_link);

        if ($api->name) {
            $status = 'success';
            $msg = $api->name . ' successfully installed.';
        } else {
            $status = 'failed';
            $msg = 'There was an error installing ' . $api->name . '.';
        }

        $json = array(
            'status' => $status,
            'msg' => $msg,
        );

        wp_send_json($json);
    }

    /**
     * Activate plugin via Ajax
     *
     * @since 1.0.0
     */
    public function oe_plugin_activation() {

        if (!current_user_can('install_plugins')) {
            wp_die(esc_html__('Sorry, you are not allowed to activate plugins on this site.', 'envothemes-demo-import'));
        }

        if (isset($_POST['nonce'])) {
            $nonce = sanitize_text_field(wp_unslash($_POST["nonce"]));
        }
        if (isset($_POST['plugin'])) {
            $plugin = sanitize_text_field(wp_unslash($_POST["plugin"]));
        }

        // Check our nonce, if they don't match then bounce!
        if (!wp_verify_nonce($nonce, 'oe_installer_nonce')) {
            die(esc_html__('Error - unable to verify nonce, please try again.', 'envothemes-demo-import'));
        }


        // Include required libs for activation
        require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );


        // Get Plugin Info
        $api = plugins_api('plugin_information',
                array(
                    'slug' => $plugin,
                    'fields' => array(
                        'short_description' => false,
                        'sections' => false,
                        'requires' => false,
                        'rating' => false,
                        'ratings' => false,
                        'downloaded' => false,
                        'last_updated' => false,
                        'added' => false,
                        'tags' => false,
                        'compatibility' => false,
                        'homepage' => false,
                        'donate_link' => false,
                    ),
                )
        );

        if ($api->name) {
            $main_plugin_file = EnvoThemes_Demo_Import_Plugin_Installer::get_plugin_file($plugin);
            $status = 'success';
            if ($main_plugin_file) {
                activate_plugin($main_plugin_file);
                $msg = $api->name . ' successfully activated.';
            }
        } else {
            $status = 'failed';
            $msg = 'There was an error activating ' . $api->name . '.';
        }

        $json = array(
            'status' => $status,
            'msg' => $msg,
        );

        wp_send_json($json);
    }

    /**
     * Activate premium plugin via Ajax
     *
     * @since 1.0.0
     */
    public function oe_premium_plugin_activation() {

        if (!current_user_can('install_plugins')) {
            wp_die(esc_html__('Sorry, you are not allowed to activate plugins on this site.', 'envothemes-demo-import'));
        }

        if (isset($_POST['nonce'])) {
            $nonce = sanitize_text_field(wp_unslash($_POST["nonce"]));
        }
        if (isset($_POST['plugin'])) {
            $plugin = sanitize_text_field(wp_unslash($_POST["plugin"]));
        }

        // Check our nonce, if they don't match then bounce!
        if (!wp_verify_nonce($nonce, 'oe_installer_nonce')) {
            die(esc_html__('Error - unable to verify nonce, please try again.', 'envothemes-demo-import'));
        }


        // Include required libs for activation
        require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );


        // Get Plugin Info
        $api = array(
            'slug' => $plugin,
            'name' => $plugin['name'],
        );

        if ($api['name']) {
            $main_plugin_file = EnvoThemes_Demo_Import_Plugin_Installer::get_plugin_file($plugin);
            $status = 'success';
            if ($main_plugin_file) {
                activate_plugin($main_plugin_file);
                $msg = $api['name'] . ' successfully activated.';
            }
        } else {
            $status = 'failed';
            $msg = 'There was an error activating ' . $api['name'] . '.';
        }

        $json = array(
            'status' => $status,
            'msg' => $msg,
        );

        wp_send_json($json);
    }

    /**
     * A method to get the main plugin file
     *
     * @since 1.0.0
     */
    public static function get_plugin_file($plugin_slug) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' ); // Load plugin lib

        $plugins = get_plugins();

        foreach ($plugins as $plugin_file => $plugin_info) {

            // Get the basename of the plugin e.g. [askismet]/askismet.php
            $slug = dirname(plugin_basename($plugin_file));

            if ($slug) {
                if ($slug == $plugin_slug) {
                    return $plugin_file; // If $slug = $plugin_name
                }
            }
        }

        return null;
    }

    /**
     * A helper to check file extension
     *
     * @since 1.0.0
     */
    public static function check_file_extension($filename) {
        if (substr(strrchr($filename, '.'), 1) === 'php') {
            // has .php exension
            return true;
        } else {
            // ./wp-content/plugins
            return false;
        }
    }

    /**
     * Load scripts
     *
     * @since 1.0.0
     */
    public static function enqueue_scripts($hook) {

        // Only load scripts when needed
        if (ENVO_ADMIN_PANEL_HOOK_PREFIX . '-extensions' != $hook && ENVO_ADMIN_PANEL_HOOK_PREFIX . '-rec-plugins' != $hook) {
            return;
        }

        // JS
        wp_enqueue_script('envothemes-installer', plugins_url('/js/installer.min.js', __FILE__), array('jquery'));

        wp_localize_script('envothemes-installer', 'oe_installer_localize', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'admin_nonce' => wp_create_nonce('oe_installer_nonce'),
            'install_now' => __('Are you sure you want to install this plugin?', 'envothemes-demo-import'),
            'install_btn' => __('Install Now', 'envothemes-demo-import'),
            'activate_btn' => __('Activate', 'envothemes-demo-import'),
            'installed_btn' => __('Activated', 'envothemes-demo-import')
        ));

        // CSS
        wp_enqueue_style('envothemes-installer', plugins_url('/css/installer.min.css', __FILE__));
    }

}

// initialize
$EnvoThemes_Demo_Import_plugin_installer = new EnvoThemes_Demo_Import_Plugin_Installer();
$EnvoThemes_Demo_Import_plugin_installer->start();
