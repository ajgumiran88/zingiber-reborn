<?php
/** Draft Arabic copy stays local; language is explicit in each public URL. */
function zingiber_is_arabic(): bool
{
    return ($_GET['lang'] ?? '') === 'ar' && (!function_exists('is_admin') || !is_admin());
}

function zingiber_page_url(string $path = '/'): string
{
    $url = home_url($path);
    return zingiber_is_arabic() ? add_query_arg('lang', 'ar', $url) : $url;
}

function zingiber_localize_content(array $content): array
{
    if (!zingiber_is_arabic()) return $content;
    $content = array_replace_recursive($content, require __DIR__ . '/content-ar.php');
    foreach ($content as &$page) {
        $page['seo_title'] = $page['hero']['heading'] . ' | زنجيبر دبي';
        $page['meta_description'] = $page['hero']['subheading'];
    }
    unset($page);
    $alts = [
        'interior-hero.jpg' => 'الأجواء الدافئة والتصميم المعاصر في مطعم زنجيبر بدبي',
        'interior-dining-room.jpg' => 'جلسات أنيقة في قاعة الطعام بمطعم زنجيبر',
        'interior-feature-wall.jpg' => 'جدار بتفاصيل ساحلية وإضاءة مميزة داخل زنجيبر',
        'interior-ambience-bar.jpg' => 'إضاءة مسائية دافئة في قاعة الطعام والبار',
        'interior-bar.jpg' => 'البار وأجواء الطعام الدافئة في زنجيبر',
        'food-signature-plate.jpg' => 'طبق مميز من مطبخ زنجيبر بتقديم معاصر',
        'food-prawn-curry.jpg' => 'كاري الروبيان المستوحى من الساحل الهندي',
        'food-seafood-rice.jpg' => 'أرز بالمأكولات البحرية وتزيين أنيق',
        'food-grilled-lamb.jpg' => 'لحم ضأن مشوي بنكهات ساحلية هندية',
        'food-coastal-thali.jpg' => 'تشكيلة تالي مستوحاة من مناطق الهند الساحلية',
        'food-dessert.jpg' => 'حلوى مقدمة بعناية وأناقة في زنجيبر',
    ];
    $translateImages = static function (array &$items) use (&$translateImages, $alts): void {
        if (isset($items['src'], $items['alt'])) $items['alt'] = $alts[basename($items['src'])] ?? $items['alt'];
        foreach ($items as &$item) if (is_array($item)) $translateImages($item);
    };
    $translateImages($content);
    return $content;
}

function zingiber_translate_ui(string $translation, string $text, string $domain): string
{
    if ($domain !== 'vonaco' || !zingiber_is_arabic()) return $translation;
    static $copy;
    $copy ??= require __DIR__ . '/ui-ar.php';
    return $copy[$text] ?? $translation;
}

if (function_exists('add_filter')) {
    add_filter('gettext', 'zingiber_translate_ui', 10, 3);
    add_filter('language_attributes', static function (string $attributes): string {
        return zingiber_is_arabic() && function_exists('zingiber_is_site_template') && zingiber_is_site_template()
            ? 'lang="ar" dir="rtl"' : $attributes;
    });
}
