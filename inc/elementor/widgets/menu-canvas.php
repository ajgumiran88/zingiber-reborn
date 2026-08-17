<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class Vonaco_Elementor__Menu_Canvas extends Elementor\Widget_Base {

    public function get_name() {
        return 'vonaco-menu-canvas';
    }

    public function get_title() {
        return esc_html__('Vonaco Menu Canvas', 'vonaco');
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return ['vonaco-addons'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'icon-menu_style',
            [
                'label' => esc_html__('Icon', 'vonaco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'layout_style',
            [
                'label'        => esc_html__('Layout Style', 'vonaco'),
                'type'         => Controls_Manager::SELECT,
                'options'      => [
                    'layout-1' => esc_html__('Layout 1', 'vonaco'),
                    'layout-2' => esc_html__('Layout 2', 'vonaco'),
                ],
                'default'      => 'layout-2',
                'prefix_class' => 'vonaco-canvas-menu-',
            ]
        );

//        $this->add_responsive_control(
//            'icon_menu_size',
//            [
//                'label'     => esc_html__( 'Size Icon', 'vonaco' ),
//                'type'      => Controls_Manager::SLIDER,
//                'range'     => [
//                    'px' => [
//                        'min' => 6,
//                        'max' => 300,
//                    ],
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .menu-mobile-nav-button i' => 'font-size: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//        );

        $this->start_controls_tabs( 'color_tabs' );

        $this->start_controls_tab( 'colors_normal',
            [
                'label' => esc_html__( 'Normal', 'vonaco' ),
            ]
        );

        $this->add_control(
            'menu_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button .vonaco-icon > span'             => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .menu-mobile-nav-button:not(:hover) .screen-reader-text' => 'color: {{VALUE}};',
                ],
            ]
        );

//        $this->add_control(
//            'background_color',
//            [
//                'label'     => esc_html__('Background Color', 'vonaco'),
//                'type'      => Controls_Manager::COLOR,
//                'default'   => '',
//                'selectors' => [
//                    '{{WRAPPER}} .menu-mobile-nav-button .vonaco-icon'  => 'background-color: {{VALUE}};',
//                ],
//            ]
//        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'colors_hover',
            [
                'label' => esc_html__( 'Hover', 'vonaco' ),
            ]
        );

        $this->add_control(
            '_menu_color_hover',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .menu-mobile-nav-button:hover .vonaco-icon > span'             => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .menu-mobile-nav-button:hover .screen-reader-text' => 'color: {{VALUE}};',
                ],
            ]
        );

//        $this->add_control(
//            'background_color_hover',
//            [
//                'label'     => esc_html__('Background Color', 'vonaco'),
//                'type'      => Controls_Manager::COLOR,
//                'default'   => '',
//                'selectors' => [
//                    '{{WRAPPER}} .menu-mobile-nav-button .vonaco-icon:hover'  => 'background-color: {{VALUE}};',
//                ],
//            ]
//        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-canvas-menu-wrapper');
        ?>
        <div <?php echo vonaco_elementor_get_render_attribute_string('wrapper', $this); ?>>
            <?php vonaco_mobile_nav_button(); ?>
        </div>
        <?php
    }

}

$widgets_manager->register(new Vonaco_Elementor__Menu_Canvas());
