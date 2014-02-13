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
  (function($){
    $(document).ready(function() {
      $('#register_submit').click(function () {
          $("form#register .error").remove();
          var hasError = false;
          $(".requiredField").each(function() {
            if($.trim($(this).val()) == '') {
              if ( $(this).attr('name') == 'site_category' ) {
                $(this).after('<span class="error">Please choose something and then press `Continue`</span>');
              } else {
                $(this).after('<span class="error">*</span>');
              }
              hasError = true;
            } else 
            if ( $(this).attr('name') == 'email' ) {
              var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
              if ( ! emailReg.test($.trim($(this).val())) ) {
                $(this).after('<span class="error">You entered an invalid email</span>');
                hasError = true;
              }
            }
          });
          if ( ! hasError ) {
            $('form#register').submit();
          }
      });
    });
  })(jQuery);
</script>

<div class="wrap">
	<h2><?php _e( 'Register to SweetCaptcha', 'sweetcaptcha' ); ?></h2>

	<?php echo $form_html; ?>

	<p class="submit">
		<input type="button" id="register_submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Continue') ?>" />
		&nbsp;or <a href="options-general.php?page=sweetcaptcha&skip_register=1">skip</a> if you are already registered.
	</p>
</div>
