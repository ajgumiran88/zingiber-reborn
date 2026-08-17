<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Vonaco_Elementor_MenuList extends Elementor\Widget_Base {

    public function get_name() {
        return 'vonaco-menu-list';
    }

    public function get_script_depends() {
        return ['vonaco-elementor-menu-list', 'tweenmax'];
    }

    public function get_title() {
        return esc_html__('Vonaco Menu List', 'vonaco');
    }

    public function get_icon() {
        return 'eicon-bullet-list';
    }

    public function get_categories() {
        return array('vonaco-addons');
    }

    protected function register_controls() {


        $this->start_controls_section(
            'section_menu-list',
            [
                'label' => esc_html__('Menu', 'vonaco'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'price',
            [
                'label'   => esc_html__('Price', 'vonaco'),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'sub_title',
            [
                'label'       => esc_html__('Sub Title', 'vonaco'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => 'true',
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__('Title', 'vonaco'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => 'true',
            ]
        );

        $repeater->add_control(
            'item_description',
            [
                'label'   => esc_html__('Description', 'vonaco'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label'   => esc_html__('Image', 'vonaco'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'   => esc_html__('Link', 'vonaco'),
                'type'    => Controls_Manager::URL
            ]
        );

        $this->add_control(
            'price_list',
            [
                'label'       => esc_html__('List Items', 'vonaco'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'menu_style',
            [
                'label'   => esc_html__('Style', 'vonaco'),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Style 1', 'vonaco'),
                    '2' => esc_html__('Style 2', 'vonaco'),
                    '3' => esc_html__('Style 3', 'vonaco'),
                    '4' => esc_html__('Style 4', 'vonaco'),
                    '5' => esc_html__('Style 5', 'vonaco'),
                ]
            ]
        );

        $this->add_control('show_effect',
            [
                'label'     => esc_html__('Show Effect', 'vonaco'),
                'type'      => Controls_Manager::SWITCHER,
                'condition' => [
                    'menu_style' => '3'
                ]
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'   => esc_html__('Columns', 'vonaco'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label'      => esc_html__('Spacing', 'vonaco'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-menu-list-item-wrapper .row'         => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
                    '{{WRAPPER}} .elementor-menu-list-item-wrapper .column-item' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2); margin-bottom: calc({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'Horizontal_align',
            [
                'label'        => esc_html__('Horizontal Align', 'vonaco'),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'left'  => [
                        'title' => esc_html__('Left', 'vonaco'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'vonaco'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'      => 'left',
                'prefix_class' => 'menulist-align-'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_list_style',
            [
                'label' => esc_html__('List', 'vonaco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'list_color',
            [
                'label'     => esc_html__('Background Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-list-text' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'list_spacing',
            [
                'label'      => esc_html__('Padding', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-menu-list-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'heading_sub_title',
            [
                'label'     => esc_html__('Sub Title', 'vonaco'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'sub_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-list-sub-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sub_typography',
                'selector' => '{{WRAPPER}} .elementor-menu-list-sub-title',
            ]
        );

        $this->add_control(
            'heading_sub_spacing',
            [
                'label'      => esc_html__('Margin', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-menu-list-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading__title',
            [
                'label'     => esc_html__('Title', 'vonaco'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-list-title'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-menu-list-title a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'heading_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-list-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'heading_typography',
                'selector' => '{{WRAPPER}} .elementor-menu-list-title',
            ]
        );

        $this->add_control(
            'heading_spacing',
            [
                'label'      => esc_html__('Margin', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-menu-list-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading__price',
            [
                'label'     => esc_html__('Price', 'vonaco'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-list-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .elementor-menu-list-price',
            ]
        );

        $this->add_control(
            'price_spacing',
            [
                'label'      => esc_html__('Margin', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-menu-list-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_item_description',
            [
                'label'     => esc_html__('Description', 'vonaco'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'     => esc_html__('Color', 'vonaco'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-list-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'description_typography',
                'selector' => '{{WRAPPER}} .elementor-menu-list-description',
            ]
        );

        $this->add_control(
            'description_spacing',
            [
                'label'      => esc_html__('Margin', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-menu-list-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_image_style',
            [
                'label'      => esc_html__('Image', 'vonaco'),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'image_size',
                'default' => 'full',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => esc_html__('Border Radius', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-menu-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_spacing',
            [
                'label'      => esc_html__('Margin', 'vonaco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-menu-list-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    private function render_image($item, $instance, $html = true) {
        $image_id   = $item['image']['id'];
        $image_size = $instance['image_size_size'];
        if ('custom' === $image_size) {
            $image_src = Group_Control_Image_Size::get_attachment_image_src($image_id, 'image_size', $instance);
        } else {
            $image_src = wp_get_attachment_image_src($image_id, $image_size);
            if(is_array($image_src)){
                $image_src = $image_src[0];
            }
        }

        if ($image_src) {
            if (!$html) {
                return $image_src;
            }

            return printf('<img src="%s" alt="%s" />', $image_src, $item['title']);
        }
        return false;
    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-menu-list-item-wrapper');

        // Row
        $this->add_render_attribute('row', 'class', 'row');
        $this->add_render_attribute('row', 'class', 'menu-style-' . esc_attr($settings['menu_style']));

        if (!empty($settings['column_widescreen'])) {
            $this->add_render_attribute('row', 'data-elementor-columns-widescreen', $settings['column_widescreen']);
        }

        if (!empty($settings['column'])) {
            $this->add_render_attribute('row', 'data-elementor-columns', $settings['column']);
        } else {
            $this->add_render_attribute('row', 'data-elementor-columns', 5);
        }

        if (!empty($settings['column_laptop'])) {
            $this->add_render_attribute('row', 'data-elementor-columns-laptop', $settings['column_laptop']);
        }

        if (!empty($settings['column_tablet_extra'])) {
            $this->add_render_attribute('row', 'data-elementor-columns-tablet-extra', $settings['column_tablet_extra']);
        }

        if (!empty($settings['column_tablet'])) {
            $this->add_render_attribute('row', 'data-elementor-columns-tablet', $settings['column_tablet']);
        } else {
            $this->add_render_attribute('row', 'data-elementor-columns-tablet', 2);
        }

        if (!empty($settings['column_mobile_extra'])) {
            $this->add_render_attribute('row', 'data-elementor-columns-mobile-extra', $settings['column_mobile_extra']);
        }

        if (!empty($settings['column_mobile'])) {
            $this->add_render_attribute('row', 'data-elementor-columns-mobile', $settings['column_mobile']);
        } else {
            $this->add_render_attribute('row', 'data-elementor-columns-mobile', 1);
        }

        // Item

        ?>

        <div <?php echo vonaco_elementor_get_render_attribute_string('wrapper', $this); // WPCS: XSS ok.
        ?>>
            <div <?php echo vonaco_elementor_get_render_attribute_string('row', $this); // WPCS: XSS ok.
            ?>>
                <?php foreach ($settings['price_list'] as $index => $item) :
                    $item_key = 'item_' . $index;
                    $this->add_render_attribute($item_key, 'class', 'column-item elementor-menu-list-item');

                    if ($settings['menu_style'] == '3' && $settings['show_effect'] == 'yes' && !empty($item['image']['url'])) {
                        $this->add_render_attribute($item_key, 'data-fx', '1');
                        $this->add_render_attribute($item_key, 'data-img', $this->render_image($item, $settings, false));
                    }
                    ?>
                    <div <?php echo vonaco_elementor_get_render_attribute_string($item_key, $this); // WPCS: XSS ok.
                    ?>>

                        <?php if ($settings['menu_style'] == '1' || $settings['menu_style'] == '3'): ?>
                            <?php
                            if (!empty($item['image']['url'])) :
                                if ($settings['show_effect'] !== 'yes') {
                                    ?>
                                    <div class="elementor-menu-list-image">
                                        <?php $this->render_image($item, $settings); ?>
                                    </div>
                                <?php }
                            endif; ?>
                            <div class="elementor-menu-list-text">
                                <?php
                                $name_html = $item['title'];
                                if (!empty($item['link']['url'])) :
                                    $name_html = '<a href="' . esc_url($item['link']['url']) . '">' . esc_html($name_html) . '</a>';
                                endif; ?>
                                <?php if (!empty($item['title'])) : ?>
                                    <?php printf('<div class="elementor-menu-list-title">%s</div>', $name_html); ?>
                                <?php endif; ?>

                                <?php if (!empty($item['item_description'])) : ?>
                                    <div class="elementor-menu-list-description"><?php printf('%s',$item['item_description']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($item['price'])) : ?>
                                    <div class="elementor-menu-list-price"><?php printf('%s',$item['price']) ; ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($settings['menu_style'] == '2'): ?>

                            <?php if (!empty($item['image']['url'])) : ?>
                                <div class="elementor-menu-list-image">
                                    <?php $this->render_image($item, $settings); ?>
                                </div>
                            <?php endif; ?>
                            <div class="elementor-menu-list-text">
                                <div class="elementor-menu-list-header">
                                    <?php
                                    $name_html = $item['title'];
                                    if (!empty($item['link']['url'])) :
                                        $name_html = '<a href="' . esc_url($item['link']['url']) . '">' . esc_html($name_html) . '</a>';
                                    endif; ?>
                                    <?php if (!empty($item['title'])) : ?>
                                        <?php printf('<div class="elementor-menu-list-title">%s</div>', $name_html); ?>
                                    <?php endif; ?>
                                    <span class="elementor-menu-list-separator"></span>
                                    <?php if (!empty($item['price'])) : ?>
                                        <div class="elementor-menu-list-price"><?php printf('%s',$item['price']); ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($item['item_description'])) : ?>
                                    <div class="elementor-menu-list-description"><?php printf('%s',$item['item_description']); ?></div>
                                <?php endif; ?>
                            </div>

                        <?php endif; ?>

                        <?php if ($settings['menu_style'] == '4'): ?>

                            <?php if (!empty($item['image']['url']) || !empty($item['price'])) : ?>
                                <div class="elementor-menu-list-image">
                                    <?php $this->render_image($item, $settings); ?>
                                    <div class="elementor-menu-list-price"><?php printf('%s',$item['price']); ?></div>
                                </div>
                            <?php endif; ?>

                            <div class="elementor-menu-list-text">
                                <?php
                                $name_html = $item['title'];
                                if (!empty($item['link']['url'])) :
                                    $name_html = '<a href="' . esc_url($item['link']['url']) . '">' . esc_html($name_html) . '</a>';
                                endif; ?>
                                <?php if (!empty($item['title'])) : ?>
                                    <?php printf('<div class="elementor-menu-list-title">%s</div>', $name_html); ?>
                                <?php endif; ?>

                                <?php if (!empty($item['item_description'])) : ?>
                                    <div class="elementor-menu-list-description"><?php printf('%s',$item['item_description']); ?></div>
                                <?php endif; ?>
                            </div>

                        <?php endif; ?>

                        <?php if ($settings['menu_style'] == '5'): ?>
                            <div class="elementor-menu-list-item-content">
                                <?php if (!empty($item['image']['url'])) : ?>
                                    <div class="elementor-menu-list-image">
                                        <?php $this->render_image($item, $settings); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="elementor-menu-list-text">
                                    <div class="elementor-menu-list-header">
                                        <?php
                                        $name_html = $item['title'];
                                        if (!empty($item['link']['url'])) :
                                            $name_html = '<a href="' . esc_url($item['link']['url']) . '">' . esc_html($name_html) . '</a>';
                                        endif; ?>
                                        <?php if (!empty($item['sub_title'])) : ?>
                                            <div class="elementor-menu-list-sub-title"><?php printf('%s',$item['sub_title']); ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($item['title'])) : ?>
                                            <?php printf('<div class="elementor-menu-list-title">%s</div>', $name_html); ?>
                                        <?php endif; ?>
                                        <?php if (!empty($item['item_description'])) : ?>
                                            <div class="elementor-menu-list-description"><?php printf('%s',$item['item_description']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($item['price'])) : ?>
                                        <div class="elementor-menu-list-price"><?php printf('%s',$item['price']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

}


$widgets_manager->register(new Vonaco_Elementor_MenuList());