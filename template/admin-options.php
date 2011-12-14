<?php
	if (!empty($message)):
?>

<div class="updated">
	<p>
		<strong><?php echo $message; ?></strong>
	</p>
</div>

<?php
	endif;
?>

<div class="wrap">
	<h2><?php _e( 'SweetCaptcha Settings', 'sweetcaptcha' ); ?></h2>

	<p>
		To get the ID, key, and secret follow the 60 seconds instructions. It's FREE &amp; easy.<br />
		<br />
		1. Go and register for FREE <a target="_blank" href="http://www.sweetcaptcha.com/register.php">here</a>.<br />
		2. Choose your FREE SweetCaptcha design and language.<br />
		3. Copy &amp; Paste the 3 values here. (After you register, you will get this 3 values by mail)<br />
		4. Show off your new SweetCaptcha on our Facebook wall <a target="_blank" href="http://www.facebook.com/SweetCaptcha">here</a> ;-)<br />
		<br />
		Congratulations on your new SweetCaptcha!
	</p>

	<form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		
		<table class="form-table"><tbody>

<?php
	if (!empty($sweetcaptcha_options) && is_array($sweetcaptcha_options)):
		foreach($sweetcaptcha_options as $opt_name => $opt_title):
			
?>

		<tr valign="top">
			<th scope="row" style="min-width: 25%"><label for="<?php echo $opt_name ?>"><?php echo $opt_title . ':'; ?></label></th>
			<?php
				if (!substr_count($opt_name, '_form_')) {
					$type = 'text';
					$checked = null;
					$class = ' class="regular-text"';
					$value = isset($options_values[ $opt_name ]) ? $options_values[ $opt_name ] : null;
				} else {
					$type = 'checkbox';
					$checked = isset($options_values[ $opt_name ]) && !empty($options_values[ $opt_name ]) ? ' checked="checked"' : null;
					$class = null;
					$value = 1;
				}
			?>
			<td>
				<input<?php echo $class?> id="<?php echo $opt_name ?>" type="<?php echo $type ?>" name="<?php echo $opt_name ?>" value="<?php echo $value?>" size="50"<?php echo $checked ?> />
				<?php if (isset($descriptions[ $opt_name ])): ?>
				<span class="description">
					<?php echo $descriptions[ $opt_name ]; ?>
				</span>
				<?php endif; ?>
			</td>
		</tr>

<?php
		endforeach;
	endif;
?>
		</tbody></table>
		
		<p>
			<?php echo __('To integarte SweetCaptcha with Contact Form 7 please do the following:') . '<br />'; ?>
			<?php echo __('A. Copy the following tag with square brackets [sweetcaptcha]') . '<br />'; ?> 
			<?php echo __('B. Open the page with settings of Contact Form 7') . '<br />'; ?>
			<?php echo __('C. Paste the copied tag into "Form" section above the line which contains "&lt;p&gt;[submit "Send"]&lt;/p&gt;"') . '<br />'; ?>
			<?php printf( __('D. Need more help ?  <a href="%s" title="Contact us" target="_blank">Contact us</a>.'), 'http://www.sweetcaptcha.com/contact.php' );?>
		</p>
		
		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>
		<p>
		<strong>How does your site FEEL today? Monitor it for FREE.<br />
		<a href=" http://do.tl/ogw8rg" target="_blank">Jumpple</a> - Your website monitor.Protect your website with JUMPPLE - Jumpple on!.</strong>
		</p>
	</form>
</div>
