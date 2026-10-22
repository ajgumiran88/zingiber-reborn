<?php
/**
 * Footer for the Zingiber page experience.
 */

$siteContent = zingiber_get_site_content();
$contact = $siteContent['contact']['details'];
$footerOrder = ['about', 'menu', 'gallery', 'careers', 'contact'];
get_template_part('template-parts/zingiber-enquiry');
?>
<footer class="zingiber-footer">
    <div class="zingiber-footer__invitation">
        <img class="zingiber-footer__roots" data-zingiber-reveal src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/ginger-cluster.png')); ?>" alt="" width="1536" height="1024" loading="lazy" aria-hidden="true">
        <div class="zingiber-container zingiber-footer__invitation-inner">
            <a class="zingiber-footer__email" href="mailto:<?php echo esc_attr($contact['email']); ?>"><?php echo esc_html($contact['email']); ?></a>
            <a class="zingiber-footer__book" href="<?php echo esc_url(home_url('/contact/#zingiber-enquiry')); ?>"><?php esc_html_e('Book a table', 'vonaco'); ?><span aria-hidden="true">↗</span></a>
        </div>
    </div>
    <div class="zingiber-container zingiber-footer__grid">
        <div class="zingiber-footer__contact" data-zingiber-reveal>
            <h3 class="zingiber-footer__heading"><?php esc_html_e('Visit Zingiber', 'vonaco'); ?></h3>
            <address>
                <span><?php echo esc_html($contact['address_name']); ?></span>
                <span><?php echo esc_html(zingiber_prevent_widows($contact['address'])); ?></span>
            </address>
            <?php if (!empty($contact['phone'])) : ?>
                <a href="tel:<?php echo esc_attr(preg_replace('/[^+0-9]/', '', $contact['phone'])); ?>"><?php echo esc_html($contact['phone']); ?></a>
            <?php else : ?>
                <span><?php echo esc_html($contact['phone_label']); ?></span>
            <?php endif; ?>
        </div>
        <div class="zingiber-footer__hours" data-zingiber-reveal>
            <h3 class="zingiber-footer__heading"><?php esc_html_e('Opening Hours', 'vonaco'); ?></h3>
            <p><?php echo esc_html(zingiber_prevent_widows($contact['hours'])); ?></p>
        </div>
        <nav class="zingiber-footer__nav" data-zingiber-reveal aria-label="<?php esc_attr_e('Footer navigation', 'vonaco'); ?>">
            <p class="zingiber-footer__heading"><?php esc_html_e('Explore', 'vonaco'); ?></p>
            <ul>
                <?php foreach ($footerOrder as $slug) : ?>
                    <li><a href="<?php echo esc_url(home_url('/' . $slug . '/')); ?>"><?php echo esc_html($siteContent[$slug]['title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="zingiber-footer__social" data-zingiber-reveal>
            <p class="zingiber-footer__heading"><?php esc_html_e('Follow Along', 'vonaco'); ?></p>
            <a href="https://www.instagram.com/zingiberdubai/" target="_blank" rel="noopener noreferrer">Instagram <?php echo esc_html($contact['instagram']); ?></a>
            <a href="https://www.tiktok.com/@zingiberdubai" target="_blank" rel="noopener noreferrer">TikTok <?php echo esc_html($contact['tiktok']); ?></a>
            <span>Facebook <?php echo esc_html($contact['facebook']); ?></span>
        </div>
    </div>
    <a class="zingiber-container zingiber-footer__wordmark" data-zingiber-reveal href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Zingiber home', 'vonaco'); ?>">
        <img src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-dark.png')); ?>" alt="Zingiber" width="1400" height="235" loading="lazy">
    </a>
    <div class="zingiber-container zingiber-footer__bottom">
        <p>&copy; <?php echo esc_html(date_i18n('Y')); ?> <?php esc_html_e('Zingiber Restaurant. All rights reserved.', 'vonaco'); ?></p>
        <p><?php echo esc_html(zingiber_prevent_widows(__('Jumeirah Lakes Towers, Dubai, UAE', 'vonaco'))); ?></p>
    </div>
</footer>

<button
    type="button"
    class="zingiber-back-to-top"
    data-zingiber-back-to-top
    aria-label="<?php esc_attr_e('Back to top', 'vonaco'); ?>"
>
    <svg class="zingiber-back-to-top__icon" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
        <path
            fill="none"
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.5"
            d="M3.5 9.5 8 5l4.5 4.5M8 5.25v7.25"
        />
    </svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
