<?php
class WPEditorAdmin {
  
  public static function build_admin_menu() {
    $page_roles = WPEditorSetting::get_value( 'admin_page_roles' );
    $page_roles = unserialize( $page_roles);

    //$settings = add_submenu_page( 'options-general.php', __( 'WP Editor', 'wp-editor' ), __( 'WP Editor', 'wp-editor' ), $page_roles['settings'], 'wpeditor_settings', array( 'WPEditorAdmin', 'add_settings_page' ) );

    //add_action( 'admin_print_styles-' . $settings, array( 'WPEditorAdmin', 'settings_styles_and_scripts' ) );

    if ( WPEditorSetting::get_value( 'hide_wpeditor_menu' ) ) {
      $settings = add_submenu_page( 'options-general.php', __( 'WP Editor Settings', 'wp-editor' ), __( 'WP Editor', 'wp-editor' ), $page_roles['settings'], 'wpeditor_admin', array( 'WPEditorAdmin', 'OLD_add_settings_page' ) );
    }
    else {
      $icon = WPEDITOR_URL . '/images/wpeditor_logo_16.png';
      $settings = add_menu_page( __( 'WP Editor Settings', 'wp-editor' ), __( 'WP Editor', 'wp-editor' ), $page_roles['settings'], 'wpeditor_admin', array( 'WPEditorAdmin', 'OLD_add_settings_page' ), $icon );
    }

    add_action( 'admin_print_styles-' . $settings, array( 'WPEditorAdmin', 'default_stylesheet_and_script' ) );
  }
  
  public static function add_plugins_page() {
    global $wpeditor_plugin;
    
    $page_title = __( 'Plugin Editor', 'wp-editor' );
    $menu_title = __( 'Plugin Editor', 'wp-editor' );
    $capability = 'edit_plugins';
    $menu_slug = 'wpeditor_plugin';
    $wpeditor_plugin = add_plugins_page( $page_title, $menu_title, $capability, $menu_slug, array( 'WPEditorPlugins', 'add_plugins_page' ) );
    add_action( "load-$wpeditor_plugin", array( 'WPEditorPlugins', 'plugins_help_tab' ) );
    if ( isset( $_GET['page'] ) && $_GET['page'] == 'wpeditor_plugin' ) {
      add_action( 'admin_print_styles', array( 'WPEditorAdmin', 'editor_stylesheet_and_scripts' ) );
    }
  }
  
  public static function add_themes_page() {
    global $wpeditor_themes;
    
    $page_title = __( 'Theme Editor', 'wp-editor' );
    $menu_title = __( 'Theme Editor', 'wp-editor' );
    $capability = 'edit_themes';
    $menu_slug = 'wpeditor_themes';
    $wpeditor_themes = add_theme_page( $page_title, $menu_title, $capability, $menu_slug, array( 'WPEditorThemes', 'add_themes_page' ) );
    
    add_action( "load-$wpeditor_themes", array( 'WPEditorThemes', 'themes_help_tab' ) );
    if ( isset( $_GET['page']) && $_GET['page'] == 'wpeditor_themes' ) {
      add_action( 'admin_print_styles', array( 'WPEditorAdmin', 'editor_stylesheet_and_scripts' ) );
    }
  }
  
  public static function OLD_add_settings_page() {
    $view = WPEditor::get_view( 'views/OLDsettings.php' );
    echo $view;
  }

  public static function add_settings_page() {
    $view = WPEditor::get_view( 'views/settings.php' );
    echo $view;
  }
  
  public static function editor_stylesheet_and_scripts() {
    wp_enqueue_style( 'wpeditor' );
    wp_enqueue_script( 'wpeditor' );
    wp_enqueue_style( 'nivo-lightbox' );
    wp_enqueue_style( 'nivo-lightbox-default' );
    wp_enqueue_script( 'nivo-lightbox' );
    wp_enqueue_style( 'codemirror' );
    wp_enqueue_style( 'codemirror_dialog' );
    wp_enqueue_style( 'codemirror_fullscreen' );
    wp_enqueue_style( 'codemirror_themes' );
    wp_enqueue_style( 'chosen' );

    if ( ! wp_script_is( 'codemirror', 'enqueued' ) ) {
      wp_enqueue_script( 'codemirror' );
    }
    wp_enqueue_script( 'codemirror_mustache' );
    wp_enqueue_script( 'codemirror_fullscreen' );
    wp_enqueue_script( 'codemirror_php' );
    wp_enqueue_script( 'codemirror_javascript' );
    wp_enqueue_script( 'codemirror_css' );
    wp_enqueue_script( 'codemirror_xml' );
    wp_enqueue_script( 'codemirror_clike' );
    wp_enqueue_script( 'codemirror_dialog' );
    wp_enqueue_script( 'codemirror_search' );
    wp_enqueue_script( 'codemirror_searchcursor' );
    wp_enqueue_script( 'attrchange' );
    wp_enqueue_script( 'chosen' );
  }
  
  public static function default_stylesheet_and_script() {
    wp_enqueue_style( 'wpeditor' );
    wp_enqueue_script( 'wpeditor' );
  }

  public static function settings_styles_and_scripts() {
    wp_enqueue_style( 'chosen' );
    wp_enqueue_script( 'chosen' );
  }
  
  public static function remove_default_editor_menus() {
    // Remove default plugin editor
    if ( WPEditorSetting::get_value( 'hide_default_plugin_editor' ) == 1 ) {
      global $submenu;
      unset( $submenu['plugins.php'][15] );
    }
    if ( WPEditorSetting::get_value( 'hide_default_theme_editor' ) == 1 ) {
      // Remove default themes editor
      remove_action( 'admin_menu', '_add_themes_utility_last', 101 );
    }
  }
  
}