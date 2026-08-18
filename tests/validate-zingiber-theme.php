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
        foreach ([
            '<header',
            '<nav',
            'aria-label="Primary navigation"',
            'data-zingiber-menu-toggle',
            'aria-controls="zingiber-primary-menu"',
            'aria-expanded="false"',
            'type="button"',
            'zingiber_theme_asset_url',
            'wp_head()',
            'wp_body_open()',
        ] as $contract) {
            if (strpos($header, $contract) === false) {
                $fail('templates', sprintf('Header contract missing: %s.', $contract));
            }
        }

        if (preg_match_all('/<header\b/i', $header) !== 1) {
            $fail('templates', 'The Zingiber header must contain exactly one header element.');
        }
    }

    $footer = $read('footer-zingiber.php');
    if ($footer !== null) {
        foreach ([
            '<footer',
            'zingiber_theme_asset_url',
            'wp_footer()',
            'Jumeirah Lakes Towers',
            '@zingiberdubai',
        ] as $contract) {
            if (strpos($footer, $contract) === false) {
                $fail('templates', sprintf('Footer contract missing: %s.', $contract));
            }
        }


        if (preg_match_all('/<footer\b/i', $footer) !== 1) {
            $fail('templates', 'The Zingiber footer must contain exactly one footer element.');
        }

        if (strpos($footer, "\$contact['email']") === false && strpos($footer, 'reservations@zingiber.ae') === false) {
            $fail('templates', 'The Zingiber footer must render the approved reservations email.');
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

    $setup = $read('inc/zingiber/setup.php');
    if ($setup !== null) {
        foreach ([
            'zingiber_theme_asset_url',
            'zingiber_current_page_content',
            'zingiber_install_site_pages',
            'after_switch_theme',
            'wp_enqueue_scripts',
            'body_class',
            'get_page_by_path',
            'wp_insert_post',
            'wp_create_nav_menu',
            "'zingiber-fonts'",
            "'zingiber-site'",
        ] as $contract) {
            if (strpos($setup, $contract) === false) {
                $fail('templates', sprintf('Theme bootstrap contract missing: %s.', $contract));
            }
        }
    }

    $functions = $read('functions.php');
    if ($functions === null || strpos($functions, "inc/zingiber/setup.php") === false) {
        $fail('templates', 'functions.php does not load the Zingiber setup module.');
    }

    $homeTemplate = $read('template-zingiber-home.php');
    if ($homeTemplate !== null) {
        foreach (['hero', 'chef', 'principles', 'coastal-regions', 'menu', 'experience', 'gallery', 'reservations'] as $sectionId) {
            if (strpos($homeTemplate, 'id="' . $sectionId . '"') === false) {
                $fail('templates', sprintf('Home 2 section missing: #%s.', $sectionId));
            }
        }

        foreach ([
            "get_header('zingiber')",
            "get_footer('zingiber')",
            "\$home['principles']",
            "\$home['regions']",
            "\$home['featured_images']",
            'data-zingiber-reveal',
            "home_url('/menu/')",
            "home_url('/contact/')",
        ] as $contract) {
            if (strpos($homeTemplate, $contract) === false) {
                $fail('templates', sprintf('Home 2 template contract missing: %s.', $contract));
            }
        }

        if (preg_match_all('/<h1\b/i', $homeTemplate) !== 1) {
            $fail('templates', 'Home 2 template must contain exactly one H1.');
        }
    }

    $pageTemplate = $read('template-zingiber-page.php');
    if ($pageTemplate !== null) {
        foreach ([
            'zingiber_current_page_content()',
            "get_header('zingiber')",
            "get_footer('zingiber')",
            'zingiber-page-hero',
            'zingiber-page-principles',
            'zingiber-page-menu',
            'zingiber-page-gallery',
            'zingiber-page-careers',
            'zingiber-page-contact',
            "mailto:",
            "\$page['slug']",
            "\$page['details']",
        ] as $contract) {
            if (strpos($pageTemplate, $contract) === false) {
                $fail('templates', sprintf('Supporting-page template contract missing: %s.', $contract));
            }
        }

        if (preg_match_all('/<h1\b/i', $pageTemplate) !== 1) {
            $fail('templates', 'Supporting-page template must contain exactly one H1.');
        }
    }

    if ($setup !== null) {
        foreach (['zingiber_filter_document_title', 'zingiber_output_meta_description', 'document_title_parts', '<meta name="description"'] as $seoContract) {
            if (strpos($setup, $seoContract) === false) {
                $fail('templates', sprintf('SEO metadata contract missing: %s.', $seoContract));
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

        if (strpos($css, '"Estratto Var"') === false || strpos($css, '"Luxora Grotesk"') === false) {
            $fail('brand', 'Stylesheet must name Estratto Var and Luxora Grotesk in the type stack.');
        }

        $setupSource = $read('inc/zingiber/setup.php') ?? '';
        if (strpos($setupSource, 'Cormorant+Garamond') === false || strpos($setupSource, 'Manrope') === false) {
            $fail('brand', 'Open substitutes for Estratto Var and Luxora Grotesk must be enqueued.');
        }

        foreach ([':focus-visible', '@media (prefers-reduced-motion: reduce)', '@media (max-width: 767px)'] as $requirement) {
            if (strpos($css, $requirement) === false) {
                $fail('brand', sprintf('Responsive/accessibility rule missing: %s.', $requirement));
            }
        }

        foreach (['#C4A574', '#C9A66B', '#F7F2EA', '#080706'] as $offBrand) {
            if (stripos($css, $offBrand) !== false) {
                $fail('brand', sprintf('Off-palette colour %s is not in the Zingiber brand guidelines.', $offBrand));
            }
        }

        if (strpos($css, '.zingiber-site a:not(.zingiber-button)') === false
            && strpos($css, '.zingiber-site a.zingiber-button') === false) {
            $fail('brand', 'Filled buttons must not inherit body text color, or labels become unreadable on dark fills.');
        }

        if (!preg_match('/\.zingiber-button\s*\{[^}]*color:\s*(?:#fff(?:fff)?|var\(--zingiber-sand\))/s', $css)) {
            $fail('brand', 'Filled buttons must use light text on dark backgrounds.');
        }
    }

    $style = $read('style.css');
    if ($style === null) {
        $fail('brand', 'style.css is missing.');
    } else {
        foreach ([
            'Theme Name: Zingiber Restaurant' => '/Theme\s+Name:\s+Zingiber\s+Restaurant/',
            'Text Domain: vonaco' => '/Text\s+Domain:\s+vonaco/',
            'Description: A professional Zingiber restaurant experience' => '/Description:\s+A\s+professional\s+Zingiber\s+restaurant\s+experience/',
        ] as $metadata => $pattern) {
            if (preg_match($pattern, $style) !== 1) {
                $fail('brand', sprintf('WordPress theme metadata missing: %s.', $metadata));
            }
        }
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
            $xpath = new DOMXPath($document);
            $xpath->registerNamespace('wp', 'http://wordpress.org/export/1.2/');
            $xpath->registerNamespace('content', 'http://purl.org/rss/1.0/modules/content/');
            $xpath->registerNamespace('excerpt', 'http://wordpress.org/export/1.2/excerpt/');

            $homeItems = $xpath->query('//item[wp:post_type="page" and wp:post_name="home"]');
            if ($homeItems === false || $homeItems->length !== 1) {
                $fail('import', 'Home 2 WXR must contain exactly one published Home page item.');
            }

            $templateMeta = $xpath->query('//item[wp:post_name="home"]/wp:postmeta[wp:meta_key="_wp_page_template" and wp:meta_value="template-zingiber-home.php"]');
            if ($templateMeta === false || $templateMeta->length !== 1) {
                $fail('import', 'Home 2 WXR is missing the Zingiber Home page-template metadata.');
            }

            if (strpos($xml, '_elementor_data') !== false) {
                $fail('import', 'Home 2 WXR must not depend on legacy Elementor demo data.');
            }

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
