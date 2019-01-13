<?php
/**
 * Plugin Name: No Email, IP and URL for Wordpress Comments
 * Plugin URI: https://github.com/alexmoise/No-Email-IP-and-URL-for-Wordpress-Comments
 * GitHub Plugin URI: https://github.com/alexmoise/No-Email-IP-and-URL-for-Wordpress-Comments
 * Description: A quite simple plugin to remove Email and Website fields from comments area and also stop collecting the commenter's IP address. Also disables comment system cookie and empties comment notes text. Now including a settings page to selectively disable or enable any of these Wordpress Comments features as needed.
 * Version: 1.0.4
 * Author: Alex Moise
 * Author URI: https://moise.pro
 */

if ( ! defined( 'ABSPATH' ) ) {	exit(0);}

// automatically disable and enable the "Comment author must fill out name and email" option depending on Remove Email field option with which is not compatible 
if ( isset( $_GET['settings-updated'] ) ) { add_action( 'admin_notices', 'mo_deactivate_require_name_email' ); }
function mo_deactivate_require_name_email() {
	if ( get_option ( 'moneiuwc_remove_email' ) == 1 )  {
		update_option( 'require_name_email', '0' );
		echo '<div class="notice-info notice" style="margin-left: 0px;"><p>The <strong>Comment author must fill out name and email</strong> option has been <strong>disabled</strong> in the <a href="'.get_site_url().'/wp-admin/options-discussion.php">Comments settings</a> page since it is not compatible with <strong>Remove Email field</strong>.</p></div>';
	}
	if ( get_option ( 'moneiuwc_remove_email' ) == 0 )  {
		update_option( 'require_name_email', '1' );
		echo '<div class="notice-info notice" style="margin-left: 0px;"><p>The <strong>Comment author must fill out name and email</strong> option has been <strong>enabled</strong> in the <a href="'.get_site_url().'/wp-admin/options-discussion.php">Comments settings</a> page since the <strong>Remove Email field</strong> is not active anymore.</p></div>';
	}
}

// remove email and url field from comments
function mo_remove_comment_fields($fields) {
	if ( get_option( 'moneiuwc_remove_email' ) ) 	{ 	if(isset($fields['email']))		{ unset($fields['email']); 	 } }
	if ( get_option( 'moneiuwc_remove_website' ) ) 	{ 	if(isset($fields['url'])) 		{ unset($fields['url']); 	 } }
	if ( get_option( 'moneiuwc_disable_cookie' ) ) 	{ 	if(isset($fields['cookies'])) 	{ unset($fields['cookies']); } }
	return $fields;
}
add_filter('comment_form_default_fields', 'mo_remove_comment_fields');

// empty comment form notes
function mo_empty_comment_form_notes($defaults){
	if ( get_option( 'moneiuwc_empty_notes' ) ) { $defaults['comment_notes_before'] = ''; }
	return $defaults;
}
add_filter( 'comment_form_defaults', 'mo_empty_comment_form_notes' );

// don't store commenter IP address
function mo_dont_store_commenter_ip( $comment_author_ip ) {
	if ( get_option( 'moneiuwc_discard_ip' ) ) { return ''; } else { return $comment_author_ip; }
}
add_filter( 'pre_comment_user_ip', 'mo_dont_store_commenter_ip' );

// disable comments cookie
add_action('init', 'mo_disable_comment_cookie');
function mo_disable_comment_cookie() {
	if ( get_option( 'moneiuwc_disable_cookie' ) ) { remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' ); }
}

// === Add plugin's admin options ===

// Create its settings menu
add_action('admin_menu', 'moneiuwc_create_menu');
function moneiuwc_create_menu() {
	//create Settings menu item
	add_options_page('No Email, IP and URL for Wordpress Comments Settings page title', 'Comments options', 'manage_options', 'moneiuwc-options', 'moneiuwc_options_management' );
	//call register settings function
	add_action( 'admin_init', 'moneiuwc_register_settings' );
}

// Register its settings
function moneiuwc_register_settings() {
	register_setting( 'moneiuwc-settings-group', 'moneiuwc_remove_email' );
	register_setting( 'moneiuwc-settings-group', 'moneiuwc_remove_website' );
	register_setting( 'moneiuwc-settings-group', 'moneiuwc_empty_notes' );
	register_setting( 'moneiuwc-settings-group', 'moneiuwc_discard_ip' );
	register_setting( 'moneiuwc-settings-group', 'moneiuwc_disable_cookie' );
	register_setting( 'moneiuwc-settings-group', 'moneiuwc_display_first_settings_notice' );
	register_setting( 'moneiuwc-settings-group', 'moneiuwc_delete_options_uninstall' );
}

// Show an sdmin notice inviting to plugin's settings
add_action( 'admin_notices', 'moneiuwc_first_settings_notice' );
function moneiuwc_first_settings_notice() {
	if ( get_option ( 'moneiuwc_display_first_settings_notice' ) != 1 )  {
		echo "<div class=\"notice-info notice\"><p><strong>No Email, IP and URL for Wordpress Comments</strong> plugin is installed and activated. Now go to <a href=\"".get_site_url()."/wp-admin/options-general.php?page=moneiuwc-options\">Comments settings</a> page and <em><strong>enable the options</strong></em> of your choice in order to remove comments features you wish. This notice will be automatically dismissed after first saving the <strong>No Email, IP and URL for Wordpress Comments</strong> plugin options.</p></div>";
	}
}

// Setting management page in WP-Admin section:
function moneiuwc_options_management() {
?>
<div class="wrap">
<h1>Settings for <strong>No Email, IP and URL for Wordpress Comments Settings</strong></h1>

<form method="post" action="options.php">
    <?php settings_fields( 'moneiuwc-settings-group' ); ?>
    <?php do_settings_sections( 'moneiuwc-settings-group' ); ?>

	<h2><strong>Please read below and choose options accordingly:</strong></h2>
	<p>Please keep in mind that comments behaviour is also affected by the settings in <a href="<?php echo get_site_url(); ?>/wp-admin/options-discussion.php">Discussion</a> admin page. <br><strong>MOST IMPORTANT thing to remember</strong> is that the option <strong>"Comment author must fill out name and email"</strong> is not compatible with <strong>"Remove Email field"</strong> option below, so setting the <strong>"Remove Email field"</strong> option will cause an automatic change of the <strong>"Comment author must fill out name and email"</strong>! <br>Details about how it's changed are shown in an admin notice above at each change (it is basically set to "disabled" when <strong>"Remove Email field"</strong> is "enabled" or the other way around)</p>
	
	<input name="moneiuwc_display_first_settings_notice" type="hidden" value="1" <?php echo esc_attr( get_option('moneiuwc_display_first_settings_notice', '1') ); ?> />
	
	<table class="form-table">
		<tr valign="top">
			<th scope="row">Remove Email field: </th>
			<td> 
				<input name="moneiuwc_remove_email" type="checkbox" value="1" <?php checked( '1', get_option( 'moneiuwc_remove_email' ) ); ?> />
				<span>(Beware that if this is <strong><?php if ( get_option ( 'moneiuwc_remove_email' ) == 1 )  {echo 'un';} ?>checked</strong> it will also <strong><?php if ( get_option ( 'moneiuwc_remove_email' ) == 0 )  {echo 'de';} ?>activate</strong> "Comment author must fill out name and email" in <a href="<?php echo get_site_url(); ?>/wp-admin/options-discussion.php">Discussion</a> admin page)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Remove Website field: </th>
			<td> <input name="moneiuwc_remove_website" type="checkbox" value="1" <?php checked( '1', get_option( 'moneiuwc_remove_website' ) ); ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Empty (remove) notes text: </th>
			<td>
				<input name="moneiuwc_empty_notes" type="checkbox" value="1" <?php checked( '1', get_option( 'moneiuwc_empty_notes' ) ); ?> />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Don't collect IP addresses: </th>
			<td>
				<input name="moneiuwc_discard_ip" type="checkbox" value="1" <?php checked( '1', get_option( 'moneiuwc_discard_ip' ) ); ?> />
				<span>(Don't collect IPs from now on, but old IP collected will stay in your comments list)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Disable cookie: </th>
			<td>
				<input name="moneiuwc_disable_cookie" type="checkbox" value="1" <?php checked( '1', get_option( 'moneiuwc_disable_cookie' ) ); ?> />
				<span>(Don't set new cookies from now on, these already set will stay in the browsers of visitors who already commented. Also removes the checkbox option to save the cookie as it's not needed.)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Delete this plugin options from database on uninstall: </th>
			<td>
				<input name="moneiuwc_delete_options_uninstall" type="checkbox" value="1" <?php checked( '1', get_option( 'moneiuwc_delete_options_uninstall' ) ); ?> />
				<span>Otherwise they'll stay there in case the plugin gets reinstalled later (useful for updating)</span>
			</td>
		</tr>
	</table>

	<?php submit_button(); ?>

</form>
</div>
<?php } ?>
