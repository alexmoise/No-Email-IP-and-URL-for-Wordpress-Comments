<?php
/**
 * Uninstall of the No Email, IP and URL for Wordpress Comments plugin.
 * Version: 1.0.7
 */
if ( ! defined( 'ABSPATH' ) ) 			{exit(0);}
if ( ! defined('WP_UNINSTALL_PLUGIN')) 	{die;}
// checking if removal is requested
if ( get_option( 'moneiuwc_delete_options_uninstall' ) ) {
	//defining options to remove as variables ..
		$moneiuwc_option_remove_email							= 'moneiuwc_remove_email';
		$moneiuwc_option_require_name							= 'moneiuwc_require_name';
		$moneiuwc_option_remove_website							= 'moneiuwc_remove_website';
		$moneiuwc_option_remove_empty_notes						= 'moneiuwc_empty_notes';
		$moneiuwc_option_remove_discard_ip						= 'moneiuwc_discard_ip';
		$moneiuwc_option_remove_remove_cookie					= 'moneiuwc_disable_cookie';
		$moneiuwc_option_remove_display_first_settings_notice	= 'moneiuwc_display_first_settings_notice';
		$moneiuwc_option_remove_delete_options_uninstall		= 'moneiuwc_delete_options_uninstall';
	// removing the options ..
		delete_option( $moneiuwc_option_remove_email );
		delete_option( $moneiuwc_option_require_name );
		delete_option( $moneiuwc_option_remove_website );
		delete_option( $moneiuwc_option_remove_empty_notes );
		delete_option( $moneiuwc_option_remove_discard_ip );
		delete_option( $moneiuwc_option_remove_remove_cookie );
		delete_option( $moneiuwc_option_remove_display_first_settings_notice );
		delete_option( $moneiuwc_option_remove_delete_options_uninstall );
}
?>
