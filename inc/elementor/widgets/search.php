<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class Vonaco_Elementor_Search extends Elementor\Widget_Base {
    public function get_name() {
        return 'vonaco-search';
    }

    public function get_title() {
        return esc_html__('Vonaco Search Form', 'vonaco');
    }

    public function get_icon() {
        return 'eicon-site-search';
    }

    public function get_categories() {
        return array('vonaco-addons');
    }

    protected function register_controls() {
        $this->start_controls_section(
            'search-form-style',
            [
                'label' => esc_html__('Style', 'vonaco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'layout_style',
            [
                'label'   => esc_html__('Layout Style', 'vonaco'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'layout-1' => esc_html__('Layout 1', 'vonaco'),
                    'layout-2' => esc_html__('Layout 2', 'vonaco'),
                ],
                'default' => 'layout-1',
            ]
        );

        $this->add_control(
            'hide_search',
            [
                'label'        => esc_html__('Hide filter categories', 'vonaco'),
                'type'         => Controls_Manager::SWITCHER,
                'condition'    => [
                    'layout_style' => 'layout-1',
                ],
                'prefix_class' => 'search-form-hide-search-',
            ]
        );

        $this->add_control(
            'text_input_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} input' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_width',
            [
                'label'      => esc_html__('Border width', 'vonaco'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} form input[type=search]' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label'     => esc_html__('Border Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_color_focus',
            [
                'label'     => esc_html__('Border Color Focus', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_form',
            [
                'label'     => esc_html__('Background Form', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'background_button',
            [
                'label'     => esc_html__('Background Button', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .widget_product_search form button[type=submit]:not(:hover)' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'background_button_hover',
            [
                'label'     => esc_html__('Background Button Hover', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .widget_product_search form button[type=submit]:hover' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_form',
            [
                'label'     => esc_html__('Color Icon', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .site-header-search > a i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .widget_product_search form:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius_button',
            [
                'label'      => esc_html__('Border Radius Button', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .widget_product_search form button[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius_input',
            [
                'label'      => esc_html__('Border Radius Input', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .widget_product_search form input[type=search]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ($settings['layout_style'] === 'layout-1' && vonaco_is_woocommerce_activated()):{
            vonaco_product_search();
        }
        endif;

        if ($settings['layout_style'] === 'layout-2'):{
            wp_enqueue_script('vonaco-search-popup');
            add_action('wp_footer', 'vonaco_header_search_popup', 1);
            ?>
            <div class="site-header-search">
                <a href="#" class="button-search-popup">
                    <i class="vonaco-icon-search"></i>
                    <span class="content"><?php echo esc_html__('Search', 'vonaco'); ?></span>
                </a>
            </div>
            <?php
        }
        endif;
    }
}

$widgets_manager->register(new Vonaco_Elementor_Search());
