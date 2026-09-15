<?php
/**
 * Template Name: Zingiber Home 2
 * Template Post Type: page
 */

$siteContent = zingiber_get_site_content();
$home = $siteContent['home'];
$menuPage = $siteContent['menu'];
$contact = $siteContent['contact']['details'];
$sections = $home['sections'];

get_header('zingiber');
?>
<main id="zingiber-main" class="zingiber-main">
    <section
        id="hero"
        class="zingiber-hero"
    >
        <div class="zingiber-hero__media" aria-hidden="true">
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
                <div class="zingiber-rule" aria-hidden="true"></div>
            </div>
            <h1 data-zingiber-reveal>
                <span class="zingiber-hero__title-line"><?php echo esc_html('Introducing a Modern' . "\u{00A0}" . 'Indian'); ?></span>
                <span class="zingiber-hero__title-line zingiber-hero__title-line--italic"><?php echo esc_html('Coastal Dining' . "\u{00A0}" . 'Experience.'); ?></span>
            </h1>
            <p class="zingiber-hero__lede" data-zingiber-reveal><?php echo esc_html(zingiber_prevent_widows($home['hero']['subheading'])); ?></p>
            <div class="zingiber-hero__hairline" aria-hidden="true" data-zingiber-reveal></div>
            <a class="zingiber-button zingiber-button--prominent zingiber-hero__cta" href="<?php echo esc_url(home_url('/menu/')); ?>" data-zingiber-reveal><?php echo esc_html($home['calls_to_action']['secondary']['label']); ?></a>
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

    <section id="chef" class="zingiber-section zingiber-chef">
        <div class="zingiber-container zingiber-chef__grid">
            <div class="zingiber-chef__copy zingiber-panel zingiber-panel--sand" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php echo esc_html($sections['chef_story']['eyebrow']); ?></p>
                <h2>
                    <span class="zingiber-heading-line"><?php echo esc_html('Coastal India,'); ?></span>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('Reimagined for Now')); ?></span>
                </h2>
                <?php foreach ($sections['chef_story']['body'] as $paragraph) : ?>
                    <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                <?php endforeach; ?>
                <a class="zingiber-text-link zingiber-text-link--dark" href="<?php echo esc_url(home_url('/about/')); ?>"><?php echo esc_html($home['calls_to_action']['story']['label']); ?><?php echo zingiber_icon_svg('arrow'); ?></a>
            </div>
            <figure class="zingiber-chef__figure" data-zingiber-reveal>
                <?php
                zingiber_theme_image($sections['chef_story']['image'], [
                    'width' => 1536,
                    'height' => 1024,
                    'sizes' => '(min-width: 1025px) 38vw, 100vw',
                ]);
                ?>
                <figcaption><?php echo esc_html(zingiber_prevent_widows(__('A contemporary expression of India’s coast.', 'vonaco'))); ?></figcaption>
            </figure>
        </div>
    </section>

    <section id="principles" class="zingiber-principles" aria-labelledby="principles-title">
        <div class="zingiber-container">
            <div class="zingiber-principles__heading" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php esc_html_e('What Guides Us', 'vonaco'); ?></p>
                <h2 id="principles-title"><?php echo esc_html(zingiber_prevent_widows('Four Principles. One Point of View.')); ?></h2>
            </div>
            <ol class="zingiber-principles__list">
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

    <section id="coastal-regions" class="zingiber-section zingiber-coast">
        <div class="zingiber-container zingiber-coast__intro" data-zingiber-reveal>
            <p class="zingiber-eyebrow"><?php echo esc_html($sections['coastal_expression']['eyebrow']); ?></p>
            <h2>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('A Contemporary Take')); ?></span>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('on Coastal India')); ?></span>
            </h2>
            <div class="zingiber-coast__copy">
                <?php foreach ($sections['coastal_expression']['body'] as $paragraph) : ?>
                    <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="zingiber-container zingiber-coast__regions" aria-label="<?php esc_attr_e('Coastal regions that inspire the menu', 'vonaco'); ?>">
            <?php foreach ($home['regions'] as $index => $region) : ?>
                <article class="zingiber-region<?php echo $index % 2 ? ' is-reversed' : ''; ?>" data-zingiber-reveal>
                    <div class="zingiber-region__copy">
                        <span class="zingiber-region__index"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                        <h3><?php echo esc_html($region['name']); ?></h3>
                        <?php if (!empty($region['summary'])) : ?>
                            <p class="zingiber-region__summary"><?php echo esc_html(zingiber_prevent_widows($region['summary'])); ?></p>
                        <?php endif; ?>
                    </div>
                    <figure class="zingiber-region__image">
                        <?php
                        zingiber_theme_image($region['image'], [
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

    <section id="menu" class="zingiber-section zingiber-menu-story">
        <div class="zingiber-container zingiber-menu-story__grid">
            <div class="zingiber-menu-story__images" data-zingiber-reveal>
                <?php
                zingiber_theme_image($menuPage['hero']['image'], [
                    'class' => 'zingiber-menu-story__image zingiber-menu-story__image--wide',
                    'width' => 1672,
                    'height' => 941,
                    'sizes' => '(min-width: 1025px) 50vw, 100vw',
                ]);
                zingiber_theme_image($home['featured_images'][3], [
                    'class' => 'zingiber-menu-story__image zingiber-menu-story__image--detail',
                    'width' => 1536,
                    'height' => 1024,
                    'sizes' => '(min-width: 1025px) 28vw, 70vw',
                ]);
                ?>
            </div>
            <div class="zingiber-menu-story__copy" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php echo esc_html($menuPage['hero']['eyebrow']); ?></p>
                <h2>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('A Journey Across')); ?></span>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('India’s Coastline')); ?></span>
                </h2>
                <?php foreach ($menuPage['sections'][0]['body'] as $paragraph) : ?>
                    <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                <?php endforeach; ?>
                <a class="zingiber-button zingiber-button--dark zingiber-button--prominent" href="<?php echo esc_url(home_url('/menu/')); ?>"><?php esc_html_e('Explore the Menu', 'vonaco'); ?></a>
            </div>
        </div>
    </section>

    <section id="experience" class="zingiber-experience">
        <div class="zingiber-experience__image" data-zingiber-reveal>
            <?php
            zingiber_theme_image($sections['experience']['image'], [
                'width' => 2400,
                'height' => 1350,
                'sizes' => '(min-width: 1025px) 55vw, 100vw',
            ]);
            ?>
        </div>
        <div class="zingiber-experience__copy" data-zingiber-reveal>
            <div class="zingiber-panel zingiber-panel--dark">
                <p class="zingiber-eyebrow"><?php echo esc_html($sections['experience']['eyebrow']); ?></p>
                <h2>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('An Experience')); ?></span>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('Beyond the Plate')); ?></span>
                </h2>
                <?php foreach ($sections['experience']['body'] as $paragraph) : ?>
                    <p><?php echo esc_html(zingiber_prevent_widows($paragraph)); ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="gallery" class="zingiber-section zingiber-gallery" aria-labelledby="gallery-title">
        <div class="zingiber-container zingiber-gallery__heading" data-zingiber-reveal>
            <div>
                <p class="zingiber-eyebrow"><?php echo esc_html($siteContent['gallery']['hero']['eyebrow']); ?></p>
                <h2 id="gallery-title"><?php echo esc_html(zingiber_prevent_widows($siteContent['gallery']['hero']['heading'])); ?></h2>
            </div>
            <a class="zingiber-text-link zingiber-text-link--dark" href="<?php echo esc_url(home_url('/gallery/')); ?>"><?php esc_html_e('View the Gallery', 'vonaco'); ?><?php echo zingiber_icon_svg('arrow'); ?></a>
        </div>
        <div class="zingiber-container zingiber-gallery__grid">
            <?php foreach ($home['featured_images'] as $index => $galleryImage) : ?>
                <figure class="zingiber-gallery__item zingiber-gallery__item--<?php echo esc_attr((string) ($index + 1)); ?>" data-zingiber-reveal>
                    <?php
                    zingiber_theme_image($galleryImage, [
                        'width' => 1536,
                        'height' => 1024,
                        'sizes' => '(min-width: 1025px) 40vw, 100vw',
                    ]);
                    ?>
                </figure>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="reservations" class="zingiber-reservations">
        <div class="zingiber-reservations__image" data-zingiber-reveal>
            <?php
            zingiber_theme_image($sections['reservation']['image'], [
                'width' => 2400,
                'height' => 1350,
                'sizes' => '(min-width: 1025px) 45vw, 100vw',
            ]);
            ?>
        </div>
        <div class="zingiber-reservations__copy" data-zingiber-reveal>
            <div class="zingiber-panel zingiber-panel--dark">
                <p class="zingiber-eyebrow"><?php echo esc_html($sections['reservation']['eyebrow']); ?></p>
                <h2>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('Your Table')); ?></span>
                    <span class="zingiber-heading-line"><?php echo esc_html(zingiber_prevent_widows('by the Coast')); ?></span>
                </h2>
                <p><?php echo esc_html(zingiber_prevent_widows($sections['reservation']['body'][0])); ?></p>
                <address>
                    <span><?php echo esc_html($contact['address_name']); ?></span>
                    <span><?php echo esc_html(zingiber_prevent_widows($contact['address'])); ?></span>
                    <?php if (!zingiber_is_pending_detail($contact['hours'] ?? '')) : ?>
                        <span><?php echo esc_html(zingiber_prevent_widows($contact['hours'])); ?></span>
                    <?php endif; ?>
                </address>
                <a class="zingiber-button zingiber-button--prominent zingiber-reservation-cta" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Plan Your Visit', 'vonaco'); ?></a>
            </div>
        </div>
    </section>
</main>
<?php
get_footer('zingiber');
