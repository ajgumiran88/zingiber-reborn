<?php
get_header(); ?>

    <div id="primary" class="content">
        <main id="main" class="site-main" role="main">
            <div class="error-404 not-found">
                <div class="page-content">
                    <header class="page-header">
                        <h1 class="page-title"><?php esc_html_e('404','vonaco'); ?></h1>
                        <h3 class="page-title"><?php esc_html_e('Oops! That Links Is Broken.', 'vonaco'); ?></h3>
                    </header><!-- .page-header -->

                    <div class="error-text">
                        <span><?php esc_html_e('Page does not exist or some other error occured. Go to our Home Page', 'vonaco') ?></span>
                    </div>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="button-404">
                        <span class="button-text"><?php esc_html_e('Back To Home', 'vonaco'); ?></span>
                        <i class="vonaco-icon vonaco-icon-arrow-circle"></i>
                    </a>
                </div><!-- .page-content -->
            </div><!-- .error-404 -->
        </main><!-- #main -->
    </div><!-- #primary -->
<?php
get_footer();
