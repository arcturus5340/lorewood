<?php

if ( ! class_exists( 'WP_Mobile_Menu' ) ) {
	die;
}
class WP_Mobile_Menu_options {
	public function __construct() {
		$this->init_options();
	}

	private function init_options() {
		add_action( 'tf_create_options', array( $this, 'create_plugin_options' ) );
	}

	public function create_plugin_options() {
		global  $mm_fs ;
		$prefix = '';
		$menus = get_terms( 'nav_menu', array(
			'hide_empty' => true,
		) );

		$menus_options = array();
		$menus_options[0] = __( 'Choose one menu', 'mobile-menu' );

		foreach ( $menus as $menu ) {
			$menus_options[ $menu->name ] = $menu->name;
		}

		$display_type = array(
			'slideout-over' => __( 'Slideout Over Content', 'mobile-menu' ),
			'slideout-push' => __( 'Slideout Push Content', 'mobile-menu' ),
		);

		// Initialize Titan with my special unique namespace.
		$titan = TitanFramework::getInstance( 'mobmenu' );
		// Create my admin options panel.
		$panel = $titan->createAdminPanel( array(
			'name' => __( 'Mobile Menu Options', 'mobile-menu' ),
			'id'   => 'mobile-menu-options',
			'icon' => 'dashicons-smartphone',
		) );

		// Only proceed if we are in the plugin page.
		if ( ! is_admin() || isset( $_GET['page'] ) && 'mobile-menu-options' === $_GET['page'] ) {
			// Create General Options panel.
			$general_tab = $panel->createTab( array(
				'name' => __( 'General Options', 'mobile-menu' ),
			) );

			// Create Header Options panel.
			$header_tab = $panel->createTab( array(
				'name' => __( 'Header', 'mobile-menu' ),
			) );

			// Create Left Menu Options panel.
			$left_menu_tab = $panel->createTab( array(
				'name' => __( 'Left Menu', 'mobile-menu' ),
			) );

			// Create Right Menu Options panel.
			$right_menu_tab = $panel->createTab( array(
				'name' => __( 'Right Menu', 'mobile-menu' ),
			) );

			// Create Color Options panel.
			$colors_tab = $panel->createTab( array(
				'name' => __( 'Colors', 'mobile-menu' ),
			) );

			// Create Documentation panel.
			$documentation_tab = $panel->createTab( array(
				'name' => __( 'Documentation', 'mobile-menu' ),
			) );

			// Documentation IFrame.
			$documentation_tab->createOption( array(
				'type' => 'iframe',
				'url'  => 'http://wpmobilemenu.com/documentation-iframe/',
			) );

			// Width trigger.
			$general_tab->createOption( array(
				'name'    => __( 'Mobile Menu Display Rules( Width Trigger )', 'mobile-menu' ),
				'id'      => 'width_trigger',
				'type'    => 'number',
				'desc'    => __( 'The Mobile menu will appear below this screen resolution. Place it at 5000 to be always visible. ', 'mobile-menu' ),
				'default' => get_option( 'mobmenu_opt_res_trigger', '1024' ),
				'max'     => '5000',
				'min'     => '479',
				'unit'    => 'px',
			) );
			$general_tab->createOption( array(
				'type' => 'note',
				'desc' => __( 'The Width trigger field is very important because it determines the width that will show the Mobile Menu. If you want it always visible set it to 5000px', 'mobile-menu' ),
			) );

			$enable_left_menu = get_option( 'mobmenu_opt_left_menu_enabled' );

			if ( 'false' === $enable_left_menu ) {
				$enable_left_menu = false;
			} else {
				$enable_left_menu = true;
			}

			// Enable/Disable Left Header Menu.
			$general_tab->createOption( array(
				'name'     => __( 'Enable Left Menu', 'mobile-menu' ),
				'id'       => 'enable_left_menu',
				'type'     => 'enable',
				'default'  => $enable_left_menu,
				'desc'     => __( 'Enable or disable the Left Menu.', 'mobile-menu' ),
				'enabled'  => __( 'On', 'mobile-menu' ),
				'disabled' => __( 'Off', 'mobile-menu' ),
			) );

			$enable_right_menu = get_option( 'mobmenu_opt_right_menu_enabled' );

			if ( 'false' === $enable_right_menu ) {
				$enable_right_menu = false;
			} else {
				$enable_right_menu = true;
			}

			// Enable/Disable Right Header Menu.
			$general_tab->createOption( array(
				'name'     => __( 'Enable Right Menu', 'mobile-menu' ),
				'id'       => 'enable_right_menu',
				'type'     => 'enable',
				'default'  => $enable_right_menu,
				'desc'     => __( 'Enable or disable the Right Menu.', 'mobile-menu' ),
				'enabled'  => __( 'On', 'mobile-menu' ),
				'disabled' => __( 'Off', 'mobile-menu' ),
			) );

			$general_tab->createOption( array(
				'name' => __( 'Hide Original Menu/header', 'mobile-menu' ),
				'type' => 'heading',
			) );

			$general_tab->createOption( array(
				'type' => 'note',
				'desc' => __( 'If you need help identifying the correct elements just send us an email to <a href="mailto:support@wpmobilemenu.com">support@wpmobilemenu.com</a> with your site url and a screenshot of the element you want to hide. We reply fast.', 'mobile-menu' ),
			) );

			// Hide Html Elements.
			$general_tab->createOption( array(
				'name'    => __( 'Hide Menu Elements', 'mobile-menu' ),
				'id'      => 'hide_elements',
				'type'    => 'text',
				'default' => get_option( 'mobmenu_opt_hide_selectors', '' ),
				'desc'    => __( '<p>This will hide the desired elements when the Mobile menu is trigerred at the chosen width. You can use CSS class or IDs.</p><br>Example of an ID and a CSS class: #main-navigation, .site-header', 'mobile-menu' ),
			) );

			$general_tab->createOption( array(
				'name'    => __( 'Hide elements by default', 'mobile-menu' ),
				'id'      => 'default_hided_elements',
				'type'    => 'multicheck',
				'desc'    => __( 'Check the desired elements', 'mobile-menu' ),
				'options' => array(
					'1' => '.nav',
					'2' => '.main-navigation',
					'3' => '.genesis-nav-menu',
					'4' => '#main-header',
					'5' => '#et-top-navigation',
					'6' => '.site-header',
					'7' => '.site-branding',
					'8' => '.ast-mobile-menu-buttons',


				),
				'default' => array( '1', '2', '3', '4', '5', '6', '7', '8' ),
			) );

			$general_tab->createOption( array(
				'name' => __( 'Miscelaneous Options', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Menu Display Type that set's the type of animation when the menu opens.
			$general_tab->createOption( array(
				'name'    => __( 'Menu Display Type', 'mobile-menu' ),
				'id'      => 'menu_display_type',
				'type'    => 'select',
				'desc'    => __( 'Choose the display type for the mobile menu.', 'mobile-menu' ),
				'options' => $display_type,
				'default' => '',
			) );

			// Menu Border Style.
			$general_tab->createOption( array(
				'name'    => __( 'Menu Items Border Size', 'mobile-menu' ),
				'id'      => 'menu_items_border_size',
				'type'    => 'number',
				'default' => '0',
				'desc'    => __( 'Choose the size of the menu items border.<a href="/wp-admin/admin.php?page=mobile-menu-options&tab=colors" target="_blank">Click here</a> to adjust the color.', 'mobile-menu' ),
				'max'     => '5',
				'min'     => '0',
				'unit'    => 'px',
			) );

			// Close Menu Icon Font.
			$general_tab->createOption( array(
				'name'    => __( 'Close Icon', 'mobile-menu' ),
				'id'      => 'close_icon_font',
				'type'    => 'text',
				'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mobile-menu' ),
				'default' => 'cancel-1',
			) );

			// Close Menu Icon Font Size.
			$general_tab->createOption( array(
				'name'    => __( 'Close Icon Font Size', 'mobile-menu' ),
				'id'      => 'close_icon_font_size',
				'type'    => 'number',
				'desc'    => __( 'Enter the Close Icon Font Size', 'mobile-menu' ),
				'default' => '30',
				'max'     => '100',
				'min'     => '0',
				'unit'    => 'px',
			) );

			// Submenu Open Icon Font.
			$general_tab->createOption( array(
				'name'    => __( 'Submenu Open Icon', 'mobile-menu' ),
				'id'      => 'submenu_open_icon_font',
				'type'    => 'text',
				'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mobile-menu' ),
				'default' => 'down-open',
			) );

			// Submenu Close Icon Font.
			$general_tab->createOption( array(
				'name'    => __( 'Submenu Close Icon', 'mobile-menu' ),
				'id'      => 'submenu_close_icon_font',
				'type'    => 'text',
				'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mobile-menu' ),
				'default' => 'up-open',
			) );

			// Submenu Icon Font Size.
			$general_tab->createOption( array(
				'name'    => __( 'Submenu Icon Font Size', 'mobile-menu' ),
				'id'      => 'submenu_icon_font_size',
				'type'    => 'number',
				'desc'    => __( 'Enter the Submenu Icon Font Size', 'mobile-menu' ),
				'default' => '30',
				'max'     => '100',
				'min'     => '0',
				'unit'    => 'px',
			) );

			$general_tab->createOption( array(
				'name' => __( 'Advanced Options', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Sticky Html Elements.
			$general_tab->createOption( array(
				'name'    => __( 'Sticky Html Elements', 'mobile-menu' ),
				'id'      => 'sticky_elements',
				'type'    => 'text',
				'default' => '',
				'desc'    => __( '<p>If you are having issues with sticky elements that dont assume a sticky behaviour, enter the ids or class name that identify that element.</p>', 'mobile-menu' ),
			) );

			// Custom css.
			$general_tab->createOption( array(
				'name' => __( 'Custom CSS', 'mobile-menu' ),
				'id'   => 'custom_css',
				'type' => 'code',
				'desc' => __( 'Put your custom CSS rules here', 'mobile-menu' ),
				'lang' => 'css',
			) );

			// Custom js.
			$general_tab->createOption( array(
				'name' => __( 'Custom JS', 'mobile-menu' ),
				'id'   => 'custom_js',
				'type' => 'code',
				'desc' => __( 'Put your custom JS rules here', 'mobile-menu' ),
				'lang' => 'javascript',
			) );

			// Header Main Options.
			$header_tab->createOption( array(
				'name' => __( 'Main options', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Enable/Disable Sticky Header.
			$header_tab->createOption( array(
				'name'     => __( 'Sticky Header', 'mobile-menu' ),
				'id'       => 'enabled_sticky_header',
				'type'     => 'enable',
				'default'  => true,
				'desc'     => __( 'Choose if you want to have the Header Fixed or scrolling with the content.', 'mobile-menu' ),
				'enabled'  => __( 'Yes', 'mobile-menu' ),
				'disabled' => __( 'No', 'mobile-menu' ),
			) );

			// Enable/Disable Naked Header.
			$header_tab->createOption( array(
				'name'     => __( 'Naked Header', 'mobile-menu' ),
				'id'       => 'enabled_naked_header',
				'type'     => 'enable',
				'default'  => false,
				'desc'     => __( 'Choose if you want to display a naked header with no background color(transparent).', 'mobile-menu' ),
				'enabled'  => __( 'Yes', 'mobile-menu' ),
				'disabled' => __( 'No', 'mobile-menu' ),
			) );

			// Enable/Disable Logo Url.
			$header_tab->createOption( array(
				'name'     => __( 'Disable Logo/Text', 'mobile-menu' ),
				'id'       => 'disabled_logo_text',
				'type'     => 'enable',
				'default'  => false,
				'desc'     => __( 'Choose if you want to disable the logo/text so it will only display the menu icons in the header.', 'mobile-menu' ),
				'enabled'  => __( 'Yes', 'mobile-menu' ),
				'disabled' => __( 'No', 'mobile-menu' ),
			) );

			$header_tab->createOption( array(
				'name' => __( 'Logo options', 'mobile-menu' ),
				'type' => 'heading',
			) );

			$header_branding = array(
				'logo' => __( 'Logo', 'mobile-menu' ),
				'text' => __( 'Text', 'mobile-menu' ),
			);

			// Enable/Disable Site Logo(deprecated field).
			$header_tab->createOption( array(
				'name'     => __( 'Site Logo', 'mobile-menu' ),
				'id'       => 'enabled_logo',
				'type'     => 'enable',
				'default'  => false,
				'desc'     => __( 'Choose if you want to display an image has logo or text instead.', 'mobile-menu' ),
				'enabled'  => __( 'Logo', 'mobile-menu' ),
				'disabled' => __( 'Text', 'mobile-menu' ),
			) );

			if ( $titan->getOption( 'enabled_logo' ) ) {
				$default_header_branding = 'logo';
			} else {
				$default_header_branding = 'text';
			}

			// Use the page title in the Header or Header Banner(global Option).
			$header_tab->createOption( array(
				'name'    => __( 'Site Logo', 'mobile-menu' ),
				'id'      => 'header_branding',
				'type'    => 'select',
				'desc'    => __( 'Chose the Header Branding ( Logo/Text ).', 'mobile-menu' ),
				'options' => $header_branding,
				'default' => $default_header_branding,
			) );

			// Site Logo Image.
			$header_tab->createOption( array(
				'name'    => __( 'Logo', 'mobile-menu' ),
				'id'      => 'logo_img',
				'type'    => 'upload',
				'desc'    => __( 'Upload your logo image', 'mobile-menu' ),
				'default' => get_option( 'mobmenu_opt_site_logo_img' ),
			) );

			// Header Height.
			$header_tab->createOption( array(
				'name'    => __( 'Logo Height', 'mobile-menu' ),
				'id'      => 'logo_height',
				'type'    => 'number',
				'desc'    => __( 'Enter the height of the logo', 'mobile-menu' ),
				'default' => '',
				'max'     => '500',
				'min'     => '0',
				'unit'    => 'px',
			) );

			// Site Logo Retina Image.
			$header_tab->createOption( array(
				'name'    => __( 'Logo Retina', 'mob-menu-lang' ),
				'id'      => 'logo_img_retina',
				'type'    => 'upload',
				'desc'    => __( 'Upload your logo image for retina devices', 'mob-menu-lang' ),
				'default' => get_option( 'mobmenu_opt_site_logo_img' ),
			) );

			// Enable/Disable Logo Url.
			$header_tab->createOption( array(
				'name'     => __( 'Disable Logo URL ', 'mobile-menu' ),
				'id'       => 'disabled_logo_url',
				'type'     => 'enable',
				'default'  => false,
				'desc'     => __( 'Choose if you want to disable the logo url to avoid being redirect to the homepage or alternative home url when touching the header logo.', 'mobile-menu' ),
				'enabled'  => __( 'Yes', 'mobile-menu' ),
				'disabled' => __( 'No', 'mobile-menu' ),
			) );

			// Alternative Site URL.
			$header_tab->createOption( array(
				'name'    => __( 'Alternative Logo URL', 'mobile-menu' ),
				'id'      => 'logo_url',
				'type'    => 'text',
				'desc'    => __( 'Enter you alternative logo URL. If you leave it blank it will use the Site URL.', 'mobile-menu' ),
				'default' => '',
			) );

			// Logo/text Top Margin.
			$header_tab->createOption( array(
				'name'    => __( 'Logo/Text Top Margin', 'mobile-menu' ),
				'id'      => 'logo_top_margin',
				'type'    => 'number',
				'desc'    => __( 'Enter the logo/text top margin', 'mobile-menu' ),
				'default' => get_option( 'mobmenu_opt_header_logo_topmargin', '0' ),
				'max'     => '450',
				'min'     => '0',
				'unit'    => 'px',
			) );

			$header_tab->createOption( array(
				'name' => __( 'Header options', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Header Height.
			$header_tab->createOption( array(
				'name'    => __( 'Header Height', 'mobile-menu' ),
				'id'      => 'header_height',
				'type'    => 'number',
				'desc'    => __( 'Enter the height of the header', 'mobile-menu' ),
				'default' => get_option( 'mobmenu_opt_header_height', '40' ),
				'max'     => '500',
				'min'     => '20',
				'unit'    => 'px',
			) );

			// Header Text.
			$header_tab->createOption( array(
				'name'    => __( 'Header Text', 'mobile-menu' ),
				'id'      => 'header_text',
				'type'    => 'text',
				'desc'    => __( 'Enter the desired text for the Mobile Header. If not specified it will use the site title.', 'mobile-menu' ),
				'default' => get_option( 'mobmenu_opt_header_text', '' ),
			) );

			// Header Text Font Size.
			$header_tab->createOption( array(
				'name'    => __( 'Header Text Font Size', 'mobile-menu' ),
				'id'      => 'header_font_size',
				'type'    => 'number',
				'desc'    => __( 'Enter the header text font size', 'mobile-menu' ),
				'default' => get_option( 'mobmenu_opt_header_font_size', '20' ),
				'max'     => '100',
				'min'     => '5',
				'unit'    => 'px',
			) );

			// Header Logo/Text Alignment.
			$header_tab->createOption( array(
				'name'    => __( 'Header Logo/Text Alignment', 'mobile-menu' ),
				'id'      => 'header_text_align',
				'type'    => 'select',
				'desc'    => __( 'Chose the header Logo/Text alignment.', 'mobile-menu' ),
				'options' => array(
					'left'   => __( 'Left', 'mobile-menu' ),
					'center' => __( 'Center', 'mobile-menu' ),
					'right'  => __( 'Right', 'mobile-menu' ),
				),
				'default' => 'center',
			) );

			// Header Logo/Text Left Margin.
			$header_tab->createOption( array(
				'name'    => __( 'Header Logo/Text Left Margin', 'mobile-menu' ),
				'id'      => 'header_text_left_margin',
				'type'    => 'number',
				'desc'    => __( 'Enter the header Logo/Text left margin (only used whit Header Left Alignment)', 'mobile-menu' ),
				'default' => '20',
				'max'     => '200',
				'min'     => '0',
				'unit'    => 'px',
			) );

			// Header Logo/Text Right Margin.
			$header_tab->createOption( array(
				'name'    => __( 'Header Logo/Text Right Margin', 'mobile-menu' ),
				'id'      => 'header_text_right_margin',
				'type'    => 'number',
				'desc'    => __( 'Enter the header Logo/Text right margin (only used whit Header Right Alignment)', 'mobile-menu' ),
				'default' => '20',
				'max'     => '200',
				'min'     => '0',
				'unit'    => 'px',
			) );

			$def_value = $titan->getOption( 'header_font_size' );

			if ( $def_value > 0 ) {
				$def_value .= 'px';
			} else {
				$def_value = '';
			}

			$header_tab->createOption( array(
				'name'                => __( 'Header Menu Font', 'mobile-menu' ),
				'id'                  => 'header_menu_font',
				'type'                => 'font',
				'desc'                => __( 'Select a style', 'mobile-menu' ),
				'show_font_weight'    => true,
				'show_font_style'     => true,
				'show_line_height'    => true,
				'show_letter_spacing' => true,
				'show_text_transform' => true,
				'show_font_variant'   => false,
				'show_text_shadow'    => false,
				'show_color'          => false,
				'css'                 => '.mobmenu .headertext {
					value
					}',
				'default'             => array(
					'line-height' => '1.5em',
					'font-family' => 'Dosis',
					'font-size'   => $def_value,
				),
			) );

			// Left Menu.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Left Menu', 'mobile-menu' ),
				'id'      => 'left_menu',
				'type'    => 'select',
				'desc'    => __( 'Select the menu that will open in the left side.', 'mobile-menu' ),
				'options' => $menus_options,
				'default' => $titan->getOption( 'left_menu' ),
			) );

			// Click Menu Parent link to open Sub menu.
			$left_menu_tab->createOption( array(
				'name'     => __( 'Parent Link open submenu', 'mobile-menu' ),
				'id'       => 'left_menu_parent_link_submenu',
				'type'     => 'enable',
				'default'  => false,
				'desc'     => __( 'Choose if you want to open the submenu by click in the Parent Menu item.', 'mobile-menu' ),
				'enabled'  => __( 'Yes', 'mobile-menu' ),
				'disabled' => __( 'No', 'mobile-menu' ),
			) );

			// Click Menu Parent link to open Sub menu(2nd Level).
			$left_menu_tab->createOption( array(
				'name'     => __( 'Parent Link open submenu(2nd Level)', 'mobile-menu' ),
				'id'       => 'left_menu_parent_link_submenu_2nd_level',
				'type'     => 'enable',
				'default'  => false,
				'desc'     => __( 'Choose if you want to open the sub-submenu by click in the sub menu item.', 'mobile-menu' ),
				'enabled'  => __( 'Yes', 'mobile-menu' ),
				'disabled' => __( 'No', 'mobile-menu' ),
			) );

			$left_menu_tab->createOption( array(
				'name' => __( 'Menu Icon', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Text After Left Icon.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Text After Icon', 'mobile-menu' ),
				'id'      => 'left_menu_text',
				'type'    => 'text',
				'desc'    => __( 'Enter the text that will appear after the Icon.', 'mobile-menu' ),
				'default' => '',
			) );

			// Text After Left Icon Font Options.
			$left_menu_tab->createOption( array(
				'name'                => __( 'Text After Icon Font', 'mobile-menu' ),
				'id'                  => 'text_after_left_icon_font',
				'type'                => 'font',
				'desc'                => __( 'Select a style', 'mobile-menu' ),
				'show_font_weight'    => true,
				'show_font_style'     => true,
				'show_line_height'    => true,
				'show_letter_spacing' => true,
				'show_text_transform' => true,
				'show_font_variant'   => false,
				'show_text_shadow'    => false,
				'show_color'          => false,
				'css'                 => ' .mobmenul-container .left-menu-icon-text {
							value
				}',
				'default'             => array(
					'line-height' => '1.5em',
					'font-family' => 'Dosis',
				),
			) );

			// Icon Action Option.
			$left_menu_tab->createOption( array(
				'name'     => __( 'Icon Action', 'mobile-menu' ),
				'id'       => 'left_menu_icon_action',
				'type'     => 'enable',
				'default'  => true,
				'desc'     => __( 'Open the Left Menu Panel or open a Link url.', 'mobile-menu' ),
				'enabled'  => __( 'Open Menu', 'mobile-menu' ),
				'disabled' => __( 'Open Link Url', 'mobile-menu' ),
			) );

			// Icon URL.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Icon Link URL', 'mobile-menu' ),
				'id'      => 'left_icon_url',
				'type'    => 'text',
				'desc'    => __( 'Enter the Icon Link Url.', 'mobile-menu' ),
				'default' => '',
			) );

			// Icon URL Target.
			$left_menu_tab->createOption( array(
				'name'     => __( 'Icon Link Url Target', 'mobile-menu' ),
				'id'       => 'left_icon_url_target',
				'type'     => 'enable',
				'default'  => true,
				'desc'     => __( 'Choose it the link will open in the same window or in the new window.', 'mobile-menu' ),
				'enabled'  => __( 'Self', 'mobile-menu' ),
				'disabled' => __( 'Blank', 'mobile-menu' ),
			) );

			// Icon Image/text Option.
			$left_menu_tab->createOption( array(
				'name'     => __( 'Icon Type', 'mobile-menu' ),
				'id'       => 'left_menu_icon_opt',
				'type'     => 'enable',
				'default'  => false,
				'desc'     => __( 'Choose if you want to display an image or an icon.', 'mobile-menu' ),
				'enabled'  => __( 'Image', 'mobile-menu' ),
				'disabled' => __( 'Icon Font', 'mobile-menu' ),
			) );

			// Left Menu Icon Font.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Icon Font', 'mobile-menu' ),
				'id'      => 'left_menu_icon_font',
				'type'    => 'text',
				'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mobile-menu' ),
				'default' => 'menu',
			) );

			// Left Menu Icon Font Size.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Icon Font Size', 'mobile-menu' ),
				'id'      => 'left_icon_font_size',
				'type'    => 'number',
				'desc'    => __( 'Enter the Left Icon Font Size', 'mobile-menu' ),
				'default' => '30',
				'max'     => '100',
				'min'     => '0',
				'unit'    => 'px',
			) );

			// Left Menu Icon.
			$left_menu_tab->createOption( array(
				'name'        => __( 'Icon Image', 'mobile-menu' ),
				'id'          => 'left_menu_icon',
				'type'        => 'upload',
				'placeholder' => 'Click here to select the icon',
				'desc'        => __( 'Upload your left menu icon image', 'mobile-menu' ),
				'default'     => get_option( 'mobmenu_opt_left_icon' ),
			) );

			// Left Menu Icon Top Margin.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Icon Top Margin', 'mobile-menu' ),
				'id'      => 'left_icon_top_margin',
				'type'    => 'number',
				'desc'    => __( 'Enter the Left Icon Top Margin', 'mobile-menu' ),
				'default' => get_option( 'mobmenu_opt_left_icon_topmargin', '5' ),
				'max'     => '450',
				'min'     => '0',
				'unit'    => 'px',
			) );
			// Left Menu Icon Left Margin.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Icon Left Margin', 'mobile-menu' ),
				'id'      => 'left_icon_left_margin',
				'type'    => 'number',
				'desc'    => __( 'Enter the Left Icon Left Margin', 'mobile-menu' ),
				'default' => '5',
				'max'     => '450',
				'min'     => '0',
				'unit'    => 'px',
			) );

			$left_menu_tab->createOption( array(
				'name' => __( 'Left Panel options', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Left Menu Background Image.
			$left_menu_tab->createOption( array(
				'name' => __( 'Panel Background Image', 'mobile-menu' ),
				'id'   => 'left_menu_bg_image',
				'type' => 'upload',
				'desc' => __( 'Upload your left menu background image(this will override the Background color option)', 'mobile-menu' ),
			) );

			// Left Menu Background Image Opacity.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Panel Background Image Opacity', 'mobile-menu' ),
				'id'      => 'left_menu_bg_opacity',
				'type'    => 'number',
				'desc'    => __( 'Enter the Left Background image opacity', 'mobile-menu' ),
				'default' => '100',
				'max'     => '100',
				'min'     => '10',
				'step'    => '10',
				'unit'    => '%',
			) );

			// Left Menu Background Image Size.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Panel Background Image Size', 'mobile-menu' ),
				'id'      => 'left_menu_bg_image_size',
				'type'    => 'upload',
				'type'    => 'select',
				'desc'    => __( 'Select the Background image size type. <a href="https://www.w3schools.com/cssref/css3_pr_background-size.asp" target="_blank">See the CSS Documentation</a>', 'mobile-menu' ),
				'options' => array(
					'auto'    => __( 'Auto', 'mobile-menu' ),
					'contain' => __( 'Contain', 'mobile-menu' ),
					'cover'   => __( 'Cover', 'mobile-menu' ),
					'inherit' => __( 'Inherit', 'mobile-menu' ),
					'initial' => __( 'Initial', 'mobile-menu' ),
					'unset'   => __( 'Unset', 'mobile-menu' ),
				),
				'default' => 'cover',
			) );

			// Left Menu Gradient css.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Panel Background Gradient Css', 'mobile-menu' ),
				'id'      => 'left_menu_bg_gradient',
				'type'    => 'text',
				'desc'    => __( '<a href="https://webgradients.com/" target="_blank">Click here</a> to get your desired Gradient, just press the copy button and paste in this field.', 'mobile-menu' ),
				'default' => '',
			) );

			// Left Menu Panel Width Units.
			$left_menu_tab->createOption( array(
				'name'     => __( 'Menu Panel Width Units', 'mobile-menu' ),
				'id'       => 'left_menu_width_units',
				'type'     => 'enable',
				'default'  => true,
				'desc'     => __( 'Choose the width units.', 'mobile-menu' ),
				'enabled'  => 'Pixels',
				'disabled' => __( 'Percentage', 'mobile-menu' ),
			) );

			// Left Menu Panel Width.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Menu Panel Width(Pixels)', 'mobile-menu' ),
				'id'      => 'left_menu_width',
				'type'    => 'number',
				'desc'    => __( 'Enter the Left Menu Panel Width', 'mobile-menu' ),
				'default' => '270',
				'max'     => '1000',
				'min'     => '0',
				'unit'    => 'px',
			) );

			// Left Menu Panel Width.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Menu Panel Width(Percentage)', 'mobile-menu' ),
				'id'      => 'left_menu_width_percentage',
				'type'    => 'number',
				'desc'    => __( 'Enter the Left Menu Panel Width', 'mobile-menu' ),
				'default' => '70',
				'max'     => '90',
				'min'     => '0',
				'unit'    => '%',
			) );

			// Left Menu Content Padding.
			$left_menu_tab->createOption( array(
				'name'    => __( 'Left Menu Content Padding', 'mobile-menu' ),
				'id'      => 'left_menu_content_padding',
				'type'    => 'number',
				'desc'    => __( 'Enter the Left Menu Content Padding', 'mobile-menu' ),
				'default' => '0',
				'max'     => '30',
				'min'     => '0',
				'step'    => '1',
				'unit'    => '%',
			) );

			// Left Menu Font.
			$left_menu_tab->createOption( array(
				'name'                => __( 'Left Menu Font', 'mobile-menu' ),
				'id'                  => 'left_menu_font',
				'type'                => 'font',
				'desc'                => __( 'Select a style', 'mobile-menu' ),
				'show_font_weight'    => true,
				'show_font_style'     => true,
				'show_line_height'    => true,
				'show_letter_spacing' => true,
				'show_text_transform' => true,
				'show_font_variant'   => false,
				'show_text_shadow'    => false,
				'show_color'          => false,
				'css'                 => ' #mobmenuleft  .mob-expand-submenu , #mobmenuleft > .widgettitle, #mobmenuleft li a, #mobmenuleft li a:visited, #mobmenuleft .mobmenu_content h2, #mobmenuleft .mobmenu_content h3 {
							value
				}',
				'default'             => array(
					'line-height' => '1.5em',
					'font-family' => 'Dosis',
				),
			) );

			// Right Menu.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Right Menu', 'mobile-menu' ),
				'id'      => 'right_menu',
				'type'    => 'select',
				'desc'    => __( 'Select the menu that will open in the right side.', 'mobile-menu' ),
				'options' => $menus_options,
				'default' => $titan->getOption( 'right_menu' ),
			) );

			// Click Menu Parent link to open Sub menu.
			$right_menu_tab->createOption( array(
				'name'     => __( 'Parent Link open submenu', 'mobile-menu' ),
				'id'       => 'right_menu_parent_link_submenu',
				'type'     => 'enable',
				'default'  => false,
				'desc'     => __( 'Choose if you want to open the submenu by click in the Parent Menu item.', 'mobile-menu' ),
				'enabled'  => __( 'Yes', 'mobile-menu' ),
				'disabled' => __( 'No', 'mobile-menu' ),
			) );

			// Click Menu Parent link to open Sub menu(2nd Level).
			$right_menu_tab->createOption( array(
				'name'     => __( 'Parent Link open submenu(2nd Level)', 'mobile-menu' ),
				'id'       => 'right_menu_parent_link_submenu_2nd_level',
				'type'     => 'enable',
				'default'  => false,
				'desc'     => __( 'Choose if you want to open the sub-submenu by click in the sub menu item.', 'mobile-menu' ),
				'enabled'  => __( 'Yes', 'mobile-menu' ),
				'disabled' => __( 'No', 'mobile-menu' ),
			) );

			// Icon Heading.
			$right_menu_tab->createOption( array(
				'name' => __( 'Menu Icon', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Text Before Right Icon.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Text Before Icon', 'mobile-menu' ),
				'id'      => 'right_menu_text',
				'type'    => 'text',
				'desc'    => __( 'Enter the text that will appear before the Icon.', 'mobile-menu' ),
				'default' => '',
			) );

			// Text Before Right Icon Font Options.
			$right_menu_tab->createOption( array(
				'name'                => __( 'Text Before Icon Font', 'mobile-menu' ),
				'id'                  => 'text_before_right_icon_font',
				'type'                => 'font',
				'desc'                => __( 'Select a style', 'mobile-menu' ),
				'show_font_weight'    => true,
				'show_font_style'     => true,
				'show_line_height'    => true,
				'show_letter_spacing' => true,
				'show_text_transform' => true,
				'show_font_variant'   => false,
				'show_text_shadow'    => false,
				'show_color'          => false,
				'css'                 => ' .mobmenur-container .right-menu-icon-text {
							value
				}',
				'default'             => array(
					'line-height' => '1.5em',
					'font-family' => 'Dosis',
				),
			) );

			// Icon Action Option.
			$right_menu_tab->createOption( array(
				'name'     => __( 'Icon Action', 'mobile-menu' ),
				'id'       => 'right_menu_icon_action',
				'type'     => 'enable',
				'default'  => true,
				'desc'     => __( 'Open the Right Menu Panel or open a Link url.', 'mobile-menu' ),
				'enabled'  => __( 'Open Menu', 'mobile-menu' ),
				'disabled' => __( 'Open Link Url', 'mobile-menu' ),
			) );

			// Icon URL.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Icon Link URL', 'mobile-menu' ),
				'id'      => 'right_icon_url',
				'type'    => 'text',
				'desc'    => __( 'Enter the Icon Link Url.', 'mobile-menu' ),
				'default' => '',
			) );

			// Icon URL Target.
			$right_menu_tab->createOption( array(
				'name'     => __( 'Icon Link Url Target', 'mobile-menu' ),
				'id'       => 'right_icon_url_target',
				'type'     => 'enable',
				'default'  => true,
				'desc'     => __( 'Choose it the link will open in the same window or in the new window.', 'mobile-menu' ),
				'enabled'  => __( 'Self', 'mobile-menu' ),
				'disabled' => __( 'Blank', 'mobile-menu' ),
			) );

			// Icon Image/Icon Font.
			$right_menu_tab->createOption( array(
				'name'     => __( 'Icon Type', 'mobile-menu' ),
				'id'       => 'right_menu_icon_opt',
				'type'     => 'enable',
				'default'  => false,
				'desc'     => __( 'Choose if you want to display an image or an icon.', 'mobile-menu' ),
				'enabled'  => __( 'Image', 'mobile-menu' ),
				'disabled' => __( 'Icon Font', 'mobile-menu' ),
			) );

			// Right Menu Icon Font.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Icon Font', 'mobile-menu' ),
				'id'      => 'right_menu_icon_font',
				'type'    => 'text',
				'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mobile-menu' ),
				'default' => 'menu',
			) );

			// Right Menu Icon Font Size.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Icon Font Size', 'mobile-menu' ),
				'id'      => 'right_icon_font_size',
				'type'    => 'number',
				'desc'    => __( 'Enter the Right Icon Font Size', 'mobile-menu' ),
				'default' => '30',
				'max'     => '100',
				'min'     => '0',
				'unit'    => 'px',
			) );

			// Right Menu Icon.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Icon Image', 'mobile-menu' ),
				'id'      => 'right_menu_icon',
				'type'    => 'upload',
				'desc'    => __( 'Upload your right menu icon image', 'mobile-menu' ),
				'default' => get_option( 'mobmenu_opt_right_icon' ),
			) );

			// Right Menu Icon Top Margin.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Icon Top Margin', 'mobile-menu' ),
				'id'      => 'right_icon_top_margin',
				'type'    => 'number',
				'desc'    => __( 'Enter the Right Icon Top Margin', 'mobile-menu' ),
				'default' => get_option( 'mobmenu_opt_right_icon_topmargin', '5' ),
				'max'     => '450',
				'min'     => '0',
				'unit'    => 'px',
			) );

			// Right Menu Icon Right Margin.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Icon Right Margin', 'mobile-menu' ),
				'id'      => 'right_icon_right_margin',
				'type'    => 'number',
				'desc'    => __( 'Enter the Right Icon Right Margin', 'mobile-menu' ),
				'default' => '5',
				'max'     => '450',
				'min'     => '0',
				'unit'    => 'px',
			) );

			// Background Heading.
			$right_menu_tab->createOption( array(
				'name' => __( 'Right Panel options', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Right Menu Background Image.
			$right_menu_tab->createOption( array(
				'name' => __( 'Panel Background Image', 'mobile-menu' ),
				'id'   => 'right_menu_bg_image',
				'type' => 'upload',
				'desc' => __( 'upload your right menu background image(this will override the Background color option)', 'mobile-menu' ),
			) );

			// Right Menu Background Image Opacity.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Panel Background Image Opacity', 'mobile-menu' ),
				'id'      => 'right_menu_bg_opacity',
				'type'    => 'number',
				'desc'    => __( 'Enter the Right Background image opacity', 'mobile-menu' ),
				'default' => '100',
				'max'     => '100',
				'min'     => '10',
				'step'    => '10',
				'unit'    => '%',
			) );

			// Left Menu Background Image Size.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Panel Background Image Size', 'mobile-menu' ),
				'id'      => 'right_menu_bg_image_size',
				'type'    => 'upload',
				'type'    => 'select',
				'desc'    => __( 'Select the Background image size type. <a href="https://www.w3schools.com/cssref/css3_pr_background-size.asp" target="_blank">See the CSS Documentation</a>', 'mobile-menu' ),
				'options' => array(
					'auto'    => __( 'Auto', 'mobile-menu' ),
					'contain' => __( 'Contain', 'mobile-menu' ),
					'cover'   => __( 'Cover', 'mobile-menu' ),
					'inherit' => __( 'Inherit', 'mobile-menu' ),
					'initial' => __( 'Initial', 'mobile-menu' ),
					'unset'   => __( 'Unset', 'mobile-menu' ),
				),
				'default' => 'cover',
			) );

			// Right Menu Gradient css.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Panel Background Gradient Css', 'mobile-menu' ),
				'id'      => 'right_menu_bg_gradient',
				'type'    => 'text',
				'desc'    => __( '<a href="https://webgradients.com/" target="_blank">Click here</a> to get your desired Gradient, just press the copy button and paste in this field.', 'mobile-menu' ),
				'default' => '',
			) );

			// Right Menu Panel Width Units.
			$right_menu_tab->createOption( array(
				'name'     => __( 'Menu Panel Width Units', 'mobile-menu' ),
				'id'       => 'right_menu_width_units',
				'type'     => 'enable',
				'default'  => true,
				'desc'     => __( 'Choose the width units.', 'mobile-menu' ),
				'enabled'  => __( 'Pixels', 'mobile-menu' ),
				'disabled' => __( 'Percentage', 'mobile-menu' ),
			) );

			// Right Menu Panel Width.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Menu Panel Width(Pixels)', 'mobile-menu' ),
				'id'      => 'right_menu_width',
				'type'    => 'number',
				'desc'    => __( 'Enter the Right Menu Panel Width', 'mobile-menu' ),
				'default' => '270',
				'max'     => '450',
				'min'     => '0',
				'unit'    => 'px',
			) );

			// Right Menu Panel Width.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Menu Panel Width(Percentage)', 'mobile-menu' ),
				'id'      => 'right_menu_width_percentage',
				'type'    => 'number',
				'desc'    => __( 'Enter the Right Menu Panel Width', 'mobile-menu' ),
				'default' => '70',
				'max'     => '90',
				'min'     => '0',
				'unit'    => '%',
			) );

			// Right Menu Content Padding.
			$right_menu_tab->createOption( array(
				'name'    => __( 'Right Menu Content Padding', 'mobile-menu' ),
				'id'      => 'right_menu_content_padding',
				'type'    => 'number',
				'desc'    => __( 'Enter the Right Menu Content Padding', 'mobile-menu' ),
				'default' => '0',
				'max'     => '30',
				'min'     => '0',
				'step'    => '1',
				'unit'    => '%',
			) );

			// Right Menu Font.
			$right_menu_tab->createOption( array(
				'name'                => __( 'Right Menu Font', 'mobile-menu' ),
				'id'                  => 'right_menu_font',
				'type'                => 'font',
				'desc'                => __( 'Select a style', 'mobile-menu' ),
				'show_font_weight'    => true,
				'show_font_style'     => true,
				'show_line_height'    => true,
				'show_letter_spacing' => true,
				'show_text_transform' => true,
				'show_font_variant'   => false,
				'show_text_shadow'    => false,
				'show_color'          => false,
				'css'                 => '#mobmenuright li a, #mobmenuright li a:visited, #mobmenuright .mobmenu_content h2, #mobmenuright .mobmenu_content h3 {
							value
				}',
				'default'             => array(
					'line-height' => '1.5em',
					'font-family' => 'Dosis',
				),
			) );

			// Overlay Background color.
			$colors_tab->createOption( array(
				'name'    => __( 'Overlay Background Color', 'mobile-menu' ),
				'id'      => 'overlay_bg_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => 'rgba(255,255,255,0.78)',
			) );

			// Menu Items Border color.
			$colors_tab->createOption( array(
				'name'    => __( 'Menu Items Border Color', 'mobile-menu' ),
				'id'      => 'menu_items_border_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => 'rgba(0, 0, 0, 0.83)',
			) );

			// Header Left Menu Section.
			$colors_tab->createOption( array(
				'name' => __( 'Header Colors', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Header Background color.
			$colors_tab->createOption( array(
				'name'    => __( 'Header Background Color', 'mobile-menu' ),
				'id'      => 'header_bg_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_header_bgcolor', '#fbfbfb' ),
			) );

			// Header Text color.
			$colors_tab->createOption( array(
				'name'    => __( 'Header Text Color', 'mobile-menu' ),
				'id'      => 'header_text_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_header_textcolor', '#222' ),
			) );

			// Header Left Menu Section.
			$colors_tab->createOption( array(
				'name' => __( 'Left Menu Colors', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Left Menu Icon color.
			$colors_tab->createOption( array(
				'name'    => __( 'Icon Color', 'mobile-menu' ),
				'id'      => 'left_menu_icon_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#222',
			) );

			// Header Text After Left Icon.
			$colors_tab->createOption( array(
				'name'    => __( 'Text After Left Icon', 'mobile-menu' ),
				'id'      => 'header_text_after_icon',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#222',
			) );

			// Left Panel Close Button Color.
			$colors_tab->createOption( array(
				'name'    => __( 'Close Button Color', 'mobile-menu' ),
				'id'      => 'left_panel_close_button_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#000',
			) );

			// Left Panel Background color.
			$colors_tab->createOption( array(
				'name'    => __( 'Background Color', 'mobile-menu' ),
				'id'      => 'left_panel_bg_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_left_menu_bgcolor', '#f9f9f9' ),
			) );

			// Left Panel Text color.
			$colors_tab->createOption( array(
				'name'    => __( 'Text Color', 'mobile-menu' ),
				'id'      => 'left_panel_text_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_left_text_color', '#222' ),
			) );

			// Left Panel Background Hover Color.
			$colors_tab->createOption( array(
				'name'    => __( 'Background Hover Color', 'mobile-menu' ),
				'id'      => 'left_panel_hover_bgcolor',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_left_bg_color_hover', '#a3d3e8' ),
			) );

			// Left Panel Text color Hover.
			$colors_tab->createOption( array(
				'name'    => __( 'Hover Text Color', 'mobile-menu' ),
				'id'      => 'left_panel_hover_text_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_left_text_color_hover', '#fff' ),
			) );


			// 2nd Level Left Menu Section.
			$colors_tab->createOption( array(
				'name' => __( 'Left 2nd Level Menu Colors', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Left Panel 2nd Level Background Color.
			$colors_tab->createOption( array(
				'name'    => __( '2nd Level Background Color', 'mobile-menu' ),
				'id'      => 'left_panel_submenu_bgcolor',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_left_submenu_bg_color', '#eff1f1' ),
			) );

			// Left Panel Sub-menu Text Color.
			$colors_tab->createOption( array(
				'name'    => __( '2nd Level Text Color', 'mobile-menu' ),
				'id'      => 'left_panel_submenu_text_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_left_submenu_text_color', '#222' ),
			) );

			// Left Panel 2nd Level Background Color Hover.
			$colors_tab->createOption( array(
				'name'    => __( '2nd Level Background Color Hover', 'mobile-menu' ),
				'id'      => 'left_panel_2nd_level_bgcolor_hover',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#eff1f1',
			) );

			// Left Panel 2nd Level Text Color Hover.
			$colors_tab->createOption( array(
				'name'    => __( '2nd Level Text Color Hover', 'mobile-menu' ),
				'id'      => 'left_panel_2nd_level_text_color_hover',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#222',
			) );

			// 3rd Level Left Menu Section.
			$colors_tab->createOption( array(
				'name' => __( 'Left 3rd Level Menu Colors', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Left Panel 3rd Level Background Color Hover.
			$colors_tab->createOption( array(
				'name'    => __( '3rd Level Background Color', 'mobile-menu' ),
				'id'      => 'left_panel_3rd_level_bgcolor',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#eff1f1',
			) );

			// Left Panel 3rd Level Text Color Hover.
			$colors_tab->createOption( array(
				'name'    => __( '3rd Level Text Color', 'mobile-menu' ),
				'id'      => 'left_panel_3rd_level_text_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#222',
			) );

			// Left Panel 3rd Level Background Color Hover.
			$colors_tab->createOption( array(
				'name'    => __( '3rd Level Background Color Hover', 'mobile-menu' ),
				'id'      => 'left_panel_3rd_level_bgcolor_hover',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#eff1f1',
			) );

			// Left Panel 3rd Level Text Color Hover.
			$colors_tab->createOption( array(
				'name'    => __( '3rd Level Text Color Hover', 'mobile-menu' ),
				'id'      => 'left_panel_3rd_level_text_color_hover',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#222',
			) );

			// Header Right Menu Section.
			$colors_tab->createOption( array(
				'name' => __( 'Right Menu Colors', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Right Menu Icon color.
			$colors_tab->createOption( array(
				'name'    => __( 'Icon Color', 'mobile-menu' ),
				'id'      => 'right_menu_icon_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#222',
			) );

			// Header Text Before Right Icon.
			$colors_tab->createOption( array(
				'name'    => __( 'Text Before Right Icon', 'mobile-menu' ),
				'id'      => 'header_text_before_icon',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#222',
			) );

			// Right Panel Close Button Color.
			$colors_tab->createOption( array(
				'name'    => __( 'Close Button Color', 'mobile-menu' ),
				'id'      => 'right_panel_close_button_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#000',
			) );

			// Right Panel Background color.
			$colors_tab->createOption( array(
				'name'    => __( 'Background Color', 'mobile-menu' ),
				'id'      => 'right_panel_bg_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_right_menu_bgcolor', '#f9f9f9' ),
			) );

			// Right Panel Background Hover Color.
			$colors_tab->createOption( array(
				'name'    => __( 'Background Hover Color', 'mobile-menu' ),
				'id'      => 'right_panel_hover_bgcolor',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_right_bg_color_hover', '#a3d3e8' ),
			) );

			// Right Panel Text color.
			$colors_tab->createOption( array(
				'name'    => __( 'Text Color', 'mobile-menu' ),
				'id'      => 'right_panel_text_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_right_text_color', '#222' ),
			) );

			// Right Panel Text color Hover.
			$colors_tab->createOption( array(
				'name'    => __( 'Hover Text Color', 'mobile-menu' ),
				'id'      => 'right_panel_hover_text_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_right_text_color_hover', '#fff' ),
			) );

			// Header Right Menu Section.
			$colors_tab->createOption( array(
				'name' => __( 'Right 2nd Level Menu Colors', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Right Panel 2nd Level Background Color.
			$colors_tab->createOption( array(
				'name'    => __( '2nd Level Background Color', 'mobile-menu' ),
				'id'      => 'right_panel_submenu_bgcolor',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_right_submenu_bg_color', '#eff1f1' ),
			) );

			// Right Panel 2nd Level Text Color.
			$colors_tab->createOption( array(
				'name'    => __( '2nd Level Text Color', 'mobile-menu' ),
				'id'      => 'right_panel_submenu_text_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_right_submenu_text_color', '#222' ),
			) );

			// Right Panel 2nd Level Background Color Hover.
			$colors_tab->createOption( array(
				'name'    => __( '2nd Level Background Color Hover', 'mobile-menu' ),
				'id'      => 'right_panel_2nd_level_bgcolor_hover',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#eff1f1',
			) );

			// Right Panel 3rd Level Text Color Hover.
			$colors_tab->createOption( array(
				'name'    => __( '2nd Level Text Color Hover', 'mobile-menu' ),
				'id'      => 'right_panel_2nd_level_text_color_hover',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#222',
			) );

			// Header Right Menu Section.
			$colors_tab->createOption( array(
				'name' => __( 'Right 3rd Level Menu Colors', 'mobile-menu' ),
				'type' => 'heading',
			) );

			// Right Panel 3rd Level Background Color.
			$colors_tab->createOption( array(
				'name'    => __( '3rd Level Background Color', 'mobile-menu' ),
				'id'      => 'right_panel_3rd_level_bgcolor',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#eff1f1',
			) );

			// Right Panel 3rd Level Text Color.
			$colors_tab->createOption( array(
				'name'    => __( '3rd Level Text Color', 'mobile-menu' ),
				'id'      => 'right_panel_3rd_level_text_color',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => get_option( 'mobmenu_opt_right_submenu_text_color', '#222' ),
			) );

			// Right Panel 3rd Level Background Color Hover.
			$colors_tab->createOption( array(
				'name'    => __( '3rd Level Background Color Hover', 'mobile-menu' ),
				'id'      => 'right_panel_3rd_level_bgcolor_hover',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#eff1f1',
			) );

			// Right Panel 3rd Level Text Color Hover.
			$colors_tab->createOption( array(
				'name'    => __( '3rd Level Text Color Hover', 'mobile-menu' ),
				'id'      => 'right_panel_3rd_level_text_color_hover',
				'type'    => 'color',
				'desc'    => '',
				'alpha'   => true,
				'default' => '#222',
			) );

			$panel->createOption( array(
				'type' => 'save',
			) );
		}

	}
}
