<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!vonaco_is_contactform_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;


class Vonaco_Elementor_ContactForm extends Elementor\Widget_Base {

    public function get_name() {
        return 'vonaco-contactform';
    }

    public function get_title() {
        return esc_html__('Vonaco Contact Form', 'vonaco');
    }

    public function get_categories() {
        return array('vonaco-addons');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'contactform7',
            [
                'label' => esc_html__('General', 'vonaco'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $cf7               = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');
        $contact_forms[''] = esc_html__('Please select form', 'vonaco');
        if ($cf7) {
            foreach ($cf7 as $cform) {
                $contact_forms[$cform->ID] = $cform->post_title;
            }
        } else {
            $contact_forms[0] = esc_html__('No contact forms found', 'vonaco');
        }

        $this->add_control(
            'cf_id',
            [
                'label'   => esc_html__('Select contact form', 'vonaco'),
                'type'    => Controls_Manager::SELECT,
                'options' => $contact_forms,
                'default' => ''
            ]
        );

        $this->add_control(
            'form_name',
            [
                'label'   => esc_html__('Form name', 'vonaco'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Contact form', 'vonaco'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_input',
            [
                'label' => esc_html__('Input', 'vonaco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_input_style');

        $this->start_controls_tab(
            'tab_input_normal',
            [
                'label' => esc_html__('Normal', 'vonaco'),
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input:not([type="submit"])' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type="date"]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form textarea' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select:not([size]):not([multiple])' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_color_placeholder',
            [
                'label'     => esc_html__('Color Placeholder', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input:not([type="submit"])::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type="date"]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form textarea::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select:not([size]):not([multiple])::placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_background',
            [
                'label'     => esc_html__('Background Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input:not([type="submit"])' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type="date"]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form textarea' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select:not([size]):not([multiple])' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_color_border',
            [
                'label'     => esc_html__('Color Border', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input:not([type="submit"])' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type="date"]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form textarea' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select:not([size]):not([multiple])' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_focus',
            [
                'label' => esc_html__('Focus', 'vonaco'),
            ]
        );

        $this->add_control(
            'input_border_color_focus',
            [
                'label'     => esc_html__('Border Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form input:not([type="submit"]):focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form input[type="date"]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form textarea:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form select:not([size]):not([multiple]):focus' => 'border-color: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        //Button
        $this->start_controls_section(
            'style_button',
            [
                'label' => esc_html__('Button', 'vonaco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .wpcf7-form .form-button input[type=submit]',
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'vonaco'),
            ]
        );

        $this->add_control(
            'button_bacground',
            [
                'label'     => esc_html__('Background Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .form-button input[type=submit]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => esc_html__('Text Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .form-button input[type=submit]' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'button_border',
            [
                'label'     => esc_html__('Border Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .form-button input[type=submit]' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'vonaco'),
            ]
        );

        $this->add_control(
            'button_bacground_hover',
            [
                'label'     => esc_html__('Background Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .form-button input[type=submit]:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .form-button input[type=submit]:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_hover',
            [
                'label'     => esc_html__('Border Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .form-button input[type=submit]:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'align_input',
            [
                'label'     => esc_html__('Alignment', 'vonaco'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'vonaco'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'vonaco'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'vonaco'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .form-button' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'border_button',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .wpcf7-form .form-button input[type=submit]',
                'separator'   => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-form .form-button input[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__('Padding', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-form .form-button input[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!$settings['cf_id']) {
            return;
        }
        $args['id']    = $settings['cf_id'];
        $args['title'] = $settings['form_name'];

        echo vonaco_do_shortcode('contact-form-7', $args);
    }
}

$widgets_manager->register(new Vonaco_Elementor_ContactForm());
