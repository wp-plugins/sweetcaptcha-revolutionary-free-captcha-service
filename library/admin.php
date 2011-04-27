<?php

// settings definiiton
$sweetcaptcha_options = array(
    'sweetcaptcha_app_id'				=> __('Application ID', 'sweetcaptcha'),
    'sweetcaptcha_key'					=> __('SweetCaptcha Key', 'sweetcaptcha'),
    'sweetcaptcha_secret'				=> __('SweetCaptcha Secret', 'sweetcaptcha'),
    'sweetcaptcha_public_url'			=> __('SweetCaptcha Public URL', 'sweetcaptcha'),
    'sweetcaptcha_form_ommit_users'		=> __('Ommit captcha for registered users', 'sweetcaptcha'),
    'sweetcaptcha_form_registration'	=> __('SweetCaptcha for Registration Form', 'sweetcaptcha'),
    'sweetcaptcha_form_comment'			=> __('SweetCaptcha for Comment Form', 'sweetcaptcha'),
    'sweetcaptcha_form_login'			=> __('SweetCaptcha for Login Form', 'sweetcaptcha'),
    'sweetcaptcha_form_lost'			=> __('SweetCaptcha for Lost Password Form', 'sweetcaptcha'),
    'sweetcaptcha_form_contact_7'		=> __('SweetCaptcha for Contact Form 7', 'sweetcaptcha')
);

/**
 * Add SweetCaptcha settings link to admin menu
 * @return void
 */
function sweetcaptcha_admin_menu() {
	add_options_page(__('SweetCaptcha','sweetcaptcha'), __('SweetCaptcha','sweetcaptcha'), 'manage_options', 'sweetcaptcha', 'sweetcaptcha_options_page');
}

/**
 * SweetCaptcha options page logic
 * @return void
 */
function sweetcaptcha_options_page() {
	global $sweetcaptcha_options;
	
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $opt_name = 'mt_favorite_color';
    $hidden_field_name = 'mt_submit_hidden';
    $data_field_name = 'mt_favorite_color';
    
    $descriptions = array(
    	'sweetcaptcha_app_id'				=> __('Insert your Application ID', 'sweetcaptcha'),
    	'sweetcaptcha_key'					=> __('Insert SweetCaptcha Key', 'sweetcaptcha'),
    	'sweetcaptcha_secret'				=> __('Insert SweetCaptcha Secret', 'sweetcaptcha'),
    	'sweetcaptcha_public_url'			=> __('Default values is "/wp-content/plugins/sweetcaptcha/library/sweetcaptcha.php" - don\'t change it unless you know what are you doing.', 'sweetcaptcha'),
    	'sweetcaptcha_form_ommit_users'		=> __('Disable SweetCaptcha for registered users.', 'sweetcaptcha'),
    	'sweetcaptcha_form_registration'	=> __('Enable SweetCaptcha for registration form.', 'sweetcaptcha'),
    	'sweetcaptcha_form_comment'			=> __('Enable SweetCaptcha for comment form.', 'sweetcaptcha'),
    	'sweetcaptcha_form_login'			=> __('Enable SweetCaptcha for login form.', 'sweetcaptcha'),
    	'sweetcaptcha_form_lost'			=> __('Enable SweetCaptcha for lost password form.', 'sweetcaptcha'),
    	'sweetcaptcha_form_contact_7'		=> __('Enable SweetCaptcha for contact form 7 plug-in.', 'sweetcaptcha')
    );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
    	$rs = TRUE;
    	
        // Read their posted value
        foreach($sweetcaptcha_options as $opt_name => $v) {
        	$opt_val = isset( $_POST[ $opt_name  ] ) ? $_POST[ $opt_name ] : null;
        	
        	// Save the posted value in the database
        	update_option( $opt_name, $opt_val );
        }

        // Put an settings updated message on the screen
        $message = $rs ? __('settings saved.', 'sweetcaptcha' ) : __('settings cannot be saved.', 'sweetcaptcha' );
    }
    
    // Read in existing option value from database
    $options_values = sweetcaptcha_options();
    
    require_once SWEETCAPTCHA_TEMPLATE . '/admin-options.php';
}

/**
 * Get all SweetCaptcha options values as asociative array
 * @return array
 */
function sweetcaptcha_options() {
	global $sweetcaptcha_options;
	
    $options_values = array();
    
    foreach($sweetcaptcha_options as $opt_name => $opt_title) {
    	$options_values[ $opt_name ] = get_option( $opt_name );
    }
    
    return $options_values;
}

/**
 * SweetCaptcha plug-in activation hook
 * @return void
 */
function sweetcaptcha_activate() {
	$sweetcaptcha_defaults = array(
	    'sweetcaptcha_app_id'				=> '',
	    'sweetcaptcha_key'					=> '',
	    'sweetcaptcha_secret'				=> '',
	    'sweetcaptcha_public_url'			=> '/wp-content/plugins/sweetcaptcha/library/sweetcaptcha.php',
	    'sweetcaptcha_form_ommit_users'		=> '1',
	    'sweetcaptcha_form_registration'	=> '1',
	    'sweetcaptcha_form_comment'			=> '1',
	    'sweetcaptcha_form_login'			=> '1',
	    'sweetcaptcha_form_lost'			=> '1',
	    'sweetcaptcha_form_contact_7'		=> '1',
		'sweetcaptcha_installed'			=> '1'
	);
	
	if ( !get_option( 'sweetcaptcha_installed') ) {
	    foreach($sweetcaptcha_defaults as $opt_name => $opt_val) {
	    	$opt_curr_val = get_option( $opt_name );
	    	
	    	if ( empty($opt_curr_val) ) {
	    		update_option( $opt_name, $opt_val );
	    	}
	    }
	}
}

/**
 * Delete SweetCaptcha options from database
 * @return void
 */
function sweetcaptcha_uninstall() {
	global $sweetcaptcha_options;
	
	foreach($sweetcaptcha_options as $opt_name => $opt_title) {
    	delete_option($opt_name);
    }
}
