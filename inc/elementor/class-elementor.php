<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Vonaco_Elementor')) :

    /**
     * The vonaco Elementor Integration class
     */
    class Vonaco_Elementor {
        private $suffix = '';

        public function __construct() {
            $this->suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'register_auto_scripts_frontend']);
            add_action('elementor/init', array($this, 'add_category'));
            add_action('wp_enqueue_scripts', [$this, 'add_scripts'], 15);
            add_action('elementor/widgets/register', array($this, 'customs_widgets'));
            add_action('elementor/widgets/register', array($this, 'include_widgets'));
            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'add_js']);

            // Custom Animation Scroll
            add_filter('elementor/controls/animations/additional_animations', [$this, 'add_animations_scroll']);

            // Elementor Fix Noitice WooCommerce
            add_action('elementor/editor/before_enqueue_scripts', array($this, 'woocommerce_fix_notice'));

            // Backend
            add_action('elementor/editor/after_enqueue_styles', [$this, 'add_style_editor'], 99);

            // Add Icon Custom
            add_action('elementor/icons_manager/native', [$this, 'add_icons_native']);
            add_action('elementor/controls/controls_registered', [$this, 'add_icons']);

            // Add Breakpoints
            add_action('wp_enqueue_scripts', 'vonaco_elementor_breakpoints', 9999);
            // Add Parallax in section
            require get_theme_file_path('inc/elementor/section-parallax.php');

            if (!vonaco_is_elementor_pro_activated()) {
                require trailingslashit(get_template_directory()) . 'inc/elementor/custom-css.php';
                require trailingslashit(get_template_directory()) . 'inc/elementor/sticky-section.php';
                if (is_admin()) {
                    add_action('manage_elementor_library_posts_columns', [$this, 'admin_columns_headers']);
                    add_action('manage_elementor_library_posts_custom_column', [$this, 'admin_columns_content'], 10, 2);
                }
            }

            add_filter('elementor/fonts/additional_fonts', [$this, 'additional_fonts']);
            add_action('wp_enqueue_scripts', [$this, 'elementor_kit']);
        }

        public function elementor_kit() {
            $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
            Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
            $myvals = get_post_meta($active_kit_id, '_elementor_page_settings', true);
            if (!empty($myvals)) {
                $css = '';
                foreach ($myvals['system_colors'] as $key => $value) {
                    $css .= $value['color'] !== '' ? '--' . $value['_id'] . ':' . $value['color'] . ';' : '';
                }

                $var = "body{{$css}}";
                wp_add_inline_style('vonaco-style', $var);
            }
        }

        public function additional_fonts($fonts) {
            $fonts["vonaco heading"] = 'system';
            return $fonts;
        }

        public function admin_columns_headers($defaults) {
            $defaults['shortcode'] = esc_html__('Shortcode', 'vonaco');

            return $defaults;
        }

        public function admin_columns_content($column_name, $post_id) {
            if ('shortcode' === $column_name) {
                ob_start();
                ?>
                <input class="elementor-shortcode-input" type="text" readonly onfocus="this.select()" value="[hfe_template id='<?php echo esc_attr($post_id); ?>']"/>
                <?php
                ob_get_contents();
            }
        }

        public function add_js() {
            global $vonaco_version;
            wp_enqueue_script('vonaco-elementor-frontend', get_theme_file_uri('/assets/js/elementor-frontend.js'), [], $vonaco_version);
        }

        public function add_style_editor() {
            global $vonaco_version;
            wp_enqueue_style('vonaco-elementor-editor-icon', get_theme_file_uri('/assets/css/admin/elementor/icons.css'), [], $vonaco_version);
        }

        public function add_scripts() {
            global $vonaco_version;
            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_enqueue_style('vonaco-elementor', get_template_directory_uri() . '/assets/css/base/elementor.css', '', $vonaco_version);
            wp_style_add_data('vonaco-elementor', 'rtl', 'replace');

            // Add Scripts
            wp_register_script('tweenmax', get_theme_file_uri('/assets/js/vendor/TweenMax.min.js'), array('jquery'), '1.11.1');
            wp_register_script('parallaxmouse', get_theme_file_uri('/assets/js/vendor/jquery-parallax.js'), array('jquery'), $vonaco_version);

            if (vonaco_elementor_check_type('animated-bg-parallax')) {
                wp_enqueue_script('tweenmax');
                wp_enqueue_script('jquery-panr', get_theme_file_uri('/assets/js/vendor/jquery-panr' . $suffix . '.js'), array('jquery'), '0.0.1');
            }
        }


        public function register_auto_scripts_frontend() {
            global $vonaco_version;
            wp_register_script('vonaco-elementor-brand', get_theme_file_uri('/assets/js/elementor/brand.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-button-popup', get_theme_file_uri('/assets/js/elementor/button-popup.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-countdown', get_theme_file_uri('/assets/js/elementor/countdown.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-header-group', get_theme_file_uri('/assets/js/elementor/header-group.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-image-gallery', get_theme_file_uri('/assets/js/elementor/image-gallery.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-menu-list', get_theme_file_uri('/assets/js/elementor/menu-list.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-posts-grid', get_theme_file_uri('/assets/js/elementor/posts-grid.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-product-categories', get_theme_file_uri('/assets/js/elementor/product-categories.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-product-tab', get_theme_file_uri('/assets/js/elementor/product-tab.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-products', get_theme_file_uri('/assets/js/elementor/products.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-reservation-form', get_theme_file_uri('/assets/js/elementor/reservation-form.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-tabs', get_theme_file_uri('/assets/js/elementor/tabs.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-testimonial', get_theme_file_uri('/assets/js/elementor/testimonial.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
            wp_register_script('vonaco-elementor-video', get_theme_file_uri('/assets/js/elementor/video.js'), array('jquery','elementor-frontend'), $vonaco_version, true);
           
        }

        public function add_category() {
            Elementor\Plugin::instance()->elements_manager->add_category(
                'vonaco-addons',
                array(
                    'title' => esc_html__('vonaco Addons', 'vonaco'),
                    'icon'  => 'fa fa-plug',
                ),
                1);
        }

        public function add_animations_scroll($animations) {
            $animations['vonaco Animation'] = [
                'opal-move-up'    => 'Move Up',
                'opal-move-down'  => 'Move Down',
                'opal-move-left'  => 'Move Left',
                'opal-move-right' => 'Move Right',
                'opal-flip'       => 'Flip',
                'opal-helix'      => 'Helix',
                'opal-scale-up'   => 'Scale',
                'opal-am-popup'   => 'Popup',
            ];

            return $animations;
        }

        public function customs_widgets() {
            $files = glob(get_theme_file_path('/inc/elementor/custom-widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        /**
         * @param $widgets_manager Elementor\Widgets_Manager
         */
        public function include_widgets($widgets_manager) {
            $files = glob(get_theme_file_path('/inc/elementor/widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        public function woocommerce_fix_notice() {
            if (vonaco_is_woocommerce_activated()) {
                remove_action('woocommerce_cart_is_empty', 'woocommerce_output_all_notices', 5);
                remove_action('woocommerce_shortcode_before_product_cat_loop', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_account_content', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10);
            }
        }

        public function add_icons( $manager ) {
            $new_icons = json_decode( '{"vonaco-icon-account":"account","vonaco-icon-angle-down":"angle-down","vonaco-icon-angle-left":"angle-left","vonaco-icon-angle-right":"angle-right","vonaco-icon-angle-up":"angle-up","vonaco-icon-arrow-circle-left":"arrow-circle-left","vonaco-icon-arrow-circle":"arrow-circle","vonaco-icon-arrow-down":"arrow-down","vonaco-icon-arrow-left":"arrow-left","vonaco-icon-arrow-right":"arrow-right","vonaco-icon-arrow-up":"arrow-up","vonaco-icon-backtop":"backtop","vonaco-icon-book-call":"book-call","vonaco-icon-calendar-1":"calendar-1","vonaco-icon-calendar":"calendar","vonaco-icon-cart-shopping":"cart-shopping","vonaco-icon-cart":"cart","vonaco-icon-check-square-solid":"check-square-solid","vonaco-icon-chevron-down":"chevron-down","vonaco-icon-chevron-left":"chevron-left","vonaco-icon-chevron-right":"chevron-right","vonaco-icon-chevron-up":"chevron-up","vonaco-icon-clock":"clock","vonaco-icon-compare":"compare","vonaco-icon-cornish":"cornish","vonaco-icon-email-1":"email-1","vonaco-icon-eye":"eye","vonaco-icon-facebook-f":"facebook-f","vonaco-icon-filter-ul":"filter-ul","vonaco-icon-google-plus-g":"google-plus-g","vonaco-icon-heart-1":"heart-1","vonaco-icon-linkedin-in":"linkedin-in","vonaco-icon-list-ul":"list-ul","vonaco-icon-location-1":"location-1","vonaco-icon-location":"location","vonaco-icon-long-arrow-down":"long-arrow-down","vonaco-icon-long-arrow-left":"long-arrow-left","vonaco-icon-long-arrow-right":"long-arrow-right","vonaco-icon-long-arrow-up":"long-arrow-up","vonaco-icon-modish":"modish","vonaco-icon-next-right":"next-right","vonaco-icon-pen":"pen","vonaco-icon-phone-1":"phone-1","vonaco-icon-phone":"phone","vonaco-icon-pin":"pin","vonaco-icon-prev-left":"prev-left","vonaco-icon-quote":"quote","vonaco-icon-rhombus":"rhombus","vonaco-icon-salad":"salad","vonaco-icon-search-1":"search-1","vonaco-icon-shoping":"shoping","vonaco-icon-shopping-bag":"shopping-bag","vonaco-icon-sliders-v":"sliders-v","vonaco-icon-spaghetti":"spaghetti","vonaco-icon-star-1":"star-1","vonaco-icon-th-large":"th-large","vonaco-icon-tweet":"tweet","vonaco-icon-vonaco-plate":"vonaco-plate","vonaco-icon-vonaco-quotes1":"vonaco-quotes1","vonaco-icon-360":"360","vonaco-icon-bars":"bars","vonaco-icon-caret-down":"caret-down","vonaco-icon-caret-left":"caret-left","vonaco-icon-caret-right":"caret-right","vonaco-icon-caret-up":"caret-up","vonaco-icon-cart-empty":"cart-empty","vonaco-icon-check-square":"check-square","vonaco-icon-circle":"circle","vonaco-icon-cloud-download-alt":"cloud-download-alt","vonaco-icon-comment":"comment","vonaco-icon-comments":"comments","vonaco-icon-contact":"contact","vonaco-icon-credit-card":"credit-card","vonaco-icon-dot-circle":"dot-circle","vonaco-icon-edit":"edit","vonaco-icon-envelope":"envelope","vonaco-icon-expand-alt":"expand-alt","vonaco-icon-external-link-alt":"external-link-alt","vonaco-icon-file-alt":"file-alt","vonaco-icon-file-archive":"file-archive","vonaco-icon-filter":"filter","vonaco-icon-folder-open":"folder-open","vonaco-icon-folder":"folder","vonaco-icon-frown":"frown","vonaco-icon-gift":"gift","vonaco-icon-grid":"grid","vonaco-icon-grip-horizontal":"grip-horizontal","vonaco-icon-heart-fill":"heart-fill","vonaco-icon-heart":"heart","vonaco-icon-history":"history","vonaco-icon-home":"home","vonaco-icon-info-circle":"info-circle","vonaco-icon-instagram":"instagram","vonaco-icon-level-up-alt":"level-up-alt","vonaco-icon-list":"list","vonaco-icon-map-marker-check":"map-marker-check","vonaco-icon-meh":"meh","vonaco-icon-minus-circle":"minus-circle","vonaco-icon-minus":"minus","vonaco-icon-mobile-android-alt":"mobile-android-alt","vonaco-icon-money-bill":"money-bill","vonaco-icon-pencil-alt":"pencil-alt","vonaco-icon-play-circle":"play-circle","vonaco-icon-play":"play","vonaco-icon-plus-circle":"plus-circle","vonaco-icon-plus":"plus","vonaco-icon-random":"random","vonaco-icon-reply-all":"reply-all","vonaco-icon-reply":"reply","vonaco-icon-search-plus":"search-plus","vonaco-icon-search":"search","vonaco-icon-shield-check":"shield-check","vonaco-icon-shopping-basket":"shopping-basket","vonaco-icon-shopping-cart":"shopping-cart","vonaco-icon-sign-out-alt":"sign-out-alt","vonaco-icon-smile":"smile","vonaco-icon-spinner":"spinner","vonaco-icon-square":"square","vonaco-icon-star":"star","vonaco-icon-store":"store","vonaco-icon-sync":"sync","vonaco-icon-tachometer-alt":"tachometer-alt","vonaco-icon-th-list":"th-list","vonaco-icon-thumbtack":"thumbtack","vonaco-icon-ticket":"ticket","vonaco-icon-times-circle":"times-circle","vonaco-icon-times":"times","vonaco-icon-trophy-alt":"trophy-alt","vonaco-icon-truck":"truck","vonaco-icon-user-headset":"user-headset","vonaco-icon-user-shield":"user-shield","vonaco-icon-user":"user","vonaco-icon-video":"video","vonaco-icon-adobe":"adobe","vonaco-icon-amazon":"amazon","vonaco-icon-android":"android","vonaco-icon-angular":"angular","vonaco-icon-apper":"apper","vonaco-icon-apple":"apple","vonaco-icon-atlassian":"atlassian","vonaco-icon-behance":"behance","vonaco-icon-bitbucket":"bitbucket","vonaco-icon-bitcoin":"bitcoin","vonaco-icon-bity":"bity","vonaco-icon-bluetooth":"bluetooth","vonaco-icon-btc":"btc","vonaco-icon-centos":"centos","vonaco-icon-chrome":"chrome","vonaco-icon-codepen":"codepen","vonaco-icon-cpanel":"cpanel","vonaco-icon-discord":"discord","vonaco-icon-dochub":"dochub","vonaco-icon-docker":"docker","vonaco-icon-dribbble":"dribbble","vonaco-icon-dropbox":"dropbox","vonaco-icon-drupal":"drupal","vonaco-icon-ebay":"ebay","vonaco-icon-facebook":"facebook","vonaco-icon-figma":"figma","vonaco-icon-firefox":"firefox","vonaco-icon-google-plus":"google-plus","vonaco-icon-google":"google","vonaco-icon-grunt":"grunt","vonaco-icon-gulp":"gulp","vonaco-icon-html5":"html5","vonaco-icon-joomla":"joomla","vonaco-icon-link-brand":"link-brand","vonaco-icon-linkedin":"linkedin","vonaco-icon-mailchimp":"mailchimp","vonaco-icon-opencart":"opencart","vonaco-icon-paypal":"paypal","vonaco-icon-pinterest-p":"pinterest-p","vonaco-icon-reddit":"reddit","vonaco-icon-skype":"skype","vonaco-icon-slack":"slack","vonaco-icon-snapchat":"snapchat","vonaco-icon-spotify":"spotify","vonaco-icon-trello":"trello","vonaco-icon-twitter":"twitter","vonaco-icon-vimeo":"vimeo","vonaco-icon-whatsapp":"whatsapp","vonaco-icon-wordpress":"wordpress","vonaco-icon-yoast":"yoast","vonaco-icon-youtube":"youtube"}', true );
			$icons     = $manager->get_control( 'icon' )->get_settings( 'options' );
			$new_icons = array_merge(
				$new_icons,
				$icons
			);
			// Then we set a new list of icons as the options of the icon control
			$manager->get_control( 'icon' )->set_settings( 'options', $new_icons ); 
        }

        public function add_icons_native($tabs) {
            global $vonaco_version;
            $tabs['opal-custom'] = [
                'name'          => 'vonaco-icon',
                'label'         => esc_html__('vonaco Icon', 'vonaco'),
                'prefix'        => 'vonaco-icon-',
                'displayPrefix' => 'vonaco-icon-',
                'labelIcon'     => 'fab fa-font-awesome-alt',
                'ver'           => $vonaco_version,
                'fetchJson'     => get_theme_file_uri('/inc/elementor/icons.json'),
                'native'        => true,
            ];

            return $tabs;
        }
    }

endif;

return new Vonaco_Elementor();
