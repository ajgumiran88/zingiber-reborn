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
    <link rel="preload" as="image" href="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-light.png')); ?>">
    <link rel="preload" as="image" href="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/brand-contour-deboss-red.webp')); ?>">
    <?php
    $zingiberHeroAsset = $navigationPages['home']['hero']['image']['src'] ?? '';
    if (function_exists('zingiber_current_page_content')) {
        $zingiberCurrentPage = zingiber_current_page_content();
        if (!empty($zingiberCurrentPage['hero']['image']['src'])) {
            $zingiberHeroAsset = $zingiberCurrentPage['hero']['image']['src'];
        }
    }
    $zingiberHeroWebp = preg_replace('/\.(jpe?g|png)$/i', '.webp', (string) $zingiberHeroAsset);
    $zingiberHeroPreload = (is_string($zingiberHeroWebp) && $zingiberHeroWebp !== $zingiberHeroAsset && is_file(get_theme_file_path(ltrim($zingiberHeroWebp, '/'))))
        ? $zingiberHeroWebp
        : $zingiberHeroAsset;
    ?>
    <link rel="preload" as="image" href="<?php echo esc_url(zingiber_theme_asset_url($zingiberHeroPreload)); ?>" fetchpriority="high">
    <style id="zingiber-preloader-critical">
        html.zingiber-preload{overflow:hidden}
        html.zingiber-intro-skip .zingiber-preloader{display:none}
        .zingiber-preloader{position:fixed;inset:0;z-index:100000;display:grid;place-items:center;isolation:isolate;overflow:hidden;background-color:#701616;background-image:radial-gradient(ellipse at 50% 42%,rgba(112,22,22,.08),transparent 62%),radial-gradient(ellipse at 50% 100%,rgba(15,15,15,.28),transparent 58%),linear-gradient(180deg,rgba(15,15,15,.06),rgba(15,15,15,.18)),url('<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/brand-contour-deboss-red.webp')); ?>');background-position:center,center,center,center;background-repeat:no-repeat;background-size:auto,auto,auto,cover;color:#B87333}
        .zingiber-preloader::after{position:absolute;inset:clamp(14px,2.2vw,24px);border:1px solid rgba(184,115,51,.42);content:"";pointer-events:none}
        .zingiber-preloader__inner{display:flex;flex-direction:column;align-items:center;gap:1.75rem;padding:1.5rem}
        .zingiber-preloader__logo{display:block;width:min(22rem,78vw);aspect-ratio:1400/235;background-color:#B87333;-webkit-mask:url('<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-light.png')); ?>') center/contain no-repeat;mask:url('<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-light.png')); ?>') center/contain no-repeat;-webkit-mask-size:contain;mask-size:contain;opacity:0;transform:translateZ(0) scale(0.92);animation:zingiber-preloader-logo 900ms cubic-bezier(0.16,1,0.3,1) 120ms forwards}
        .zingiber-preloader__progress{width:min(18rem,68vw);height:2px;overflow:hidden;background:rgba(184,115,51,.28)}
        .zingiber-preloader__progress-bar{display:block;width:0;height:100%;background:#B87333;transform-origin:left center}
        @keyframes zingiber-preloader-logo{to{opacity:1;transform:scale(1)}}
        @media (prefers-reduced-motion:reduce){.zingiber-preloader__logo{opacity:1;transform:none;animation:none}}
    </style>
    <script>
    /*
     * The curtain is a first-impression device, not a page transition. It arms
     * on the first view of a session; every later tab click inside that session
     * paints straight through. sessionStorage (not localStorage) keeps it
     * per-visit, so a returning guest still gets the full opening.
     *
     * This runs before the body so the curtain never flashes on a skipped view.
     */
    (function () {
        var seen = false;
        try { seen = window.sessionStorage.getItem('zingiber-intro-seen') === '1'; } catch (e) {}
        if (seen) {
            document.documentElement.classList.add('zingiber-intro-skip');
            return;
        }
        document.documentElement.classList.add('zingiber-preload');
    }());
    </script>
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
        <span
            class="zingiber-preloader__logo"
            role="img"
            aria-label="<?php esc_attr_e('Zingiber', 'vonaco'); ?>"
        ></span>
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
        <a class="zingiber-book-link" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Book Now', 'vonaco'); ?></a>
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
            class="zingiber-menu-toggle"
            type="button"
            aria-controls="zingiber-navigation"
            aria-expanded="false"
            aria-label="<?php esc_attr_e('Open navigation', 'vonaco'); ?>"
            data-zingiber-menu-toggle
        >
            <span class="zingiber-menu-toggle__label"><?php esc_html_e('Menu', 'vonaco'); ?></span>
            <span class="zingiber-menu-toggle__icon" aria-hidden="true"><span></span><span></span></span>
        </button>

    </div>
</header>

<dialog id="zingiber-navigation" class="zingiber-navigation" aria-label="<?php esc_attr_e('Navigation menu', 'vonaco'); ?>" data-zingiber-menu>
    <div class="zingiber-navigation__masthead zingiber-container">
        <a class="zingiber-book-link" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Book Now', 'vonaco'); ?></a>
        <a class="zingiber-navigation__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Zingiber home', 'vonaco'); ?>">
            <img src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/zingiber-logo-dark.png')); ?>" alt="Zingiber" width="1400" height="235">
        </a>
        <button class="zingiber-menu-toggle" type="button" aria-label="<?php esc_attr_e('Close navigation', 'vonaco'); ?>" data-zingiber-menu-close autofocus>
            <span class="zingiber-menu-toggle__label"><?php esc_html_e('Close', 'vonaco'); ?></span>
            <span class="zingiber-menu-toggle__cross" aria-hidden="true"></span>
        </button>
    </div>
    <nav class="zingiber-navigation__links" aria-label="Primary navigation">
        <ul id="zingiber-primary-menu">
            <?php foreach ($navigationOrder as $slug) : ?>
                <?php if (!isset($navigationPages[$slug])) { continue; } ?>
                <?php $isCurrent = ($slug === 'home' && is_front_page()) || is_page($slug); ?>
                <li>
                    <a href="<?php echo esc_url($slug === 'home' ? home_url('/') : home_url('/' . $slug . '/')); ?>"<?php echo $isCurrent ? ' aria-current="page"' : ''; ?>><?php echo esc_html($navigationLabels[$slug]); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a class="zingiber-navigation__social" href="https://www.instagram.com/zingiberdubai/" target="_blank" rel="noopener noreferrer">Instagram <?php echo zingiber_icon_svg('arrow'); ?></a>
        <figure class="zingiber-navigation__image" aria-hidden="true">
            <?php zingiber_theme_image($navigationPages['home']['sections']['chef_story']['image'], ['width' => 1536, 'height' => 1024, 'sizes' => '(min-width: 768px) 42rem, 90vw']); ?>
        </figure>
    </nav>
</dialog>
