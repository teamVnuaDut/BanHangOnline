<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Admin_Page_Premium' ) ) :

    /**
     * Class for plugin admin ajax hooks
     */
    class AWS_Admin_Page_Premium {

        /*
         * Constructor
         */
        public function __construct() {
            
            $this->generate_content();

        }

        /*
         * Generate options fields
         */
        private function generate_content() {

            echo '<div class="links">';
                echo  '<span class="links-title">' . __( 'Website Links:', 'advanced-woo-search' ) . '</span>';
                echo '<ul>';
                    echo '<li><a target="_blank" href="https://advanced-woo-search.com/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin">' . __( 'Plugin home page', 'advanced-woo-search' ) . '</a></li>';
                    echo '<li><a target="_blank" href="https://advanced-woo-search.com/features/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin">' . __( 'Features', 'advanced-woo-search' ) . '</a></li>';
                    echo '<li><a target="_blank" href="https://advanced-woo-search.com/guide/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin">' . __( 'Documentation', 'advanced-woo-search' ) . '</a></li>';
                    echo '<li><a target="_blank" href="https://advanced-woo-search.com/pricing/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin">' . __( 'Pricing', 'advanced-woo-search' ) . '</a></li>';
                echo '</ul>';
            echo '</div>';

            echo '<div class="buy-premium">';
                echo '<a target="_blank" href="https://advanced-woo-search.com/pricing/?utm_source=plugin&utm_medium=settings-tab&utm_campaign=aws-pro-plugin">';
                    echo '<span class="desc">' . __( 'Upgrade to the', 'advanced-woo-search' ) . '<b> ' . __( 'Premium plugin version', 'advanced-woo-search' ) . '</b><br>' . __( 'to have all available features!', 'advanced-woo-search' ) . '</span>';
                    echo '<ul>';
                        echo '<li>' . __( '30-day money back guarantee', 'advanced-woo-search' ) . '</li>';
                        echo '<li>' . __( 'Priority support', 'advanced-woo-search' ) . '</li>';
                        echo '<li>' . __( '1 year of support and updates', 'advanced-woo-search' ) . '</li>';
                    echo '</ul>';
                echo '</a>';
            echo '</div>';

            echo '<div class="features">';

                echo '<h3>' . __( 'Premium Features', 'advanced-woo-search' ) . '</h3>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'New Search Sources', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( 'Search additionally inside %sproduct attributes%s, %staxonomies%s and %scustom fields%s. Inside the plugin settings page it is possible to choose some specific sources that must be available for search ( for example only several product attributes ) or just search for all of them.', 'advanced-woo-search' ), '<b>', '</b>', '<b>', '</b>', '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/search-sources/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature1.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Product Variations Support', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "Search and show inside search results %svariable product%s, %sproduct variations%s or %sboth%s. With the variable products will be displayed all attributes that belong to that specific variation.", 'advanced-woo-search' ), '<b>', '</b>', '<b>', '</b>', '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/variable-products-search/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature2.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Archive Pages Search', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "Search for WooCommerce product %scustom taxonomies%s and %sattributes archive pages%s. Display them right inside the search results list along with the product search results.", 'advanced-woo-search' ), '<b>', '</b>', '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/terms-pages-search/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature3.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Users Search', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo esc_html__( "Search for website users and display them right inside the search results box. Choose what role the user must have to be available for search.", 'advanced-woo-search' );
                            echo '<br><a href="https://advanced-woo-search.com/features/users-search/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature4.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                 echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Search Form Filters', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "For each search form you can create a unique set of tabs. These tabs have their own %sset of settings%s and work as %sfilters%s for your search results.", 'advanced-woo-search' ), '<b>', '</b>', '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/filters-button/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature5.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Search Form Instances', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "Unlimited number of search form instances with totally different settings and products look. You can create totally different search forms for any of your needs. Each instance is %sindependent%s and has its own %sset of settings%s.", 'advanced-woo-search' ), '<b>', '</b>', '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/search-form-instances/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature6.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Search Logic Change', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "Switch from %sOR%s to %sAND%s search logic. Choose from %spartial%s or %sexact match%s search.", 'advanced-woo-search' ), '<b>', '</b>', '<b>', '</b>', '<b>', '</b>', '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/search-operators-and-rules/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature7.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Add to Cart Button', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "Display %sAdd to Cart%s button right inside the search results. This makes it possible to add the product to the cart without visiting the product page. This button works perfectly also and with product variations.", 'advanced-woo-search' ), '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/add-to-cart-button/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature8.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Search Results Layouts', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "Choose between several %spredefined search results layouts%s. For example you can display all products in search results in a grid or in column one by one.", 'advanced-woo-search' ), '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/search-results-layouts/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature9.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Exclude/Include Products Filters', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "Option to exclude or include products from search results by %sproduct ids%s, %staxonomies%s or %sattributes%s. Show only those products that you want. This option works great with the search form filters.", 'advanced-woo-search' ), '<b>', '</b>', '<b>', '</b>', '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/exclude-include-products/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature10.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Search Suggestions', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "Generate and show %sterms suggestions for current search query%s. Such suggestions will be visible at the top of the search results list.", 'advanced-woo-search' ), '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/search-suggestions/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature14.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'ACF Plugin Support', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "All fields that were created with the help of %sAdvanced Custom Fields%s plugin are available for search. Also use a special built-in filter to display any ACF field value right inside the search results list.", 'advanced-woo-search' ), '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/acf-plugin-support/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . '</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature11.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'WCFM / Dokan / WC Vendors Plugins Support', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo sprintf( esc_html__( "Build-in integration with %sWCFM Multivendor Marketplace%s, %sWC Vendors%s and %sDokan WooCommerce Multivendor Marketplace%s plugins. Show vendor information for each product, search for vendors, use plugin search form inside vendors stores, filter search results based on specific vendor shop parameters.", 'advanced-woo-search' ), '<b>', '</b>', '<b>', '</b>', '<b>', '</b>' );
                            echo '<br><a href="https://advanced-woo-search.com/features/wcfm-plugin-support/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . ' (WCFM)</a>';
                            echo '<br><a href="https://advanced-woo-search.com/features/dokan-plugin-support/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . ' (Dokan)</a>';
                            echo '<br><a href="https://advanced-woo-search.com/features/wc-vendors-plugin-support/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=aws-pro-plugin" target="_blank">' . __( 'Learn more', 'advanced-woo-search' ) . ' (WC Vendors)</a>';
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature13.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="features-item">';
                    echo '<div class="column">';
                        echo '<h4 class="title">';
                            echo __( 'Priority Support', 'advanced-woo-search' );
                        echo '</h4>';
                        echo '<p class="desc">';
                            echo esc_html__( "You will benefit from our full support for any issues you have with this plugin.", 'advanced-woo-search' );
                        echo '</p>';
                    echo '</div>';
                    echo '<div class="column">';
                        echo '<div class="img">';
                            echo '<img alt="" src="' . AWS_URL . 'assets/img/pro/feature12.png' . '" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

            echo '</div>';

            echo '<div class="faq">';

                echo '<h3>' . __( 'Frequently Asked Questions', 'advanced-woo-search' ) . '</h3>';

                echo '<div class="faq-item">';
                    echo '<h4 class="question">';
                        echo __( 'Do you offer refunds?', 'advanced-woo-search' );
                    echo '</h4>';
                    echo '<div class="answer">';
                        echo __( 'If you\'re not completely happy with your purchase and we\'re unable to resolve the issue, let us know and we\'ll refund the full purchase price. Refunds can be processed within 30 days of the original purchase.', 'advanced-woo-search' );
                    echo '</div>';
                echo '</div>';

                echo '<div class="faq-item">';
                    echo '<h4 class="question">';
                        echo __( 'What payment methods do you accept?', 'advanced-woo-search' );
                    echo '</h4>';
                    echo '<div class="answer">';
                        echo __( 'Checkout is powered FastSpring company. They supports major credit and debit cards, PayPal, and a variety of other mainstream payment methods, so there’s plenty to pick from.', 'advanced-woo-search' );
                    echo '</div>';
                echo '</div>';

                echo '<div class="faq-item">';
                    echo '<h4 class="question">';
                        echo __( 'Do you offer support if I need help?', 'advanced-woo-search' );
                    echo '</h4>';
                    echo '<div class="answer">';
                        echo __( 'Yes! You will benefit of our full support for any issues you have with this plugin.', 'advanced-woo-search' );
                    echo '</div>';
                echo '</div>';

                echo '<div class="faq-item">';
                    echo '<h4 class="question">';
                        echo __( 'I have other pre-sale questions, can you help?', 'advanced-woo-search' );
                    echo '</h4>';
                    echo '<div class="answer">';
                        echo __( 'Yes! You can ask us any question through our', 'advanced-woo-search' ) . ' <a href="https://advanced-woo-search.com/contact/?utm_source=plugin&utm_medium=premium-tab&utm_campaign=sti-pro-plugin" target="_blank">' . __( 'contact form.', 'advanced-woo-search' ) . '</a>';
                    echo '</div>';
                echo '</div>';

            echo '</div>';

            echo '<div class="buy-premium">';
                echo '<a target="_blank" href="https://advanced-woo-search.com/pricing/?utm_source=plugin&utm_medium=settings-tab&utm_campaign=aws-pro-plugin">';
                    echo '<span class="desc">' . __( 'Upgrade to the', 'advanced-woo-search' ) . '<b> ' . __( 'Premium plugin version', 'advanced-woo-search' ) . '</b><br>' . __( 'to have all available features!', 'advanced-woo-search' ) . '</span>';
                echo '</a>';
            echo '</div>';

        }
        
    }

endif;
