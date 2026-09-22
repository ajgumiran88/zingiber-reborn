<?php
/**
 * Template Name: Zingiber Editorial Page
 * Template Post Type: page
 */

$siteContent = zingiber_get_site_content();
$zingiberPage = zingiber_current_page_content();
$home = $siteContent['home'];

get_header('zingiber');
?>
<main id="zingiber-main" class="zingiber-main zingiber-page zingiber-page-<?php echo esc_attr($zingiberPage['slug']); ?>">
    <section class="zingiber-page-hero">
        <div class="zingiber-page-hero__media" aria-hidden="true">
            <?php
            zingiber_theme_image($zingiberPage['hero']['image'], [
                'width' => 2400,
                'height' => 1350,
                'loading' => 'eager',
                'fetchpriority' => 'high',
                'sizes' => '100vw',
                'aria_hidden' => true,
            ]);
            ?>
        </div>
        <div class="zingiber-page-hero__veil" aria-hidden="true"></div>
        <div class="zingiber-container zingiber-page-hero__content">
            <p class="zingiber-eyebrow" data-zingiber-reveal><?php echo esc_html($zingiberPage['hero']['eyebrow']); ?></p>
            <div class="zingiber-rule" aria-hidden="true" data-zingiber-reveal></div>
            <h1 data-zingiber-reveal>
                <?php if ($zingiberPage['slug'] === 'menu') : ?>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('A Journey Across')); ?></span>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('India’s Coastline')); ?></span>
                <?php elseif ($zingiberPage['slug'] === 'about') : ?>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('A Modern Expression')); ?></span>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('of Coastal India')); ?></span>
                <?php else : ?>
                    <?php echo esc_html(zingiber_prevent_widows($zingiberPage['hero']['heading'])); ?>
                <?php endif; ?>
            </h1>
            <p data-zingiber-reveal><?php echo esc_html(zingiber_prevent_widows($zingiberPage['hero']['subheading'])); ?></p>
        </div>
    </section>

    <?php if ($zingiberPage['slug'] === 'about') : ?>
        <section class="zingiber-section zingiber-page-editorial zingiber-page-editorial--about">
            <div class="zingiber-container">
                <?php foreach ($zingiberPage['sections'] as $index => $section) : ?>
                    <article class="zingiber-page-editorial__row<?php echo $index % 2 ? ' is-reversed' : ''; ?>">
                        <div class="zingiber-page-editorial__copy" data-zingiber-reveal>
                            <p class="zingiber-eyebrow"><?php echo esc_html(sprintf('%02d / Our Story', $index + 1)); ?></p>
                            <h2><?php echo esc_html(zingiber_prevent_widows($section['heading'])); ?></h2>
                            <?php foreach ($section['body'] as $paragraph) : ?>
                                <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                            <?php endforeach; ?>
                        </div>
                        <figure class="zingiber-page-editorial__image" data-zingiber-reveal>
                            <?php
                            zingiber_theme_image($section['image'], [
                                'width' => 1536,
                                'height' => 1024,
                                'sizes' => '(min-width: 1025px) 42vw, 100vw',
                            ]);
                            ?>
                        </figure>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="zingiber-page-principles" aria-labelledby="about-principles-title">
            <div class="zingiber-container">
                <div class="zingiber-principles__heading" data-zingiber-reveal>
                    <p class="zingiber-eyebrow"><?php esc_html_e('What Guides Us', 'vonaco'); ?></p>
                    <h2 id="about-principles-title"><?php echo esc_html(zingiber_prevent_widows('Four Principles. One Point of View.')); ?></h2>
                </div>
                <ol>
                    <?php foreach ($home['principles'] as $index => $principle) : ?>
                        <li data-zingiber-reveal>
                            <span class="zingiber-principles__icon"><?php echo zingiber_icon_svg($principle['icon']); ?></span>
                            <span class="zingiber-principles__index"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                            <strong><?php echo esc_html($principle['title']); ?></strong>
                            <?php if (!empty($principle['summary'])) : ?>
                                <p class="zingiber-principles__summary"><?php echo esc_html(zingiber_prevent_widows($principle['summary'])); ?></p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </section>

    <?php elseif ($zingiberPage['slug'] === 'menu') : ?>
        <section class="zingiber-section zingiber-page-menu">
            <div class="zingiber-container zingiber-page-menu__intro">
                <div data-zingiber-reveal>
                    <p class="zingiber-eyebrow"><?php esc_html_e('The Culinary Journey', 'vonaco'); ?></p>
                    <h2><?php echo esc_html(zingiber_prevent_widows($zingiberPage['sections'][0]['heading'])); ?></h2>
                </div>
                <div data-zingiber-reveal>
                    <?php foreach ($zingiberPage['sections'][0]['body'] as $paragraph) : ?>
                        <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="zingiber-container zingiber-page-menu__images">
                <figure data-zingiber-reveal>
                    <?php
                    zingiber_theme_image(
                        [
                            'src' => 'assets/images/zingiber/food-prawn-curry.jpg',
                            'alt' => __('Coastal Indian prawn curry with refined presentation', 'vonaco'),
                        ],
                        [
                            'width' => 1024,
                            'height' => 1536,
                            'sizes' => '(min-width: 1025px) 28vw, 90vw',
                        ]
                    );
                    ?>
                </figure>
                <figure data-zingiber-reveal>
                    <?php
                    zingiber_theme_image(
                        [
                            'src' => 'assets/images/zingiber/food-coastal-thali.jpg',
                            'alt' => __('A modern composition inspired by India’s coastal regions', 'vonaco'),
                        ],
                        [
                            'width' => 1672,
                            'height' => 941,
                            'sizes' => '(min-width: 1025px) 28vw, 90vw',
                        ]
                    );
                    ?>
                </figure>
                <figure data-zingiber-reveal>
                    <?php
                    zingiber_theme_image(
                        [
                            'src' => 'assets/images/zingiber/food-dessert.jpg',
                            'alt' => __('Elegant dessert presentation from the Zingiber kitchen', 'vonaco'),
                        ],
                        [
                            'width' => 1024,
                            'height' => 1536,
                            'sizes' => '(min-width: 1025px) 28vw, 90vw',
                        ]
                    );
                    ?>
                </figure>
            </div>
            <div class="zingiber-container zingiber-page-menu__note zingiber-panel zingiber-panel--sand" data-zingiber-reveal>
                <p><?php echo esc_html(zingiber_prevent_widows($zingiberPage['sections'][1]['body'][0])); ?></p>
                <a class="zingiber-button zingiber-button--dark zingiber-button--prominent" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Plan Your Visit', 'vonaco'); ?></a>
            </div>
        </section>

    <?php elseif ($zingiberPage['slug'] === 'gallery') : ?>
        <section class="zingiber-section zingiber-page-gallery">
            <div class="zingiber-container zingiber-page-gallery__intro" data-zingiber-reveal>
                <div class="zingiber-page-gallery__title">
                    <p class="zingiber-eyebrow"><?php esc_html_e('Inside Zingiber', 'vonaco'); ?></p>
                    <h2><?php echo esc_html(zingiber_prevent_widows($zingiberPage['sections'][0]['heading'])); ?></h2>
                </div>
                <div class="zingiber-page-gallery__copy">
                    <?php foreach ($zingiberPage['sections'][0]['body'] as $paragraph) : ?>
                        <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
            $zingiberGallery = $zingiberPage['sections'][0]['gallery'];
            $zingiberGalleryFilters = $zingiberPage['sections'][0]['filters'];
            $zingiberGalleryCount = count($zingiberGallery);
            ?>
            <div class="zingiber-container zingiber-page-gallery__toolbar">
                <?php
                /*
                 * The filter row is printed for everyone but only revealed once the
                 * JS class lands, so a visitor without scripts sees the full set
                 * rather than a row of buttons that cannot do anything.
                 */
                ?>
                <div class="zingiber-gallery-filters" role="group" aria-label="<?php esc_attr_e('Filter the gallery', 'vonaco'); ?>" data-zingiber-gallery-filters>
                    <?php foreach ($zingiberGalleryFilters as $zingiberFilterKey => $zingiberFilterLabel) : ?>
                        <button
                            class="zingiber-gallery-filter<?php echo $zingiberFilterKey === 'all' ? ' is-active' : ''; ?>"
                            type="button"
                            data-zingiber-gallery-filter="<?php echo esc_attr($zingiberFilterKey); ?>"
                            aria-pressed="<?php echo $zingiberFilterKey === 'all' ? 'true' : 'false'; ?>"
                        >
                            <?php echo esc_html($zingiberFilterLabel); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <p
                    class="zingiber-gallery-count"
                    role="status"
                    data-zingiber-gallery-count
                    data-count-one="<?php echo esc_attr__('%s photograph', 'vonaco'); ?>"
                    data-count-other="<?php echo esc_attr__('%s photographs', 'vonaco'); ?>"
                >
                    <?php
                    printf(
                        /* translators: %s: number of photographs currently shown. */
                        esc_html(_n('%s photograph', '%s photographs', $zingiberGalleryCount, 'vonaco')),
                        esc_html(number_format_i18n($zingiberGalleryCount))
                    );
                    ?>
                </p>
            </div>
            <div
                class="zingiber-container zingiber-page-gallery__grid"
                data-zingiber-gallery-grid
                data-label-viewer="<?php esc_attr_e('Gallery viewer', 'vonaco'); ?>"
                data-label-close="<?php esc_attr_e('Close gallery viewer', 'vonaco'); ?>"
                data-label-previous="<?php esc_attr_e('Previous photograph', 'vonaco'); ?>"
                data-label-next="<?php esc_attr_e('Next photograph', 'vonaco'); ?>"
                data-label-counter="<?php esc_attr_e('%1$s of %2$s', 'vonaco'); ?>"
            >
                <?php foreach ($zingiberGallery as $index => $image) : ?>
                    <?php
                    /*
                     * The row layout sizes each frame from its own aspect
                     * ratio, so the ratio travels with the markup.
                     */
                    $zingiberRatio = round((int) $image['width'] / max(1, (int) $image['height']), 4);
                    ?>
                    <figure
                        class="zingiber-page-gallery__item"
                        style="--zingiber-ar: <?php echo esc_attr((string) $zingiberRatio); ?>;"
                        data-zingiber-reveal
                        data-zingiber-gallery-item
                        data-zingiber-gallery-category="<?php echo esc_attr($image['category']); ?>"
                    >
                        <?php
                        /*
                         * The trigger is a link to the full-size file, so the tile
                         * still opens the photograph if the lightbox script never
                         * runs. The script cancels the navigation and opens the
                         * viewer in place.
                         */
                        ?>
                        <a
                            class="zingiber-page-gallery__trigger"
                            href="<?php echo esc_url(zingiber_theme_asset_url(ltrim($image['src'], '/'))); ?>"
                            data-zingiber-gallery-open="<?php echo esc_attr((string) $index); ?>"
                            data-zingiber-gallery-src="<?php echo esc_url(zingiber_theme_image_display_src($image)); ?>"
                            aria-label="<?php echo esc_attr(sprintf(__('View larger: %s', 'vonaco'), $image['alt'])); ?>"
                        >
                            <?php
                            zingiber_theme_image($image, [
                                'width' => (int) $image['width'],
                                'height' => (int) $image['height'],
                                'sizes' => '(min-width: 1025px) 32vw, (min-width: 768px) 46vw, 100vw',
                            ]);
                            ?>
                            <span class="zingiber-page-gallery__zoom" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                                    <circle cx="11" cy="11" r="6.5"></circle>
                                    <path d="M11 8.4v5.2M8.4 11h5.2M15.8 15.8 20 20"></path>
                                </svg>
                            </span>
                        </a>
                    </figure>
                <?php endforeach; ?>
            </div>
            <p class="zingiber-container zingiber-gallery-empty" data-zingiber-gallery-empty hidden>
                <?php esc_html_e('No photographs in this set yet.', 'vonaco'); ?>
            </p>
        </section>

    <?php elseif ($zingiberPage['slug'] === 'careers') : ?>
        <section class="zingiber-section zingiber-page-careers">
            <div class="zingiber-container zingiber-page-careers__grid">
                <figure data-zingiber-reveal>
                    <?php
                    zingiber_theme_image($zingiberPage['sections'][0]['image'], [
                        'width' => 2400,
                        'height' => 1350,
                        'sizes' => '(min-width: 1025px) 50vw, 100vw',
                    ]);
                    ?>
                </figure>
                <div class="zingiber-panel zingiber-panel--sand" data-zingiber-reveal>
                    <p class="zingiber-eyebrow"><?php esc_html_e('Careers at Zingiber', 'vonaco'); ?></p>
                    <h2><?php echo esc_html(zingiber_prevent_widows($zingiberPage['sections'][0]['heading'])); ?></h2>
                    <?php foreach ($zingiberPage['sections'][0]['body'] as $paragraph) : ?>
                        <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                    <?php endforeach; ?>
                    <a class="zingiber-button zingiber-button--dark zingiber-button--prominent" href="mailto:reservations@zingiber.ae?subject=Careers%20at%20Zingiber"><?php echo esc_html($zingiberPage['sections'][0]['action']['label']); ?></a>
                </div>
            </div>
        </section>

    <?php elseif ($zingiberPage['slug'] === 'contact') : ?>
        <section class="zingiber-section zingiber-page-contact">
            <div class="zingiber-container zingiber-page-contact__grid">
                <div class="zingiber-page-contact__copy" data-zingiber-reveal>
                    <p class="zingiber-eyebrow"><?php esc_html_e('Contact & Reservations', 'vonaco'); ?></p>
                    <h2>
                        <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('We Look Forward')); ?></span>
                        <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('to Welcoming You')); ?></span>
                    </h2>
                    <p><?php echo esc_html(zingiber_prevent_widows($zingiberPage['sections'][0]['body'][0])); ?></p>
                    <a class="zingiber-button zingiber-button--dark zingiber-button--prominent" href="mailto:<?php echo esc_attr($zingiberPage['details']['email']); ?>?subject=Reservation%20Enquiry"><?php esc_html_e('Email Reservations', 'vonaco'); ?></a>
                </div>
                <div class="zingiber-page-contact__details zingiber-panel zingiber-panel--sand" data-zingiber-reveal>
                    <div>
                        <p class="zingiber-page-contact__label"><?php esc_html_e('Visit', 'vonaco'); ?></p>
                        <address><strong><?php echo esc_html($zingiberPage['details']['address_name']); ?></strong><span><?php echo esc_html(zingiber_prevent_widows($zingiberPage['details']['address'])); ?></span></address>
                    </div>
                    <div>
                        <p class="zingiber-page-contact__label"><?php esc_html_e('Reservations', 'vonaco'); ?></p>
                        <a href="mailto:<?php echo esc_attr($zingiberPage['details']['email']); ?>"><?php echo esc_html($zingiberPage['details']['email']); ?></a>
                        <?php if (!zingiber_is_pending_detail($zingiberPage['details']['phone'] ?? '') && !zingiber_is_pending_detail($zingiberPage['details']['phone_label'] ?? '')) : ?>
                            <span><?php echo esc_html($zingiberPage['details']['phone'] !== '' ? $zingiberPage['details']['phone'] : $zingiberPage['details']['phone_label']); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (!zingiber_is_pending_detail($zingiberPage['details']['hours'] ?? '')) : ?>
                    <div>
                        <p class="zingiber-page-contact__label"><?php esc_html_e('Opening Hours', 'vonaco'); ?></p>
                        <span><?php echo esc_html(zingiber_prevent_widows($zingiberPage['details']['hours'])); ?></span>
                    </div>
                    <?php endif; ?>
                    <div>
                        <p class="zingiber-page-contact__label"><?php esc_html_e('Follow', 'vonaco'); ?></p>
                        <a href="https://www.instagram.com/zingiberdubai/" target="_blank" rel="noopener noreferrer">Instagram <?php echo esc_html($zingiberPage['details']['instagram']); ?></a>
                        <a href="https://www.tiktok.com/@zingiberdubai" target="_blank" rel="noopener noreferrer">TikTok <?php echo esc_html($zingiberPage['details']['tiktok']); ?></a>
                        <span>Facebook <?php echo esc_html($zingiberPage['details']['facebook']); ?></span>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php
get_footer('zingiber');
