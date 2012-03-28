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
	width: 200px;
	min-width: 25%;
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
			jQuery('#register').submit();
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
