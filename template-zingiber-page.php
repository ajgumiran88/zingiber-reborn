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
    <?php if ($zingiberPage['slug'] !== 'menu') : ?>
    <section class="zingiber-page-hero<?php echo $zingiberPage['slug'] === 'about' ? ' zingiber-about-hero' : ''; ?>">
        <div class="zingiber-page-hero__media" aria-hidden="true">
            <?php
            zingiber_theme_image($zingiberPage['hero']['image'], [
                'width' => 2400,
                'height' => 1350,
                'loading' => 'eager',
                'fetchpriority' => 'high',
                'sizes' => '100vw',
                'class' => 'zingiber-page-hero__photo',
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
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows(__('A Journey Across', 'vonaco'))); ?></span>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows(__('India’s Coastline', 'vonaco'))); ?></span>
                <?php elseif ($zingiberPage['slug'] === 'about') : ?>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows(__('A Modern Expression', 'vonaco'))); ?></span>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows(__('of Coastal India', 'vonaco'))); ?></span>
                <?php else : ?>
                    <?php echo esc_html(zingiber_prevent_widows($zingiberPage['hero']['heading'])); ?>
                <?php endif; ?>
            </h1>
            <p data-zingiber-reveal><?php echo esc_html(zingiber_prevent_widows($zingiberPage['hero']['subheading'])); ?></p>
            <?php if ($zingiberPage['slug'] === 'about') : ?>
                <div class="zingiber-about-hero__links" data-zingiber-reveal>
                    <a class="zingiber-text-link" href="#about-story"><?php echo esc_html($home['calls_to_action']['story']['label']); ?><span aria-hidden="true">↓</span></a>
                    <a class="zingiber-text-link" href="<?php echo esc_url(zingiber_page_url('/menu/')); ?>"><?php echo esc_html($home['calls_to_action']['secondary']['label']); ?><span aria-hidden="true">↗</span></a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($zingiberPage['slug'] === 'about') : ?>
        <section class="zingiber-section zingiber-page-editorial zingiber-page-editorial--about">
            <div class="zingiber-container">
                <?php [$story, $heritage] = $zingiberPage['sections']; ?>
                <div class="zingiber-about__story" id="about-story">
                    <div class="zingiber-about__chapter" data-zingiber-reveal>
                        <p class="zingiber-eyebrow"><?php echo esc_html(sprintf(__('%02d / Our Story', 'vonaco'), 1)); ?></p>
                        <h2><?php echo esc_html(zingiber_prevent_widows($story['heading'])); ?></h2>
                    </div>
                    <div class="zingiber-about__columns" data-zingiber-reveal>
                        <?php foreach ($story['body'] as $paragraph) : ?>
                            <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="zingiber-about__pair">
                    <figure class="zingiber-about__photograph zingiber-about__dish" data-zingiber-reveal="image">
                        <?php zingiber_theme_image($story['image'], ['width' => 1024, 'height' => 1280, 'sizes' => '(min-width: 768px) 48vw, 94vw']); ?>
                        <figcaption><?php echo esc_html($home['hero']['eyebrow']); ?></figcaption>
                    </figure>
                    <div class="zingiber-about__experience">
                        <div class="zingiber-about__statement" data-zingiber-reveal>
                            <p class="zingiber-eyebrow"><?php echo esc_html(sprintf(__('%02d / Our Story', 'vonaco'), 2)); ?></p>
                            <h2><?php echo esc_html(zingiber_prevent_widows($heritage['heading'])); ?></h2>
                            <?php foreach ($heritage['body'] as $paragraph) : ?>
                                <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                            <?php endforeach; ?>
                        </div>
                        <figure class="zingiber-about__photograph zingiber-about__room" data-zingiber-reveal="image">
                            <?php zingiber_theme_image($heritage['image'], ['width' => 1200, 'height' => 900, 'sizes' => '(min-width: 768px) 42vw, 94vw']); ?>
                        </figure>
                    </div>
                </div>
            </div>
        </section>

        <section class="zingiber-page-principles zingiber-about__principles" aria-labelledby="about-principles-title">
            <div class="zingiber-container">
                <div class="zingiber-principles__heading" data-zingiber-reveal>
                    <p class="zingiber-eyebrow"><?php esc_html_e('What Guides Us', 'vonaco'); ?></p>
                    <h2 id="about-principles-title"><?php echo esc_html(zingiber_prevent_widows(__('Four Principles. One Point of View.', 'vonaco'))); ?></h2>
                </div>
                <ol>
                    <?php foreach ($home['principles'] as $index => $principle) : ?>
                        <li data-zingiber-reveal>
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
        <?php
        // JF-inspired menu rhythm: region pills + organic plates. Copy stays approved coastal regions — no invented dishes/prices.
        $menuRegions = $home['regions'];
        $menuPlatePool = [
            ['src' => 'assets/images/zingiber/food-prawn-curry.jpg', 'alt' => __('Coastal Indian prawn curry with refined presentation', 'vonaco')],
            ['src' => 'assets/images/zingiber/food-seafood-rice.jpg', 'alt' => __('Seafood rice inspired by India’s coastal culinary traditions', 'vonaco')],
            ['src' => 'assets/images/zingiber/food-grilled-lamb.jpg', 'alt' => __('A precisely plated grilled dish from the Zingiber kitchen', 'vonaco')],
            ['src' => 'assets/images/zingiber/food-coastal-thali.jpg', 'alt' => __('A modern composition inspired by India’s coastal regions', 'vonaco')],
        ];
        ?>
        <section class="zingiber-section zingiber-page-menu" data-zingiber-menu-page>
            <div class="zingiber-container zingiber-page-menu__mast">
                <p class="zingiber-eyebrow" data-zingiber-reveal><?php esc_html_e('The Culinary Journey', 'vonaco'); ?></p>
                <h1 class="zingiber-page-menu__display" data-zingiber-reveal><?php echo esc_html($zingiberPage['title']); ?></h1>
                <p class="zingiber-page-menu__lede" data-zingiber-reveal><?php echo esc_html(zingiber_prevent_widows($zingiberPage['sections'][0]['heading'])); ?></p>
            </div>
        <nav class="zingiber-page-menu__tabs" aria-label="<?php esc_attr_e('Menu regions', 'vonaco'); ?>" data-zingiber-menu-tabs>
            <button class="zingiber-page-menu__step" type="button" data-zingiber-menu-step="-1" aria-label="<?php esc_attr_e('Previous menu region', 'vonaco'); ?>" hidden><?php echo zingiber_icon_svg('arrow'); ?></button>
            <div class="zingiber-page-menu__rail" data-zingiber-menu-rail>
                    <?php foreach ($menuRegions as $region) :
                        $regionId = sanitize_title($region['name']);
                        ?>
                        <a class="zingiber-page-menu__tab" href="#<?php echo esc_attr($regionId); ?>"><?php echo esc_html($region['name']); ?></a>
                    <?php endforeach; ?>
                            </div>
            <button class="zingiber-page-menu__step" type="button" data-zingiber-menu-step="1" aria-label="<?php esc_attr_e('Next menu region', 'vonaco'); ?>" hidden><?php echo zingiber_icon_svg('arrow'); ?></button>
        </nav>

            <div class="zingiber-container zingiber-page-menu__intro" data-zingiber-reveal>
                <?php foreach ($zingiberPage['sections'][0]['body'] as $paragraph) : ?>
                    <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                <?php endforeach; ?>
            </div>
            <div class="zingiber-container zingiber-page-menu__images" role="list">
                <?php foreach ($menuRegions as $index => $region) :
                    $regionId = sanitize_title($region['name']);
                    $plate = $menuPlatePool[$index] ?? $region['image'];
                    ?>
                    <article class="zingiber-menu-plate" id="<?php echo esc_attr($regionId); ?>" role="listitem" data-zingiber-reveal data-zingiber-menu-region>
                        <figure class="zingiber-menu-plate__figure">
                            <?php
                            zingiber_theme_image(
                                [
                                    'src' => $plate['src'],
                                    'alt' => $plate['alt'],
                                ],
                                [
                                    'width' => 1024,
                                    'height' => 1024,
                                    'sizes' => '(min-width: 600px) 24vw, 45vw',
                                ]
                            );
                            ?>
                        </figure>
                        <h3 class="zingiber-menu-plate__title"><?php echo esc_html($region['name']); ?></h3>
                        <p class="zingiber-menu-plate__copy"><?php echo esc_html(zingiber_prevent_widows($region['summary'])); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>

        <div class="zingiber-container zingiber-page-menu__note zingiber-panel zingiber-panel--sand" data-zingiber-reveal>
                <p><?php echo esc_html(zingiber_prevent_widows($zingiberPage['sections'][1]['body'][0])); ?></p>
                <a class="zingiber-button zingiber-button--dark zingiber-button--prominent" href="<?php echo esc_url(zingiber_page_url('/contact/')); ?>"><?php esc_html_e('Plan Your Visit', 'vonaco'); ?></a>
            </div>
            <div class="zingiber-page-menu__dock">
            <a href="<?php echo esc_url(zingiber_page_url('/contact/')); ?>"><span><?php esc_html_e('Book Now', 'vonaco'); ?></span></a>
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
                <?php foreach (['experience', 'reservation'] as $sectionKey) : $section = $home['sections'][$sectionKey]; ?>
                    <div class="zingiber-page-contact__copy" data-zingiber-reveal>
                        <p class="zingiber-eyebrow"><?php echo esc_html($section['eyebrow']); ?></p>
                        <h2><?php echo esc_html(zingiber_prevent_widows($section['heading'])); ?></h2>
                        <?php foreach ($section['body'] as $paragraph) : ?>
                            <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php
get_footer('zingiber');
