<?php

if ( ! class_exists( 'WP_Mobile_Menu' ) ) {
	die;
}
class WP_Mobile_Menu_Core {
	public function __construct() {
	}

	/**
	 * Init WP Mobile Menu.
	 *
	 * @since 1.0
	 */
	public function add_body_class() {

			add_action( 'body_class', function ( $classes ) {
				$titan = TitanFramework::getInstance( 'mobmenu' );
				$display_type = $titan->getOption( 'menu_display_type' );

				if ( 'slideout-over' === $display_type || '' === $display_type ) {
					$menu_display_type = 'mob-menu-slideout-over';
				} else {
					$menu_display_type = 'mob-menu-slideout';
				}

				$classes[] = $menu_display_type;
				return $classes;
			} );

	}

	/**
	 * Frontend Scripts.
	 */
	public function frontend_enqueue_scripts() {

		wp_register_script( 'mobmenujs', plugins_url( 'js/mobmenu.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'mobmenujs' );
		wp_enqueue_style( 'cssmobmenu-icons', plugins_url( 'css/mobmenu-icons.css', __FILE__ ) );

		// Filters.
		add_filter( 'wp_footer', array( $this, 'load_dynamic_css_style' ) );

	}

	/**
	 * Load dynamic CSS.
	 */
	public function load_dynamic_css_style() {
		$titan         = TitanFramework::getInstance( 'mobmenu' );
		echo '<style id="dynamic-mobmenu-inline-css">';
		include_once 'dynamic-style.php';
		echo $titan->getOption('custom_css');
		echo '</style>';

	}

	/**
	 * Dismiss the WP Mobile Menu Pro Banner
	 */
	public function dismiss_wp_mobile_notice() {
		if ( check_ajax_referer( 'wp-mobile-menu-security-nonce', 'security' ) ) {
			update_option( 'wp_mobile_menu_banner_dismissed', 'yes' );
		}
		wp_die();
	}

	/**
	 * Build the icons HTML.
	 */
	public function get_icons_html() {
		$menu_title = '';

		if ( isset( $_POST['menu_item_id'] ) ) {
			$menu_item_id = absint( $_POST['menu_item_id'] );
		}
		if ( isset( $_POST['menu_id'] ) ) {
			$menu_id = absint( $_POST['menu_id'] );
		}
		if ( isset( $_POST['menu_title'] ) ) {
			$menu_title = $_POST['menu_title'];
		}
		if ( isset( $_POST['full_content'] ) ) {
			$full_content = $_POST['full_content'];
		}
		$seleted_icon = get_post_meta( $menu_item_id, '_mobmenu_icon', true );

		if ( ! empty( $seleted_icon ) ) {
			$selected = ' data-selected-icon="' . $seleted_icon . '" ';
		} else {
			$selected = '';
		}

		$icons = $this->get_icons_list();

		if ( 'yes' === $full_content ) {
			$output = '<div class="mobmenu-icons-overlay"></div><div class="mobmenu-icons-content" data-menu-id="' . $menu_id . '" data-menu-item-id="' . $menu_item_id . '">';
			$output .= '<div id="mobmenu-modal-header"><h2>' . $menu_title . ' - Menu Item Icon</h2><div class="mobmenu-icons-close-overlay"><span class="mobmenu-item mobmenu-close-overlay mob-icon-cancel-circle"></span></div>';
			$output .= '<div class="mobmenu-icons-search"><input type="text" name="mobmenu_search_icons" id="mobmenu_search_icons" value="" placeholder="Search"><span class="mobmenu-item mob-icon-search-circle"></span></div>';
			$output .= '<div class="mobmenu-icons-remove-selected">' . __( 'Remove Icon Selection', 'mobile-menu' ) . '</div>';
			$output .= '</div><div id="mobmenu-modal-body"><div class="mobmenu-icons-holder" ' . $selected . '>';

			// Loop through all the icons to create the icons list.
			foreach ( $icons as $icon ) {
				$output .= '<span class="mobmenu-item mob-icon-' . $icon . '" data-icon-key="' . $icon . '"></span>';
			}
			$output .= '</div></div>';
		} else {
			$output = '<div class="mobmenu-icons-holder" ' . $selected . ' data-title="' . esc_attr( $menu_title ) . '" - Menu Item Icon" >';
		}

		echo $output;
		wp_die();
	}

	/**
	 * Register the Mobile Menus.
	 */
	public function register_menus() {
		register_nav_menus(
			array(
				'left-wp-mobile-menu'  => __( 'Left Mobile Menu' , 'mob-menu-lang' ),
				'right-wp-mobile-menu' => __( 'Right Mobile Menu' , 'mob-menu-lang' ),
			)
		);
	}

	/**
	 * Build the WP Mobile Menu Html Markup.
	 */
	public function load_menu_html_markup() {
		global  $mm_fs ;
		$titan                         = TitanFramework::getInstance( 'mobmenu' );
		$menu_display_type             = 'mob-menu-slideout';
		$left_logged_in_user           = false;
		$right_logged_in_user          = false;
		$mobmenu_parent_link           = '';
		$mobmenu_parent_link_2nd_level = '';
		$left_menu_content             = '';
		$right_menu_content            = '';
		$output                        = '';
		$logo_content                  = '';
		$output                       .= '<div class="mobmenu-overlay"></div>';
		$close_icon                    = $titan->getOption( 'close_icon_font' );
		$submenu_open_icon_font        = $titan->getOption( 'submenu_open_icon_font' );
		$submenu_close_icon_font       = $titan->getOption( 'submenu_close_icon_font' );
		$header_text                   = $titan->getOption( 'header_text' );

		if ( '' === $header_text ) {
			$header_text = get_bloginfo();
		}

		$sticky_el_data_detach = '';
		if ( $titan->getOption( 'sticky_elements' ) ) {
			$sticky_el_data_detach = 'data-detach-el="' . $titan->getOption( 'sticky_elements' ) . '"';
		}

		$output .= '<div class="mob-menu-header-holder mobmenu" ' . $sticky_el_data_detach . ' data-open-icon="' . $submenu_open_icon_font . '" data-close-icon="' . $submenu_close_icon_font . '">';

		$display_type = $titan->getOption( 'menu_display_type' );

		if ( 'slideout-over' === $display_type || '' === $display_type ) {
			$menu_display_type = ' data-menu-display="mob-menu-slideout-over" ';
		} else {
			$menu_display_type = ' data-menu-display="mob-menu-slideout" ';
		}

		if ( $titan->getOption( 'enable_left_menu' ) && ! $left_logged_in_user ) {
			$left_menu_text = '';
			if ( '' !== $titan->getOption( 'left_menu_text' ) ) {
				$left_menu_text .= '<span class="left-menu-icon-text">' . __( $titan->getOption( 'left_menu_text' ), 'mobile-menu' ) . '</span>';
			}

			if ( $titan->getOption( 'left_menu_icon_action' ) ) {
				$left_menu_content .= '<a href="#" class="mobmenu-left-bt">';
			} else {

				if ( $titan->getOption( 'left_icon_url_target' ) ) {
					$left_icon_url_target = '_self';
				} else {
					$left_icon_url_target = '_blank';
				}

				$left_menu_content .= '<a href="' . $titan->getOption( 'left_icon_url' ) . '" target="' . $left_icon_url_target . '" id="mobmenu-center">';
			}

			$left_icon_image = wp_get_attachment_image_src( $titan->getOption( 'left_menu_icon' ) );
			$left_icon_image = $left_icon_image[0];

			if ( ! $titan->getOption( 'left_menu_icon_opt' ) || '' === $left_icon_image ) {
				$left_menu_content .= '<i class="mob-icon-' . $titan->getOption( 'left_menu_icon_font' ) . ' mob-menu-icon"></i><i class="mob-icon-' . $close_icon . ' mob-cancel-button"></i>';
			} else {
				$left_menu_content .= '<img src="' . $left_icon_image . '" alt="' . __( 'Left Menu Icon', 'mobile-menu' ) . '"><i class="mob-icon-' . $close_icon . ' mob-cancel-button"></i>';
			}

			$left_menu_content .= $left_menu_text;
			$left_menu_content .= '</a>';
			$left_menu_content = apply_filters( 'mm_left_menu_filter', $left_menu_content );
		}

		// If the logo branding isn't disabled.
		if ( ! $titan->getOption( 'disabled_logo_text' ) ) {
			// Format the Header Branding.
			$logo_content = $this->format_header_branding( $titan, $header_text );

		}

		// Right Menu Content.
		if ( $titan->getOption( 'enable_right_menu' ) && ! $right_logged_in_user ) {
			$right_menu_text    = '';
			$right_menu_content = '';

			if ( '' !== $titan->getOption( 'right_menu_text' ) ) {
				$right_menu_text .= '<span class="right-menu-icon-text">' . __( $titan->getOption( 'right_menu_text' ), 'mobile-menu' ) . '</span>';
			}

			if ( $titan->getOption( 'right_menu_icon_action' ) ) {
				$right_menu_content .= '<a href="#" class="mobmenu-right-bt">';
			} else {

				if ( $titan->getOption( 'right_icon_url_target' ) ) {
					$right_icon_url_target = '_self';
				} else {
					$right_icon_url_target = '_blank';
				}

				$right_menu_content .= '<a href="' . $titan->getOption( 'right_icon_url' ) . '" target="' . $right_icon_url_target . '">';
			}

			$right_icon_image = wp_get_attachment_image_src( $titan->getOption( 'right_menu_icon' ) );
			$right_icon_image = $right_icon_image[0];

			if ( ! $titan->getOption( 'right_menu_icon_opt' ) || '' === $right_icon_image ) {
				$right_menu_content .= '<i class="mob-icon-' . $titan->getOption( 'right_menu_icon_font' ) . ' mob-menu-icon"></i><i class="mob-icon-' . $close_icon . ' mob-cancel-button"></i>';
			} else {
				$right_menu_content .= '<img src="' . $right_icon_image . '" alt="' . __( 'Right Menu Icon', 'mobile-menu' ) . '"><i class="mob-icon-' . $close_icon . ' mob-cancel-button"></i>';
			}

			$right_menu_content .= $right_menu_text;
			$right_menu_content .= '</a>';
			$right_menu_content = apply_filters( 'mm_right_menu_filter', $right_menu_content );
		}

		// Build the Header Content.
		$header_output         = '<div  class="mobmenul-container">' . $left_menu_content . '</div>';
		$header_output        .= $logo_content;
		$header_output        .= '<div  class="mobmenur-container">' . $right_menu_content . '</div>';
		$output               .= $header_output;
		$output               .= '</div>';

		echo $output;

		if ( $titan->getOption( 'enable_left_menu' ) && ! $left_logged_in_user ) {
			if ( $titan->getOption( 'left_menu_parent_link_submenu' ) ) {
				$mobmenu_parent_link = 'mobmenu-parent-link';
			}
			if ( $titan->getOption( 'left_menu_parent_link_submenu_2nd_level' ) ) {
				$mobmenu_parent_link_2nd_level = 'mobmenu-parent-link-2nd-level';
			}
			?>

			<div class="mob-menu-left-panel mobmenu <?php echo $mobmenu_parent_link; ?> <?php echo $mobmenu_parent_link_2nd_level; ?>">
				<a href="#" class="mobmenu-left-bt"><i class="mob-icon-<?php echo $close_icon; ?> mob-cancel-button"></i></a>
				<div class="mobmenu_content">
			<?php

			if ( is_active_sidebar( 'mobmlefttop' ) ) {
				?>
				<ul class="leftmtop">
					<?php dynamic_sidebar( 'Left Menu Top' ); ?>
				</ul>
			<?php
			}

			// Grab the current left menu.
			$current_left_menu = $titan->getOption( 'left_menu' );
			if ( '0' === $current_left_menu ){
				$current_left_menu = '';
			}

			// Only build the menu it there is a menu assigned to it.
			if ( '' !== $current_left_menu ) {
				if ( has_nav_menu( 'left-wp-mobile-menu' ) ) {
					$menu_param = 'theme_location';
					$current_left_menu = 'left-wp-mobile-menu';
				} else {
					$menu_param = 'menu';
				}
				// Display the left menu.
				wp_nav_menu( array(
					$menu_param   => $current_left_menu,
					'items_wrap'  => '<ul id="mobmenuleft">%3$s</ul>',
					'fallback_cb' => false,
					'depth'       => 3,
					'walker'      => new WP_Mobile_Menu_Walker_Nav_Menu( 'left' ),
				) );
			} else {
				if ( is_admin() ) {
					echo "<span class='no-menu-assigned'>Assign a menu in the Left Menu options.</span>";
				}
			}

			// Check if the Left Menu Bottom Widget has any content.
			if ( is_active_sidebar( 'mobmleftbottom' ) ) {
				?>
					<ul class="leftmbottom">
						<?php dynamic_sidebar( 'Left Menu Bottom' ); ?>
					</ul>
			<?php
			}
		

		?>

			</div><div class="mob-menu-left-bg-holder"></div></div>

			<?php
			}
			if ( $titan->getOption( 'enable_right_menu' ) && ! $right_logged_in_user ) {
				$mobmenu_parent_link = '';
				if ( $titan->getOption( 'right_menu_parent_link_submenu' ) ) {
					$mobmenu_parent_link = 'mobmenu-parent-link';
				}

				if ( $titan->getOption( 'right_menu_parent_link_submenu_2nd_level' ) ) {
					$mobmenu_parent_link_2nd_level = 'mobmenu-parent-link-2nd-level';
				}
				?>
				<!--  Right Panel Structure -->
				<div class="mob-menu-right-panel mobmenu <?php echo $mobmenu_parent_link; ?> <?php echo $mobmenu_parent_link_2nd_level; ?>">
					<a href="#" class="mobmenu-right-bt"><i class="mob-icon-cancel mob-cancel-button"></i></a>
					<div class="mobmenu_content">
					
			<?php
			// Check if the Right Menu Top Widget has any content.
			if ( is_active_sidebar( 'mobmrighttop' ) ) {
			?>
				<ul class="rightmtop">
					<?php dynamic_sidebar( 'Right Menu Top' ); ?>
				</ul>
			<?php
			}
			?>

		<?php
		// Grab the select menu.
		$current_right_menu = $titan->getOption( 'right_menu' );

		// Only build the menu it there is a menu assigned to it.
		if ( '' !== $current_right_menu ) {
			if ( has_nav_menu( 'right-wp-mobile-menu' ) ) {
				$menu_param = 'theme_location';
				$current_right_menu = 'right-wp-mobile-menu';
			} else {
				$menu_param = 'menu';
			}
			// Display the right menu.
			wp_nav_menu( array(
				$menu_param   => $current_right_menu,
				'items_wrap'  => '<ul id="mobmenuright">%3$s</ul>',
				'fallback_cb' => false,
				'depth'       => 3,
				'walker'      => new WP_Mobile_Menu_Walker_Nav_Menu( 'right' ),
			) );
		} else {
			if ( is_admin() ) {
				echo "<span class='no-menu-assigned'>Assign a menu in the Right Menu options.</span>";
			}
		}

		// Check if the Right Menu Bottom Widget has any content.
		if ( is_active_sidebar( 'mobmrightbottom' ) ) {
			?>
		<ul class="rightmbottom">
			<?php dynamic_sidebar( 'Right Menu Bottom' ); ?>
		</ul>
		<?php
		}
		?>

			</div><div class="mob-menu-right-bg-holder"></div></div>

		<?php
		}
	}

	/**
	 *
	 * Format Header Branding(Logo + Text).
	 *
	 * @since 2.6
	 * @var $titan
	 * @var $header_text
	 */
	public function format_header_branding( $titan, $header_text ) {

		global $mm_fs;
		$logo_img     = wp_get_attachment_image_src( $titan->getOption( 'logo_img' ), 'full' );
		$logo_img     = $logo_img[0];
		$logo_output  = '';
		$logo_url     = '';
		$logo_url_end = '';

		if ( $titan->getOption( 'logo_img_retina' ) ) {
			$logo_img_retina          = wp_get_attachment_image_src( $titan->getOption( 'logo_img_retina' ), 'full' );
			$logo_img_retina          = $logo_img_retina[0];
			$logo_img_retina_metadata = wp_get_attachment_metadata( $titan->getOption( 'logo_img_retina' ) );
			$logo_img_retina_width    = intval( $logo_img_retina_metadata['width'], 10 ) / 2;
		}

		$header_branding = $titan->getOption( 'header_branding' );

		// If there is a retina logo use only that logo.
		if ( '' !== $titan->getOption( 'logo_img_retina' ) ) {
			$logo_img = $logo_img_retina;
		}

		if ( ( 'logo' === $header_branding || 'logo-text' === $header_branding || 'text-logo' === $header_branding ) && null !== $logo_img ) {
			$logo_output .= '<img class="mob-standard-logo" src="' . $logo_img . '"  alt=" ' . __( 'Logo Header Menu', 'mob-menu-lang' ) . '">';
		}

		if ( $titan->getOption( 'disabled_logo_url' ) ) {
			if ( '' === $logo_output ) {
				$logo_url = '<h3 class="headertext">';
				$logo_url_end = '</h3>';
			}
		} else {

			if ( '' === $titan->getOption( 'logo_url' ) ) {
				if ( function_exists( 'pll_home_url' ) ) {
					$logo_url = pll_home_url();
				} else {
					$logo_url = get_bloginfo( 'url' );
				}
			} else {
				$logo_url = $titan->getOption( 'logo_url' );
			}

			$logo_url_end = '</a>';
			$logo_url = '<a href="' . $logo_url . '" class="headertext">';
		}

		if ( $header_branding ) {

			$output = '<div class="mob-menu-logo-holder">' . $logo_url;
			$header_text = '<span>' . $header_text . '</span>';

			switch ( $header_branding ) {

				case 'logo':
					$output .= $logo_output;
					break;
				case 'text':
					$output .= $header_text;
					break;
				case 'logo-text':
					$output .= $logo_output;
					$output .= $header_text;
					break;
				case 'text-logo':
					$output .= $header_text;
					$output .= $logo_output;
					break;

			}
		}

		$output .= $logo_url_end . '</div>';

		return $output;

	}

	/**
	 *
	 * Save Menu Item Icon.
	 *
	 * @since 2.0
	 */
	public function save_menu_item_icon() {

		if ( isset( $_POST['menu_item_id'] ) ) {
			$menu_item_id = absint( esc_attr( $_POST['menu_item_id'] ) );
			$menu_item_icon = esc_attr( $_POST['menu_item_icon'] );
			if ( $menu_item_id > 0 ) {
				update_post_meta( $menu_item_id, '_mobmenu_icon', $menu_item_icon );
			}
			wp_send_json_success();
		}
	}

	/**
	 * Register Sidebar Menu Widgets.
	 */
	public function register_sidebar() {

		$args = array(
			'name'          => 'Left Menu Top',
			'id'            => 'mobmlefttop',
			'description'   => '',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		);
		register_sidebar( $args );

		$args = array(
			'name'          => 'Left Menu Bottom',
			'id'            => 'mobmleftbottom',
			'description'   => '',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		);
		register_sidebar( $args );

		$args = array(
			'name'          => 'Right Menu Top',
			'id'            => 'mobmrighttop',
			'description'   => '',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		);
		register_sidebar( $args );

		$args = array(
			'name'          => 'Right Menu Bottom',
			'id'            => 'mobmrightbottom',
			'description'   => '',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		);
		register_sidebar( $args );
	}

	/**
	 * Get the Icon Font list.
	 */
	public function get_icons_list() {
		global  $mm_fs ;
		$icons_base = array(
			'menu',
			'menu-2',
			'menu-3',
			'menu-1',
			'menu-outline',
			'user',
			'user-1',
			'star',
			'star-1',
			'star-empty',
			'ok',
			'ok-1',
			'cancel',
			'cancel-2',
			'cancel-circled',
			'cancel-circled2',
			'cancel-circle',
			'cancel-1',
			'vimeo',
			'twitter',
			'facebook-squared',
			'gplus',
			'pinterest',
			'tumblr',
			'linkedin',
			'instagram',
			'plus',
			'plus-outline',
			'plus-1',
			'minus',
			'minus-1',
			'icon-plus-2',
			'minus-2',
			'down-open',
			'icon-up-open-big',
			'down-dir',
			'left-dir',
			'right-dir',
			'up-dir',
			'left-open',
			'right-open',
			'up-open-2',
			'down-open-2',

		);
		return $icons_base;
	}
}
