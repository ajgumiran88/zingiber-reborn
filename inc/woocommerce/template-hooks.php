<?php
/**
 * =================================================
 * Hook vonaco_page
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_single_post_top
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_single_post
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_single_post_bottom
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_loop_post
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_footer
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_after_footer
 * =================================================
 */
add_action('vonaco_after_footer', 'vonaco_sticky_single_add_to_cart', 999);

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */
add_action('wp_footer', 'vonaco_render_woocommerce_shop_canvas', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_before_header
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_before_content
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_content_top
 * =================================================
 */
add_action('vonaco_content_top', 'vonaco_shop_messages', 10);

/**
 * =================================================
 * Hook vonaco_post_content_before
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_post_content_after
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_sidebar
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_loop_after
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_page_after
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_woocommerce_before_shop_loop_item
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_woocommerce_before_shop_loop_item_title
 * =================================================
 */
add_action('vonaco_woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
add_action('vonaco_woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 15);

/**
 * =================================================
 * Hook vonaco_woocommerce_shop_loop_item_title
 * =================================================
 */
add_action('vonaco_woocommerce_shop_loop_item_title', 'vonaco_woocommerce_get_product_category', 5);
add_action('vonaco_woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 5);

/**
 * =================================================
 * Hook vonaco_woocommerce_after_shop_loop_item_title
 * =================================================
 */
add_action('vonaco_woocommerce_after_shop_loop_item_title', 'vonaco_woocommerce_get_product_description', 15);
add_action('vonaco_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 20);
add_action('vonaco_woocommerce_after_shop_loop_item_title', 'vonaco_woocommerce_group_action', 25);

/**
 * =================================================
 * Hook vonaco_woocommerce_after_shop_loop_item
 * =================================================
 */
