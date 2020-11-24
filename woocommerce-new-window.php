<?php

/**
 * Plugin Name:       WooCommerce External Product in new window
 * Plugin URI:        Makes it possible to open affiliate products in a new tab for WooCommerce
 * Description:       Handle the basics with this plugin.
 * Version:           1.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            otavusse
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    add_filter('product_type_options', function($product_type_options) {
        $product_type_options['newtab'] = array(
            'id' => '_newtab',
            'wrapper_class' => 'show_if_external',
            'label' => __('Open in new window', 'woocommerce'),
            'description' => __('WooCommerce External Product in new window.', 'newtab'),
            'default' => 'no',
        );
        return $product_type_options;
    });

    add_action('woocommerce_process_product_meta_external', 'save_newtab_option_fields');

    function save_newtab_option_fields($post_id) {
        $is_newtab = isset($_POST ['_newtab']) ? 'yes' : 'no';
        update_post_meta($post_id, '_newtab', $is_newtab);
    }

    add_action('wp_footer', function () {

        global $product;
        $key_newtab = get_post_meta(get_the_ID(), '_newtab', true);

        if ($key_newtab === 'yes' && $product && 'external' === $product->get_type()) {
            ?>
            <script>
                let q = document.querySelector(".product-type-external form.cart");
                q.setAttribute("target", "_blank");
            </script>
            <?php

        }
    }, 999);
}