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
        style="--zingiber-hero-image: url('<?php echo esc_url(zingiber_theme_asset_url($home['hero']['image']['src'])); ?>');"
    >
        <div class="zingiber-hero__veil" aria-hidden="true"></div>
        <div class="zingiber-container zingiber-hero__content">
            <div class="zingiber-hero__intro" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php echo esc_html($home['hero']['eyebrow']); ?></p>
                <div class="zingiber-rule" aria-hidden="true"></div>
            </div>
            <h1 data-zingiber-reveal>
                <span class="zingiber-hero__title-line"><?php echo esc_html('Introducing Modern' . "\u{00A0}" . 'Indian'); ?></span>
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
                <img
                    src="<?php echo esc_url(zingiber_theme_asset_url($sections['chef_story']['image']['src'])); ?>"
                    alt="<?php echo esc_attr($sections['chef_story']['image']['alt']); ?>"
                    width="1536"
                    height="1024"
                    loading="lazy"
                >
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
                    </div>
                    <figure class="zingiber-region__image">
                        <img
                            src="<?php echo esc_url(zingiber_theme_asset_url($region['image']['src'])); ?>"
                            alt="<?php echo esc_attr($region['image']['alt']); ?>"
                            width="1536"
                            height="1024"
                            loading="lazy"
                        >
                    </figure>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="menu" class="zingiber-section zingiber-menu-story">
        <div class="zingiber-container zingiber-menu-story__grid">
            <div class="zingiber-menu-story__images" data-zingiber-reveal>
                <img
                    class="zingiber-menu-story__image zingiber-menu-story__image--wide"
                    src="<?php echo esc_url(zingiber_theme_asset_url($menuPage['hero']['image']['src'])); ?>"
                    alt="<?php echo esc_attr($menuPage['hero']['image']['alt']); ?>"
                    width="1672"
                    height="941"
                    loading="lazy"
                >
                <img
                    class="zingiber-menu-story__image zingiber-menu-story__image--detail"
                    src="<?php echo esc_url(zingiber_theme_asset_url($home['featured_images'][3]['src'])); ?>"
                    alt="<?php echo esc_attr($home['featured_images'][3]['alt']); ?>"
                    width="1536"
                    height="1024"
                    loading="lazy"
                >
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
            <img
                src="<?php echo esc_url(zingiber_theme_asset_url($sections['experience']['image']['src'])); ?>"
                alt="<?php echo esc_attr($sections['experience']['image']['alt']); ?>"
                width="2400"
                height="1350"
                loading="lazy"
            >
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
                    <img
                        src="<?php echo esc_url(zingiber_theme_asset_url($galleryImage['src'])); ?>"
                        alt="<?php echo esc_attr($galleryImage['alt']); ?>"
                        width="1536"
                        height="1024"
                        loading="lazy"
                    >
                </figure>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="reservations" class="zingiber-reservations">
        <div class="zingiber-reservations__image" data-zingiber-reveal>
            <img
                src="<?php echo esc_url(zingiber_theme_asset_url($sections['reservation']['image']['src'])); ?>"
                alt="<?php echo esc_attr($sections['reservation']['image']['alt']); ?>"
                width="2400"
                height="1350"
                loading="lazy"
            >
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
                    <span class="zingiber-operational-placeholder"><?php echo esc_html(zingiber_prevent_widows($contact['hours'])); ?></span>
                </address>
                <a class="zingiber-button zingiber-button--prominent zingiber-reservation-cta" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Plan Your Visit', 'vonaco'); ?></a>
            </div>
        </div>
    </section>
</main>
<?php
get_footer('zingiber');
