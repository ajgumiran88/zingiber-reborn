<div class="column-item post-style-1">
    <div class="post-inner">
        <?php if (has_post_thumbnail()): ?>
            <div class="post-thumbnail">
                <?php
                $categories_list = get_the_category_list(' ');
                if ('post' === get_post_type() && $categories_list) {
                    // Make sure there's more than one category before displaying.
                    echo '<div class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'vonaco') . '</span>' . $categories_list . '</div>';
                }
                the_post_thumbnail('vonaco-post-marsonry'); ?>
            </div>
        <?php endif; ?>
        <div class="entry-header">
            <div class="entry-meta">
                <?php vonaco_post_meta(); ?>
            </div>
            <?php the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
            <div class="post-excerpt">
                <p><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
            </div>
        </div>
    </div>
</div>
