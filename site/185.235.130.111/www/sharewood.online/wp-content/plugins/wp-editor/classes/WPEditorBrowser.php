<?php
class WPEditorBrowser {
  
  public static function get_files_and_folders( $dir, $contents, $type ) {
    $slash = '/';
    if ( WPWINDOWS ) {
      $slash = '\\';
    }
    $output = array();
    if ( is_dir( $dir ) ) {
      if ( $handle = opendir( $dir ) ) {
        $size_document_root = strlen( $_SERVER['DOCUMENT_ROOT'] );
        $pos = strrpos( $dir, $slash );
        $topdir = substr( $dir, 0, $pos + 1 );
        $i = 0;
        while ( false !== ( $file = readdir( $handle ) ) ) {
          if ( $file != '.' && $file != '..' && substr( $file, 0, 1 ) != '.' && self::allowed_files( $dir, $file ) ) {
            $rows[ $i ]['data'] = $file;
            $rows[ $i ]['dir'] = is_dir( $dir . $slash . $file );
            $i++;
          }
        }
        closedir( $handle );
      }

      if ( isset( $rows ) ) {  
        $size = count( $rows );
        $rows = self::sort_rows( $rows );
        for( $i = 0; $i < $size; ++$i ) {
          $topdir = $dir . $slash . $rows[ $i ]['data'];
          $output[ $i ]['name'] = $rows[ $i ]['data'];
          $output[ $i ]['path'] = $topdir;
          if ( $rows[ $i ]['dir'] ) {
            $output[ $i ]['filetype'] = 'folder';
            $output[ $i ]['extension'] = 'folder';
            $output[ $i ]['filesize'] = '';
          }
          else {
            $output[ $i ]['writable'] = false;
            if ( is_writable( $output[ $i ]['path'] ) ) {
              $output[ $i ]['writable'] = true;
            }
            $output[ $i ]['filetype'] = 'file';
            $path = pathinfo( $output[ $i ]['name'] );
            if ( isset( $path['extension'] ) ) {
              $output[ $i ]['extension'] = strtolower( $path['extension'] );
            }
            $output[ $i ]['filesize'] = '( ' . round( filesize( $topdir ) * .0009765625, 2) . ' KB)';
            if ( $type == 'theme' ) {
              $output[ $i ]['file'] = str_replace( realpath( get_theme_root() ) . $slash, '', $output[ $i ]['path'] );
              $output[ $i ]['url'] = get_theme_root_uri() . $slash . $output[ $i ]['file'];
            }
            else {
              $output[ $i ]['file'] = str_replace( realpath( WP_PLUGIN_DIR ) . $slash, '', $output[ $i ]['path'] );
              $output[ $i ]['url'] = plugins_url() . $slash . $output[ $i ]['file'];
            }
          }
        }
      }
      else {
        $output[-1] = 'this folder has no contents';
      }
    }
    elseif ( is_file( $dir ) ) {
      if ( isset( $contents ) && $contents == 1 ) {
        $output['name'] = basename( $dir );
        $output['path'] = $dir;
        $output['filetype'] = 'file';
        $path = pathinfo( $output['name'] );
        if ( isset( $path['extension'] ) ) {
          $output['extension'] = strtolower( $path['extension'] );
        }
        $output['content'] = file_get_contents( $dir );
        $output['writable'] = false;
        if ( is_writable( $output['path'] ) ) {
          $output['writable'] = true;
        }
        if ( $type == 'theme' ) {
          $output['file'] = str_replace( realpath( get_theme_root() ) . $slash, '', $output['path'] );
          $output['url'] = get_theme_root_uri() . $slash . $output['file'];
        }
        else {
          $output['file'] = str_replace( realpath( WP_PLUGIN_DIR ) . $slash, '', $output['path'] );
          $output['url'] = plugins_url() . $slash . $output['file'];
        }
      }
      else {
        $pos = strrpos( $dir, $slash );
        $newdir = substr( $dir, 0, $pos );
        if ( $handle = opendir( $newdir ) ) {
          $size_document_root = strlen( $_SERVER['DOCUMENT_ROOT'] );
          $pos = strrpos( $newdir, $slash );
          $topdir = substr( $newdir, 0, $pos + 1 );
          $i = 0;
          while ( false !== ( $file = readdir( $handle ) ) ) {
            if ( $file != '.' && $file != '..' && substr( $file, 0, 1 ) != '.' && WPEditorBrowser::allowed_files( $newdir, $file ) ) {
              $rows[ $i ]['data'] = $file;
              $rows[ $i ]['dir'] = is_dir( $newdir . $slash . $file );
              $i++;
            }
          }
          closedir( $handle );
        }
      
        if ( isset( $rows ) ) {
          $size = count( $rows );
          $rows = self::sort_rows( $rows );
          for( $i = 0; $i < $size; ++$i ) {
            $topdir = $newdir . $slash . $rows[ $i ]['data'];
            $output[ $i ]['name'] = $rows[ $i ]['data'];
            $output[ $i ]['path'] = $topdir;
            if ( $rows[ $i ]['dir'] ) {
              $output[ $i ]['filetype'] = 'folder';
              $output[ $i ]['extension'] = 'folder';
              $output[ $i ]['filesize'] = '';
            }
            else {
              $output[ $i ]['writable'] = false;
              if ( is_writable( $output[ $i ]['path'] ) ) {
                $output[ $i ]['writable'] = true;
              }
              $output[ $i ]['filetype'] = 'file';
              $path = pathinfo( $rows[ $i ]['data'] );
              if ( isset( $path['extension'] ) ) {
                $output[ $i ]['extension'] = strtolower( $path['extension'] );
              }
              $output[ $i ]['filesize'] = '( ' . round( filesize( $topdir ) * .0009765625, 2) . ' KB)';
            }
            if ( $output[ $i ]['path'] == $dir ) {
              $output[ $i ]['content'] = file_get_contents( $dir );
            }
            $output[ $i ]['writable'] = false;
            if ( is_writable( $output[ $i ]['path'] ) ) {
              $output[ $i ]['writable'] = true;
            }
            if ( $type == 'theme' ) {
              $output[ $i ]['file'] = str_replace( realpath( get_theme_root() ) . $slash, '', $output[ $i ]['path'] );
              $output[ $i ]['url'] = get_theme_root_uri() . $slash . $output[ $i ]['file'];
            }
            else {
              $output[ $i ]['file'] = str_replace( realpath( WP_PLUGIN_DIR ) . $slash, '', $output[ $i ]['path'] );
              $output[ $i ]['url'] = plugins_url() . $slash . $output[ $i ]['file'];
            }
          }
        }
        else {
          $output[-1] = 'bad file or unable to open';
        }
      }
    }
    else {
      $output[-1] = 'bad file or unable to open';
    };
    return $output;
  }
  
  public static function sort_rows( $data ) {
    $size = count( $data );

    for( $i = 0; $i < $size; ++$i ) {
      $row_num = self::find_smallest( $i, $size, $data );
      $tmp = $data[ $row_num ];
      $data[ $row_num ] = $data[ $i ];
      $data[ $i ] = $tmp;
    }

    return $data;
  }

  public static function find_smallest( $i, $end, $data ) {
    $min['pos'] = $i;
    $min['value'] = $data[ $i ]['data'];
    $min['dir'] = $data[ $i ]['dir'];
    for(; $i < $end; ++$i ) {
      if ( $data[ $i ]['dir'] ) {
        if ( $min['dir'] ) {
          if ( $data[ $i ]['data'] < $min['value'] ) {
            $min['value'] = $data[ $i ]['data'];
            $min['dir'] = $data[ $i ]['dir'];
            $min['pos'] = $i;
          }
        }
        else {
          $min['value'] = $data[ $i ]['data'];
          $min['dir'] = $data[ $i ]['dir'];
          $min['pos'] = $i;
        }
      }
      else {
        if (!$min['dir'] && $data[ $i ]['data'] < $min['value'] ) {
          $min['value'] = $data[ $i ]['data'];
          $min['dir'] = $data[ $i ]['dir'];
          $min['pos'] = $i;
        }
      }
    }
    return $min['pos'];
  }
  
  public static function allowed_files( $dir, $file ) {
    $slash = '/';
    if ( WPWINDOWS ) {
      $slash = '\\';
    }
    $output = true;
    if ( strstr( $dir, 'plugins' ) ) {
      $allowed_extensions = explode( '~', WPEditorSetting::get_value( 'plugin_editor_allowed_extensions' ) );
    }
    elseif ( strstr( $dir, 'themes' ) ) {
      $allowed_extensions = explode( '~', WPEditorSetting::get_value( 'theme_editor_allowed_extensions' ) );
    }
    
    if ( is_dir( $dir . $slash . $file ) ) {
      $output = true;
    }
    else {
      $file = pathinfo( $file );
      if ( isset( $file['extension'] ) && in_array( strtolower( $file['extension'] ), $allowed_extensions ) ) {
        $output = true;
      }
      else {
        $output = false;
      }
    }
    return $output;
  }
  
  public static function upload_theme_files() {
    // Theme file upload
    $slash = '/';
    if ( WPWINDOWS ) {
      $slash = '\\';
    }
    if ( isset( $_FILES["file-0"] ) && isset( $_POST['current_theme_root'] ) ) {
      $error = $_FILES["file-0"]["error"];
      $error_message = __( 'No Errors', 'wp-editor' );
      $success = __( 'Unsuccessful', 'wp-editor' );
      $current_theme_root = $_POST['current_theme_root'];
      $directory = '';
      if ( isset( $_POST['directory'] ) ) {
        $directory = $_POST['directory'];
        $dir = substr( $directory, -1 );
        if ( $dir != $slash ) {
          $directory = $directory . $slash;
        }
        $dir = substr( $directory, 0, 1 );
        if ( $dir == $slash ) {
          $directory = substr( $directory, 1 );
        }
      }
      $complete_directory = $current_theme_root . $directory;
      if ( ! is_dir( $complete_directory ) ) {
        mkdir( $complete_directory, 0777, true );
      }
      
      if ( $_FILES["file-0"]["error"] > 0 ) {
        $error_message = __( 'Return Code', 'wp-editor' ) . ": " . $_FILES["file-0"]["error"];
      }
      else {
        //$result = "Upload: " . $_FILES["file-0"]["name"] . "<br />";
        //$result .= "Type: " . $_FILES["file-0"]["type"] . "<br />";
        //$result .= "Size: " . ( $_FILES["file-0"]["size"] / 1024) . " Kb<br />";
        //$result .= "Temp file: " . $_FILES["file-0"]["tmp_name"] . "<br />";

        if ( file_exists( $complete_directory . $_FILES["file-0"]["name"] ) ) {
          $error = -1;
          $error_message = $_FILES["file-0"]["name"] . __( ' already exists', 'wp-editor' );
        }
        else {
          move_uploaded_file( $_FILES["file-0"]["tmp_name"], $current_theme_root . $directory . $_FILES["file-0"]["name"] );
          $success = "Stored in: " . basename( $complete_directory ) . $slash . $_FILES["file-0"]["name"];
        }
      }
    }
    else {
      $error = -2;
      $error_message = __( 'No File Selected', 'wp-editor' );
      $success = __( 'Unsuccessful', 'wp-editor' );
    }
    $result = array(
      'error' => array(
        $error,
        $error_message
      ),
      'success' => $success
    );
    return $result;
  }
  
  public static function upload_plugin_files() {
    // Plugin file upload
    $slash = '/';
    if ( WPWINDOWS ) {
      $slash = '\\';
    }
    if ( isset( $_FILES["file-0"] ) && isset( $_POST['current_plugin_root'] ) ) {
      $error = $_FILES["file-0"]["error"];
      $error_message = __( 'No Errors', 'wp-editor' );
      $success = __( 'Unsuccessful', 'wp-editor' );
      $current_plugin_root = $_POST['current_plugin_root'];
      $directory = '';
      if ( isset( $_POST['directory'] ) ) {
        $directory = $_POST['directory'];
        $dir = substr( $directory, -1 );
        if ( $dir != $slash ) {
          $directory = $directory . $slash;
        }
        $dir = substr( $directory, 0, 1 );
        if ( $dir == $slash ) {
          $directory = substr( $directory, 1 );
        }
      }
      $complete_directory = $current_plugin_root . $slash . $directory;
      if ( ! is_dir( $complete_directory ) ) {
        mkdir( $complete_directory, 0777, true );
      }
      
      if ( $_FILES["file-0"]["error"] > 0 ) {
        $error_message = __( 'Return Code', 'wp-editor' ) . ": " . $_FILES["file-0"]["error"];
      }
      else {
        //$result = "Upload: " . $_FILES["file-0"]["name"] . "<br />";
        //$result .= "Type: " . $_FILES["file-0"]["type"] . "<br />";
        //$result .= "Size: " . ( $_FILES["file-0"]["size"] / 1024) . " Kb<br />";
        //$result .= "Temp file: " . $_FILES["file-0"]["tmp_name"] . "<br />";

        if ( file_exists( $complete_directory . $_FILES["file-0"]["name"] ) ) {
          $error = -1;
          $error_message = $_FILES["file-0"]["name"] . __( ' already exists', 'wp-editor' );
        }
        else {
          move_uploaded_file( $_FILES["file-0"]["tmp_name"], $complete_directory . $_FILES["file-0"]["name"] );
          $success = "Stored in: " . basename( $complete_directory ) . $slash . $_FILES["file-0"]["name"];
        }
      }
    }
    else {
      $error = -2;
      $error_message = __( 'No File Selected', 'wp-editor' );
      $success = __( 'Unsuccessful', 'wp-editor' );
    }
    $result = array(
      'error' => array(
        $error,
        $error_message
      ),
      'success' => $success
    );
    return $result;
  }
  
  public static function download_theme( $theme_name ) {
    if ( current_user_can( 'edit_themes' ) ) {
      $slash = '/';
      if ( WPWINDOWS ) {
        $slash = '\\';
      }
      $position = strpos( $theme_name, $slash );
      $theme_name = substr( $theme_name, 0, $position );
      $theme = wp_get_theme( $theme_name );
      
      if ( $theme->exists() ) {
        $directory = $theme->get_stylesheet_directory() . $slash;
        $filename = $theme_name . '.zip';
        // create object
        $zip = self::compress( $directory, $filename );
        if ( $zip ) {
          header( 'Content-Disposition: attachment; filename="' . $theme_name . '.zip' . '"');
          header( 'Content-Description: File Transfer' );
          header( 'Content-Type: application/octet-stream' );
          header( 'Content-Transfer-Encoding: binary' );
          header( 'Pragma: public' );
          header( 'Content-Length: ' . filesize( $filename ) );
          ob_clean();
          flush();
          readfile( $filename );
          unlink( $filename );
          exit;
        }
        else {
          wp_redirect( admin_url( 'themes.php?page=wpeditor_themes&error=3' ) );
          exit;
        }
      }
      else {
        wp_redirect( admin_url( 'themes.php?page=wpeditor_themes&error=2' ) );
        exit;
      }
    }
    else {
      wp_redirect( admin_url( 'themes.php?page=wpeditor_themes&error=1' ) );
      exit;
    }
  }
  
  public static function download_file( $file_path, $type ) {
    if ( ( $type == 'theme' && current_user_can( 'edit_themes' ) ) || ( $type == 'plugin' && current_user_can( 'edit_plugins' ) ) ) {
      $slash = '/';
      if ( WPWINDOWS ) {
        $slash = '\\';
      }
      if ( file_exists( $file_path ) ) {
        $content = file_get_contents( $file_path );
        $filename = basename( $file_path );
        $filesize = strlen( $content);
        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Content-Description: File Transfer' );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        header( 'Content-Length: ' . $filesize );
        header( 'Expires: 0' );
        header( 'Pragma: public' );
        ob_clean();
        flush();
        echo $content;
        exit;
      }
      else {
        if ( $type == 'theme' ) {
          wp_redirect( admin_url( 'themes.php?page=wpeditor_themes&error=2' ) );
          exit;
        }
        elseif ( $type == 'plugin' ) {
          wp_redirect( admin_url( 'plugins.php?page=wpeditor_plugin&error=2' ) );
          exit;
        }
      }
    }
    else {
      if ( $type == 'theme' ) {
        wp_redirect( admin_url( 'themes.php?page=wpeditor_themes&error=4' ) );
        exit;
      }
      elseif ( $type == 'plugin' ) {
        wp_redirect( admin_url( 'plugins.php?page=wpeditor_plugin&error=4' ) );
        exit;
      }
    }
  }
  
  public static function download_plugin( $plugin_name ) {
    if ( current_user_can( 'edit_plugins' ) ) {
      $slash = '/';
      if ( WPWINDOWS ) {
        $slash = '\\';
      }
      //Get the directory to zip
      $plugin_name = basename( $plugin_name );
      $position = strpos( $plugin_name, '.' );
      $plugin_name = substr( $plugin_name, 0, $position );
      $directory = WP_PLUGIN_DIR . $slash . $plugin_name . $slash;
      $filename = $plugin_name . '.zip';
      if ( is_dir( $directory ) ) {
        $zip = self::compress( $directory, $filename );
        if ( $zip ) {
          header( 'Content-Disposition: attachment; filename="' . $plugin_name . '.zip' . '"');
          header( 'Content-Description: File Transfer' );
          header( 'Content-Type: application/octet-stream' );
          header( 'Content-Transfer-Encoding: binary' );
          header( 'Pragma: public' );
          header( 'Content-Length: ' . filesize( $filename ) );
          ob_clean();
          flush();
          readfile( $filename );
          unlink( $filename );
          exit;
        }
        else {
          wp_redirect( admin_url( 'plugins.php?page=wpeditor_plugin&error=3' ) );
          exit;
        }
      }
      else {
        wp_redirect( admin_url( 'plugins.php?page=wpeditor_plugin&error=2' ) );
        exit;
      }
    }
    else {
      wp_redirect( admin_url( 'plugins.php?page=wpeditor_plugin&error=1' ) );
      exit;
    }
  }
  
  public static function compress( $directory, $filename ) {
    $zip = new ZipArchive();
    if ( ! $zip->open( $filename, ZIPARCHIVE::CREATE ) ) {
      //wp_die( '<p>' . __( 'error ziping files.', 'wpe-editor' ) . '</p><script>alert( "' . __( 'error ziping files. ZipArchive Create Error', 'wpe-editor' ) . '");</script>' );
      //exit;
    }
    self::add_files_to_zip( $directory, $zip );
    return $zip->close();
  }
  
  public static function add_files_to_zip( $directory, $zip, $zipdir='' ) {
    if ( is_dir( $directory ) ) {
      if ( $dh = opendir( $directory ) ) {
        //Add the directory
        //$zip->addEmptyDir( $directory );
        // Loop through all the files
        while ( ( $file = readdir( $dh ) ) !== false ) {
          //If it's a folder, run the function again!
          if (!is_file( $directory . $file ) ) {
            // Skip parent and root directories
            if ( ( $file !== ".") && ( $file !== "..") ) {
              self::add_files_to_zip( $directory . $file . "/", $zip, $zipdir . $file . "/");
            }
          }
          else {
            // Add the files
            $zip->addFile( $directory . $file, $zipdir . $file );
          }
        }
      }
    }
  }
  
}