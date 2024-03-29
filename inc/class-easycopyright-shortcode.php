<?php

// Die if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
   _e( 'Hello, i\'m just plugin, and i\'m called when wordpress call me!', 'easy-copy');
   die();
}

if ( ! class_exists( EasyCopyright_Shortcode::class ) ) :

/**
 * Copyright Shortcode Class
 * 
 * This class is used to define shortcodes for the plugin.
 * 
 * @since 1.0.0
 */
class EasyCopyright_Shortcode
{

   /**
    * Init hooks and filters
    *
    * @since 1.0.0
    */
   public function __construct() {
      add_action( 'init', [ __CLASS__, 'copyright_register_shortcode' ] );
      add_filter( 'copyright_render_shortcode_filter', [ __CLASS__, 'copyright_render_shortcode' ], 15, 2 );
      add_action( 'edit_form_after_title', [__CLASS__, 'copyright_show_shortcode'] );
   }

   /**
    * Register Copyright shortcode
    *
    * @since 1.0.0
    */
   public static function copyright_register_shortcode() {
      add_shortcode( 'easy-copyright', [ __CLASS__, 'copyright_shortcode' ] );
   }

   /**
    * Build copyright shortcode
    *
    * @param   array  $raw_atts  shortcode attributes
    * @return  string $html      html code of copyright
    * @since   1.0.0
    */
   public static function copyright_shortcode($raw_atts = array())
   {
   
      $default_atts = array(
         'id' => 0,
      );

      $atts = shortcode_atts(
         $default_atts,
         $raw_atts,
         'easy-copyright'
      );

      $post_id = $atts['id'];
      $post  = trim( $post_id ) == '' ? null : get_post( $post_id );
      if ( $post == null || $post->post_type != EasyCopyright::$post_type || $post->post_status != 'publish' ) {
         return __( 'No such copright ID found', 'easy-copy' );
      }

      $post_meta_data = EasyCopyright_CustomPost::copyright_get_metabox_data( $post->ID, false );

      $html = apply_filters( 'copyright_render_shortcode_filter', $post_meta_data );
     
      return $html;
   }

   /**
    * Copyright shortcode callback
    *
    * @param   array  $data      copyright data
    * @return  string $html      html code of copyright
    * @since   1.0.0
    */
   public static function copyright_render_shortcode( $data = array() ) { 

      // Field Order Name
      $field_order_name =  EasyCopyright_CustomPost::copyright_get_field_order_name();
      $field_order_info =  EasyCopyright_CustomPost::copyright_get_field_order_info();
      $field_order      =  $data[ $field_order_name ] ? $data[ $field_order_name ] : $field_order_info['default'];
      
      // html layout of copyright
      $html  =    '<div class="easy-copyright">';
      $html .=    $field_order;
      $html .=    '</div>';

      // replace copyright data
      $html  = preg_replace_callback(
         '/\[([a-z_]+)\]/',

         function( $matches ) use ( $data ) {
            $key =  $matches[1];
            $field_name = '_easy_' . $key;

            if ( !empty( $data[ $field_name ] ) ) {
               return $data[ $field_name ];
            }

            switch ( $key ) {
               case 'symbol':
                  return '&copy;';
                  break;
               case 'end_year':
                  return date_i18n( 'Y' );
                  break;
               case 'extra_text':
                  return 'All rights reserved';
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
    * @since 1.0.0
    */
   public static function copyright_show_shortcode( $post ) {
      $php_shortcode_preview = "do_shortcode( '[easy-copyright id=\"" . $post->ID . "\"]' )";
      if ( $post->post_type === EasyCopyright::$post_type ) {
         if ( 'publish' === $post->post_status ) {
      ?>
         <div class='easy-shortcode'>
            <input readonly onclick="this.select();" value="[easy-copyright id='<?echo esc_attr( $post->ID ); ?>']">   
            <input class="easy-shortcode__showblock" readonly onclick="this.select();" value="<?php echo esc_attr( $php_shortcode_preview ) ?>">   
         </div>
      <?php
         }
      }
   }

}

endif;