<?php
/**
 * Footer for the Zingiber page experience.
 */

$siteContent = zingiber_get_site_content();
$contact = $siteContent['contact']['details'];
$footerOrder = ['about', 'menu', 'gallery', 'careers', 'contact'];
?>
<footer class="zingiber-footer">
    <div class="zingiber-container zingiber-footer__grid">
        <div class="zingiber-footer__brand">
            <a class="zingiber-footer__logo-link" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Zingiber home', 'vonaco'); ?>">
                <img
                    class="zingiber-footer__logo"
                    src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-light.png')); ?>"
                    alt="<?php esc_attr_e('Zingiber', 'vonaco'); ?>"
                    width="536"
                    height="356"
                    loading="lazy"
                >
            </a>
            <p><?php esc_html_e('A chef-led modern Indian coastal restaurant shaped by heritage, innovation, and storytelling.', 'vonaco'); ?></p>
        </div>

        <div class="zingiber-footer__nav">
            <p class="zingiber-footer__heading"><?php esc_html_e('Explore', 'vonaco'); ?></p>
            <ul>
                <?php foreach ($footerOrder as $slug) : ?>
                    <li><a href="<?php echo esc_url(home_url('/' . $slug . '/')); ?>"><?php echo esc_html($siteContent[$slug]['title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="zingiber-footer__contact">
            <p class="zingiber-footer__heading"><?php esc_html_e('Visit Zingiber', 'vonaco'); ?></p>
            <address>
                <span><?php echo esc_html($contact['address_name']); ?></span>
                <span><?php echo esc_html($contact['address']); ?></span>
            </address>
            <a href="mailto:<?php echo esc_attr($contact['email']); ?>"><?php echo esc_html($contact['email']); ?></a>
            <span><?php echo esc_html($contact['hours']); ?></span>
        </div>

        <div class="zingiber-footer__social">
            <p class="zingiber-footer__heading"><?php esc_html_e('Follow', 'vonaco'); ?></p>
            <a href="https://www.instagram.com/zingiberdubai/" target="_blank" rel="noopener noreferrer">Instagram <?php echo esc_html($contact['instagram']); ?></a>
            <a href="https://www.tiktok.com/@zingiberdubai" target="_blank" rel="noopener noreferrer">TikTok <?php echo esc_html($contact['tiktok']); ?></a>
            <span>Facebook <?php echo esc_html($contact['facebook']); ?></span>
        </div>
    </div>

    <div class="zingiber-container zingiber-footer__bottom">
        <p>&copy; <?php echo esc_html(date_i18n('Y')); ?> <?php esc_html_e('Zingiber Restaurant. All rights reserved.', 'vonaco'); ?></p>
        <p><?php esc_html_e('Jumeirah Lakes Towers, Dubai, UAE', 'vonaco'); ?></p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
