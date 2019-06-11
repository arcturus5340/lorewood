<?php

/**
 * ---------------
 * Plugin Styling
 * ---------------
 * WP Mobile Menu
 * Copyright WP Mobile Menu 2017 - http://www.wpmobilemenu.com/
 * CUSTOM CSS OUTPUT
 */

global $mm_fs;
$titan = TitanFramework::getInstance( 'mobmenu' );
$default_elements = '';
$def_el_arr = $titan->getOption( 'default_hided_elements' );

if ( in_array( '1', $def_el_arr ) ) {
	$default_elements .= '.nav, ';
}
if ( in_array( '2', $def_el_arr ) ) {
	$default_elements .= '.main-navigation, ';
}
if ( in_array( '3', $def_el_arr ) ) {
	$default_elements .= '.genesis-nav-menu, ';
}
if ( in_array( '4', $def_el_arr ) ) {
	$default_elements .= '#main-header, ';
}
if ( in_array( '5', $def_el_arr ) ) {
	$default_elements .= '#et-top-navigation, ';
}
if ( in_array( '6', $def_el_arr ) ) {
	$default_elements .= '.site-header, ';
}
if ( in_array( '7', $def_el_arr ) ) {
	$default_elements .= '.site-branding, ';
}
if ( in_array( '8', $def_el_arr ) ) {
	$default_elements .= '.ast-mobile-menu-buttons, ';
}

$default_elements .= '.hide';

// Check if the Mobile Menu is enable in the plugin options.
if ( $titan->getOption( 'enabled_naked_header' ) ) {
	$header_bg_color = 'transparent';
	$wrap_padding_top = '0';
} else {
	$header_bg_color = $titan->getOption( 'header_bg_color' );
	$wrap_padding_top = $titan->getOption( 'header_height' );
}

$trigger_res = $titan->getOption( 'width_trigger' );
$right_menu_width = $titan->getOption( 'right_menu_width' ) . 'px';

if ( $titan->getOption( 'right_menu_width_units' ) ) {
	$right_menu_width = $titan->getOption( 'right_menu_width' ) . 'px';
	$right_menu_width_translate = $right_menu_width;
} else {
	$right_menu_width = $titan->getOption( 'right_menu_width_percentage' ) . '%';
	$right_menu_width_translate = '100%';
}


if ( $titan->getOption( 'left_menu_width_units' ) ) {
	$left_menu_width = $titan->getOption( 'left_menu_width' ) . 'px';
	$left_menu_width_translate = $left_menu_width;
} else {
	$left_menu_width = $titan->getOption( 'left_menu_width_percentage' ) . '%';
	$left_menu_width_translate = '100%';
}

$logo_height = '';
if ( $titan->getOption( 'logo_height' ) > 0 ) {
	$logo_height = $titan->getOption( 'logo_height' );
} else {
	$logo_height = $titan->getOption( 'header_height' );
}
$logo_height = 'height:' . $logo_height . 'px!important;';

$header_height       = $titan->getOption( 'header_height' );
$total_header_height = $header_height;

?>
@media only screen and (min-width:<?php echo $trigger_res; ?>px) {
	
	.mob_menu, .mob_menu_left_panel, .mob_menu_right_panel, .mobmenu {
		display: none!important;
	}
	
}

/* Our css Custom Options values */
@media only screen and (max-width:<?php echo ($trigger_res - 1); ?>px) {
	<?php if ( '' !== $titan->getOption( 'hide_elements' ) ) { ?>
	<?php echo  $titan->getOption( 'hide_elements' ); ?> {
		display:none !important;
	}
	<?php } ?>

	.mob-menu-left-panel .mobmenu-left-bt, .mob-menu-right-panel .mobmenu-right-bt {
		position: absolute;
		right: 0px;
		top: 0px;
		font-size: 30px;
	}

	.mob-menu-slideout  .mob-cancel-button{
		display: none;
	}

	.mobmenu, .mob-menu-left-panel, .mob-menu-right-panel {
		display: block;
	}

	.mobmenur-container i {
		color: <?php echo $titan->getOption( 'right_menu_icon_color' ); ?>;
	}

	.mobmenul-container i {
		color: <?php echo $titan->getOption( 'left_menu_icon_color' ); ?>;
	}
	.mobmenul-container img {
		max-height:  <?php echo $titan->getOption( 'header_height' ); ?>px;
		float: left;
	}
		.mobmenur-container img {
		max-height:  <?php echo $titan->getOption( 'header_height' ); ?>px;
		float: right;
	}
	#mobmenuleft li a , #mobmenuleft li a:visited {
		color: <?php echo  $titan->getOption( 'left_panel_text_color' ); ?>;

	}
	.mobmenu_content h2, .mobmenu_content h3, .show-nav-left .mob-menu-copyright, .show-nav-left .mob-expand-submenu i {
		color: <?php echo $titan->getOption( 'left_panel_text_color' ); ?>;
	}
	.mobmenu_content #mobmenuleft > li > a:hover {
		background-color: <?php echo $titan->getOption( 'left_panel_hover_bgcolor' ); ?>;
	}

	.mobmenu_content #mobmenuright > li > a:hover {
		background-color: <?php echo $titan->getOption( 'right_panel_hover_bgcolor' ); ?>;
	}
	
	.mobmenu_content #mobmenuleft .sub-menu {
		background-color: <?php echo $titan->getOption( 'left_panel_submenu_bgcolor' ); ?>;
		margin: 0;
		color: <?php echo $titan->getOption( 'left_panel_submenu_text_color' ); ?>;
		width: 100%;
		position: initial;
	}

	/* 2nd Level Left Background Color on Hover */
	.mobmenu_content #mobmenuleft .sub-menu li:hover {
		background-color: <?php echo $titan->getOption( 'left_panel_2nd_level_bgcolor_hover' ); ?>;
	}

	/* 2nd Level Left Background Color on Hover */
	.mobmenu_content #mobmenuleft .sub-menu li:hover a {
		color: <?php echo $titan->getOption( 'left_panel_2nd_level_text_color_hover' ); ?>;
	}
	
	/* 2nd Level Right Background Color on Hover */
	.mobmenu_content #mobmenuright .sub-menu li:hover {
		background-color: <?php echo $titan->getOption( 'right_panel_2nd_level_bgcolor_hover' ); ?>;
	}

	/* 2nd Level Right Background Color on Hover */
	.mobmenu_content #mobmenuright .sub-menu li:hover a {
		color: <?php echo $titan->getOption( 'right_panel_2nd_level_text_color_hover' ); ?>;
	}
	.mob-cancel-button {
		font-size: <?php echo $titan->getOption( 'close_icon_font_size' ); ?>px!important;
		z-index: 99999999;
	}

	.mob-menu-left-panel .mob-cancel-button {
		color: <?php echo $titan->getOption( 'left_panel_close_button_color' ); ?>;
	}

	.mob-menu-right-panel .mob-cancel-button {
		color: <?php echo $titan->getOption( 'right_panel_close_button_color' ); ?>;
	}

	.mob-menu-slideout-over .mobmenu_content {
		padding-top: 40px;
	}

	.mob-menu-left-bg-holder {
		<?php
		if ( $titan->getOption( 'left_menu_bg_image' ) ) {
		?>
		background: url(<?php echo wp_get_attachment_url( $titan->getOption( 'left_menu_bg_image' ) ); ?>);
		<?php
		}
		?>
		opacity: <?php echo $titan->getOption( 'left_menu_bg_opacity' ) / 100 ; ?>;
		background-attachment: fixed ;
		background-position: center top ;
		-webkit-background-size:  <?php echo $titan->getOption( 'left_menu_bg_image_size' );?>;
		-moz-background-size: <?php echo $titan->getOption( 'left_menu_bg_image_size' );?>;
		background-size: <?php echo $titan->getOption( 'left_menu_bg_image_size' );?>;
	}
	.mob-menu-right-bg-holder { 
		<?php
			if ( $titan->getOption( 'right_menu_bg_image' ) ) {
		?>
		background: url(<?php echo wp_get_attachment_url( $titan->getOption( 'right_menu_bg_image' ) ); ?>);
		<?php
			}

		?>
		opacity: <?php echo  $titan->getOption( 'right_menu_bg_opacity' ) / 100 ; ?>;
		background-attachment: fixed ;
		background-position: center top ;
		-webkit-background-size: <?php echo $titan->getOption( 'right_menu_bg_image_size' );?>;
		-moz-background-size: <?php echo $titan->getOption( 'right_menu_bg_image_size' );?>;
		background-size:  <?php echo $titan->getOption( 'right_menu_bg_image_size' );?>;
	}

	.mobmenu_content #mobmenuleft .sub-menu a {
		color: <?php echo $titan->getOption( 'left_panel_submenu_text_color' ); ?>;
	}

	.mobmenu_content #mobmenuright .sub-menu  a{
		color: <?php echo $titan->getOption( 'right_panel_submenu_text_color' ); ?>;
	}
	.mobmenu_content #mobmenuright .sub-menu .sub-menu {
		background-color: inherit;
	}

	.mobmenu_content #mobmenuright .sub-menu  {
		background-color: <?php echo  $titan->getOption( 'right_panel_submenu_bgcolor' ); ?>;
		margin: 0;
		color: <?php echo $titan->getOption( 'right_panel_submenu_text_color' ); ?> ;
		position: initial;
		width: 100%;
	}

	#mobmenuleft li a:hover {
		color: <?php echo $titan->getOption( 'left_panel_hover_text_color' ); ?> ;

	}
	
	#mobmenuright li a , #mobmenuright li a:visited, .show-nav-right .mob-menu-copyright, .show-nav-right .mob-expand-submenu i {
		color: <?php echo $titan->getOption( 'right_panel_text_color' ); ?>;
	}

	#mobmenuright li a:hover {
		color: <?php echo $titan->getOption( 'right_panel_hover_text_color' ); ?>;
	}

	.mobmenul-container {
		top: <?php echo $titan->getOption( 'left_icon_top_margin' ); ?>px;
		margin-left: <?php echo $titan->getOption( 'left_icon_left_margin' ); ?>px;
	}

	.mobmenur-container {
		top: <?php echo $titan->getOption( 'right_icon_top_margin' ); ?>px;
		margin-right: <?php echo $titan->getOption( 'right_icon_right_margin' ); ?>px;
	}
		
	/* 2nd Level Menu Items Padding */
	.mobmenu .sub-menu li a {
		padding-left: 50px;
	}
		
	/* 3rd Level Menu Items Padding */
	.mobmenu .sub-menu .sub-menu li a {
		padding-left: 75px;
	}
	
	/* 3rd Level Left Menu Items Background color*/
	.mobmenu_content #mobmenuleft .sub-menu  .sub-menu li a {
		color: <?php echo $titan->getOption( 'left_panel_3rd_level_text_color' ); ?>;
	}

	/* 3rd Level Left Menu Items Background color on Hover*/
	.mobmenu_content #mobmenuleft .sub-menu  .sub-menu li a:hover {
		color: <?php echo $titan->getOption( 'left_panel_3rd_level_text_color_hover' ); ?>;
	}

	/* 3rd Level Left Menu Items Background color*/
	.mobmenu_content #mobmenuleft .sub-menu .sub-menu li {
		background-color: <?php echo $titan->getOption( 'left_panel_3rd_level_bgcolor' ); ?>;
	}

	/* 3rd Level Left Menu Items Background color on Hover*/
	.mobmenu_content #mobmenuleft .sub-menu .sub-menu li:hover {
		background-color: <?php echo $titan->getOption( 'left_panel_3rd_level_bgcolor_hover' ); ?>;
	}

	/* 3rd Level Right Menu Items Background color*/
	.mobmenu_content #mobmenuright .sub-menu  .sub-menu li a {
		color: <?php echo $titan->getOption( 'right_panel_3rd_level_text_color' ); ?>;		
	}

	/* 3rd Level Right Menu Items Background color*/
	.mobmenu_content #mobmenuright .sub-menu .sub-menu li {
		background-color: <?php echo $titan->getOption( 'right_panel_3rd_level_bgcolor' ); ?>;
	}

	/* 3rd Level Right Menu Items Background color on Hover*/
	.mobmenu_content #mobmenuright .sub-menu .sub-menu li:hover {
		background-color: <?php echo $titan->getOption( 'right_panel_3rd_level_bgcolor_hover' ); ?>;
	}

	/* 3rd Level Right Menu Items Background color on Hover*/
	.mobmenu_content #mobmenuright .sub-menu  .sub-menu li a:hover {
		color: <?php echo $titan->getOption( 'right_panel_3rd_level_text_color_hover' ); ?>;
	}

	<?php

	$header_margin_left     = '';
	$header_margin_right    = '';
	$header_text_position   = 'absolute';
	$border_menu_size       = $titan->getOption( 'menu_items_border_size' );
	$submenu_open_icon_font = $titan->getOption( 'submenu_open_icon_font' );

	if ( 'left' === $titan->getOption( 'header_text_align' ) ) {
			$header_margin_left = 'margin-left:' . $titan->getOption( 'header_text_left_margin' ) . 'px;';
	}

	if ( 'right' === $titan->getOption( 'header_text_align' ) ) {
		$header_margin_right = 'margin-right:' . $titan->getOption( 'header_text_right_margin' ) . 'px;';
	}

	if ( 'center' === $titan->getOption( 'header_text_align' ) ) {
		$header_text_position = 'initial';
	}

	if ( $titan->getOption( 'enabled_sticky_header' ) ) {
		$header_position = 'fixed';
	} else {
		$header_position = 'absolute';
	}

	if ( 0 < $border_menu_size ) { 
		$border_menu_color = $titan->getOption( 'menu_items_border_color' );
		$border_style      =  $border_menu_size . 'px solid ' . $border_menu_color; ?>

		.mobmenu_content li {
			border-top: <?php echo $border_style; ?>;
		}

	<?php
	}

	?>

	.mob-menu-logo-holder {
		padding-top: <?php echo $titan->getOption( 'logo_top_margin' ); ?>px;
		text-align: <?php echo $titan->getOption( 'header_text_align' ); ?>;
		<?php echo $header_margin_left; ?>
		<?php echo $header_margin_right; ?>
	}

	.mob-menu-header-holder {

		background-color: <?php echo $header_bg_color; ?>;
		height: <?php echo $total_header_height; ?>px;
		width: 100%;
		font-weight:bold;
		position:<?php echo $header_position; ?>;
		top:0px;	
		right: 0px;
		z-index: 99998;
		color:#000;
		display: block;
	}

	.mobmenu-push-wrap, body.mob-menu-slideout-over {
		padding-top: <?php echo $wrap_padding_top; ?>px;
	}
	<?php

	if ( '' !== $titan->getOption( 'left_menu_bg_gradient' ) ) {
		$left_panel_bg_color = $titan->getOption( 'left_menu_bg_gradient' );
	} else {
		$left_panel_bg_color = 'background-color:' . $titan->getOption( 'left_panel_bg_color' ) . ';';
	}

	if ( $titan->getOption( 'right_menu_bg_gradient' ) != '' ) {
		$right_panel_bg_color = $titan->getOption( 'right_menu_bg_gradient' );
	} else {
		$right_panel_bg_color = 'background-color:' . $titan->getOption( 'right_panel_bg_color' ) . ';';
	}

	?>
	.mob-menu-slideout 	.mob-menu-left-panel {
		<?php echo $left_panel_bg_color; ?>
		width: <?php echo $left_menu_width; ?>;
		-webkit-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
		-moz-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
		-ms-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
		-o-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
		transform: translateX(-<?php echo $left_menu_width_translate; ?>);
	}

	.mob-menu-slideout .mob-menu-right-panel {
		<?php echo  $right_panel_bg_color ; ?>
		width: <?php echo $right_menu_width; ?>; 
		-webkit-transform: translateX( <?php echo $right_menu_width_translate; ?> );
		-moz-transform: translateX( <?php echo $right_menu_width_translate; ?> );
		-ms-transform: translateX( <?php echo $right_menu_width_translate; ?> );
		-o-transform: translateX( <?php echo $right_menu_width_translate; ?> );
		transform: translateX( <?php echo $right_menu_width_translate; ?> );
	}

	/* Will animate the content to the right 275px revealing the hidden nav */
	.mob-menu-slideout.show-nav-left .mobmenu-push-wrap, .mob-menu-slideout.show-nav-left .mob-menu-header-holder {

		-webkit-transform: translate(<?php echo $left_menu_width_translate; ?>, 0);
		-moz-transform: translate(<?php echo $left_menu_width_translate; ?>, 0);
		-ms-transform: translate(<?php echo $left_menu_width_translate; ?>, 0);
		-o-transform: translate(<?php echo $left_menu_width_translate; ?>, 0);
		transform: translate(<?php echo $left_menu_width_translate; ?>, 0);
		-webkit-transform: translate3d(<?php echo $left_menu_width; ?>, 0, 0);
		-moz-transform: translate3d(<?php echo $left_menu_width; ?>, 0, 0);
		-ms-transform: translate3d(<?php echo $left_menu_width; ?>, 0, 0);
		-o-transform: translate3d(<?php echo $left_menu_width; ?>, 0, 0);
		transform: translate3d(<?php echo $left_menu_width; ?>, 0, 0);
	}

	.mob-menu-slideout.show-nav-right .mobmenu-push-wrap , .mob-menu-slideout.show-nav-right .mob-menu-header-holder {

		-webkit-transform: translate(-<?php echo $right_menu_width_translate; ?>, 0);
		-moz-transform: translate(-<?php echo $right_menu_width_translate; ?>, 0);
		-ms-transform: translate(-<?php echo $right_menu_width_translate; ?>, 0);
		-o-transform: translate(-<?php echo $right_menu_width_translate; ?>, 0);
		transform: translate(-<?php echo $right_menu_width_translate; ?>, 0);

		-webkit-transform: translate3d(-<?php echo  $right_menu_width; ?>, 0, 0);
		-moz-transform: translate3d(-<?php echo  $right_menu_width; ?>, 0, 0);
		-ms-transform: translate3d(-<?php echo  $right_menu_width; ?>, 0, 0);
		-o-transform: translate3d(-<?php echo  $right_menu_width; ?>, 0, 0);
		transform: translate3d(-<?php echo  $right_menu_width; ?>, 0, 0);
	}

	/* Mobmenu Slide Over */
	.mobmenu-overlay {
		opacity: 0;
	}

	.mob-menu-slideout-top .mobmenu-overlay, .mob-menu-slideout .mob-menu-right-panel .mob-cancel-button, .mob-menu-slideout .mob-menu-left-panel .mob-cancel-button {
		display: none!important;
	}

	.show-nav-left .mobmenu-overlay, .show-nav-right .mobmenu-overlay {
		width: 100%;
		height: 100%;
		background: <?php echo  $titan->getOption( 'overlay_bg_color' ); ?>;
		z-index: 99999;
		position: absolute;
		left: 0;
		top: 0;
		opacity: 1;
		-webkit-transition: .5s ease;
		-moz-transition: .5s ease;
		-ms-transition: .5s ease;
		-o-transition: .5s ease;
		transition: .5s ease;
		position: fixed;
		cursor: pointer;
	}

	.mob-menu-slideout-over .mob-menu-left-panel {
		display: block!important;
		<?php echo $left_panel_bg_color; ?>
		width: <?php echo $left_menu_width; ?>;
		-webkit-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
		-moz-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
		-ms-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
		-o-transform: translateX(-<?php echo  $left_menu_width_translate; ?>);
		transform: translateX(-<?php echo $left_menu_width_translate; ?>);
		-webkit-transition: -webkit-transform .5s;
		-moz-transition: -moz-transform .5s;
		-ms-transition: -ms-transform .5s;
		-o-transition: -o-transform .5s;
		transition: transform .5s;
	}

	.mob-menu-slideout-over .mob-menu-right-panel {
		display: block!important;
		<?php echo $right_panel_bg_color; ?>
		width:  <?php echo  $right_menu_width; ?>;
		-webkit-transform: translateX(<?php echo $right_menu_width_translate; ?>);
		-moz-transform: translateX(<?php echo $right_menu_width_translate; ?>);
		-ms-transform: translateX(<?php echo $right_menu_width_translate; ?>);
		-o-transform: translateX(<?php echo $right_menu_width_translate; ?>);
		transform: translateX(<?php echo $right_menu_width_translate; ?>);
		-webkit-transition: -webkit-transform .5s;
		-moz-transition: -moz-transform .5s;
		-ms-transition: -ms-transform .5s;
		-o-transition: -o-transform .5s;
		transition: transform .5s;
	}

	.mob-menu-slideout-over.show-nav-left .mob-menu-left-panel {
		display: block!important;
		<?php echo $left_panel_bg_color; ?>
		width:  <?php echo $left_menu_width; ?>;
		-webkit-transform: translateX(0);
		-moz-transform: translateX(0);
		-ms-transform: translateX(0);
		-o-transform: translateX(0);
		transform: translateX(0);
		-webkit-transition: -webkit-transform .5s;
		-moz-transition: -moz-transform .5s;
		-ms-transition: -ms-transform .5s;
		-o-transition: -o-transform .5s;
		transition: transform .5s;
	}

	.show-nav-right.mob-menu-slideout-over .mob-menu-right-panel {
		display: block!important;
		<?php echo $right_panel_bg_color; ?>
		width:  <?php echo $right_menu_width; ?>;
		-webkit-transform: translateX( 0 );
		-moz-transform: translateX( 0 );
		-ms-transform: translateX( 0 );
		-o-transform: translateX(0 );
		transform: translateX( 0 );
	}

	/* Hides everything pushed outside of it */
	.mob-menu-slideout .mob-menu-left-panel, .mob-menu-slideout-over .mob-menu-left-panel  {
		position: fixed;
		top: 0;
		height: 100%;
		z-index: 300000;
		overflow-y: auto;   
		overflow-x: hidden;
		opacity: 1;
	}

	.mob-menu-slideout .mob-menu-right-panel, .mob-menu-slideout-over .mob-menu-right-panel {
		position: fixed;
		top: 0;
		right: 0;
		height: 100%;
		z-index: 300000;
		overflow-y: auto;   
		overflow-x: hidden;
		opacity: 1;

	}   
	
	/* End of Mobmenu Slide Over */

	.mobmenu .headertext {
		color: <?php echo $titan->getOption( 'header_text_color' );?>;
	}

	.headertext span { 
		position: <?php echo $header_text_position; ?>;
		line-height: <?php echo $total_header_height; ?>px;
	}

			
	/* Adds a transition and the resting translate state */
	.mob-menu-slideout .mobmenu-push-wrap, .mob-menu-slideout .mob-menu-header-holder {
		
		-webkit-transition: -webkit-transform .5s;
		-moz-transition: -moz-transform .5s;
		-ms-transition: -ms-transform .5s;
		-o-transition: -o-transform .5s;
		transition: transform .5s;
		-webkit-transform: translate(0, 0);
		-moz-transform: translate(0, 0);
		-ms-transform: translate(0, 0);
		-o-transform: translate(0, 0);
		transform: translate(0, 0);
		-webkit-transform: translate3d(0, 0, 0);
		-moz-transform: translate3d(0, 0, 0);
		-ms-transform: translate3d(0, 0, 0);
		-o-transform: translate3d(0, 0, 0);
		transform: translate3d(0, 0, 0);

	}

	/* Mobile Menu Frontend CSS Style*/
	html, body {
		overflow-x: hidden;
	}

	.hidden-overflow {
		overflow: hidden!important;
	}

	/* Hides everything pushed outside of it */
	.mob-menu-slideout .mob-menu-left-panel {
		position: fixed;
		top: 0;
		height: 100%;
		z-index: 300000;
		overflow-y: auto;   
		overflow-x: hidden;
		opacity: 1;
		-webkit-transition: -webkit-transform .5s;
		-moz-transition: -moz-transform .5s;
		-ms-transition: -ms-transform .5s;
		-o-transition: -o-transform .5s;
		transition: transform .5s;
	}   

	.mob-menu-slideout.show-nav-left .mob-menu-left-panel {
		transition: transform .5s;
		-webkit-transform: translateX(0);
		-moz-transform: translateX(0);
		-ms-transform: translateX(0);
		-o-transform: translateX(0);
		transform: translateX(0);
	}

	body.admin-bar .mobmenu {
		top: 32px;
	}

	@media screen and ( max-width: 782px ){
		body.admin-bar .mobmenu {
			top: 46px;   
		}
	}

	.mob-menu-slideout .mob-menu-right-panel {
		position: fixed;
		top: 0;
		right: 0;
		height: 100%;
		z-index: 300000;
		overflow-y: auto;   
		overflow-x: hidden;
		opacity: 1;
		-webkit-transition: -webkit-transform .5s;
		-moz-transition: -moz-transform .5s;
		-ms-transition: -ms-transform .5s;
		-o-transition: -o-transform .5s;
		transition: transform .5s;
	}   

	.mob-menu-slideout.show-nav-right .mob-menu-right-panel {
		transition: transform .5s;
		-webkit-transform: translateX(0);
		-moz-transform: translateX(0);
		-ms-transform: translateX(0);
		-o-transform: translateX(0);
		transform: translateX(0);
	}

	.show-nav-left .mobmenu-push-wrap {
		height: 100%;
	}

	/* Will animate the content to the right 275px revealing the hidden nav */
	.mob-menu-slideout.show-nav-left .mobmenu-push-wrap, .show-nav-left .mob-menu-header-holder {
		-webkit-transition: -webkit-transform .5s;
		-moz-transition: -moz-transform .5s;
		-ms-transition: -ms-transform .5s;
		-o-transition: -o-transform .5s;
		transition: transform .5s;
	}

	.show-nav-right .mobmenu-push-wrap {
		height: 100%;
	}

	/* Will animate the content to the right 275px revealing the hidden nav */
	.mob-menu-slideout.show-nav-right .mobmenu-push-wrap , .mob-menu-slideout.show-nav-right .mob-menu-header-holder{  
		-webkit-transition: -webkit-transform .5s;
		-moz-transition: -moz-transform .5s;
		-ms-transition: -ms-transform .5s;
		-o-transition: -o-transform .5s;
		transition: transform .5s;
	}

	.widget img {
		max-width: 100%; 
	}

	#mobmenuleft, #mobmenuright {
		margin: 0;
		padding: 0;
	}

	#mobmenuleft li > ul {
		display:none;
		left: 15px;
	}
	
	.mob-expand-submenu {
		position: relative;
		right: 0px;
		float: right;
		margin-top: -50px;
	}

	.mob-expand-submenu i {
		padding: 12px;
	}

	#mobmenuright  li > ul {
		display:none;
		left: 15px;
	}

	.rightmbottom, .rightmtop {
		padding-left: 10px;
		padding-right: 10px;
	}

	.mobmenu_content {
		z-index: 1;
		height: 100%;
		overflow: auto;
	}
	
	.mobmenu_content li a {
		display: block;
		letter-spacing: 1px;
		padding: 10px 20px;
		text-decoration: none;
	}

	.mobmenu_content li {
		list-style: none;
	}
	.mob-menu-left-panel li, .leftmbottom, .leftmtop{
		padding-left: <?php echo $titan->getOption( 'left_menu_content_padding' ); ?>%;
		padding-right: <?php echo $titan->getOption( 'left_menu_content_padding' ); ?>%;
	}

	.mob-menu-right-panel li, .rightmbottom, .rightmtop{
		padding-left: <?php echo $titan->getOption( 'right_menu_content_padding' ); ?>%;
		padding-right: <?php echo $titan->getOption( 'right_menu_content_padding' ); ?>%;
	}

	.mob-menu-slideout .mob_menu_left_panel_anim {
		-webkit-transition: all .30s ease-in-out !important;
		transition: all .30s ease-in-out !important;
		transform: translate(0px) !important;
		-ms-transform: translate(0px) !important;
		-webkit-transform: translate(0px) !important;
	}

	.mob-menu-slideout .mob_menu_right_panel_anim {
		-webkit-transition: all .30s ease-in-out !important;
		transition: all .30s ease-in-out !important;
		transform: translate(0px) !important;
		-ms-transform: translate(0px) !important;
		-webkit-transform: translate(0px) !important;
	}

	.mobmenul-container {
		position: absolute;
	}

	.mobmenur-container {
		position: absolute;
		right: 0px; 
	} 

	.mob-menu-slideout .mob_menu_left_panel {
		width: 230px;
		height: 100%;
		position: fixed;
		top: 0px;
		left: 0px;
		z-index: 99999999;
		transform: translate(-230px);
		-ms-transform: translate(-230px);
		-webkit-transform: translate(-230px);
		transition: all .30s ease-in-out !important;
		-webkit-transition: all .30s ease-in-out !important;
		overflow:hidden;
	}  

	.leftmbottom h2 {
		font-weight: bold;
		background-color: transparent;
		color: inherit;
	}
	
	.show-nav-right .mobmenur-container img, .show-nav-left .mobmenul-container img,  .mobmenu .mob-cancel-button, .show-nav-left .mobmenu .mob-menu-icon, .show-nav-right .mobmenu .mob-menu-icon, .mob-menu-slideout-over.show-nav-left .mobmenur-container, .mob-menu-slideout-over.show-nav-right .mobmenul-container  {
		display:none;
	}
	
	.show-nav-left .mobmenu .mob-cancel-button,  .mobmenu .mob-menu-icon, .show-nav-right .mobmenu .mob-cancel-button {
		display:block;
	}

	.mobmenul-container i {
		line-height: <?php echo $titan->getOption( 'left_icon_font_size' ); ?>px;
		font-size: <?php echo $titan->getOption( 'left_icon_font_size' ); ?>px;
		float: left;
	}
	.left-menu-icon-text {
		float: left;
		line-height: <?php echo $titan->getOption( 'left_icon_font_size' ); ?>px;
		color: <?php echo $titan->getOption( 'header_text_after_icon' ); ?>;
	}

	.right-menu-icon-text {
		float: right;
		line-height: <?php echo $titan->getOption( 'right_icon_font_size' ); ?>px;
		color: <?php echo $titan->getOption( 'header_text_before_icon' ); ?>;
	}
	
	.mobmenur-container i {
		line-height: <?php echo $titan->getOption( 'right_icon_font_size' ); ?>px;
		font-size: <?php echo $titan->getOption( 'right_icon_font_size' ); ?>px;
		float: right;
	}
	
	.mobmenu_content .widget {
		padding-bottom: 0px;
		padding: 20px;
	}
	
	.mobmenu input[type="text"]:focus, .mobmenu input[type="email"]:focus, .mobmenu textarea:focus, .mobmenu input[type="tel"]:focus, .mobmenu input[type="number"]:focus {
		border-color: rgba(0, 0, 0, 0)!important;
	}	

	.mob-expand-submenu i {
		padding: 12px;
		top: 10px;
		position: relative;
		font-weight: 600;
		cursor: pointer;
		font-size: <?php echo $titan->getOption( 'submenu_icon_font_size' ); ?>px;
	}

	<?php echo  $default_elements; ?> {
		display: none!important;
	}

	.mob-menu-left-bg-holder, .mob-menu-right-bg-holder {
		width: 100%;
		height: 100%;
		position: absolute;
		z-index: -50;
		background-repeat: no-repeat;
		top: 0;
		left: 0;
	}
	
	.mobmenu_content .sub-menu {
		display: none;
	}

	.mob-standard-logo {
		display: inline-block;
		<?php echo $logo_height; ?>
	}

}
.mob-standard-logo {
	display: inline-block;
}
.mobmenu-push-wrap {
	height:100%;
}
.no-menu-assigned {
	font-size: 12px;
	padding-left: 10px;
	margin-top: 20px;
	position: absolute;
}

<?php
