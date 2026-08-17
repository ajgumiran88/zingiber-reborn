<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Vonaco_Elementor_Tabs extends Widget_Base {

    public function get_categories() {
        return array('vonaco-addons');
    }

    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'vonaco-tabs';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Vonaco Tabs', 'vonaco');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-tabs';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords.
     * @since 2.1.0
     * @access public
     *
     */
    public function get_keywords() {
        return ['tabs', 'accordion', 'toggle'];
    }

    /**
     * Get HTML wrapper class.
     *
     * Retrieve the widget container class. Can be used to override the
     * container class for specific widgets.
     *
     * @since 2.0.9
     * @access protected
     */
    protected function get_html_wrapper_class() {
        return 'elementor-widget-' . $this->get_name() . ' elementor-widget-tabs';
    }

    public function get_script_depends() {
        return ['vonaco-elementor-tabs'];
    }

    public function get_style_depends() {
        return ['widget-tabs'];
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        $templates = Plugin::instance()->templates_manager->get_source('local')->get_items();

        $options = [
            '0' => '— ' . esc_html__('Select', 'vonaco') . ' —',
        ];

        $types = [];

        foreach ($templates as $template) {
            $options[$template['template_id']] = $template['title'] . ' (' . $template['type'] . ')';
            $types[$template['template_id']]   = $template['type'];
        }

        $this->start_controls_section(
            'section_tabs',
            [
                'label' => esc_html__('Tabs', 'vonaco'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label'       => esc_html__('Title', 'vonaco'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Tab Title', 'vonaco'),
                'placeholder' => esc_html__('Tab Title', 'vonaco'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'source',
            [
                'label'   => esc_html__('Source', 'vonaco'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'html',
                'options' => [
                    'html'     => esc_html__('HTML', 'vonaco'),
                    'template' => esc_html__('Template', 'vonaco')
                ]
            ]
        );

        $repeater->add_control(
            'tab_template',
            [
                'label'       => esc_html__('Choose Template', 'vonaco'),
                'default'     => 0,
                'type'        => Controls_Manager::SELECT,
                'options'     => $options,
                'types'       => $types,
                'label_block' => 'true',
                'condition'   => [
                    'source' => 'template',
                ],
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label'       => esc_html__('Content', 'vonaco'),
                'default'     => esc_html__('Tab Content', 'vonaco'),
                'placeholder' => esc_html__('Tab Content', 'vonaco'),
                'type'        => Controls_Manager::WYSIWYG,
                'show_label'  => false,
                'condition'   => [
                    'source' => 'html',
                ],
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label'       => esc_html__('Tabs Items', 'vonaco'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'tab_title'   => esc_html__('Tab #1', 'vonaco'),
                        'tab_content' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'vonaco'),
                    ],
                    [
                        'tab_title'   => esc_html__('Tab #2', 'vonaco'),
                        'tab_content' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'vonaco'),
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->add_control(
            'view',
            [
                'label'   => esc_html__('View', 'vonaco'),
                'type'    => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->add_control(
            'type',
            [
                'label'        => esc_html__('Type', 'vonaco'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'horizontal',
                'options'      => [
                    'horizontal' => esc_html__('Horizontal', 'vonaco'),
                ],
                'prefix_class' => 'elementor-tabs-view-',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'tabs_align_horizontal',
            [
                'label' => esc_html__( 'Alignment', 'vonaco' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '' => [
                        'title' => esc_html__( 'Start', 'vonaco' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'vonaco' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'vonaco' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Justified', 'vonaco' ),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                ],
                'prefix_class' => 'elementor-tabs-alignment-',
                'condition' => [
                    'type' => 'horizontal',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_header_tabs_style',
            [
                'label' => esc_html__('Header Tab', 'vonaco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'header_tab__background_color',
            [
                'label'     => esc_html__('Background Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs .elementor-tabs-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
                'haeder_tab_padding',
            [
                'label'      => esc_html__('Padding', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs .elementor-tabs-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
                'header_tab_margin',
            [
                'label'      => esc_html__('Margin', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs .elementor-tabs-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'header_tab_border',
                'selector'  => '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs .elementor-tabs-wrapper',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tabs_title_style',
            [
                'label' => esc_html__('Title', 'vonaco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tab_title_typography',
                'selector' => '{{WRAPPER}}.elementor-widget-tabs .elementor-tab-title',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
            ]
        );

        $this->start_controls_tabs('tabs_carousel_dots_style');

        $this->start_controls_tab(
            'tab_title_color_normal',
            [
                'label' => esc_html__('Normal', 'vonaco'),
            ]
        );

        $this->add_control(
            'tab_title_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs .elementor-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_color_active',
            [
                'label' => esc_html__('Active', 'vonaco'),
            ]
        );

        $this->add_control(
            'tab_title_active_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs .elementor-tab-title.elementor-active' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tab_title_padding',
            [
                'label'      => esc_html__('Padding', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs .elementor-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'tab_title_margin',
            [
                'label'      => esc_html__('Margin', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs .elementor-tab-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tabs_icon_style',
            [
                'label' => esc_html__('Icon', 'vonaco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_tab_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs.elementor-tabs-view-horizontal i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'vonaco' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', '%'],

                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs.elementor-tabs-view-horizontal i' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_tab_padding',
            [
                'label'      => esc_html__('Padding', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs.elementor-tabs-view-horizontal i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_tab_margin',
            [
                'label'      => esc_html__('Margin', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}}.elementor-widget-vonaco-tabs.elementor-widget-tabs.elementor-tabs-view-horizontal i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tabs_content_style',
            [
                'label' => esc_html__('Content', 'vonaco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_content',
            [
                'label'     => esc_html__('Content', 'vonaco'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .elementor-tab-content',
            ]
        );


        $this->add_responsive_control('tab_content_padding',
            [
                'label'      => esc_html__('Padding', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control('tab_content_margin',
            [
                'label'      => esc_html__('Margin', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'tab_content_border',
                'selector'  => '{{WRAPPER}} .elementor-tabs-content-wrapper',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $tabs = $this->get_settings_for_display('tabs');

        $id_int = substr($this->get_id_int(), 0, 3);
        ?>
        <div class="elementor-tabs" role="tablist">
            <div class="elementor-tabs-wrapper">
                <?php
                foreach ($tabs as $index => $item) :
                    $tab_count = $index + 1;
                    $class_item = 'elementor-repeater-item-' . $item['_id'];
                    $class_content = ($index == 0) ? 'elementor-active' : '';
                    $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);

                    $this->add_render_attribute($tab_title_setting_key, [
                        'id'            => 'elementor-tab-title-' . $id_int . $tab_count,
                        'class'         => [
                            'elementor-tab-title',
                            'elementor-tab-desktop-title',
                            $class_content,
                            $class_item
                        ],
                        'data-tab'      => $tab_count,
                        'role'          => 'tab',
                        'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
                    ]);
                    ?>
                    <div <?php echo vonaco_elementor_get_render_attribute_string($tab_title_setting_key, $this); ?>>
                        <span class="title"><?php echo esc_html($item['tab_title']); ?></span>
                    </div>
                    <i class="vonaco-icon-rhombus"></i>
                <?php endforeach; ?>
            </div>
            <div class="elementor-tabs-content-wrapper">
                <?php
                foreach ($tabs as $index => $item) :
                    $tab_count = $index + 1;
                    $class_item = 'elementor-repeater-item-' . $item['_id'];
                    $class_content = ($index == 0) ? 'elementor-active' : '';
                    $tab_content_setting_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);

                    $this->add_render_attribute($tab_content_setting_key, [
                        'id'              => 'elementor-tab-content-' . $id_int . $tab_count,
                        'class'           => [
                            'elementor-tab-content',
                            'elementor-clearfix',
                            $class_content,
                            $class_item
                        ],
                        'data-tab'        => $tab_count,
                        'role'            => 'tabpanel',
                        'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
                    ]);

                    $this->add_inline_editing_attributes($tab_content_setting_key, 'advanced');
                    ?>
                    <div <?php echo vonaco_elementor_get_render_attribute_string($tab_content_setting_key, $this); // WPCS: XSS ok.
                    ?>>
                        <?php if ('html' === $item['source']): ?>
                            <?php echo vonaco_elementor_parse_text_editor($item['tab_content'], $this); // WPCS: XSS ok. ?>
                        <?php else: ?>
                            <?php echo Plugin::instance()->frontend->get_builder_content_for_display($item['tab_template']); ?>
                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}

$widgets_manager->register(new Vonaco_Elementor_Tabs());
