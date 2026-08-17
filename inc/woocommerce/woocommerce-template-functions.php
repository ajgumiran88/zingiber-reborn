<?php

if (!function_exists('vonaco_before_content')) {
    /**
     * Before Content
     * Wraps all WooCommerce content in wrappers which match the theme markup
     *
     * @return  void
     * @since   1.0.0
     */
    function vonaco_before_content() {
        echo <<<HTML
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
HTML;

    }
}


if (!function_exists('vonaco_after_content')) {
    /**
     * After Content
     * Closes the wrapping divs
     *
     * @return  void
     * @since   1.0.0
     */
    function vonaco_after_content() {
        echo <<<HTML
	</main><!-- #main -->
</div><!-- #primary -->
HTML;

        do_action('vonaco_sidebar');
    }
}

if (!function_exists('vonaco_cart_link_fragment')) {
    /**
     * Cart Fragments
     * Ensure cart contents update when products are added to the cart via AJAX
     *
     * @param array $fragments Fragments to refresh via AJAX.
     *
     * @return array            Fragments to refresh via AJAX
     */
    function vonaco_cart_link_fragment($fragments) {
        ob_start();
        vonaco_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        ob_start();
        vonaco_handheld_footer_bar_cart_link();
        $fragments['a.footer-cart-contents'] = ob_get_clean();

        return $fragments;
    }
}

if (!function_exists('vonaco_cart_link')) {
    /**
     * Cart Link
     * Displayed a link to the cart including the number of items present and the cart total
     *
     * @return void
     * @since  1.0.0
     */
    function vonaco_cart_link() {
        $cart = WC()->cart;
        ?>
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'vonaco'); ?>">
            <?php if ($cart): ?>
                <span class="count"><?php echo wp_kses_data(sprintf(_n('%d', '%d', WC()->cart->get_cart_contents_count(), 'vonaco'), WC()->cart->get_cart_contents_count())); ?></span>
                <?php echo WC()->cart->get_cart_subtotal(); ?>
            <?php endif; ?>
        </a>
        <?php
    }
}

class Vonaco_Custom_Walker_Category extends Walker_Category {

    public function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters(
            'list_cats',
            esc_attr($category->name),
            $category
        );

        // Don't generate an element if the category name is empty.
        if (!$cat_name) {
            return;
        }

        $link = '<a class="pf-value" href="' . esc_url(get_term_link($category)) . '" data-val="' . esc_attr($category->slug) . '" data-title="' . esc_attr($category->name) . '" ';
        if ($args['use_desc_for_title'] && !empty($category->description)) {
            /**
             * Filters the category description for display.
             *
             * @param string $description Category description.
             * @param object $category Category object.
             *
             * @since 1.2.0
             *
             */
            $link .= 'title="' . esc_attr(strip_tags(apply_filters('category_description', $category->description, $category))) . '"';
        }

        $link .= '>';
        $link .= $cat_name . '</a>';

        if (!empty($args['feed_image']) || !empty($args['feed'])) {
            $link .= ' ';

            if (empty($args['feed_image'])) {
                $link .= '(';
            }

            $link .= '<a href="' . esc_url(get_term_feed_link($category->term_id, $category->taxonomy, $args['feed_type'])) . '"';

            if (empty($args['feed'])) {
                $alt = ' alt="' . sprintf(esc_html__('Feed for all posts filed under %s', 'vonaco'), $cat_name) . '"';
            } else {
                $alt  = ' alt="' . $args['feed'] . '"';
                $name = $args['feed'];
                $link .= empty($args['title']) ? '' : $args['title'];
            }

            $link .= '>';

            if (empty($args['feed_image'])) {
                $link .= $name;
            } else {
                $link .= "<img src='" . $args['feed_image'] . "'$alt" . ' />';
            }
            $link .= '</a>';

            if (empty($args['feed_image'])) {
                $link .= ')';
            }
        }

        if (!empty($args['show_count'])) {
            $link .= ' (' . number_format_i18n($category->count) . ')';
        }
        if ('list' == $args['style']) {
            $output      .= "\t<li";
            $css_classes = array(
                'cat-item',
                'cat-item-' . $category->term_id,
            );

            if (!empty($args['current_category'])) {
                // 'current_category' can be an array, so we use `get_terms()`.
                $_current_terms = get_terms(
                    $category->taxonomy,
                    array(
                        'include'    => $args['current_category'],
                        'hide_empty' => false,
                    )
                );

                foreach ($_current_terms as $_current_term) {
                    if ($category->term_id == $_current_term->term_id) {
                        $css_classes[] = 'current-cat pf-active';
                    } elseif ($category->term_id == $_current_term->parent) {
                        $css_classes[] = 'current-cat-parent';
                    }
                    while ($_current_term->parent) {
                        if ($category->term_id == $_current_term->parent) {
                            $css_classes[] = 'current-cat-ancestor';
                            break;
                        }
                        $_current_term = get_term($_current_term->parent, $category->taxonomy);
                    }
                }
            }

            /**
             * Filters the list of CSS classes to include with each category in the list.
             *
             * @param array $css_classes An array of CSS classes to be applied to each list item.
             * @param object $category Category data object.
             * @param int $depth Depth of page, used for padding.
             * @param array $args An array of wp_list_categories() arguments.
             *
             * @since 4.2.0
             *
             * @see wp_list_categories()
             *
             */
            $css_classes = implode(' ', apply_filters('category_css_class', $css_classes, $category, $depth, $args));

            $output .= ' class="' . $css_classes . '"';
            $output .= ">$link\n";
        } elseif (isset($args['separator'])) {
            $output .= "\t$link" . $args['separator'] . "\n";
        } else {
            $output .= "\t$link<br />\n";
        }
    }
}

if (!function_exists('vonaco_show_categories_dropdown')) {
    function vonaco_show_categories_dropdown() {
        static $id = 0;
        $args  = array(
            'hide_empty' => 1,
            'parent'     => 0
        );
        $terms = get_terms('product_cat', $args);
        if (!empty($terms) && !is_wp_error($terms)) {
            ?>
            <div class="search-by-category input-dropdown">
                <div class="input-dropdown-inner vonaco-scroll-content">
                    <!--                    <input type="hidden" name="product_cat" value="0">-->
                    <a href="#" data-val="0"><span><?php esc_html_e('All category', 'vonaco'); ?></span></a>
                    <?php
                    $args_dropdown = array(
                        'id'               => 'product_cat' . $id++,
                        'show_count'       => 0,
                        'class'            => 'dropdown_product_cat_ajax',
                        'show_option_none' => esc_html__('All category', 'vonaco'),
                    );
                    wc_product_dropdown_categories($args_dropdown);
                    ?>
                    <div class="list-wrapper vonaco-scroll">
                        <ul class="vonaco-scroll-content">
                            <li class="d-none">
                                <a href="#" data-val="0"><?php esc_html_e('All category', 'vonaco'); ?></a></li>
                            <?php
                            if (!apply_filters('vonaco_show_only_parent_categories_dropdown', false)) {
                                $args_list = array(
                                    'title_li'           => false,
                                    'taxonomy'           => 'product_cat',
                                    'use_desc_for_title' => false,
                                    'walker'             => new vonaco_Custom_Walker_Category(),
                                );
                                wp_list_categories($args_list);
                            } else {
                                foreach ($terms as $term) {
                                    ?>
                                    <li>
                                        <a href="#" data-val="<?php echo esc_attr($term->slug); ?>"><?php echo esc_attr($term->name); ?></a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('vonaco_product_search')) {
    /**
     * Display Product Search
     *
     * @return void
     * @uses  vonaco_is_woocommerce_activated() check if WooCommerce is activated
     * @since  1.0.0
     */
    function vonaco_product_search() {
        if (vonaco_is_woocommerce_activated()) {
            static $index = 0;
            $index++;
            ?>
            <div class="site-search ajax-search">
                <div class="widget woocommerce widget_product_search">
                    <div class="ajax-search-result d-none"></div>
                    <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
                        <label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>"><?php esc_html_e('Search for:', 'vonaco'); ?></label>
                        <input type="search" id="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__('Search products&hellip;', 'vonaco'); ?>" autocomplete="off" value="<?php echo get_search_query(); ?>" name="s"/>
                        <button type="submit" value="<?php echo esc_attr_x('Search', 'submit button', 'vonaco'); ?>"><?php echo esc_html_x('Search', 'submit button', 'vonaco'); ?></button>
                        <input type="hidden" name="post_type" value="product"/>
                        <?php vonaco_show_categories_dropdown(); ?>
                    </form>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('vonaco_header_cart')) {
    /**
     * Display Header Cart
     *
     * @return void
     * @uses  vonaco_is_woocommerce_activated() check if WooCommerce is activated
     * @since  1.0.0
     */
    function vonaco_header_cart() {
        if (vonaco_is_woocommerce_activated()) {
            if (!vonaco_get_theme_option('show_header_cart', true)) {
                return;
            }
            ?>
            <div class="site-header-cart menu">
                <?php vonaco_cart_link(); ?>
                <?php

                if (!apply_filters('woocommerce_widget_cart_is_hidden', is_cart() || is_checkout())) {

                    if (vonaco_get_theme_option('header_cart_dropdown', 'side') == 'side') {
                        add_action('wp_footer', 'vonaco_header_cart_side');
                    } else {
                        the_widget('WC_Widget_Cart', 'title=');
                    }
                }
                ?>
            </div>
            <?php
        }
    }
}

if (!function_exists('vonaco_header_cart_side')) {
    function vonaco_header_cart_side() {
        if (vonaco_is_woocommerce_activated()) {
            ?>
            <div class="site-header-cart-side">
                <div class="cart-side-heading">
                    <span class="cart-side-title"><?php echo esc_html__('Shopping cart', 'vonaco'); ?></span>
                    <a href="#" class="close-cart-side"><?php echo esc_html__('close', 'vonaco') ?></a></div>
                <?php the_widget('WC_Widget_Cart', 'title='); ?>
            </div>
            <div class="cart-side-overlay"></div>
            <?php
        }
    }
}

if (!function_exists('vonaco_upsell_display')) {
    /**
     * Upsells
     * Replace the default upsell function with our own which displays the correct number product columns
     *
     * @return  void
     * @since   1.0.0
     * @uses    woocommerce_upsell_display()
     */
    function vonaco_upsell_display() {
        $columns = apply_filters('vonaco_upsells_columns', 4);
        woocommerce_upsell_display(-1, $columns);
    }
}

if (!function_exists('vonaco_sorting_wrapper')) {
    /**
     * Sorting wrapper
     *
     * @return  void
     * @since   1.4.3
     */
    function vonaco_sorting_wrapper() {
        echo '<div class="vonaco-sorting">';
    }
}

if (!function_exists('vonaco_sorting_wrapper_close')) {
    /**
     * Sorting wrapper close
     *
     * @return  void
     * @since   1.4.3
     */
    function vonaco_sorting_wrapper_close() {
        echo '</div>';
    }
}

if (!function_exists('vonaco_product_columns_wrapper')) {
    /**
     * Product columns wrapper
     *
     * @return  void
     * @since   2.2.0
     */
    function vonaco_product_columns_wrapper() {
        $columns = vonaco_loop_columns();
        echo '<div class="columns-' . absint($columns) . '">';
    }
}

if (!function_exists('vonaco_loop_columns')) {
    /**
     * Default loop columns on product archives
     *
     * @return integer products per row
     * @since  1.0.0
     */
    function vonaco_loop_columns() {
        $columns = 3; // 3 products per row

        if (function_exists('wc_get_default_products_per_row')) {
            $columns = wc_get_default_products_per_row();
        }

        return apply_filters('vonaco_loop_columns', $columns);
    }
}

if (!function_exists('vonaco_product_columns_wrapper_close')) {
    /**
     * Product columns wrapper close
     *
     * @return  void
     * @since   2.2.0
     */
    function vonaco_product_columns_wrapper_close() {
        echo '</div>';
    }
}

if (!function_exists('vonaco_shop_messages')) {
    /**
     * ThemeBase shop messages
     *
     * @since   1.4.4
     * @uses    vonaco_do_shortcode
     */
    function vonaco_shop_messages() {
        if (!is_checkout()) {
            echo vonaco_do_shortcode('woocommerce_messages');
        }
    }
}

if (!function_exists('vonaco_woocommerce_pagination')) {
    /**
     * ThemeBase WooCommerce Pagination
     * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
     * but since ThemeBase adds pagination before that function is excuted we need a separate function to
     * determine whether or not to display the pagination.
     *
     * @since 1.4.4
     */
    function vonaco_woocommerce_pagination() {
        if (woocommerce_products_will_display()) {
            woocommerce_pagination();
        }
    }
}

if (!function_exists('vonaco_handheld_footer_bar')) {
    /**
     * Display a menu intended for use on handheld devices
     *
     * @since 2.0.0
     */
    function vonaco_handheld_footer_bar() {
        $links = array(
            'shop'       => array(
                'priority' => 5,
                'callback' => 'vonaco_handheld_footer_bar_shop_link',
            ),
            'my-account' => array(
                'priority' => 10,
                'callback' => 'vonaco_handheld_footer_bar_account_link',
            ),
            'search'     => array(
                'priority' => 20,
                'callback' => 'vonaco_handheld_footer_bar_search',
            ),
            'wishlist'   => array(
                'priority' => 30,
                'callback' => 'vonaco_handheld_footer_bar_wishlist',
            ),
        );

        if (wc_get_page_id('myaccount') === -1) {
            unset($links['my-account']);
        }

        if (!function_exists('yith_wcwl_count_all_products') && !function_exists('woosw_init')) {
            unset($links['wishlist']);
        }

        $links = apply_filters('vonaco_handheld_footer_bar_links', $links);
        ?>
        <div class="vonaco-handheld-footer-bar">
            <ul class="columns-<?php echo count($links); ?>">
                <?php foreach ($links as $key => $link) : ?>
                    <li class="<?php echo esc_attr($key); ?>">
                        <?php
                        if ($link['callback']) {
                            call_user_func($link['callback'], $key, $link);
                        }
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
}

if (!function_exists('vonaco_handheld_footer_bar_search')) {
    /**
     * The search callback function for the handheld footer bar
     *
     * @since 2.0.0
     */
    function vonaco_handheld_footer_bar_search() {
        echo '<a href=""><span class="title">' . esc_attr__('Search', 'vonaco') . '</span></a>';
        vonaco_product_search();
    }
}

if (!function_exists('vonaco_handheld_footer_bar_cart_link')) {
    /**
     * The cart callback function for the handheld footer bar
     *
     * @since 2.0.0
     */
    function vonaco_handheld_footer_bar_cart_link() {
        ?>
        <a class="footer-cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'vonaco'); ?>">
            <span class="count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span>
        </a>
        <?php
    }
}

if (!function_exists('vonaco_handheld_footer_bar_account_link')) {
    /**
     * The account callback function for the handheld footer bar
     *
     * @since 2.0.0
     */
    function vonaco_handheld_footer_bar_account_link() {
        echo '<a href="' . esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))) . '"><span class="title">' . esc_attr__('My Account', 'vonaco') . '</span></a>';
    }
}

if (!function_exists('vonaco_handheld_footer_bar_shop_link')) {
    /**
     * The shop callback function for the handheld footer bar
     *
     * @since 2.0.0
     */
    function vonaco_handheld_footer_bar_shop_link() {
        echo '<a href="' . esc_url(get_permalink(get_option('woocommerce_shop_page_id'))) . '"><span class="title">' . esc_attr__('Shop', 'vonaco') . '</span></a>';
    }
}

if (!function_exists('vonaco_handheld_footer_bar_wishlist')) {
    /**
     * The wishlist callback function for the handheld footer bar
     *
     * @since 2.0.0
     */
    function vonaco_handheld_footer_bar_wishlist() {
        if (function_exists('yith_wcwl_count_all_products')) {
            ?>
            <a class="footer-wishlist" href="<?php echo esc_url(get_permalink(get_option('yith_wcwl_wishlist_page_id'))); ?>">
                <span class="title"><?php echo esc_html__('Wishlist', 'vonaco'); ?></span>
                <span class="count"><?php echo esc_html(yith_wcwl_count_all_products()); ?></span>
            </a>
            <?php
        } elseif (function_exists('woosw_init')) {
            $key = WPCleverWoosw::get_key();
            ?>
            <a class="footer-wishlist" href="<?php echo esc_url(WPCleverWoosw::get_url($key, true)); ?>">
                <span class="title"><?php echo esc_html__('Wishlist', 'vonaco'); ?></span>
                <span class="count"><?php echo esc_html(count(WPCleverWoosw::get_count($key))); ?></span>
            </a>
            <?php
        }
    }
}

if (!function_exists('vonaco_single_product_pagination')) {
    /**
     * Single Product Pagination
     *
     * @since 2.3.0
     */
    function vonaco_single_product_pagination() {

        // Show only products in the same category?
        $in_same_term   = apply_filters('vonaco_single_product_pagination_same_category', true);
        $excluded_terms = apply_filters('vonaco_single_product_pagination_excluded_terms', '');
        $taxonomy       = apply_filters('vonaco_single_product_pagination_taxonomy', 'product_cat');

        $previous_product = vonaco_get_previous_product($in_same_term, $excluded_terms, $taxonomy);
        $next_product     = vonaco_get_next_product($in_same_term, $excluded_terms, $taxonomy);

        if ((!$previous_product && !$next_product) || !is_product()) {
            return;
        }

        ?>
        <div class="vonaco-product-pagination-wrap">
            <nav class="vonaco-product-pagination" aria-label="<?php esc_attr_e('More products', 'vonaco'); ?>">
                <?php if ($previous_product) : ?>
                    <a href="<?php echo esc_url($previous_product->get_permalink()); ?>" rel="prev">
                        <span class="pagination-prev"><i class="vonaco-icon-arrow-left"></i>Prev</span>
                        <div class="product-item">
                            <?php echo sprintf('%s', $previous_product->get_image()); ?>
                            <div class="vonaco-product-pagination-content">
                                <span class="vonaco-product-pagination__title"><?php echo sprintf('%s', $previous_product->get_name()); ?></span>
                                <?php if ($price_html = $previous_product->get_price_html()) :
                                    printf('<span class="price">%s</span>', $price_html);
                                endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if ($next_product) : ?>
                    <a href="<?php echo esc_url($next_product->get_permalink()); ?>" rel="next">
                        <span class="pagination-next">Next<i class="vonaco-icon-arrow-right"></i></span>
                        <div class="product-item">
                            <?php echo sprintf('%s', $next_product->get_image()); ?>
                            <div class="vonaco-product-pagination-content">
                                <span class="vonaco-product-pagination__title"><?php echo sprintf('%s', $next_product->get_name()); ?></span>
                                <?php if ($price_html = $next_product->get_price_html()) :
                                    printf('<span class="price">%s</span>', $price_html);
                                endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            </nav><!-- .vonaco-product-pagination -->
        </div>
        <?php

    }
}

if (!function_exists('vonaco_sticky_single_add_to_cart')) {
    /**
     * Sticky Add to Cart
     *
     * @since 2.3.0
     */
    function vonaco_sticky_single_add_to_cart() {
        global $product;

        if (!is_product()) {
            return;
        }

        $show = false;

        if ($product->is_purchasable() && $product->is_in_stock()) {
            $show = true;
        } else if ($product->is_type('external')) {
            $show = true;
        }

        if (!$show) {
            return;
        }

        $params = apply_filters(
            'vonaco_sticky_add_to_cart_params', array(
                'trigger_class' => 'entry-summary',
            )
        );

        wp_localize_script('vonaco-sticky-add-to-cart', 'vonaco_sticky_add_to_cart_params', $params);
        ?>

        <section class="vonaco-sticky-add-to-cart">
            <div class="col-full">
                <div class="vonaco-sticky-add-to-cart__content">
                    <?php echo woocommerce_get_product_thumbnail(); ?>
                    <div class="vonaco-sticky-add-to-cart__content-product-info">
						<span class="vonaco-sticky-add-to-cart__content-title"><?php esc_attr_e('You\'re viewing:', 'vonaco'); ?>
							<strong><?php the_title(); ?></strong></span>
                        <span class="vonaco-sticky-add-to-cart__content-price"><?php echo sprintf('%s', $product->get_price_html()); ?></span>
                        <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                    </div>
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="vonaco-sticky-add-to-cart__content-button button alt">
                        <?php echo esc_attr($product->add_to_cart_text()); ?>
                    </a>
                </div>
            </div>
        </section><!-- .vonaco-sticky-add-to-cart -->
        <?php
    }
}

if (!function_exists('vonaco_woocommerce_product_loop_start')) {
    function vonaco_woocommerce_product_loop_start() {
        echo '<div class="product-block">';
    }
}

if (!function_exists('vonaco_woocommerce_product_loop_end')) {
    function vonaco_woocommerce_product_loop_end() {
        echo '</div>';
    }
}

if (!function_exists('vonaco_woocommerce_product_caption_start')) {
    function vonaco_woocommerce_product_caption_start() {
        echo '<div class="product-caption">';
    }
}

if (!function_exists('vonaco_woocommerce_product_caption_end')) {
    function vonaco_woocommerce_product_caption_end() {
        echo '</div>';
    }
}

if (!function_exists('vonaco_woocommerce_product_loop_image')) {
    function vonaco_woocommerce_product_loop_image() {
        ?>
        <div class="product-transition"><?php do_action('vonaco_woocommerce_product_loop_image') ?></div>
        <?php
    }
}

if (!function_exists('vonaco_woocommerce_product_content_product_imagin')) {
    function vonaco_woocommerce_product_content_product_imagin() {
        ?>
        <div class="content-product-imagin"><?php do_action('vonaco_woocommerce_product_content_product_imagin') ?></div>
        <?php
    }
}

if (!function_exists('vonaco_woocommerce_product_loop_bottom')) {
    function vonaco_woocommerce_product_loop_bottom() {
        ?>
        <div class="product-caption-bottom">
            <?php do_action('vonaco_woocommerce_product_loop_bottom'); ?>
        </div>
        <?php
    }
}

if (!function_exists('vonaco_woocommerce_product_list_add_to_cart')) {
    function vonaco_woocommerce_product_list_add_to_cart() {
        ?>
        <?php woocommerce_template_loop_add_to_cart(); ?>
        <?php
    }
}

if (!function_exists('vonaco_add_quantity_field')) {
    function vonaco_add_quantity_field() {
        global $product;

        if (!$product->is_sold_individually() && 'variable' != $product->get_type() && $product->is_purchasable()) {
            woocommerce_quantity_input(array('min_value' => 1, 'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity()));
        }
    }
}

if (!function_exists('vonaco_woocommerce_product_loop_action')) {
    function vonaco_woocommerce_product_loop_action() {
        ?>
        <div class="group-action">
            <div class="shop-action">
                <?php do_action('vonaco_woocommerce_product_loop_action'); ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('vonaco_woocommerce_single_content_title')) {
    function vonaco_woocommerce_single_content_title() {
        ?>
        <div class="content-single-title-wrapper"><?php do_action('vonaco_woocommerce_single_content_title') ?></div>
        <?php
    }
}

if (!function_exists('vonaco_stock_label')) {
    function vonaco_stock_label() {
        global $product;
        if ($product->is_in_stock()) {
            echo '<span class="inventory_status">' . esc_html__('In Stock', 'vonaco') . '</span>';
        } else {
            echo '<span class="inventory_status out-stock">' . esc_html__('Out of Stock', 'vonaco') . '</span>';
        }
    }
}


if (!function_exists('vonaco_woocommerce_product_gallery_image')) {
    function vonaco_woocommerce_product_gallery_image() {
        /**
         * @var $product WC_Product
         */
        global $product;
        $gallery = $product->get_gallery_image_ids();
        if (count($gallery) > 0) {
            $size = apply_filters('woocommerce_product_loop_size', 'shop_catalog');
            echo '<div class="woocommerce-loop-product__gallery">';
            $url1    = wp_get_attachment_image_src($product->get_image_id(), $size);
            $srcset1 = wp_get_attachment_image_srcset($product->get_image_id(), $size);

            echo '<span class="gallery_item active" data-image="' . $url1[0] . '"  data-scrset="' . $srcset1 . '">' . $product->get_image('thumbnail') . '</span>';
            foreach ($gallery as $attachment_id) {
                $url    = wp_get_attachment_image_src($attachment_id, $size);
                $srcset = wp_get_attachment_image_srcset($attachment_id, $size);
                echo '<span class="gallery_item" data-image="' . $url[0] . '" data-scrset="' . $srcset . '">' . wp_get_attachment_image($attachment_id, 'thumbnail') . '</span>';
            }
            echo '</div>';
        }
    }
}

if (!function_exists('vonaco_template_loop_product_thumbnail')) {
    function vonaco_template_loop_product_thumbnail($size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0) {
        global $product;
        if (!$product) {
            return '';
        }
        $gallery    = $product->get_gallery_image_ids();
        $hover_skin = vonaco_get_theme_option('woocommerce_product_hover', 'none');
        if ($hover_skin == 'none' || count($gallery) <= 0) {
            echo '<div class="product-image">' . $product->get_image('shop_catalog') . '</div>';

            return '';
        }
        $image_featured = '<div class="product-image">' . $product->get_image('shop_catalog') . '</div>';
        $image_featured .= '<div class="product-image second-image">' . wp_get_attachment_image($gallery[0], 'shop_catalog') . '</div>';

        echo <<<HTML
<div class="product-img-wrap {$hover_skin}">
    <div class="inner">
        {$image_featured}
    </div>
</div>
HTML;
    }
}

if (!function_exists('vonaco_woocommerce_template_loop_rating')) {
    function vonaco_woocommerce_template_loop_rating() {
        global $product;
        $count = $product->get_review_count();
        echo '<div class="vonaco-count-review">' . wc_get_rating_html($product->get_average_rating()) . '<span>';
        printf(esc_html(_nx('%1$s Review', '%1$s Reviews', $count, 'Review Count', 'vonaco')),
            number_format_i18n($count)
        );
        echo '</span></div>';
    }
}

if (!function_exists('vonaco_template_loop_product_thumbnail_special')) {
    function vonaco_template_loop_product_thumbnail_special($size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0) {
        global $product;
        if ($product) {
            echo '<div class="product-image">' . $product->get_image('shop_catalog') . '</div>';
        }
    }
}

if (!function_exists('vonaco_woocommerce_single_product_image_thumbnail_html')) {
    function vonaco_woocommerce_single_product_image_thumbnail_html($image, $attachment_id) {
        return wc_get_gallery_image_html($attachment_id, true);
    }
}

if (!function_exists('woocommerce_template_loop_product_title')) {

    /**
     * Show the product title in the product loop.
     */
    function woocommerce_template_loop_product_title() {
        echo '<h3 class="woocommerce-loop-product__title"><a href="' . esc_url_raw(get_the_permalink()) . '">' . get_the_title() . '</a></h3>';
    }
}

if (!function_exists('vonaco_woocommerce_get_product_category')) {
    function vonaco_woocommerce_get_product_category() {
        global $product;
        echo wc_get_product_category_list($product->get_id(), ', ', '<div class="posted-in">', '</div>');
    }
}

if (!function_exists('vonaco_woocommerce_get_product_description')) {
    function vonaco_woocommerce_get_product_description() {
        global $post;

        $short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);

        if ($short_description) {
            ?>
            <div class="short-description">
                <?php echo sprintf('%s', $short_description); ?>
            </div>
            <?php
        }
    }
}

if (!function_exists('vonaco_woocommerce_get_product_short_description')) {
    function vonaco_woocommerce_get_product_short_description() {
        global $post;
        $short_description = wp_trim_words(apply_filters('woocommerce_short_description', $post->post_excerpt), 20);
        if ($short_description) {
            ?>
            <div class="short-description">
                <?php echo sprintf('%s', $short_description); ?>
            </div>
            <?php
        }
    }
}


if (!function_exists('vonaco_woocommerce_product_loop_wishlist_button')) {
    function vonaco_woocommerce_product_loop_wishlist_button() {
        if (vonaco_is_woocommerce_extension_activated('YITH_WCWL')) {
            echo vonaco_do_shortcode('yith_wcwl_add_to_wishlist');
        }
    }
}

if (!function_exists('vonaco_woocommerce_product_loop_compare_button')) {
    function vonaco_woocommerce_product_loop_compare_button() {
        if (vonaco_is_woocommerce_extension_activated('YITH_Woocompare')) {
            global $yith_woocompare;
            if (get_option('yith_woocompare_compare_button_in_products_list', 'no') == 'yes') {
                remove_action('woocommerce_after_shop_loop_item', array(
                    $yith_woocompare->obj,
                    'add_compare_link'
                ), 20);
            }

            echo vonaco_do_shortcode('yith_compare_button');
        }
    }
}

if (!function_exists('vonaco_header_wishlist')) {
    function vonaco_header_wishlist() {
        if (function_exists('yith_wcwl_count_all_products')) {
            if (!vonaco_get_theme_option('show_header_wishlist', true)) {
                return;
            }
            ?>
            <div class="site-header-wishlist">
                <a class="header-wishlist" href="<?php echo esc_url(get_permalink(get_option('yith_wcwl_wishlist_page_id'))); ?>">
                    <i class="vonaco-icon-heart"></i>
                    <span class="count"><?php echo esc_html(yith_wcwl_count_all_products()); ?></span>
                </a>
            </div>
            <?php
        } elseif (function_exists('woosw_init')) {
            if (!vonaco_get_theme_option('show_header_wishlist', true)) {
                return;
            }
            $key = WPCleverWoosw::get_key();

            ?>
            <div class="site-header-wishlist">
                <a class="header-wishlist" href="<?php echo esc_url(WPCleverWoosw::get_url($key, true)); ?>">
                    <i class="vonaco-icon-heart-1"></i>
                    <span class="count"><?php echo esc_html(WPCleverWoosw::get_count($key)); ?></span>
                </a>
            </div>
            <?php
        }
    }
}

if (!function_exists('woosw_ajax_update_count') && function_exists('woosw_init')) {
    function woosw_ajax_update_count() {
        $key = WPCleverWoosw::get_key();

        wp_send_json(array(
            'text' => esc_html(_nx('Item', 'Items', WPCleverWoosw::get_count($key), 'items wishlist', 'vonaco'))
        ));
    }

    add_action('wp_ajax_woosw_ajax_update_count', 'woosw_ajax_update_count');
    add_action('wp_ajax_nopriv_woosw_ajax_update_count', 'woosw_ajax_update_count');
}

if (defined('YITH_WCWL') && !function_exists('yith_wcwl_ajax_update_count')) {
    function yith_wcwl_ajax_update_count() {
        wp_send_json(array(
            'count' => yith_wcwl_count_all_products(),
            'text'  => esc_html(_nx('Item', 'Items', yith_wcwl_count_all_products(), 'items wishlist', 'vonaco'))
        ));
    }

    add_action('wp_ajax_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count');
    add_action('wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count');
}

if (!function_exists('vonaco_button_grid_list_layout')) {
    function vonaco_button_grid_list_layout() {
        ?>
        <div class="gridlist-toggle desktop-hide-down">
            <a href="<?php echo esc_url(add_query_arg('layout', 'list')); ?>" id="list" class="<?php echo isset($_GET['layout']) && $_GET['layout'] == 'list' ? 'active' : ''; ?>" title="<?php echo esc_html__('List View', 'vonaco'); ?>"><i class="vonaco-icon-list-ul"></i></a>
            <a href="<?php echo esc_url(add_query_arg('layout', 'grid')); ?>" id="grid" class="<?php echo isset($_GET['layout']) && $_GET['layout'] == 'list' ? '' : 'active'; ?>" title="<?php echo esc_html__('Grid View', 'vonaco'); ?>"><i class="vonaco-icon-th-large"></i></a>
        </div>
        <?php
    }
}

if (!function_exists('vonaco_woocommerce_change_path_shortcode')) {
    function vonaco_woocommerce_change_path_shortcode($template, $slug, $name) {
        wc_get_template('content-widget-product.php', apply_filters('vonaco_product_template_arg', array('show_rating' => false)));
    }
}

if (!function_exists('vonaco_woocommerce_list_show_rating_arg')) {
    function vonaco_woocommerce_list_show_rating_arg($arg) {
        $arg['show_rating'] = true;

        return $arg;
    }
}

if (!function_exists('vonaco_woocommerce_list_get_excerpt')) {
    function vonaco_woocommerce_list_show_excerpt() {
        echo '<div class="product-excerpt">' . get_the_excerpt() . '</div>';
    }
}

if (!function_exists('vonaco_woocommerce_list_get_rating')) {
    function vonaco_woocommerce_list_show_rating() {
        global $product;
        echo wc_get_rating_html($product->get_average_rating());
    }
}

if (!function_exists('vonaco_single_product_quantity_label')) {
    function vonaco_single_product_quantity_label() {
        echo '<label class="quantity_label">' . esc_html__('Quantity', 'vonaco') . ' </label>';
    }
}

if (!function_exists('vonaco_woocommerce_time_sale')) {
    function vonaco_woocommerce_time_sale() {
        /**
         * @var $product WC_Product
         */
        global $product;

        if (!$product->is_on_sale()) {
            return;
        }

        $time_sale = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
        if ($time_sale) {
            wp_enqueue_script('vonaco-countdown');
            $time_sale += (get_option('gmt_offset') * HOUR_IN_SECONDS);
            ?>
            <div class="time-sale">
                <div class="deal-text">
                    <span><?php echo esc_html__('Hurry up. Offer end in', 'vonaco'); ?></span>
                </div>
                <div class="vonaco-countdown" data-countdown="true" data-date="<?php echo esc_html($time_sale); ?>">
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-days"></span>
                        <span class="countdown-label"><?php echo esc_html__('Days', 'vonaco') ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-hours"></span>
                        <span class="countdown-label"><?php echo esc_html__('Hours', 'vonaco') ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-minutes"></span>
                        <span class="countdown-label"><?php echo esc_html__('Mins', 'vonaco') ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-seconds"></span>
                        <span class="countdown-label"><?php echo esc_html__('Secs', 'vonaco') ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('vonaco_woocommerce_deal_progress')) {
    function vonaco_woocommerce_deal_progress() {
        global $product;

        $limit = get_post_meta($product->get_id(), '_deal_quantity', true);
        $sold  = intval(get_post_meta($product->get_id(), '_deal_sales_counts', true));
        if (empty($limit)) {
            return;
        }

        ?>

        <div class="deal-sold">
            <div class="deal-progress">
                <div class="progress-bar">
                    <div class="progress-value" style="width: <?php echo trim($sold / $limit * 100) ?>%"></div>
                </div>
            </div>
            <div class="deal-sold-text">
                <span><?php echo esc_html__('Sold: ', 'vonaco'); ?></span><span><?php echo esc_html($sold); ?>/<?php echo esc_html($limit); ?></span>
            </div>
        </div>

        <?php
    }
}

if (!function_exists('vonaco_woocommerce_group_action')) {
    function vonaco_woocommerce_group_action() {
        ?>
        <div class="group-action">
            <div class="shop-action">
                <?php
                vonaco_quickview_button();
                ?>
                <div class="opal-add-to-cart-button">
                    <?php woocommerce_template_loop_add_to_cart(); ?>
                </div>
                <?php
                vonaco_wishlist_button();
                vonaco_compare_button();
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('vonaco_single_product_extra')) {
    function vonaco_single_product_extra() {
        global $product;
        $product_extra = vonaco_get_theme_option('single_product_content_meta', '');
        $product_extra = get_post_meta($product->get_id(), '_extra_info', true) !== '' ? get_post_meta($product->get_id(), '_extra_info', true) : $product_extra;
        if ($product_extra !== '') {
            echo '<div class="vonaco-single-product-extra">' . html_entity_decode($product_extra) . '</div>';
        }
    }
}

if (!function_exists('vonaco_single_product_gallery_wrap_start')) {
    function vonaco_single_product_gallery_wrap_start() {
        echo '<div class="sumary-gallery-wrap">';
    }
}
if (!function_exists('vonaco_single_product_gallery_wrap_end')) {
    function vonaco_single_product_gallery_wrap_end() {
        echo '</div>';
    }
}

if (!function_exists('vonaco_button_shop_canvas')) {
    function vonaco_button_shop_canvas() {
        if (is_active_sidebar('sidebar-woocommerce-shop')) { ?>
            <a href="#" class="filter-toggle" aria-expanded="false">
                <i class="vonaco-icon-sliders-v"></i><span><?php esc_html_e('Filter By', 'vonaco'); ?></span></a>
            <?php
        }
    }
}

if (!function_exists('vonaco_button_shop_dropdown')) {
    function vonaco_button_shop_dropdown() {
        if (is_active_sidebar('sidebar-woocommerce-shop')) { ?>
            <a href="#" class="filter-toggle-dropdown" aria-expanded="false">
                <i class="vonaco-icon-sliders-v"></i><span><?php esc_html_e('Filter By', 'vonaco'); ?></span></a>
            <?php
        }
    }
}

if (!function_exists('vonaco_render_woocommerce_shop_canvas')) {
    function vonaco_render_woocommerce_shop_canvas() {
        if (is_active_sidebar('sidebar-woocommerce-shop') && vonaco_is_product_archive()) {
            ?>
            <div id="vonaco-canvas-filter" class="vonaco-canvas-filter">
                <span class="filter-close"><?php esc_html_e('HIDE FILTER', 'vonaco'); ?></span>
                <div class="vonaco-canvas-filter-wrap">
                    <?php if (vonaco_get_theme_option('woocommerce_archive_layout') == 'canvas' || vonaco_get_theme_option('woocommerce_archive_layout') == 'fullwidth') {
                        dynamic_sidebar('sidebar-woocommerce-shop');
                    }
                    ?>
                </div>
            </div>
            <div class="vonaco-overlay-filter"></div>
            <?php
        }
    }
}
if (!function_exists('vonaco_render_woocommerce_shop_dropdown')) {
    function vonaco_render_woocommerce_shop_dropdown() {
        ?>
        <div id="vonaco-dropdown-filter" class="vonaco-dropdown-filter">
            <div class="vonaco-dropdown-filter-wrap">
                <?php dynamic_sidebar('sidebar-woocommerce-shop'); ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('woocommerce_checkout_order_review_start')) {

    function woocommerce_checkout_order_review_start() {
        echo '<div class="checkout-review-order-table-wrapper">';
    }
}

if (!function_exists('woocommerce_checkout_order_review_end')) {

    function woocommerce_checkout_order_review_end() {
        echo '</div>';
    }
}

if (!function_exists('vonaco_woocommerce_get_product_label_stock')) {
    function vonaco_woocommerce_get_product_label_stock() {
        /**
         * @var $product WC_Product
         */
        global $product;
        if ($product->get_stock_status() == 'outofstock') {
            echo '<span class="stock-label">' . esc_html__('Out Of Stock', 'vonaco') . '</span>';
        }
    }
}

if (!function_exists('vonaco_woocommerce_single_content_wrapper_start')) {
    function vonaco_woocommerce_single_content_wrapper_start() {
        echo '<div class="content-single-wrapper">';
    }
}

if (!function_exists('vonaco_woocommerce_single_content_wrapper_end')) {
    function vonaco_woocommerce_single_content_wrapper_end() {
        echo '</div>';
    }
}

if (!function_exists('vonaco_woocommerce_single_brand')) {
    function vonaco_woocommerce_single_brand() {
        $id = get_the_ID();

        $terms = get_the_terms($id, 'product_brand');

        if (is_wp_error($terms)) {
            return $terms;
        }

        if (empty($terms)) {
            return false;
        }

        $links = array();

        foreach ($terms as $term) {
            $link = get_term_link($term, 'product_brand');
            if (is_wp_error($link)) {
                return $link;
            }
            $links[] = '<a href="' . esc_url($link) . '" rel="tag">' . $term->name . '</a>';
        }
        echo '<div class="product-brand">' . esc_html__('Brands: ', 'vonaco') . join('', $links) . '</div>';
    }
}

if (!function_exists('vonaco_single_product_video_360')) {
    function vonaco_single_product_video_360() {
        global $product;
        echo '<div class="product-video-360">';
        $images = get_post_meta($product->get_id(), '_product_360_image_gallery', true);
        $video  = get_post_meta($product->get_id(), '_video_select', true);

        if ($video && wc_is_valid_url($video)) {
            echo '<a class="product-video-360__btn btn-video" href="' . $video . '"><i class="vonaco-icon-video"></i><span>' . esc_html__('Video', 'vonaco') . '</span></a>';
        }

        if ($images) {
            $array      = explode(',', $images);
            $images_url = [];
            foreach ($array as $id) {
                $url          = wp_get_attachment_image_src($id, 'full');
                $images_url[] = $url[0];
            }

            echo '<a class="product-video-360__btn btn-360" href="#view-360"><i class="vonaco-icon-360"></i><span>' . esc_html__('360 View', 'vonaco') . '</span></a>';
            ?>
            <div id="view-360" class="view-360 zoom-anim-dialog mfp-hide">
                <div id="rotateimages" class="opal-loading" data-images="<?php echo implode(',', $images_url); ?>"></div>
                <div class="view-360-group">
                    <span class='view-360-button view-360-prev'><i class="vonaco-icon-angle-left"></i></span>
                    <i class="vonaco-icon-360 view-360-svg"></i>
                    <span class='view-360-button view-360-next'><i class="vonaco-icon-angle-right"></i></span>
                </div>
            </div>
            <?php
        }

        echo '</div>';
    }
}

if (!function_exists('vonaco_output_product_data_accordion')) {
    function vonaco_output_product_data_accordion() {
        $product_tabs = apply_filters('woocommerce_product_tabs', array());
        if (!empty($product_tabs)) : ?>
            <div id="vonaco-accordion-container" class="woocommerce-tabs wc-tabs-wrapper product-accordions">
                <?php $_count = 0; ?>
                <?php foreach ($product_tabs as $key => $tab) : ?>
                    <div class="accordion-item">
                        <div class="accordion-head <?php echo esc_attr($key); ?>_tab js-btn-accordion"
                             id="tab-title-<?php echo esc_attr($key); ?>">
                            <div class="accordion-title"><?php echo apply_filters('woocommerce_product_' . $key . '_tab_title', esc_html($tab['title']), $key); ?></div>
                        </div>
                        <div class="accordion-body js-card-body">
                            <?php call_user_func($tab['callback'], $key, $tab); ?>
                        </div>
                    </div>
                    <?php $_count++; ?>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }
}

if (!function_exists('vonaco_quickview_button')) {
    function vonaco_quickview_button() {
        if (function_exists('woosq_init')) {
            echo do_shortcode('[woosq]');
        }
    }
}

if (!function_exists('vonaco_compare_button')) {
    function vonaco_compare_button() {
        if (function_exists('woosc_init')) {
            echo do_shortcode('[woosc]');
        }
    }
}

if (!function_exists('vonaco_wishlist_button')) {
    function vonaco_wishlist_button() {
        if (function_exists('woosw_init')) {
            echo do_shortcode('[woosw]');
        }
    }
}


if (!function_exists('vonaco_quick_shop')) {
    function vonaco_quick_shop($id = false) {
        if (isset($_GET['id'])) {
            $id = sanitize_text_field((int)$_GET['id']);
        }
        if (!$id || !vonaco_is_woocommerce_activated()) {
            return;
        }

        global $post;

        $args = array('post__in' => array($id), 'post_type' => 'product');

        $quick_posts = get_posts($args);

        foreach ($quick_posts as $post) :
            setup_postdata($post);
            woocommerce_template_single_add_to_cart();
        endforeach;

        wp_reset_postdata();

        die();
    }

    add_action('wp_ajax_vonaco_quick_shop', 'vonaco_quick_shop');
    add_action('wp_ajax_nopriv_vonaco_quick_shop', 'vonaco_quick_shop');

}

if (!function_exists('vonaco_quick_shop_wrapper')) {
    function vonaco_quick_shop_wrapper() {
        global $product;
        ?>
        <div class="quick-shop-wrapper">
            <div class="quick-shop-close cross-button"></div>
            <div class="quick-shop-form">
            </div>
        </div>
        <?php
    }
}

function vonaco_ajax_add_to_cart_handler() {
    WC_Form_Handler::add_to_cart_action();
    WC_AJAX::get_refreshed_fragments();
}

//add_action('wc_ajax_vonaco_add_to_cart', 'vonaco_ajax_add_to_cart_handler');
//add_action('wc_ajax_nopriv_vonaco_add_to_cart', 'vonaco_ajax_add_to_cart_handler');

// Remove WC Core add to cart handler to prevent double-add
//remove_action('wp_loaded', array('WC_Form_Handler', 'add_to_cart_action'), 20);

function vonaco_ajax_add_to_cart_add_fragments($fragments) {
    $all_notices  = WC()->session->get('wc_notices', array());
    $notice_types = apply_filters('woocommerce_notice_types', array('error', 'success', 'notice'));

    ob_start();
    foreach ($notice_types as $notice_type) {
        if (wc_notice_count($notice_type) > 0) {
            wc_get_template("notices/{$notice_type}.php", array(
                'notices' => array_filter($all_notices[$notice_type]),
            ));
        }
    }
    $fragments['notices_html'] = ob_get_clean();

    wc_clear_notices();

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'vonaco_ajax_add_to_cart_add_fragments');


if (!function_exists('vonaco_ajax_search_result')) {
    function vonaco_ajax_search_result() {
        ?>
        <div class="ajax-search-result d-none">
        </div>
        <?php
    }
}
add_action('pre_get_product_search_form', 'vonaco_ajax_search_result');

if (!function_exists('vonaco_ajax_live_search_template')) {
    function vonaco_ajax_live_search_template() {
        echo <<<HTML
        <script type="text/html" id="tmpl-ajax-live-search-template">
        <div class="product-item-search">
            <# if(data.url){ #>
            <a class="product-link" href="{{{data.url}}}" title="{{{data.title}}}">
            <# } #>
                <# if(data.img){#>
                <img src="{{{data.img}}}" alt="{{{data.title}}}">
                 <# } #>
                <div class="product-content">
                <h3 class="product-title">{{{data.title}}}</h3>
                <# if(data.price){ #>
                {{{data.price}}}
                 <# } #>
                </div>
                <# if(data.url){ #>
            </a>
            <# } #>
        </div>
        </script>
HTML;
    }
}
add_action('wp_footer', 'vonaco_ajax_live_search_template');

if (!function_exists('vonaco_single_product_review_template')) {
    function vonaco_single_product_review_template() {
        global $product;

        if (comments_open()) {
            ?>
            <div class="single-product-reviews-wrap">
                <?php
                echo '<h3 class="review-title">' . esc_html__('Reviews', 'vonaco') . '<sup class="count">' . $product->get_review_count() . '</sup></h3>';
                comments_template();
                ?>
            </div>
            <?php
        }
    }
}

if (!function_exists('vonaco_single_product_tabs_template')) {
    function vonaco_single_product_tabs_template() {
        $product_tabs = apply_filters('woocommerce_product_tabs', array());
        if (!empty($product_tabs)) : ?>
            <div class="vonaco-woocommerce-tabs">
                <?php foreach ($product_tabs as $key => $product_tab) : ?>
                    <div class="umimi-woocommerce-tabs-panel">
                        <?php
                        if (isset($product_tab['callback'])) {
                            call_user_func($product_tab['callback'], $key, $product_tab);
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }
}
