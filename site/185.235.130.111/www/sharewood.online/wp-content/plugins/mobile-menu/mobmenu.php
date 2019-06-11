<?php

/**
 * Plugin Name: Mobile Menu
 * Plugin URI: https://www.wpmobilemenu.com/
 * Description: An easy to use WordPress responsive mobile menu. Keep your mobile visitors engaged.
 * Author: Takanakui
 * Version: 2.7.3
 * Author URI: https://www.wpmobilemenu.com/
 * Tested up to: 5.1
 * Text Domain: mobile-menu
 * Domain Path: /languages/
 * GitHub Plugin URI: https://github.com/ruiguerreiro79/mobile-menu
 * License: GPLv2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}
define( 'WP_MOBILE_MENU_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

if ( ! class_exists( 'WP_Mobile_Menu' ) ) {
	/**
	 * Main Mobile Menu class
	 */
	class WP_Mobile_Menu {
		public $mm_fs;
		public $mobmenu_core;
		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->init_mobile_menu();
		}

		public function wp_mobile_menu_custom_admin_notice() {
			?>
			<div class="wp-mobile-menu-notice notice notice-success is-dismissible" data-ajax-nonce="<?php echo wp_create_nonce( 'wp-mobile-menu-security-nonce' ); ?>">
				<span class="dashicons dashicons-warning"></span>
				<?php
					_e( 'Do you need extra/advanced features? Check the <strong>Professional</strong> and <strong>Business</strong> versions. See all the advanced features, Header Banner, Ajax Sliding Cart, Alternative menus per page, Menus only visible for logged in users, Disable Mobile Menus in specific pages, 2000+ Menu Icons, Find more about the PRO Features <a href="' . esc_url( $this->mm_fs()->get_upgrade_url() ) . '"> Know more ...</a>', 'mobile-menu' );
				?>
				</div>

		<?php
		}

		/**
		 * Init WP Mobile Menu
		 *
		 * @since 1.0
		 */
		public function init_mobile_menu() {

			// Init Freemius.
			$this->mm_fs = $this->mm_fs();
			// Uninstall Action.
			$this->mm_fs->add_action( 'after_uninstall', array( $this, 'mm_fs_uninstall_cleanup' ) );
			// Include Required files.
			$this->include_required_files();
			// Instanciate the Menu Options.
			new WP_Mobile_Menu_options();
			// Instanciate the Mobile Menu Core Functions.
			$this->mobmenu_core = new WP_Mobile_Menu_Core();

			// Hooks.
			if ( is_admin() ) {

				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			}
			// Sidebar Menu Widgets.
			add_action( 'wp_loaded', array( $this->mobmenu_core, 'register_sidebar' ) );

			// Register Menus.
			add_action( 'init', array( $this->mobmenu_core, 'register_menus' ) );

			// Load frontend assets.
			if ( ! is_admin() ) {
				$this->load_frontend_assets();
			}

			// Load Translation Text Domain.
			add_action( 'plugins_loaded', array( $this, 'mm_load_textdomain' ) );

			// Load Ajax actions.
			$this->load_ajax_actions();

		}

		/**
		 * Load Text Domain
		 *
		 * @since 2.6
		 */
		public function mm_load_textdomain() {
			load_plugin_textdomain( 'mobile-menu', false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Init Freemius Settings
		 *
		 * @since 1.0
		 */
		public function mm_fs() {
			global  $mm_fs ;

			if ( ! isset( $this->mm_fs ) ) {
				// Include Freemius SDK.
				require_once dirname( __FILE__ ) . '/freemius/start.php';
				$mm_fs = fs_dynamic_init( array(
					'id'             => '235',
					'slug'           => 'mobile-menu',
					'type'           => 'plugin',
					'public_key'     => 'pk_1ec93edfb66875251b62505b96489',
					'is_premium'     => false,
					'has_addons'     => false,
					'has_paid_plans' => false,
					'trial'               => array(
						'days'               => 14,
						'is_require_payment' => true,
					),
					'menu'           => array(
						'slug' => 'mobile-menu-options',
					),
					'is_live'        => true,
				) );
			}

			return $mm_fs;
		}

		/**
		 * Include required files
		 *
		 * @since 1.0
		 */
		private function include_required_files() {
			require_once dirname( __FILE__ ) . '/vendor/titan-framework/titan-framework-embedder.php';
			require_once dirname( __FILE__ ) . '/includes/class-wp-mobile-menu-core.php';
			require_once dirname( __FILE__ ) . '/includes/class-wp-mobile-menu-options.php';
			require_once dirname( __FILE__ ) . '/includes/class-wp-mobile-menu-walker-nav-menu.php';
		}

		/**
		 * Load Frontend Assets
		 *
		 * @since 1.0
		 */
		private function load_frontend_assets() {
			// Enqueue Html to the Footer.
			add_action( 'wp_footer', array( $this->mobmenu_core, 'load_menu_html_markup' ) );
			// Frontend Scripts.
			add_action( 'wp_enqueue_scripts', array( $this->mobmenu_core, 'frontend_enqueue_scripts' ), 100 );
			// Add menu display type class to the body.
			add_action( 'init', array( $this->mobmenu_core, 'add_body_class' ) );

		}

		/**
		 * Load Ajax actions
		 *
		 * @since 1.0
		 */
		private function load_ajax_actions() {
			add_action( 'wp_ajax_get_icons_html', array( $this->mobmenu_core, 'get_icons_html' ) );
			add_action( 'wp_ajax_nopriv_get_icons_html', array( $this->mobmenu_core, 'get_icons_html' ) );
			add_action( 'wp_ajax_dismiss_wp_mobile_notice', array( $this->mobmenu_core, 'dismiss_wp_mobile_notice' ) );
			add_action( 'wp_ajax_nopriv_dismiss_wp_mobile_notice', array( $this->mobmenu_core, 'dismiss_wp_mobile_notice' ) );
			add_action( 'wp_ajax_save_menu_item_icon', array( $this->mobmenu_core, 'save_menu_item_icon' ) );
			add_action( 'wp_ajax_nopriv_save_menu_item_icon', array( $this->mobmenu_core, 'save_menu_item_icon' ) );
		}

		/** Admin Scripts. **/
		public function admin_enqueue_scripts( $hook ) {
			global  $mm_fs ;

			if ( 'toplevel_page_mobile-menu-options' === $hook && ! $mm_fs->is__premium_only() ) {
				if ( ! get_option( 'wp_mobile_menu_banner_dismissed' ) ) {
					add_action( 'admin_notices', array( $this, 'wp_mobile_menu_custom_admin_notice' ) );
				}
				wp_enqueue_style( 'cssmobmenu-admin', plugins_url( 'includes/css/mobmenu-admin.css', __FILE__ ) );
			}

			if ( 'nav-menus.php' === $hook || 'toplevel_page_mobile-menu-options' === $hook ) {
				wp_enqueue_style( 'cssmobmenu-icons', plugins_url( 'includes/css/mobmenu-icons.css', __FILE__ ) );
				wp_enqueue_style( 'cssmobmenu-admin', plugins_url( 'includes/css/mobmenu-admin.css', __FILE__ ) );
				wp_register_script( 'mobmenu-admin-js', plugins_url( 'includes/js/mobmenu-admin.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_script( 'mobmenu-admin-js' );
			}

		}

	}
}

// Instanciate the WP_Mobile_Menu.
new WP_Mobile_Menu();
