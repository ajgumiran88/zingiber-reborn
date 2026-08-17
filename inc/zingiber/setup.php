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
        return get_theme_file_uri(ltrim($path, '/'));
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

if (!function_exists('zingiber_enqueue_assets')) {
    function zingiber_enqueue_assets(): void
    {
        if (!zingiber_is_site_template()) {
            return;
        }

        $cssPath = get_theme_file_path('assets/css/zingiber.css');
        $jsPath = get_theme_file_path('assets/js/zingiber.js');

        wp_enqueue_style(
            'zingiber-fonts',
            'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Manrope:wght@400;500;600&display=swap',
            [],
            null
        );
        wp_enqueue_style(
            'zingiber-site',
            zingiber_theme_asset_url('assets/css/zingiber.css'),
            ['zingiber-fonts'],
            is_file($cssPath) ? (string) filemtime($cssPath) : null
        );
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
