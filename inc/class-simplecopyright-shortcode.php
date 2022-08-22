<?php

// Die if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
   _e( 'Hello, i\'m just plugin, and i\'m called when wordpress call me!', 'simple-copy');
   die();
}

if ( ! class_exists( SimpleCopyright_Shortcode::class ) ) :

/**
 * Copyright Shortcode Class
 * 
 * This class is used to define shortcodes for the plugin.
 * 
 * @since 0.0.1
 */
class SimpleCopyright_Shortcode
{

   /**
    * Init hooks and filters
    *
    * @since 0.0.1
    */
   public function __construct() {
      add_action( 'init', [ __CLASS__, 'copyright_register_shortcode' ] );
      add_filter( 'copyright_render_shortcode_filter', [ __CLASS__, 'copyright_render_shortcode' ], 15, 2 );
      add_action( 'edit_form_after_title', [__CLASS__, 'copyright_show_shortcode'] );
   }

   /**
    * Register Copyright shortcode
    *
    * @since 0.0.1
    */
   public static function copyright_register_shortcode() {
      add_shortcode( 'simple-copyright', [ __CLASS__, 'copyright_shortcode' ] );
   }

   /**
    * Build copyright shortcode
    *
    * @param   array  $raw_atts  shortcode attributes
    * @return  string $html      html code of copyright
    * @since   0.0.1
    */
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

   /**
    * Copyright shortcode callback
    *
    * @param   array  $data      copyright data
    * @return  string $html      html code of copyright
    * @since   0.0.1
    */
   public static function copyright_render_shortcode( $data = array() ) { 


      $html  =  '<div class="simple-copyright">';
      $html .= '<span class="scpy__symbol">{_scpy_symbol} </span>';
      $html .= '<span class="scpy__startyear">{_scpy_start_year}&nbsp;</span>';
      $html .= '<span class="scpy__endyear">{_scpy_end_year}&nbsp;</span>';
      $html .= '<span class="scpy__name">{_scpy_copy_name}</span>';
      $html .= '<span class="scpy__extra">.&nbsp;{_scpy_extra_text}</span>';
      $html .= '</div>';

      // replace copyright data
      $html  = preg_replace_callback(
         '/\{(_scpy_[a-z_]+)\}/',

         function( $matches ) use ( $data ) {
            $key =  $matches[1];

            if ( isset( $data[ $key ] ) ) {
               if ( $key == '_scpy_start_year' ) {
                  return $data[ $key ].'&nbsp;&mdash;';
               }
               return $data[ $key ];
            }

            switch ( $key ) {
               case '_scpy_symbol':
                  return '&copy;';
                  break;
               case '_scpy_end_year':
                  return date_i18n( 'Y' );
                  break;
               case '_scpy_extra_text':
                  return 'All rights reserved.';
                  break;

               return '';
            }

         },
         $html
      );
      return $html; 
   }

   /**
    * Shows the Shortcode to user,
    * when post is published
    *
    * @since 0.0.1
    */
   public static function copyright_show_shortcode( $post ) {
      if ( $post->post_type === SimpleCopyright::$post_type ) {
         if ( $post->post_status == 'publish' ) {
      ?>
         <div class='scpy-shortcode'>
            <input readonly onclick="this.select();" value="[simple-copyright id='<?echo $post->ID; ?>']">   
            <input class="scpy-shortcode__showblock" readonly onclick="this.select();" value="do_shortcode( '[simple-copyright id='<?echo $post->ID; ?>']' );">   
         </div>
      <?php
         }
      }
   }

}

endif;