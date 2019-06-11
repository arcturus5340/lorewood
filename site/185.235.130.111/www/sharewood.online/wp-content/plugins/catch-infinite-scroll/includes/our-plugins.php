<?php 
/* Adds Catch Plugins tab in Add Plugin page to show all plugins by Catch Plugins in wordpress.org */
if( ! function_exists( 'add_our_plugins_tab' ) ) {
	function add_our_plugins_tab($tabs) {
		// Add our filter here
		$tabs['catchplugins'] = _x( 'Catch Plugins', 'Plugin Installer' );

	    return $tabs;
	}
	add_filter( 'install_plugins_tabs', 'add_our_plugins_tab', 1 );
}

if( ! function_exists( 'catchplugins' ) ) {
	function catchplugins() {
		/* From CORE Start */
		global $paged, $tab;
		wp_reset_vars( array( 'tab' ) );

		$defined_class = new WP_Plugin_Install_List_Table();
		$paged = $defined_class->get_pagenum();

		$per_page = 30;
		//$installed_plugins = catch_get_installed_plugins();

		$args = array(
			'page' => $paged,
			'per_page' => $per_page,
			'fields' => array(
				'last_updated' => true,
				'icons' => true,
				'active_installs' => true
			),
			// Send the locale and installed plugin slugs to the API so it can provide context-sensitive results.
			'locale' => get_user_locale(),
			//'installed_plugins' => array_keys( $installed_plugins ),
		);
		/* From CORE End */

		// Add author filter for our plugins
		$args['author'] = 'catchplugins';

		return $args;
	}
	add_filter( "install_plugins_table_api_args_catchplugins", 'catchplugins', 1 );
}

if( ! function_exists( 'cathcplugins_plugins_table' ) ) {
add_action( 'install_plugins_catchplugins', 'cathcplugins_plugins_table' );
	function cathcplugins_plugins_table() {
		global $wp_list_table;
		printf(
			'<p class="catch-plugins-list">' . __( 'You can use any of our free plugins or premium plugins from <a href="%s" target="_blank">Catch Plugins</a>' ) . '.</p>',
			'https://catchplugins.com/'
		);
		?>
		<form id="plugin-filter" method="post">
			<?php $wp_list_table->display(); ?>
		</form>
		<?php
	}
}