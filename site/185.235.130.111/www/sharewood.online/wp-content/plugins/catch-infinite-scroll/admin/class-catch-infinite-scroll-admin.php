<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Infinite_Scroll
 * @subpackage Catch_Infinite_Scroll/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Catch_Infinite_Scroll
 * @subpackage Catch_Infinite_Scroll/admin
 * @author     catchplugins.com <info@catchplugins.com>
 */
class Catch_Infinite_Scroll_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $CATCH_INFINITE_SCROLL    The ID of this plugin.
	 */
	private $CATCH_INFINITE_SCROLL;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $CATCH_INFINITE_SCROLL       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $CATCH_INFINITE_SCROLL, $version ) {

		$this->catch_infinite_scroll = $CATCH_INFINITE_SCROLL;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Catch_Infinite_Scroll_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Catch_Infinite_Scroll_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if( isset( $_GET['page'] ) && 'catch-infinite-scroll' == $_GET['page'] ) {
			wp_enqueue_style( $this->catch_infinite_scroll, plugin_dir_url( __FILE__ ) . 'css/catch-infinite-scroll-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->catch_infinite_scroll . '-tabs', plugin_dir_url( __FILE__ ) . 'css/admin-dashboard.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Catch_Infinite_Scroll_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Catch_Infinite_Scroll_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if( isset( $_GET['page'] ) && 'catch-infinite-scroll' == $_GET['page'] ) {
			$defaults = catch_infinite_scroll_default_options();

			wp_enqueue_script( $this->catch_infinite_scroll . '-match-height', plugin_dir_url( __FILE__ ) . 'js/jquery.matchHeight.min.js', array( 'jquery' ), $this->version, false );

			wp_register_script( $this->catch_infinite_scroll, plugin_dir_url( __FILE__ ) . 'js/catch-infinite-scroll-admin.js', array( 'jquery' ), $this->version, false );

			wp_localize_script( $this->catch_infinite_scroll, 'default_options', $defaults );

			wp_enqueue_script( $this->catch_infinite_scroll );

			wp_enqueue_media();
		}

	}

	/**
	 * Catch Infinite Scroll: action_links
	 * Catch Infinite Scroll Settings Link function callback
	 *
	 * @param arrray $links Link url.
	 *
	 * @param arrray $file File name.
	 */
	public function action_links( $links, $file ) {
		if ( $file === $this->catch_infinite_scroll . '/' . $this->catch_infinite_scroll . '.php' ) {
			$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=catch-infinite-scroll' ) ) . '">' . esc_html__( 'Settings', 'catch-infinite-scroll' ) . '</a>';

			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	/**
	 * Catch Infinite Scroll: add_plugin_settings_menu
	 * add Catch Infinite Scroll to menu
	 */
	public function add_plugin_settings_menu() {
		add_menu_page(
			esc_html__( 'Catch Infinite Scroll', 'catch-infinite-scroll' ),
			esc_html__( 'Catch Infinite Scroll', 'catch-infinite-scroll' ),
			'manage_options',
			'catch-infinite-scroll',
			array( $this, 'settings_page' ),
			'dashicons-update',
			'99.01564'
		);
	}

	/**
	 * Catch Infinite Scroll: catch_web_tools_settings_page
	 * Catch Infinite Scroll Setting function
	 */
	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		require plugin_dir_path( __FILE__ ) . 'partials/catch-infinite-scroll-admin-display.php';
	}

	/**
	 * Catch Infinite Scroll: register_settings
	 * Catch Infinite Scroll Register Settings
	 */
	public function register_settings() {
		register_setting(
			'catch-infinite-scroll-group',
			'catch_infinite_scroll_options',
			array( $this, 'sanitize_callback' )
		);
	}

	/**
	 * Catch Infinite Scroll: sanitize_callback
	 * Catch Infinite Scroll Sanitization function callback
	 *
	 * @param array $input Input data for sanitization.
	 */
	public function sanitize_callback( $input ) {
		if ( isset( $input['reset'] ) && $input['reset'] ) {
			//If reset, restore defaults
			return catch_infinite_scroll_default_options();
		}
		$message = null;
		$type = null;

		// Verify the nonce before proceeding.
	    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	    	|| ( ! isset( $_POST['catch_infinite_scroll_nonce'] )
	    	|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['catch_infinite_scroll_nonce'] ) ), basename( __FILE__ ) ) )
	    	|| ( ! check_admin_referer( basename( __FILE__ ), 'catch_infinite_scroll_nonce' ) ) ) {
	    	if ( null !== $input ) {

				if ( isset( $input['trigger'] ) && $input['trigger'] ) {
					$input['trigger']            = sanitize_key( $input['trigger'] );
				}

				if ( isset( $input['next_selector'] ) && $input['next_selector'] ) {
					$input['next_selector']      = wp_kses_post( $input['next_selector'] );
				}

				if ( isset( $input['content_selector'] ) ) {
					$input['content_selector']   = wp_kses_post( $input['content_selector'] );
				}

				if ( isset( $input['item_selector'] ) ) {
					$input['item_selector']      = wp_kses_post( $input['item_selector'] );
				}

				if ( isset( $input['navigation_selector'] ) ) {
					$input['navigation_selector']      = wp_kses_post( $input['navigation_selector'] );
				}

				if ( isset( $input['image'] ) ) {
					$input['image']      = esc_url_raw( $input['image'] );
				}

				if ( isset( $input['load_more_text'] ) ) {
					$input['load_more_text']      = wp_kses_post( $input['load_more_text'] );
				}

				if ( isset( $input['finish_text'] ) ) {
					$input['finish_text']      = wp_kses_post( $input['finish_text'] );
				}

			}

			return $input;
	    } // End if().
	    return 'Invalid Nonce';

	}

}
