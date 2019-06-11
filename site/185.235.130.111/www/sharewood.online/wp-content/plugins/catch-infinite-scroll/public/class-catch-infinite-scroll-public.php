<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Infinite_Scroll
 * @subpackage Catch_Infinite_Scroll/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Catch_Infinite_Scroll
 * @subpackage Catch_Infinite_Scroll/public
 * @author     catchplugins.com <info@catchplugins.com>
 */
class Catch_Infinite_Scroll_Public {

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
	 * @param      string    $CATCH_INFINITE_SCROLL       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $CATCH_INFINITE_SCROLL, $version ) {

		$this->catch_infinite_scroll = $CATCH_INFINITE_SCROLL;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->catch_infinite_scroll, plugin_dir_url( __FILE__ ) . 'css/catch-infinite-scroll-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		$settings = catch_infinite_scroll_get_options( 'catch_infinite_scroll_options' );

		$options                   = array();
		$options['jetpack_enabled']					= ( class_exists( 'Jetpack' ) ) ? true : false;
		$options['image']          = $settings['image'];
		$options['load_more_text'] = $settings['load_more_text'];
		$options['finish_text']    = $settings['finish_text'];

		if( ! is_singular() || is_search()  ){
			$options['event']              = $settings['trigger'];
			$options['navigationSelector'] = $settings['navigation_selector'];
			$options['nextSelector']       = $settings['next_selector'];
			$options['contentSelector']    = $settings['content_selector'];
			$options['itemSelector']       = $settings['item_selector'];
			$options['type']               = 'post';

	        if( class_exists( 'WooCommerce' ) ) {
		        if( is_shop() ){
		            //WooCommerce
					$options['navigationSelector'] = 'nav.woocommerce-pagination';
					$options['nextSelector']       = 'nav.woocommerce-pagination .page-numbers a.next';
					$options['contentSelector']    = 'ul.products';
					$options['itemSelector']       = 'li.product.type-product';
					$options['type']               = 'shop';
		        }
		    }

			wp_register_script( $this->catch_infinite_scroll, plugin_dir_url( __FILE__ ) . 'js/catch-infinite-scroll-public.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->catch_infinite_scroll, 'selector', $options );
	        wp_enqueue_script( $this->catch_infinite_scroll );
        }

	}
}
