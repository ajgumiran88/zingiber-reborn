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
            <p class="zingiber-eyebrow" data-zingiber-reveal><?php echo esc_html($home['hero']['eyebrow']); ?></p>
            <h1 data-zingiber-reveal><?php echo esc_html($home['hero']['heading']); ?></h1>
            <p class="zingiber-hero__lede" data-zingiber-reveal><?php echo esc_html($home['hero']['subheading']); ?></p>
            <div class="zingiber-hero__actions" data-zingiber-reveal>
                <a class="zingiber-button" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php echo esc_html($home['calls_to_action']['primary']['label']); ?></a>
                <a class="zingiber-text-link" href="<?php echo esc_url(home_url('/menu/')); ?>"><?php echo esc_html($home['calls_to_action']['secondary']['label']); ?><span aria-hidden="true">↗</span></a>
            </div>
        </div>
        <a class="zingiber-hero__scroll" href="#chef"><span><?php esc_html_e('Discover Zingiber', 'vonaco'); ?></span><span aria-hidden="true">↓</span></a>
    </section>

    <section id="chef" class="zingiber-section zingiber-chef">
        <div class="zingiber-container zingiber-chef__grid">
            <div class="zingiber-chef__copy" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php echo esc_html($sections['chef_story']['eyebrow']); ?></p>
                <h2><?php echo esc_html($sections['chef_story']['heading']); ?></h2>
                <?php foreach ($sections['chef_story']['body'] as $paragraph) : ?>
                    <p><?php echo esc_html($paragraph); ?></p>
                <?php endforeach; ?>
                <a class="zingiber-text-link zingiber-text-link--dark" href="<?php echo esc_url(home_url('/about/')); ?>"><?php echo esc_html($home['calls_to_action']['story']['label']); ?><span aria-hidden="true">↗</span></a>
            </div>
            <figure class="zingiber-chef__figure" data-zingiber-reveal>
                <img
                    src="<?php echo esc_url(zingiber_theme_asset_url($sections['chef_story']['image']['src'])); ?>"
                    alt="<?php echo esc_attr($sections['chef_story']['image']['alt']); ?>"
                    width="1536"
                    height="1024"
                    loading="lazy"
                >
                <figcaption><?php esc_html_e('A contemporary expression of India’s coast.', 'vonaco'); ?></figcaption>
            </figure>
        </div>
    </section>

    <section id="principles" class="zingiber-principles" aria-labelledby="principles-title">
        <div class="zingiber-container">
            <div class="zingiber-principles__heading" data-zingiber-reveal>
                <p class="zingiber-eyebrow"><?php esc_html_e('What Guides Us', 'vonaco'); ?></p>
                <h2 id="principles-title"><?php esc_html_e('Four Principles. One Point of View.', 'vonaco'); ?></h2>
            </div>
            <ol class="zingiber-principles__list">
                <?php foreach ($home['principles'] as $index => $principle) : ?>
                    <li data-zingiber-reveal>
                        <span><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                        <strong><?php echo esc_html($principle['title']); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>

    <section id="coastal-regions" class="zingiber-section zingiber-coast">
        <div class="zingiber-container zingiber-coast__intro" data-zingiber-reveal>
            <p class="zingiber-eyebrow"><?php echo esc_html($sections['coastal_expression']['eyebrow']); ?></p>
            <h2><?php echo esc_html($sections['coastal_expression']['heading']); ?></h2>
            <?php foreach ($sections['coastal_expression']['body'] as $paragraph) : ?>
                <p><?php echo esc_html($paragraph); ?></p>
            <?php endforeach; ?>
        </div>
        <div class="zingiber-container zingiber-coast__composition">
            <figure class="zingiber-coast__image zingiber-coast__image--primary" data-zingiber-reveal>
                <img
                    src="<?php echo esc_url(zingiber_theme_asset_url($sections['coastal_expression']['image']['src'])); ?>"
                    alt="<?php echo esc_attr($sections['coastal_expression']['image']['alt']); ?>"
                    width="1024"
                    height="1536"
                    loading="lazy"
                >
            </figure>
            <ol class="zingiber-coast__regions" aria-label="<?php esc_attr_e('Coastal regions that inspire the menu', 'vonaco'); ?>">
                <?php foreach ($home['regions'] as $index => $region) : ?>
                    <li data-zingiber-reveal><span><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span><?php echo esc_html($region['name']); ?></li>
                <?php endforeach; ?>
            </ol>
            <figure class="zingiber-coast__image zingiber-coast__image--secondary" data-zingiber-reveal>
                <img
                    src="<?php echo esc_url(zingiber_theme_asset_url($home['featured_images'][0]['src'])); ?>"
                    alt="<?php echo esc_attr($home['featured_images'][0]['alt']); ?>"
                    width="1536"
                    height="1024"
                    loading="lazy"
                >
            </figure>
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
                <h2><?php echo esc_html($menuPage['hero']['heading']); ?></h2>
                <?php foreach ($menuPage['sections'][0]['body'] as $paragraph) : ?>
                    <p><?php echo esc_html($paragraph); ?></p>
                <?php endforeach; ?>
                <a class="zingiber-button zingiber-button--dark" href="<?php echo esc_url(home_url('/menu/')); ?>"><?php esc_html_e('Explore the Menu', 'vonaco'); ?></a>
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
            <p class="zingiber-eyebrow"><?php echo esc_html($sections['experience']['eyebrow']); ?></p>
            <h2><?php echo esc_html($sections['experience']['heading']); ?></h2>
            <?php foreach ($sections['experience']['body'] as $paragraph) : ?>
                <p><?php echo esc_html($paragraph); ?></p>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="gallery" class="zingiber-section zingiber-gallery" aria-labelledby="gallery-title">
        <div class="zingiber-container zingiber-gallery__heading" data-zingiber-reveal>
            <div>
                <p class="zingiber-eyebrow"><?php echo esc_html($siteContent['gallery']['hero']['eyebrow']); ?></p>
                <h2 id="gallery-title"><?php echo esc_html($siteContent['gallery']['hero']['heading']); ?></h2>
            </div>
            <a class="zingiber-text-link zingiber-text-link--dark" href="<?php echo esc_url(home_url('/gallery/')); ?>"><?php esc_html_e('View the Gallery', 'vonaco'); ?><span aria-hidden="true">↗</span></a>
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
            <p class="zingiber-eyebrow"><?php echo esc_html($sections['reservation']['eyebrow']); ?></p>
            <h2><?php echo esc_html($sections['reservation']['heading']); ?></h2>
            <p><?php echo esc_html($sections['reservation']['body'][0]); ?></p>
            <address>
                <span><?php echo esc_html($contact['address_name']); ?></span>
                <span><?php echo esc_html($contact['address']); ?></span>
                <span><?php echo esc_html($contact['hours']); ?></span>
            </address>
            <a class="zingiber-button zingiber-reservation-cta" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Plan Your Visit', 'vonaco'); ?></a>
        </div>
    </section>
</main>
<?php
get_footer('zingiber');
