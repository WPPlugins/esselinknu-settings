<?PHP
/**
 * Plugin Name: Esselink.nu Settings
 * Plugin URI: http://www.esselink.nu
 * Description: Settings plugin for customs configuration of Esselink.nu WP websites
 * Version: 1.7
 * Author: Esselink.nu
 * Author URI: http://www.esselink.nu
**/


// Block  access to this file
if ( !function_exists( 'add_action' ) ) { 
	exit; 
} 

$plugin = plugin_basename(__FILE__); 


// Initiate 
function esselink_nu_settings_init() {
	// Nothing here
}
add_action('init', 'esselink_nu_settings_init');


// Add settings link on plugin page
function esselink_nu_settings_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=esselink_nu_settings">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
add_filter("plugin_action_links_$plugin", 'esselink_nu_settings_settings_link' );


// Adds admin settings page
require plugin_dir_path( __FILE__ ) . 'includes/admin-settings.php';


///////// START Set plugin settings ////////


// Send email on update
if(get_option('esselink_nu_settings_send_emails') == false)
	add_option( 'esselink_nu_settings_send_emails', 'true', '', 'yes' );
if(get_option('esselink_nu_settings_send_emails') != false && get_option('esselink_nu_settings_send_emails') == "true")
{
	add_filter( 'automatic_updates_send_debug_email', '__return_true' );
}

// Set auto update WP Core
if(get_option('esselink_nu_settings_auto_update_wp') == false)
	add_option( 'esselink_nu_settings_auto_update_wp', 'true', '', 'yes' );
if(get_option('esselink_nu_settings_auto_update_wp') != false && get_option('esselink_nu_settings_auto_update_wp') == "true")
{
	add_filter( 'allow_dev_auto_core_updates', '__return_true' );	// Enable development updates 
	add_filter( 'allow_minor_auto_core_updates', '__return_true' ); // Enable minor updates
	add_filter( 'allow_major_auto_core_updates', '__return_true' ); // Enable major updates
}


// Set auto update Themes
if(get_option('esselink_nu_settings_auto_update_themes') == false)
	add_option( 'esselink_nu_settings_auto_update_themes', 'true', '', 'yes' );
if(get_option('esselink_nu_settings_auto_update_themes') != false && get_option('esselink_nu_settings_auto_update_themes') == "true")
{
	add_filter( 'auto_update_theme', '__return_true' );
}

// Set auto update Plugins
if(get_option('esselink_nu_settings_auto_update_plugins') == false)
	add_option( 'esselink_nu_settings_auto_update_plugins', 'true', '', 'yes' );
function esselink_nu_settings_auto_update_specific_plugins ( $update, $item ) {
	// Check if exclude list exists
	if (get_option('esselink_nu_settings_excluded_plugins') != false)
		$excluded_plugins = get_option('esselink_nu_settings_excluded_plugins');
	else
		return true;


	// Check if plugin is in exclude list
	if ( in_array( $item->plugin, $excluded_plugins ) )
		return false;
	
	return true;
}
if(get_option('esselink_nu_settings_auto_update_plugins') != false && get_option('esselink_nu_settings_auto_update_plugins') == "true")
{
	if( get_option('esselink_nu_settings_excluded_plugins') != false && count(get_option('esselink_nu_settings_excluded_plugins')) > 0 )
	{
		add_filter( 'auto_update_plugin', 'esselink_nu_settings_auto_update_specific_plugins', 10, 2 );
	} else 
	{
		add_filter( 'auto_update_plugin', '__return_true' );
	}	
}

///////// END Set plugin settings ////////



			/*	delete_option( 'esselink_nu_settings_send_emails');
				delete_option( 'esselink_nu_settings_auto_update_wp');
				delete_option( 'esselink_nu_settings_auto_update_themes');
				delete_option( 'esselink_nu_settings_auto_update_plugins');
*/