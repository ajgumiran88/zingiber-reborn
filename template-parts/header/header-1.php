<header id="masthead" class="site-header header-1" role="banner">
    <div class="header-container">
        <div class="container header-main">
            <div class="header-left">
                <?php
                vonaco_site_branding();
                if (vonaco_is_woocommerce_activated()) {
                    ?>
                    <div class="site-header-cart header-cart-mobile">
                        <?php vonaco_cart_link(); ?>
                    </div>
                    <?php
                }
                ?>
                <?php vonaco_mobile_nav_button(); ?>
            </div>
            <div class="header-center">
                <?php vonaco_primary_navigation(); ?>
            </div>
            <div class="header-right desktop-hide-down">
                <div class="header-group-action">
                    <?php
                    vonaco_header_account();
                    if (vonaco_is_woocommerce_activated()) {
                        vonaco_header_wishlist();
                        vonaco_header_cart();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</header><!-- #masthead -->
