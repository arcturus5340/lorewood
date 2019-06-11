<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class WP_Mobile_Menu_Walker_Nav_Menu extends Walker_Nav_Menu {
	public $menu_position;
	public function __construct( $myarg ) {
		$this->menu_position = $myarg;
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		global  $mm_fs ;
		$titan = TitanFramework::getInstance( 'mobmenu' );
		$icon_class = '';
		$mobile_icon = '';
		$indent = ( $depth ? str_repeat( "\t", $depth ) : '' );
		$class_names = '';
		$value = '';
		$classes = ( empty( $item->classes ) ? array() : (array) $item->classes );
		$classes[] = 'menu-item-' . $item->ID;
		$class_names = join( ' ', apply_filters(
			'nav_menu_css_class',
			array_filter( $classes ),
			$item,
			$args
		) );

		$class_names = ( $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '' );
		$id = apply_filters(
			'nav_menu_item_id',
			'menu-item-' . $item->ID,
			$item,
			$args
		);

		$id = ( $id ? ' id="' . esc_attr( $id ) . '"' : '' );
		$output .= $indent . '';
		$attributes = ( ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '' );
		$attributes .= ( ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '' );
		$attributes .= ( ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '' );
		$attributes .= ( ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '' );
		$item_output = $args->before;
		$item_output .= '<li ' . $class_names . '><a' . $attributes . ' class="' . $icon_class . '">' . $mobile_icon;
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		$output .= apply_filters(
			'walker_nav_menu_start_el',
			$item_output,
			$item,
			$depth,
			$args
		);
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		// Copy all the end_el code from source, and modify.
		$output .= '</li>';
	}
}
