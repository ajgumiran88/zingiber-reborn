<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Vonaco_Login' ) ) :
	class Vonaco_Login {
		public function __construct() {
			add_action( 'wp_ajax_vonaco_login', array( $this, 'ajax_login' ) );
			add_action( 'wp_ajax_nopriv_vonaco_login', array( $this, 'ajax_login' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );
		}

		public function scripts(){
			global $vonaco_version;
			wp_enqueue_script( 'vonaco-ajax-login', get_template_directory_uri() . '/assets/js/frontend/login.js', array('jquery'), $vonaco_version, true );
		}

		public function ajax_login() {
			do_action( 'vonaco_ajax_verify_captcha' );
			check_ajax_referer( 'ajax-vonaco-login-nonce', 'security-login' );
			$info                  = array();
			$info['user_login']    = $_REQUEST['username'];
			$info['user_password'] = $_REQUEST['password'];
			$info['remember']      = $_REQUEST['remember'];

			$user_signon = wp_signon( $info, false );
			if ( is_wp_error( $user_signon ) ) {
				wp_send_json( array(
					'status' => false,
					'msg'    => esc_html__( 'Wrong username or password. Please try again!!!', 'vonaco' )
				) );
			} else {
				wp_set_current_user( $user_signon->ID );
				wp_send_json( array(
					'status' => true,
					'msg'    => esc_html__( 'Signin successful, redirecting...', 'vonaco' )
				) );
			}
		}
	}
new Vonaco_Login();
endif;
