<?php
/**
 * Exception error codes
 * 701 - Could not create Log File
 */ 
class WPEditorException extends Exception {
  
  public static function exceptionMessages( $errorCode, $errorMessage, $reasons=null ) {
    $exception = array(
      'errorCode' => $errorCode,
      'errorMessage' => $errorMessage
    );
    switch ( $errorCode ) {
      case 701;
        $exception['exception'] = __( 'WPEditor was unable to create the log file.  It looks like file permissions are not currently enabled on your site.', 'wpeditor' );
        break;
      default;
        $exception['exception'] = __( "Unfortunately there has been an error with the WPEditor Plugin.  Please contact the site Administrator for more information.<br />Error Code: $errorCode $errorMessage", 'wpeditor' );
        break;
    }
    return $exception;
  }
}