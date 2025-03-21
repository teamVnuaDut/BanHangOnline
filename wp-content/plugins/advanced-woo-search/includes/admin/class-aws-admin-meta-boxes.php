<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Admin_Meta_Boxes' ) ) :

    /**
     * Class for plugin admin panel
     */
    class AWS_Admin_Meta_Boxes {

        /*
         * Get content for the General tab
         * @return string
         */
        static public function get_general_tab_content() {

            $html = '';
            
            $html .= '<table class="form-table">';
                $html .= '<tbody>';
        
                $html .= '<tr id="activation">';
        
                    $html .= '<th>' . esc_html__( 'Activation', 'advanced-woo-search' ) . '</th>';
                    $html .='<td>';
                        $html .='<div class="description activation">';
                            $html .= esc_html__( 'In case you need to add plugin search form on your website, you can do it in several ways:', 'advanced-woo-search' ) . '<br>';
                            $html .='<div class="list">';
                                $html .='1. ' . sprintf(esc_html__( "Enable a %s option ( may not work with some themes )", 'advanced-woo-search' ), '<a href="#main">' . __( 'Seamless integration', 'advanced-woo-search' ) . '</a>' ) . '<br>';
                                $html .='2. ' . sprintf( esc_html__( 'Using shortcode %s', 'advanced-woo-search' ), '<code>[aws_search_form]</code>' ) . '<br>';
                                $html .='3. ' . sprintf( esc_html__( "Add search form as a widget. Go to %s and drag&drop 'AWS Widget' to one of your widget areas", 'advanced-woo-search' ), '<a href="' . admin_url( 'widgets.php' ) . '" target="_blank">' . __( 'Widgets Screen', 'advanced-woo-search' ) . '</a>' ) . '<br>';
                                $html .='4. ' . sprintf( esc_html__( 'Add PHP code to the necessary files of your theme: %s', 'advanced-woo-search' ), "<code>&lt;?php aws_get_search_form( true ); ?&gt;</code>" ) . '<br>';
                            $html .='</div>';
                        $html .='</div>';
                    $html .='</td>';

                $html .= '</tr>';
        
                $html .= '<tr>';
        
                    $html .= '<th>' . esc_html__( 'Reindex table', 'advanced-woo-search' ) . '</th>';
                    $html .= '<td>';
                        $html .= '<div id="aws-reindex"><input class="button" type="button" value="' . esc_attr__( 'Reindex table', 'advanced-woo-search' ) . '"><span class="loader"></span><span class="reindex-progress">0%</span><span class="reindex-notice">' . __( 'Please do not close the page.', 'advanced-woo-search' ) . '</span></div><br><br>';
                        $html .= '<span class="description">' .
                            sprintf( esc_html__( 'This action only need for %s one time %s - after you activate this plugin. After this all products changes will be re-indexed automatically.', 'advanced-woo-search' ), '<strong>', '</strong>' ) . '<br>' .
                            __( 'Update all data in plugins index table. Index table - table with products data where plugin is searching all typed terms.<br>Use this button if you think that plugin not shows last actual data in its search results.<br>' .
                            '<strong>CAUTION:</strong> this can take large amount of time.', 'advanced-woo-search' ) . sprintf( __( 'Index table options can be found inside %s section.', 'advanced-woo-search' ), '<a href="'.esc_url( admin_url('admin.php?page=aws-options&tab=performance') ).'">' . __( 'Performance', 'advanced-woo-search' ) . '</a>' ) . '<br><br>' .
                            esc_html__( 'Products in index:', 'advanced-woo-search' ) . '<span id="aws-reindex-count"> <strong>' . AWS_Helpers::get_indexed_products_count() . '</strong></span>';
                        $html .= '</span>';
                    $html .= '</td>';
        
                $html .= '</tr>';
        
                $html .= '</tbody>';
            $html .= '</table>';

            return $html;
            
        }
        
        /*
         * Get content for the welcome notice
         * @return string
         */
        static public function get_welcome_notice() {

            $html = '';

            $html .= '<div id="aws-welcome-panel">';
                $html .= '<div class="aws-welcome-notice updated notice is-dismissible" style="background:#f2fbff;">';

                    $html .= '<div class="aws-welcome-panel" style="border:none;box-shadow:none;padding:0;margin:16px 0 0;background:transparent;">';
                        $html .= '<div class="aws-welcome-panel-content">';
                            $html .= '<h2>' . sprintf( __( 'Welcome to %s', 'advanced-woo-search' ), 'Advanced Woo Search' ) . '</h2>';
                            $html .= '<p class="about-description">' . __( 'Powerful search plugin for WooCommerce.', 'advanced-woo-search' ) . '</p>';
                            $html .= '<div class="aws-welcome-panel-column-container">';
                                $html .= '<div class="aws-welcome-panel-column">';
                                    $html .= '<h4>' . __( 'Get Started', 'advanced-woo-search' ) . '</h4>';
                                    $html .= '<p style="margin-bottom:10px;">' . __( 'In order to start using the plugin search form you need to take following steps:', 'advanced-woo-search' ) . '</p>';
                                    $html .= '<ul>';
                                        $html .= '<li><strong>1.</strong> <strong>' . __( 'Index plugin table.', 'advanced-woo-search' ) . '</strong> ' . __( 'Click on the \'Reindex table\' button and wait till the index process is finished.', 'advanced-woo-search' ) . '</li>';
                                        $html .= '<li><strong>2.</strong> <strong>' . __( 'Set plugin settings.', 'advanced-woo-search' ) . '</strong> ' . __( 'Leave it to default values or customize some of them.', 'advanced-woo-search' ) . '</li>';
                                        $html .= '<li><strong>3.</strong> <strong>' . __( 'Add search form.', 'advanced-woo-search' ) . '</strong> ' . sprintf( __( 'There are several ways you can add a search form to your site. Use the \'Seamless integration\' option, shortcode, widget or custom php function. Read more inside %s section or read %s.', 'advanced-woo-search' ), '<a href="#activation">' .  __( 'Activation', 'advanced-woo-search' ) . '</a>', '<a target="_blank" href="https://advanced-woo-search.com/guide/search-form/">' .  __( 'guide article', 'advanced-woo-search' ) . '</a>' ) . '</li>';
                                        $html .= '<li><strong>4.</strong> <strong>' . __( 'Finish!', 'advanced-woo-search' ) . '</strong> ' . __( 'Now all is set and you can check your search form on the pages where you add it.', 'advanced-woo-search' ) . '</li>';
                                    $html .= '</ul>';
                                $html .= '</div>';
                                $html .= '<div class="aws-welcome-panel-column">';
                                    $html .= '<h4>' . __( 'Documentation', 'advanced-woo-search' ) . '</h4>';
                                    $html .= '<ul>';
                                        $html .= '<li><a href="https://advanced-woo-search.com/guide/steps-to-get-started/" class="aws-welcome-icon aws-welcome-edit-page" target="_blank">' . __( 'Steps to Get Started', 'advanced-woo-search' ) . '</a></li>';
                                        $html .= '<li><a href="https://advanced-woo-search.com/guide/search-form/" class="aws-welcome-icon aws-welcome-edit-page" target="_blank">' . __( 'How to Add Search Form', 'advanced-woo-search' ) . '</a></li>';
                                        $html .= '<li><a href="https://advanced-woo-search.com/guide/search-source/" class="aws-welcome-icon aws-welcome-edit-page" target="_blank">' . __( 'Search Sources', 'advanced-woo-search' ) . '</a></li>';
                                        $html .= '<li><a href="https://advanced-woo-search.com/guide/terms-search/" class="aws-welcome-icon aws-welcome-edit-page" target="_blank">' . __( 'Terms Pages Search', 'advanced-woo-search' ) . '</a></li>';
                                    $html .= '</ul>';
                                $html .= '</div>';
                                $html .= '<div class="aws-welcome-panel-column aws-welcome-panel-last">';
                                    $html .= '<h4>' . __( 'Help', 'advanced-woo-search' ) . '</h4>';
                                    $html .= '<ul>';
                                        $html .= '<li><div class="aws-welcome-icon aws-welcome-widgets-menus"><a href="https://wordpress.org/support/plugin/advanced-woo-search/" target="_blank">' . __( 'Support Forums', 'advanced-woo-search' ) . '</a></div></li>';
                                        $html .= '<li><div class="aws-welcome-icon aws-welcome-widgets-menus"><a href="https://advanced-woo-search.com/contact/" target="_blank">' . __( 'Contact Form', 'advanced-woo-search' ) . '</a></div></li>';
                                    $html .= '</ul>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';

                $html .= '</div>';
            $html .= '</div>';

            return $html;

        }

        /*
         * Get content for the reindex notice
         * @return string
         */
        static public function get_reindex_notice() {

            $html = '';

            $html .= '<div class="updated notice is-dismissible">';
                $html .= '<p>';
                    $html .= sprintf( esc_html__( 'Advanced Woo Search: In order to apply the changes in the index table you need to reindex. %s', 'advanced-woo-search' ), '<a class="button button-secondary" href="'.esc_url( admin_url('admin.php?page=aws-options') ).'">'.esc_html__( 'Go to Settings Page', 'advanced-woo-search' ).'</a>'  );
                $html .= '</p>';
            $html .= '</div>';

            return $html;

        }

    }

endif;