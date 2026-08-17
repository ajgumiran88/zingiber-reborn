<?php

if (!function_exists('vonaco_display_comments')) {
    /**
     * vonaco display comments
     *
     * @since  1.0.0
     */
    function vonaco_display_comments() {
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || 0 !== intval(get_comments_number())) :
            comments_template();
        endif;
    }
}

if (!function_exists('vonaco_comment')) {
    /**
     * vonaco comment template
     *
     * @param array $comment the comment array.
     * @param array $args the comment args.
     * @param int $depth the comment depth.
     *
     * @since 1.0.0
     */
    function vonaco_comment($comment, $args, $depth) {
        if ('div' === $args['style']) {
            $tag       = 'div';
            $add_below = 'comment';
        } else {
            $tag       = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo esc_attr($tag) . ' '; ?><?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
        <div class="comment-body">
        <div class="comment-meta commentmetadata">
            <div class="comment-author vcard">
                <?php echo get_avatar($comment, 60); ?>
                <?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
            </div>
            <a href="<?php echo esc_url(htmlspecialchars(get_comment_link($comment->comment_ID))); ?>"
               class="comment-date">
                <?php echo '<time datetime="' . get_comment_date('c') . '">' . get_comment_date() . '</time>'; ?>
            </a>
            <?php if ('0' === $comment->comment_approved) : ?>
                <em class="comment-awaiting-moderation"><?php esc_attr_e('Your comment is awaiting moderation.', 'vonaco'); ?></em>
                <br/>
            <?php endif; ?>

        </div>
        <?php if ('div' !== $args['style']) : ?>
        <div id="div-comment-<?php comment_ID(); ?>" class="comment-content">
    <?php endif; ?>
        <div class="comment-text">
            <?php comment_text(); ?>
        </div>
        <div class="reply">
            <?php
            comment_reply_link(
                array_merge(
                    $args, array(
                        'add_below' => $add_below,
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                    )
                )
            );
            ?>
            <?php edit_comment_link(esc_html__('Edit', 'vonaco'), '  ', ''); ?>
        </div>
        </div>
        <?php if ('div' !== $args['style']) : ?>
            </div>
        <?php endif; ?>
        <?php
    }
}

if (!function_exists('vonaco_comment_form_defaults')) {
    /**
     * Filters the comment form default arguments.
     *
     * @return void
     * @since  1.0.0
     */
    function vonaco_comment_form_defaults($defaults) {
        $defaults['submit_button'] = '<button type="submit" id="%2$s" class="%3$s">%4$s <i class="vonaco-icon-arrow-circle"></i></button>';
        return $defaults;
    }

    add_filter('comment_form_defaults', 'vonaco_comment_form_defaults');
}

if (!function_exists('vonaco_credit')) {
    /**
     * Display the theme credit
     *
     * @return void
     * @since  1.0.0
     */
    function vonaco_credit() {
        ?>
        <div class="site-info">
            <?php echo apply_filters('vonaco_copyright_text', $content = '&copy; ' . date('Y') . ' ' . '<a class="site-url" href="' . esc_url(site_url()) . '">' . esc_html(get_bloginfo('name')) . '</a>' . esc_html__('. All Rights Reserved.', 'vonaco')); ?>
        </div><!-- .site-info -->
        <?php
    }
}

if (!function_exists('vonaco_social')) {
    function vonaco_social() {
        $social_list = vonaco_get_theme_option('social_text', []);
        if (empty($social_list)) {
            return;
        }
        ?>
        <div class="vonaco-social">
            <ul>
                <?php

                foreach ($social_list as $social_item) {
                    ?>
                    <li><a href="<?php echo esc_url($social_item); ?>"></a></li>
                    <?php
                }
                ?>

            </ul>
        </div>
        <?php
    }
}

if (!function_exists('vonaco_site_branding')) {
    /**
     * Site branding wrapper and display
     *
     * @return void
     * @since  1.0.0
     */
    function vonaco_site_branding() {
        ?>
        <div class="site-branding">
            <?php echo vonaco_site_title_or_logo(); ?>
        </div>
        <?php
    }
}

if (!function_exists('vonaco_site_title_or_logo')) {
    /**
     * Display the site title or logo
     *
     * @param bool $echo Echo the string or return it.
     *
     * @return string
     * @since 2.1.0
     */
    function vonaco_site_title_or_logo() {
        ob_start();
        the_custom_logo(); ?>
        <div class="site-branding-text">
            <?php if (is_front_page()) : ?>
                <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                          rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php else : ?>
                <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                         rel="home"><?php bloginfo('name'); ?></a></p>
            <?php endif; ?>

            <?php
            $description = get_bloginfo('description', 'display');

            if ($description || is_customize_preview()) :
                ?>
                <p class="site-description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div><!-- .site-branding-text -->
        <?php
        $html = ob_get_clean();
        return $html;
    }
}

if (!function_exists('vonaco_primary_navigation')) {
    /**
     * Display Primary Navigation
     *
     * @return void
     * @since  1.0.0
     */
    function vonaco_primary_navigation() {
        ?>
        <nav class="main-navigation" role="navigation"
             aria-label="<?php esc_html_e('Primary Navigation', 'vonaco'); ?>">
            <?php
            $args = apply_filters('vonaco_nav_menu_args', [
                'fallback_cb'     => '__return_empty_string',
                'theme_location'  => 'primary',
                'container_class' => 'primary-navigation',
            ]);
            wp_nav_menu($args);
            ?>
        </nav>
        <?php
    }
}

if (!function_exists('vonaco_mobile_navigation')) {
    /**
     * Display Handheld Navigation
     *
     * @return void
     * @since  1.0.0
     */
    function vonaco_mobile_navigation() {
        ?>
        <div class="mobile-nav-tabs">
            <ul>
                <?php if (isset(get_nav_menu_locations()['handheld'])) { ?>
                    <li class="mobile-tab-title mobile-pages-title active" data-menu="pages">
                        <span><?php echo esc_html(get_term(get_nav_menu_locations()['handheld'], 'nav_menu')->name); ?></span>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <nav class="mobile-menu-tab mobile-navigation mobile-pages-menu active"
             aria-label="<?php esc_html_e('Mobile Navigation', 'vonaco'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location'  => 'handheld',
                    'container_class' => 'handheld-navigation',
                )
            );
            ?>
        </nav>
        <?php
    }
}

if (!function_exists('vonaco_homepage_header')) {
    /**
     * Display the page header without the featured image
     *
     * @since 1.0.0
     */
    function vonaco_homepage_header() {
        edit_post_link(esc_html__('Edit this section', 'vonaco'), '', '', '', 'button vonaco-hero__button-edit');
        ?>
        <header class="entry-header">
            <?php
            the_title('<h1 class="entry-title">', '</h1>');
            ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('vonaco_page_header')) {
    /**
     * Display the page header
     *
     * @since 1.0.0
     */
    function vonaco_page_header() {

        if (is_front_page() || !is_page_template('default')) {
            return;
        }

        if (vonaco_is_elementor_activated() && function_exists('hfe_init')) {
            if (vonaco_breadcrumb::get_template_id() !== '') {
                return;
            }
        }

        ?>
        <header class="entry-header">
            <?php
            if (has_post_thumbnail()) {
                vonaco_post_thumbnail('full');
            }
            the_title('<h1 class="entry-title">', '</h1>');
            ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('vonaco_page_content')) {
    /**
     * Display the post content
     *
     * @since 1.0.0
     */
    function vonaco_page_content() {
        ?>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php
            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'vonaco'),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->
        <?php
    }
}

if (!function_exists('vonaco_post_header')) {
    /**
     * Display the post header with a link to the single post
     *
     * @since 1.0.0
     */
    function vonaco_post_header() {
        ?>
        <header class="entry-header">
            <?php
            if (is_single()) {
                $categories_list = get_the_category_list(' ');
                if ('post' === get_post_type() && $categories_list) {
                    // Make sure there's more than one category before displaying.
                    echo '<div class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'vonaco') . '</span>' . $categories_list . '</div>';
                }
                the_title('<h1 class="alpha entry-title">', '</h1>');
                ?>
                <div class="entry-meta">
                    <?php vonaco_post_meta(array('show_author' => 1, 'show_cats' => 0)); ?>
                </div>
                <?php
            } else {
                ?>
                <div class="entry-meta">
                    <?php
                    vonaco_post_meta();
                    ?>
                </div>
                <?php
                the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
            }
            ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('vonaco_post_content')) {
    /**
     * Display the post content with a link to the single post
     *
     * @since 1.0.0
     */
    function vonaco_post_content() {
        ?>
        <div class="entry-content">
            <?php

            /**
             * Functions hooked in to vonaco_post_content_before action.
             *
             */
            do_action('vonaco_post_content_before');


            if (is_single()) {
                the_content(
                    sprintf(
                    /* translators: %s: post title */
                        esc_html__('Read More', 'vonaco') . ' %s',
                        '<span class="screen-reader-text">' . get_the_title() . '</span>'
                    )
                );
            } else {
                the_excerpt();
                echo '<div class="more-link-wrap"><a class="more-link button" href="' . get_permalink() . '">' . esc_html__('Read More', 'vonaco') . '<i class="vonaco-icon-arrow-circle"></i></a></div>';
            }

            /**
             * Functions hooked in to vonaco_post_content_after action.
             *
             */
            do_action('vonaco_post_content_after');

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'vonaco'),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->
        <?php
    }
}

if (!function_exists('vonaco_post_meta')) {
    /**
     * Display the post meta
     *
     * @since 1.0.0
     */
    function vonaco_post_meta($atts = array()) {
        global $post;
        if ('post' !== get_post_type()) {
            return;
        }
        extract(
            shortcode_atts(
                array(
                    'show_author' => 1,
                    'show_date'   => 1,
                ),
                $atts
            )
        );

        $posted_on = '';
        if ($show_date == 1) {
            // Posted on.
            $posted_on = '<span class="posted-on">' . sprintf('<a href="%1$s" rel="bookmark">%2$s</a>', esc_url(get_permalink()), get_the_date()) . '</span>';
        }
        $author = '';
        // Author.
        if ($show_author == 1) {
            $author_id = $post->post_author;
            $author    = sprintf(
                '<span class="post-author"><span>%1$s<a href="%2$s" class="url fn" rel="author">%3$s</a></span></span>',
                esc_html__('By ', 'vonaco'),
                esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                esc_html(get_the_author_meta('display_name', $author_id))
            );
        }

        echo wp_kses(
            sprintf('%1$s %2$s', $posted_on, $author), array(
                'div'  => array(
                    'class' => array(),
                ),
                'span' => array(
                    'class' => array(),
                ),
                'a'    => array(
                    'href'  => array(),
                    'rel'   => array(),
                    'class' => array(),
                ),
                'time' => array(
                    'datetime' => array(),
                    'class'    => array(),
                ),
            )
        );

    }
}

if (!function_exists('vonaco_meta_footer')) {
    function vonaco_meta_footer() {

        echo '<div class="entry-meta bottom">';

        if ('post' !== get_post_type()) {
            return;
        }

        $categories      = '';
        $categories_list = get_the_category_list(',');
        if ('post' === get_post_type() && $categories_list) {
            // Make sure there's more than one category before displaying.
            $categories = '<span class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'vonaco') . '</span>' . $categories_list . '</span>';
        }

        // Author.
        $author = sprintf(
            '<span class="post-author"><span>%1$s<a href="%2$s" class="url fn" rel="author">%3$s</a></span></span>',
            esc_html__('By ', 'vonaco'),
            esc_url(get_author_posts_url(get_the_author_meta('ID'))),
            esc_html(get_the_author())
        );

        echo wp_kses(
            sprintf('%1$s %2$s', $author, $categories), array(
                'div'  => array(
                    'class' => array(),
                ),
                'span' => array(
                    'class' => array(),
                ),
                'a'    => array(
                    'href'  => array(),
                    'rel'   => array(),
                    'class' => array(),
                ),
                'time' => array(
                    'datetime' => array(),
                    'class'    => array(),
                )
            )
        );

        echo '</div>';
    }
}

if (!function_exists('vonaco_get_allowed_html')) {
    function vonaco_get_allowed_html() {
        return apply_filters(
            'vonaco_allowed_html',
            array(
                'br'     => array(),
                'i'      => array(),
                'b'      => array(),
                'u'      => array(),
                'em'     => array(),
                'del'    => array(),
                'a'      => array(
                    'href'  => true,
                    'class' => true,
                    'title' => true,
                    'rel'   => true,
                ),
                'strong' => array(),
                'span'   => array(
                    'style' => true,
                    'class' => true,
                ),
            )
        );
    }
}

if (!function_exists('vonaco_edit_post_link')) {
    /**
     * Display the edit link
     *
     * @since 2.5.0
     */
    function vonaco_edit_post_link() {
        edit_post_link(
            sprintf(
                wp_kses(__('Edit <span class="screen-reader-text">%s</span>', 'vonaco'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<div class="edit-link">',
            '</div>'
        );
    }
}

if (!function_exists('vonaco_categories_link')) {
    /**
     * Prints HTML with meta information for the current cateogries
     */
    function vonaco_categories_link() {

        // Get Categories for posts.
        $categories_list = get_the_category_list(',');

        if ('post' === get_post_type() && $categories_list) {
            // Make sure there's more than one category before displaying.
            echo '<span class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'vonaco') . '</span>' . $categories_list . '</span>';
        }
    }
}

if (!function_exists('vonaco_post_taxonomy')) {
    /**
     * Display the post taxonomies
     *
     * @since 2.4.0
     */
    function vonaco_post_taxonomy() {
        /* translators: used between list items, there is a space after the comma */

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list('', ', ');
        ?>
        <aside class="entry-taxonomy">
            <?php if ($tags_list) : ?>
                <div class="tags-links">
                    <span class="label"><?php echo esc_html(_n('Tag:', 'Tags:', count(get_the_tags()), 'vonaco')); ?></span>
                    <?php printf('%s', $tags_list); ?>
                </div>
            <?php endif;
            if (vonaco_is_elementor_activated()) {
                vonaco_social_share();
            }
            ?>
        </aside>
        <?php
    }
}

if (!function_exists('vonaco_paging_nav')) {
    /**
     * Display navigation to next/previous set of posts when applicable.
     */
    function vonaco_paging_nav() {

        $args = array(
            'type'      => 'list',
            'next_text' => '<span>' . esc_html__('Next', 'vonaco') . '</span>',
            'prev_text' => '<span>' . esc_html__('Prev', 'vonaco') . '</span>',
        );

        the_posts_pagination($args);
    }
}

if (!function_exists('vonaco_post_nav')) {
    /**
     * Display navigation to next/previous post when applicable.
     */
    function vonaco_post_nav() {

        $prev_post = get_previous_post();
        $next_post = get_next_post();
        $args      = [];
        if ($next_post) {
            $args['next_text'] = '<span class="nav-content"><span class="reader-text">' . esc_html__('Next post', 'vonaco') . ' </span><span class="title">%title</span></span>';
        }
        if ($prev_post) {
            $args['prev_text'] = '<span class="nav-content"><span class="reader-text">' . esc_html__('Previous post', 'vonaco') . ' </span><span class="title">%title</span></span> ';
        }

        the_post_navigation($args);

    }
}

if (!function_exists('vonaco_posted_on')) {
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     *
     * @deprecated 2.4.0
     */
    function vonaco_posted_on() {
        _deprecated_function('vonaco_posted_on', '2.4.0');
    }
}

if (!function_exists('vonaco_homepage_content')) {
    /**
     * Display homepage content
     * Hooked into the `homepage` action in the homepage template
     *
     * @return  void
     * @since  1.0.0
     */
    function vonaco_homepage_content() {
        while (have_posts()) {
            the_post();

            get_template_part('content', 'homepage');

        } // end of the loop.
    }
}

if (!function_exists('vonaco_get_sidebar')) {
    /**
     * Display vonaco sidebar
     *
     * @uses get_sidebar()
     * @since 1.0.0
     */
    function vonaco_get_sidebar() {
        get_sidebar();
    }
}

if (!function_exists('vonaco_post_thumbnail')) {
    /**
     * Display post thumbnail
     *
     * @param string $size the post thumbnail size.
     *
     * @uses has_post_thumbnail()
     * @uses the_post_thumbnail
     * @var $size . thumbnail|medium|large|full|$custom
     * @since 1.5.0
     */
    function vonaco_post_thumbnail($size = 'post-thumbnail') {
        if (has_post_thumbnail()) {
            echo '<div class="post-thumbnail">';
            if ('post' == get_post_type()) {
                $categories_list = get_the_category_list(' ');
                if ('post' === get_post_type() && $categories_list) {
                    // Make sure there's more than one category before displaying.
                    echo '<div class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'vonaco') . '</span>' . $categories_list . '</div>';
                }
            }
            the_post_thumbnail($size ? $size : 'post-thumbnail');
            echo '</div>';
        }else {
            if ('post' == get_post_type()) {
                $categories_list = get_the_category_list(' ');
                if ('post' === get_post_type() && $categories_list) {
                    // Make sure there's more than one category before displaying.
                    echo '<div class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'vonaco') . '</span>' . $categories_list . '</div>';
                }
            }
        }
    }
}

if (!function_exists('vonaco_primary_navigation_wrapper')) {
    /**
     * The primary navigation wrapper
     */
    function vonaco_primary_navigation_wrapper() {
        echo '<div class="vonaco-primary-navigation"><div class="col-full">';
    }
}

if (!function_exists('vonaco_primary_navigation_wrapper_close')) {
    /**
     * The primary navigation wrapper close
     */
    function vonaco_primary_navigation_wrapper_close() {
        echo '</div></div>';
    }
}

if (!function_exists('vonaco_header_container')) {
    /**
     * The header container
     */
    function vonaco_header_container() {
        echo '<div class="col-full">';
    }
}

if (!function_exists('vonaco_header_container_close')) {
    /**
     * The header container close
     */
    function vonaco_header_container_close() {
        echo '</div>';
    }
}

if (!function_exists('vonaco_header_custom_link')) {
    function vonaco_header_custom_link() {
        echo vonaco_get_theme_option('custom-link', '');
    }

}

if (!function_exists('vonaco_header_contact_info')) {
    function vonaco_header_contact_info() {
        echo vonaco_get_theme_option('contact-info', '');
    }

}

if (!function_exists('vonaco_header_account')) {
    function vonaco_header_account() {

        if (!vonaco_get_theme_option('show_header_account', true)) {
            return;
        }

        if (vonaco_is_woocommerce_activated()) {
            $account_link = get_permalink(get_option('woocommerce_myaccount_page_id'));
        } else {
            $account_link = wp_login_url();
        }
        ?>
        <div class="site-header-account">
            <a href="<?php echo esc_url($account_link); ?>">
                <i class="vonaco-icon-account"></i>
            </a>
            <div class="account-dropdown">

            </div>
        </div>
        <?php
    }

}

if (!function_exists('vonaco_template_account_dropdown')) {
    function vonaco_template_account_dropdown() {
        if (!vonaco_get_theme_option('show_header_account', true)) {
            return;
        }
        ?>
        <div class="account-wrap d-none">
            <div class="account-inner <?php if (is_user_logged_in()): echo "dashboard"; endif; ?>">
                <?php if (!is_user_logged_in()) {
                    vonaco_form_login();
                } else {
                    vonaco_account_dropdown();
                }
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('vonaco_form_login')) {
    function vonaco_form_login() {
        ?>
        <div class="login-form-head">
            <span class="login-form-title"><?php esc_attr_e('Sign in', 'vonaco') ?></span>
            <span class="pull-right">
                <a class="register-link" href="<?php echo esc_url(wp_registration_url()); ?>"
                   title="<?php esc_attr_e('Register', 'vonaco'); ?>"><?php esc_attr_e('Create an Account', 'vonaco'); ?></a>
            </span>
        </div>
        <form class="vonaco-login-form-ajax" data-toggle="validator">
            <p>
                <label><?php esc_attr_e('Username or email', 'vonaco'); ?> <span class="required">*</span></label>
                <input name="username" type="text" required placeholder="<?php esc_attr_e('Username', 'vonaco') ?>">
            </p>
            <p>
                <label><?php esc_attr_e('Password', 'vonaco'); ?> <span class="required">*</span></label>
                <input name="password" type="password" required
                       placeholder="<?php esc_attr_e('Password', 'vonaco') ?>">
            </p>
            <button type="submit" data-button-action
                    class="btn btn-primary btn-block w-100 mt-1"><?php esc_html_e('Login', 'vonaco') ?></button>
            <input type="hidden" name="action" value="vonaco_login">
            <?php wp_nonce_field('ajax-vonaco-login-nonce', 'security-login'); ?>
        </form>
        <div class="login-form-bottom">
            <a href="<?php echo wp_lostpassword_url(get_permalink()); ?>" class="lostpass-link"
               title="<?php esc_attr_e('Lost your password?', 'vonaco'); ?>"><?php esc_attr_e('Lost your password?', 'vonaco'); ?></a>
        </div>
        <?php
    }
}

if (!function_exists('')) {
    function vonaco_account_dropdown() { ?>
        <?php if (has_nav_menu('my-account')) : ?>
            <nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e('Dashboard', 'vonaco'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'my-account',
                    'menu_class'     => 'account-links-menu',
                    'depth'          => 1,
                ));
                ?>
            </nav><!-- .social-navigation -->
        <?php else: ?>
            <ul class="account-dashboard">

                <?php if (vonaco_is_woocommerce_activated()): ?>
                    <li>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
                           title="<?php esc_html_e('Dashboard', 'vonaco'); ?>"><?php esc_html_e('Dashboard', 'vonaco'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>"
                           title="<?php esc_html_e('Orders', 'vonaco'); ?>"><?php esc_html_e('Orders', 'vonaco'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('downloads')); ?>"
                           title="<?php esc_html_e('Downloads', 'vonaco'); ?>"><?php esc_html_e('Downloads', 'vonaco'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>"
                           title="<?php esc_html_e('Edit Address', 'vonaco'); ?>"><?php esc_html_e('Edit Address', 'vonaco'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>"
                           title="<?php esc_html_e('Account Details', 'vonaco'); ?>"><?php esc_html_e('Account Details', 'vonaco'); ?></a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo esc_url(get_dashboard_url(get_current_user_id())); ?>"
                           title="<?php esc_html_e('Dashboard', 'vonaco'); ?>"><?php esc_html_e('Dashboard', 'vonaco'); ?></a>
                    </li>
                <?php endif; ?>
                <li>
                    <a title="<?php esc_html_e('Log out', 'vonaco'); ?>" class="tips"
                       href="<?php echo esc_url(wp_logout_url(home_url())); ?>"><?php esc_html_e('Log Out', 'vonaco'); ?></a>
                </li>
            </ul>
        <?php endif;

    }
}

if (!function_exists('vonaco_header_search_popup')) {
    function vonaco_header_search_popup() {
        ?>
        <div class="site-search-popup">
            <div class="site-search-popup-wrap">
                <a href="#" class="site-search-popup-close"><i class="vonaco-icon-times-circle"></i></a>
                <?php
                if (vonaco_is_woocommerce_activated()) {
                    vonaco_product_search();
                } else {
                    ?>
                    <div class="site-search">
                        <?php get_search_form(); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="site-search-popup-overlay"></div>
        <?php
    }
}

if (!function_exists('vonaco_header_search_button')) {
    function vonaco_header_search_button() {

        add_action('wp_footer', 'vonaco_header_search_popup', 1);
        ?>
        <div class="site-header-search">
            <a href="#" class="button-search-popup"><i class="vonaco-icon-search-2"></i></a>
        </div>
        <?php
    }
}


if (!function_exists('vonaco_header_sticky')) {
    function vonaco_header_sticky() {
        get_template_part('template-parts/header', 'sticky');
    }
}

if (!function_exists('vonaco_mobile_nav')) {
    function vonaco_mobile_nav() {
        if (isset(get_nav_menu_locations()['handheld'])) {
            ?>
            <div class="vonaco-mobile-nav">
                <div class="menu-scroll-mobile">
                    <a href="#" class="mobile-nav-close"><i class="vonaco-icon-times"></i></a>
                    <?php
                    vonaco_mobile_navigation();
                    vonaco_social();
                    ?>
                </div>
            </div>
            <div class="vonaco-overlay"></div>
            <?php
        }
    }
}

if (!function_exists('vonaco_mobile_nav_button')) {
    function vonaco_mobile_nav_button() {
        if (isset(get_nav_menu_locations()['handheld'])) {
            ?>
            <a href="#" class="menu-mobile-nav-button">
				<span
                        class="toggle-text screen-reader-text"><?php echo esc_attr(apply_filters('vonaco_menu_toggle_text', esc_html__('Menu', 'vonaco'))); ?></span>
                <div class="vonaco-icon">
                    <span class="icon-1"></span>
                    <span class="icon-2"></span>
                    <span class="icon-3"></span>
                    <span class="icon-4"></span>
                </div>
            </a>
            <?php
        }
    }
}


if (!function_exists('vonaco_footer_default')) {
    function vonaco_footer_default() {
        get_template_part('template-parts/copyright');
    }
}


if (!function_exists('vonaco_pingback_header')) {
    /**
     * Add a pingback url auto-discovery header for single posts, pages, or attachments.
     */
    function vonaco_pingback_header() {
        if (is_singular() && pings_open()) {
            echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
        }
    }
}

if (!function_exists('vonaco_social_share')) {
    function vonaco_social_share() {
        get_template_part('template-parts/socials');
    }
}

if (!function_exists('vonaco_update_comment_fields')) {
    function vonaco_update_comment_fields($fields) {

        $commenter = wp_get_current_commenter();
        $req       = get_option('require_name_email');
        $aria_req  = $req ? "aria-required='true'" : '';

        $fields['author']
            = '<p class="comment-form-author">
			<input id="author" name="author" type="text" placeholder="' . esc_attr__('Your Name *', 'vonaco') . '" value="' . esc_attr($commenter['comment_author']) .
              '" size="30" ' . $aria_req . ' />
		</p>';

        $fields['email']
            = '<p class="comment-form-email">
			<input id="email" name="email" type="email" placeholder="' . esc_attr__('Email Address *', 'vonaco') . '" value="' . esc_attr($commenter['comment_author_email']) .
              '" size="30" ' . $aria_req . ' />
		</p>';

        $fields['url']
            = '<p class="comment-form-url">
			<input id="url" name="url" type="url"  placeholder="' . esc_attr__('Your Website', 'vonaco') . '" value="' . esc_attr($commenter['comment_author_url']) .
              '" size="30" />
			</p>';

        return $fields;
    }
}

add_filter('comment_form_default_fields', 'vonaco_update_comment_fields');

if (!function_exists('vonaco_update_comment_review_fields')) {
    function vonaco_update_comment_review_fields($comment_form) {
        $commenter = wp_get_current_commenter();

        $name_email_required = (bool)get_option('require_name_email', 1);
        $fields              = array(
            'author' => array(
                'label'    => esc_html__('Name', 'vonaco'),
                'type'     => 'text',
                'value'    => $commenter['comment_author'],
                'required' => $name_email_required,
            ),
            'email'  => array(
                'label'    => esc_html__('Email', 'vonaco'),
                'type'     => 'email',
                'value'    => $commenter['comment_author_email'],
                'required' => $name_email_required,
            ),
        );

        $comment_form['fields'] = array();

        foreach ($fields as $key => $field) {
            $field_html = '<p class="comment-form-' . esc_attr($key) . '">';

            $field_html .= '<input id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" type="' . esc_attr($field['type']) . '" value="' . esc_attr($field['value']) . '" size="30" ' . ($field['required'] ? 'required' : '') . ' placeholder="' . esc_html($field['label']) . ' *"' . ' />';

            $field_html .= '</p>';

            $comment_form['fields'][$key] = $field_html;
        }

        if (wc_review_ratings_enabled()) {
            $comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__('Your rating', 'vonaco') . (wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '') . '</label><select name="rating" id="rating" required>
						<option value="">' . esc_html__('Rate&hellip;', 'vonaco') . '</option>
						<option value="5">' . esc_html__('Perfect', 'vonaco') . '</option>
						<option value="4">' . esc_html__('Good', 'vonaco') . '</option>
						<option value="3">' . esc_html__('Average', 'vonaco') . '</option>
						<option value="2">' . esc_html__('Not that bad', 'vonaco') . '</option>
						<option value="1">' . esc_html__('Very poor', 'vonaco') . '</option>
					</select></div>';
        }
        $comment_form['comment_field'] .= '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" required placeholder="' . esc_html__('Your review *', 'vonaco') . '"></textarea></p>';

        return $comment_form;
    }
}
add_filter('woocommerce_product_review_comment_form_args', 'vonaco_update_comment_review_fields');


function vonaco_replace_categories_list($output, $args) {
    if ($args['show_count'] = 1) {
        $pattern     = '#<li([^>]*)><a([^>]*)>(.*?)<\/a>\s*\(([0-9]*)\)\s*#i';  // removed ( and )
        $replacement = '<li$1><a$2><span class="cat-name">$3</span> <span class="cat-count">($4)</span></a>';
        return preg_replace($pattern, $replacement, $output);
    }
    return $output;
}

add_filter('wp_list_categories', 'vonaco_replace_categories_list', 10, 2);

function vonaco_replace_archive_list($link_html, $url, $text, $format, $before, $after, $selected) {
    if ($format == 'html') {
        $pattern     = '#<li><a([^>]*)>(.*?)<\/a>&nbsp;\s*\(([0-9]*)\)\s*#i';  // removed ( and )
        $replacement = '<li><a$1><span class="archive-name">$2</span> <span class="archive-count">($3)</span></a>';
        return preg_replace($pattern, $replacement, $link_html);
    }
    return $link_html;
}

add_filter('get_archives_link', 'vonaco_replace_archive_list', 10, 7);


add_filter('bcn_breadcrumb_title', 'vonaco_breadcrumb_title_swapper', 3, 10);
function vonaco_breadcrumb_title_swapper($title, $type, $id) {
    if (in_array('home', $type)) {
        $title = esc_html__('Home', 'vonaco');
    }
    return $title;
}
