<?php
if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit();
}

define( 'WPEDITOR_PATH', plugin_dir_path( __FILE__ ) ); // e.g. /var/www/example.com/wordpress/wp-content/plugins/wpeditor
require_once( WPEDITOR_PATH . 'classes/WPEditor.php' );
require_once( WPEDITOR_PATH . 'classes/WPEditorSetting.php' );

global $wpdb;
$prefix = WPEditor::get_table_prefix();
$sqlFile = WPEDITOR_PATH . 'sql/uninstall.sql';
$sql = str_replace( '[prefix]', $prefix, file_get_contents( $sqlFile ) );
$queries = explode( ";\n", $sql );
foreach ( $queries as $sql ) {
  if ( strlen( $sql ) > 5 ) {
    $wpdb->query( $sql );
  }
}