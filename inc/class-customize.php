<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Vonaco_Customize')) {

    class  Vonaco_Customize {


        public function __construct() {
            add_action('customize_register', array($this, 'customize_register'));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         */
        public function customize_register($wp_customize) {

            /**
             * Theme options.
             */
            require_once get_theme_file_path('inc/customize-control/editor.php');
            $this->init_vonaco_blog($wp_customize);

            $this->init_vonaco_social($wp_customize);

            if (vonaco_is_woocommerce_activated()) {
                $this->init_woocommerce($wp_customize);
            }

            do_action('vonaco_customize_register', $wp_customize);
        }


        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_vonaco_blog($wp_customize) {

            $wp_customize->add_section('vonaco_blog_archive', array(
                'title' => esc_html__('Blog', 'vonaco'),
            ));

            // =========================================
            // Select Style
            // =========================================

            $wp_customize->add_setting('vonaco_options_blog_style', array(
                'type'              => 'option',
                'default'           => 'standard',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_blog_style', array(
                'section' => 'vonaco_blog_archive',
                'label'   => esc_html__('Blog style', 'vonaco'),
                'type'    => 'select',
                'choices' => array(
                    'standard' => esc_html__('Blog Standard', 'vonaco'),
                    //====start_premium
                    'style-1'  => esc_html__('Blog Marsonry', 'vonaco'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('vonaco_options_blog_columns', array(
                'type'              => 'option',
                'default'           => 1,
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_blog_columns', array(
                'section' => 'vonaco_blog_archive',
                'label'   => esc_html__('Colunms', 'vonaco'),
                'type'    => 'select',
                'choices' => array(
                    1 => esc_html__('1', 'vonaco'),
                    2 => esc_html__('2', 'vonaco'),
                    3 => esc_html__('3', 'vonaco'),
                    4 => esc_html__('4', 'vonaco'),
                ),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_vonaco_social($wp_customize) {

            $wp_customize->add_section('vonaco_social', array(
                'title' => esc_html__('Socials', 'vonaco'),
            ));
            $wp_customize->add_setting('vonaco_options_social_share', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_social_share', array(
                'type'    => 'checkbox',
                'section' => 'vonaco_social',
                'label'   => esc_html__('Show Social Share', 'vonaco'),
            ));
            $wp_customize->add_setting('vonaco_options_social_share_facebook', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_social_share_facebook', array(
                'type'    => 'checkbox',
                'section' => 'vonaco_social',
                'label'   => esc_html__('Share on Facebook', 'vonaco'),
            ));
            $wp_customize->add_setting('vonaco_options_social_share_twitter', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_social_share_twitter', array(
                'type'    => 'checkbox',
                'section' => 'vonaco_social',
                'label'   => esc_html__('Share on Twitter', 'vonaco'),
            ));
            $wp_customize->add_setting('vonaco_options_social_share_linkedin', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_social_share_linkedin', array(
                'type'    => 'checkbox',
                'section' => 'vonaco_social',
                'label'   => esc_html__('Share on Linkedin', 'vonaco'),
            ));
            $wp_customize->add_setting('vonaco_options_social_share_google-plus', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_social_share_google-plus', array(
                'type'    => 'checkbox',
                'section' => 'vonaco_social',
                'label'   => esc_html__('Share on Google+', 'vonaco'),
            ));

            $wp_customize->add_setting('vonaco_options_social_share_pinterest', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_social_share_pinterest', array(
                'type'    => 'checkbox',
                'section' => 'vonaco_social',
                'label'   => esc_html__('Share on Pinterest', 'vonaco'),
            ));
            $wp_customize->add_setting('vonaco_options_social_share_email', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_social_share_email', array(
                'type'    => 'checkbox',
                'section' => 'vonaco_social',
                'label'   => esc_html__('Share on Email', 'vonaco'),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_woocommerce($wp_customize) {

            $wp_customize->add_panel('woocommerce', array(
                'title' => esc_html__('Woocommerce', 'vonaco'),
            ));

            $wp_customize->add_section('vonaco_woocommerce_archive', array(
                'title'      => esc_html__('Archive', 'vonaco'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
                'priority'   => 1,
            ));

            $wp_customize->add_setting('vonaco_options_woocommerce_archive_layout', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_woocommerce_archive_layout', array(
                'section' => 'vonaco_woocommerce_archive',
                'label'   => esc_html__('Layout Style', 'vonaco'),
                'type'    => 'select',
                'choices' => array(
                    'default'   => esc_html__('Sidebar', 'vonaco'),
                    'canvas'    => esc_html__('Canvas Filter', 'vonaco'),
                    'dropdown'  => esc_html__('Dropdown Filter', 'vonaco'),
                    'fullwidth' => esc_html__('Full Width', 'vonaco'),
                ),
            ));

            $wp_customize->add_setting('vonaco_options_woocommerce_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'left',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_woocommerce_archive_sidebar', array(
                'section' => 'vonaco_woocommerce_archive',
                'label'   => esc_html__('Sidebar Position', 'vonaco'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'vonaco'),
                    'right' => esc_html__('Right', 'vonaco'),

                ),
            ));

            // =========================================
            // Single Product
            // =========================================

            $wp_customize->add_section('vonaco_woocommerce_single', array(
                'title'      => esc_html__('Single Product', 'vonaco'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('vonaco_options_single_product_gallery_layout', array(
                'type'              => 'option',
                'default'           => 'horizontal',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('vonaco_options_single_product_gallery_layout', array(
                'section' => 'vonaco_woocommerce_single',
                'label'   => esc_html__('Style', 'vonaco'),
                'type'    => 'select',
                'choices' => array(
                    'horizontal' => esc_html__('Horizontal', 'vonaco'),
                    //====start_premium
                    'vertical'   => esc_html__('Vertical', 'vonaco'),
                    //====end_premium
                ),
            ));


            // =========================================
            // Product
            // =========================================

            $wp_customize->add_section('vonaco_woocommerce_product', array(
                'title'      => esc_html__('Product Block', 'vonaco'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('vonaco_options_wocommerce_block_style', array(
                'type'              => 'option',
                'default'           => '1',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('vonaco_options_wocommerce_block_style', array(
                'section' => 'vonaco_woocommerce_product',
                'label'   => esc_html__('Style', 'vonaco'),
                'type'    => 'select',
                'choices' => array(
                    '1' => esc_html__('Style 1', 'vonaco')
                ),
            ));

            $wp_customize->add_setting('vonaco_options_woocommerce_product_hover', array(
                'type'              => 'option',
                'default'           => 'none',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('vonaco_options_woocommerce_product_hover', array(
                'section' => 'vonaco_woocommerce_product',
                'label'   => esc_html__('Animation Image Hover', 'vonaco'),
                'type'    => 'select',
                'choices' => array(
                    'none'          => esc_html__('None', 'vonaco'),
                    'bottom-to-top' => esc_html__('Bottom to Top', 'vonaco'),
                    'top-to-bottom' => esc_html__('Top to Bottom', 'vonaco'),
                    'right-to-left' => esc_html__('Right to Left', 'vonaco'),
                    'left-to-right' => esc_html__('Left to Right', 'vonaco'),
                    'swap'          => esc_html__('Swap', 'vonaco'),
                    'fade'          => esc_html__('Fade', 'vonaco'),
                    'zoom-in'       => esc_html__('Zoom In', 'vonaco'),
                    'zoom-out'      => esc_html__('Zoom Out', 'vonaco'),
                ),
            ));

            $wp_customize->add_setting('vonaco_options_wocommerce_row_laptop', array(
                'type'              => 'option',
                'default'           => 3,
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_wocommerce_row_laptop', array(
                'section' => 'woocommerce_product_catalog',
                'label'   => esc_html__('Products per row Laptop', 'vonaco'),
                'type'    => 'number',
            ));

            $wp_customize->add_setting('vonaco_options_wocommerce_row_tablet', array(
                'type'              => 'option',
                'default'           => 2,
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_wocommerce_row_tablet', array(
                'section' => 'woocommerce_product_catalog',
                'label'   => esc_html__('Products per row tablet', 'vonaco'),
                'type'    => 'number',
            ));

            $wp_customize->add_setting('vonaco_options_wocommerce_row_mobile', array(
                'type'              => 'option',
                'default'           => 1,
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('vonaco_options_wocommerce_row_mobile', array(
                'section' => 'woocommerce_product_catalog',
                'label'   => esc_html__('Products per row mobile', 'vonaco'),
                'type'    => 'number',
            ));
        }
    }
}
return new Vonaco_Customize();
