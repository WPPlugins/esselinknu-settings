<?PHP

// Block  access to this file
if ( !function_exists( 'add_action' ) ) { 
	exit; 
}


// Adds settings link to menu
function esselink_nu_settings_add_pages() {
	add_options_page( 'Esselink.nu', 'Esselink.nu', 'manage_options', 'esselink_nu_settings', 'esselink_nu_settings_page'	);
}
add_action( 'admin_menu', 'esselink_nu_settings_add_pages' );

// Check if value is boolean
function checkBoolean($val)
{
	if(strtolower($val) == "true")
		return "true";
	return "false";
}

function checkPluginExists($val)
{
	$current_plugins = get_plugins();
	
	if($current_plugins[$val] != null)
		return $val;
	
	return null;
}

// Admin setting page
function esselink_nu_settings_page() {
	$current_plugins = get_plugins();
?>
	<div class="wrap">
		<h2>Esselink.nu Settings</h2><p>
	
		<?PHP	
			if ( ($_SERVER['REQUEST_METHOD'] === 'POST') && wp_verify_nonce($_POST['esselink_nu_settings'],'esselink_nu_settings') ) {


				// Save settings
				update_option( 'esselink_nu_settings_send_emails', checkBoolean($_POST['esselink_nu_settings_send_emails']), 'true' );
				update_option( 'esselink_nu_settings_auto_update_wp', checkBoolean($_POST['esselink_nu_settings_auto_update_wp']), 'true' );
				update_option( 'esselink_nu_settings_auto_update_themes', checkBoolean($_POST['esselink_nu_settings_auto_update_themes']), 'true' );
				update_option( 'esselink_nu_settings_auto_update_plugins', checkBoolean($_POST['esselink_nu_settings_auto_update_plugins']), 'true' );
	
				// Save plugins to exclude
				$excluded_plugins = array();
				foreach ($_POST as  $key => $val) {
					if (strpos($key,'_php'))
					{
						$plugin = checkPluginExists(str_replace ('_php','.php', $key));
						if($plugin != null)
							$excluded_plugins[] = $plugin;
					}
				}


				update_option( 'esselink_nu_settings_excluded_plugins', $excluded_plugins, 'true' );		
	

				// Done
				echo "<div class='updated settings-error'><p><strong>Settings saved</strong></p></div>";
			}
	
			$excluded_plugins = get_option('esselink_nu_settings_excluded_plugins');
			if(!isset($excluded_plugins) || $excluded_plugins == "" )
				$excluded_plugins = array();
			

		?>

		<form  action="options-general.php?page=esselink_nu_settings" method="post">	

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="esselink_nu_settings_send_emails">Send Email for Automatic Updates</label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>Send Email for Automatic Updates</span>
								</legend>
								<label for="esselink_nu_settings_send_emails">
									<input name="esselink_nu_settings_send_emails" type="checkbox" <?= (get_option('esselink_nu_settings_send_emails')=='true') ? "checked" : "" ?> id="esselink_nu_settings_send_emails" value="true">Yes
								</label>
							</fieldset>
						</td>
					</tr>			
					<tr>
						<th scope="row">Automatically update WordPress Core</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>Automatically update WordPress Core</span>
								</legend>
								<label for="esselink_nu_settings_auto_update_wp">
									<input name="esselink_nu_settings_auto_update_wp" type="checkbox" <?= (get_option('esselink_nu_settings_auto_update_wp')=='true') ? "checked" : "" ?> id="esselink_nu_settings_auto_update_wp" value="true">Yes
								</label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Automatically update Themes</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>Automatically update Themes</span>
								</legend>
								<label for="esselink_nu_settings_auto_update_themes">
									<input name="esselink_nu_settings_auto_update_themes" type="checkbox" <?= (get_option('esselink_nu_settings_auto_update_themes')=='true') ? "checked" : "" ?> id="esselink_nu_settings_auto_update_themes" value="true">Yes
								</label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Automatically update Plugins</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>Automatically update Plugins</span>
								</legend>
								<label for="esselink_nu_settings_auto_update_plugins">
									<input name="esselink_nu_settings_auto_update_plugins" type="checkbox" <?= (get_option('esselink_nu_settings_auto_update_plugins')=='true') ? "checked" : "" ?> id="esselink_nu_settings_auto_update_plugins" value="true">Yes
								</label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Exclude Plugin from update</th>
						<td>
							<fieldset>
						<?PHP
							foreach ($current_plugins as  $key => $val) {
								if($key != "esselinknu-settings/esselink-nu-settings.php")
								{
						?>
								<label for="<?= $key; ?>">
									<input type="checkbox" name="<?= $key; ?>" id="<?= $key; ?>" <?= (isset($excluded_plugins) && in_array($key, $excluded_plugins)) ? "checked" : ""; ?>><?= $val["Name"]; ?>
								</label>
								<br>
						<?PHP
								}
							}
						?>
							</fieldset>
						</td>
					</tr>
				</tbody>
			</table>
		
			<?php 
				wp_nonce_field('esselink_nu_settings','esselink_nu_settings');
			?>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  />
			</p>
		</form>

	</div>
	<?PHP

}