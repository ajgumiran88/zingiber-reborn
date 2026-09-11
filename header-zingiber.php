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
    <link rel="preload" as="image" href="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-dark.png')); ?>">
    <style id="zingiber-preloader-critical">
        html.zingiber-preload{overflow:hidden}
        .zingiber-preloader{position:fixed;inset:0;z-index:100000;display:grid;place-items:center;background:#0F0F0F;color:#EDE7E1}
        .zingiber-preloader__inner{display:flex;flex-direction:column;align-items:center;gap:1.75rem;padding:1.5rem}
        .zingiber-preloader__logo{width:min(22rem,78vw);height:auto;opacity:0;transform:scale(0.92);animation:zingiber-preloader-logo 900ms cubic-bezier(0.16,1,0.3,1) 120ms forwards}
        .zingiber-preloader__progress{width:min(22rem,78vw);height:1px;overflow:hidden;background:rgba(184,115,51,0.22)}
        .zingiber-preloader__progress-bar{display:block;width:0;height:100%;background:#B87333;transform-origin:left center}
        @keyframes zingiber-preloader-logo{to{opacity:1;transform:scale(1)}}
        @media (prefers-reduced-motion:reduce){.zingiber-preloader__logo{opacity:1;transform:none;animation:none}}
    </style>
    <script>document.documentElement.classList.add('zingiber-preload');</script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div
    class="zingiber-preloader"
    data-zingiber-preloader
    role="status"
    aria-live="polite"
    aria-busy="true"
>
    <div class="zingiber-preloader__inner">
        <img
            class="zingiber-preloader__logo"
            src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-dark.png')); ?>"
            alt="<?php esc_attr_e('Zingiber', 'vonaco'); ?>"
            width="1400"
            height="235"
            decoding="async"
        >
        <div
            class="zingiber-preloader__progress"
            role="progressbar"
            aria-valuemin="0"
            aria-valuemax="100"
            aria-valuenow="0"
            aria-label="<?php esc_attr_e('Loading', 'vonaco'); ?>"
            data-zingiber-preloader-progress
        >
            <span class="zingiber-preloader__progress-bar" data-zingiber-preloader-bar></span>
        </div>
        <span class="zingiber-preloader__sr"><?php esc_html_e('Loading Zingiber', 'vonaco'); ?></span>
    </div>
</div>
<?php get_template_part('template-parts/zingiber-frame'); ?>
<a class="zingiber-skip-link" href="#zingiber-main"><?php esc_html_e('Skip to content', 'vonaco'); ?></a>

<header class="zingiber-header" data-zingiber-header>
    <div class="zingiber-container zingiber-header__inner">
        <a class="zingiber-logo-link" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php esc_attr_e('Zingiber home', 'vonaco'); ?>">
            <span class="zingiber-logo-stack">
                <img
                    class="zingiber-logo zingiber-logo--light"
                    src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-light.png')); ?>"
                    alt="<?php esc_attr_e('Zingiber', 'vonaco'); ?>"
                    width="1400"
                    height="235"
                >
                <img
                    class="zingiber-logo zingiber-logo--dark"
                    src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-dark.png')); ?>"
                    alt=""
                    width="1400"
                    height="235"
                    aria-hidden="true"
                >
            </span>
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
