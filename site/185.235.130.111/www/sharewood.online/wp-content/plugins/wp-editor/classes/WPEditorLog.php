<?php
class WPEditorLog {
  
  public static function log( $data ) {
    if ( defined( 'WPEDITOR_DEBUG' ) && WPEDITOR_DEBUG ) {
      $tz = '- Server time zone ' . date( 'T' );
      $date = date( 'm/d/Y g:i:s a', self::local_ts() );
      $header = strpos( $_SERVER['REQUEST_URI'], 'wp-admin' ) ? "\n\n======= ADMIN REQUEST =======\n[LOG DATE: $date $tz]\n" : "\n\n[LOG DATE: $date $tz]\n";
      $filename = WPEDITOR_PATH . '/log.txt'; 
      if ( file_exists( $filename ) && is_writable( $filename ) ) {
        file_put_contents( $filename, $header . $data, FILE_APPEND );
      }
    }
  }
  
  public static function local_ts( $timestamp=null ) {
    $timestamp = isset( $timestamp) ? $timestamp : time();
    if ( date( 'T' ) == 'UTC' ) {
      $timestamp += ( get_option( 'gmt_offset' ) * 3600 );
    }
    return $timestamp;
  }
  
  public static function create_log_file() {
    $log_dir_path = WPEDITOR_PATH;
    $log_file_path = self::get_log_file_path();
    
    if ( file_exists( $log_dir_path ) ) {
      if ( is_writable( $log_dir_path ) ) {
        @fclose( fopen( $log_file_path, 'a' ) );
        if ( ! is_writable( $log_file_path ) ) {
          throw new WPEditorException( "Unable to create log file. $log_file_path", 701 );
        }
      }
      else {
        throw new WPEditorException( "Log file directory is not writable. $log_dir_path", 702 );
      }
    }
    else {
      throw new WPEditorException( "Log file directory does not exist. $log_dir_path", 703 );
    }
    
    
    return $log_file_path;
  }
  
  public static function get_log_file_path() {
    $log_file_path = WPEDITOR_PATH . '/log.txt';
    return $log_file_path;
  }
  
  public static function exists() {
    $exists = false;
    $log_file_path = self::get_log_file_path();
    if ( file_exists( $log_file_path ) && filesize( $log_file_path ) > 0 ) {
      $exists = true;
    }
    return $exists;
  }
  
}