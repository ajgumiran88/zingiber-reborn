<?php
/**
 * Header for the Zingiber page experience.
 */

$navigationPages = zingiber_get_site_content();
$navigationOrder = ['home', 'about', 'menu', 'gallery', 'careers', 'contact'];
$navigationLabels = [
    'home' => 'Home',
    'about' => 'About',
    'menu' => 'Menu',
    'gallery' => 'Gallery',
    'careers' => 'Careers',
    'contact' => 'Contact',
];
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part('template-parts/zingiber-frame'); ?>
<a class="zingiber-skip-link" href="#zingiber-main"><?php esc_html_e('Skip to content', 'vonaco'); ?></a>

<header class="zingiber-header" data-zingiber-header>
    <div class="zingiber-container zingiber-header__inner">
        <a class="zingiber-logo-link" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php esc_attr_e('Zingiber home', 'vonaco'); ?>">
            <img
                class="zingiber-logo"
                src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-light.png')); ?>"
                alt="<?php esc_attr_e('Zingiber', 'vonaco'); ?>"
                width="536"
                height="356"
            >
        </a>

        <button
            class="zingiber-mobile-toggle"
            type="button"
            aria-controls="zingiber-primary-menu"
            aria-expanded="false"
            data-zingiber-menu-toggle
        >
            <span class="zingiber-mobile-toggle__label"><?php esc_html_e('Menu', 'vonaco'); ?></span>
            <span class="zingiber-mobile-toggle__icon" aria-hidden="true"><span></span><span></span></span>
        </button>

        <div class="zingiber-header__actions">
            <p class="zingiber-header__place"><?php esc_html_e('JLT · Dubai', 'vonaco'); ?></p>
            <nav class="zingiber-primary-nav" aria-label="Primary navigation" data-zingiber-menu>
                <?php if (has_nav_menu('primary')) : ?>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_id' => 'zingiber-primary-menu',
                        'menu_class' => 'zingiber-menu',
                        'depth' => 1,
                        'fallback_cb' => false,
                    ]);
                    ?>
                <?php else : ?>
                    <ul id="zingiber-primary-menu" class="zingiber-menu">
                        <?php foreach ($navigationOrder as $slug) : ?>
                            <?php if (!isset($navigationPages[$slug])) { continue; } ?>
                            <?php
                            $itemUrl = $slug === 'home' ? home_url('/') : home_url('/' . $slug . '/');
                            $isCurrent = ($slug === 'home' && is_front_page()) || is_page($slug);
                            ?>
                            <li class="menu-item<?php echo $isCurrent ? ' current-menu-item' : ''; ?>">
                                <a href="<?php echo esc_url($itemUrl); ?>">
                                    <?php echo esc_html($navigationLabels[$slug]); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </nav>

            <a class="zingiber-button zingiber-button--header" href="<?php echo esc_url(home_url('/contact/')); ?>">
                <?php esc_html_e('Reserve a Table', 'vonaco'); ?>
            </a>
        </div>
    </div>
</header>
