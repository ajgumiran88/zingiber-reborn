<?php

/**
 * Zingiber-specific WordPress setup.
 *
 * The parent Vonaco compatibility layer remains untouched; this module owns
 * only the custom Zingiber page experience and its packaged assets.
 */

require_once __DIR__ . '/content.php';

if (!function_exists('zingiber_theme_asset_url')) {
    function zingiber_theme_asset_url(string $path): string
    {
        $relative = ltrim($path, '/');
        $url = get_theme_file_uri($relative);
        $file = get_theme_file_path($relative);

        // InfinityFree caches theme assets for up to a year; bust on file change.
        if (is_file($file)) {
            $url = add_query_arg('v', (string) filemtime($file), $url);
        }

        return $url;
    }
}

if (!function_exists('zingiber_icon_svg')) {
    function zingiber_icon_svg(string $name): string
    {
        $icons = [
            'arrow' => '<svg class="zingiber-icon zingiber-icon--arrow" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true" focusable="false"><path d="M3 11 11 3m0 0H5.5M11 3v5.5" stroke="currentColor" stroke-width="1.15" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'scroll' => '<svg class="zingiber-icon zingiber-icon--scroll" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true" focusable="false"><path d="M7 2v9M3.5 8.5 7 12l3.5-3.5" stroke="currentColor" stroke-width="1.15" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ];

        return $icons[$name] ?? '';
    }
}

if (!function_exists('zingiber_is_site_template')) {
    function zingiber_is_site_template(): bool
    {
        return is_page_template([
            'template-zingiber-home.php',
            'template-zingiber-page.php',
        ]);
    }
}

if (!function_exists('zingiber_current_page_content')) {
    function zingiber_current_page_content(): array
    {
        $content = zingiber_get_site_content();
        $slug = is_front_page() ? 'home' : get_post_field('post_name', get_queried_object_id());

        if (is_string($slug) && isset($content[$slug])) {
            return $content[$slug];
        }

        return $content['home'];
    }
}

if (!function_exists('zingiber_licensed_font_faces')) {
    function zingiber_licensed_font_faces(): string
    {
        $dir = get_theme_file_path('assets/fonts/zingiber');
        if (!is_dir($dir)) {
            return '';
        }

        $faces = [];
        $files = array_merge(
            glob($dir . '/*.woff2') ?: [],
            glob($dir . '/*.woff') ?: [],
            glob($dir . '/*.ttf') ?: [],
            glob($dir . '/*.otf') ?: []
        );

        foreach ($files as $file) {
            $basename = basename($file);
            $name = strtolower($basename);
            $family = null;

            if (strpos($name, 'estratto') !== false) {
                $family = 'Estratto Var';
            } elseif (strpos($name, 'luxora') !== false) {
                $family = 'Luxora Grotesk';
            }

            if ($family === null) {
                continue;
            }

            $weight = '400';
            if (strpos($name, 'thin') !== false) {
                $weight = '100';
            } elseif (strpos($name, 'light') !== false) {
                $weight = '300';
            } elseif (strpos($name, 'book') !== false || strpos($name, 'regular') !== false) {
                $weight = '400';
            } elseif (strpos($name, 'medium') !== false) {
                $weight = '500';
            } elseif (strpos($name, 'semibold') !== false || strpos($name, 'semi') !== false) {
                $weight = '600';
            } elseif (strpos($name, 'bold') !== false) {
                $weight = '700';
            } elseif (strpos($name, 'heavy') !== false || strpos($name, 'black') !== false) {
                $weight = '800';
            }

            $style = (strpos($name, 'italic') !== false) ? 'italic' : 'normal';
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $format = [
                'woff2' => 'woff2',
                'woff' => 'woff',
                'ttf' => 'truetype',
                'otf' => 'opentype',
            ][$ext] ?? 'woff2';

            $url = zingiber_theme_asset_url('assets/fonts/zingiber/' . $basename);
            $faces[] = sprintf(
                '@font-face{font-family:"%s";src:url("%s") format("%s");font-weight:%s;font-style:%s;font-display:swap;}',
                $family,
                esc_url($url),
                $format,
                $weight,
                $style
            );
        }

        return implode('', $faces);
    }
}

if (!function_exists('zingiber_enqueue_assets')) {
    function zingiber_enqueue_assets(): void
    {
        if (!zingiber_is_site_template()) {
            return;
        }

        $cssPath = get_theme_file_path('assets/css/zingiber.css');
        $jsPath = get_theme_file_path('assets/js/zingiber.js');
        $licensedFaces = zingiber_licensed_font_faces();

        wp_enqueue_style(
            'zingiber-fonts',
            'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Manrope:wght@300;400;500;600;700&display=swap',
            [],
            null
        );
        wp_enqueue_style(
            'zingiber-site',
            zingiber_theme_asset_url('assets/css/zingiber.css'),
            ['zingiber-fonts'],
            is_file($cssPath) ? (string) filemtime($cssPath) : null
        );

        if ($licensedFaces !== '') {
            wp_add_inline_style('zingiber-site', $licensedFaces);
        }
        wp_enqueue_script(
            'zingiber-site',
            zingiber_theme_asset_url('assets/js/zingiber.js'),
            [],
            is_file($jsPath) ? (string) filemtime($jsPath) : null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'zingiber_enqueue_assets', 30);

if (!function_exists('zingiber_body_classes')) {
    function zingiber_body_classes(array $classes): array
    {
        if (!zingiber_is_site_template()) {
            return $classes;
        }

        $page = zingiber_current_page_content();
        $classes[] = 'zingiber-site';
        $classes[] = 'zingiber-page-' . sanitize_html_class($page['slug'] ?? 'home');

        return array_values(array_unique($classes));
    }
}
add_filter('body_class', 'zingiber_body_classes');

if (!function_exists('zingiber_install_site_pages')) {
    function zingiber_install_site_pages(): void
    {
        $content = zingiber_get_site_content();
        $pageOrder = ['home', 'about', 'menu', 'gallery', 'careers', 'contact'];
        $pageIds = [];

        foreach ($pageOrder as $slug) {
            if (!isset($content[$slug])) {
                continue;
            }

            $page = get_page_by_path($slug, OBJECT, 'page');
            if (!$page instanceof WP_Post) {
                $pageId = wp_insert_post([
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'post_name' => $slug,
                    'post_title' => $content[$slug]['title'],
                    'post_content' => '',
                    'menu_order' => array_search($slug, $pageOrder, true),
                ]);
                if (is_wp_error($pageId)) {
                    continue;
                }
            } else {
                $pageId = $page->ID;
            }

            $pageId = (int) $pageId;
            update_post_meta($pageId, '_wp_page_template', $content[$slug]['template']);
            $pageIds[$slug] = $pageId;
        }

        if (isset($pageIds['home'])) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $pageIds['home']);
        }

        $menu = wp_get_nav_menu_object('Zingiber Primary');
        $menuId = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu('Zingiber Primary');
        if ($menuId <= 0) {
            return;
        }

        $existingItems = wp_get_nav_menu_items($menuId) ?: [];
        $linkedPageIds = [];
        foreach ($existingItems as $item) {
            $linkedPageIds[] = (int) $item->object_id;
        }

        foreach ($pageOrder as $position => $slug) {
            if (!isset($pageIds[$slug]) || in_array($pageIds[$slug], $linkedPageIds, true)) {
                continue;
            }

            wp_update_nav_menu_item($menuId, 0, [
                'menu-item-object-id' => $pageIds[$slug],
                'menu-item-object' => 'page',
                'menu-item-position' => $position + 1,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish',
            ]);
        }

        $locations = get_theme_mod('nav_menu_locations', []);
        foreach (array_keys(get_registered_nav_menus()) as $location) {
            if (empty($locations[$location])) {
                $locations[$location] = $menuId;
                set_theme_mod('nav_menu_locations', $locations);
                break;
            }
        }
    }
}
add_action('after_switch_theme', 'zingiber_install_site_pages');

if (!function_exists('zingiber_filter_document_title')) {
    function zingiber_filter_document_title(array $parts): array
    {
        if (!zingiber_is_site_template()) {
            return $parts;
        }

        $page = zingiber_current_page_content();

        return ['title' => $page['seo_title']];
    }
}
add_filter('document_title_parts', 'zingiber_filter_document_title', 20);

if (!function_exists('zingiber_output_meta_description')) {
    function zingiber_output_meta_description(): void
    {
        if (!zingiber_is_site_template()) {
            return;
        }

        if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION')) {
            return;
        }

        $page = zingiber_current_page_content();
        if (empty($page['meta_description'])) {
            return;
        }

        echo '<meta name="description" content="' . esc_attr($page['meta_description']) . '">' . "\n";
    }
}
add_action('wp_head', 'zingiber_output_meta_description', 2);
