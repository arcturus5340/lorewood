<?php
/*
Plugin Name: WP Editor
Plugin URI: http://wpeditor.net
Description: This plugin modifies the default behavior of the WordPress plugin and theme editors.
Version: 1.2.6.3
Author: Benjamin Rojas
Author URI: http://benjaminrojas.net
Text Domain: wpeditor
Domain Path: /languages/
------------------------------------------------------------------------
WP Editor Plugin
Copyright 2012  Benjamin Rojas

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! class_exists( 'WPEditor' ) ) {
	
	ob_start();
	
	$wp_34 = false;
	if ( version_compare( get_bloginfo( 'version' ), '3.4', '>=' ) ) {
		$wp_34 = true;
	}
	define( 'WP_34', $wp_34 );

	$wp_43 = false;
	if ( version_compare( get_bloginfo( 'version' ), '4.3', '>=' ) ) {
		$wp_43 = true;
	}
	define( 'WP_43', $wp_43 );
	
	// Define the default path and URL for the WP Editor plugin
	$plugin_file = __FILE__;
	if ( isset( $plugin ) ) {
		$plugin_file = $plugin;
	}
	elseif ( isset( $mu_plugin ) ) {
		$plugin_file = $mu_plugin;
	}
	elseif ( isset( $network_plugin ) ) {
		$plugin_file = $network_plugin;
	}
	define( 'WPEDITOR_PATH', WP_PLUGIN_DIR . '/' . basename( dirname( $plugin_file ) ) . '/' );
	define( 'WPEDITOR_URL', plugin_dir_url( WPEDITOR_PATH ) . basename( dirname( $plugin_file ) ) . '/' );
	
	// Define the WP Editor version number
	define( 'WPEDITOR_VERSION_NUMBER', wpe_version_number() );
	
	// IS_ADMIN is true when the dashboard or the administration panels are displayed
	if ( ! defined( 'IS_ADMIN' ) ) {
		define( 'IS_ADMIN',  is_admin() );
	}
	
	$windows = false;
	if ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' ) {
		$windows = true;
	}
	
	define( 'WPWINDOWS', $windows );
	
	load_plugin_textdomain( 'wp-editor', false, '/' . basename(dirname(__FILE__)) . '/languages/' );
	
	// Load the main WP Editor class
	require_once( WPEDITOR_PATH . 'classes/WPEditor.php' );
	$wpedit = new WPEditor();

	// Register activation hook to install WP Editor database tables and system code
	register_activation_hook( __FILE__, array( $wpedit, 'install' ) );

	// Check for WordPress 3.1 auto-upgrades
	if ( function_exists( 'register_update_hook' ) ) {
		register_update_hook( __FILE__, array( $wpedit, 'install' ) );
	}

	// Initialize the main WP Editor Class
	add_action( 'init',  array( $wpedit, 'init' ) );
	
	// Add settings link to plugin page
	add_filter( 'plugin_action_links', 'wpe_settings_link', 10, 2 );
}

function wpe_settings_link( $links, $file ) {
	$thisFile = plugin_basename( WPEDITOR_PATH ) . '/' . basename( __FILE__ );
	if ( $file == $thisFile ) {
		$settings = '<a href="' . admin_url( 'admin.php?page=wpeditor_admin' ) . '" title="' . __( 'Open the settings page for this plugin', 'wp-editor' ) . '">' . __( 'Settings', 'wp-editor' ) . '</a>';
		array_unshift( $links, $settings );
	}
	return $links;
}
function wpe_version_number() {
	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	$plugin_data = get_plugin_data( WPEDITOR_PATH . '/wpeditor.php' );
	return $plugin_data['Version'];
}