<?php

// Die if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
   _e( 'Hello, i\'m just plugin, and i\'m called when wordpress call me!', 'simple-copy');
   die();
}

if ( ! class_exists( SimpleCopyright_ShortCodes::class ) ) :

/**
 * Copyright Shortcode Class
 * 
 * This class is used to define shortcodes for the plugin.
 * 
 * @since 1.0.0
 */
class SimpleCopyright_Shortcode
{
   public function __construct() {
      add_action( 'init', [ __CLASS__, 'copyright_register_shortcode' ] );
      add_filter( 'copyright_render_shortcode_filter', [ __CLASS__, 'copyright_render_shortcode' ], 15, 2 );
   }

   public static function copyright_register_shortcode() {
      add_shortcode( 'simple-copyright', [ __CLASS__, 'copyright_shortcode' ] );
   }

   public static function copyright_shortcode($raw_atts = array())
   {
   
      $default_atts = array(
         'id' => 0,
      );

      $atts = shortcode_atts(
         $default_atts,
         $raw_atts,
         'simple-copyright'
      );

      $post_id = $atts['id'];
      $post  = trim( $post_id ) == '' ? null : get_post( $post_id );
      if ( $post == null || $post->post_type != SimpleCopyright::$post_type || $post->post_status != 'publish' ) {
         return __( 'No such copright ID found', 'simple-copy' );
      }

      $post_meta_data = SimpleCopyright_CustomPost::copyright_get_metabox_data( $post->ID );


      $html = apply_filters( 'copyright_render_shortcode_filter', $post_meta_data );

     
      return $html;
   }

   public static function copyright_render_shortcode( $data = array() ) { 
      
      $html = '<div class="simple-copyright">';
      if (array_key_exists( '_scpy_copy_name', $data )) {
         $html .= '<span class="scpy__name"> '.$data['_scpy_copy_name'].' </span>';
      }
      
      if ( array_key_exists( '_scpy_start_year', $data) ) {
         $html .= '<span class="scpy__starting-year"> ' . $data['_scpy_start_year'] . ' &mdash; </span>';
      }

      $html .= '<span class="scpy__ending-year"> ';
      if ( array_key_exists( '_scpy_end_year', $data) ) {
         $html .= $data['_scpy_end_year'];
      } else {
         $html .=  date_i18n( 'Y' );
      }
      $html .=  ' </span>';
      
      $html .= '<span class="scpy__copy-symbol">';
      if ( array_key_exists( '_scpy_symbol', $data) ) {
         $html .=  $data['_scpy_symbol'];
      } else {
         $html .= '&copy;';
      }
      $html .= ' </span>';
      $html .= ' <span class="scpy__text">All rights reserved.</span>';
      $html .= '</div>';
      return $html; 
   }

}

endif;