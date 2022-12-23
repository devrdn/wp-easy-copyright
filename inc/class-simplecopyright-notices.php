<?php
// Die if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
   _e("Hello, i'm just plugin, and i'm called when wordpress call me!", 'simple-copy');
   die();
}

if ( !class_exists( SimpleCopyright_Notices::class ) ) :

/**
 * Class for SimpleCopyright Admin Notices
 * 
 * @since 1.0.0
 */
class SimpleCopyright_Notices
{
   
   /**
    * Invalid length message
    *
    * @since 1.0.0
    */
   private static $invalid_length_message = "Invalid field length: [field]."; 

   /**
    * Administration notice: Invalid Field length
    *
    * @param string $field field name
    * @since 1.0.0
    */
   public static function copyright_invalid_field_length () {
      $field = "test";
      $message = self::copyright_create_message( self::$invalid_length_message, 'field', $field );
      ?>
         <div class="notice notice-error is-dismissible">
            <?php _e($message, 'simple-copy'); ?>
         </div>
      <?php
   }

   /**
    * Create notice message
    *
    * @param string $message     message with dynamic field
    * @param string $text        name of replacement field
    * @param string $replacement replacement content
    * @since 1.0.0
    */
   private static function copyright_create_message( $message, $text, $replacement) {
      $new_message = preg_replace( '/\['. $text .'\]/', $replacement, $message );
      return $new_message;
   }
}

endif; // class_exists