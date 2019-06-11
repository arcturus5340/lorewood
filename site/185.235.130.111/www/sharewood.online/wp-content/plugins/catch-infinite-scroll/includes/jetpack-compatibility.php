<?php 

// First, make sure Jetpack doesn't concatenate all its CSS
add_filter( 'jetpack_implode_frontend_css', '__return_false' );

// Then, remove Jetpack's CSS file for Infinite Scroll module if Jetpack is inactive
function catch_inifite_scroll_remove_jetpack_styles() {
	if( class_exists( 'Jetpack' ) ) {
		wp_deregister_style( 'the-neverending-homepage' ); // Infinite Scroll
	}
}
add_action( 'wp_footer', 'catch_inifite_scroll_remove_jetpack_styles', 100 );