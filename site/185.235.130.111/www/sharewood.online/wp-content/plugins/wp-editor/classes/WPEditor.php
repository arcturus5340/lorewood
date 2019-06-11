<?php
class WPEditor {
  
  public function install() {
    
    global $wpdb;
    $prefix = $this->get_table_prefix();
    $sqlFile = WPEDITOR_PATH . 'sql/database.sql';
    $sql = str_replace( '[prefix]', $prefix, file_get_contents( $sqlFile ) );
    $queries = explode( ";\n", $sql );
    $wpdb->hide_errors();
    foreach ( $queries as $sql ) {
      if ( strlen( $sql ) > 5 ) {
        $wpdb->query( $sql );
      }
    }
    
    // Set the version number for this version of WPEditor
    require_once( WPEDITOR_PATH . 'classes/WPEditorSetting.php' );
    WPEditorSetting::set_value( 'version', WPEDITOR_VERSION_NUMBER );

    if ( ! WPEditorSetting::get_value( 'upgrade' ) ) {
      $this->first_install();
    }

  }
  
  public function first_install() {
    
    // Set the database to upgrade instead of first time install
    WPEditorSetting::set_value( 'upgrade', 1 );
    
    // Check if the post editor has been enabled and enable if not
    if ( ! WPEditorSetting::get_value( 'enable_post_editor' ) ) {
      WPEditorSetting::set_value( 'enable_post_editor', 1 );
    }
    
    // Check if the plugin and theme editors have been hidden before and hide them if not
    if ( ! WPEditorSetting::get_value( 'hide_default_plugin_editor' ) ) {
      WPEditorSetting::set_value( 'hide_default_plugin_editor', 1 );
    }
    if ( ! WPEditorSetting::get_value( 'hide_default_theme_editor' ) ) {
      WPEditorSetting::set_value( 'hide_default_theme_editor', 1 );
    }
    
    // Check if the edit link for plugins has been hidden before and hide if not
    if ( ! WPEditorSetting::get_value( 'replace_plugin_edit_links' ) ) {
      WPEditorSetting::set_value( 'replace_plugin_edit_links', 1 );
    }
    
    // Check if the plugin line numbers have been disabled and enable if not
    if ( ! WPEditorSetting::get_value( 'enable_plugin_line_numbers' ) ) {
      WPEditorSetting::set_value( 'enable_plugin_line_numbers', 1 );
    }
    
    // Check if the theme line numbers have been disabled and enable if not
    if ( ! WPEditorSetting::get_value( 'enable_theme_line_numbers' ) ) {
      WPEditorSetting::set_value( 'enable_theme_line_numbers', 1 );
    }
    
    // Check if the post line numbers have been disabled and enable if not
    if ( ! WPEditorSetting::get_value( 'enable_post_line_numbers' ) ) {
      WPEditorSetting::set_value( 'enable_post_line_numbers', 1 );
    }
    
    // Check if plugin line wrapping has been disabled and enable if not
    if ( ! WPEditorSetting::get_value( 'enable_plugin_line_wrapping' ) ) {
      WPEditorSetting::set_value( 'enable_plugin_line_wrapping', 1 );
    }
    
    // Check if theme line wrapping has been disabled and enable if not
    if ( ! WPEditorSetting::get_value( 'enable_theme_line_wrapping' ) ) {
      WPEditorSetting::set_value( 'enable_theme_line_wrapping', 1 );
    }
    
    // Check if post line wrapping has been disabled and enable if not
    if ( ! WPEditorSetting::get_value( 'enable_post_line_wrapping' ) ) {
      WPEditorSetting::set_value( 'enable_post_line_wrapping', 1 );
    }
    
    // Check if plugin active line highlighting has been disabled and enable if not
    if ( ! WPEditorSetting::get_value( 'enable_plugin_active_line' ) ) {
      WPEditorSetting::set_value( 'enable_plugin_active_line', 1 );
    }
    
    // Check if theme active line highlighting has been disabled and enable if not
    if ( ! WPEditorSetting::get_value( 'enable_theme_active_line' ) ) {
      WPEditorSetting::set_value( 'enable_theme_active_line', 1 );
    }
    
    // Check if post active line highlighting has been disabled and enable if not
    if ( ! WPEditorSetting::get_value( 'enable_post_active_line' ) ) {
      WPEditorSetting::set_value( 'enable_post_active_line', 1 );
    }
    
    // Check if the default allowed extensions for the plugin editor have been set and set if not
    if ( ! WPEditorSetting::get_value( 'plugin_editor_allowed_extensions' ) ) {
      WPEditorSetting::set_value( 'plugin_editor_allowed_extensions', 'php~js~css~txt~htm~html~jpg~jpeg~png~gif~sql~po~less~xml' );
    }
    
    // Check if the default allowed extensions for the theme editor have been set and set if not
    if ( ! WPEditorSetting::get_value( 'theme_editor_allowed_extensions' ) ) {
      WPEditorSetting::set_value( 'theme_editor_allowed_extensions', 'php~js~css~txt~htm~html~jpg~jpeg~png~gif~sql~po~less~xml' );
    }
    
    // Check if the upload plugin file option has been set and set if not
    if ( ! WPEditorSetting::get_value( 'plugin_file_upload' ) ) {
      WPEditorSetting::set_value( 'plugin_file_upload', 1 );
    }
    
    // Check if the upload theme file option has been set and set if not
    if ( ! WPEditorSetting::get_value( 'theme_file_upload' ) ) {
      WPEditorSetting::set_value( 'theme_file_upload', 1 );
    }
    
    // Check if the plugin indent unit option has been set and set if not
    if ( ! WPEditorSetting::get_value( 'plugin_indent_unit' ) ) {
      WPEditorSetting::set_value( 'plugin_indent_unit', 2 );
    }
    
    // Check if the theme indent unit option has been set and set if not
    if ( ! WPEditorSetting::get_value( 'theme_indent_unit' ) ) {
      WPEditorSetting::set_value( 'theme_indent_unit', 2 );
    }
    
    // Check if the post indent unit option has been set and set if not
    if ( ! WPEditorSetting::get_value( 'post_indent_unit' ) ) {
      WPEditorSetting::set_value( 'post_indent_unit', 2 );
    }
    
  }
  
  public function init() {
    // Load all additional required classes
    $this->loadCoreModels();
    
    // Verify that upgrade has been run
    if (IS_ADMIN ) {
      if ( version_compare( WPEDITOR_VERSION_NUMBER, WPEditorSetting::get_value( 'version' ) ) ) {
        $this->install();
      }
    }
    
    // Define debugging and testing info
    $wpeditor_logging = WPEditorSetting::get_value( 'wpeditor_logging' ) ? true : false;
    define( 'WPEDITOR_DEBUG', $wpeditor_logging );
    
    $default_wpeditor_roles = array(
      'settings' => 'manage_options',
      'theme-editor' => 'edit_themes',
      'plugin-editor' => 'edit_plugins'
    );
    // Set default admin page roles if there isn't any
    $wpeditor_roles = WPEditorSetting::get_value( 'admin_page_roles' );
    if ( empty( $wpeditor_roles ) ) {
      WPEditorSetting::set_value( 'admin_page_roles', serialize( $default_wpeditor_roles ) );
    }
    // Ensure that all admin page roles have been set.
    else {
      $update_roles = false;
      $wpeditor_roles = unserialize( $wpeditor_roles );
      foreach ( $default_wpeditor_roles as $key => $value ) {
        if ( ! array_key_exists( $key, $wpeditor_roles ) ) {
          $wpeditor_roles[ $key ] = $value;
          $update_roles = true;
        }
      }
      if ( $update_roles ) {
        WPEditorSetting::set_value( 'admin_page_roles', serialize( $wpeditor_roles ) );
      }
      $wpeditor_roles = serialize( $wpeditor_roles );
    }
    
    if ( IS_ADMIN ) {
      // Load default stylesheet
      add_action( 'admin_init', array( $this, 'register_default_stylesheet' ) );
      // Load default script
      add_action( 'admin_init', array( $this, 'register_default_script' ) );
      // Register the default settings
      add_action( 'admin_init', array( 'WPEditorSetting', 'register_settings' ) );
      
      // Remove default editor submenus
      add_action( 'admin_menu', array( 'WPEditorAdmin', 'remove_default_editor_menus' ) );
      // Add WP Editor Settings Page
      add_action( 'admin_menu', array( 'WPEditorAdmin', 'build_admin_menu' ) );

      // Add Plugin Editor Page
      add_action( 'admin_menu', array( 'WPEditorAdmin', 'add_plugins_page' ) );
      // Add Theme Editor Page
      add_action( 'admin_menu', array( 'WPEditorAdmin', 'add_themes_page' ) );
      
      // Ajax request to save settings
      add_action( 'wp_ajax_save_wpeditor_settings', array( 'WPEditorAjax', 'save_settings' ) );
      
      // Ajax request to save files
      add_action( 'wp_ajax_save_files', array( 'WPEditorAjax', 'save_file' ) );
      
      // Ajax request to upload files
      add_action( 'wp_ajax_upload_files', array( 'WPEditorAjax', 'upload_file' ) );
      
      // Ajax request to retrieve files and folders
      add_action( 'wp_ajax_ajax_folders', array( 'WPEditorAjax', 'ajax_folders' ) );
      
      // Replace default plugin edit links
      add_filter( 'plugin_action_links', array( $this, 'replace_plugin_edit_links' ), 9, 1 );
      
      add_filter( 'the_editor', array( 'WPEditorPosts', 'add_posts_jquery' ) );
      
      if (!current_user_can( 'editor' ) && !current_user_can( 'administrator' ) ) {
        global $pagenow;
        if ( $pagenow == 'index.php' ) {
          add_filter( 'admin_footer', array( 'WPEditorPosts', 'add_posts_jquery' ) );
        }
      }
      
    }
  }
  
  public function loadCoreModels() {
    require_once( WPEDITOR_PATH . 'classes/WPEditorAdmin.php' );
    require_once( WPEDITOR_PATH . 'classes/WPEditorAjax.php' );
    require_once( WPEDITOR_PATH . 'classes/WPEditorBrowser.php' );
    require_once( WPEDITOR_PATH . 'classes/WPEditorException.php' );
    require_once( WPEDITOR_PATH . 'classes/WPEditorLog.php' );
    require_once( WPEDITOR_PATH . 'classes/WPEditorPlugins.php' );
    require_once( WPEDITOR_PATH . 'classes/WPEditorPosts.php' );
    require_once( WPEDITOR_PATH . 'classes/WPEditorSetting.php' );
    require_once( WPEDITOR_PATH . 'classes/WPEditorThemes.php' );
  }
  
  public function register_default_stylesheet() {
    wp_register_style( 'wpeditor', WPEDITOR_URL . '/wpeditor.css', false, WPEDITOR_VERSION_NUMBER );
    wp_register_style( 'nivo-lightbox', WPEDITOR_URL . '/extensions/nivo-lightbox/css/nivo-lightbox.css', false, WPEDITOR_VERSION_NUMBER );
    wp_register_style( 'nivo-lightbox-default', WPEDITOR_URL . '/extensions/nivo-lightbox/themes/default/default.css', array( 'nivo-lightbox' ), WPEDITOR_VERSION_NUMBER );
    wp_register_style( 'codemirror', WPEDITOR_URL . '/extensions/codemirror/css/codemirror.css', false, WPEDITOR_VERSION_NUMBER );
    wp_register_style( 'codemirror_dialog', WPEDITOR_URL . '/extensions/codemirror/css/dialog.css', false, WPEDITOR_VERSION_NUMBER );
    wp_register_style( 'codemirror_fullscreen', WPEDITOR_URL . '/extensions/codemirror/css/fullscreen.css', false, WPEDITOR_VERSION_NUMBER );
    wp_register_style( 'codemirror_themes', WPEDITOR_URL . '/extensions/codemirror/themes/themes.css', false, WPEDITOR_VERSION_NUMBER );
    wp_register_style( 'chosen', WPEDITOR_URL . '/extensions/chosen/css/chosen.min.css', false, WPEDITOR_VERSION_NUMBER );
  }
  
  public function register_default_script() {
    wp_deregister_script( 'quicktags' );
    wp_register_script( 'quicktags', WPEDITOR_URL . '/js/quicktags.js', false, WPEDITOR_VERSION_NUMBER, true );
    wp_localize_script( 'quicktags', 'quicktagsL10n', array(
      'closeAllOpenTags'    => __( 'Close all open tags', 'wp-editor' ),
      'closeTags'       => __( 'close tags', 'wp-editor' ),
      'enterURL'        => __( 'Enter the URL', 'wp-editor' ),
      'enterImageURL'     => __( 'Enter the URL of the image', 'wp-editor' ),
      'enterImageDescription' => __( 'Enter a description of the image', 'wp-editor' ),
      'textdirection'     => __( 'text direction', 'wp-editor' ),
      'toggleTextdirection' => __( 'Toggle Editor Text Direction', 'wp-editor' ),
      'dfw'         => __( 'Distraction-free writing mode', 'wp-editor' ),
      'strong'        => __( 'Bold', 'wp-editor' ),
      'strongClose'     => __( 'Close bold tag', 'wp-editor' ),
      'em'          => __( 'Italic', 'wp-editor' ),
      'emClose'       => __( 'Close italic tag', 'wp-editor' ),
      'link'          => __( 'Insert link', 'wp-editor' ),
      'blockquote'      => __( 'Blockquote', 'wp-editor' ),
      'blockquoteClose'   => __( 'Close blockquote tag', 'wp-editor' ),
      'del'         => __( 'Deleted text (strikethrough)', 'wp-editor' ),
      'delClose'        => __( 'Close deleted text tag', 'wp-editor' ),
      'ins'         => __( 'Inserted text', 'wp-editor' ),
      'insClose'        => __( 'Close inserted text tag', 'wp-editor' ),
      'image'         => __( 'Insert image', 'wp-editor' ),
      'ul'          => __( 'Bulleted list', 'wp-editor' ),
      'ulClose'       => __( 'Close bulleted list tag', 'wp-editor' ),
      'ol'          => __( 'Numbered list', 'wp-editor' ),
      'olClose'       => __( 'Close numbered list tag', 'wp-editor' ),
      'li'          => __( 'List item', 'wp-editor' ),
      'liClose'       => __( 'Close list item tag', 'wp-editor' ),
      'code'          => __( 'Code', 'wp-editor' ),
      'codeClose'       => __( 'Close code tag', 'wp-editor' ),
      'more'          => __( 'Insert Read More tag', 'wp-editor' ),
    ) );
    wp_register_script( 'wpeditor', WPEDITOR_URL . 'js/wpeditor.js', false, WPEDITOR_VERSION_NUMBER );
    wp_localize_script( 'wpeditor', 'WPE', array(
            'wp_editor_ajax_nonce_ajax_folders_themes'  => wp_create_nonce( 'wp_editor_ajax_nonce_ajax_folders_themes' ),
            'wp_editor_ajax_nonce_ajax_folders_plugins' => wp_create_nonce( 'wp_editor_ajax_nonce_ajax_folders_plugins' ),
            'wp_editor_ajax_nonce_save_files_themes'  => wp_create_nonce( 'wp_editor_ajax_nonce_save_files_themes' ),
            'wp_editor_ajax_nonce_save_files_plugins' => wp_create_nonce( 'wp_editor_ajax_nonce_save_files_plugins' )
    ) );
    wp_register_script( 'wp-editor-posts-jquery', WPEDITOR_URL . 'js/posts-jquery.js', false, WPEDITOR_VERSION_NUMBER, true );
    wp_register_script( 'nivo-lightbox', WPEDITOR_URL . 'extensions/nivo-lightbox/js/nivo-lightbox.min.js', array( 'jquery' ), WPEDITOR_VERSION_NUMBER );
    wp_register_script( 'attrchange', WPEDITOR_URL . 'extensions/attrchange/attrchange.js', false, WPEDITOR_VERSION_NUMBER );

    if ( ! wp_script_is( 'codemirror', 'enqueued' ) ) {
      wp_register_script( 'codemirror', WPEDITOR_URL . 'extensions/codemirror/js/codemirror.js', false, WPEDITOR_VERSION_NUMBER );
    }
    wp_register_script( 'codemirror_php', WPEDITOR_URL . 'extensions/codemirror/js/php.js', false, WPEDITOR_VERSION_NUMBER );
    wp_register_script( 'codemirror_javascript', WPEDITOR_URL . 'extensions/codemirror/js/javascript.js', false, WPEDITOR_VERSION_NUMBER );
    wp_register_script( 'codemirror_css', WPEDITOR_URL . 'extensions/codemirror/js/css.js', false, WPEDITOR_VERSION_NUMBER );
    wp_register_script( 'codemirror_xml', WPEDITOR_URL . 'extensions/codemirror/js/xml.js', false, WPEDITOR_VERSION_NUMBER );
    wp_register_script( 'codemirror_clike', WPEDITOR_URL . 'extensions/codemirror/js/clike.js', false, WPEDITOR_VERSION_NUMBER );
    wp_register_script( 'codemirror_dialog', WPEDITOR_URL . 'extensions/codemirror/js/dialog.js', false, WPEDITOR_VERSION_NUMBER );
    wp_register_script( 'codemirror_search', WPEDITOR_URL . 'extensions/codemirror/js/search.js', false, WPEDITOR_VERSION_NUMBER );
    wp_register_script( 'codemirror_searchcursor', WPEDITOR_URL . 'extensions/codemirror/js/searchcursor.js', false, WPEDITOR_VERSION_NUMBER );
    wp_register_script( 'codemirror_mustache', WPEDITOR_URL . 'extensions/codemirror/js/mustache.js', false, WPEDITOR_VERSION_NUMBER );
    wp_register_script( 'codemirror_fullscreen', WPEDITOR_URL . 'extensions/codemirror/js/fullscreen.js', false, WPEDITOR_VERSION_NUMBER );
    //wp_register_script( 'codemirror_foldcode', WPEDITOR_URL . 'extensions/codemirror/js/foldcode.js' );

    wp_register_script( 'chosen', WPEDITOR_URL . 'extensions/chosen/js/chosen.jquery.min.js', array( 'jquery' ), WPEDITOR_VERSION_NUMBER );
  }
  
  public static function get_view( $filename, $data=null ) {
    $filename = WPEDITOR_PATH . "/$filename";
    ob_start();
    include $filename;
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
  }
  
  public static function get_table_name( $name ) {
    return WPEditor::get_table_prefix() . $name;
  }
  
  public static function get_table_prefix() {
    global $wpdb;
    return $wpdb->prefix . 'wpeditor_';
  }
  
  public static function replace_plugin_edit_links( $links ) {
    $data = '';
    if ( isset( $_REQUEST['plugin_status'] ) && in_array( $_REQUEST['plugin_status'], array( 'mustuse', 'dropins' ) ) ) {
      $data = $links;
    }
    elseif ( WPEditorSetting::get_value( 'replace_plugin_edit_links' ) == 1 ) {
      foreach ( $links as $key => $value ) {
        if ( $key === 'edit' ) {
          $value = str_replace( 'plugin-editor.php?', 'plugins.php?page=wpeditor_plugin&', $value );
        }
        $data[ $key ] = $value;
      }
    }
    else {
      $data = $links;
    }
    return $data;
  }
  
}