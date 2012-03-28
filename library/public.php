<?php

/**
 * Add SweetCaptcha jQuery - version >= 1.4 is required to login pages
 * @return void
 */
 
function sweetcaptcha_login_head() {
	global $wp_version;
	$wp_versions = explode( '.', $wp_version );
	
	if ( ( $wp_versions[ 0 ] >= 2 ) && ( $wp_versions[ 1 ] >= 9 ) ) {
		
    //edited voodoo
    $jquery = get_option('home') . '/wp-includes/js/jquery/jquery.js';
	} else {
	   // edited voodoo
		$jquery = get_option('home') . '/wp-content/plugins/sweetcaptcha-revolutionary-free-captcha-service/js/jquery.min.js';
	}
	 // edited voodoo
	 
	echo '<script type="text/javascript" src="' . $jquery . '"></script>';
	
}

/**
 * Add Sweetcaptcha jQuery - version >= 1.4 is required to wordpress pages
 * @return void
 */
function sweetcaptcha_wp_head() {
	//edited voodoo
	wp_enqueue_script( 'jquery' );
  //echo '<script type="text/javascript" src="' . get_bloginfo('siteurl') . '/wp-content/plugins/sweetcaptcha/js/jquery.min.js"></script>';
}

/**
 * Get SweetCaptcha values from POST data
 * @return array
 */
function sweetcaptcha_get_values() {
	return array(
		'sckey'		=> ( isset( $_POST[ 'sckey' ] ) ? $_POST[ 'sckey' ] : '' ),
		'scvalue'	=> ( isset( $_POST[ 'scvalue' ] ) ? $_POST[ 'scvalue' ] : '' ),
		'scvalue2'	=> ( isset( $_POST[ 'scvalue2' ] ) ? $_POST[ 'scvalue2' ] : '' ),
	);
}

/**
 * Move submit button under SweetCaptcha field.
 * @return string
 */
function sweetcaptcha_move_submit_button() {
	return '<div id="sweetcaptcha-submit-button" class="form-submit"><br /></div>'.
		'<script type="text/javascript">'.
			'var sub = document.getElementById("submit");'.
			'if (sub!=undefined){'.
			'sub.parentNode.removeChild(sub);'.
			'document.getElementById("sweetcaptcha-submit-button").appendChild (sub);'.
			'document.getElementById("submit").tabIndex = 6;}'.
		'</script>';
}

/**
 * Add SweetCaptcha to comment form
 * @return boolean
 */
function sweetcaptcha_comment_form() {
	global $sweetcaptcha_instance, $user_ID, $wp_version;
	
	$wp_versions = explode( '.', $wp_version );
	
	if ( get_option( 'sweetcaptcha_form_ommit_users' ) && isset($user_ID) && (int)$user_ID > 0 ) {
		return TRUE;
	}
	
	echo $sweetcaptcha_instance->get_html();
	echo sweetcaptcha_move_submit_button();
	
	if ( $wp_versions[ 0 ] >= 3 && $wp_versions[ 1 ] >= 0 ) { 
		echo '<script language="JavaScript">document.getElementById("respond").style.overflow="visible";</script>';
	}
	
	remove_action( 'comment_form', 'sweetcaptcha_comment_form' );
	
	return TRUE;
}

/**
 * Add SweetCaptcha check submitted comment form
 * @return boolean
 */
function sweetcaptcha_comment_form_check($comment) {
	global $sweetcaptcha_instance, $user_ID;
	
	if ( get_option( 'sweetcaptcha_form_ommit_users' ) && isset($user_ID) && (int)$user_ID > 0 ) {
		return $comment;
	}
	
	if ( !empty( $comment[ 'comment_type' ] ) && ( $comment[ 'comment_type' ] != 'comment' ) ) {
		return $comment;
	}
	
	$scValues = sweetcaptcha_get_values();
	
	if ( $sweetcaptcha_instance->check($scValues) == 'true' ) {
        return $comment;
	} else {
		// since 2.0.4
		if (function_exists('wp_die')) {
			wp_die('<strong>' . __( 'ERROR', 'sweetcaptcha' ) . '</strong>: ' . __( 'The solution of task you submitted was incorrect. Please read the instruction and try again.', 'sweetcaptcha' ) );
		} else {
			die('<strong>' . __( 'ERROR', 'sweetcaptcha' ) . '</strong>: ' . __( 'The solution of task you submitted was incorrect. Please read the instruction and try again.', 'sweetcaptcha' ));
		}
	}
}

/**
 * Add SweetCaptcha to login form
 * @return boolean
 */
function sweetcaptcha_login_form() {
	global $sweetcaptcha_instance;

	if (!get_option('sweetcaptcha_form_login')) {
		return true;
	}
	
	echo $sweetcaptcha_instance->get_html();
	echo '<script language="JavaScript">if (document.getElementById("login")) { document.getElementById("login").style.width = "582px"; } jQuery(document).ready(function(){ jQuery("#sidebar-login-form #captchi li").css("display","block"); jQuery("#sidebar-login-form #captchi").css("max-height","500px");});</script><br>';
	
	return true;
}

/**
 * Add SweetCaptcha authetificate check
 * @param $user
 * @return WP_Error
 */
function sweetcaptcha_authenticate($user) {
	global $sweetcaptcha_instance;
	
	if (!get_option('sweetcaptcha_form_login')) {
		return $user;
	}

	$scValues = sweetcaptcha_get_values();
	if ( !empty( $_POST ) && $sweetcaptcha_instance->check($scValues) != 'true' ) {
		$user = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'sweetcaptcha' ) . '</strong>: ' . __( 'The solution of task you submitted was incorrect. Please read the instruction and try again.', 'sweetcaptcha' ) );
	}
	
	return $user;
}

/**
 * Add SweetCaptcha lost password check
 * @param $user
 * @return mixed WP_Error or boolean
 */
function sweetcaptcha_lost_password_check($user) {
	global $sweetcaptcha_instance;
	
	$scValues = sweetcaptcha_get_values();
	
	if ( $sweetcaptcha_instance->check($scValues) != 'true' ) {
		$user = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'sweetcaptcha' ) . '</strong>: ' . __('The solution of task you submitted was incorrect. Please read the instruction and try again.', 'sweetcaptcha' ) );
		return $user;
	}
	
	return TRUE;
}

/**
 * Add SweetCaptcha registration form check
 * @param $errors
 * @return WP_Errors
 */
function sweetcaptcha_register_form_check($errors) {
	global $sweetcaptcha_instance;
	
	$scValues = sweetcaptcha_get_values();
	
	if ( $sweetcaptcha_instance->check($scValues) != 'true' ) {
		$errors->add( 'captcha_wrong', '<strong>' . __( 'ERROR', 'sweetcaptcha' ) . '</strong>: ' . __('The solution of task you submitted was incorrect. Please read the instruction and try again.', 'sweetcaptcha' ) );			
	}
	
	return $errors;
}

/**
 * Add SweetCaptcha to BuddyPress registration form
 * @return boolean
 */
function sweetcaptcha_before_registration_submit_buttons() {
	global $sweetcaptcha_instance;
	
	echo '<div style="clear: both;">' . $sweetcaptcha_instance->get_html() . '</div>';
	
	return TRUE;
}

/**
 * Add SweetCaptcha to BuddyPress registration form validation
 * @return boolean
 */
function sweetcaptcha_signup_validate() {
	global $bp, $sweetcaptcha_instance;
	
	$scValues = sweetcaptcha_get_values();
	
	if ( $sweetcaptcha_instance->check($scValues) != 'true' ) {
		$bp->signup->errors['signup_username'] = __('The solution of task you submitted was incorrect. Please read the instruction and try again.', 'sweetcaptcha' );
	}
}

/**
 * Add SweetCaptcha to Wordpress Network sign-up form 
 * @param $errors
 * @return boolean
 */
function sweetcaptcha_signup_extra_fields($errors) {
	global $sweetcaptcha_instance;
	
	$error = $errors->get_error_message( 'captcha_wrong' );
	
	echo $sweetcaptcha_instance->get_html();
	
	if ( isset($error) && !empty( $error ) ) {
		echo '<p class="error">' . $error . '</p>';
	}

	return true;
}

/**
 * Add SweetCaptcha validation to Wordpress Network sign-up form
 * @param $errors
 * @return mixed
 */
function sweetcaptcha_wpmu_validate_user_signup($errors) {
	global $sweetcaptcha_instance;
	
	if ( $_POST['stage'] == 'validate-user-signup' ) {
		$scValues = sweetcaptcha_get_values();
	
		if ( $sweetcaptcha_instance->check( $scValues ) != 'true' ) {
			$errors['errors']->add( 'captcha_wrong', '<strong>' . __( 'ERROR', 'sweetcaptcha' ) . '</strong>: ' . __('The solution of task you submitted was incorrect. Please read the instruction and try again.', 'sweetcaptcha' ) );
		}
		
	}
	
	return $errors;
}

/**
 * Add SweetCaptcha short code
 * @param $atts
 * @return string
 */
function sweetcaptcha_shortcode( $atts ) {
	global $sweetcaptcha_instance;
	return $sweetcaptcha_instance->get_html();
}

/**
 * Validate SweetCaptcha form
 * @param $errors
 * @param $tag
 * @return mixed array
 */
function sweetcaptcha_validate($errors, $tag = NULL) {
	global $sweetcaptcha_instance;

	$scValues = sweetcaptcha_get_values();
	if ( $sweetcaptcha_instance->check( $scValues ) != 'true' ) {
		if ( !empty( $tag ) ) { // if Contact Form 7
			$errors['valid'] = false;
			$errors['reason']['your-message'] = __('The solution of task you submitted was incorrect. Please read the instruction and try again.', 'sweetcaptcha' );
		} else {
			$errors['errors']->add( 'sweetcaptcha', '<strong>' . __( 'ERROR', 'sweetcaptcha' ) . '</strong>: ' . __('The solution of task you submitted was incorrect. Please read the instruction and try again.', 'sweetcaptcha' ) );
		}
	}
	
	return $errors;
}
