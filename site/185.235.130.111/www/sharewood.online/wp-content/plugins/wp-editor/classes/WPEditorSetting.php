<?php
class WPEditorSetting {

  /**
   * Get Settings
   *
   * Retrieves all plugin settings
   *
   * @since 1.0
   * @return array WP Editor settings
   */
  public static function get_settings() {

    $settings = get_option( 'wpe_settings' );

    if( empty( $settings ) ) {

      // Update old settings with new single option

      $general_settings         = is_array( get_option( 'wpe_settings_general' ) )        ? get_option( 'wpe_settings_general' )        : array();
      $theme_editor_settings    = is_array( get_option( 'wpe_settings_theme_editor' ) )   ? get_option( 'wpe_settings_theme_editor' )   : array();
      $plugin_editor_settings   = is_array( get_option( 'wpe_settings_plugin_editor' ) )  ? get_option( 'wpe_settings_plugin_editor' )  : array();
      $post_editor_settings     = is_array( get_option( 'wpe_settings_post_editor' ) )    ? get_option( 'wpe_settings_post_editor' )    : array();
      $license_settings         = is_array( get_option( 'wpe_settings_license' ) )        ? get_option( 'wpe_settings_license' )        : array();

      $settings = array_merge( $general_settings, $theme_editor_settings, $plugin_editor_settings, $post_editor_settings, $license_settings );

      update_option( 'wpe_settings', $settings );

    }
    return apply_filters( 'wpe_get_settings', $settings );
  }

  /**
   * Add all settings sections and fields
   *
   * @since 1.0
   * @return void
  */
  public static function register_settings() {

    if ( false == get_option( 'wpe_settings' ) ) {
      add_option( 'wpe_settings' );
    }

    foreach ( self::get_registered_settings() as $tab => $sections ) {
      foreach ( $sections as $section => $settings) {

        // Check for backwards compatibility
        $section_tabs = self::get_settings_tab_sections( $tab );
        if ( ! is_array( $section_tabs ) || ! array_key_exists( $section, $section_tabs ) ) {
          $section = 'main';
          $settings = $sections;
        }

        add_settings_section(
          'wpe_settings_' . $tab . '_' . $section,
          __return_null(),
          '__return_false',
          'wpe_settings_' . $tab . '_' . $section
        );

        foreach ( $settings as $option ) {
          // For backwards compatibility
          if ( empty( $option['id'] ) ) {
            continue;
          }

          $name = isset( $option['name'] ) ? $option['name'] : '';

          add_settings_field(
            'wpe_settings[' . $option['id'] . ']',
            $name,
            method_exists( __CLASS__, 'wpe_' . $option['type'] . '_callback' ) ? array( __CLASS__, 'wpe_' . $option['type'] . '_callback' ) : array( __CLASS__, 'missing_callback' ),
            'wpe_settings_' . $tab . '_' . $section,
            'wpe_settings_' . $tab . '_' . $section,
            array(
              'section'     => $section,
              'id'          => isset( $option['id'] )          ? $option['id']          : null,
              'desc'        => ! empty( $option['desc'] )      ? $option['desc']        : '',
              'name'        => isset( $option['name'] )        ? $option['name']        : null,
              'size'        => isset( $option['size'] )        ? $option['size']        : null,
              'options'     => isset( $option['options'] )     ? $option['options']     : '',
              'std'         => isset( $option['std'] )         ? $option['std']         : '',
              'min'         => isset( $option['min'] )         ? $option['min']         : null,
              'max'         => isset( $option['max'] )         ? $option['max']         : null,
              'step'        => isset( $option['step'] )        ? $option['step']        : null,
              'chosen'      => isset( $option['chosen'] )      ? $option['chosen']      : null,
              'placeholder' => isset( $option['placeholder'] ) ? $option['placeholder'] : null,
              'allow_blank' => isset( $option['allow_blank'] ) ? $option['allow_blank'] : true,
              'readonly'    => isset( $option['readonly'] )    ? $option['readonly']    : false,
              'faux'        => isset( $option['faux'] )        ? $option['faux']        : false,
            )
          );
        }
      }

    }

    // Creates our settings in the options table
    register_setting( 'wpe_settings', 'wpe_settings', 'wpe_settings_sanitize' );

  }

  /**
   * Retrieve the array of plugin settings
   *
   * @since 1.8
   * @return array
  */
  public static function get_registered_settings() {

    /**
     * 'Whitelisted' WP Editor settings, filters are provided for each settings
     * section to allow extensions and other plugins to add their own settings
     */
    $wpe_settings = array(
      /** General Settings */
      'general' => apply_filters( 'wpe_settings_general',
        array(
          'main' => array(
            'allowed_extensions' => array(
              'id'      => 'allowed_extensions',
              'name'    => __( 'Allowed Extensions', 'wp-editor' ),
              'desc'    => __( 'Select the extensions you want to enable for the Theme and Plugin editors.', 'wp-editor' ),
              'type'    => 'multiselect',
              'optons'  => apply_filters( 'allowed_extensions', array(
                'php'   => '.php',
                'js'    => '.js',
                'css'   => '.css',
                'scss'  => '.scss',
                'txt'   => '.txt',
                'htm'   => '.htm',
                'html'  => '.html',
                'jpg'   => '.jpg',
                'jpeg'  => '.jpeg',
                'png'   => '.png',
                'gif'   => '.gif',
                'sql'   => '.sql',
                'po'    => '.po',
                'pot'   => '.pot',
                'less'  => '.less',
                'xml'   => '.xml'
              ) )
            ),
          ),
          'codemirror' => array(
            'test' => array(
              'id'   => 'test_codemirror',
              'name' => '<h3>' . __( 'Test Codemirror', 'wp-editor' ) . '</h3>',
              'desc' => '',
              'type' => 'header',
            ),
          )
        )
      ),
      /** Theme Editor Settings */

    );

    return apply_filters( 'wpe_registered_settings', $wpe_settings );
  }

  /**
   * Retrieve settings tabs
   *
   * @since 1.8
   * @return array $tabs
   */
  public static function get_settings_tabs() {

    $settings = self::get_registered_settings();

    $tabs = array(
      'general' => __( 'General', 'wp-editor' ),
      'theme_editor' => __( 'Theme Editor', 'wp-editor' ),
      'plugin_editor' => __( 'Plugin Editor', 'wp-editor' ),
      'post_editor' => __( 'Page/Post Editor', 'wp-editor' ),
      'license' => __( 'License', 'wp-editor' )
    );

    return apply_filters( 'wpe_settings_tabs', $tabs );
  }

  /**
   * Retrieve settings tabs
   *
   * @since 2.5
   * @return array $section
   */
  public static function get_settings_tab_sections( $tab = false ) {

    $tabs     = false;
    $sections = WPEditorSetting::get_registered_settings_sections();

    if( $tab && ! empty( $sections[ $tab ] ) ) {
      $tabs = $sections[ $tab ];
    } else if ( $tab ) {
      $tabs = false;
    }

    return $tabs;
  }

  /**
   * Get the settings sections for each tab
   * Uses a static to avoid running the filters on every request to this function
   *
   * @since  2.5
   * @return array Array of tabs and sections
   */
  public static function get_registered_settings_sections() {

    static $sections = false;

    if ( false !== $sections ) {
      return $sections;
    }

    $sections = array(
      'general'       => apply_filters( 'wpe_settings_sections_general', array(
        'main'        => __( 'Main', 'wp-editor' ),
        'codemirror'  => __( 'Codemirror', 'wp-editor' ),
      ) ),
      'theme_editor'  => apply_filters( 'wpe_settings_sections_theme_editor', array(
        'main'        => __( 'Theme Editor Settings', 'wp-editor' ),
      ) ),
      'plugin_editor' => apply_filters( 'wpe_settings_sections_plugin_editor', array(
        'main'        => __( 'Plugin Editor Settings', 'wp-editor' ),
      ) ),
      'post_editor'   => apply_filters( 'wpe_settings_sections_post_editor', array(
        'main'        => __( 'Page/Post Editor Settings', 'wp-editor' ),
      ) ),
      'license'       => apply_filters( 'wpe_settings_sections_license', array(
        'main'         => __( 'WP Editor License', 'wp-editor' ),
      ) ),
    );

    $sections = apply_filters( 'wpe_settings_sections', $sections );

    return $sections;
  }

  public static function missing_callback( $args ) {
    printf(
      __( 'The callback function used for the %s setting is missing.', 'easy-digital-downloads' ),
      '<strong>' . $args['id'] . '</strong>'
    );
  }

  public static function wpe_multiselect_callback( $args ) {
    global $wpe_options; //need to set this up

    ob_start(); ?>
      

    <?php echo ob_get_clean();
  }
  
  public static function set_value( $key, $value ) {
    global $wpdb;
    $settings_table = WPEditor::get_table_name( 'settings' );
    
    if ( ! empty( $key ) ) {
      $db_key = $wpdb->get_var( "SELECT `key` from $settings_table where `key`='$key'" );
      if ( $db_key ) {
        if ( ! empty( $value ) || $value !== 0 ) {
          $wpdb->update( $settings_table, 
            array( 'key'=>$key, 'value'=>$value ),
            array( 'key'=>$key ),
            array( '%s', '%s' ),
            array( '%s' )
          );
        }
        else {
          $wpdb->query( "DELETE from $settings_table where `key`='$key'" );
        }
      }
      else {
        if ( !empty( $value ) || $value !== 0 ) {
          $wpdb->insert( $settings_table, 
            array( 'key'=>$key, 'value'=>$value ),
            array( '%s', '%s' )
          );
        }
      }
    }
    
  }
  
  public static function get_value( $key, $entities=false ) {
    $value = false;
    global $wpdb;
    $settings_table = WPEditor::get_table_name( 'settings' );
    $value = $wpdb->get_var( "SELECT `value` from $settings_table where `key`='$key'" );
    
    if(!empty( $value ) && $entities ) {
      $value = htmlentities( $value );
    }
    
    return $value;
  }
  
}