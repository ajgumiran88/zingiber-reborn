<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;

class Vonaco_Elementor_Reservation_Form extends Elementor\Widget_Base
{

    public function get_name()
    {
        return 'vonaco-reservation-form';
    }

    public function get_title()
    {
        return esc_html__('Vonaco Reservation Form', 'vonaco');
    }

    public function get_categories()
    {
        return array('vonaco-addons');
    }

    public function get_icon()
    {
        return 'eicon-form-horizontal';
    }

    public function get_script_depends()
    {
        return ['vonaco-elementor-reservation-form', 'jquery-ui-datepicker'];
    }

    public function get_style_depends()
    {
        return ['jquery-ui-smoothness'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_id',
            [
                'label' => esc_html__('ID', 'vonaco'),
            ]
        );

        $this->add_control(
            'reservation_id',
            [
                'label' => esc_html__('ID', 'vonaco'),
                'type' => Controls_Manager::TEXT,
                'default' => '1',
            ]
        );

        $this->add_control(
            'reservation_restref',
            [
                'label' => esc_html__('Restref', 'vonaco'),
                'type' => Controls_Manager::TEXT,
                'default' => '1',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_person',
            [
                'label' => esc_html__('Person', 'vonaco'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'person_title_value',
            [
                'label' => esc_html__('Person Value', 'vonaco'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'person_title',
            [
                'label' => esc_html__('Person', 'vonaco'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'person',
            [
                'label' => esc_html__('Person', 'vonaco'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'person_title_value' => esc_html__('1', 'vonaco'),
                        'person_title' => esc_html__('1 Person', 'vonaco'),
                    ],
                    [
                        'person_title_value' => esc_html__('2', 'vonaco'),
                        'person_title' => esc_html__('2 Person', 'vonaco'),
                    ],
                    [
                        'person_title_value' => esc_html__('3', 'vonaco'),
                        'person_title' => esc_html__('3 Person', 'vonaco'),
                    ],
                ],
                'title_field' => '{{{ person_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_time',
            [
                'label' => esc_html__('Time', 'vonaco'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'time_title_value',
            [
                'label' => esc_html__('Time Value', 'vonaco'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'time_title',
            [
                'label' => esc_html__('Time', 'vonaco'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'time',
            [
                'label' => esc_html__('Time', 'vonaco'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'time_title_value' => esc_html__('07:00', 'vonaco'),
                        'time_title' => esc_html__('07:00 pm', 'vonaco'),
                    ],
                    [
                        'time_title_value' => esc_html__('08:00', 'vonaco'),
                        'time_title' => esc_html__('08:00 pm', 'vonaco'),
                    ],
                    [
                        'time_title_value' => esc_html__('09:00', 'vonaco'),
                        'time_title' => esc_html__('09:00 pm', 'vonaco'),
                    ],
                ],
                'title_field' => '{{{ time_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__('Button', 'vonaco'),
            ]
        );

        $this->add_control(
            'button_value',
            [
                'label' => esc_html__('Value', 'vonaco'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Book a table'
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => esc_html__('Icon', 'vonaco'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'button_description',
            [
                'label' => esc_html__('Description', 'vonaco'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'button_image',
            [
                'label' => esc_html__('Choose Image', 'vonaco'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="vonaco-reservation-form">
            <form target="_blank" class="opentable-form" action="https://www.opentable.com/restaurant-search.aspx">
                <input type="hidden" name="rid" class="rid"
                       value="<?php echo esc_html($settings['reservation_id']); ?>">
                <input type="hidden" name="restref" class="restref"
                       value="<?php echo esc_html($settings['reservation_restref']); ?>">
                <input type="hidden" name="txtDateFormat" class="txtDateFormat" value="dd/mm/yyyy">
                <div class="row" data-elementor-columns="3" data-elementor-columns-tablet="3"
                     data-elementor-columns-mobile="1">
                    <div class="column-item">
                        <div class="select-item">
                            <i class="vonaco-icon-account"></i>
                            <select name="partysize" class="select-person">
                                <?php foreach ($settings['person'] as $person): ?>
                                    <option value="<?php printf('%s',$person['person_title_value']); ?>"><?php echo esc_html($person['person_title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                    <div class="column-item">
                        <div class="select-item">
                            <i class="vonaco-icon-calendar-1"></i>
                            <input class="vonaco-datepicker" name="startDate" type="text" data-dateformat="dd/mm/yy"
                                   autocomplete="off" placeholder="MM/DD/YYYY">
                        </div>

                    </div>
                    <div class="column-item">
                        <div class="select-item">
                            <i class="vonaco-icon-clock"></i>
                            <select name="ResTime" class="select-time">
                                <?php foreach ($settings['time'] as $person): ?>
                                    <option value="<?php printf('%s',$person['time_title_value']); ?>"><?php echo esc_html($person['time_title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <?php if (!empty($settings['button_value'])): ?>
                        <button class="btn-reservation" type="submit" name="button">
                            <span><?php echo esc_html($settings['button_value']); ?></span>
                            <?php \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true']); ?>
                        </button>
                    <?php endif; ?>

                    <?php if (!empty($settings['button_description']) || !empty($settings['button_image']['url'])): ?>
                        <div class="button-description">
                            <span><?php echo esc_html($settings['button_description']); ?></span>
                               <?php echo Group_Control_Image_Size::get_attachment_image_html($settings, 'full', 'button_image'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <?php
    }

}

$widgets_manager->register(new Vonaco_Elementor_Reservation_Form());