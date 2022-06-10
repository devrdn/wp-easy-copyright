<?php

if( !defined ( 'WP_UNINSTALL_PLUGIN' ) ) {
   die; 
}


/**
 * Manage plugin uninstallation
 * Removing All Plugins Data
 * 
 * @since 1.0.0
 */
class SC_Uninstall {

   /**
    * All WordPress Post Status
    *
    * @since 1.0.0
    */
   private static $post_status = array (
      'publish',
      'draft',
      'pending',
      'future',
      'private',
      'trash',
      'auto-draft',
      'inherit',
   );

   /**
    * Remove all data of plugin from database
    *
    * @since 1.0.0
    */
   public static function uninstall() {
      self::delete_copyright_post_type();
      self::delete_copyright_post_meta();
   }


   /**
    * Delete Copyright Plugin Post Meta
    *
    * @since 1.0.0
    */
   public static function delete_copyright_post_meta() {
      $copyrights_meta_to_delete = array(
         'post_type' => 'simplecopy',
         'post_status' => self::$post_status,
         'numberposts' => -1,
      );

      $copyrights_meta = get_posts( $copyrights_meta_to_delete );

      foreach( $copyrights_meta as $copyright ) {
         delete_post_meta( $copyright->ID, '_sc_copyright_text' );
      } 
   }

   /**
    * Delete Copyright Plugin Post Type
    *
    * @since 1.0.0
    */
   public static function delete_copyright_post_type() {


      $copyrights_to_delete = array (
         'post_type'    => 'simplecopy',
         'post_status'  => self::$post_status,
         'numberposts'  => -1,
      );

      $copyrights = get_posts( $copyrights_to_delete );

      foreach ( $copyrights as $copyright ) {
         wp_delete_post( $copyright->ID, true );
      }
   }

} 

SC_Uninstall::uninstall(); // Run uninstallation
