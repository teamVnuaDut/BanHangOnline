<?php

if (!function_exists('envo_extra_product_categories')) {

    function envo_extra_product_categories() {

        if (get_theme_mod('woo_archive_product_categories', 1) == 1) {
            global $product;

            $name = '';
            $id = $product->get_id();
            $cat_ids = $product->get_category_ids();

            // if product has categories, concatenate cart item name with them
            if ($cat_ids) {
                $name = wc_get_product_category_list($id, ', ', '<div class="archive-product-categories">', '</div>');
            }

            echo wp_kses_post($name);
        }
    }

    add_action('woocommerce_after_shop_loop_item_title', 'envo_extra_product_categories', 1);
}
