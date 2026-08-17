<?php
// Image Box
use Elementor\Controls_Manager;

add_action( 'elementor/element/image-box/section_image/before_section_end', function ($element, $args ) {
	$element->add_control(
		'image_style',
		[
			'label'   => esc_html__( 'Style', 'vonaco' ),
			'type'    => Controls_Manager::SELECT,
			'default'   => 'style-1',
			'options' => [
				'style-1'       => esc_html__( 'Style 1', 'vonaco' ),
				'style-2'       => esc_html__( 'Style 2', 'vonaco' ),
			],
			'prefix_class' => 'vonaco-image-box-',
		]
	);
}, 10, 2 );
