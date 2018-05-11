<?php
/**
 * Plugin Name: No Email, IP and URL for Wordpress Comments
 * Plugin URI: https://github.com/alexmoise/No-Email-IP-and-URL-for-Wordpress-Comments
 * GitHub Plugin URI: https://github.com/alexmoise/No-Email-IP-and-URL-for-Wordpress-Comments
 * Description: A very simple plugin to remove Email and Website fields from comments area and also stop collecting the commenter's IP address. No settings page needed at this moment. For details/troubleshooting please contact me at https://moise.pro/contact/
 * Version: 0.1.0
 * Author: Alex Moise
 * Author URI: https://moise.pro
 */

if ( ! defined( 'ABSPATH' ) ) {	exit(0);}

// remove email and url field from comments
function mo_remove_comment_fields($fields) {
    if(isset($fields['email'])) unset($fields['email']);
	if(isset($fields['url'])) unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields', 'mo_remove_comment_fields');

// don't store commenter IP address
function mo_dont_store_commenter_ip( $comment_author_ip ) {
	return '';
}
add_filter( 'pre_comment_user_ip', 'mo_dont_store_commenter_ip' );

?>
