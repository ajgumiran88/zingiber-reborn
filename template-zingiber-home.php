<?php
/**
 * Template Name: Zingiber Home 2
 * Template Post Type: page
 */

$siteContent = zingiber_get_site_content();
$home = $siteContent['home'];
$sections = $home['sections'];
$principleImages = [$home['regions'][0]['image'], $sections['chef_story']['image'], $sections['experience']['image'], $home['regions'][3]['image']];

get_header('zingiber');
?>
<main id="zingiber-main" class="zingiber-main">
    <section
        id="hero"
        class="zingiber-hero"
    >
        <div class="zingiber-hero__media" aria-hidden="true">
            <video class="zingiber-hero__video" playsinline muted loop preload="none" poster="<?php echo esc_url(zingiber_theme_asset_url($home['hero']['image']['src'])); ?>" hidden data-zingiber-hero-video>
                <?php if (!empty($home['hero']['video'])) : ?>
                    <source src="<?php echo esc_url($home['hero']['video']); ?>" type="video/mp4">
                <?php endif; ?>
            </video>
            <?php
            zingiber_theme_image($home['hero']['image'], [
                'class' => 'zingiber-hero__photo',
                'width' => 2400,
                'height' => 1350,
                'loading' => 'eager',
                'fetchpriority' => 'high',
                'sizes' => '100vw',
                'aria_hidden' => true,
            ]);
            ?>
        </div>
        <div class="zingiber-hero__veil" aria-hidden="true"></div>
        <div class="zingiber-container zingiber-hero__content">
            <div class="zingiber-hero__intro" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php echo esc_html($home['hero']['eyebrow']); ?></p>
            </div>
            <h1 data-zingiber-reveal>
                <span class="zingiber-hero__title-line"><?php echo esc_html('Introducing a Modern' . "\u{00A0}" . 'Indian'); ?></span>
                <span class="zingiber-hero__title-line zingiber-hero__title-line--italic"><?php echo esc_html('Coastal Dining' . "\u{00A0}" . 'Experience.'); ?></span>
            </h1>
            <a class="zingiber-circle-link zingiber-hero__cta" href="<?php echo esc_url(home_url('/menu/')); ?>" data-zingiber-reveal>
                <span class="zingiber-sr-only"><?php esc_html_e('See Menu', 'vonaco'); ?></span>
                <svg class="zingiber-circle-link__ring" viewBox="0 0 120 120" aria-hidden="true" focusable="false">
                    <defs><path id="zingiber-menu-ring" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0" /></defs>
                    <text><textPath href="#zingiber-menu-ring" textLength="276"><?php esc_html_e('SEE MENU · SEE MENU · ', 'vonaco'); ?></textPath></text>
                </svg>
                <span class="zingiber-circle-link__arrow" aria-hidden="true"><?php echo zingiber_icon_svg('arrow'); ?></span>
            </a>
        </div>
        <a
            class="zingiber-hero__scroll-guide"
            href="#chef"
            aria-label="<?php esc_attr_e('Scroll to discover', 'vonaco'); ?>"
            data-zingiber-hero-scroll
            data-zingiber-reveal
        >
            <span class="zingiber-hero__scroll-guide-track" aria-hidden="true">
                <span class="zingiber-hero__scroll-guide-marker"></span>
            </span>
        </a>
    </section>

    <section id="chef" class="zingiber-story" aria-labelledby="story-title">
            <figure class="zingiber-story__image">
                <?php zingiber_theme_image($sections['chef_story']['image'], [
                    'width' => 1536,
                    'height' => 1024,
                    'sizes' => '(min-width: 768px) 50vw, 100vw',
                ]); ?>
            </figure>
            <div class="zingiber-story__copy" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php echo esc_html($sections['chef_story']['eyebrow']); ?></p>
                <h2 id="story-title">
                    <span class="zingiber-heading-line"><?php echo esc_html('Coastal India'); ?><span class="zingiber-punct">,</span></span>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('Reimagined for Now')); ?></span>
                </h2>
                <?php foreach ($sections['chef_story']['body'] as $paragraph) : ?>
                    <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                <?php endforeach; ?>
                <a class="zingiber-arrow-link" href="<?php echo esc_url(home_url('/menu/')); ?>"><?php esc_html_e('View Full Menu', 'vonaco'); ?><span aria-hidden="true"><?php echo zingiber_icon_svg('arrow'); ?></span></a>
            </div>
    </section>

    <section id="coastal-regions" class="zingiber-coastal zingiber-section" aria-labelledby="coast-title" data-zingiber-coast>
        <div class="zingiber-container zingiber-coastal__intro" data-zingiber-reveal>
            <div>
                <p class="zingiber-eyebrow"><?php echo esc_html($sections['coastal_expression']['eyebrow']); ?></p>
                <h2 id="coast-title"><?php echo esc_html(zingiber_prevent_widows($sections['coastal_expression']['heading'])); ?></h2>
            </div>
            <div class="zingiber-coastal__copy">
                <?php foreach ($sections['coastal_expression']['body'] as $paragraph) : ?>
                    <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        <ul class="zingiber-coastal__track" tabindex="0" aria-label="<?php esc_attr_e('Coastal regions. Use the left and right arrow keys to explore.', 'vonaco'); ?>" data-zingiber-coast-track>
            <?php foreach ($home['regions'] as $index => $region) : ?>
                <li class="zingiber-coastal__slide">
                    <figure>
                        <?php zingiber_theme_image($region['image'], [
                            'width' => 1536,
                            'height' => 1024,
                            'sizes' => '(min-width: 1025px) 75vw, 90vw',
                        ]); ?>
                    </figure>
                    <div class="zingiber-coastal__caption">
                        <h3><?php echo esc_html($region['name']); ?></h3>
                        <p><?php echo esc_html(zingiber_prevent_widows($region['summary'])); ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="zingiber-container zingiber-coastal__controls">
            <p class="zingiber-coastal__index" aria-live="polite" aria-atomic="true"><span data-zingiber-coast-index>01</span><span aria-hidden="true"> / </span><span class="zingiber-sr-only"><?php esc_html_e('of', 'vonaco'); ?> </span><?php echo esc_html(sprintf('%02d', count($home['regions']))); ?></p>
            <progress class="zingiber-coastal__progress" value="1" max="<?php echo esc_attr((string) count($home['regions'])); ?>" aria-label="<?php esc_attr_e('Coastal region progress', 'vonaco'); ?>" data-zingiber-coast-progress></progress>
            <div class="zingiber-coastal__buttons">
                <button type="button" data-zingiber-coast-step="-1" aria-label="<?php esc_attr_e('Previous coastal region', 'vonaco'); ?>" disabled><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true" focusable="false"><path d="m14 6-6 6 6 6" /></svg></button>
                <button type="button" data-zingiber-coast-step="1" aria-label="<?php esc_attr_e('Next coastal region', 'vonaco'); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true" focusable="false"><path d="m10 6 6 6-6 6" /></svg></button>
            </div>
        </div>
    </section>

    <section id="principles" class="zingiber-card-section zingiber-section" aria-labelledby="principles-title">
        <div class="zingiber-container">
            <div class="zingiber-card-section__heading" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php esc_html_e('What Guides Us', 'vonaco'); ?></p>
                <h2 id="principles-title"><?php echo esc_html(zingiber_prevent_widows('Four Principles. One Point of View.')); ?></h2>
            </div>
            <ol id="zingiber-principle-cards" class="zingiber-principle-cards" tabindex="0" aria-label="<?php esc_attr_e('Our four principles. Scroll to explore.', 'vonaco'); ?>">
                <?php foreach ($home['principles'] as $index => $principle) : ?>
                    <li data-zingiber-reveal>
                        <a class="zingiber-principle-card" href="<?php echo esc_url(home_url('/about/#about-principles-title')); ?>" aria-label="<?php echo esc_attr(sprintf(__('Explore %s', 'vonaco'), $principle['title'])); ?>">
                            <?php zingiber_theme_image($principleImages[$index], ['width' => 1024, 'height' => 1536, 'sizes' => '(min-width: 768px) 30vw, 85vw']); ?>
                            <h3><?php echo esc_html($principle['title']); ?></h3>
                            <div class="zingiber-principle-card__copy">
                                <p><?php echo esc_html(zingiber_prevent_widows($principle['summary'])); ?></p>
                                <span class="zingiber-principle-card__explore"><?php esc_html_e('Explore', 'vonaco'); ?><?php echo zingiber_icon_svg('arrow'); ?></span>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ol>
            <div class="zingiber-principles-controls" aria-label="Principles navigation">
                <button type="button" data-zingiber-principles-step="-1" aria-controls="zingiber-principle-cards" aria-label="Previous principle" disabled><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M20 12H4m6-6-6 6 6 6"/></svg></button>
                <progress data-zingiber-principles-progress value="0" max="100" aria-label="Principles scroll progress"></progress>
                <button type="button" data-zingiber-principles-step="1" aria-controls="zingiber-principle-cards" aria-label="Next principle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 12h16m-6-6 6 6-6 6"/></svg></button>
            </div>
        </div>
    </section>

    <section id="gallery" class="zingiber-section zingiber-gallery-teaser" aria-labelledby="gallery-title">
        <div class="zingiber-container zingiber-gallery-teaser__heading" data-zingiber-reveal>
            <div>
                <p class="zingiber-eyebrow"><?php echo esc_html($siteContent['gallery']['hero']['eyebrow']); ?></p>
                <h2 id="gallery-title"><?php echo esc_html(zingiber_prevent_widows($siteContent['gallery']['hero']['heading'])); ?></h2>
            </div>
            <a class="zingiber-text-link zingiber-text-link--dark" href="<?php echo esc_url(home_url('/gallery/')); ?>"><?php esc_html_e('View the Gallery', 'vonaco'); ?><?php echo zingiber_icon_svg('arrow'); ?></a>
        </div>
        <div class="zingiber-container zingiber-gallery-teaser__strip">
            <?php foreach (array_slice($home['featured_images'], 0, 4) as $galleryImage) : ?>
                <figure class="zingiber-gallery-teaser__tile" data-zingiber-reveal>
                    <a href="<?php echo esc_url(home_url('/gallery/')); ?>">
                    <?php
                    zingiber_theme_image($galleryImage, [
                        'width' => 1536,
                        'height' => 1024,
                        'sizes' => '(min-width: 768px) 25vw, 45vw',
                    ]);
                    ?>
                    </a>
                </figure>
            <?php endforeach; ?>
        </div>
    </section>

</main>
<?php
get_footer('zingiber');
