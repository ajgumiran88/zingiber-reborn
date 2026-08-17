
<?php
// Image
use Elementor\Controls_Manager;


add_action('elementor/element/image-carousel/section_image_carousel/before_section_end', function ($element, $args) {
	$element->add_control(
		'image_fullwidth',
		[
			'label' => esc_html__('Full Width', 'vonaco'),
			'type' => Controls_Manager::SWITCHER,
			'default' => '',
			'prefix_class' => 'image-carousel-fullwidth-vonaco-',
		]
	);

}, 10, 2);

add_action('elementor/element/image-carousel/section_style_image/before_section_end', function ($element, $args) {
    $element->add_control(
    'gutter',
    [
        'label'      => esc_html__('Gutter', 'vonaco'),
        'type'       => Controls_Manager::SLIDER,
        'range'      => [
            'px' => [
                'min' => 0,
                'max' => 60,
            ],
        ],
        'size_units' => ['px'],
        'selectors'  => [
            '{{WRAPPER}}.elementor-widget-image-carousel .swiper-slide-inner' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2); margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: calc({{SIZE}}{{UNIT}})',
            '{{WRAPPER}}.elementor-widget-image-carousel .elementor-image-carousel'         => 'margin-left: calc({{SIZE}}{{UNIT}} / -2); margin-right: calc({{SIZE}}{{UNIT}} / -2);',
        ],
    ]
    );

}, 10, 2);
