<?php
/*
Plugin Name: SweetCaptcha
Plugin URI: http://www.sweetcaptcha.com
Description: Adds SweetCaptcha anti-spam solution to WordPress on the comment form, registration form, and other forms. Is compatible with Contact Form 7 and BuddyPress plug-ins. Wordpress network is also supported.
Version: 1.0.8
Author: Sweet Captcha.com ltd.
Author URI: http://www.sweetcaptcha.com
License: GNU GPL2
*/

/*
Copyright (C) 2010 SweetCaptcha.com ltd. (www.sweetcaptcha.com). All rights reserved.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License,
version 2, as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// for backward compatibility - 2.0
defined('WP_PLUGIN_DIR') or
	define('WP_PLUGIN_DIR', ABSPATH . '/wp-content/plugins');

// define absolute path to plugin
define('SWEETCAPTCHA_ROOT', WP_PLUGIN_DIR . '/sweetcaptcha-revolutionary-free-captcha-service');
// define absolute path to plugin  sweetcaptcha.php
define('SWEETCAPTCHA_PHP_PATH', WP_PLUGIN_URL . '/sweetcaptcha-revolutionary-free-captcha-service/library/sweetcaptcha.php');
// define absolute path to plugin library
define('SWEETCAPTCHA_LIBRARY', SWEETCAPTCHA_ROOT . '/library');
// define absolute path to plugin templates
define('SWEETCAPTCHA_TEMPLATE', SWEETCAPTCHA_ROOT . '/template');

// prepare wordpress version for check
$wp_versions = explode( '.', $wp_version );

require_once SWEETCAPTCHA_LIBRARY . '/sweetcaptcha.php';

// split action to admin and public part
if (is_admin()) {
	require_once SWEETCAPTCHA_LIBRARY . '/admin.php';
	
	// Add admin notices.
	add_action('admin_notices', 'sweetcaptcha_admin_notices');

	// add link to settings menu
	add_action('admin_menu', 'sweetcaptcha_admin_menu');
	
	// add activation hook -> default option values - XXX - doesn't work since wp 3.1 and below 2.8
	//register_activation_hook( __FILE__, 'sweetcaptcha_activate' );
	
	// because various problems with register activation hook trough Wordpress versions - check if Sweet Captcha is installed, otherwise set default values
	add_action('admin_menu', 'sweetcaptcha_activate');
	
	// add uninstall hook -> remove option values - only for wordpress version >= 2.7
	if ( ( $wp_versions[ 0 ] >=2 ) && ( $wp_versions[ 1 ] >= 7 ) ) {
		register_uninstall_hook( __FILE__, 'sweetcaptcha_uninstall' );
	}

} else {
	
	require_once SWEETCAPTCHA_LIBRARY . '/public.php';
	
	// add jquery to all public pages
 

		add_action( 'login_head' , 'sweetcaptcha_login_head' );
    wp_enqueue_script( 'jquery' );

	
	// add Sweet Captcha to comment form
	if ( get_option( 'sweetcaptcha_form_comment' ) ) {
		add_action( 'comment_form', 'sweetcaptcha_comment_form', 1 );
		add_filter( 'preprocess_comment', 'sweetcaptcha_comment_form_check', 1 );
	}
	
	// add Sweet Captcha to login form
	if ( get_option( 'sweetcaptcha_form_registration' ) ) {
		// only for version >= 2.8
		if ( ( ( $wp_versions[ 0 ] >= 2 ) || ( $wp_versions[ 1 ] > 7 ) ) ) {
			add_action( 'login_form', 'sweetcaptcha_login_form', 1 );
			add_filter( 'authenticate', 'sweetcaptcha_authenticate', 40, 3 );
		}
	}
	
	// add Sweet Captcha to lost password form
	if ( get_option( 'sweetcaptcha_form_lost' ) ) {
		add_action( 'lostpassword_form', 'sweetcaptcha_login_form', 1 );
		add_filter( 'allow_password_reset', 'sweetcaptcha_lost_password_check', 1 );
	}
	
	// add Sweet Captcha to registration form
	if ( get_option( 'sweetcaptcha_form_registration' ) ) {
		add_action('register_form', 'sweetcaptcha_login_form' );
		add_filter('registration_errors', 'sweetcaptcha_register_form_check' );
		
		add_action('bp_before_registration_submit_buttons', 'sweetcaptcha_before_registration_submit_buttons' );
		add_action('bp_signup_validate', 'sweetcaptcha_signup_validate' );
		
		// adding SweetCaptcha to Network Wordpress registration form - only version >= 3
		if ( ( $wp_versions[ 0 ] > 2 ) ) {
			add_action('signup_extra_fields', 'sweetcaptcha_signup_extra_fields' );
			add_filter('wpmu_validate_user_signup', 'sweetcaptcha_wpmu_validate_user_signup' );
		}
	}
	
	// add SweetCaptcha to BuddyPress login form
	add_action( 'bp_sidebar_login_form', 'sweetcaptcha_login_form' );
}

// add SweetCaptcha to Contact Form 7 - to both admin and public part of Wordpress
if ( get_option( 'sweetcaptcha_form_contact_7' ) ) {
	if ( function_exists( 'wpcf7_add_shortcode' ) ) {
		wpcf7_add_shortcode( 'sweetcaptcha', 'sweetcaptcha_shortcode', true );
		add_filter( 'wpcf7_validate_sweetcaptcha', 'sweetcaptcha_validate', 10, 2 );
	}
}

// load plugin text domain - i18n
if ( function_exists( 'load_plugin_textdomain' ) ) {
	load_plugin_textdomain( 'sweetcaptcha', true, 'sweetcaptcha/languages' );
}