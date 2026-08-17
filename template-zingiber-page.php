<?php
/**
 * Template Name: Zingiber Editorial Page
 * Template Post Type: page
 */

$siteContent = zingiber_get_site_content();
$page = zingiber_current_page_content();
$home = $siteContent['home'];

get_header('zingiber');
?>
<main id="zingiber-main" class="zingiber-main zingiber-page zingiber-page-<?php echo esc_attr($page['slug']); ?>">
    <section class="zingiber-page-hero">
        <div class="zingiber-page-hero__media" aria-hidden="true">
            <img
                src="<?php echo esc_url(zingiber_theme_asset_url($page['hero']['image']['src'])); ?>"
                alt=""
                width="2400"
                height="1350"
            >
        </div>
        <div class="zingiber-page-hero__veil" aria-hidden="true"></div>
        <div class="zingiber-container zingiber-page-hero__content">
            <p class="zingiber-eyebrow" data-zingiber-reveal><?php echo esc_html($page['hero']['eyebrow']); ?></p>
            <h1 data-zingiber-reveal><?php echo esc_html($page['hero']['heading']); ?></h1>
            <p data-zingiber-reveal><?php echo esc_html($page['hero']['subheading']); ?></p>
        </div>
    </section>

    <?php if ($page['slug'] === 'about') : ?>
        <section class="zingiber-section zingiber-page-editorial zingiber-page-editorial--about">
            <div class="zingiber-container">
                <?php foreach ($page['sections'] as $index => $section) : ?>
                    <article class="zingiber-page-editorial__row<?php echo $index % 2 ? ' is-reversed' : ''; ?>">
                        <div class="zingiber-page-editorial__copy" data-zingiber-reveal>
                            <p class="zingiber-eyebrow"><?php echo esc_html(sprintf('%02d / Our Story', $index + 1)); ?></p>
                            <h2><?php echo esc_html($section['heading']); ?></h2>
                            <?php foreach ($section['body'] as $paragraph) : ?>
                                <p><?php echo esc_html($paragraph); ?></p>
                            <?php endforeach; ?>
                        </div>
                        <figure class="zingiber-page-editorial__image" data-zingiber-reveal>
                            <img src="<?php echo esc_url(zingiber_theme_asset_url($section['image']['src'])); ?>" alt="<?php echo esc_attr($section['image']['alt']); ?>" width="1536" height="1024" loading="lazy">
                        </figure>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="zingiber-page-principles" aria-labelledby="about-principles-title">
            <div class="zingiber-container">
                <p class="zingiber-eyebrow" data-zingiber-reveal><?php esc_html_e('At Our Core', 'vonaco'); ?></p>
                <h2 id="about-principles-title" data-zingiber-reveal><?php esc_html_e('Guided by Four Principles', 'vonaco'); ?></h2>
                <ol>
                    <?php foreach ($home['principles'] as $index => $principle) : ?>
                        <li data-zingiber-reveal><span><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span><strong><?php echo esc_html($principle['title']); ?></strong></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </section>

    <?php elseif ($page['slug'] === 'menu') : ?>
        <section class="zingiber-section zingiber-page-menu">
            <div class="zingiber-container zingiber-page-menu__intro">
                <div data-zingiber-reveal>
                    <p class="zingiber-eyebrow"><?php esc_html_e('The Culinary Journey', 'vonaco'); ?></p>
                    <h2><?php echo esc_html($page['sections'][0]['heading']); ?></h2>
                </div>
                <div data-zingiber-reveal>
                    <?php foreach ($page['sections'][0]['body'] as $paragraph) : ?>
                        <p><?php echo esc_html($paragraph); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="zingiber-container zingiber-page-menu__images">
                <figure data-zingiber-reveal><img src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/food-prawn-curry.jpg')); ?>" alt="<?php esc_attr_e('Coastal Indian prawn curry with refined presentation', 'vonaco'); ?>" width="1024" height="1536" loading="lazy"></figure>
                <figure data-zingiber-reveal><img src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/food-coastal-thali.jpg')); ?>" alt="<?php esc_attr_e('A modern composition inspired by India’s coastal regions', 'vonaco'); ?>" width="1672" height="941" loading="lazy"></figure>
                <figure data-zingiber-reveal><img src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/food-dessert.jpg')); ?>" alt="<?php esc_attr_e('Elegant dessert presentation from the Zingiber kitchen', 'vonaco'); ?>" width="1024" height="1536" loading="lazy"></figure>
            </div>
            <div class="zingiber-container zingiber-page-menu__note" data-zingiber-reveal>
                <p><?php echo esc_html($page['sections'][1]['body'][0]); ?></p>
                <a class="zingiber-button zingiber-button--dark" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Plan Your Visit', 'vonaco'); ?></a>
            </div>
        </section>

    <?php elseif ($page['slug'] === 'gallery') : ?>
        <section class="zingiber-section zingiber-page-gallery">
            <div class="zingiber-container zingiber-page-gallery__intro" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php esc_html_e('Inside Zingiber', 'vonaco'); ?></p>
                <h2><?php echo esc_html($page['sections'][0]['heading']); ?></h2>
                <?php foreach ($page['sections'][0]['body'] as $paragraph) : ?>
                    <p><?php echo esc_html($paragraph); ?></p>
                <?php endforeach; ?>
            </div>
            <div class="zingiber-container zingiber-page-gallery__grid">
                <?php foreach ($page['sections'][0]['gallery'] as $index => $image) : ?>
                    <figure class="zingiber-page-gallery__item zingiber-page-gallery__item--<?php echo esc_attr((string) ($index + 1)); ?>" data-zingiber-reveal>
                        <img src="<?php echo esc_url(zingiber_theme_asset_url($image['src'])); ?>" alt="<?php echo esc_attr($image['alt']); ?>" width="1536" height="1024" loading="lazy">
                    </figure>
                <?php endforeach; ?>
            </div>
        </section>

    <?php elseif ($page['slug'] === 'careers') : ?>
        <section class="zingiber-section zingiber-page-careers">
            <div class="zingiber-container zingiber-page-careers__grid">
                <figure data-zingiber-reveal>
                    <img src="<?php echo esc_url(zingiber_theme_asset_url($page['sections'][0]['image']['src'])); ?>" alt="<?php echo esc_attr($page['sections'][0]['image']['alt']); ?>" width="2400" height="1350" loading="lazy">
                </figure>
                <div data-zingiber-reveal>
                    <p class="zingiber-eyebrow"><?php esc_html_e('Careers at Zingiber', 'vonaco'); ?></p>
                    <h2><?php echo esc_html($page['sections'][0]['heading']); ?></h2>
                    <?php foreach ($page['sections'][0]['body'] as $paragraph) : ?>
                        <p><?php echo esc_html($paragraph); ?></p>
                    <?php endforeach; ?>
                    <a class="zingiber-button zingiber-button--dark" href="mailto:reservations@zingiber.ae?subject=Careers%20at%20Zingiber"><?php echo esc_html($page['sections'][0]['action']['label']); ?></a>
                </div>
            </div>
        </section>

    <?php elseif ($page['slug'] === 'contact') : ?>
        <section class="zingiber-section zingiber-page-contact">
            <div class="zingiber-container zingiber-page-contact__grid">
                <div class="zingiber-page-contact__copy" data-zingiber-reveal>
                    <p class="zingiber-eyebrow"><?php esc_html_e('Contact & Reservations', 'vonaco'); ?></p>
                    <h2><?php echo esc_html($page['sections'][0]['heading']); ?></h2>
                    <p><?php echo esc_html($page['sections'][0]['body'][0]); ?></p>
                    <a class="zingiber-button zingiber-button--dark" href="mailto:<?php echo esc_attr($page['details']['email']); ?>?subject=Reservation%20Enquiry"><?php esc_html_e('Email Reservations', 'vonaco'); ?></a>
                </div>
                <div class="zingiber-page-contact__details" data-zingiber-reveal>
                    <div>
                        <p class="zingiber-page-contact__label"><?php esc_html_e('Visit', 'vonaco'); ?></p>
                        <address><strong><?php echo esc_html($page['details']['address_name']); ?></strong><span><?php echo esc_html($page['details']['address']); ?></span></address>
                    </div>
                    <div>
                        <p class="zingiber-page-contact__label"><?php esc_html_e('Reservations', 'vonaco'); ?></p>
                        <a href="mailto:<?php echo esc_attr($page['details']['email']); ?>"><?php echo esc_html($page['details']['email']); ?></a>
                        <span><?php echo esc_html($page['details']['phone_label']); ?></span>
                    </div>
                    <div>
                        <p class="zingiber-page-contact__label"><?php esc_html_e('Opening Hours', 'vonaco'); ?></p>
                        <span><?php echo esc_html($page['details']['hours']); ?></span>
                    </div>
                    <div>
                        <p class="zingiber-page-contact__label"><?php esc_html_e('Follow', 'vonaco'); ?></p>
                        <a href="https://www.instagram.com/zingiberdubai/" target="_blank" rel="noopener noreferrer">Instagram <?php echo esc_html($page['details']['instagram']); ?></a>
                        <a href="https://www.tiktok.com/@zingiberdubai" target="_blank" rel="noopener noreferrer">TikTok <?php echo esc_html($page['details']['tiktok']); ?></a>
                        <span>Facebook <?php echo esc_html($page['details']['facebook']); ?></span>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php
get_footer('zingiber');
