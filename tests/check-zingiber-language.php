<?php
declare(strict_types=1);
$filters = [];
$siteTemplate = false;
function add_filter($name, $callback, ...$unused): void { $GLOBALS['filters'][$name] = $callback; }
function zingiber_is_site_template(): bool { return $GLOBALS['siteTemplate']; }
function zingiber_current_page_content(): array { return ['slug' => 'home']; }
if (is_file(__DIR__ . '/../inc/zingiber/language.php')) require __DIR__ . '/../inc/zingiber/language.php';
require __DIR__ . '/../inc/zingiber/content.php';
$english = zingiber_get_site_content();
$_GET['lang'] = 'ar';
$attributes = $filters['language_attributes'];
if ($attributes('lang="en-US"') !== 'lang="en-US"') {
    fwrite(STDERR, "FAIL: unrelated templates must keep their document language\n"); exit(1);
}
$siteTemplate = true;
if ($attributes('lang="en-US"') !== 'lang="ar" dir="rtl"') exit(1);
$arabic = zingiber_get_site_content();
if ($arabic['home']['hero']['heading'] === $english['home']['hero']['heading']) {
    fwrite(STDERR, "FAIL: Arabic selection must translate page content\n"); exit(1);
}
foreach ($english as $slug => $page) {
    if ($arabic[$slug]['slug'] !== $page['slug'] || $arabic[$slug]['template'] !== $page['template']) exit(1);
    if ($arabic[$slug]['hero']['heading'] === $page['hero']['heading']) exit(1);
}
if ($arabic['contact']['details']['email'] !== $english['contact']['details']['email']) exit(1);
foreach (['en', 'invalid', ['ar']] as $invalid) {
    $_GET['lang'] = $invalid;
    if (zingiber_get_site_content() !== $english) exit(1);
}
echo "PASS: Arabic content, stable routes/contact data, English fallback and malformed input\n";
