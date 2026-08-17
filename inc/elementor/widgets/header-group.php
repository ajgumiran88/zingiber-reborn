 <?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
 use Elementor\Plugin;

class Vonaco_Elementor_Header_Group extends Elementor\Widget_Base {

    public function get_name() {
        return 'vonaco-header-group';
    }

    public function get_title() {
        return esc_html__('Vonaco Header Group', 'vonaco');
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return array('vonaco-addons');
    }

    public function get_script_depends() {
        return ['vonaco-elementor-header-group', 'slick', 'vonaco-cart-canvas'];
    }

    public function button_canvas_template_html() {
        $template_id = $this->get_settings('template_id');
        ?>
        <div class="header-button-canvas" id="<?php echo 'header-canvas-' . esc_attr($this->get_id()); ?>">
            <?php
            if ('publish' !== get_post_status($template_id)) {
                return;
            }
            ?>
            <div class="button-side-heading">
                <a class="close-button-side" href="#"><i class="vonaco-icon-times"></i></a>
            </div>
            <div class="header-template-canvas">
                <?php
                if (!empty($template_id)) {
                    echo Plugin::instance()->frontend->get_builder_content_for_display($template_id);
                } else {
                    echo esc_html__('No Templates', 'vonaco');
                }
                ?>
            </div>
        </div>
        <div class="button-side-overlay"></div>
        <?php
    }

    protected function register_controls() {

        $templates = \Elementor\Plugin::instance()->templates_manager->get_source('local')->get_items();

        $options = [
            '0' => '— ' . esc_html__('Select', 'vonaco') . ' —',
        ];

        $types = [];

        foreach ($templates as $template) {
            $options[$template['template_id']] = $template['title'] . ' (' . $template['type'] . ')';
            $types[$template['template_id']]   = $template['type'];
        }

        $this->start_controls_section(
            'header_group_config',
            [
                'label' => esc_html__('Config', 'vonaco'),
            ]
        );

        $this->add_control(
            'show_search',
            [
                'label' => esc_html__('Show search', 'vonaco'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_account',
            [
                'label' => esc_html__('Show account', 'vonaco'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_wishlist',
            [
                'label' => esc_html__('Show wishlist', 'vonaco'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_cart',
            [
                'label' => esc_html__('Show cart', 'vonaco'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_button',
            [
                'label' => esc_html__('Show button', 'vonaco'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'template_id',
            [
                'label'       => esc_html__('Choose Template', 'vonaco'),
                'default'     => 0,
                'type'        => Controls_Manager::SELECT,
                'options'     => $options,
                'types'       => $types,
                'label_block' => 'true',
                'condition'   => ['show_button' => 'yes'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'header-group-style',
            [
                'label' => esc_html__('Icon', 'vonaco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:not(:hover) i:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:not(:hover):before'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div .button-content:not(:hover) > span'   => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action .count'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:hover i:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:hover:before'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'     => esc_html__('Font Size', 'vonaco'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a i:before' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:before'   => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-header-group-wrapper');
        ?>
        <div <?php echo vonaco_elementor_get_render_attribute_string('wrapper', $this); ?>>
            <div class="header-group-action">

                <?php if ($settings['show_search'] === 'yes'):{
                    vonaco_header_search_button();
                }
                endif; ?>

                <?php if ($settings['show_account'] === 'yes'):{
                    vonaco_header_account();
                }
                endif; ?>

                <?php if ($settings['show_wishlist'] === 'yes' && vonaco_is_woocommerce_activated()):{
                    vonaco_header_wishlist();
                }
                endif; ?>

                <?php if ($settings['show_cart'] === 'yes' && vonaco_is_woocommerce_activated()):{
                    vonaco_header_cart();
                }
                endif; ?>

                <?php if ($settings['show_button'] === 'yes'):{
                    add_action('wp_footer', [$this, 'button_canvas_template_html']);
                    ?>
                    <div class="site-header-button">
                        <a class="button-content" href="#" data-target="<?php echo '#header-canvas-' . esc_attr($this->get_id()); ?>">
                            <span class="icon-1"></span>
                            <span class="icon-2"></span>
                            <span class="icon-3"></span>
                        </a>
                    </div>
                    <?php
                }
                endif; ?>

            </div>
        </div>
        <?php
    }
}

$widgets_manager->register(new Vonaco_Elementor_Header_Group());
