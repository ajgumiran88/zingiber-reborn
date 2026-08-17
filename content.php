<article id="post-<?php the_ID(); ?>" <?php post_class('article-default'); ?>>
    <div class="post-inner">
        <?php vonaco_post_thumbnail(); ?>
        <div class="post-content">
            <?php
            /**
             * Functions hooked in to vonaco_loop_post action.
             *
             * @see vonaco_post_header          - 15
             * @see vonaco_post_content         - 30
             */
            do_action('vonaco_loop_post');
            ?>
        </div>
    </div>
</article><!-- #post-## -->