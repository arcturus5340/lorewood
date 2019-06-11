<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              catchplugins.com
 * @since             1.0.0
 * @package           Catch_Infinite_Scroll
 *
 * @wordpress-plugin
 * Plugin Name:       Catch Infinite Scroll
 * Plugin URI:        catchplugins.com/plugins/catch-infinite-scroll
 * Description:       Catch Infinite Scroll is a WordPress plugin that allows you to add the magic of infinite scrolling with several customization options on your website without affecting your wallet.
 * Version:           1.3
 * Author:            Catch Plugins
 * Author URI:        catchplugins.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       catch-infinite-scroll
 * Tags:              infinite scroll, infinite scrolling, infinite, scroll, load more
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CATCH_INFINITE_SCROLL_VERSION', '1.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-catch-infinite-scroll-activator.php
 */
if ( !defined( 'CATCH_INFINITE_SCROLL_URL' ) ) {
	define( 'CATCH_INFINITE_SCROLL_URL', plugin_dir_url( __FILE__ ) );
}


// The absolute path of the directory that contains the file
if ( !defined( 'CATCH_INFINITE_SCROLL_PATH' ) ) {
	define( 'CATCH_INFINITE_SCROLL_PATH', plugin_dir_path( __FILE__ ) );
}


// Gets the path to a plugin file or directory, relative to the plugins directory, without the leading and trailing slashes.
if ( !defined( 'CATCH_INFINITE_SCROLL_BASENAME' ) ) {
	define( 'CATCH_INFINITE_SCROLL_BASENAME', plugin_basename( __FILE__ ) );
}
function catch_infinite_scroll_activate() {
	$required = 'catch-infinite-scroll-pro/catch-infinite-scroll-pro.php';
	if ( is_plugin_active( $required ) ) {
		$message = esc_html__( 'Sorry, Pro plugin is already active. No need to activate Free version. %1$s&laquo; Return to Plugins%2$s.', 'catch-infinite-scroll' );
		$message = sprintf( $message, '<br><a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">', '</a>' );
		wp_die( $message );
	}
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-catch-infinite-scroll-activator.php';
	Catch_Infinite_Scroll_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-catch-infinite-scroll-deactivator.php
 */
function catch_infinite_scroll_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-catch-infinite-scroll-deactivator.php';
	Catch_Infinite_Scroll_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'catch_infinite_scroll_activate' );
register_deactivation_hook( __FILE__, 'catch_infinite_scroll_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-catch-infinite-scroll.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function catch_infinite_scroll_run() {

	$plugin = new Catch_Infinite_Scroll();
	$plugin->run();

}
catch_infinite_scroll_run();

/**
 * Returns the options array for Top options
 *
 *  @since    1.0
 */
function catch_infinite_scroll_get_options(){
	$defaults = catch_infinite_scroll_default_options();
	$options  = get_option( 'catch_infinite_scroll_options', $defaults );

	return wp_parse_args( $options, $defaults  ) ;
}

/**
 * Return array of default options
 *
 * @since     1.0
 * @return    array    default options.
 */
function catch_infinite_scroll_default_options( $option = null ) {
	$default_options = array(
		'trigger'             => 'click',
		'navigation_selector' => 'nav.navigation, nav#nav-below',
		'next_selector'       => 'nav.navigation .nav-links a.next, nav.navigation .nav-links .nav-previous a, nav#nav-below .nav-previous a',
		'content_selector'    => '#content',
		'item_selector'       => 'article.status-publish',
		'image'               => esc_url( trailingslashit( plugins_url( 'catch-infinite-scroll' ) ) . 'image/loader.gif' ),
		'load_more_text'      => esc_html__( 'Load More', 'catch-infinite-scroll' ),
		'finish_text'         => esc_html__( 'No more items to display', 'catch-infinite-scroll' )
	);

	$theme_support = get_theme_support( 'infinite-scroll' );

	if( isset( $theme_support ) && !empty( $theme_support ) ) {
		$default_options['trigger'] = isset( $theme_support[0]['type'] ) ? $theme_support[0]['type'] : 'click';
		$default_options['content_selector'] = isset( $theme_support[0]['container'] ) ? $theme_support[0]['container'] : '.site-main';
	}

	if ( null == $option ) {
		return apply_filters( 'catch_infinite_scroll_options', $default_options );
	}
	else {
		return $default_options[ $option ];
	}
}

// Load jetpack-compatibility
require_once plugin_dir_path( __FILE__ ) . 'includes/jetpack-compatibility.php';
/* Adds Catch Themes tab in Add theme page and Themes by Catch Themes in Customizer's change theme option. */
require plugin_dir_path( __FILE__ ) . 'includes/our-themes.php';

/* Adds Catch Plugins tab in Add theme page.  */
require plugin_dir_path( __FILE__ ) . 'includes/our-plugins.php';

