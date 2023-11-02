<?php
/*
Plugin Name: Product Schema - WooCommerce
Description: This plugin automatically generates a schema for all published products and adds it to the website header. If you wish to remove the schema, simply deactivate the plugin.
Version: 1.0
Author: Dharmishtha Raval
*/

// Check if WooCommerce is active
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    // WooCommerce is active, so generate product schema
    add_action('wp_head', 'my_product_schema');
    function my_product_schema()
    {
        global $product;
        if (!is_singular('product')) {
            return;
        }
        if (!is_a($product, 'WC_Product')) {
            $product = wc_get_product(get_the_ID());
        }
        if ($product->get_status() !== 'publish') {
            return;
        }
        $schema = array(
            '@context' => 'http://schema.org/',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => $product->get_description(),
            'sku' => $product->get_sku(),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'http://schema.org/InStock' : 'http://schema.org/OutOfStock',
            ),
            "aggregateRating" => array(
                "@type" => "AggregateRating",
                "ratingValue" => $product->get_review_count(),
                "reviewCount" => get_comments_number($product->get_id()),
            ),
        );
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
} else {

    // WooCommerce is not active, so display a message
    add_action('admin_notices', 'my_product_schema_admin_notice');
    function my_product_schema_admin_notice()
    {
        echo '<div class="error"><p>To create a product schema with the plugin, WooCommerce must be installed as without it, the plugin will not function.</p></div>';
    }
}
