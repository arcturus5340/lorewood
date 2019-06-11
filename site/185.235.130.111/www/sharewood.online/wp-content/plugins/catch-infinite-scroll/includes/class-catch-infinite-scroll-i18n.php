<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Infinite_Scroll
 * @subpackage Catch_Infinite_Scroll/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Catch_Infinite_Scroll
 * @subpackage Catch_Infinite_Scroll/includes
 * @author     catchplugins.com <info@catchplugins.com>
 */
class Catch_Infinite_Scroll_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'catch-infinite-scroll',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
