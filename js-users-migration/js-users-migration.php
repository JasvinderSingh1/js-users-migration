<?php
/*
Plugin Name: JS users migration
Description: A plugin to migrate all users from Drupal to WordPress.
Author: EWS
Author URI: http://enablerswebsolutions.com/
Version: 1.0
*/

/**
 * Override WP's password hashing algorithm.
 *
 * When activating the plugin, WP's definition of these pluggable functions is
 * already declared.  Only include this plugin's definition once they do not exist.
 *
 * @see wp-includes/pluggable.php
 */
if (!function_exists('wp_hash_password') && !function_exists('wp_check_password')) {
    require_once('drupal/adaptor.php');
}

//to add custom page in admin section
add_action('admin_menu', 'js_users_migration_plugin');
function js_users_migration_plugin(){
	$plugins_url	=	plugin_dir_url( __FILE__ ) . 'images/dwp.png' ;
	add_menu_page( 'Migrate Drupal Users', 'Migrate Drupal Users', 'manage_options', 'js-users-migration', 'js_users_migration_init', $plugins_url );
}

function js_users_migration_init(){
	require plugin_dir_path( __FILE__ ) . 'js_users_migration.php';
}
?>