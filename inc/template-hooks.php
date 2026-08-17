<?php
/**
 * =================================================
 * Hook vonaco_page
 * =================================================
 */
add_action('vonaco_page', 'vonaco_page_header', 10);
add_action('vonaco_page', 'vonaco_page_content', 20);

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
add_action('vonaco_single_post', 'vonaco_post_header', 10);
add_action('vonaco_single_post', 'vonaco_post_content', 30);

/**
 * =================================================
 * Hook vonaco_single_post_bottom
 * =================================================
 */
add_action('vonaco_single_post_bottom', 'vonaco_post_taxonomy', 5);
add_action('vonaco_single_post_bottom', 'vonaco_post_nav', 10);
add_action('vonaco_single_post_bottom', 'vonaco_display_comments', 20);

/**
 * =================================================
 * Hook vonaco_loop_post
 * =================================================
 */
add_action('vonaco_loop_post', 'vonaco_post_header', 15);
add_action('vonaco_loop_post', 'vonaco_post_content', 30);

/**
 * =================================================
 * Hook vonaco_footer
 * =================================================
 */
add_action('vonaco_footer', 'vonaco_footer_default', 20);

/**
 * =================================================
 * Hook vonaco_after_footer
 * =================================================
 */

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */
add_action('wp_footer', 'vonaco_template_account_dropdown', 1);
add_action('wp_footer', 'vonaco_mobile_nav', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */
add_action('wp_head', 'vonaco_pingback_header', 1);

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
add_action('vonaco_sidebar', 'vonaco_get_sidebar', 10);

/**
 * =================================================
 * Hook vonaco_loop_after
 * =================================================
 */
add_action('vonaco_loop_after', 'vonaco_paging_nav', 10);

/**
 * =================================================
 * Hook vonaco_page_after
 * =================================================
 */
add_action('vonaco_page_after', 'vonaco_display_comments', 10);

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

/**
 * =================================================
 * Hook vonaco_woocommerce_shop_loop_item_title
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_woocommerce_after_shop_loop_item_title
 * =================================================
 */

/**
 * =================================================
 * Hook vonaco_woocommerce_after_shop_loop_item
 * =================================================
 */
