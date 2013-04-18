<?php

// Set Plugin Defaults
function plus_one_activate() {
	$plus_one_options = array(
		'plus_one_on_posts' 	=> '1',
		'plus_one_on_pages' 	=> '1',
		'plus_one_on_home' 		=> '1',
		'plus_one_location' 	=> '2',
		'plus_one_size' 		=> '3',
		'plus_one_language' 	=> 'en-US',
		'plus_one_count' 		=> '1',
		'plus_one_parse' 		=> '1',
		'plus_one_js_callback' 	=> '',
		'plus_one_url' 			=> ''
	);
	add_option( 'plus_one_options', $plus_one_options );
}

function plus_one_deactivate() {
	//delete_option( 'plus_one_options' );
}

function plus_one_defaults() {
	delete_option( 'plus_one_options' );
	plus_one_activate();
}

function plus_one_create_menu() {
	add_submenu_page( 'options-general.php', __('Google +1 Button for WordPress', 'plus-one'), __('Plus One', 'plus-one'), 'administrator', 'plus-one', 'plus_one_settings_page' );
	add_action( 'admin_init', 'register_mysettings' );
}
add_action( 'admin_menu', 'plus_one_create_menu' );

function register_mysettings() {
	register_setting( 'plus-one-settings-group', 'plus_one_options' );
}

function plus_one_settings_page() { ?>
	
	<div class="wrap">
		
		<h2><?php _e('Google +1 Button for WordPress', 'plus-one'); ?></h2>
		
		<?php
		if ( isset($_POST['plus-one-restore-defaults']) ) :
			plus_one_defaults();
			
			?>
			<div class="updated settings-error" id="setting-error-settings_updated"> 
			<p><strong><?php _e('Settings restored', 'plus-one'); ?>.</strong></p></div>
			<?php
			
		endif;
		?>
		
		<h3><?php _e('Button Preview', 'plus-one'); ?></h3>
		
		<div id="plus-one-preview">
			
			<?php echo plus_one_button(); ?>
			
			<br />
			<br />
			
			<span><strong><?php _e('Code', 'plus-one'); ?>:</strong> <?php _e('(for manual use)', 'plus-one'); ?></span>
			
			<textarea class="large-text"><?php echo plus_one_button($admin = true); plus_one_print_footer_scripts($explicit = true); ?></textarea>
			
		</div>

		<form method="post" action="options.php">
			
		    <?php settings_fields( 'plus-one-settings-group' ); ?>
		
			<?php $plus_one_options = get_option( 'plus_one_options' ); ?>
		
		    <table class="form-table">
			
			    <tr valign="top">
		        <th scope="row"><h3><?php _e('General options', 'plus-one'); ?></h3></th>
				</tr>
				
				<tr valign="top">
		        <th scope="row"><?php _e('Posts', 'plus-one'); ?>:</th>
		        <td>
					
					<?php
					if ( !isset($plus_one_options['plus_one_on_posts']) )
						$plus_one_options['plus_one_on_posts'] = false;
					?>
					
					<input type="checkbox" name="plus_one_options[plus_one_on_posts]" value="1"<?php checked( '1' == $plus_one_options['plus_one_on_posts'] ); ?> />
				
				</td>
		        </tr>
		
		        <tr valign="top">
		        <th scope="row"><?php _e('Pages', 'plus-one'); ?>:</th>
		        <td>
					
					<?php
					if ( !isset($plus_one_options['plus_one_on_pages']) )
						$plus_one_options['plus_one_on_pages'] = false;
					?>
					
					<input type="checkbox" name="plus_one_options[plus_one_on_pages]" value="1"<?php checked( '1' == $plus_one_options['plus_one_on_pages'] ); ?> />
				
				</td>
		        </tr>
		
				<th scope="row"><?php _e('Hide on Home Page', 'plus-one'); ?>:</th>
		        <td>
					
					<select name="plus_one_options[plus_one_on_home]">
					    <option value="1" <?php selected( $plus_one_options['plus_one_on_home'], 1 ); ?>><?php _e('Disabled', 'plus-one'); ?></option>
					    <option value="2" <?php selected( $plus_one_options['plus_one_on_home'], 2 ); ?>><?php _e('Enabled', 'plus-one'); ?></option>
					</select>
				
				</td>
		        </tr>
		
		        <tr valign="top">
		        <th scope="row"><?php _e('Location', 'plus-one'); ?>:</th>
		        <td>
				
					<input type="radio" name="plus_one_options[plus_one_location]" value="1"<?php checked( '1' == $plus_one_options['plus_one_location'] ); ?> />
					<?php _e('Above', 'plus-one'); ?>
					<br />
					<input type="radio" name="plus_one_options[plus_one_location]" value="2"<?php checked( '2' == $plus_one_options['plus_one_location'] ); ?> />
					<?php _e('Below', 'plus-one'); ?>
					<br />
					<input type="radio" name="plus_one_options[plus_one_location]" value="3"<?php checked( '3' == $plus_one_options['plus_one_location'] ); ?> />
					<?php _e('Both', 'plus-one'); ?>
					
				</td>
		        </tr>
		
		        <tr valign="top">
		        <th scope="row"><?php _e('Size', 'plus-one'); ?>:</th>
		        <td>
				
					<input type="radio" name="plus_one_options[plus_one_size]" value="1"<?php checked( '1' == $plus_one_options['plus_one_size'] ); ?> />
					<?php _e('Small', 'plus-one'); ?>
					<br />
					<input type="radio" name="plus_one_options[plus_one_size]" value="2"<?php checked( '2' == $plus_one_options['plus_one_size'] ); ?> />
					<?php _e('Medium', 'plus-one'); ?>
					<br />
					<input type="radio" name="plus_one_options[plus_one_size]" value="3"<?php checked( '3' == $plus_one_options['plus_one_size'] ); ?> />
					<?php _e('Standard', 'plus-one'); ?>
					<br />
					<input type="radio" name="plus_one_options[plus_one_size]" value="4"<?php checked( '4' == $plus_one_options['plus_one_size'] ); ?> />
					<?php _e('Tall', 'plus-one'); ?>
				
				</td>
		        </tr>
		
				<th scope="row"><?php _e('Language', 'plus-one'); ?>:</th>
		        <td>
					
					<select name="plus_one_options[plus_one_language]">
						
						<?php
						
						$plus_one_languages = array(
							'ar' 		=> __('Arabic', 'plus-one'),
							'bg' 		=> __('Bulgarian', 'plus-one'),
							'ca' 		=> __('Catalan', 'plus-one'),
							'zh-CN' 	=> __('Chinese (Simplified)', 'plus-one'),
							'zh-TW' 	=> __('Chinese (Traditional)', 'plus-one'),
							'hr' 		=> __('Croatian', 'plus-one'),
							'cs' 		=> __('Czech', 'plus-one'),
							'da' 		=> __('Danish', 'plus-one'),
							'nl' 		=> __('Dutch', 'plus-one'),
							'en-GB' 	=> __('English (UK)', 'plus-one'),
							'en-US' 	=> __('English (US)', 'plus-one'),
							'et' 		=> __('Estonian', 'plus-one'),
							'fil' 		=> __('Filipino', 'plus-one'),
							'fi' 		=> __('Finnish', 'plus-one'),
							'fr' 		=> __('French', 'plus-one'),
							'de' 		=> __('German', 'plus-one'),
							'el' 		=> __('Greek', 'plus-one'),
							'iw' 		=> __('Hebrew', 'plus-one'),
							'hi' 		=> __('Hindi', 'plus-one'),
							'hu' 		=> __('Hungarian', 'plus-one'),
							'id' 		=> __('Indonesian', 'plus-one'),
							'it' 		=> __('Italian', 'plus-one'),
							'ja' 		=> __('Japanese', 'plus-one'),
							'ko' 		=> __('Korean', 'plus-one'),
							'lv' 		=> __('Latvian', 'plus-one'),
							'lt' 		=> __('Lithuanian', 'plus-one'),
							'ms' 		=> __('Malay', 'plus-one'),
							'no' 		=> __('Norwegian', 'plus-one'),
							'fa' 		=> __('Persian', 'plus-one'),
							'pl' 		=> __('Polish', 'plus-one'),
							'pt-BR' 	=> __('Portuguese (Brazil)', 'plus-one'),
							'pt-PT' 	=> __('Portuguese (Portugal)', 'plus-one'),
							'ro' 		=> __('Romanian', 'plus-one'),
							'ru' 		=> __('Russian', 'plus-one'),
							'sr' 		=> __('Serbian', 'plus-one'),
							'sk' 		=> __('Slovak', 'plus-one'),
							'sl' 		=> __('Slovenian', 'plus-one'),
							'es' 		=> __('Spanish', 'plus-one'),
							'es-419' 	=> __('Spanish (Latin America)', 'plus-one'),
							'sv' 		=> __('Swedish', 'plus-one'),
							'th' 		=> __('Thai', 'plus-one'),
							'tr' 		=> __('Turkish', 'plus-one'),
							'uk' 		=> __('Ukrainian', 'plus-one'),
							'vi' 		=> __('Vietnamese', 'plus-one')
						);
						
						foreach ($plus_one_languages as $key => $language) : ?>
						
					    	<option value="<?php echo $key ?>" <?php selected( $plus_one_options['plus_one_language'], $key ); ?>><?php echo $language; ?></option>
					
						<?php endforeach; ?>
					
					</select>
				
				</td>
		        </tr>
		
			    <tr valign="top">
		        <th scope="row"><h3><?php _e('Advanced options', 'plus-one'); ?></h3></th>
				</tr>
		
		        <tr valign="top">
		        <th scope="row"><?php _e('Include count', 'plus-one'); ?>:</th>
		        <td>
					
					<?php
					if ( !isset($plus_one_options['plus_one_count']) )
						$plus_one_options['plus_one_count'] = false;
					?>
					
					<input type="checkbox" name="plus_one_options[plus_one_count]" value="1"<?php checked( '1' == $plus_one_options['plus_one_count'] ); ?> />
				
				</td>
		        </tr>
				
				<th scope="row"><?php _e('Parse', 'plus-one'); ?>:</th>
		        <td>
					
					<select name="plus_one_options[plus_one_parse]">
					    <option value="1" <?php selected( $plus_one_options['plus_one_parse'], 1 ); ?>><?php _e('Default (On Load)', 'plus-one'); ?></option>
					    <option value="2" <?php selected( $plus_one_options['plus_one_parse'], 2 ); ?>><?php _e('Explicit', 'plus-one'); ?></option>
					</select>
				
				</td>
		        </tr>
		
		        <tr valign="top">
		        <th scope="row"><?php _e('JS Callback function', 'plus-one'); ?>:</th>
		        <td>
					
					<input type="text" name="plus_one_options[plus_one_js_callback]" value="<?php echo $plus_one_options['plus_one_js_callback']; ?>" />
				
				</td>
		        </tr>
		
				<?php /*
				
				<tr valign="top">
		        <th scope="row"><?php _e('URL to +1', 'plus-one'); ?>:</th>
		        <td>
					
					<input type="text" name="plus_one_options[plus_one_url]" value="<?php echo $plus_one_options['plus_one_url']; ?>" />
				
				</td>
		        </tr>
		
				*/ ?>

		    </table>
    
		    <p class="submit">
		    	<input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
		    </p>

		</form>
		
		<form method="post">
			
			<input type="hidden" name="plus-one-restore-defaults" value="1" />
			
		    <p class="submit">
		    	<input type="submit" class="button" value="<?php _e('Restore Default Settings', 'plus-one'); ?>" />
		    </p>

		</form>
		
		<p><a title="<?php _e('More information', 'plus-one'); ?>" targe="_blank" href="http://code.google.com/apis/+1button/#target-url"><?php _e('More information', 'plus-one'); ?></a> <?php _e('on URLs and the +1 button', 'plus-one'); ?></p>
		
		<p><?php _e('A WordPress plugin by', 'plus-one'); ?> <a title="Metronet Norge" target="_blank" href="http://metronet.no/">Metronet Norge</a></p>
	
	</div>
	
<?php } ?>