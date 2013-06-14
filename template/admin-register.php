<style type="text/css">
#register_message {
	margin-bottom: 10px;
}

#register_explanation {
	margin-top: 10px;
}

#register table {
	border-collapse: collapse;
	width: 100%;
}

#register table td.left {
	text-transform: capitalize;
	padding-left: 10px;
	text-align: left;
	width: 160px;
	/*min-width: 25%;*/
}

#register table td.right {
	text-align: left;
	padding: 8px 10px;
}

#register input[type=text] {
	width: 300px;
}

#register select {
	width: 300px;
}

</style>
<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery('#register_submit').click(function () {
  		jQuery("form#register .error").remove();
    	var hasError = false;
      jQuery(".requiredField").each(function() {
  			if(jQuery.trim(jQuery(this).val()) == '') {
          if ( jQuery(this).attr('name') == 'site_category' ) {
            jQuery(this).after('<span class="error">Please choose something and then press `Continue`</span>');
          } else {
            jQuery(this).after('<span class="error">*</span>');
          }
        	hasError = true;
  			} else 
        if ( jQuery(this).attr('name') == 'email' ) {
    			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      		if ( ! emailReg.test(jQuery.trim(jQuery(this).val())) ) {
        		jQuery(this).after('<span class="error">You entered an invalid email</span>');
  					hasError = true;
    			}
      	}
      });
      if ( ! hasError ) {
        jQuery('form#register').submit();
      }
  });
});

</script>
<div class="wrap">
	<h2><?php _e( 'Register to SweetCaptcha', 'sweetcaptcha' ); ?></h2>

	<?php echo $form_html; ?>

	<p class="submit">
		<input type="button" id="register_submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Continue') ?>" />
		&nbsp;or <a href="options-general.php?page=sweetcaptcha&skip_register=1">skip</a> if you are already registered.
	</p>
</div>
