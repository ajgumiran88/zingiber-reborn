<?php

/**
 * Canonical, approved website content for the Zingiber experience.
 *
 * This module intentionally has no WordPress dependency so it can also be
 * loaded by the standalone validation suite.
 */

if (!function_exists('zingiber_prevent_widows')) {
    function zingiber_prevent_widows(string $text): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');

        if ($text === '' || !str_contains($text, ' ')) {
            return $text;
        }

        // Keep short openers with the next word so display type never strands "A" or "An".
        $text = (string) preg_replace(
            '/^(A|An|The|We|On|Of|To|By|At)\s+/ui',
            "$1\u{00A0}",
            $text
        );

        // Only glue the final word when the line still has room to wrap elsewhere.
        // Avoid turning short titles into one unbreakable string that gets mid-word cut.
        $words = preg_split('/\s+/u', $text) ?: [];
        if (count($words) < 4) {
            return $text;
        }

        return (string) preg_replace('/\s+(\S+)$/u', "\u{00A0}$1", $text);
    }
}

if (!function_exists('zingiber_get_site_content')) {
    function zingiber_get_site_content(): array
    {
        $image = static function (string $filename, string $alt): array {
            return [
                'src' => 'assets/images/zingiber/' . $filename,
                'alt' => $alt,
            ];
        };

        return [
            'home' => [
                'slug' => 'home',
                'title' => 'Zingiber',
                'seo_title' => 'Zingiber Dubai | Modern Indian Coastal Restaurant in JLT | By Chef Shankar',
                'meta_description' => 'Discover Zingiber, a chef-led modern Indian coastal restaurant in JLT, Dubai. Experience refined cuisine inspired by Goa, Kerala, Mangalore & Tamil Nadu.',
                'template' => 'template-zingiber-home.php',
                'hero' => [
                    'eyebrow' => 'Modern Indian Coastal Dining',
                    'heading' => 'Introducing Modern Indian Coastal Dining Experience.',
                    'subheading' => 'A chef-led coastal Indian dining experience, shaped by mastery, heritage, and storytelling.',
                    'image' => $image('interior-hero.jpg', 'Warm, atmospheric interior of Zingiber restaurant in Dubai'),
                ],
                'sections' => [
                    'chef_story' => [
                        'eyebrow' => 'The Zingiber Story',
                        'heading' => 'Coastal India, Reimagined for Now',
                        'body' => [
                            'Zingiber is a chef-driven modern Indian restaurant in Dubai, led by Chef Shankar Krishnamurthy. Rooted in India’s coastal heritage, the menu reinterprets regional classics through a contemporary lens — authenticity held in balance with innovation.',
                            'Every dish traces a journey across Goa, Kerala, Mangalore, and Tamil Nadu, brought to life through refined technique, thoughtful presentation, and layered flavour.',
                        ],
                        'image' => $image('food-signature-plate.jpg', 'A refined signature dish presented at Zingiber'),
                    ],
                    'coastal_expression' => [
                        'eyebrow' => 'Our Culinary Direction',
                        'heading' => 'A Contemporary Take on Coastal India',
                        'body' => [
                            'At Zingiber, coastal Indian cuisine is elevated beyond tradition. We celebrate the vibrancy of spice, the freshness of seafood, and the richness of regional cooking, reimagined for a cosmopolitan Dubai audience.',
                            'From delicately balanced curries to visually striking plates, each creation is designed to engage the senses while honouring its cultural origins.',
                        ],
                        'image' => $image('food-prawn-curry.jpg', 'Coastal Indian prawn curry with a contemporary Zingiber presentation'),
                    ],
                    'experience' => [
                        'eyebrow' => 'Dine With Us',
                        'heading' => 'An Experience Beyond the Plate',
                        'body' => [
                            'Zingiber offers a refined yet approachable dining experience. Inspired by coastal textures, natural tones, and understated elegance, the space reflects the essence of the cuisine.',
                            'Whether dining in or ordering at home through our curated cloud kitchen concepts, every touchpoint is crafted with consistency, quality, and care.',
                        ],
                        'image' => $image('interior-dining-room.jpg', 'Refined dining room with warm natural finishes at Zingiber'),
                    ],
                    'reservation' => [
                        'eyebrow' => 'Jumeirah Lakes Towers, Dubai',
                        'heading' => 'Your Table by the Coast',
                        'body' => [
                            'Join us daily for lunch and dinner service in a setting shaped by coastal textures, thoughtful hospitality, and contemporary Indian cuisine.',
                        ],
                        'image' => $image('interior-feature-wall.jpg', 'Textured feature wall and intimate seating at Zingiber'),
                    ],
                ],
                'principles' => [
                    ['title' => 'Authenticity', 'icon' => 'authenticity'],
                    ['title' => 'Innovation', 'icon' => 'innovation'],
                    ['title' => 'Consistency', 'icon' => 'consistency'],
                    ['title' => 'Storytelling', 'icon' => 'storytelling'],
                ],
                'regions' => [
                    [
                        'name' => 'Goa',
                        'image' => $image('food-prawn-curry.jpg', 'Coastal Indian prawn curry with a contemporary Zingiber presentation'),
                    ],
                    [
                        'name' => 'Kerala',
                        'image' => $image('food-seafood-rice.jpg', 'Seafood rice dish served with refined garnishes'),
                    ],
                    [
                        'name' => 'Mangalore',
                        'image' => $image('food-grilled-lamb.jpg', 'Grilled dish with layered coastal Indian flavours'),
                    ],
                    [
                        'name' => 'Tamil Nadu',
                        'image' => $image('food-coastal-thali.jpg', 'Contemporary presentation inspired by India’s coastal regions'),
                    ],
                ],
                'featured_images' => [
                    $image('food-grilled-lamb.jpg', 'Grilled dish with layered coastal Indian flavours'),
                    $image('interior-bar.jpg', 'Warm bar and dining details inside Zingiber'),
                    $image('food-coastal-thali.jpg', 'Contemporary presentation inspired by India’s coastal regions'),
                    $image('food-seafood-rice.jpg', 'Seafood rice dish served with refined garnishes'),
                    $image('food-dessert.jpg', 'Elegant dessert presentation at Zingiber'),
                ],
                'calls_to_action' => [
                    'primary' => ['label' => 'Reserve a Table', 'url' => '/contact/'],
                    'secondary' => ['label' => 'Explore the Menu', 'url' => '/menu/'],
                    'story' => ['label' => 'Discover Our Story', 'url' => '/about/'],
                ],
            ],
            'about' => [
                'slug' => 'about',
                'title' => 'About Zingiber',
                'seo_title' => 'About Zingiber | Modern Indian Coastal Dining Concept in Dubai',
                'meta_description' => 'Learn about Zingiber, a chef-led modern Indian coastal restaurant in Dubai inspired by regional traditions and elevated through contemporary techniques.',
                'template' => 'template-zingiber-page.php',
                'hero' => [
                    'eyebrow' => 'About Zingiber',
                    'heading' => 'A Modern Expression of Coastal India',
                    'subheading' => 'Warmth, depth, and authenticity shape every part of the Zingiber story.',
                    'image' => $image('interior-feature-wall.jpg', 'Coastal textures and crafted details in Zingiber’s interior'),
                ],
                'sections' => [
                    [
                        'heading' => 'Rooted in Meaning',
                        'body' => [
                            'Zingiber is inspired by the Latin name for ginger—Zingiber officinale—a foundational ingredient in Indian cuisine that symbolises warmth, depth, and authenticity.',
                            'Our concept is rooted in India’s coastal regions, where flavour, spice, and seafood traditions come together in dynamic ways. Led by Chef Shankar Krishnamurthy, whose four decades of global experience shape our culinary direction, Zingiber presents a refined interpretation of these regional influences.',
                        ],
                        'image' => $image('food-signature-plate.jpg', 'A signature Zingiber plate shaped by coastal Indian influences'),
                    ],
                    [
                        'heading' => 'Heritage, Reimagined',
                        'body' => [
                            'We are not a traditional Indian restaurant. Instead, we offer a contemporary, chef-led experience that blends heritage with innovation, delivering dishes that are both authentic and forward-thinking.',
                            'At our core, we are guided by four principles: Authenticity. Innovation. Consistency. Storytelling.',
                        ],
                        'image' => $image('interior-dining-room.jpg', 'Contemporary seating and understated elegance at Zingiber'),
                    ],
                ],
            ],
            'menu' => [
                'slug' => 'menu',
                'title' => 'Menu',
                'seo_title' => 'Zingiber Menu | Modern Indian Coastal Cuisine in Dubai',
                'meta_description' => 'Explore Zingiber’s menu featuring modern coastal Indian dishes, seafood specialties, and refined interpretations of regional classics.',
                'template' => 'template-zingiber-page.php',
                'hero' => [
                    'eyebrow' => 'The Menu',
                    'heading' => 'A Journey Across India’s Coastline',
                    'subheading' => 'A curated exploration of coastal regions, heritage, and creativity.',
                    'image' => $image('food-coastal-thali.jpg', 'A modern coastal Indian dining composition at Zingiber'),
                ],
                'sections' => [
                    [
                        'heading' => 'Coastal Regions, Contemporary Craft',
                        'body' => [
                            'Our menu is a curated exploration of India’s coastal regions, showcasing the diversity of flavours from Goa, Kerala, Mangalore, and Tamil Nadu.',
                            'Each dish is crafted with precision, balancing traditional techniques with modern presentation. Expect vibrant spices, fresh seafood, and thoughtfully layered compositions that reflect both heritage and creativity.',
                        ],
                        'image' => $image('food-seafood-rice.jpg', 'Seafood rice inspired by India’s coastal culinary traditions'),
                    ],
                    [
                        'heading' => 'Made to Be Experienced',
                        'body' => [
                            'Discover the menu in person and let our team guide your modern Indian coastal dining experience.',
                        ],
                        'image' => $image('food-grilled-lamb.jpg', 'A precisely plated grilled dish from the Zingiber kitchen'),
                    ],
                ],
            ],
            'gallery' => [
                'slug' => 'gallery',
                'title' => 'Gallery',
                'seo_title' => 'Zingiber Gallery | Modern Indian Restaurant Experience in Dubai',
                'meta_description' => 'View the Zingiber experience, from refined interiors to beautifully plated coastal Indian dishes and immersive dining moments.',
                'template' => 'template-zingiber-page.php',
                'hero' => [
                    'eyebrow' => 'Gallery',
                    'heading' => 'The Zingiber Experience',
                    'subheading' => 'Design, cuisine, and atmosphere brought together in harmony.',
                    'image' => $image('interior-bar.jpg', 'The warmly lit bar and dining atmosphere at Zingiber'),
                ],
                'sections' => [
                    [
                        'heading' => 'Every Detail, Considered',
                        'body' => [
                            'Explore Zingiber - where design, cuisine, and atmosphere come together in harmony.',
                            'From the textures of our interiors to the artistry of each plate, every detail reflects our commitment to a refined, contemporary dining experience inspired by coastal India.',
                        ],
                        'gallery' => [
                            $image('interior-hero.jpg', 'Atmospheric view through Zingiber’s contemporary dining space'),
                            $image('food-prawn-curry.jpg', 'Prawn curry presented with contemporary detail'),
                            $image('interior-dining-room.jpg', 'Warm dining room seating at Zingiber'),
                            $image('food-dessert.jpg', 'Dessert finished with elegant, precise presentation'),
                            $image('interior-feature-wall.jpg', 'Layered textures and feature lighting inside Zingiber'),
                            $image('food-signature-plate.jpg', 'A signature plate from the Zingiber kitchen'),
                        ],
                    ],
                ],
            ],
            'careers' => [
                'slug' => 'careers',
                'title' => 'Careers',
                'seo_title' => 'Careers at Zingiber | Join Our Culinary Team in Dubai',
                'meta_description' => 'Join the team at Zingiber Dubai. Explore career opportunities in a chef-led modern Indian restaurant focused on innovation and excellence.',
                'template' => 'template-zingiber-page.php',
                'hero' => [
                    'eyebrow' => 'Careers',
                    'heading' => 'Join the Zingiber Journey',
                    'subheading' => 'Build memorable guest experiences with a team driven by passion and craft.',
                    'image' => $image('interior-dining-room.jpg', 'The Zingiber dining room prepared for welcoming guests'),
                ],
                'sections' => [
                    [
                        'heading' => 'Grow With Us',
                        'body' => [
                            'At Zingiber, we are building a team driven by passion, creativity, and a shared commitment to excellence.',
                            'We believe in nurturing talent and creating an environment where innovation and craftsmanship thrive. Whether in the kitchen or front-of-house, every role contributes to delivering a memorable guest experience.',
                            'If you are inspired by modern cuisine, storytelling, and hospitality at its finest, we invite you to grow with us.',
                        ],
                        'image' => $image('interior-bar.jpg', 'Crafted hospitality details in Zingiber’s bar and dining space'),
                        'action' => [
                            'label' => 'Email Your Enquiry',
                            'url' => 'mailto:reservations@zingiber.ae?subject=Careers%20at%20Zingiber',
                        ],
                    ],
                ],
            ],
            'contact' => [
                'slug' => 'contact',
                'title' => 'Contact',
                'seo_title' => 'Contact Zingiber Dubai | Reservations, Location & Enquiries',
                'meta_description' => 'Get in touch with Zingiber Dubai. Find our location, contact details, reservations, and connect with us for a modern Indian coastal dining experience.',
                'template' => 'template-zingiber-page.php',
                'hero' => [
                    'eyebrow' => 'Contact & Reservations',
                    'heading' => 'Connect With Zingiber',
                    'subheading' => 'Our team is here to assist you with care and attention.',
                    'image' => $image('interior-hero.jpg', 'Inviting entrance and dining atmosphere at Zingiber in Dubai'),
                ],
                'sections' => [
                    [
                        'heading' => 'We Look Forward to Welcoming You',
                        'body' => [
                            'We look forward to welcoming you to Zingiber. Whether you’re planning a dining experience, making a reservation, or simply exploring our menu, our team is here to assist you with care and attention.',
                        ],
                        'image' => $image('interior-feature-wall.jpg', 'Intimate table setting against Zingiber’s textured interior'),
                    ],
                ],
                'details' => [
                    'address_name' => 'Zingiber Restaurant',
                    'address' => 'Jumeirah Lakes Towers (JLT), Dubai, UAE',
                    'email' => 'reservations@zingiber.ae',
                    'phone' => '',
                    'phone_label' => 'Phone details coming soon',
                    'hours' => 'Opening hours coming soon',
                    'instagram' => '@zingiberdubai',
                    'tiktok' => '@zingiberdubai',
                    'facebook' => 'Zingiber Dubai',
                    'whatsapp' => '',
                ],
            ],
        ];
    }
}

return zingiber_get_site_content();
