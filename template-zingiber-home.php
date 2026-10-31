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
                <span class="zingiber-hero__title-line"><?php echo esc_html(__('Introducing a Modern Indian', 'vonaco')); ?></span>
                <span class="zingiber-hero__title-line zingiber-hero__title-line--italic"><?php echo esc_html(__('Coastal Dining Experience.', 'vonaco')); ?></span>
            </h1>
            <a class="zingiber-circle-link zingiber-hero__cta" href="<?php echo esc_url(zingiber_page_url('/menu/')); ?>" data-zingiber-reveal>
                <svg class="zingiber-circle-link__ring" viewBox="0 0 120 120" aria-hidden="true" focusable="false">
                    <path d="M60 7 C88 5 113 29 113 59 C115 87 90 113 60 113 C30 115 6 91 7 61 C5 32 29 8 60 7 Z" />
                    <path d="M58 10 C88 7 110 31 111 61 C111 91 86 111 57 110 C27 111 9 86 10 57 C9 28 31 9 58 10 Z" />
                    <path d="M63 8 C92 10 114 35 110 65 C108 94 82 114 53 111 C24 108 5 83 10 54 C14 28 34 5 63 8 Z" />
                </svg>
                <span class="zingiber-circle-link__label"><?php esc_html_e('See Menu', 'vonaco'); ?></span>
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
                    <span class="zingiber-heading-line"><span data-zingiber-split><?php echo esc_html(__('Coastal India', 'vonaco')); ?></span><span class="zingiber-punct">,</span></span>
                    <span class="zingiber-heading-line" data-zingiber-split><?php echo esc_html(zingiber_prevent_widows(__('Reimagined for Now', 'vonaco'))); ?></span>
                </h2>
                <?php foreach ($sections['chef_story']['body'] as $paragraph) : ?>
                    <p data-zingiber-split><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                <?php endforeach; ?>
                <a class="zingiber-arrow-link" href="<?php echo esc_url(zingiber_page_url('/menu/')); ?>"><?php esc_html_e('View Full Menu', 'vonaco'); ?><span aria-hidden="true"><?php echo zingiber_icon_svg('arrow'); ?></span></a>
            </div>
    </section>

    <section id="coastal-regions" class="zingiber-coastal zingiber-section" aria-labelledby="coast-title" data-zingiber-coast>
        <div class="zingiber-container zingiber-coastal__intro" data-zingiber-reveal>
            <div>
                <p class="zingiber-eyebrow"><?php echo esc_html($sections['coastal_expression']['eyebrow']); ?></p>
                <h2 id="coast-title" data-zingiber-split><?php echo esc_html(zingiber_prevent_widows($sections['coastal_expression']['heading'])); ?></h2>
            </div>
            <div class="zingiber-coastal__copy">
                <?php foreach ($sections['coastal_expression']['body'] as $paragraph) : ?>
                    <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        <ul class="zingiber-coastal__track" tabindex="0" aria-label="<?php esc_attr_e('Coastal regions. Use the left and right arrow keys to explore.', 'vonaco'); ?>" data-zingiber-coast-track data-zingiber-reveal>
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
        <div class="zingiber-container zingiber-coastal__controls" data-zingiber-reveal>
            <p class="zingiber-coastal__index" aria-live="polite" aria-atomic="true"><span data-zingiber-coast-index>01</span><span class="zingiber-coastal__total"><span aria-hidden="true">/</span><span class="zingiber-sr-only"><?php esc_html_e('of', 'vonaco'); ?> </span><?php echo esc_html(sprintf('%02d', count($home['regions']))); ?></span></p>
            <progress class="zingiber-coastal__progress" value="1" max="<?php echo esc_attr((string) count($home['regions'])); ?>" aria-label="<?php esc_attr_e('Coastal region progress', 'vonaco'); ?>" data-zingiber-coast-progress></progress>
            <button type="button" class="zingiber-coast-toggle" data-zingiber-coast-toggle data-pause-label="<?php esc_attr_e('Pause slider', 'vonaco'); ?>" data-resume-label="<?php esc_attr_e('Resume slider', 'vonaco'); ?>" aria-pressed="false" aria-label="<?php esc_attr_e('Pause slider', 'vonaco'); ?>"><span class="zingiber-coast-toggle__icon" aria-hidden="true"></span></button>
            <div class="zingiber-coastal__buttons">
                <button type="button" data-zingiber-coast-step="-1" aria-label="<?php esc_attr_e('Previous coastal region', 'vonaco'); ?>" disabled><?php echo zingiber_icon_svg('previous'); ?></button>
                <button type="button" data-zingiber-coast-step="1" aria-label="<?php esc_attr_e('Next coastal region', 'vonaco'); ?>"><?php echo zingiber_icon_svg('next'); ?></button>
            </div>
        </div>
    </section>

    <section id="principles" class="zingiber-card-section zingiber-section" aria-labelledby="principles-title" data-zingiber-coast>
        <div class="zingiber-container">
            <div class="zingiber-card-section__heading" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php esc_html_e('What Guides Us', 'vonaco'); ?></p>
                <h2 id="principles-title"><?php echo esc_html(zingiber_prevent_widows(__('Four Principles. One Point of View.', 'vonaco'))); ?></h2>
            </div>
            <ol id="zingiber-principle-cards" class="zingiber-principle-cards" tabindex="0" aria-label="<?php esc_attr_e('Our four principles. Use the left and right arrow keys to explore.', 'vonaco'); ?>" data-zingiber-coast-track data-zingiber-reveal>
                <?php foreach ($home['principles'] as $index => $principle) : ?>
                    <li>
                        <a class="zingiber-principle-card" href="<?php echo esc_url(zingiber_page_url('/about/#about-principles-title')); ?>" aria-label="<?php echo esc_attr(sprintf(__('Explore %s', 'vonaco'), $principle['title'])); ?>">
                            <?php /* Cards past the first pair sit inside the horizontal scroller, where lazy-loading never triggers until they slide in. */ ?>
                            <?php zingiber_theme_image($principleImages[$index], ['width' => 1024, 'height' => 1536, 'sizes' => '(min-width: 768px) 50vw, 50vw', 'loading' => $index > 1 ? 'eager' : 'lazy', 'fetchpriority' => $index > 1 ? 'low' : 'auto']); ?>
                            <h3><?php echo esc_html($principle['title']); ?></h3>
                            <div class="zingiber-principle-card__copy">
                                <p><?php echo esc_html(zingiber_prevent_widows($principle['summary'])); ?></p>
                                <span class="zingiber-principle-card__explore"><?php esc_html_e('Explore', 'vonaco'); ?><?php echo zingiber_icon_svg('arrow'); ?></span>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ol>
            <div class="zingiber-principles__nav" data-zingiber-reveal>
                <p class="zingiber-sr-only" aria-live="polite" aria-atomic="true"><span data-zingiber-coast-index>01</span> <?php esc_html_e('of', 'vonaco'); ?> <?php echo esc_html(sprintf('%02d', count($home['principles']))); ?></p>
                <button type="button" data-zingiber-coast-step="-1" aria-label="<?php esc_attr_e('Previous principle', 'vonaco'); ?>" aria-controls="zingiber-principle-cards" disabled><?php echo zingiber_icon_svg('long-arrow'); ?></button>
                <progress class="zingiber-coastal__progress" value="1" max="<?php echo esc_attr((string) count($home['principles'])); ?>" aria-label="<?php esc_attr_e('Principles progress', 'vonaco'); ?>" data-zingiber-coast-progress></progress>
                <button type="button" class="zingiber-coast-toggle" data-zingiber-coast-toggle data-pause-label="<?php esc_attr_e('Pause slider', 'vonaco'); ?>" data-resume-label="<?php esc_attr_e('Resume slider', 'vonaco'); ?>" aria-pressed="false" aria-label="<?php esc_attr_e('Pause slider', 'vonaco'); ?>"><span class="zingiber-coast-toggle__icon" aria-hidden="true"></span></button>
                <button type="button" data-zingiber-coast-step="1" aria-label="<?php esc_attr_e('Next principle', 'vonaco'); ?>" aria-controls="zingiber-principle-cards"><?php echo zingiber_icon_svg('long-arrow'); ?></button>
            </div>
        </div>
    </section>

    <section id="gallery" class="zingiber-section zingiber-gallery-teaser" aria-labelledby="gallery-title">
        <div class="zingiber-gallery-teaser__stage">
            <?php /* JF-style floating ingredients: slot => photo cutout; slots carry position and depth in CSS. Sources: assets/images/zingiber/spices/CREDITS.md. */ ?>
            <div class="zingiber-spices" aria-hidden="true" data-zingiber-reveal>
                <?php foreach (['leaf' => 'curry-leaf', 'anise' => 'star-anise', 'pepper' => 'peppercorn', 'clove' => 'clove', 'chili' => 'chili', 'cardamom' => 'cardamom', 'cinnamon' => 'cinnamon', 'pepper-far' => 'peppercorn', 'ginger' => 'ginger'] as $slot => $spice) : ?>
                    <img class="zingiber-spice zingiber-spice--<?php echo esc_attr($slot); ?>" src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/spices/' . $spice . '.webp')); ?>" alt="" decoding="async">
                <?php endforeach; ?>
            </div>
            <div class="zingiber-gallery-teaser__heading" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php echo esc_html($siteContent['gallery']['hero']['eyebrow']); ?></p>
                <h2 id="gallery-title" data-zingiber-split><?php echo esc_html(zingiber_prevent_widows($siteContent['gallery']['hero']['heading'])); ?></h2>
                <p><?php echo esc_html(zingiber_prevent_widows($siteContent['gallery']['sections'][0]['body'][1])); ?></p>
                <a class="zingiber-text-link zingiber-text-link--dark" href="<?php echo esc_url(zingiber_page_url('/gallery/')); ?>"><?php esc_html_e('View the Gallery', 'vonaco'); ?><?php echo zingiber_icon_svg('arrow'); ?></a>
            </div>
            <?php /* JF arch: concentric pinstripe rings framing one round photograph. */ ?>
            <div class="zingiber-gallery-teaser__arch" data-zingiber-reveal>
                <?php /* Top-down thali: its plate sits dead centre, so it can turn in place like JF's dishes. */ ?>
                <div class="zingiber-gallery-teaser__plate"><?php zingiber_theme_image($home['regions'][3]['image'], ['width' => 1672, 'height' => 941, 'class' => 'zingiber-gallery-teaser__dish', 'sizes' => '(min-width: 768px) 22rem, 60vw', 'aria_hidden' => true]); ?></div>
                <a class="zingiber-circle-link zingiber-gallery-teaser__cta" href="<?php echo esc_url(zingiber_page_url('/menu/')); ?>">
                    <svg class="zingiber-circle-link__ring" viewBox="0 0 120 120" aria-hidden="true" focusable="false">
                        <path d="M60 7 C88 5 113 29 113 59 C115 87 90 113 60 113 C30 115 6 91 7 61 C5 32 29 8 60 7 Z" />
                        <path d="M58 10 C88 7 110 31 111 61 C111 91 86 111 57 110 C27 111 9 86 10 57 C9 28 31 9 58 10 Z" />
                        <path d="M63 8 C92 10 114 35 110 65 C108 94 82 114 53 111 C24 108 5 83 10 54 C14 28 34 5 63 8 Z" />
                    </svg>
                    <span class="zingiber-circle-link__label"><?php esc_html_e('See Menu', 'vonaco'); ?></span>
                </a>
            </div>
        </div>
        <div class="zingiber-container zingiber-gallery-teaser__strip">
            <?php foreach (array_slice($home['featured_images'], 0, 4) as $galleryImage) : ?>
                <figure class="zingiber-gallery-teaser__tile" data-zingiber-reveal>
                    <a href="<?php echo esc_url(zingiber_page_url('/gallery/')); ?>">
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
