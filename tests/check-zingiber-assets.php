<?php
declare(strict_types=1);

$hooks = [];
$nativeTemplate = false;
$queuedStyles = [];

function add_action($hook, $callback, $priority = 10, ...$unused): void
{
    $GLOBALS['hooks'][$hook][$priority][] = $callback;
}
function add_filter(...$unused): void {}
function is_page_template($templates): bool { return $GLOBALS['nativeTemplate']; }
function wp_dequeue_style($handle): void { unset($GLOBALS['queuedStyles'][$handle]); }

require __DIR__ . '/../inc/zingiber/setup.php';

$check = static function (bool $condition, string $message): void {
    if (!$condition) {
        fwrite(STDERR, "FAIL: $message\n");
        exit(1);
    }
};

$cleanup = $hooks['wp_print_styles'][100] ?? [];
$check(count($cleanup) === 1, 'Native style cleanup must run before styles are printed.');
$styles = array_fill_keys(['base-desktop', 'base-mobile', 'elementor-frontend', 'zingiber-site'], true);
$queuedStyles = $styles;
$cleanup[0]();
$check($queuedStyles === $styles, 'Elementor pages must retain their styles.');

$nativeTemplate = true;
$cleanup[0]();
$check($queuedStyles === array_diff_key($styles, array_flip(['base-desktop', 'base-mobile'])),
    'Native pages must drop only the unused Elementor base styles.');

$header = file_get_contents(__DIR__ . '/../header-zingiber.php');
$check(!preg_match('/<link[^>]+rel="preload"[^>]+brand-contour-deboss-red/', $header),
    'The offscreen texture must not be preloaded.');

echo "Zingiber asset checks passed.\n";
