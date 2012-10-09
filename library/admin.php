<?php

// settings definiiton
$sweetcaptcha_options = array(
    'sweetcaptcha_app_id'				=> __('Application ID', 'sweetcaptcha'),
    'sweetcaptcha_key'					=> __('SweetCaptcha Key', 'sweetcaptcha'),
    'sweetcaptcha_secret'				=> __('SweetCaptcha Secret', 'sweetcaptcha'),
//    'sweetcaptcha_public_url'			=> __('SweetCaptcha Public URL', 'sweetcaptcha'),
    'sweetcaptcha_form_ommit_users'		=> __('Ommit captcha for registered users', 'sweetcaptcha'),
    'sweetcaptcha_form_registration'	=> __('SweetCaptcha for Registration Form', 'sweetcaptcha'),
    'sweetcaptcha_form_comment'			=> __('SweetCaptcha for Comment Form', 'sweetcaptcha'),
    'sweetcaptcha_form_login'			=> __('SweetCaptcha for Login Form', 'sweetcaptcha'),
    'sweetcaptcha_form_lost'			=> __('SweetCaptcha for Lost Password Form', 'sweetcaptcha'),
    'sweetcaptcha_form_contact_7'		=> __('SweetCaptcha for Contact Form 7', 'sweetcaptcha')
);

/**
 * @return true if SweetCaptcha is properly registered.
 */
function sweetcaptcha_is_registered() {
	return ((get_option('sweetcaptcha_app_id', '')) && (get_option('sweetcaptcha_key', '')) && (get_option('sweetcaptcha_secret', '')));
}

/**
 * Display admin notices.
 * @return void
 */
function sweetcaptcha_admin_notices() {
	// If the plugin is not configured yet.
	if (!sweetcaptcha_is_registered()) {
		echo '<div class="error sweetcaptcha" style="text-align: center;"><p style="color: red; font-size: 14px; font-weight: bold;">' . __( 'Your SweetCaptcha plugin is not setup yet' ) . '</p><p>' .  __( 'Click ' ) . '<a href="options-general.php?page=sweetcaptcha">' . __( 'here' ) . '</a> ' .  __( 'to finish setup.' ) . '</p></div>';
	}
}

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
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

	$skip_resgister = ((isset($_REQUEST['skip_register'])) && ($_REQUEST['skip_register'] == 1));
	if ((sweetcaptcha_is_registered()) || ($skip_resgister)) {
		sweetcatpcha_main_settings();
	} else {
		sweetcaptcha_register_form();
	}
}


/**
 * Displays the SweetCaptcha register form.
 * @return void
 */
function sweetcaptcha_register_form() {
	global $sweetcaptcha_instance;

    $hidden_field_name = 'mt_submit_hidden';
	$form_html = 'Could not load registration form.';

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if((isset($_POST[$hidden_field_name])) && ($_POST[$hidden_field_name] == 'Y')) {
		$result = json_decode($sweetcaptcha_instance->submit_register_form($_POST), true);
		if ($result['error']) {
			if (!empty($result['html'])) {
				$form_html = $result['html'];
			}
		} else {
			update_option('sweetcaptcha_app_id', $result['app_id']);
			update_option('sweetcaptcha_key', $result['key']);
			update_option('sweetcaptcha_secret', $result['secret']);

			// Load the main options page, and ignore the post data (since it's missing all the options!).
			sweetcatpcha_main_settings(true);

			// Hide the "your plugin is not set up yet" message.
			echo "
				<script type=\"text/javascript\" language=\"javascript\">
					jQuery('div.error.sweetcaptcha').hide();
				</script>
			";
			return;
		}
	}
	else
	{
		$form_html = $sweetcaptcha_instance->get_register_form();
	}

	// Fill the fields.
	$website = json_encode(empty($_POST['website']) ? "http://{$_SERVER['SERVER_NAME']}/" : $_POST['website']);
	$email = json_encode($_POST['email']);
	$form_html .= "<script type=\"text/javascript\" language=\"javascript\">\n";
	$form_html .= "    jQuery('input[name=website]').val($website);\n";
	$form_html .= "    jQuery('input[name=email]').val($email);\n";

	if (isset($_POST['language'])) {
		$language = (int)$_POST['language'];
		$form_html .= "    jQuery('select[name=language]').val($language);\n";
	}
	if (isset($_POST['category'])) {
		$category = (int)$_POST['category'];
		$form_html .= "    jQuery('select[name=category]').val($category);\n";
	}
	$form_html .= "</script>\n";


    require_once SWEETCAPTCHA_TEMPLATE . '/admin-register.php';

	// Display share buttons.
	sweetaptcha_share_buttons();
}


/**
 * Displays the main SweetCaptcha settings.
 * @return void
 */
function sweetcatpcha_main_settings($ignore_post = false) {
	global $sweetcaptcha_options;
	
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
    if ((!$ignore_post) && (isset($_POST[ $hidden_field_name ])) && ($_POST[ $hidden_field_name ] == 'Y')) {
    	$rs = TRUE;
    	
        // Read their posted value
        foreach($sweetcaptcha_options as $opt_name => $v) {
        	$opt_val = isset( $_POST[ $opt_name  ] ) ? $_POST[ $opt_name ] : null;
        	
        	// Save the posted value in the database
        	update_option( $opt_name, $opt_val );
        }

        // Put an settings updated message on the screen
		$saved_html = 'settings saved.';
		if (sweetcaptcha_is_registered()) {
			$saved_html .= "
				<script type=\"text/javascript\" language=\"javascript\">
					jQuery( 'div.error.sweetcaptcha' ).hide();
				</script>
			";
		}
        $message = $rs ? __($saved_html, 'sweetcaptcha' ) : __('settings cannot be saved.', 'sweetcaptcha' );
    }
    
    // Read in existing option value from database
    $options_values = sweetcaptcha_options();
    
    require_once SWEETCAPTCHA_TEMPLATE . '/admin-options.php';

	// Display share buttons.
	sweetaptcha_share_buttons();
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
	    'sweetcaptcha_form_login'			=> '',
	    'sweetcaptcha_form_lost'			=> '1',
	    'sweetcaptcha_form_contact_7'		=> '1',
		'sweetcaptcha_installed'			=> '1',
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
	// These do not appear in the global .
	delete_option('sweetcaptcha_installed');
}

/**
 * Display the facebook and tweeter share buttons
 * @return void
 */
function sweetaptcha_share_buttons() {
?>

<div id="share">
<a name="fb_share" class="fb-share" type="button_count" href="#" onclick="window.open( 'http://www.facebook.com/sharer.php?u=http%3A%2F%2Fwww.sweetcaptcha.com&amp;t=Check%20this%20cool%20service%20out!', 'sharer', 'toolbar=0, status=0, width=626, height=436' ); return false;"><img src="<?php echo plugins_url('fbshare.jpg', dirname(__FILE__)); ?>" alt="Share" style="vertical-align: middle;" /></a>

<a href="http://twitter.com/share" class="twitter-share-button" data-count="none" data-text="Check out this cool service!" data-via="sweetcaptcha">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
</div>

<style type="text/css">
#share { text-align: left; padding-bottom: 2px; }

.twitter-share-button, fb-share { vertical-align: middle }
</style>

<?php
}
