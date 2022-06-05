<?php

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Manage plugin uninstallation
 * Removing All Plugins Data
 * 
 * @since 1.0.0
 */
class SC_Uninstall {

   public function __construct() {
      $post_to_delete = [ "post_type" => "msu-copy", 'numberposts' => -1 ];

      $copyrights = get_posts( $post_to_delete );

      foreach($copyrights as $copyright) {
         wp_delete_post($copyright->ID, true);
      }
   }

}

new SC_Uninstall();