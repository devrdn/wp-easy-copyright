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
    * Uninstall all data
    *
    * @since 1.0.0
    */
   public static function uninstall() {
      
      // delete copyrights
      self::delete_copyright_post_type();

   }

   /**
    * Delete Copyright Post Type
    *
    * @since 1.0.0
    */
   public static function delete_copyright_post_type() {

      $post_status = array (
         'publish',
         'draft',
         'pending',
         'future',
         'private',
         'trash',
         'auto-draft',
         'inherit',
      );

      $copyright_to_delete = array (
         'post_type' => 'simplecopy',
         'post_status' => $post_status,
         'posts_per_page' => -1
      );

      $copyrights = get_posts( $copyright_to_delete );

      foreach ( $copyrights as $copyright ) {
         wp_delete_post( $copyright->ID, true );
      }
   }
}

SC_Uninstall::uninstall(); // Run uninstallation
