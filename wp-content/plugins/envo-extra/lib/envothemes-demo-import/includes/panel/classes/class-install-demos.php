<?php
/**
 * Install demos page
 *
 * @package EnvoThemes_Demo_Import
 * @category Core
 * @author EnvoThemes
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Start Class
class envo_Install_Demos {

    /**
     * Start things up
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_page'), 999);
    }

    /**
     * Add sub menu page for the custom CSS input
     *
     * @since 1.0.0
     */
    public function add_page() {


        $title = esc_html__('Install Demos', 'envothemes-demo-import');


        add_theme_page(
                esc_html__('Install Demos', 'envothemes-demo-import'),
                $title,
                'manage_options',
                'envothemes-panel-install-demos',
                array($this, 'create_admin_page')
        );
    }

    /**
     * Settings page output
     *
     * @since 1.0.0
     */
    public function create_admin_page() {

        // Theme branding
        $brand = 'EnvoThemes'
        ?>

        <div class="envo-demo-wrap wrap">

            <h2><?php echo esc_html($brand); ?> - <?php esc_html_e('Install Demos', 'envothemes-demo-import'); ?></h2>
            <div class="theme-browser rendered">

                <?php
                // Vars
                $demos = EnvoThemes_Demos::get_demos_data();
                $categories = EnvoThemes_Demos::get_demo_all_categories($demos);
                ?>

                <?php if (!empty($categories)) : ?>
                    <div class="envo-header-bar">
                        <nav class="envo-navigation">
                            <ul>
                                <li class="active"><a href="#all" class="envo-navigation-link"><?php esc_html_e('All', 'envothemes-demo-import'); ?></a></li>
                                <?php foreach ($categories as $key => $name) : ?>
                                    <li><a href="#<?php echo esc_attr($key); ?>" class="envo-navigation-link"><?php echo esc_html($name); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </nav>
                        <div clas="envo-search">
                            <input type="text" class="envo-search-input" name="envo-search" value="" placeholder="<?php esc_html_e('Search demos...', 'envothemes-demo-import'); ?>">
                        </div>
                    </div>
                <?php endif; ?>

                <div class="themes wp-clearfix">

                    <?php
                    // Loop through all demos
                    foreach ($demos as $demo => $key) {

                        // Vars
                        $item_categories = EnvoThemes_Demos::get_demo_item_categories($key);
						$pro = isset($key['required_plugins']) ? $key['required_plugins'] : '';
						$demo_link = isset($key['demo_link']) ? $key['demo_link'] : '';
                        ?>

                        <div class="theme-wrap" data-categories="<?php echo esc_attr($item_categories); ?>" data-name="<?php echo esc_attr(strtolower($demo)); ?>">

                            <div class="theme envo-open-popup" data-demo-id="<?php echo esc_attr($demo); ?>">

                                <div class="theme-screenshot">
                                    <img src="<?php echo esc_url($key['screenshot']); ?>" />

                                    <div class="demo-import-loader preview-all preview-all-<?php echo esc_attr($demo); ?>"></div>

                                    <div class="demo-import-loader preview-icon preview-<?php echo esc_attr($demo); ?>"><i class="custom-loader"></i></div>
									<?php if (isset($pro['premium']) && !empty($pro['premium'])) { ?>
                                        <div class="pro-badge">
                                            <?php esc_html_e('PRO', 'envo-extra'); ?>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="theme-id-container">

                                    <h2 class="theme-name" id="<?php echo esc_attr($demo); ?>"><span><?php echo esc_html($key['demo_name']); ?></span></h2>

                                    <div class="theme-actions">
                                        <a class="button button-primary" href="<?php echo esc_url($demo_link); ?>" target="_blank"><?php esc_html_e('Live Preview', 'envothemes-demo-import'); ?></a>
                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                </div>

            </div>

        </div>

        <?php
    }

}

new envo_Install_Demos();
