<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!vonaco_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Vonaco_Elementor_Breadcrumb extends Elementor\Widget_Base
{

    public function get_name()
    {
        return 'woocommerce-breadcrumb';
    }

    public function get_title()
    {
        return esc_html__('Vonaco WooCommerce Breadcrumbs', 'vonaco');
    }

    public function get_icon()
    {
        return 'eicon-product-breadcrumbs';
    }

    public function get_categories()
    {
        return ['woocommerce-elements', 'woocommerce-elements-single'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_product_rating_style',
            [
                'label' => esc_html__('Style Breadcrumb', 'vonaco'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wc_style_warning',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__('The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'vonaco'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'vonaco'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-breadcrumb' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__('Link Color', 'vonaco'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-breadcrumb > a:not(:hover)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography Link', 'vonaco'),
                'name' => 'text_link_typography',
                'selector' => '{{WRAPPER}} .woocommerce-breadcrumb a',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography Text', 'vonaco'),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .woocommerce-breadcrumb',
            ]
        );
        $this->add_control(
            'display_Breadcrumb',
            [
                'label' => esc_html__('Hidden Breadcrumb', 'vonaco'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class' => 'woocommerce-breadcrumb-hidden-'
            ]
        );
        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'vonaco'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'vonaco'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'vonaco'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'vonaco'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-breadcrumb' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .vonaco-woocommerce-title' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_product_rating_style_title',
            [
                'label' => esc_html__('Style Title', 'vonaco'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color_title',
            [
                'label' => esc_html__('Title Color', 'vonaco'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vonaco-woocommerce-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .vonaco-woocommerce-title',
            ]
        );

        $this->add_control(
            'display_title',
            [
                'label' => esc_html__('Hidden Title', 'vonaco'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class' => 'hidden-vonaco-title-'
            ]
        );

        $this->add_control(
            'display_title_single',
            [
                'label' => esc_html__('Hidden Title Single', 'vonaco'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class' => 'hidden-vonaco-title-single-'
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'vonaco'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vonaco-woocommerce-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $args = apply_filters(
            'woocommerce_breadcrumb_defaults',
            array(
                'delimiter' => '<span class="icon">/</span>',
                'wrap_before' => '<nav class="woocommerce-breadcrumb">',
                'wrap_after' => '</nav>',
                'before' => '',
                'after' => '',
                'home' => _x('Home Page', 'breadcrumb', 'vonaco'),
            )
        );
        $breadcrumbs = new WC_Breadcrumb();
        if (!empty($args['home'])) {
            $breadcrumbs->add_crumb($args['home'], apply_filters('woocommerce_breadcrumb_home_url', home_url()));
        }
        $args['breadcrumb'] = $breadcrumbs->generate();

        /**
         * WooCommerce Breadcrumb hook
         *
         * @see WC_Structured_Data::generate_breadcrumblist_data() - 10
         */
        do_action('woocommerce_breadcrumb', $breadcrumbs, $args);

        printf('<div class="vonaco-woocommerce-title">%s</div>', end($args['breadcrumb'])[0]);

        wc_get_template('global/breadcrumb.php', $args);
    }

    public function render_plain_content()
    {
    }
}

$widgets_manager->register(new Vonaco_Elementor_Breadcrumb());
