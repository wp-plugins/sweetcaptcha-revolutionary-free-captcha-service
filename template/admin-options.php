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
  <p>Congratulations on your new SweetCaptcha!</p>
	
<!-- TODO: the patch by SverAlex, to make it work with all themes and in all WP -->
<div style="font-style: italic; margin-left: 10px; background: #eeeeee; padding:6px 4px 4px 4px; height:52px; width:410px; 
  -moz-box-shadow:    -2px -2px 10px 2px #E2E2E2;
  -webkit-box-shadow: -2px -2px 10px 2px #E2E2E2;
  box-shadow:         -2px -2px 10px 2px #E2E2E2;
">
  <div style="float:left;border:0px solid red;">
  If you like this plugin and find it useful, help <br>keep this plugin free and actively developed <br>
  by clicking the <a href="javascript:void(0)" onclick="document.formDonate.submit();">donate</a> button.
  </div>
  <!--<a style="display: block; float:left;margin-left: 10px;" href="http://www.paypal.com/" target="_new">
    <img style="width:100px; height:48px;" src="<?php //echo plugins_url('donate-paypal-100x48.png', __FILE__); ?>" alt="Donate with PayPal"/>
  </a>-->
  <div style="float:right; border:0px solid red; padding:0; margin:0;margin-top: -2px;">
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="formDonate" target="_blank">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="KJ9FG7STBXQ76">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img style="width:1; height:1px; border:none; padding:0; margin:0;" alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
  </form>
  </div>
  
</div>
<!-- End of the patch -->

  
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
		<strong>How does your site FEEL today? Download this awsome FREE plugin.<br />
		<a href="http://wordpress.org/extend/plugins/Jumpple/" target="_blank">Jumpple</a> - Your website monitor.Protect your website with JUMPPLE - Jumpple on!.</strong>
		</p>
	</form>
</div>
