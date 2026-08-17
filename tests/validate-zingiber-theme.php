<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$fixture = require __DIR__ . '/fixtures/required-copy.php';
$availableGroups = ['content', 'assets', 'templates', 'brand', 'import', 'placeholders'];
$selectedGroup = null;

foreach ($argv as $argument) {
    if (strpos($argument, '--group=') === 0) {
        $selectedGroup = substr($argument, strlen('--group='));
    }
}

if ($selectedGroup !== null && !in_array($selectedGroup, $availableGroups, true)) {
    fwrite(STDERR, sprintf("Unknown validation group: %s\n", $selectedGroup));
    exit(2);
}

$groups = $selectedGroup === null ? $availableGroups : [$selectedGroup];
$failures = [];

$fail = static function (string $group, string $message) use (&$failures): void {
    $failures[] = sprintf('[FAIL] %s: %s', $group, $message);
};

$read = static function (string $relativePath) use ($root): ?string {
    $path = $root . '/' . ltrim($relativePath, '/');

    if (!is_file($path)) {
        return null;
    }

    $contents = file_get_contents($path);

    return $contents === false ? null : $contents;
};

$flattenStrings = static function ($value) use (&$flattenStrings): array {
    if (is_string($value)) {
        return [$value];
    }

    if (!is_array($value)) {
        return [];
    }

    $strings = [];
    foreach ($value as $item) {
        $strings = array_merge($strings, $flattenStrings($item));
    }

    return $strings;
};

$collectImages = static function ($value) use (&$collectImages): array {
    if (!is_array($value)) {
        return [];
    }

    $images = [];
    if (isset($value['src']) && is_string($value['src']) && strpos($value['src'], 'assets/images/zingiber/') === 0) {
        $images[] = [
            'src' => $value['src'],
            'alt' => isset($value['alt']) && is_string($value['alt']) ? trim($value['alt']) : '',
        ];
    }

    foreach ($value as $item) {
        if (is_array($item)) {
            $images = array_merge($images, $collectImages($item));
        }
    }

    return $images;
};

$content = null;
$contentPath = $root . '/inc/zingiber/content.php';
if (is_file($contentPath)) {
    $loadedContent = require $contentPath;
    if (is_array($loadedContent)) {
        $content = $loadedContent;
    }
}

if (in_array('content', $groups, true)) {
    if ($content === null) {
        $fail('content', 'inc/zingiber/content.php is missing or does not return an array.');
    } else {
        foreach ($fixture['pages'] as $slug) {
            if (!isset($content[$slug]) || !is_array($content[$slug])) {
                $fail('content', sprintf('Missing required page content for "%s".', $slug));
                continue;
            }

            foreach (['slug', 'title', 'seo_title', 'meta_description', 'template', 'hero', 'sections'] as $key) {
                if (!array_key_exists($key, $content[$slug])) {
                    $fail('content', sprintf('Page "%s" is missing the "%s" field.', $slug, $key));
                }
            }
        }

        foreach (['principles', 'regions', 'featured_images', 'calls_to_action'] as $homeKey) {
            if (!isset($content['home'][$homeKey])) {
                $fail('content', sprintf('Home content is missing the "%s" collection.', $homeKey));
            }
        }

        $allCopy = implode("\n", $flattenStrings($content));
        foreach ($fixture['phrases'] as $phrase) {
            if (strpos($allCopy, $phrase) === false) {
                $fail('content', sprintf('Required approved phrase not found: "%s".', $phrase));
            }
        }

        if (strpos($allCopy, '+971 XX XXX XXXX') !== false) {
            $fail('content', 'The incomplete phone placeholder must not appear as public contact data.');
        }

        if (strpos($allCopy, 'Phone details coming soon') === false) {
            $fail('content', 'The editable phone status label is missing.');
        }

        foreach ($collectImages($content) as $image) {
            if ($image['alt'] === '') {
                $fail('content', sprintf('Image "%s" is missing meaningful alt text.', $image['src']));
            }
        }
    }
}

if (in_array('assets', $groups, true)) {
    $requiredAssets = [
        'assets/images/zingiber/zingiber-logo-dark.png',
        'assets/images/zingiber/zingiber-logo-light.png',
        'assets/images/zingiber/zingiber-mark.png',
        'assets/images/zingiber/interior-hero.jpg',
        'assets/images/zingiber/interior-dining-room.jpg',
        'assets/images/zingiber/interior-bar.jpg',
        'assets/images/zingiber/interior-feature-wall.jpg',
        'assets/images/zingiber/food-prawn-curry.jpg',
        'assets/images/zingiber/food-grilled-lamb.jpg',
        'assets/images/zingiber/food-coastal-thali.jpg',
        'assets/images/zingiber/food-seafood-rice.jpg',
        'assets/images/zingiber/food-dessert.jpg',
        'assets/images/zingiber/food-signature-plate.jpg',
    ];

    if ($content !== null) {
        foreach ($collectImages($content) as $image) {
            $requiredAssets[] = $image['src'];
        }
    }

    foreach (array_values(array_unique($requiredAssets)) as $relativePath) {
        $path = $root . '/' . $relativePath;
        if (!is_file($path)) {
            $fail('assets', sprintf('Missing production asset: %s.', $relativePath));
            continue;
        }

        $dimensions = @getimagesize($path);
        if ($dimensions === false) {
            $fail('assets', sprintf('Unreadable image asset: %s.', $relativePath));
            continue;
        }

        if (max((int) $dimensions[0], (int) $dimensions[1]) > 2400) {
            $fail('assets', sprintf('Image exceeds 2400px long edge: %s.', $relativePath));
        }
    }
}

if (in_array('templates', $groups, true)) {
    $requiredFiles = [
        'inc/zingiber/setup.php',
        'header-zingiber.php',
        'footer-zingiber.php',
        'template-zingiber-home.php',
        'template-zingiber-page.php',
        'assets/css/zingiber.css',
        'assets/js/zingiber.js',
    ];

    foreach ($requiredFiles as $relativePath) {
        if (!is_file($root . '/' . $relativePath)) {
            $fail('templates', sprintf('Missing required theme file: %s.', $relativePath));
        }
    }

    $header = $read('header-zingiber.php');
    if ($header !== null) {
        foreach (['<header', '<nav', 'aria-label="Primary navigation"', 'data-zingiber-menu-toggle', 'wp_head()', 'wp_body_open()'] as $contract) {
            if (strpos($header, $contract) === false) {
                $fail('templates', sprintf('Header contract missing: %s.', $contract));
            }
        }
    }

    $footer = $read('footer-zingiber.php');
    if ($footer !== null) {
        foreach (['<footer', 'wp_footer()', 'reservations@zingiber.ae'] as $contract) {
            if (strpos($footer, $contract) === false) {
                $fail('templates', sprintf('Footer contract missing: %s.', $contract));
            }
        }
    }

    $script = $read('assets/js/zingiber.js');
    if ($script !== null) {
        foreach (['aria-expanded', 'Escape', 'focus'] as $contract) {
            if (strpos($script, $contract) === false) {
                $fail('templates', sprintf('Mobile navigation behaviour missing: %s.', $contract));
            }
        }
    }
}

if (in_array('brand', $groups, true)) {
    $css = $read('assets/css/zingiber.css');
    if ($css === null) {
        $fail('brand', 'assets/css/zingiber.css is missing.');
    } else {
        $brandTokens = [
            '--zingiber-charcoal: #0F0F0F',
            '--zingiber-burnt-red: #701616',
            '--zingiber-terracotta: #9C4722',
            '--zingiber-copper: #B87333',
            '--zingiber-sand: #EDE7E1',
        ];

        foreach ($brandTokens as $token) {
            if (strpos($css, $token) === false) {
                $fail('brand', sprintf('Brand token missing from stylesheet: %s.', $token));
            }
        }

        foreach ([':focus-visible', '@media (prefers-reduced-motion: reduce)', '@media (max-width: 767px)'] as $requirement) {
            if (strpos($css, $requirement) === false) {
                $fail('brand', sprintf('Responsive/accessibility rule missing: %s.', $requirement));
            }
        }
    }

    $style = $read('style.css');
    if ($style === null || strpos($style, 'Theme Name: Zingiber Restaurant') === false) {
        $fail('brand', 'WordPress theme metadata has not been updated for Zingiber.');
    }
}

if (in_array('import', $groups, true)) {
    $importPath = $root . '/dummy-data/homepage/home-2.xml';
    if (!is_file($importPath)) {
        $fail('import', 'Home 2 import XML is missing.');
    } elseif (!class_exists('DOMDocument')) {
        $fail('import', 'PHP DOM extension is required to validate Home 2 import XML.');
    } else {
        $document = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $loaded = $document->load($importPath);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (!$loaded) {
            $fail('import', 'Home 2 import XML is not well formed.');
        } else {
            $xml = file_get_contents($importPath) ?: '';
            foreach (['Zingiber', 'Meet the Chef', 'A Journey Across India’s Coastline'] as $requiredCopy) {
                if (strpos($xml, $requiredCopy) === false) {
                    $fail('import', sprintf('Home 2 import XML is missing approved copy: %s.', $requiredCopy));
                }
            }
        }
    }
}

if (in_array('placeholders', $groups, true)) {
    $filesToScan = [
        'inc/zingiber/content.php',
        'inc/zingiber/setup.php',
        'header-zingiber.php',
        'footer-zingiber.php',
        'template-zingiber-home.php',
        'template-zingiber-page.php',
        'assets/css/zingiber.css',
        'assets/js/zingiber.js',
        'dummy-data/homepage/home-2.xml',
    ];
    $forbidden = [
        'Experience Taste Of Italy',
        'JOSEFINE HOELLER',
        '30% Off',
        'demo2.wpopal.com/vonaco/wp-content/uploads',
        '+971 XX XXX XXXX',
    ];

    foreach ($filesToScan as $relativePath) {
        $contents = $read($relativePath);
        if ($contents === null) {
            continue;
        }

        foreach ($forbidden as $needle) {
            if (stripos($contents, $needle) !== false) {
                $fail('placeholders', sprintf('Forbidden demo/placeholder string "%s" found in %s.', $needle, $relativePath));
            }
        }
    }
}

if ($failures !== []) {
    foreach ($failures as $failure) {
        fwrite(STDERR, $failure . PHP_EOL);
    }

    fwrite(STDERR, sprintf("Validation failed with %d issue(s).\n", count($failures)));
    exit(1);
}

foreach ($groups as $group) {
    fwrite(STDOUT, sprintf("[PASS] %s\n", $group));
}

fwrite(STDOUT, "Zingiber theme validation passed.\n");
exit(0);
